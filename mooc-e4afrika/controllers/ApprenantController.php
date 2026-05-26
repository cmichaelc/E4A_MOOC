<?php
/**
 * ApprenantController — Dashboard, devoirs, certificats
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/csrf.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/upload.php';
require_once __DIR__ . '/../models/Apprenant.php';
require_once __DIR__ . '/../models/Cours.php';
require_once __DIR__ . '/../models/Certificat.php';

requireApprenant();

$action    = $_GET['action'] ?? 'dashboard';
$appM      = new Apprenant();
$coursM    = new Cours();
$idApprenant = currentUserId();

if ($action === 'dashboard') {
    $stats    = $appM->getDashboardStats($idApprenant);
    $mesCours = $appM->getMesCours($idApprenant);
    include __DIR__ . '/../views/apprenant/dashboard.php';
}

elseif ($action === 'mes_cours') {
    $mesCours = $appM->getMesCours($idApprenant);
    include __DIR__ . '/../views/apprenant/mes_cours.php';
}

elseif ($action === 'progression') {
    $idCours  = (int)($_GET['idcours'] ?? 0);
    $detail   = $coursM->getById($idCours);
    if (!$detail || !$coursM->isEnrolled($idApprenant, $idCours)) {
        flashMessage('danger', 'Accès refusé.');
        redirect(BASE_URL . '/controllers/ApprenantController.php?action=mes_cours');
    }
    $modules  = $appM->getProgressionModules($idApprenant, $idCours);
    $contenus = [];
    foreach ($modules as $m) {
        $contenus[$m['id']] = $coursM->getContenusByModule($m['id']);
    }
    $enrolled = $coursM->isEnrolled($idApprenant, $idCours);
    include __DIR__ . '/../views/apprenant/progression.php';
}

elseif ($action === 'devoirs') {
    $devoirs = $appM->getMesDevoirs($idApprenant);
    include __DIR__ . '/../views/apprenant/devoirs.php';
}

elseif ($action === 'soumettre') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect(BASE_URL . '/controllers/ApprenantController.php?action=devoirs');
    }
    verifyCsrf();
    $idDevoir = (int)($_POST['iddevoir'] ?? 0);

    if (!isset($_FILES['fichier']) || $_FILES['fichier']['error'] === UPLOAD_ERR_NO_FILE) {
        flashMessage('danger', 'Veuillez sélectionner un fichier.');
        redirect(BASE_URL . '/controllers/ApprenantController.php?action=devoirs');
    }

    $uploader = new UploadHandler();
    $filename = $uploader->handleDevoir($_FILES['fichier']);

    if ($filename === false) {
        flashMessage('danger', implode('<br>', $uploader->getErrors()));
    } else {
        $ok = $appM->soumettreFichier($idApprenant, $idDevoir, $filename);
        if ($ok) {
            logAction("Soumission devoir #$idDevoir");
            flashMessage('success', 'Devoir soumis avec succès !');
        } else {
            flashMessage('warning', 'Vous avez déjà soumis ce devoir.');
        }
    }
    redirect(BASE_URL . '/controllers/ApprenantController.php?action=devoirs');
}

elseif ($action === 'certificats') {
    $certificats = $appM->getMesCertificats($idApprenant);
    include __DIR__ . '/../views/apprenant/certificat.php';
}

elseif ($action === 'voir_certificat') {
    $idCert  = (int)($_GET['id'] ?? 0);
    $cert    = $appM->getCertificatById($idCert, $idApprenant);
    if (!$cert) { flashMessage('danger','Certificat introuvable.'); redirect(BASE_URL.'/controllers/ApprenantController.php?action=certificats'); }
    $certM   = new Certificat();
    echo $certM->renderHtml($cert);
    exit;
}
