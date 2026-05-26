<?php
$pageTitle = 'Notation des devoirs';
include __DIR__ . '/../layouts/header.php';
?>
<main class="py-5 bg-light min-vh-100">
<div class="container">
  <h1 class="fw-800 h3 mb-4"><i class="bi bi-pencil-square text-primary me-2"></i>Notation des soumissions</h1>

  <?php if (empty($soumissions)): ?>
  <div class="card border-0 rounded-4 shadow-sm text-center p-5">
    <i class="bi bi-inbox display-3 text-muted opacity-25"></i>
    <h5 class="mt-3 text-muted">Aucune soumission à corriger</h5>
  </div>
  <?php else: ?>
  <div class="d-flex flex-column gap-3">
    <?php foreach ($soumissions as $s): ?>
    <div class="card border-0 rounded-4 shadow-sm overflow-hidden
      <?= $s['note'] !== null ? 'border-start border-4 border-success' : 'border-start border-4 border-warning' ?>">
      <div class="card-body p-4">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h6 class="fw-700 mb-1"><?= e($s['devoir_titre']) ?></h6>
            <div class="d-flex gap-2 mb-2">
              <span class="badge bg-light text-muted small"><?= e($s['mat_libelle']) ?></span>
            </div>
            <div class="d-flex align-items-center gap-2 mb-1">
              <div class="avatar-xs"><?= strtoupper(substr($s['app_prenom'], 0, 1)) ?></div>
              <span class="small fw-600"><?= e($s['app_prenom'].' '.$s['app_nom']) ?></span>
            </div>
            <div class="text-muted small">
              <i class="bi bi-clock me-1"></i>Soumis le <?= formatDate($s['date_soumis'], 'd/m/Y à H:i') ?>
            </div>
            <div class="mt-2">
              <a href="<?= BASE_URL ?>/uploads/devoirs/<?= e($s['fichier']) ?>" target="_blank"
                 class="btn btn-sm btn-outline-primary rounded-pill">
                <i class="bi bi-download me-1"></i>Télécharger le fichier
              </a>
            </div>
          </div>
          <div class="col-md-6">
            <?php if ($s['note'] !== null): ?>
            <div class="text-center mb-2">
              <span class="display-6 fw-800 text-success"><?= number_format($s['note'],1) ?></span>
              <span class="text-muted">/20</span>
            </div>
            <?php if ($s['feedback']): ?>
              <p class="text-muted small"><i class="bi bi-chat-left-text me-1"></i><?= e($s['feedback']) ?></p>
            <?php endif; ?>
            <button class="btn btn-sm btn-outline-secondary rounded-pill" onclick="document.getElementById('edit-<?= $s['id'] ?>').classList.toggle('d-none')">Modifier la note</button>
            <div class="d-none mt-2" id="edit-<?= $s['id'] ?>">
            <?php endif; ?>
            <form method="POST" action="<?= BASE_URL ?>/controllers/FormateurController.php?action=noter" class="<?= $s['note'] !== null ? '' : '' ?>">
              <?= csrfField() ?>
              <input type="hidden" name="idsoumission" value="<?= $s['id'] ?>">
              <div class="row g-2">
                <div class="col-4">
                  <label class="form-label fw-500 small">Note /20 *</label>
                  <input type="number" class="form-control bg-light border-0 rounded-3" name="note"
                         min="0" max="20" step="0.5" required value="<?= $s['note'] ?? '' ?>" placeholder="Ex: 15">
                </div>
                <div class="col-8">
                  <label class="form-label fw-500 small">Feedback</label>
                  <input type="text" class="form-control bg-light border-0 rounded-3" name="feedback"
                         placeholder="Commentaire..." value="<?= e($s['feedback'] ?? '') ?>">
                </div>
              </div>
              <button type="submit" class="btn btn-success btn-sm rounded-3 mt-2 px-4 fw-600">
                <i class="bi bi-check2 me-1"></i>Enregistrer
              </button>
            </form>
            <?php if ($s['note'] !== null): ?></div><?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
</main>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
