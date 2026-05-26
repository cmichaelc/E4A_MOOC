<?php
/**
 * Modèle User — Authentification & sécurité
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';

class User {

    protected PDO $pdo;

    public function __construct() {
        $this->pdo = getPDO();
    }

    /* ---- Connexion générique (tous rôles) ---- */

    public function login(string $email, string $password): array|false {
        // Chercher dans chaque table
        $roles = [
            'admin'     => 'administrateur',
            'formateur' => 'formateur',
            'apprenant' => 'apprenant',
        ];

        foreach ($roles as $role => $table) {
            $row = $this->findByEmail($table, $email);
            if (!$row) continue;

            // Vérification blocage
            if ($role !== 'admin' && !empty($row['locked_until'])) {
                if (strtotime($row['locked_until']) > time()) {
                    $minutes = ceil((strtotime($row['locked_until']) - time()) / 60);
                    return ['error' => "Compte bloqué. Réessayez dans {$minutes} minute(s)."];
                } else {
                    $this->resetAttempts($table, $row['id']);
                }
            }

            if (password_verify($password, $row['password'])) {
                // Vérification formateur validé
                if ($role === 'formateur' && empty($row['valide'])) {
                    return ['error' => 'Votre compte formateur n\'est pas encore validé par l\'administrateur.'];
                }
                $this->resetAttempts($table, $row['id']);
                return ['user' => $row, 'role' => $role];
            } else {
                if ($role !== 'admin') {
                    $this->incrementAttempts($table, $row['id']);
                }
                return ['error' => 'Email ou mot de passe incorrect.'];
            }
        }

        return ['error' => 'Aucun compte associé à cet email.'];
    }

    private function findByEmail(string $table, string $email): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM `{$table}` WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    private function incrementAttempts(string $table, int $id): void {
        $stmt = $this->pdo->prepare(
            "SELECT login_attempts FROM `{$table}` WHERE id = ?"
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        $attempts = ($row['login_attempts'] ?? 0) + 1;

        if ($attempts >= MAX_LOGIN_ATTEMPTS) {
            $lockedUntil = date('Y-m-d H:i:s', time() + LOCK_DURATION_MINUTES * 60);
            $this->pdo->prepare(
                "UPDATE `{$table}` SET login_attempts = ?, locked_until = ? WHERE id = ?"
            )->execute([$attempts, $lockedUntil, $id]);
        } else {
            $this->pdo->prepare(
                "UPDATE `{$table}` SET login_attempts = ? WHERE id = ?"
            )->execute([$attempts, $id]);
        }
    }

    private function resetAttempts(string $table, int $id): void {
        // $this->pdo->prepare(
        //     "UPDATE `{$table}` SET login_attempts = 0, locked_until = NULL WHERE id = ?"
        // )->execute([$id]);
    }

    /* ---- Inscription apprenant ---- */

    public function registerApprenant(array $data): bool|string {
        // Vérification email unique
        $stmt = $this->pdo->prepare("SELECT id FROM apprenant WHERE email = ?");
        $stmt->execute([$data['email']]);
        if ($stmt->fetch()) return 'Cet email est déjà utilisé.';

        $hash = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare(
            "INSERT INTO apprenant (nom, prenom, email, password, dateinscription, statut, codopt)
             VALUES (?, ?, ?, ?, CURDATE(), 'ACTIF', ?)"
        );
        $stmt->execute([
            strtoupper($data['nom']),
            ucfirst($data['prenom']),
            $data['email'],
            $hash,
            $data['codopt'],
        ]);
        return true;
    }

    /* ---- Mot de passe ---- */

    public function validatePassword(string $password): bool {
        return strlen($password) >= MIN_PASSWORD_LENGTH
            && preg_match('/[A-Za-z]/', $password)
            && preg_match('/[0-9]/', $password);
    }

    public function changePassword(string $table, int $id, string $newPassword): void {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $this->pdo->prepare("UPDATE `{$table}` SET password = ? WHERE id = ?")
                  ->execute([$hash, $id]);
    }

    /* ---- Options (pour le formulaire d'inscription) ---- */

    public function getOptions(): array {
        return $this->pdo->query("SELECT codopt, libelle FROM `option` ORDER BY libelle")->fetchAll();
    }
}
