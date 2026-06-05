<?php
/**
 * Configuration globale — E4Afrika MOOC
 */

// Base URL (adapter selon l'environnement)
define('BASE_URL', 'http://localhost/mooc-e4afrika');
define('SITE_NAME', 'E4Afrika MOOC Platform');
define('SITE_DESCRIPTION', 'Plateforme de formation en ligne pour l\'Afrique');

// Chemins
define('ROOT_PATH',    __DIR__ . '/..');
define('UPLOAD_PATH',  ROOT_PATH . '/uploads/devoirs/');
define('UPLOAD_URL',   BASE_URL . '/uploads/devoirs/');
define('AVATAR_PATH',  ROOT_PATH . '/uploads/avatars/');

// Sécurité
define('MAX_LOGIN_ATTEMPTS', 3);
define('LOCK_DURATION_MINUTES', 15);
define('MIN_PASSWORD_LENGTH', 8);

// Upload fichiers
define('MAX_FILE_SIZE',     5 * 1024 * 1024); // 5 Mo
define('ALLOWED_MIME_TYPES', [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/zip',
    'image/jpeg',
    'image/png',
]);

// Email (adapter pour PHPMailer)
define('MAIL_HOST',     'smtp.example.com');
define('MAIL_PORT',     587);
define('MAIL_USER',     'noreply@e4afrika.com');
define('MAIL_PASS',     'your_password');
define('MAIL_FROM_NAME','E4Afrika MOOC');

// Fuseau horaire
date_default_timezone_set('Africa/Porto-Novo');

// Affichage erreurs (désactiver en production)
ini_set('display_errors', 1);
error_reporting(E_ALL);
