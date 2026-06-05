<?php
$pageTitle = e($detail['titre']);
include __DIR__ . '/../layouts/header.php';
?>
<main class="py-5">
  <div class="container">
    <div class="row g-5">
      <!-- Contenu principal -->
      <div class="col-lg-8">
        <nav aria-label="breadcrumb" class="mb-3">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/controllers/CoursController.php?action=catalogue">Catalogue</a></li>
            <li class="breadcrumb-item active"><?= e($detail['titre']) ?></li>
          </ol>
        </nav>
        <div class="d-flex gap-2 mb-3">
          <span class="badge bg-primary-soft text-primary rounded-pill px-3"><?= e($detail['cat_libelle']) ?></span>
          <?= niveauBadge($detail['niveau']) ?>
          <?= statutBadge($detail['statut']) ?>
        </div>
        <h1 class="fw-800 display-6 mb-3"><?= e($detail['titre']) ?></h1>
        <p class="lead text-muted mb-4"><?= e($detail['description']) ?></p>
        <div class="d-flex flex-wrap gap-4 mb-4 text-muted small">
          <span><i class="bi bi-person-circle text-primary me-1"></i><?= e($detail['form_prenom'].' '.$detail['form_nom']) ?></span>
          <span><i class="bi bi-calendar text-primary me-1"></i>Créé le <?= formatDate($detail['datecreation']) ?></span>
          <span><i class="bi bi-clock text-primary me-1"></i><?= $detail['duree_totale'] ?> minutes</span>
          <span><i class="bi bi-book text-primary me-1"></i><?= e($detail['mat_libelle']) ?></span>
        </div>

        <!-- Modules -->
        <div class="card border-0 rounded-4 shadow-sm">
          <div class="card-header bg-white border-0 p-4 pb-0">
            <h4 class="fw-700 mb-0"><i class="bi bi-list-check text-primary me-2"></i>Contenu de la formation</h4>
            <p class="text-muted small mt-1"><?= count($modules) ?> modules</p>
          </div>
          <div class="card-body p-4">
            <div class="accordion accordion-flush" id="modulesAccordion">
              <?php foreach ($modules as $i => $m): ?>
              <div class="accordion-item border rounded-3 mb-2 overflow-hidden">
                <h2 class="accordion-header">
                  <button class="accordion-button <?= $i > 0 ? 'collapsed' : '' ?> fw-600" type="button"
                          data-bs-toggle="collapse" data-bs-target="#mod<?= $m['id'] ?>">
                    <span class="module-num me-3"><?= $i+1 ?></span>
                    <?= e($m['titre']) ?>
                    <span class="ms-auto badge bg-light text-muted rounded-pill me-2 small">
                      <?= count($contenus[$m['id']] ?? []) ?> ressource(s)
                    </span>
                  </button>
                </h2>
                <div id="mod<?= $m['id'] ?>" class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>" data-bs-parent="#modulesAccordion">
                  <div class="accordion-body pt-2">
                    <?php if (empty($contenus[$m['id']])): ?>
                      <p class="text-muted small">Aucun contenu pour ce module.</p>
                    <?php else: ?>
                    <ul class="list-unstyled mb-0">
                      <?php foreach ($contenus[$m['id']] as $ct): ?>
                      <li class="d-flex align-items-center gap-3 py-2 border-bottom">
                        <?php
                        $icons = ['VIDEO'=>'bi-play-circle-fill text-danger','PDF'=>'bi-file-earmark-pdf-fill text-danger','QUIZ'=>'bi-question-circle-fill text-warning','TEXTE'=>'bi-file-text text-secondary'];
                        $icon  = $icons[$ct['type']] ?? 'bi-file text-muted';
                        ?>
                        <i class="bi <?= $icon ?> fs-5"></i>
                        <div class="flex-grow-1">
                          <span class="small fw-500"><?= e($ct['titre']) ?></span>
                          <span class="badge bg-light text-muted ms-2 small"><?= $ct['type'] ?></span>
                        </div>
                        <?php if (!$enrolled): ?>
                          <i class="bi bi-lock text-muted small"></i>
                        <?php else: ?>
                          <span class="small text-muted"><?= $ct['duree'] ?> min</span>
                        <?php endif; ?>
                      </li>
                      <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Sidebar inscription -->
      <div class="col-lg-4">
        <div class="card border-0 rounded-4 shadow sticky-top" style="top:80px">
          <div class="cours-card-img-sm d-flex align-items-center justify-content-center rounded-top-4">
            <i class="bi bi-laptop-fill display-3 text-white opacity-50"></i>
          </div>
          <div class="card-body p-4">
            <div class="text-center mb-4">
              <div class="fs-2 fw-800 text-primary mb-1">
                <?= $detail['montant'] > 0 ? formatMontant($detail['montant']) : '<span class="text-success">Gratuit</span>' ?>
              </div>
            </div>
            <?php if (!isLoggedIn()): ?>
              <a href="<?= BASE_URL ?>/controllers/AuthController.php?action=register"
                 class="btn btn-primary w-100 btn-lg rounded-3 fw-700 mb-3">
                <i class="bi bi-person-plus-fill me-2"></i>S'inscrire pour accéder
              </a>
              <a href="<?= BASE_URL ?>/controllers/AuthController.php?action=login"
                 class="btn btn-outline-secondary w-100 rounded-3">Déjà inscrit ? Connexion</a>
            <?php elseif ($enrolled): ?>
              <div class="alert alert-success rounded-3 text-center mb-3">
                <i class="bi bi-check-circle-fill me-2"></i>Vous suivez ce cours
              </div>
              <a href="<?= BASE_URL ?>/controllers/ApprenantController.php?action=progression&idcours=<?= $detail['id'] ?>"
                 class="btn btn-success w-100 btn-lg rounded-3 fw-700">
                <i class="bi bi-play-fill me-2"></i>Continuer la formation
              </a>
            <?php else: ?>
              <form method="POST" action="<?= BASE_URL ?>/controllers/CoursController.php?action=enroll">
                <?= csrfField() ?>
                <input type="hidden" name="idcours" value="<?= $detail['id'] ?>">
                <button type="submit" class="btn btn-primary w-100 btn-lg rounded-3 fw-700 mb-3">
                  <i class="bi bi-bookmark-plus-fill me-2"></i>S'inscrire à ce cours
                </button>
              </form>
            <?php endif; ?>
            <ul class="list-unstyled mt-3 text-muted small">
              <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Accès illimité</li>
              <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i><?= count($modules) ?> modules de formation</li>
              <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Certificat de réussite</li>
              <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Accès mobile</li>
              <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Quiz et évaluations</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
