<?php
$pageTitle = 'Dashboard Formateur';
include __DIR__ . '/../layouts/header.php';
?>
<main class="py-5 bg-light min-vh-100">
<div class="container">

  <div class="dashboard-welcome rounded-4 p-4 p-md-5 mb-4 text-white position-relative overflow-hidden">
    <div class="welcome-shapes"></div>
    <div class="position-relative">
      <h1 class="fw-800 h3 mb-1">Espace Formateur 🎓</h1>
      <p class="text-white-75 mb-0">Bienvenue, <?= currentUserName() ?> — Gérez vos formations</p>
    </div>
  </div>

  <!-- KPIs -->
  <div class="row g-4 mb-5">
    <?php
    $kpis = [
      ['Mes cours',      $stats['nbCours'],      'bi-book-fill',       'primary'],
      ['Apprenants',     $stats['nbApprenants'],  'bi-people-fill',     'success'],
      ['Taux completion',$stats['avgProg'].'%',   'bi-graph-up-arrow',  'warning'],
      ['Devoirs créés',  $stats['nbDevoirs'],     'bi-file-earmark-text','info'],
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

  <!-- Mes cours récents -->
  <div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
      <h5 class="fw-700 mb-0"><i class="bi bi-collection text-primary me-2"></i>Mes cours récents</h5>
      <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>/controllers/FormateurController.php?action=create_cours" class="btn btn-primary btn-sm rounded-pill px-3">
          <i class="bi bi-plus me-1"></i>Nouveau cours
        </a>
        <a href="<?= BASE_URL ?>/controllers/FormateurController.php?action=mes_cours" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Voir tout</a>
      </div>
    </div>
    <div class="card-body p-4">
      <?php if (empty($mesCours)): ?>
        <div class="text-center py-4">
          <i class="bi bi-journal-plus display-3 text-muted opacity-25"></i>
          <p class="text-muted mt-2">Aucun cours créé. Commencez dès maintenant !</p>
          <a href="<?= BASE_URL ?>/controllers/FormateurController.php?action=create_cours" class="btn btn-primary rounded-pill px-4">Créer mon premier cours</a>
        </div>
      <?php else: ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Titre</th>
              <th>Statut</th>
              <th>Apprenants</th>
              <th>Catégorie</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach (array_slice($mesCours, 0, 5) as $c): ?>
            <tr>
              <td class="fw-600"><?= e($c['titre']) ?></td>
              <td><?= statutBadge($c['statut']) ?></td>
              <td><i class="bi bi-people me-1 text-muted"></i><?= (int)$c['nb_apprenants'] ?></td>
              <td><span class="badge bg-light text-muted"><?= e($c['cat_libelle']) ?></span></td>
              <td>
                <a href="<?= BASE_URL ?>/controllers/FormateurController.php?action=modules&idcours=<?= $c['id'] ?>"
                   class="btn btn-sm btn-outline-primary rounded-pill px-3">
                  <i class="bi bi-list-check me-1"></i>Modules
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Raccourcis -->
  <div class="row g-3">
    <?php
    $shortcuts = [
      [BASE_URL.'/controllers/FormateurController.php?action=notation','bi-pencil-square','warning','Corriger les devoirs','Consulter les soumissions'],
      [BASE_URL.'/controllers/FormateurController.php?action=devoirs',  'bi-file-earmark-plus','info','Gérer les devoirs','Créer et suivre les devoirs'],
    ];
    foreach ($shortcuts as [$url, $icon, $color, $label, $sub]): ?>
    <div class="col-md-6">
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
