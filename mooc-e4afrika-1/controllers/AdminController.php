<?php
/**
 * AdminController — Gestion globale
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/csrf.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/Cours.php';

requireAdmin();

$action = $_GET['action'] ?? 'dashboard';
$adminM = new Admin();
$coursM = new Cours();

if ($action === 'dashboard') {
    $kpis    = $adminM->getKPIs();
    $cours   = $adminM->getAllCours();
    $apprenants = $adminM->getAllApprenants();
    include __DIR__ . '/../views/admin/dashboard.php';
}
elseif ($action === 'apprenants') {
    $apprenants = $adminM->getAllApprenants();
    include __DIR__ . '/../views/admin/utilisateurs.php';
}
elseif ($action === 'update_apprenant') {
    verifyCsrf();
    $adminM->updateStatutApprenant((int)$_POST['id'], $_POST['statut']);
    flashMessage('success','Statut mis à jour.');
    redirect(BASE_URL.'/controllers/AdminController.php?action=apprenants');
}
elseif ($action === 'delete_apprenant') {
    verifyCsrf();
    $adminM->deleteApprenant((int)$_POST['id']);
    logAction('Suppression apprenant #'.$_POST['id']);
    flashMessage('success','Apprenant supprimé.');
    redirect(BASE_URL.'/controllers/AdminController.php?action=apprenants');
}
elseif ($action === 'formateurs') {
    $formateurs = $adminM->getAllFormateurs();
    include __DIR__ . '/../views/admin/formateurs.php';
}
elseif ($action === 'valider_formateur') {
    verifyCsrf();
    $adminM->validerFormateur((int)$_POST['id']);
    logAction('Validation formateur #'.$_POST['id']);
    flashMessage('success','Formateur validé.');
    redirect(BASE_URL.'/controllers/AdminController.php?action=formateurs');
}
elseif ($action === 'delete_formateur') {
    verifyCsrf();
    $adminM->deleteFormateur((int)$_POST['id']);
    flashMessage('success','Formateur supprimé.');
    redirect(BASE_URL.'/controllers/AdminController.php?action=formateurs');
}
elseif ($action === 'cours') {
    $cours = $adminM->getAllCours();
    include __DIR__ . '/../views/admin/cours.php';
}
elseif ($action === 'update_cours') {
    verifyCsrf();
    $adminM->updateStatutCours((int)$_POST['id'], $_POST['statut']);
    flashMessage('success','Statut cours mis à jour.');
    redirect(BASE_URL.'/controllers/AdminController.php?action=cours');
}
elseif ($action === 'filieres') {
    $filieres   = $adminM->getFilieres();
    $options    = $adminM->getOptions();
    $categories = $adminM->getCategories();
    $matieres   = $adminM->getMatieres();
    include __DIR__ . '/../views/admin/filieres.php';
}
elseif ($action === 'add_filiere') {
    verifyCsrf();
    $adminM->addFiliere(strtoupper(trim($_POST['codefil'])), trim($_POST['libelle']));
    flashMessage('success','Filière ajoutée.');
    redirect(BASE_URL.'/controllers/AdminController.php?action=filieres');
}
elseif ($action === 'delete_filiere') {
    verifyCsrf();
    $adminM->deleteFiliere($_POST['codefil']);
    flashMessage('success','Filière supprimée.');
    redirect(BASE_URL.'/controllers/AdminController.php?action=filieres');
}
elseif ($action === 'add_categorie') {
    verifyCsrf();
    $adminM->addCategorie(strtoupper(trim($_POST['codcat'])), trim($_POST['libelle']));
    flashMessage('success','Catégorie ajoutée.');
    redirect(BASE_URL.'/controllers/AdminController.php?action=filieres');
}
elseif ($action === 'add_matiere') {
    verifyCsrf();
    $adminM->addMatiere(strtoupper(trim($_POST['codmat'])), trim($_POST['libelle']));
    flashMessage('success','Matière ajoutée.');
    redirect(BASE_URL.'/controllers/AdminController.php?action=filieres');
}
elseif ($action === 'rapports') {
    $kpis       = $adminM->getKPIs();
    $apprenants = $adminM->getAllApprenants();
    $cours      = $adminM->getAllCours();
    include __DIR__ . '/../views/admin/rapports.php';
}
elseif ($action === 'export_csv') {
    $csv = $adminM->exportApprenantsCsv();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="apprenants_' . date('Y-m-d') . '.csv"');
    echo "\xEF\xBB\xBF" . $csv; // BOM UTF-8
    exit;
}
