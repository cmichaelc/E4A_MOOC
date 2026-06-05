<?php
$pageTitle = 'Ma progression — ' . e($detail['titre']);
include __DIR__ . '/../layouts/header.php';

$done  = count(array_filter($modules, fn($m) => $m['complete']));
$total = count($modules);
$pct   = $total > 0 ? (int)round($done / $total * 100) : 0;
?>
<main class="py-5 bg-light min-vh-100">
<div class="container">

  <!-- Breadcrumb + En-tête -->
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/controllers/ApprenantController.php?action=mes_cours">Mes cours</a></li>
      <li class="breadcrumb-item active"><?= e($detail['titre']) ?></li>
    </ol>
  </nav>

  <div class="row g-4">
    <!-- Modules à gauche -->
    <div class="col-lg-4">
      <div class="card border-0 rounded-4 shadow-sm sticky-top" style="top:80px">
        <div class="card-body p-4">
          <h5 class="fw-700 mb-1"><?= e($detail['titre']) ?></h5>
          <small class="text-muted">par <?= e($detail['form_prenom'].' '.$detail['form_nom']) ?></small>
          <!-- Barre de progression globale -->
          <div class="my-3">
            <div class="d-flex justify-content-between mb-1">
              <small class="text-muted">Progression globale</small>
              <small class="fw-700 text-<?= progressColor($pct) ?>"><?= $pct ?>%</small>
            </div>
            <div class="progress rounded-pill" style="height:10px">
              <div class="progress-bar bg-<?= progressColor($pct) ?> rounded-pill progress-animated"
                   style="width:<?= $pct ?>%" id="globalProgress"></div>
            </div>
            <small class="text-muted"><?= $done ?>/<?= $total ?> modules complétés</small>
          </div>
          <hr>
          <!-- Liste modules -->
          <div class="modules-nav d-flex flex-column gap-2">
            <?php foreach ($modules as $i => $m): ?>
            <a href="#module-<?= $m['id'] ?>" class="module-nav-item d-flex align-items-center gap-3 p-2 rounded-3 text-decoration-none
               <?= $m['complete'] ? 'module-complete' : 'module-pending' ?>">
              <div class="module-check-icon">
                <?php if ($m['complete']): ?>
                  <i class="bi bi-check-circle-fill text-success"></i>
                <?php else: ?>
                  <span class="module-num-sm"><?= $i+1 ?></span>
                <?php endif; ?>
              </div>
              <div class="flex-grow-1">
                <div class="small fw-600 text-dark lh-sm"><?= e($m['titre']) ?></div>
                <?php if ($m['complete'] && $m['date_compl']): ?>
                  <div class="x-small text-success">✓ <?= formatDate($m['date_compl'], 'd/m/Y') ?></div>
                <?php else: ?>
                  <div class="x-small text-muted"><?= count($contenus[$m['id']] ?? []) ?> ressource(s)</div>
                <?php endif; ?>
              </div>
            </a>
            <?php endforeach; ?>
          </div>
          <?php if ($pct >= 100): ?>
          <div class="mt-3">
            <a href="<?= BASE_URL ?>/controllers/ApprenantController.php?action=certificats"
               class="btn btn-success w-100 rounded-3 fw-700">
              <i class="bi bi-award-fill me-2"></i>Voir mon certificat
            </a>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Contenu principal -->
    <div class="col-lg-8">
      <?php foreach ($modules as $i => $m): ?>
      <div class="card border-0 rounded-4 shadow-sm mb-4" id="module-<?= $m['id'] ?>">
        <div class="card-header bg-white border-0 p-4 pb-3 d-flex justify-content-between align-items-center">
          <div>
            <span class="badge bg-primary rounded-pill me-2">Module <?= $i+1 ?></span>
            <h5 class="fw-700 d-inline"><?= e($m['titre']) ?></h5>
          </div>
          <?php if ($m['complete']): ?>
            <span class="badge bg-success"><i class="bi bi-check2 me-1"></i>Complété</span>
          <?php endif; ?>
        </div>
        <div class="card-body p-4">
          <!-- Contenus -->
          <?php if (!empty($contenus[$m['id']])): ?>
          <div class="d-flex flex-column gap-3 mb-4">
            <?php foreach ($contenus[$m['id']] as $ct): ?>
            <div class="contenu-item p-3 rounded-3 border">
              <?php if ($ct['type'] === 'VIDEO'): ?>
                <h6 class="fw-600 mb-2"><i class="bi bi-play-circle-fill text-danger me-2"></i><?= e($ct['titre']) ?></h6>
                <?php if (str_contains($ct['urlcontenu'], 'youtube')): ?>
                <div class="ratio ratio-16x9 rounded-3 overflow-hidden">
                  <iframe src="<?= e($ct['urlcontenu']) ?>" allowfullscreen loading="lazy"
                          title="<?= e($ct['titre']) ?>"></iframe>
                </div>
                <?php endif; ?>
              <?php elseif ($ct['type'] === 'PDF'): ?>
                <div class="d-flex align-items-center gap-3">
                  <i class="bi bi-file-earmark-pdf-fill text-danger fs-2"></i>
                  <div>
                    <h6 class="fw-600 mb-1"><?= e($ct['titre']) ?></h6>
                    <a href="<?= BASE_URL.'/'.e($ct['urlcontenu']) ?>" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill">
                      <i class="bi bi-download me-1"></i>Télécharger
                    </a>
                  </div>
                </div>
              <?php elseif ($ct['type'] === 'QUIZ'): ?>
                <div class="d-flex align-items-center justify-content-between">
                  <div>
                    <h6 class="fw-600 mb-1"><i class="bi bi-question-circle-fill text-warning me-2"></i><?= e($ct['titre']) ?></h6>
                    <small class="text-muted">Testez vos connaissances sur ce module</small>
                  </div>
                  <a href="<?= BASE_URL ?>/controllers/CoursController.php?action=quiz&idmodule=<?= $m['id'] ?>"
                     class="btn btn-warning btn-sm rounded-pill px-4 fw-600">
                    <i class="bi bi-play-fill me-1"></i>Faire le quiz
                  </a>
                </div>
              <?php endif; ?>
              <div class="mt-2 text-end">
                <small class="text-muted"><i class="bi bi-clock me-1"></i><?= $ct['duree'] ?> min</small>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

          <!-- Bouton marquer complet -->
          <?php if (!$m['complete']): ?>
          <form method="POST" action="<?= BASE_URL ?>/controllers/CoursController.php?action=complete_module">
            <?= csrfField() ?>
            <input type="hidden" name="idmodule" value="<?= $m['id'] ?>">
            <input type="hidden" name="idcours" value="<?= $detail['id'] ?>">
            <button type="submit" class="btn btn-success w-100 rounded-3 fw-700">
              <i class="bi bi-check-circle me-2"></i>Marquer ce module comme terminé
            </button>
          </form>
          <?php else: ?>
          <div class="alert alert-success rounded-3 mb-0 text-center">
            <i class="bi bi-check-circle-fill me-2"></i>
            Module complété le <?= formatDate($m['date_compl'], 'd/m/Y à H:i') ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
</main>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
