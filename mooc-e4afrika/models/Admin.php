<?php
/**
 * Modèle Admin — Gestion globale
 */
require_once __DIR__ . '/../config/database.php';

class Admin {
    private PDO $pdo;
    public function __construct() { $this->pdo = getPDO(); }

    /* ---- KPIs globaux ---- */
    public function getKPIs(): array {
        $nbApp  = (int)$this->pdo->query("SELECT COUNT(*) FROM apprenant")->fetchColumn();
        $nbForm = (int)$this->pdo->query("SELECT COUNT(*) FROM formateur WHERE valide=1")->fetchColumn();
        $nbCours= (int)$this->pdo->query("SELECT COUNT(*) FROM cours WHERE statut='PUBLIE'")->fetchColumn();
        $nbCert = (int)$this->pdo->query("SELECT COUNT(*) FROM certificat")->fetchColumn();
        $avgProg= (int)$this->pdo->query("SELECT ROUND(AVG(progression)) FROM enroller")->fetchColumn();
        $nbEnroll=(int)$this->pdo->query("SELECT COUNT(*) FROM enroller")->fetchColumn();
        return compact('nbApp','nbForm','nbCours','nbCert','avgProg','nbEnroll');
    }

    /* ---- Apprenants ---- */
    public function getAllApprenants(): array {
        return $this->pdo->query(
            "SELECT a.*, o.libelle AS opt_libelle
             FROM apprenant a JOIN `option` o ON a.codopt=o.codopt
             ORDER BY a.nom"
        )->fetchAll();
    }

    public function updateStatutApprenant(int $id, string $statut): void {
        $this->pdo->prepare("UPDATE apprenant SET statut=? WHERE id=?")
                  ->execute([$statut, $id]);
    }

    public function deleteApprenant(int $id): void {
        $this->pdo->prepare("DELETE FROM apprenant WHERE id=?")->execute([$id]);
    }

    /* ---- Formateurs ---- */
    public function getAllFormateurs(): array {
        return $this->pdo->query("SELECT * FROM formateur ORDER BY nom")->fetchAll();
    }

    public function validerFormateur(int $id): void {
        $this->pdo->prepare("UPDATE formateur SET valide=1 WHERE id=?")->execute([$id]);
    }

    public function deleteFormateur(int $id): void {
        $this->pdo->prepare("DELETE FROM formateur WHERE id=?")->execute([$id]);
    }

    /* ---- Cours ---- */
    public function getAllCours(): array {
        return $this->pdo->query(
            "SELECT c.*, f.nom AS form_nom, f.prenom AS form_prenom,
                    cat.libelle AS cat_libelle,
                    (SELECT COUNT(*) FROM enroller e WHERE e.idcours=c.id) AS nb_apprenants
             FROM cours c
             JOIN formateur f ON c.idformateur=f.id
             JOIN categorie cat ON c.codcat=cat.codcat
             ORDER BY c.datecreation DESC"
        )->fetchAll();
    }

    public function updateStatutCours(int $id, string $statut): void {
        $this->pdo->prepare("UPDATE cours SET statut=? WHERE id=?")->execute([$statut, $id]);
    }

    /* ---- Filières ---- */
    public function getFilieres(): array {
        return $this->pdo->query("SELECT * FROM filiere ORDER BY libelle")->fetchAll();
    }
    public function addFiliere(string $code, string $libelle): void {
        $this->pdo->prepare("INSERT INTO filiere (codefil,libelle) VALUES (?,?)")
                  ->execute([$code, $libelle]);
    }
    public function deleteFiliere(string $code): void {
        $this->pdo->prepare("DELETE FROM filiere WHERE codefil=?")->execute([$code]);
    }

    /* ---- Options ---- */
    public function getOptions(): array {
        return $this->pdo->query(
            "SELECT o.*, f.libelle AS fil_libelle
             FROM `option` o JOIN filiere f ON o.codefil=f.codefil
             ORDER BY o.libelle"
        )->fetchAll();
    }
    public function addOption(string $code, string $libelle, string $codefil): void {
        $this->pdo->prepare("INSERT INTO `option` (codopt,libelle,codefil) VALUES (?,?,?)")
                  ->execute([$code, $libelle, $codefil]);
    }
    public function deleteOption(string $code): void {
        $this->pdo->prepare("DELETE FROM `option` WHERE codopt=?")->execute([$code]);
    }

    /* ---- Catégories ---- */
    public function getCategories(): array {
        return $this->pdo->query("SELECT * FROM categorie ORDER BY libelle")->fetchAll();
    }
    public function addCategorie(string $code, string $libelle): void {
        $this->pdo->prepare("INSERT INTO categorie (codcat,libelle) VALUES (?,?)")
                  ->execute([$code, $libelle]);
    }
    public function deleteCategorie(string $code): void {
        $this->pdo->prepare("DELETE FROM categorie WHERE codcat=?")->execute([$code]);
    }

    /* ---- Matières ---- */
    public function getMatieres(): array {
        return $this->pdo->query("SELECT * FROM matiere ORDER BY libelle")->fetchAll();
    }
    public function addMatiere(string $code, string $libelle): void {
        $this->pdo->prepare("INSERT INTO matiere (codmat,libelle) VALUES (?,?)")
                  ->execute([$code, $libelle]);
    }
    public function deleteMatiere(string $code): void {
        $this->pdo->prepare("DELETE FROM matiere WHERE codmat=?")->execute([$code]);
    }

    /* ---- Rapports CSV ---- */
    public function exportApprenantsCsv(): string {
        $rows = $this->getAllApprenants();
        $csv  = "ID,Nom,Prénom,Email,Statut,Option,Date Inscription\n";
        foreach ($rows as $r) {
            $csv .= implode(',', [
                $r['id'], $r['nom'], $r['prenom'], $r['email'],
                $r['statut'], $r['opt_libelle'], $r['dateinscription']
            ]) . "\n";
        }
        return $csv;
    }
}
