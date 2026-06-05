<?php
$pageTitle = 'Dashboard Administrateur';
include __DIR__ . '/../layouts/header.php';
?>
<main class="py-5 bg-light min-vh-100">
<div class="container">

  <div class="dashboard-welcome rounded-4 p-4 p-md-5 mb-4 text-white position-relative overflow-hidden">
    <div class="welcome-shapes"></div>
    <div class="position-relative">
      <h1 class="fw-800 h3 mb-1">Tableau de bord Admin ⚙️</h1>
      <p class="text-white-75 mb-0">Vue globale de la plateforme E4Afrika MOOC</p>
    </div>
    <div class="position-absolute top-0 end-0 p-4 d-none d-md-block">
      <span class="fs-1 opacity-20">🛡️</span>
    </div>
  </div>

  <!-- KPIs -->
  <div class="row g-4 mb-5">
    <?php
    $cards = [
      ['Apprenants inscrits', $kpis['nbApp'],      'bi-people-fill',     'primary'],
      ['Formateurs actifs',   $kpis['nbForm'],     'bi-person-video3',   'success'],
      ['Cours publiés',       $kpis['nbCours'],    'bi-collection-fill', 'warning'],
      ['Certifications',      $kpis['nbCert'],     'bi-award-fill',      'info'],
      ['Enrollments',         $kpis['nbEnroll'],   'bi-bookmark-fill',   'secondary'],
      ['Progression moy.',    $kpis['avgProg'].'%','bi-graph-up-arrow',  'danger'],
    ];
    foreach ($cards as [$label, $val, $icon, $color]): ?>
    <div class="col-6 col-lg-2">
      <div class="kpi-card card border-0 rounded-4 shadow-sm h-100 p-3 text-center">
        <div class="kpi-icon bg-<?= $color ?>-soft mx-auto mb-2">
          <i class="bi <?= $icon ?> text-<?= $color ?>"></i>
        </div>
        <div class="kpi-value fw-800 fs-4"><?= $val ?></div>
        <div class="kpi-label text-muted" style="font-size:.72rem"><?= $label ?></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Navigation rapide Admin -->
  <div class="row g-3 mb-5">
    <?php
    $nav = [
      [BASE_URL.'/controllers/AdminController.php?action=apprenants', 'bi-people',          'primary', 'Apprenants',   'Gérer les comptes apprenants'],
      [BASE_URL.'/controllers/AdminController.php?action=formateurs', 'bi-person-video3',   'success', 'Formateurs',   'Valider et gérer les formateurs'],
      [BASE_URL.'/controllers/AdminController.php?action=cours',      'bi-collection',      'warning', 'Cours',        'Gérer tous les cours'],
      [BASE_URL.'/controllers/AdminController.php?action=filieres',   'bi-diagram-3',       'info',    'Filières/Opt.','Filières, options, matières'],
      [BASE_URL.'/controllers/AdminController.php?action=rapports',   'bi-file-bar-graph',  'danger',  'Rapports',     'Statistiques et exports'],
    ];
    foreach ($nav as [$url, $icon, $color, $label, $sub]): ?>
    <div class="col-6 col-md-4 col-lg">
      <a href="<?= $url ?>" class="shortcut-card card border-0 rounded-4 shadow-sm p-3 text-decoration-none h-100 text-center d-flex flex-column align-items-center gap-2">
        <div class="shortcut-icon bg-<?= $color ?>-soft">
          <i class="bi <?= $icon ?> text-<?= $color ?> fs-4"></i>
        </div>
        <div class="fw-700 text-dark small"><?= $label ?></div>
        <div class="text-muted" style="font-size:.72rem"><?= $sub ?></div>
      </a>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="row g-4">
    <!-- Derniers apprenants -->
    <div class="col-lg-6">
      <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
          <h5 class="fw-700 mb-0"><i class="bi bi-people text-primary me-2"></i>Derniers apprenants</h5>
          <a href="<?= BASE_URL ?>/controllers/AdminController.php?action=apprenants" class="btn btn-sm btn-outline-primary rounded-pill">Voir tout</a>
        </div>
        <div class="card-body p-4">
          <div class="table-responsive">
            <table class="table table-hover small align-middle mb-0">
              <thead class="table-light"><tr><th>Nom</th><th>Option</th><th>Statut</th></tr></thead>
              <tbody>
                <?php foreach (array_slice($apprenants, 0, 5) as $a): ?>
                <tr>
                  <td>
                    <div class="fw-600"><?= e($a['prenom'].' '.$a['nom']) ?></div>
                    <div class="text-muted x-small"><?= e($a['email']) ?></div>
                  </td>
                  <td><span class="badge bg-light text-muted"><?= e(truncate($a['opt_libelle'],20)) ?></span></td>
                  <td><?= statutBadge($a['statut']) ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Derniers cours -->
    <div class="col-lg-6">
      <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
          <h5 class="fw-700 mb-0"><i class="bi bi-collection text-warning me-2"></i>Tous les cours</h5>
          <a href="<?= BASE_URL ?>/controllers/AdminController.php?action=cours" class="btn btn-sm btn-outline-primary rounded-pill">Voir tout</a>
        </div>
        <div class="card-body p-4">
          <div class="table-responsive">
            <table class="table table-hover small align-middle mb-0">
              <thead class="table-light"><tr><th>Titre</th><th>Formateur</th><th>Statut</th></tr></thead>
              <tbody>
                <?php foreach (array_slice($cours, 0, 5) as $c): ?>
                <tr>
                  <td class="fw-600"><?= e(truncate($c['titre'],25)) ?></td>
                  <td class="text-muted"><?= e($c['form_prenom'].' '.$c['form_nom']) ?></td>
                  <td><?= statutBadge($c['statut']) ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
</main>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
