<?php
$pageTitle = 'Gestion des formateurs';
include __DIR__ . '/../layouts/header.php';
?>
<main class="py-5 bg-light min-vh-100">
<div class="container">
  <div class="mb-4">
    <h1 class="fw-800 h3 mb-1"><i class="bi bi-person-video3 text-success me-2"></i>Formateurs</h1>
    <p class="text-muted mb-0"><?= count($formateurs) ?> formateur(s) enregistré(s)</p>
  </div>

  <div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th class="ps-4">Formateur</th>
              <th>Statut contrat</th>
              <th>Inscription</th>
              <th>Validation</th>
              <th class="pe-4 text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($formateurs as $f): ?>
            <tr>
              <td class="ps-4">
                <div class="d-flex align-items-center gap-3">
                  <div class="avatar-sm bg-success-soft text-success"><?= strtoupper(substr($f['prenom'],0,1)) ?></div>
                  <div>
                    <div class="fw-600"><?= e($f['prenom'].' '.$f['nom']) ?></div>
                    <div class="text-muted small"><?= e($f['email']) ?></div>
                  </div>
                </div>
              </td>
              <td>
                <span class="badge bg-<?= $f['statut']==='PERMANENT'?'primary':'secondary' ?>-soft text-<?= $f['statut']==='PERMANENT'?'primary':'secondary' ?>">
                  <?= $f['statut'] ?>
                </span>
              </td>
              <td class="text-muted small"><?= formatDate($f['dateinscription']) ?></td>
              <td>
                <?php if ($f['valide']): ?>
                  <span class="badge bg-success"><i class="bi bi-check2 me-1"></i>Validé</span>
                <?php else: ?>
                  <span class="badge bg-warning text-dark"><i class="bi bi-hourglass me-1"></i>En attente</span>
                <?php endif; ?>
              </td>
              <td class="pe-4 text-end">
                <div class="d-flex justify-content-end gap-1">
                  <?php if (!$f['valide']): ?>
                  <form method="POST" action="<?= BASE_URL ?>/controllers/AdminController.php?action=valider_formateur" class="d-inline">
                    <?= csrfField() ?>
                    <input type="hidden" name="id" value="<?= $f['id'] ?>">
                    <button class="btn btn-sm btn-success rounded-3 px-3 fw-600">
                      <i class="bi bi-check2 me-1"></i>Valider
                    </button>
                  </form>
                  <?php endif; ?>
                  <form method="POST" action="<?= BASE_URL ?>/controllers/AdminController.php?action=delete_formateur"
                        onsubmit="return confirm('Supprimer ce formateur ?')" class="d-inline">
                    <?= csrfField() ?>
                    <input type="hidden" name="id" value="<?= $f['id'] ?>">
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
