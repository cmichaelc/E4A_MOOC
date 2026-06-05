<?php
$pageTitle = 'Mes devoirs';
include __DIR__ . '/../layouts/header.php';
?>
<main class="py-5 bg-light min-vh-100">
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="fw-800 h3 mb-1"><i class="bi bi-file-earmark-text text-primary me-2"></i>Mes devoirs</h1>
      <p class="text-muted mb-0"><?= count($devoirs) ?> devoir(s) assigné(s)</p>
    </div>
  </div>

  <?php if (empty($devoirs)): ?>
  <div class="card border-0 rounded-4 shadow-sm text-center p-5">
    <i class="bi bi-file-earmark-check display-1 text-muted opacity-25"></i>
    <h4 class="mt-3 text-muted">Aucun devoir pour le moment</h4>
  </div>
  <?php else: ?>
  <div class="row g-4">
    <?php foreach ($devoirs as $d):
      $today     = date('Y-m-d');
      $expired   = $d['datefin'] < $today;
      $submitted = !empty($d['soumission_id']);
      $daysLeft  = (int)((strtotime($d['datefin']) - strtotime($today)) / 86400);
    ?>
    <div class="col-md-6">
      <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden
        <?= $submitted ? 'border-start border-4 border-success' : ($expired ? 'border-start border-4 border-danger' : 'border-start border-4 border-warning') ?>">
        <div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <h5 class="fw-700 mb-1"><?= e($d['titre']) ?></h5>
              <span class="badge bg-light text-muted"><?= e($d['mat_libelle']) ?></span>
            </div>
            <?php if ($submitted): ?>
              <span class="badge bg-success rounded-pill"><i class="bi bi-check2 me-1"></i>Soumis</span>
            <?php elseif ($expired): ?>
              <span class="badge bg-danger rounded-pill"><i class="bi bi-x me-1"></i>Expiré</span>
            <?php else: ?>
              <span class="badge bg-warning text-dark rounded-pill">
                <i class="bi bi-clock me-1"></i><?= $daysLeft ?>j restants
              </span>
            <?php endif; ?>
          </div>
          <p class="text-muted small mb-3"><?= e(truncate($d['consigne'], 120)) ?></p>
          <div class="d-flex gap-3 text-muted small mb-3">
            <span><i class="bi bi-calendar-event me-1 text-primary"></i>Du <?= formatDate($d['datedebut']) ?></span>
            <span><i class="bi bi-calendar-x me-1 text-danger"></i>au <?= formatDate($d['datefin']) ?></span>
          </div>
          <div class="text-muted small mb-3">
            <i class="bi bi-person-circle me-1 text-primary"></i>
            Formateur : <?= e($d['form_prenom'].' '.$d['form_nom']) ?>
          </div>

          <?php if ($submitted): ?>
          <div class="alert alert-success rounded-3 mb-0 p-3">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <strong><i class="bi bi-check-circle-fill me-2"></i>Devoir soumis</strong>
                <div class="small text-muted mt-1">le <?= formatDate($d['date_soumis'], 'd/m/Y à H:i') ?></div>
              </div>
              <?php if ($d['note'] !== null): ?>
                <div class="text-end">
                  <div class="fs-3 fw-800 text-success"><?= number_format($d['note'],1) ?></div>
                  <div class="small text-muted">/20</div>
                </div>
              <?php else: ?>
                <span class="badge bg-secondary">En attente de note</span>
              <?php endif; ?>
            </div>
            <?php if ($d['feedback']): ?>
              <hr class="my-2">
              <div class="small"><strong>Feedback :</strong> <?= e($d['feedback']) ?></div>
            <?php endif; ?>
          </div>

          <?php elseif (!$expired): ?>
          <form method="POST" action="<?= BASE_URL ?>/controllers/ApprenantController.php?action=soumettre"
                enctype="multipart/form-data" class="mt-2" id="form-devoir-<?= $d['id'] ?>">
            <?= csrfField() ?>
            <input type="hidden" name="iddevoir" value="<?= $d['id'] ?>">
            <div class="mb-3">
              <label class="form-label fw-500 small">Déposer votre fichier *</label>
              <input type="file" class="form-control bg-light border-0 rounded-3 small"
                     name="fichier" id="fichier-<?= $d['id'] ?>"
                     accept=".pdf,.doc,.docx,.zip,.jpg,.png" required>
              <div class="form-text text-muted">PDF, DOC, DOCX, ZIP, JPG, PNG — Max. 5 Mo</div>
            </div>
            <button type="submit" class="btn btn-primary w-100 rounded-3 fw-600">
              <i class="bi bi-upload me-2"></i>Soumettre le devoir
            </button>
          </form>
          <?php else: ?>
          <div class="alert alert-danger rounded-3 mb-0">
            <i class="bi bi-exclamation-triangle me-2"></i>La date limite est dépassée.
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
</main>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
