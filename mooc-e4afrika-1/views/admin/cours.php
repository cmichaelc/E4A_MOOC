<?php
$pageTitle = 'Gestion des cours';
include __DIR__ . '/../layouts/header.php';
?>
<main class="py-5 bg-light min-vh-100">
<div class="container">
  <div class="mb-4">
    <h1 class="fw-800 h3 mb-1"><i class="bi bi-collection-fill text-warning me-2"></i>Tous les cours</h1>
    <p class="text-muted mb-0"><?= count($cours) ?> cours sur la plateforme</p>
  </div>
  <div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-4 border-bottom">
      <input type="text" class="form-control bg-light border-0 rounded-3" id="searchCours"
             placeholder="🔍 Rechercher un cours..." onkeyup="filterTable(this,'coursTable')">
    </div>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="coursTable">
        <thead class="table-light">
          <tr>
            <th class="ps-4">Cours</th>
            <th>Formateur</th>
            <th>Catégorie</th>
            <th>Apprenants</th>
            <th>Statut</th>
            <th class="pe-4 text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cours as $c): ?>
          <tr>
            <td class="ps-4">
              <div class="fw-700"><?= e($c['titre']) ?></div>
              <div class="text-muted small"><?= formatDate($c['datecreation']) ?> · <?= niveauBadge($c['niveau']) ?></div>
            </td>
            <td class="text-muted small"><?= e($c['form_prenom'].' '.$c['form_nom']) ?></td>
            <td><span class="badge bg-light text-dark"><?= e($c['cat_libelle']) ?></span></td>
            <td><i class="bi bi-people me-1 text-muted"></i><?= (int)$c['nb_apprenants'] ?></td>
            <td><?= statutBadge($c['statut']) ?></td>
            <td class="pe-4 text-end">
              <form method="POST" action="<?= BASE_URL ?>/controllers/AdminController.php?action=update_cours" class="d-inline">
                <?= csrfField() ?>
                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                <select name="statut" class="form-select form-select-sm border-0 bg-light rounded-3 d-inline-block w-auto"
                        onchange="this.form.submit()">
                  <?php foreach(['BROUILLON','PUBLIE','ARCHIVE'] as $s): ?>
                  <option value="<?= $s ?>" <?= $c['statut']===$s?'selected':'' ?>><?= $s ?></option>
                  <?php endforeach; ?>
                </select>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</main>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
