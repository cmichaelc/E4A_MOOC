<?php
$pageTitle = 'Mes certificats';
include __DIR__ . '/../layouts/header.php';
?>
<main class="py-5 bg-light min-vh-100">
<div class="container">
  <div class="mb-4">
    <h1 class="fw-800 h3 mb-1"><i class="bi bi-award-fill text-warning me-2"></i>Mes certificats</h1>
    <p class="text-muted mb-0"><?= count($certificats) ?> certificat(s) obtenu(s)</p>
  </div>

  <?php if (empty($certificats)): ?>
  <div class="card border-0 rounded-4 shadow-sm text-center p-5">
    <i class="bi bi-award display-1 text-muted opacity-25"></i>
    <h4 class="mt-3 text-muted">Aucun certificat pour l'instant</h4>
    <p class="text-muted">Terminez une formation pour obtenir votre premier certificat.</p>
    <a href="<?= BASE_URL ?>/controllers/ApprenantController.php?action=mes_cours" class="btn btn-primary rounded-pill px-4 mt-2">
      Voir mes cours
    </a>
  </div>
  <?php else: ?>
  <div class="row g-4">
    <?php foreach ($certificats as $c): ?>
    <div class="col-md-6 col-lg-4">
      <div class="certificat-card card border-0 rounded-4 shadow-sm overflow-hidden h-100">
        <div class="certificat-header p-4 text-white text-center position-relative">
          <div class="certificat-seal mb-2">🏆</div>
          <div class="fw-700 small text-white-75 mb-1">CERTIFICAT DE RÉUSSITE</div>
          <h5 class="fw-800 lh-sm"><?= e($c['cours_titre']) ?></h5>
        </div>
        <div class="card-body p-4">
          <div class="d-flex flex-column gap-2 mb-4">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-person-circle text-primary"></i>
              <span class="small text-muted">Formateur : <strong><?= e($c['form_prenom'].' '.$c['form_nom']) ?></strong></span>
            </div>
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-calendar-check text-success"></i>
              <span class="small text-muted">Émis le : <strong><?= formatDate($c['date_emis']) ?></strong></span>
            </div>
            <div class="d-flex align-items-start gap-2">
              <i class="bi bi-shield-check text-info"></i>
              <span class="small text-muted" style="word-break:break-all">
                Code : <code class="small"><?= substr($c['code_verif'],0,16).'...' ?></code>
              </span>
            </div>
          </div>
          <a href="<?= BASE_URL ?>/controllers/ApprenantController.php?action=voir_certificat&id=<?= $c['id'] ?>"
             target="_blank" class="btn btn-warning w-100 rounded-3 fw-700">
            <i class="bi bi-printer me-2"></i>Voir & Imprimer
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
