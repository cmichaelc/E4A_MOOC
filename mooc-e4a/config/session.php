<?php
/**
 * Gestion de session sécurisée — E4Afrika MOOC
 */

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => false, // true en production HTTPS
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}

/**
 * Régénère l'ID de session (après connexion)
 */
function regenerateSession(): void {
    session_regenerate_id(true);
}

/**
 * Détruit la session (déconnexion)
 */
function destroySession(): void {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $p["path"], $p["domain"], $p["secure"], $p["httponly"]);
    }
    session_destroy();
}
