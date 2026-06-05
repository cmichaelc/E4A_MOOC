<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../helpers/auth.php';
require_once __DIR__ . '/../../helpers/functions.php';
$flash = getFlash();
$role  = getRole();
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="E4Afrika MOOC — Plateforme de formation en ligne pour l'Afrique">
<title><?= isset($pageTitle) ? e($pageTitle).' | ' : '' ?>E4Afrika MOOC Platform</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700&display=swap">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top" id="mainNav">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="<?= BASE_URL ?>/index.php">
      <span class="brand-logo">E4<span>Afrika</span></span>
      <span class="brand-sub d-none d-md-inline">MOOC</span>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="<?= BASE_URL ?>/index.php">
            <i class="bi bi-house-fill me-1"></i>Accueil
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= BASE_URL ?>/controllers/CoursController.php?action=catalogue">
            <i class="bi bi-grid-fill me-1"></i>Catalogue
          </a>
        </li>
      </ul>
      <ul class="navbar-nav ms-auto align-items-center gap-1">
        <?php if (!isLoggedIn()): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>/controllers/AuthController.php?action=login">
              <i class="bi bi-box-arrow-in-right me-1"></i>Connexion
            </a>
          </li>
          <li class="nav-item">
            <a class="btn btn-primary btn-sm px-3" href="<?= BASE_URL ?>/controllers/AuthController.php?action=register">
              <i class="bi bi-person-plus-fill me-1"></i>S'inscrire
            </a>
          </li>
        <?php else: ?>
          <?php if ($role === 'apprenant'): ?>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/controllers/ApprenantController.php?action=dashboard"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/controllers/ApprenantController.php?action=mes_cours"><i class="bi bi-book me-1"></i>Mes cours</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/controllers/ApprenantController.php?action=devoirs"><i class="bi bi-file-earmark-text me-1"></i>Devoirs</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/controllers/ApprenantController.php?action=certificats"><i class="bi bi-award me-1"></i>Certificats</a></li>
          <?php elseif ($role === 'formateur'): ?>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/controllers/FormateurController.php?action=dashboard"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/controllers/FormateurController.php?action=mes_cours"><i class="bi bi-book me-1"></i>Mes cours</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/controllers/FormateurController.php?action=notation"><i class="bi bi-pencil-square me-1"></i>Notation</a></li>
          <?php elseif ($role === 'admin'): ?>
            <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/controllers/AdminController.php?action=dashboard"><i class="bi bi-speedometer2 me-1"></i>Admin</a></li>
          <?php endif; ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown">
              <span class="avatar-sm"><?= strtoupper(substr($_SESSION['user_nom'] ?? 'U', 0, 1)) ?></span>
              <?= currentUserName() ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow">
              <li><span class="dropdown-item-text small text-muted"><?= e($_SESSION['user_email'] ?? '') ?></span></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item text-danger" href="<?= BASE_URL ?>/controllers/AuthController.php?action=logout">
                  <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                </a>
              </li>
            </ul>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<?php if ($flash): ?>
<div class="container mt-3">
  <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show shadow-sm" role="alert">
    <i class="bi bi-<?= $flash['type']==='success'?'check-circle':'exclamation-triangle' ?>-fill me-2"></i>
    <?= $flash['message'] ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
</div>
<?php endif; ?>
