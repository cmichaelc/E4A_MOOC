<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../helpers/csrf.php';
require_once __DIR__ . '/../../helpers/functions.php';
$pageTitle = 'Mot de passe oublié';
include __DIR__ . '/../layouts/header.php';
?>
<main class="auth-page d-flex align-items-center min-vh-100">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-5 col-md-7">
        <div class="auth-card card shadow-lg border-0 rounded-4 overflow-hidden">
          <div class="auth-card-header text-center p-5 pb-4">
            <div class="auth-logo mb-3">E4<span>Afrika</span></div>
            <h1 class="h4 fw-700 text-white mb-1">Mot de passe oublié</h1>
            <p class="text-white-50 mb-0">Réinitialisez votre accès</p>
          </div>
          <div class="card-body p-5">
            <p class="text-muted text-center mb-4">
              Entrez votre adresse email. Si un compte existe, vous recevrez un lien de réinitialisation.
            </p>
            <form method="POST" action="<?= BASE_URL ?>/controllers/AuthController.php?action=forgot">
              <?= csrfField() ?>
              <div class="mb-4">
                <label class="form-label fw-500" for="forgot_email">Adresse email</label>
                <div class="input-group input-group-lg">
                  <span class="input-group-text bg-light border-0"><i class="bi bi-envelope text-primary"></i></span>
                  <input type="email" class="form-control bg-light border-0 ps-2" id="forgot_email"
                         name="email" placeholder="votre@email.com" required>
                </div>
              </div>
              <button type="submit" class="btn btn-primary w-100 btn-lg rounded-3 fw-600">
                <i class="bi bi-send me-2"></i>Envoyer le lien
              </button>
            </form>
            <hr class="my-4">
            <p class="text-center text-muted mb-0">
              <a href="<?= BASE_URL ?>/controllers/AuthController.php?action=login" class="text-primary fw-600 text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i>Retour à la connexion
              </a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
