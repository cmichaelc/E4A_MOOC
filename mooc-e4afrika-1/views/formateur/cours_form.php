<?php
$pageTitle = $editCours ? 'Modifier le cours' : 'Créer un cours';
include __DIR__ . '/../layouts/header.php';
?>
<main class="py-5 bg-light min-vh-100">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="d-flex align-items-center gap-3 mb-4">
        <a href="<?= BASE_URL ?>/controllers/FormateurController.php?action=mes_cours" class="btn btn-outline-secondary rounded-3">
          <i class="bi bi-arrow-left"></i>
        </a>
        <div>
          <h1 class="fw-800 h4 mb-0">
            <?= $editCours ? '<i class="bi bi-pencil-fill text-warning me-2"></i>Modifier le cours' : '<i class="bi bi-plus-circle-fill text-primary me-2"></i>Créer un nouveau cours' ?>
          </h1>
          <p class="text-muted small mb-0">Renseignez les informations de la formation</p>
        </div>
      </div>

      <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-4 p-md-5">
          <form method="POST" id="coursForm">
            <?= csrfField() ?>
            <div class="mb-4">
              <label class="form-label fw-600">Titre du cours *</label>
              <input type="text" class="form-control bg-light border-0 rounded-3" name="titre"
                     placeholder="Ex : Introduction à Python" required
                     value="<?= e($editCours['titre'] ?? '') ?>">
            </div>
            <div class="mb-4">
              <label class="form-label fw-600">Description *</label>
              <textarea class="form-control bg-light border-0 rounded-3" name="description"
                        rows="5" placeholder="Décrivez le contenu et les objectifs de votre formation..." required><?= e($editCours['description'] ?? '') ?></textarea>
            </div>
            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label class="form-label fw-600">Catégorie *</label>
                <select class="form-select bg-light border-0 rounded-3" name="codcat" required>
                  <option value="">-- Choisir --</option>
                  <?php foreach ($categories as $cat): ?>
                  <option value="<?= e($cat['codcat']) ?>"
                    <?= ($editCours['codcat'] ?? '') === $cat['codcat'] ? 'selected' : '' ?>>
                    <?= e($cat['libelle']) ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-600">Matière *</label>
                <select class="form-select bg-light border-0 rounded-3" name="codmat" required>
                  <option value="">-- Choisir --</option>
                  <?php foreach ($matieres as $m): ?>
                  <option value="<?= e($m['codmat']) ?>"
                    <?= ($editCours['codmat'] ?? '') === $m['codmat'] ? 'selected' : '' ?>>
                    <?= e($m['libelle']) ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="row g-3 mb-4">
              <div class="col-md-4">
                <label class="form-label fw-600">Niveau *</label>
                <select class="form-select bg-light border-0 rounded-3" name="niveau" required>
                  <?php foreach (['DEBUTANT','INTERMEDIAIRE','AVANCE'] as $niv): ?>
                  <option value="<?= $niv ?>" <?= ($editCours['niveau'] ?? 'DEBUTANT') === $niv ? 'selected' : '' ?>><?= $niv ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label fw-600">Montant (FCFA)</label>
                <input type="number" class="form-control bg-light border-0 rounded-3" name="montant"
                       min="0" step="500" placeholder="0 = Gratuit"
                       value="<?= $editCours['montant'] ?? 0 ?>">
              </div>
              <div class="col-md-4">
                <label class="form-label fw-600">Durée totale (min)</label>
                <input type="number" class="form-control bg-light border-0 rounded-3" name="duree_totale"
                       min="0" placeholder="ex: 480"
                       value="<?= $editCours['duree_totale'] ?? 0 ?>">
              </div>
            </div>
            <?php if ($editCours): ?>
            <div class="mb-4">
              <label class="form-label fw-600">Statut</label>
              <select class="form-select bg-light border-0 rounded-3" name="statut">
                <?php foreach (['BROUILLON','PUBLIE','ARCHIVE'] as $s): ?>
                <option value="<?= $s ?>" <?= ($editCours['statut'] ?? '') === $s ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <?php endif; ?>
            <div class="d-flex gap-3 justify-content-end pt-3 border-top">
              <a href="<?= BASE_URL ?>/controllers/FormateurController.php?action=mes_cours"
                 class="btn btn-outline-secondary rounded-3 px-4">Annuler</a>
              <button type="submit" class="btn btn-primary rounded-3 px-5 fw-700">
                <i class="bi bi-<?= $editCours ? 'save' : 'plus-circle' ?> me-2"></i>
                <?= $editCours ? 'Enregistrer' : 'Créer le cours' ?>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</main>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
