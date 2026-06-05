<?php
/**
 * CoursController — Catalogue, détail, enrollment
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/csrf.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../models/Cours.php';

$action = $_GET['action'] ?? 'catalogue';
$cours  = new Cours();

/* ---- Catalogue public ---- */
if ($action === 'catalogue') {
    $search     = trim($_GET['q']   ?? '');
    $catFilter  = trim($_GET['cat'] ?? '');
    $listeCours = $cours->getAllPublished($search, $catFilter);
    $categories = $cours->getCategories();
    include __DIR__ . '/../views/public/catalogue.php';
}

/* ---- Détail d'un cours ---- */
elseif ($action === 'detail') {
    $id     = (int)($_GET['id'] ?? 0);
    $detail = $cours->getById($id);
    if (!$detail) { flashMessage('danger','Cours introuvable.'); redirect(BASE_URL.'/controllers/CoursController.php'); }

    $modules  = $cours->getModulesByCours($id);
    $contenus = [];
    foreach ($modules as $m) {
        $contenus[$m['id']] = $cours->getContenusByModule($m['id']);
    }
    $enrolled = isLoggedIn() && isApprenant()
                ? $cours->isEnrolled(currentUserId(), $id) : false;
    include __DIR__ . '/../views/public/cours_detail.php';
}

/* ---- Enrollment ---- */
elseif ($action === 'enroll') {
    requireApprenant();
    $idCours = (int)($_POST['idcours'] ?? 0);
    if ($idCours > 0) {
        $cours->enroll(currentUserId(), $idCours);
        logAction("Enrollment cours #$idCours");
        flashMessage('success', 'Vous êtes inscrit à ce cours !');
    }
    redirect(BASE_URL . '/controllers/CoursController.php?action=detail&id=' . $idCours);
}

/* ---- Marquer module complet ---- */
elseif ($action === 'complete_module') {
    requireApprenant();
    require_once __DIR__ . '/../models/Apprenant.php';
    $idModule = (int)($_POST['idmodule'] ?? 0);
    $idCours  = (int)($_POST['idcours']  ?? 0);
    if ($idModule > 0) {
        $appM = new Apprenant();
        $appM->markModuleComplete(currentUserId(), $idModule);
        logAction("Module #$idModule complété");
        flashMessage('success', 'Module marqué comme complété !');
    }
    redirect(BASE_URL . '/views/apprenant/progression.php?idcours=' . $idCours);
}

/* ---- Quiz ---- */
elseif ($action === 'quiz') {
    requireApprenant();
    $idModule = (int)($_GET['idmodule'] ?? 0);
    $module   = $cours->getModuleById($idModule);
    if (!$module) { redirect(BASE_URL.'/controllers/CoursController.php'); }
    $questions= $cours->getQuizByModule($idModule);
    include __DIR__ . '/../views/apprenant/quiz.php';
}

elseif ($action === 'submit_quiz') {
    requireApprenant();
    verifyCsrf();
    $idModule = (int)($_POST['idmodule'] ?? 0);
    $questions= $cours->getQuizByModule($idModule);
    $score    = 0;
    $results  = [];
    foreach ($questions as $q) {
        $rep     = $_POST['q_' . $q['id']] ?? '';
        $correct = ($rep === $q['reponse']);
        if ($correct) $score++;
        $cours->saveReponseQuiz(currentUserId(), $q['id'], $rep, $q['reponse']);
        $results[$q['id']] = ['rep' => $rep, 'correct' => $correct, 'bonne' => $q['reponse']];
    }
    $total   = count($questions);
    $pct     = $total > 0 ? round($score / $total * 100) : 0;
    $module  = $cours->getModuleById($idModule);
    include __DIR__ . '/../views/apprenant/quiz.php';
}
