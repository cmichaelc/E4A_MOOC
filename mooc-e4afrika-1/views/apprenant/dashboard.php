<?php
$pageTitle = 'Mon Dashboard';
include __DIR__ . '/../layouts/header.php';
?>
<main class="py-5 bg-light min-vh-100">
<div class="container">

  <!-- Bienvenue -->
  <div class="dashboard-welcome rounded-4 p-4 p-md-5 mb-4 text-white position-relative overflow-hidden">
    <div class="welcome-shapes"></div>
    <div class="position-relative">
      <h1 class="fw-800 h3 mb-1">Bonjour, <?= currentUserName() ?> 👋</h1>
      <p class="text-white-75 mb-0">Voici votre espace personnel. Continuez votre apprentissage !</p>
    </div>
    <div class="position-absolute top-0 end-0 p-4 d-none d-md-block">
      <span class="fs-1 opacity-20">📚</span>
    </div>
  </div>

  <!-- KPI Cards -->
  <div class="row g-4 mb-5">
    <?php
    $kpis = [
      ['Cours suivis',    $stats['total_cours']     ?? 0, 'bi-book-fill',      'primary'],
      ['Cours terminés',  $stats['cours_termines']  ?? 0, 'bi-check-circle-fill','success'],
      ['Progression moy.',($stats['moy_progression']??0).'%','bi-graph-up-arrow','warning'],
      ['Certificats',     $stats['nb_certificats']  ?? 0, 'bi-award-fill',     'info'],
    ];
    foreach ($kpis as [$label, $val, $icon, $color]): ?>
    <div class="col-6 col-lg-3">
      <div class="kpi-card card border-0 rounded-4 shadow-sm h-100 p-4">
        <div class="kpi-icon bg-<?= $color ?>-soft mb-3">
          <i class="bi <?= $icon ?> text-<?= $color ?>"></i>
        </div>
        <div class="kpi-value fw-800"><?= $val ?></div>
        <div class="kpi-label text-muted small"><?= $label ?></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Cours en cours -->
  <div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
      <h5 class="fw-700 mb-0"><i class="bi bi-play-circle text-primary me-2"></i>Mes formations en cours</h5>
      <a href="<?= BASE_URL ?>/controllers/ApprenantController.php?action=mes_cours" class="btn btn-sm btn-outline-primary rounded-pill">Voir tout</a>
    </div>
    <div class="card-body p-4">
      <?php if (empty($mesCours)): ?>
        <div class="text-center py-4">
          <i class="bi bi-book display-3 text-muted opacity-25"></i>
          <p class="text-muted mt-2">Vous n'êtes inscrit à aucun cours.</p>
          <a href="<?= BASE_URL ?>/controllers/CoursController.php?action=catalogue" class="btn btn-primary rounded-pill px-4">
            Explorer le catalogue
          </a>
        </div>
      <?php else: ?>
      <div class="row g-3">
        <?php foreach (array_slice($mesCours, 0, 4) as $c): ?>
        <div class="col-md-6">
          <div class="cours-progress-card p-3 rounded-3 border h-100">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <div>
                <h6 class="fw-700 mb-1 lh-sm"><?= e($c['titre']) ?></h6>
                <small class="text-muted"><?= e($c['form_prenom'].' '.$c['form_nom']) ?></small>
              </div>
              <?php if ($c['termine']): ?>
                <span class="badge bg-success rounded-pill"><i class="bi bi-check2 me-1"></i>Terminé</span>
              <?php else: ?>
                <span class="badge bg-primary-soft text-primary rounded-pill"><?= $c['progression'] ?>%</span>
              <?php endif; ?>
            </div>
            <div class="progress rounded-pill mb-2" style="height:6px">
              <div class="progress-bar bg-<?= progressColor((int)$c['progression']) ?> rounded-pill"
                   style="width:<?= $c['progression'] ?>%" role="progressbar"></div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <small class="text-muted">Inscrit le <?= formatDate($c['date_enroll']) ?></small>
              <a href="<?= BASE_URL ?>/controllers/ApprenantController.php?action=progression&idcours=<?= $c['id'] ?>"
                 class="btn btn-sm btn-primary rounded-pill px-3">
                <?= $c['termine'] ? 'Revoir' : 'Continuer' ?> <i class="bi bi-arrow-right ms-1"></i>
              </a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Raccourcis -->
  <div class="row g-3">
    <?php
    $shortcuts = [
      [BASE_URL.'/controllers/ApprenantController.php?action=devoirs',    'bi-file-earmark-text','warning','Mes devoirs',   'Soumettre vos travaux'],
      [BASE_URL.'/controllers/ApprenantController.php?action=certificats','bi-award',            'success','Certificats',   'Voir mes attestations'],
      [BASE_URL.'/controllers/CoursController.php?action=catalogue',      'bi-grid',             'info',   'Catalogue',     'Découvrir de nouveaux cours'],
    ];
    foreach ($shortcuts as [$url, $icon, $color, $label, $sub]): ?>
    <div class="col-md-4">
      <a href="<?= $url ?>" class="shortcut-card card border-0 rounded-4 shadow-sm p-4 text-decoration-none h-100 d-flex flex-row align-items-center gap-3">
        <div class="shortcut-icon bg-<?= $color ?>-soft">
          <i class="bi <?= $icon ?> text-<?= $color ?> fs-4"></i>
        </div>
        <div>
          <div class="fw-700 text-dark"><?= $label ?></div>
          <div class="text-muted small"><?= $sub ?></div>
        </div>
        <i class="bi bi-chevron-right text-muted ms-auto"></i>
      </a>
    </div>
    <?php endforeach; ?>
  </div>

</div>
</main>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
