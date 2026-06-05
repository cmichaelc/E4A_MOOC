<?php
$pageTitle = 'Rapports & Statistiques';
include __DIR__ . '/../layouts/header.php';
?>
<main class="py-5 bg-light min-vh-100">
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="fw-800 h3 mb-1"><i class="bi bi-file-bar-graph text-danger me-2"></i>Rapports & Statistiques</h1>
      <p class="text-muted mb-0">Vue analytique de la plateforme</p>
    </div>
    <a href="<?= BASE_URL ?>/controllers/AdminController.php?action=export_csv"
       class="btn btn-success rounded-pill px-4 fw-600">
      <i class="bi bi-download me-2"></i>Exporter apprenants CSV
    </a>
  </div>

  <!-- KPI Summary -->
  <div class="row g-4 mb-5">
    <?php
    $cards = [
      ['Apprenants total',   $kpis['nbApp'],      'bi-people',         'primary'],
      ['Formateurs validés', $kpis['nbForm'],     'bi-person-check',   'success'],
      ['Cours publiés',      $kpis['nbCours'],    'bi-collection',     'warning'],
      ['Certifications émises',$kpis['nbCert'],   'bi-award',          'info'],
      ['Total enrollments',  $kpis['nbEnroll'],   'bi-bookmark-check', 'secondary'],
      ['Progression moy.',   $kpis['avgProg'].'%','bi-graph-up-arrow', 'danger'],
    ];
    foreach ($cards as [$label, $val, $icon, $color]): ?>
    <div class="col-6 col-lg-2">
      <div class="kpi-card card border-0 rounded-4 shadow-sm h-100 p-3 text-center">
        <i class="bi <?= $icon ?> text-<?= $color ?> fs-2 mb-2"></i>
        <div class="fw-800 fs-3 text-<?= $color ?>"><?= $val ?></div>
        <div class="text-muted" style="font-size:.72rem"><?= $label ?></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="row g-4">
    <!-- Tableau apprenants -->
    <div class="col-12">
      <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between">
          <h5 class="fw-700 mb-0"><i class="bi bi-people text-primary me-2"></i>Récapitulatif apprenants</h5>
          <span class="badge bg-primary-soft text-primary"><?= count($apprenants) ?> au total</span>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover small align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th class="ps-4">Nom</th>
                  <th>Email</th>
                  <th>Option</th>
                  <th>Statut</th>
                  <th>Inscription</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($apprenants as $a): ?>
                <tr>
                  <td class="ps-4 fw-600"><?= e($a['prenom'].' '.$a['nom']) ?></td>
                  <td class="text-muted"><?= e($a['email']) ?></td>
                  <td><span class="badge bg-light text-dark"><?= e(truncate($a['opt_libelle'],18)) ?></span></td>
                  <td><?= statutBadge($a['statut']) ?></td>
                  <td class="text-muted"><?= formatDate($a['dateinscription']) ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Tableau cours -->
    <div class="col-12">
      <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-header bg-white border-0 p-4 pb-0">
          <h5 class="fw-700 mb-0"><i class="bi bi-collection text-warning me-2"></i>Récapitulatif cours</h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover small align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th class="ps-4">Titre</th>
                  <th>Formateur</th>
                  <th>Catégorie</th>
                  <th>Apprenants</th>
                  <th>Statut</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($cours as $c): ?>
                <tr>
                  <td class="ps-4 fw-600"><?= e($c['titre']) ?></td>
                  <td class="text-muted"><?= e($c['form_prenom'].' '.$c['form_nom']) ?></td>
                  <td><span class="badge bg-light text-dark"><?= e($c['cat_libelle']) ?></span></td>
                  <td><i class="bi bi-people me-1 text-muted"></i><?= (int)$c['nb_apprenants'] ?></td>
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
