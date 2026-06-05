<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../helpers/csrf.php';
require_once __DIR__ . '/../../helpers/functions.php';
$pageTitle = 'Créer un compte';
include __DIR__ . '/../layouts/header.php';
?>
<main class="auth-page py-5">
  <div class="container py-3">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8">
        <div class="auth-card card shadow-lg border-0 rounded-4 overflow-hidden">
          <div class="auth-card-header text-center p-4 pb-3">
            <div class="auth-logo mb-2">E4<span>Afrika</span></div>
            <h1 class="h3 fw-700 text-white mb-1">Créer votre compte</h1>
            <p class="text-white-50 mb-0">Rejoignez la communauté E4Afrika</p>
          </div>
          <div class="card-body p-4 p-md-5">
            <?php if (!empty($errors)): ?>
              <div class="alert alert-danger rounded-3">
                <ul class="mb-0 ps-3">
                  <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>
            <form method="POST" action="<?= BASE_URL ?>/controllers/AuthController.php?action=register" id="registerForm" novalidate>
              <?= csrfField() ?>
              <div class="row g-3 mb-3">
                <div class="col-6">
                  <label class="form-label fw-500" for="nom">Nom *</label>
                  <input type="text" class="form-control bg-light border-0 rounded-3" id="nom" name="nom"
                         placeholder="DUPONT" required value="<?= e($_POST['nom'] ?? '') ?>">
                </div>
                <div class="col-6">
                  <label class="form-label fw-500" for="prenom">Prénom *</label>
                  <input type="text" class="form-control bg-light border-0 rounded-3" id="prenom" name="prenom"
                         placeholder="Jean" required value="<?= e($_POST['prenom'] ?? '') ?>">
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label fw-500" for="reg_email">Email *</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-0"><i class="bi bi-envelope text-primary"></i></span>
                  <input type="email" class="form-control bg-light border-0" id="reg_email" name="email"
                         placeholder="votre@email.com" required value="<?= e($_POST['email'] ?? '') ?>">
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label fw-500" for="codopt">Option / Filière *</label>
                <select class="form-select bg-light border-0 rounded-3" id="codopt" name="codopt" required>
                  <option value="">-- Sélectionner votre option --</option>
                  <?php foreach ($options as $opt): ?>
                    <option value="<?= e($opt['codopt']) ?>"
                      <?= (($_POST['codopt'] ?? '') === $opt['codopt']) ? 'selected' : '' ?>>
                      <?= e($opt['libelle']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label fw-500" for="reg_password">Mot de passe *</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-0"><i class="bi bi-lock text-primary"></i></span>
                  <input type="password" class="form-control bg-light border-0" id="reg_password" name="password"
                         placeholder="Min. 8 caractères" required>
                  <button class="btn btn-light border-0" type="button" id="togglePwd2">
                    <i class="bi bi-eye text-muted"></i>
                  </button>
                </div>
                <div class="progress mt-2" style="height:4px" id="pwdStrengthBar">
                  <div class="progress-bar" id="pwdStrength" style="width:0%"></div>
                </div>
                <small class="text-muted" id="pwdHint">Min. 8 caractères, une lettre et un chiffre</small>
              </div>
              <div class="mb-4">
                <label class="form-label fw-500" for="confirm">Confirmer le mot de passe *</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-0"><i class="bi bi-shield-check text-primary"></i></span>
                  <input type="password" class="form-control bg-light border-0" id="confirm" name="confirm"
                         placeholder="Répéter le mot de passe" required>
                </div>
                <div class="invalid-feedback d-none" id="confirmError">Les mots de passe ne correspondent pas.</div>
              </div>
              <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="cgv" name="cgv" required>
                <label class="form-check-label small" for="cgv">
                  J'accepte les <a href="#" class="text-primary">conditions d'utilisation</a>
                </label>
              </div>
              <button type="submit" class="btn btn-primary w-100 btn-lg rounded-3 fw-600">
                <i class="bi bi-person-plus-fill me-2"></i>Créer mon compte
              </button>
            </form>
            <hr class="my-4">
            <p class="text-center text-muted mb-0">
              Déjà inscrit ?
              <a href="<?= BASE_URL ?>/controllers/AuthController.php?action=login" class="text-primary fw-600 text-decoration-none">
                Se connecter
              </a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
