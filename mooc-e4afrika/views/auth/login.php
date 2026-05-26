<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../helpers/csrf.php';
require_once __DIR__ . '/../../helpers/functions.php';
$pageTitle = 'Connexion';
include __DIR__ . '/../layouts/header.php';
?>
<main class="auth-page d-flex align-items-center min-vh-100">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-5 col-md-7">
        <div class="auth-card card shadow-lg border-0 rounded-4 overflow-hidden">
          <div class="auth-card-header text-center p-5 pb-4">
            <div class="auth-logo mb-3">E4<span>Afrika</span></div>
            <h1 class="h3 fw-700 text-white mb-1">Bon retour !</h1>
            <p class="text-white-50 mb-0">Connectez-vous à votre espace</p>
          </div>
          <div class="card-body p-5">
            <form method="POST" action="<?= BASE_URL ?>/controllers/AuthController.php?action=login" id="loginForm" novalidate>
              <?= csrfField() ?>
              <div class="mb-4">
                <label for="email" class="form-label fw-500">Adresse email</label>
                <div class="input-group input-group-lg">
                  <span class="input-group-text bg-light border-0"><i class="bi bi-envelope text-primary"></i></span>
                  <input type="email" class="form-control border-0 bg-light ps-2" id="email" name="email"
                         placeholder="votre@email.com" required autocomplete="email"
                         value="<?= e($_POST['email'] ?? '') ?>">
                </div>
              </div>
              <div class="mb-4">
                <label for="password" class="form-label fw-500">Mot de passe</label>
                <div class="input-group input-group-lg">
                  <span class="input-group-text bg-light border-0"><i class="bi bi-lock text-primary"></i></span>
                  <input type="password" class="form-control border-0 bg-light ps-2" id="password" name="password"
                         placeholder="••••••••" required autocomplete="current-password">
                  <button class="btn btn-light border-0" type="button" id="togglePwd">
                    <i class="bi bi-eye text-muted"></i>
                  </button>
                </div>
              </div>
              <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="remember" name="remember">
                  <label class="form-check-label small text-muted" for="remember">Se souvenir de moi</label>
                </div>
                <a href="<?= BASE_URL ?>/controllers/AuthController.php?action=forgot" class="small text-primary text-decoration-none">
                  Mot de passe oublié ?
                </a>
              </div>
              <button type="submit" class="btn btn-primary w-100 btn-lg rounded-3 fw-600" id="loginBtn">
                <span class="btn-text"><i class="bi bi-box-arrow-in-right me-2"></i>Se connecter</span>
                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
              </button>
            </form>
            <hr class="my-4">
            <p class="text-center text-muted mb-0">
              Pas encore de compte ?
              <a href="<?= BASE_URL ?>/controllers/AuthController.php?action=register" class="text-primary fw-600 text-decoration-none">
                Créer un compte
              </a>
            </p>
          </div>
        </div>
        <!-- Aide comptes démo -->
        <div class="card border-0 bg-light rounded-4 mt-3 p-3">
          <p class="small text-muted mb-2 fw-600"><i class="bi bi-info-circle me-1 text-primary"></i>Comptes de démonstration :</p>
          <div class="d-flex flex-column gap-1">
            <span class="small text-muted"><b>Admin :</b> admin@e4afrika.com / <em>password</em></span>
            <span class="small text-muted"><b>Formateur :</b> kokou.mensah@e4afrika.com / <em>password</em></span>
            <span class="small text-muted"><b>Apprenant :</b> fidele.agossou@email.com / <em>password</em></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
