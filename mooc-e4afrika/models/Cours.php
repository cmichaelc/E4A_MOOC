<?php
/**
 * Modèle Cours — CRUD cours, modules, contenus
 */

require_once __DIR__ . '/../config/database.php';

class Cours {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getPDO();
    }

    /* ---- Catalogue public ---- */

    public function getAllPublished(string $search = '', string $cat = ''): array {
        $sql = "SELECT c.*, f.nom AS form_nom, f.prenom AS form_prenom,
                       cat.libelle AS cat_libelle, m.libelle AS mat_libelle,
                       (SELECT COUNT(*) FROM enroller e WHERE e.idcours = c.id) AS nb_apprenants
                FROM cours c
                JOIN formateur f   ON c.idformateur = f.id
                JOIN categorie cat ON c.codcat = cat.codcat
                JOIN matiere m     ON c.codmat = m.codmat
                WHERE c.statut = 'PUBLIE'";
        $params = [];

        if ($search !== '') {
            $sql .= " AND (c.titre LIKE ? OR c.description LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        if ($cat !== '') {
            $sql .= " AND c.codcat = ?";
            $params[] = $cat;
        }

        $sql .= " ORDER BY c.datecreation DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false {
        $stmt = $this->pdo->prepare(
            "SELECT c.*, f.nom AS form_nom, f.prenom AS form_prenom,
                    cat.libelle AS cat_libelle, m.libelle AS mat_libelle
             FROM cours c
             JOIN formateur f   ON c.idformateur = f.id
             JOIN categorie cat ON c.codcat = cat.codcat
             JOIN matiere m     ON c.codmat = m.codmat
             WHERE c.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /* ---- Modules ---- */

    public function getModulesByCours(int $idCours): array {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM module WHERE idcours = ? ORDER BY ordre"
        );
        $stmt->execute([$idCours]);
        return $stmt->fetchAll();
    }

    public function getModuleById(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM module WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function addModule(string $titre, int $ordre, int $idCours): int {
        $stmt = $this->pdo->prepare(
            "INSERT INTO module (titre, ordre, idcours) VALUES (?, ?, ?)"
        );
        $stmt->execute([$titre, $ordre, $idCours]);
        return (int)$this->pdo->lastInsertId();
    }

    public function updateModule(int $id, string $titre, int $ordre): void {
        $this->pdo->prepare("UPDATE module SET titre=?, ordre=? WHERE id=?")
                  ->execute([$titre, $ordre, $id]);
    }

    public function deleteModule(int $id): void {
        $this->pdo->prepare("DELETE FROM module WHERE id=?")->execute([$id]);
    }

    /* ---- Contenus ---- */

    public function getContenusByModule(int $idModule): array {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM contenu WHERE idmodule = ? ORDER BY id"
        );
        $stmt->execute([$idModule]);
        return $stmt->fetchAll();
    }

    public function addContenu(array $data): void {
        $stmt = $this->pdo->prepare(
            "INSERT INTO contenu (titre, urlcontenu, type, duree, idmodule)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['titre'], $data['urlcontenu'],
            $data['type'],  $data['duree'], $data['idmodule']
        ]);
    }

    public function deleteContenu(int $id): void {
        $this->pdo->prepare("DELETE FROM contenu WHERE id=?")->execute([$id]);
    }

    /* ---- CRUD Cours (formateur) ---- */

    public function create(array $data): int {
        $stmt = $this->pdo->prepare(
            "INSERT INTO cours (titre, datecreation, description, montant, niveau,
                                duree_totale, codcat, idformateur, codmat, statut)
             VALUES (?, CURDATE(), ?, ?, ?, ?, ?, ?, ?, 'BROUILLON')"
        );
        $stmt->execute([
            $data['titre'], $data['description'], $data['montant'],
            $data['niveau'], $data['duree_totale'],
            $data['codcat'], $data['idformateur'], $data['codmat']
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void {
        $stmt = $this->pdo->prepare(
            "UPDATE cours SET titre=?, description=?, montant=?, niveau=?,
                              duree_totale=?, codcat=?, codmat=?, statut=?
             WHERE id=?"
        );
        $stmt->execute([
            $data['titre'], $data['description'], $data['montant'],
            $data['niveau'], $data['duree_totale'], $data['codcat'],
            $data['codmat'], $data['statut'], $id
        ]);
    }

    public function delete(int $id): void {
        $this->pdo->prepare("DELETE FROM cours WHERE id=?")->execute([$id]);
    }

    public function getByFormateur(int $idFormateur): array {
        $stmt = $this->pdo->prepare(
            "SELECT c.*, cat.libelle AS cat_libelle,
                    (SELECT COUNT(*) FROM enroller e WHERE e.idcours = c.id) AS nb_apprenants
             FROM cours c
             JOIN categorie cat ON c.codcat = cat.codcat
             WHERE c.idformateur = ?
             ORDER BY c.datecreation DESC"
        );
        $stmt->execute([$idFormateur]);
        return $stmt->fetchAll();
    }

    /* ---- Enrollment ---- */

    public function isEnrolled(int $idApprenant, int $idCours): bool {
        $stmt = $this->pdo->prepare(
            "SELECT 1 FROM enroller WHERE idapprenant=? AND idcours=?"
        );
        $stmt->execute([$idApprenant, $idCours]);
        return (bool)$stmt->fetch();
    }

    public function enroll(int $idApprenant, int $idCours): void {
        $stmt = $this->pdo->prepare(
            "INSERT IGNORE INTO enroller (idapprenant, idcours, date_enroll, progression)
             VALUES (?, ?, CURDATE(), 0)"
        );
        $stmt->execute([$idApprenant, $idCours]);
    }

    /* ---- Catégories & Matières (pour formulaires) ---- */

    public function getCategories(): array {
        return $this->pdo->query("SELECT * FROM categorie ORDER BY libelle")->fetchAll();
    }

    public function getMatieres(): array {
        return $this->pdo->query("SELECT * FROM matiere ORDER BY libelle")->fetchAll();
    }

    /* ---- Quiz ---- */

    public function getQuizByModule(int $idModule): array {
        $stmt = $this->pdo->prepare("SELECT * FROM quiz WHERE idmodule=? ORDER BY id");
        $stmt->execute([$idModule]);
        return $stmt->fetchAll();
    }

    public function addQuiz(array $data): void {
        $stmt = $this->pdo->prepare(
            "INSERT INTO quiz (question, choix_a, choix_b, choix_c, choix_d, reponse, idmodule)
             VALUES (?,?,?,?,?,?,?)"
        );
        $stmt->execute([
            $data['question'], $data['choix_a'], $data['choix_b'],
            $data['choix_c'] ?? null, $data['choix_d'] ?? null,
            $data['reponse'], $data['idmodule']
        ]);
    }

    public function saveReponseQuiz(int $idApprenant, int $idQuiz, string $reponse, string $correcte): void {
        $correct = ($reponse === $correcte) ? 1 : 0;
        $stmt = $this->pdo->prepare(
            "INSERT INTO reponse_quiz (idapprenant, idquiz, reponse, correct, date_rep)
             VALUES (?,?,?,?,NOW())
             ON DUPLICATE KEY UPDATE reponse=?, correct=?, date_rep=NOW()"
        );
        $stmt->execute([$idApprenant, $idQuiz, $reponse, $correct, $reponse, $correct]);
    }
}
