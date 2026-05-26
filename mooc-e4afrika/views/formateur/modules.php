<?php
$pageTitle = 'Modules — ' . e($detail['titre']);
include __DIR__ . '/../layouts/header.php';
?>
<main class="py-5 bg-light min-vh-100">
<div class="container">
  <div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?= BASE_URL ?>/controllers/FormateurController.php?action=mes_cours" class="btn btn-outline-secondary rounded-3">
      <i class="bi bi-arrow-left"></i>
    </a>
    <div>
      <h1 class="fw-800 h4 mb-0"><i class="bi bi-list-check text-primary me-2"></i>Modules — <?= e($detail['titre']) ?></h1>
      <p class="text-muted small mb-0"><?= count($modules) ?> module(s) — Gérez le contenu de votre cours</p>
    </div>
  </div>

  <div class="row g-4">
    <!-- Modules existants -->
    <div class="col-lg-8">
      <?php if (empty($modules)): ?>
      <div class="card border-0 rounded-4 shadow-sm text-center p-5">
        <i class="bi bi-collection display-3 text-muted opacity-25"></i>
        <h5 class="mt-3 text-muted">Aucun module créé</h5>
        <p class="text-muted small">Ajoutez votre premier module dans le formulaire ci-contre.</p>
      </div>
      <?php else: ?>
      <?php foreach ($modules as $i => $m): ?>
      <div class="card border-0 rounded-4 shadow-sm mb-3">
        <div class="card-header bg-white border-0 p-4 pb-3 d-flex justify-content-between align-items-center">
          <div>
            <span class="badge bg-primary rounded-pill me-2">Module <?= $m['ordre'] ?></span>
            <span class="fw-700"><?= e($m['titre']) ?></span>
          </div>
          <span class="badge bg-light text-muted"><?= count($contenus[$m['id']] ?? []) ?> contenu(s)</span>
        </div>
        <div class="card-body p-4">
          <!-- Contenus existants -->
          <?php if (!empty($contenus[$m['id']])): ?>
          <ul class="list-unstyled mb-3">
            <?php foreach ($contenus[$m['id']] as $ct): ?>
            <li class="d-flex align-items-center gap-2 py-2 border-bottom">
              <i class="bi bi-<?= $ct['type']==='VIDEO'?'play-circle':'file-earmark' ?>-fill text-<?= $ct['type']==='VIDEO'?'danger':'secondary' ?>"></i>
              <span class="small flex-grow-1"><?= e($ct['titre']) ?></span>
              <span class="badge bg-light text-muted small"><?= $ct['type'] ?></span>
              <span class="text-muted small"><?= $ct['duree'] ?> min</span>
              <form method="POST" action="<?= BASE_URL ?>/controllers/FormateurController.php?action=delete_contenu" class="d-inline"
                    onsubmit="return confirm('Supprimer ?')">
                <?= csrfField() ?>
                <input type="hidden" name="idcontenu" value="<?= $ct['id'] ?>">
                <input type="hidden" name="idcours" value="<?= $detail['id'] ?>">
                <button class="btn btn-sm btn-link text-danger p-0"><i class="bi bi-trash"></i></button>
              </form>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>

          <!-- Ajouter contenu -->
          <details class="mt-2">
            <summary class="btn btn-sm btn-outline-primary rounded-3 mb-3">
              <i class="bi bi-plus me-1"></i>Ajouter un contenu
            </summary>
            <form method="POST" action="<?= BASE_URL ?>/controllers/FormateurController.php?action=add_contenu" class="p-3 bg-light rounded-3 mt-2">
              <?= csrfField() ?>
              <input type="hidden" name="idmodule" value="<?= $m['id'] ?>">
              <input type="hidden" name="idcours" value="<?= $detail['id'] ?>">
              <div class="row g-2 mb-2">
                <div class="col-md-6">
                  <input type="text" class="form-control form-control-sm border-0 rounded-3" name="titre" placeholder="Titre du contenu" required>
                </div>
                <div class="col-md-3">
                  <select class="form-select form-select-sm border-0 rounded-3" name="type">
                    <option value="VIDEO">VIDEO</option>
                    <option value="PDF">PDF</option>
                    <option value="QUIZ">QUIZ</option>
                    <option value="TEXTE">TEXTE</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <input type="number" class="form-control form-control-sm border-0 rounded-3" name="duree" placeholder="min" min="0">
                </div>
              </div>
              <div class="mb-2">
                <input type="text" class="form-control form-control-sm border-0 rounded-3" name="urlcontenu" placeholder="URL vidéo ou chemin PDF" required>
              </div>
              <button class="btn btn-sm btn-primary rounded-3 px-4">Ajouter</button>
            </form>
          </details>
        </div>
      </div>
      <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Ajouter module -->
    <div class="col-lg-4">
      <div class="card border-0 rounded-4 shadow-sm sticky-top" style="top:80px">
        <div class="card-header bg-white border-0 p-4 pb-0">
          <h6 class="fw-700 mb-0"><i class="bi bi-plus-circle text-primary me-2"></i>Ajouter un module</h6>
        </div>
        <div class="card-body p-4">
          <form method="POST" action="<?= BASE_URL ?>/controllers/FormateurController.php?action=add_module">
            <?= csrfField() ?>
            <input type="hidden" name="idcours" value="<?= $detail['id'] ?>">
            <div class="mb-3">
              <label class="form-label fw-500 small">Titre du module *</label>
              <input type="text" class="form-control bg-light border-0 rounded-3" name="titre"
                     placeholder="Ex : Introduction au langage" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-500 small">Ordre d'affichage</label>
              <input type="number" class="form-control bg-light border-0 rounded-3" name="ordre"
                     min="1" value="<?= count($modules)+1 ?>">
            </div>
            <button type="submit" class="btn btn-primary w-100 rounded-3 fw-600">
              <i class="bi bi-plus-circle me-2"></i>Créer le module
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</main>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
