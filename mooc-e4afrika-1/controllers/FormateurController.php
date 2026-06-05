<?php
/**
 * FormateurController — Dashboard, cours, devoirs, notation
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/csrf.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../models/Formateur.php';
require_once __DIR__ . '/../models/Cours.php';

requireFormateur();

$action   = $_GET['action'] ?? 'dashboard';
$formatM  = new Formateur();
$coursM   = new Cours();
$idForm   = currentUserId();

if ($action === 'dashboard') {
    $stats    = $formatM->getDashboardStats($idForm);
    $mesCours = $coursM->getByFormateur($idForm);
    include __DIR__ . '/../views/formateur/dashboard.php';
}

elseif ($action === 'mes_cours') {
    $mesCours   = $coursM->getByFormateur($idForm);
    $categories = $coursM->getCategories();
    $matieres   = $coursM->getMatieres();
    include __DIR__ . '/../views/formateur/mes_cours.php';
}

elseif ($action === 'create_cours') {
    $categories = $coursM->getCategories();
    $matieres   = $coursM->getMatieres();
    $editCours  = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verifyCsrf();
        $data = [
            'titre'       => trim($_POST['titre'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'montant'     => (int)($_POST['montant'] ?? 0),
            'niveau'      => $_POST['niveau'] ?? 'DEBUTANT',
            'duree_totale'=> (int)($_POST['duree_totale'] ?? 0),
            'codcat'      => $_POST['codcat'] ?? '',
            'codmat'      => $_POST['codmat'] ?? '',
            'idformateur' => $idForm,
        ];
        $newId = $coursM->create($data);
        logAction("Création cours #$newId");
        flashMessage('success', 'Cours créé ! Ajoutez maintenant vos modules.');
        redirect(BASE_URL . '/controllers/FormateurController.php?action=modules&idcours=' . $newId);
    }
    include __DIR__ . '/../views/formateur/cours_form.php';
}

elseif ($action === 'edit_cours') {
    $idCours    = (int)($_GET['idcours'] ?? 0);
    $editCours  = $coursM->getById($idCours);
    $categories = $coursM->getCategories();
    $matieres   = $coursM->getMatieres();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verifyCsrf();
        $data = [
            'titre'       => trim($_POST['titre'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'montant'     => (int)($_POST['montant'] ?? 0),
            'niveau'      => $_POST['niveau'] ?? 'DEBUTANT',
            'duree_totale'=> (int)($_POST['duree_totale'] ?? 0),
            'codcat'      => $_POST['codcat'] ?? '',
            'codmat'      => $_POST['codmat'] ?? '',
            'statut'      => $_POST['statut'] ?? 'BROUILLON',
        ];
        $coursM->update($idCours, $data);
        flashMessage('success', 'Cours mis à jour.');
        redirect(BASE_URL . '/controllers/FormateurController.php?action=mes_cours');
    }
    include __DIR__ . '/../views/formateur/cours_form.php';
}

elseif ($action === 'delete_cours') {
    verifyCsrf();
    $idCours = (int)($_POST['idcours'] ?? 0);
    $coursM->delete($idCours);
    logAction("Suppression cours #$idCours");
    flashMessage('success', 'Cours supprimé.');
    redirect(BASE_URL . '/controllers/FormateurController.php?action=mes_cours');
}

elseif ($action === 'modules') {
    $idCours = (int)($_GET['idcours'] ?? 0);
    $detail  = $coursM->getById($idCours);
    $modules = $coursM->getModulesByCours($idCours);
    $contenus= [];
    foreach ($modules as $m) {
        $contenus[$m['id']] = $coursM->getContenusByModule($m['id']);
    }
    include __DIR__ . '/../views/formateur/modules.php';
}

elseif ($action === 'add_module') {
    verifyCsrf();
    $idCours = (int)($_POST['idcours'] ?? 0);
    $titre   = trim($_POST['titre'] ?? '');
    $ordre   = (int)($_POST['ordre'] ?? 1);
    $coursM->addModule($titre, $ordre, $idCours);
    flashMessage('success', 'Module ajouté.');
    redirect(BASE_URL . '/controllers/FormateurController.php?action=modules&idcours=' . $idCours);
}

elseif ($action === 'add_contenu') {
    verifyCsrf();
    $idModule= (int)($_POST['idmodule'] ?? 0);
    $idCours = (int)($_POST['idcours']  ?? 0);
    $coursM->addContenu([
        'titre'      => trim($_POST['titre'] ?? ''),
        'urlcontenu' => trim($_POST['urlcontenu'] ?? ''),
        'type'       => $_POST['type'] ?? 'VIDEO',
        'duree'      => (int)($_POST['duree'] ?? 0),
        'idmodule'   => $idModule,
    ]);
    flashMessage('success', 'Contenu ajouté.');
    redirect(BASE_URL . '/controllers/FormateurController.php?action=modules&idcours=' . $idCours);
}

elseif ($action === 'devoirs') {
    $devoirs  = $formatM->getDevoirs($idForm);
    $matieres = $coursM->getMatieres();
    $pdo      = getPDO();
    $options  = $pdo->query("SELECT * FROM `option` ORDER BY libelle")->fetchAll();
    include __DIR__ . '/../views/formateur/devoirs.php';
}

elseif ($action === 'create_devoir') {
    verifyCsrf();
    $formatM->createDevoir([
        'titre'       => trim($_POST['titre'] ?? 'Devoir'),
        'datedebut'   => $_POST['datedebut'] ?? date('Y-m-d'),
        'datefin'     => $_POST['datefin']   ?? date('Y-m-d'),
        'consigne'    => trim($_POST['consigne'] ?? ''),
        'codmat'      => $_POST['codmat'] ?? '',
        'codopt'      => $_POST['codopt'] ?? '',
        'idformateur' => $idForm,
    ]);
    flashMessage('success', 'Devoir créé.');
    redirect(BASE_URL . '/controllers/FormateurController.php?action=devoirs');
}

elseif ($action === 'notation') {
    $soumissions = $formatM->getSoumissions($idForm);
    include __DIR__ . '/../views/formateur/notation.php';
}

elseif ($action === 'noter') {
    verifyCsrf();
    $idSoumission = (int)($_POST['idsoumission'] ?? 0);
    $note         = (float)($_POST['note'] ?? 0);
    $feedback     = trim($_POST['feedback'] ?? '');
    $formatM->noterSoumission($idSoumission, $note, $feedback);
    logAction("Notation soumission #$idSoumission");
    flashMessage('success', 'Note enregistrée.');
    redirect(BASE_URL . '/controllers/FormateurController.php?action=notation');
}
