<?php
$pageTitle = 'Gestion des devoirs';
include __DIR__ . '/../layouts/header.php';
?>
<main class="py-5 bg-light min-vh-100">
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-800 h3 mb-0"><i class="bi bi-file-earmark-text text-primary me-2"></i>Mes devoirs</h1>
  </div>
  <div class="row g-4">
    <!-- Liste devoirs -->
    <div class="col-lg-8">
      <?php if (empty($devoirs)): ?>
      <div class="card border-0 rounded-4 shadow-sm text-center p-5">
        <i class="bi bi-file-earmark-plus display-3 text-muted opacity-25"></i>
        <h5 class="mt-3 text-muted">Aucun devoir créé</h5>
      </div>
      <?php else: ?>
      <div class="d-flex flex-column gap-3">
        <?php foreach ($devoirs as $d): ?>
        <div class="card border-0 rounded-4 shadow-sm">
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <div>
                <h5 class="fw-700 mb-1"><?= e($d['titre']) ?></h5>
                <div class="d-flex gap-2">
                  <span class="badge bg-light text-muted"><?= e($d['mat_libelle']) ?></span>
                  <span class="badge bg-info-soft text-info"><?= e($d['opt_libelle']) ?></span>
                </div>
              </div>
              <span class="badge bg-primary-soft text-primary">
                <i class="bi bi-upload me-1"></i><?= (int)$d['nb_soumissions'] ?> soumission(s)
              </span>
            </div>
            <p class="text-muted small mb-2"><?= e(truncate($d['consigne'], 100)) ?></p>
            <div class="d-flex gap-3 text-muted small">
              <span><i class="bi bi-calendar-event me-1 text-primary"></i>Du <?= formatDate($d['datedebut']) ?></span>
              <span><i class="bi bi-calendar-x me-1 text-danger"></i>au <?= formatDate($d['datefin']) ?></span>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- Créer devoir -->
    <div class="col-lg-4">
      <div class="card border-0 rounded-4 shadow-sm sticky-top" style="top:80px">
        <div class="card-header bg-white border-0 p-4 pb-0">
          <h6 class="fw-700 mb-0"><i class="bi bi-plus-circle text-primary me-2"></i>Créer un devoir</h6>
        </div>
        <div class="card-body p-4">
          <form method="POST" action="<?= BASE_URL ?>/controllers/FormateurController.php?action=create_devoir">
            <?= csrfField() ?>
            <div class="mb-3">
              <label class="form-label fw-500 small">Titre *</label>
              <input type="text" class="form-control bg-light border-0 rounded-3" name="titre" required placeholder="Ex: Exercice Python">
            </div>
            <div class="mb-3">
              <label class="form-label fw-500 small">Consigne *</label>
              <textarea class="form-control bg-light border-0 rounded-3" name="consigne" rows="3" required placeholder="Instructions détaillées..."></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label fw-500 small">Matière *</label>
              <select class="form-select bg-light border-0 rounded-3" name="codmat" required>
                <option value="">-- Choisir --</option>
                <?php foreach ($matieres as $m): ?>
                <option value="<?= e($m['codmat']) ?>"><?= e($m['libelle']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label fw-500 small">Option cible *</label>
              <select class="form-select bg-light border-0 rounded-3" name="codopt" required>
                <option value="">-- Choisir --</option>
                <?php foreach ($options as $o): ?>
                <option value="<?= e($o['codopt']) ?>"><?= e($o['libelle']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label fw-500 small">Date début *</label>
                <input type="date" class="form-control bg-light border-0 rounded-3" name="datedebut" required value="<?= date('Y-m-d') ?>">
              </div>
              <div class="col-6">
                <label class="form-label fw-500 small">Date limite *</label>
                <input type="date" class="form-control bg-light border-0 rounded-3" name="datefin" required value="<?= date('Y-m-d', strtotime('+14 days')) ?>">
              </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 rounded-3 fw-600">
              <i class="bi bi-plus-circle me-2"></i>Créer le devoir
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</main>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
