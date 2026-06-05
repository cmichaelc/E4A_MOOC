<?php
$pageTitle = 'Filières, Options & Matières';
include __DIR__ . '/../layouts/header.php';
?>
<main class="py-5 bg-light min-vh-100">
<div class="container">
  <h1 class="fw-800 h3 mb-4"><i class="bi bi-diagram-3 text-info me-2"></i>Gestion des référentiels</h1>

  <div class="row g-4">

    <!-- Filières -->
    <div class="col-lg-4">
      <div class="card border-0 rounded-4 shadow-sm h-100">
        <div class="card-header bg-white border-0 p-4 pb-0">
          <h6 class="fw-700 mb-0"><i class="bi bi-building text-primary me-2"></i>Filières</h6>
        </div>
        <div class="card-body p-4">
          <ul class="list-unstyled mb-3">
            <?php foreach ($filieres as $f): ?>
            <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
              <div>
                <span class="fw-600 small"><?= e($f['libelle']) ?></span>
                <span class="text-muted x-small ms-2"><?= e($f['codefil']) ?></span>
              </div>
              <form method="POST" action="<?= BASE_URL ?>/controllers/AdminController.php?action=delete_filiere"
                    onsubmit="return confirm('Supprimer ?')">
                <?= csrfField() ?>
                <input type="hidden" name="codefil" value="<?= e($f['codefil']) ?>">
                <button class="btn btn-link btn-sm text-danger p-0"><i class="bi bi-trash-fill"></i></button>
              </form>
            </li>
            <?php endforeach; ?>
          </ul>
          <form method="POST" action="<?= BASE_URL ?>/controllers/AdminController.php?action=add_filiere" class="d-flex gap-2">
            <?= csrfField() ?>
            <input type="text" class="form-control form-control-sm bg-light border-0 rounded-3" name="codefil" placeholder="Code (ex: FIL04)" required maxlength="10">
            <input type="text" class="form-control form-control-sm bg-light border-0 rounded-3" name="libelle" placeholder="Libellé" required>
            <button class="btn btn-sm btn-primary rounded-3 px-3"><i class="bi bi-plus"></i></button>
          </form>
        </div>
      </div>
    </div>

    <!-- Catégories -->
    <div class="col-lg-4">
      <div class="card border-0 rounded-4 shadow-sm h-100">
        <div class="card-header bg-white border-0 p-4 pb-0">
          <h6 class="fw-700 mb-0"><i class="bi bi-tags text-warning me-2"></i>Catégories</h6>
        </div>
        <div class="card-body p-4">
          <ul class="list-unstyled mb-3">
            <?php foreach ($categories as $c): ?>
            <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
              <div>
                <span class="fw-600 small"><?= e($c['libelle']) ?></span>
                <span class="text-muted x-small ms-2"><?= e($c['codcat']) ?></span>
              </div>
              <form method="POST" action="<?= BASE_URL ?>/controllers/AdminController.php?action=delete_categorie"
                    onsubmit="return confirm('Supprimer ?')">
                <?= csrfField() ?>
                <input type="hidden" name="codcat" value="<?= e($c['codcat']) ?>">
                <button class="btn btn-link btn-sm text-danger p-0"><i class="bi bi-trash-fill"></i></button>
              </form>
            </li>
            <?php endforeach; ?>
          </ul>
          <form method="POST" action="<?= BASE_URL ?>/controllers/AdminController.php?action=add_categorie" class="d-flex gap-2">
            <?= csrfField() ?>
            <input type="text" class="form-control form-control-sm bg-light border-0 rounded-3" name="codcat" placeholder="Code" required maxlength="10">
            <input type="text" class="form-control form-control-sm bg-light border-0 rounded-3" name="libelle" placeholder="Libellé" required>
            <button class="btn btn-sm btn-warning rounded-3 px-3"><i class="bi bi-plus"></i></button>
          </form>
        </div>
      </div>
    </div>

    <!-- Matières -->
    <div class="col-lg-4">
      <div class="card border-0 rounded-4 shadow-sm h-100">
        <div class="card-header bg-white border-0 p-4 pb-0">
          <h6 class="fw-700 mb-0"><i class="bi bi-book text-success me-2"></i>Matières</h6>
        </div>
        <div class="card-body p-4">
          <ul class="list-unstyled mb-3">
            <?php foreach ($matieres as $m): ?>
            <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
              <div>
                <span class="fw-600 small"><?= e($m['libelle']) ?></span>
                <span class="text-muted x-small ms-2"><?= e($m['codmat']) ?></span>
              </div>
              <form method="POST" action="<?= BASE_URL ?>/controllers/AdminController.php?action=delete_matiere"
                    onsubmit="return confirm('Supprimer ?')">
                <?= csrfField() ?>
                <input type="hidden" name="codmat" value="<?= e($m['codmat']) ?>">
                <button class="btn btn-link btn-sm text-danger p-0"><i class="bi bi-trash-fill"></i></button>
              </form>
            </li>
            <?php endforeach; ?>
          </ul>
          <form method="POST" action="<?= BASE_URL ?>/controllers/AdminController.php?action=add_matiere" class="d-flex gap-2">
            <?= csrfField() ?>
            <input type="text" class="form-control form-control-sm bg-light border-0 rounded-3" name="codmat" placeholder="Code" required maxlength="10">
            <input type="text" class="form-control form-control-sm bg-light border-0 rounded-3" name="libelle" placeholder="Libellé" required>
            <button class="btn btn-sm btn-success rounded-3 px-3"><i class="bi bi-plus"></i></button>
          </form>
        </div>
      </div>
    </div>

    <!-- Options -->
    <div class="col-12">
      <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-header bg-white border-0 p-4 pb-0">
          <h6 class="fw-700 mb-0"><i class="bi bi-list-ul text-danger me-2"></i>Options</h6>
        </div>
        <div class="card-body p-4">
          <div class="row g-3 mb-3">
            <?php foreach ($options as $o): ?>
            <div class="col-md-4">
              <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                <div>
                  <div class="fw-600 small"><?= e($o['libelle']) ?></div>
                  <div class="text-muted x-small"><?= e($o['fil_libelle']) ?> · <?= e($o['codopt']) ?></div>
                </div>
                <form method="POST" action="<?= BASE_URL ?>/controllers/AdminController.php?action=delete_option"
                      onsubmit="return confirm('Supprimer ?')">
                  <?= csrfField() ?>
                  <input type="hidden" name="codopt" value="<?= e($o['codopt']) ?>">
                  <button class="btn btn-link btn-sm text-danger p-0"><i class="bi bi-trash-fill"></i></button>
                </form>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <form method="POST" action="<?= BASE_URL ?>/controllers/AdminController.php?action=add_option" class="row g-2">
            <?= csrfField() ?>
            <div class="col-md-2"><input type="text" class="form-control bg-light border-0 rounded-3" name="codopt" placeholder="Code option" required maxlength="10"></div>
            <div class="col-md-5"><input type="text" class="form-control bg-light border-0 rounded-3" name="libelle" placeholder="Libellé de l'option" required></div>
            <div class="col-md-3">
              <select class="form-select bg-light border-0 rounded-3" name="codefil" required>
                <option value="">-- Filière --</option>
                <?php foreach ($filieres as $f): ?>
                <option value="<?= e($f['codefil']) ?>"><?= e($f['libelle']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-2"><button class="btn btn-danger w-100 rounded-3"><i class="bi bi-plus me-1"></i>Ajouter</button></div>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>
</main>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
