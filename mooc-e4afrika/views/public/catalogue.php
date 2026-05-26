<?php
$pageTitle = 'Catalogue des cours';
include __DIR__ . '/../layouts/header.php';
?>
<main class="py-5 bg-light min-vh-100">
  <div class="container">
    <!-- En-tête -->
    <div class="row align-items-center mb-5">
      <div class="col-md-6">
        <h1 class="fw-800 display-6 mb-1">Catalogue des <span class="text-primary">formations</span></h1>
        <p class="text-muted">Explorez nos <?= count($listeCours) ?> formation(s) disponibles</p>
      </div>
      <div class="col-md-6">
        <form method="GET" action="<?= BASE_URL ?>/controllers/CoursController.php" class="d-flex gap-2">
          <input type="hidden" name="action" value="catalogue">
          <input type="text" class="form-control rounded-3 border-0 shadow-sm" name="q"
                 value="<?= e($search) ?>" placeholder="Rechercher un cours...">
          <button type="submit" class="btn btn-primary px-4 rounded-3">
            <i class="bi bi-search"></i>
          </button>
        </form>
      </div>
    </div>

    <div class="row g-4">
      <!-- Sidebar filtres -->
      <div class="col-lg-3">
        <div class="card border-0 rounded-4 shadow-sm p-4 sticky-top" style="top:80px">
          <h6 class="fw-700 mb-3">Filtrer par catégorie</h6>
          <div class="d-flex flex-column gap-2">
            <a href="<?= BASE_URL ?>/controllers/CoursController.php?action=catalogue<?= $search ? '&q='.urlencode($search) : '' ?>"
               class="filter-link <?= $catFilter === '' ? 'active' : '' ?>">
              <i class="bi bi-grid me-2"></i>Toutes les catégories
            </a>
            <?php foreach ($categories as $cat): ?>
            <a href="<?= BASE_URL ?>/controllers/CoursController.php?action=catalogue&cat=<?= urlencode($cat['codcat']) ?><?= $search ? '&q='.urlencode($search) : '' ?>"
               class="filter-link <?= $catFilter === $cat['codcat'] ? 'active' : '' ?>">
              <i class="bi bi-tag me-2"></i><?= e($cat['libelle']) ?>
            </a>
            <?php endforeach; ?>
          </div>
          <hr class="my-4">
          <h6 class="fw-700 mb-3">Niveau</h6>
          <div class="d-flex flex-column gap-1">
            <?php foreach(['DEBUTANT'=>'success','INTERMEDIAIRE'=>'warning','AVANCE'=>'danger'] as $niv => $col): ?>
            <span class="badge bg-<?= $col ?>-subtle text-<?= $col ?> rounded-pill px-3 py-2 text-start"><?= $niv ?></span>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Grille de cours -->
      <div class="col-lg-9">
        <?php if (empty($listeCours)): ?>
        <div class="text-center py-5">
          <i class="bi bi-search display-1 text-muted opacity-25"></i>
          <h4 class="mt-3 text-muted">Aucun cours trouvé</h4>
          <p class="text-muted">Essayez d'autres mots-clés ou explorez toutes les catégories.</p>
          <a href="<?= BASE_URL ?>/controllers/CoursController.php?action=catalogue" class="btn btn-primary rounded-pill">Voir tous les cours</a>
        </div>
        <?php else: ?>
        <div class="row g-4">
          <?php foreach ($listeCours as $c): ?>
          <div class="col-md-6 col-xl-4">
            <div class="cours-card card border-0 rounded-4 shadow-hover h-100 overflow-hidden">
              <div class="cours-card-img d-flex align-items-center justify-content-center">
                <i class="bi bi-laptop-fill display-1 text-white opacity-40"></i>
              </div>
              <div class="card-body p-4 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <span class="badge bg-primary-soft text-primary rounded-pill small"><?= e($c['cat_libelle']) ?></span>
                  <?= niveauBadge($c['niveau']) ?>
                </div>
                <h5 class="fw-700 mb-2 lh-sm"><?= e($c['titre']) ?></h5>
                <p class="text-muted small mb-3 flex-grow-1"><?= e(truncate($c['description'], 90)) ?></p>
                <div class="d-flex align-items-center gap-2 mb-3">
                  <div class="avatar-xs"><?= strtoupper(substr($c['form_prenom'], 0, 1)) ?></div>
                  <span class="small text-muted"><?= e($c['form_prenom'].' '.$c['form_nom']) ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                  <div class="d-flex gap-3 text-muted small">
                    <span><i class="bi bi-people me-1"></i><?= (int)$c['nb_apprenants'] ?></span>
                    <span><i class="bi bi-clock me-1"></i><?= $c['duree_totale'] ?> min</span>
                  </div>
                  <span class="fw-700 text-primary fs-6">
                    <?= $c['montant'] > 0 ? formatMontant($c['montant']) : '<span class="text-success fw-700">Gratuit</span>' ?>
                  </span>
                </div>
              </div>
              <div class="card-footer bg-white border-0 px-4 pb-4 pt-0">
                <a href="<?= BASE_URL ?>/controllers/CoursController.php?action=detail&id=<?= $c['id'] ?>"
                   class="btn btn-primary w-100 rounded-3 fw-600">
                  Voir le cours <i class="bi bi-arrow-right ms-1"></i>
                </a>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
