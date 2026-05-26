<?php
$pageTitle = 'Gestion des apprenants';
include __DIR__ . '/../layouts/header.php';
?>
<main class="py-5 bg-light min-vh-100">
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="fw-800 h3 mb-1"><i class="bi bi-people-fill text-primary me-2"></i>Apprenants</h1>
      <p class="text-muted mb-0"><?= count($apprenants) ?> apprenant(s) inscrit(s)</p>
    </div>
    <a href="<?= BASE_URL ?>/controllers/AdminController.php?action=export_csv" class="btn btn-success rounded-pill px-4">
      <i class="bi bi-file-earmark-spreadsheet me-2"></i>Exporter CSV
    </a>
  </div>

  <div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-0">
      <!-- Filtre rapide -->
      <div class="p-4 border-bottom">
        <input type="text" class="form-control bg-light border-0 rounded-3" id="searchTable"
               placeholder="🔍 Rechercher par nom, email..." onkeyup="filterTable(this,'appTable')">
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="appTable">
          <thead class="table-light">
            <tr>
              <th class="ps-4">#</th>
              <th>Apprenant</th>
              <th>Option</th>
              <th>Inscription</th>
              <th>Statut</th>
              <th class="pe-4 text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($apprenants as $a): ?>
            <tr>
              <td class="ps-4 text-muted small"><?= $a['id'] ?></td>
              <td>
                <div class="d-flex align-items-center gap-3">
                  <div class="avatar-sm"><?= strtoupper(substr($a['prenom'],0,1)) ?></div>
                  <div>
                    <div class="fw-600"><?= e($a['prenom'].' '.$a['nom']) ?></div>
                    <div class="text-muted small"><?= e($a['email']) ?></div>
                  </div>
                </div>
              </td>
              <td><span class="badge bg-light text-dark"><?= e(truncate($a['opt_libelle'],22)) ?></span></td>
              <td class="text-muted small"><?= formatDate($a['dateinscription']) ?></td>
              <td><?= statutBadge($a['statut']) ?></td>
              <td class="pe-4 text-end">
                <div class="d-flex justify-content-end gap-1">
                  <!-- Changer statut -->
                  <form method="POST" action="<?= BASE_URL ?>/controllers/AdminController.php?action=update_apprenant" class="d-inline">
                    <?= csrfField() ?>
                    <input type="hidden" name="id" value="<?= $a['id'] ?>">
                    <select name="statut" class="form-select form-select-sm border-0 bg-light rounded-3 d-inline-block w-auto"
                            onchange="this.form.submit()" title="Changer le statut">
                      <?php foreach(['ACTIF','SUSPENDU','TERMINE','ABANDON'] as $s): ?>
                      <option value="<?= $s ?>" <?= $a['statut']===$s?'selected':'' ?>><?= $s ?></option>
                      <?php endforeach; ?>
                    </select>
                  </form>
                  <form method="POST" action="<?= BASE_URL ?>/controllers/AdminController.php?action=delete_apprenant"
                        onsubmit="return confirm('Supprimer cet apprenant ?')" class="d-inline">
                    <?= csrfField() ?>
                    <input type="hidden" name="id" value="<?= $a['id'] ?>">
                    <button class="btn btn-sm btn-outline-danger rounded-3"><i class="bi bi-trash"></i></button>
                  </form>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</main>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
