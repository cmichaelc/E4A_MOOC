<?php
/**
 * AuthController — Connexion, déconnexion, inscription
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../helpers/csrf.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../models/User.php';

$action = $_GET['action'] ?? 'login';
$user   = new User();

/* ============================================================
   LOGIN
   ============================================================ */
if ($action === 'login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verifyCsrf();
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            flashMessage('danger', 'Veuillez remplir tous les champs.');
        } else {
            $result = $user->login($email, $password);
            if (isset($result['error'])) {
                flashMessage('danger', $result['error']);
            } else {
                $u = $result['user'];
                regenerateSession();
                $_SESSION['user_id']   = $u['id'];
                $_SESSION['user_nom']  = $u['prenom'] . ' ' . $u['nom'];
                $_SESSION['user_email']= $u['email'];
                $_SESSION['role']      = $result['role'];

                logAction('Connexion');

                switch ($result['role']) {
                    case 'admin':
                        redirect(BASE_URL . '/views/admin/dashboard.php'); break;
                    case 'formateur':
                        redirect(BASE_URL . '/views/formateur/dashboard.php'); break;
                    default:
                        redirect(BASE_URL . '/views/apprenant/dashboard.php');
                }
            }
        }
    }
    include __DIR__ . '/../views/auth/login.php';
}

/* ============================================================
   LOGOUT
   ============================================================ */
elseif ($action === 'logout') {
    logAction('Déconnexion');
    destroySession();
    redirect(BASE_URL . '/index.php');
}

/* ============================================================
   REGISTER
   ============================================================ */
elseif ($action === 'register') {
    $options = $user->getOptions();
    $errors  = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verifyCsrf();
        $nom      = trim($_POST['nom']      ?? '');
        $prenom   = trim($_POST['prenom']   ?? '');
        $email    = trim($_POST['email']    ?? '');
        $password = $_POST['password']      ?? '';
        $confirm  = $_POST['confirm']       ?? '';
        $codopt   = $_POST['codopt']        ?? '';

        if (!$nom || !$prenom || !$email || !$password || !$codopt) {
            $errors[] = 'Tous les champs sont obligatoires.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email invalide.';
        }
        if ($password !== $confirm) {
            $errors[] = 'Les mots de passe ne correspondent pas.';
        }
        if (!$user->validatePassword($password)) {
            $errors[] = 'Le mot de passe doit contenir au moins 8 caractères, une lettre et un chiffre.';
        }

        if (empty($errors)) {
            $result = $user->registerApprenant(compact('nom','prenom','email','password','codopt'));
            if ($result === true) {
                flashMessage('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');
                redirect(BASE_URL . '/controllers/AuthController.php?action=login');
            } else {
                $errors[] = $result;
            }
        }
    }
    include __DIR__ . '/../views/auth/register.php';
}

/* ============================================================
   FORGOT PASSWORD
   ============================================================ */
elseif ($action === 'forgot') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verifyCsrf();
        flashMessage('info', 'Si cet email existe, un lien de réinitialisation a été envoyé.');
        redirect(BASE_URL . '/controllers/AuthController.php?action=login');
    }
    include __DIR__ . '/../views/auth/forgot_password.php';
}
