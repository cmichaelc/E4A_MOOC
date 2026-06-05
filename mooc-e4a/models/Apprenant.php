<?php
/**
 * Modèle Apprenant — Progression, devoirs, certificats
 */
require_once __DIR__ . '/../config/database.php';

class Apprenant {
    private PDO $pdo;

    public function __construct() { $this->pdo = getPDO(); }

    public function getById(int $id): array|false {
        $stmt = $this->pdo->prepare(
            "SELECT a.*, o.libelle AS opt_libelle, f.libelle AS fil_libelle
             FROM apprenant a
             JOIN `option` o ON a.codopt = o.codopt
             JOIN filiere f  ON o.codefil = f.codefil
             WHERE a.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getMesCours(int $id): array {
        $stmt = $this->pdo->prepare(
            "SELECT c.*, e.progression, e.termine, e.date_enroll,
                    f.nom AS form_nom, f.prenom AS form_prenom,
                    cat.libelle AS cat_libelle
             FROM enroller e
             JOIN cours c       ON e.idcours = c.id
             JOIN formateur f   ON c.idformateur = f.id
             JOIN categorie cat ON c.codcat = cat.codcat
             WHERE e.idapprenant = ? ORDER BY e.date_enroll DESC"
        );
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }

    public function markModuleComplete(int $idApprenant, int $idModule): void {
        $this->pdo->prepare(
            "INSERT INTO progression_module (idapprenant,idmodule,complete,date_compl)
             VALUES (?,?,1,NOW())
             ON DUPLICATE KEY UPDATE complete=1,date_compl=NOW()"
        )->execute([$idApprenant, $idModule]);
        $this->updateCoursProgression($idApprenant, $idModule);
    }

    private function updateCoursProgression(int $idApprenant, int $idModule): void {
        $row = $this->pdo->prepare("SELECT idcours FROM module WHERE id=?");
        $row->execute([$idModule]);
        $mod = $row->fetch();
        if (!$mod) return;
        $idCours = $mod['idcours'];

        $total = (int)$this->pdo->prepare("SELECT COUNT(*) FROM module WHERE idcours=?")
                                 ->execute([$idCours]) ? $this->pdo->prepare("SELECT COUNT(*) FROM module WHERE idcours=?")->execute([$idCours]) : 0;

        $stT = $this->pdo->prepare("SELECT COUNT(*) FROM module WHERE idcours=?");
        $stT->execute([$idCours]);
        $total = (int)$stT->fetchColumn();

        $stD = $this->pdo->prepare(
            "SELECT COUNT(*) FROM progression_module pm
             JOIN module m ON pm.idmodule=m.id
             WHERE pm.idapprenant=? AND m.idcours=? AND pm.complete=1"
        );
        $stD->execute([$idApprenant, $idCours]);
        $done = (int)$stD->fetchColumn();

        $pct     = $total > 0 ? (int)round($done / $total * 100) : 0;
        $termine = $pct >= 100 ? 1 : 0;

        $this->pdo->prepare(
            "UPDATE enroller SET progression=?,termine=? WHERE idapprenant=? AND idcours=?"
        )->execute([$pct, $termine, $idApprenant, $idCours]);

        if ($termine) $this->genererCertificat($idApprenant, $idCours);
    }

    public function getProgressionModules(int $idApprenant, int $idCours): array {
        $stmt = $this->pdo->prepare(
            "SELECT m.id, m.titre, m.ordre,
                    IFNULL(pm.complete,0) AS complete, pm.date_compl
             FROM module m
             LEFT JOIN progression_module pm
               ON pm.idmodule=m.id AND pm.idapprenant=?
             WHERE m.idcours=? ORDER BY m.ordre"
        );
        $stmt->execute([$idApprenant, $idCours]);
        return $stmt->fetchAll();
    }

    public function getMesDevoirs(int $idApprenant): array {
        $stmt = $this->pdo->prepare(
            "SELECT d.*, m.libelle AS mat_libelle,
                    sd.id AS soumission_id, sd.note, sd.date_soumis,
                    sd.fichier AS fichier_soumis, sd.feedback,
                    f.nom AS form_nom, f.prenom AS form_prenom
             FROM devoir d
             JOIN matiere m   ON d.codmat = m.codmat
             JOIN formateur f ON d.idformateur = f.id
             JOIN apprenant a ON a.codopt = d.codopt
             LEFT JOIN soumission_devoir sd
               ON sd.iddevoir=d.id AND sd.idapprenant=?
             WHERE a.id=? ORDER BY d.datefin ASC"
        );
        $stmt->execute([$idApprenant, $idApprenant]);
        return $stmt->fetchAll();
    }

    public function soumettreFichier(int $idApprenant, int $idDevoir, string $fichier): bool {
        $stmt = $this->pdo->prepare(
            "SELECT id FROM soumission_devoir WHERE idapprenant=? AND iddevoir=?"
        );
        $stmt->execute([$idApprenant, $idDevoir]);
        if ($stmt->fetch()) return false;

        $this->pdo->prepare(
            "INSERT INTO soumission_devoir (idapprenant,iddevoir,fichier,date_soumis)
             VALUES (?,?,?,NOW())"
        )->execute([$idApprenant, $idDevoir, $fichier]);
        return true;
    }

    private function genererCertificat(int $idApprenant, int $idCours): void {
        $stmt = $this->pdo->prepare(
            "SELECT id FROM certificat WHERE idapprenant=? AND idcours=?"
        );
        $stmt->execute([$idApprenant, $idCours]);
        if ($stmt->fetch()) return;
        $code = bin2hex(random_bytes(16));
        $this->pdo->prepare(
            "INSERT INTO certificat (idapprenant,idcours,date_emis,code_verif)
             VALUES (?,?,CURDATE(),?)"
        )->execute([$idApprenant, $idCours, $code]);
    }

    public function getMesCertificats(int $id): array {
        $stmt = $this->pdo->prepare(
            "SELECT cert.*, c.titre AS cours_titre,
                    f.nom AS form_nom, f.prenom AS form_prenom,
                    a.nom AS app_nom, a.prenom AS app_prenom
             FROM certificat cert
             JOIN cours c     ON cert.idcours = c.id
             JOIN formateur f ON c.idformateur = f.id
             JOIN apprenant a ON cert.idapprenant = a.id
             WHERE cert.idapprenant=? ORDER BY cert.date_emis DESC"
        );
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }

    public function getCertificatById(int $id, int $idApprenant): array|false {
        $stmt = $this->pdo->prepare(
            "SELECT cert.*, c.titre AS cours_titre,
                    f.nom AS form_nom, f.prenom AS form_prenom,
                    a.nom AS app_nom, a.prenom AS app_prenom,
                    o.libelle AS opt_libelle
             FROM certificat cert
             JOIN cours c     ON cert.idcours = c.id
             JOIN formateur f ON c.idformateur = f.id
             JOIN apprenant a ON cert.idapprenant = a.id
             JOIN `option` o  ON a.codopt = o.codopt
             WHERE cert.id=? AND cert.idapprenant=?"
        );
        $stmt->execute([$id, $idApprenant]);
        return $stmt->fetch();
    }

    public function getDashboardStats(int $id): array {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) AS total_cours, SUM(termine) AS cours_termines,
                    ROUND(AVG(progression)) AS moy_progression
             FROM enroller WHERE idapprenant=?"
        );
        $stmt->execute([$id]);
        $stats = $stmt->fetch();

        $stmt2 = $this->pdo->prepare("SELECT COUNT(*) FROM certificat WHERE idapprenant=?");
        $stmt2->execute([$id]);
        $stats['nb_certificats'] = (int)$stmt2->fetchColumn();
        return $stats;
    }
}
