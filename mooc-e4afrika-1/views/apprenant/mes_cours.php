<?php
$pageTitle = 'Mes cours';
include __DIR__ . '/../layouts/header.php';
?>
<main class="py-5 bg-light min-vh-100">
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="fw-800 h3 mb-1"><i class="bi bi-book-fill text-primary me-2"></i>Mes formations</h1>
      <p class="text-muted mb-0"><?= count($mesCours) ?> formation(s) suivie(s)</p>
    </div>
    <a href="<?= BASE_URL ?>/controllers/CoursController.php?action=catalogue" class="btn btn-primary rounded-pill px-4">
      <i class="bi bi-plus me-1"></i>Nouvelle formation
    </a>
  </div>

  <?php if (empty($mesCours)): ?>
  <div class="card border-0 rounded-4 shadow-sm text-center p-5">
    <i class="bi bi-book display-1 text-muted opacity-25"></i>
    <h4 class="mt-3 text-muted">Aucune formation suivie</h4>
    <a href="<?= BASE_URL ?>/controllers/CoursController.php?action=catalogue" class="btn btn-primary rounded-pill px-5 mt-3">Découvrir les cours</a>
  </div>
  <?php else: ?>
  <div class="row g-4">
    <?php foreach ($mesCours as $c): ?>
    <div class="col-md-6 col-xl-4">
      <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden">
        <div class="cours-card-img-sm d-flex align-items-center justify-content-center">
          <i class="bi bi-laptop-fill display-3 text-white opacity-40"></i>
        </div>
        <div class="card-body p-4">
          <span class="badge bg-primary-soft text-primary rounded-pill mb-2"><?= e($c['cat_libelle']) ?></span>
          <h5 class="fw-700 mb-2 lh-sm"><?= e($c['titre']) ?></h5>
          <div class="text-muted small mb-3">
            <i class="bi bi-person-circle me-1"></i><?= e($c['form_prenom'].' '.$c['form_nom']) ?>
          </div>
          <!-- Progression -->
          <div class="mb-3">
            <div class="d-flex justify-content-between mb-1">
              <small class="text-muted">Progression</small>
              <small class="fw-700 text-<?= progressColor((int)$c['progression']) ?>"><?= $c['progression'] ?>%</small>
            </div>
            <div class="progress rounded-pill" style="height:8px">
              <div class="progress-bar bg-<?= progressColor((int)$c['progression']) ?> rounded-pill"
                   style="width:<?= $c['progression'] ?>%"></div>
            </div>
          </div>
          <?php if ($c['termine']): ?>
            <div class="alert alert-success py-2 px-3 rounded-3 small mb-0 text-center">
              <i class="bi bi-award-fill me-1"></i>Formation terminée — Certificat disponible
            </div>
          <?php endif; ?>
        </div>
        <div class="card-footer bg-white border-0 px-4 pb-4">
          <a href="<?= BASE_URL ?>/controllers/ApprenantController.php?action=progression&idcours=<?= $c['id'] ?>"
             class="btn btn-<?= $c['termine'] ? 'outline-success' : 'primary' ?> w-100 rounded-3 fw-600">
            <i class="bi bi-<?= $c['termine'] ? 'eye' : 'play-fill' ?> me-2"></i>
            <?= $c['termine'] ? 'Revoir le cours' : 'Continuer' ?>
          </a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
</main>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
