<?php
/**
 * Modèle Formateur
 */
require_once __DIR__ . '/../config/database.php';

class Formateur {
    private PDO $pdo;
    public function __construct() { $this->pdo = getPDO(); }

    public function getById(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM formateur WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAll(): array {
        return $this->pdo->query("SELECT * FROM formateur ORDER BY nom")->fetchAll();
    }

    public function getDashboardStats(int $id): array {
        $stCours = $this->pdo->prepare("SELECT COUNT(*) FROM cours WHERE idformateur=?");
        $stCours->execute([$id]);
        $nbCours = (int)$stCours->fetchColumn();

        $stApp = $this->pdo->prepare(
            "SELECT COUNT(DISTINCT e.idapprenant) FROM enroller e
             JOIN cours c ON e.idcours=c.id WHERE c.idformateur=?"
        );
        $stApp->execute([$id]);
        $nbApprenants = (int)$stApp->fetchColumn();

        $stProg = $this->pdo->prepare(
            "SELECT ROUND(AVG(e.progression)) FROM enroller e
             JOIN cours c ON e.idcours=c.id WHERE c.idformateur=?"
        );
        $stProg->execute([$id]);
        $avgProg = (int)$stProg->fetchColumn();

        $stDev = $this->pdo->prepare("SELECT COUNT(*) FROM devoir WHERE idformateur=?");
        $stDev->execute([$id]);
        $nbDevoirs = (int)$stDev->fetchColumn();

        return compact('nbCours', 'nbApprenants', 'avgProg', 'nbDevoirs');
    }

    public function getSoumissions(int $idFormateur): array {
        $stmt = $this->pdo->prepare(
            "SELECT sd.*, d.titre AS devoir_titre, d.datefin,
                    a.nom AS app_nom, a.prenom AS app_prenom,
                    m.libelle AS mat_libelle
             FROM soumission_devoir sd
             JOIN devoir d    ON sd.iddevoir = d.id
             JOIN apprenant a ON sd.idapprenant = a.id
             JOIN matiere m   ON d.codmat = m.codmat
             WHERE d.idformateur=? ORDER BY sd.date_soumis DESC"
        );
        $stmt->execute([$idFormateur]);
        return $stmt->fetchAll();
    }

    public function noterSoumission(int $idSoumission, float $note, string $feedback): void {
        $this->pdo->prepare(
            "UPDATE soumission_devoir SET note=?, feedback=? WHERE id=?"
        )->execute([$note, $feedback, $idSoumission]);
    }

    public function getDevoirs(int $idFormateur): array {
        $stmt = $this->pdo->prepare(
            "SELECT d.*, m.libelle AS mat_libelle, o.libelle AS opt_libelle,
                    (SELECT COUNT(*) FROM soumission_devoir sd WHERE sd.iddevoir=d.id) AS nb_soumissions
             FROM devoir d
             JOIN matiere m  ON d.codmat=m.codmat
             JOIN `option` o ON d.codopt=o.codopt
             WHERE d.idformateur=? ORDER BY d.datefin DESC"
        );
        $stmt->execute([$idFormateur]);
        return $stmt->fetchAll();
    }

    public function createDevoir(array $data): void {
        $this->pdo->prepare(
            "INSERT INTO devoir (titre,datedebut,datefin,consigne,codmat,codopt,idformateur)
             VALUES (?,?,?,?,?,?,?)"
        )->execute([
            $data['titre'], $data['datedebut'], $data['datefin'],
            $data['consigne'], $data['codmat'], $data['codopt'], $data['idformateur']
        ]);
    }

    public function deleteDevoir(int $id, int $idFormateur): void {
        $this->pdo->prepare(
            "DELETE FROM devoir WHERE id=? AND idformateur=?"
        )->execute([$id, $idFormateur]);
    }
}
