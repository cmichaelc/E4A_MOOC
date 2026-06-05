<?php
$pageTitle = 'Mes cours';
include __DIR__ . '/../layouts/header.php';
?>
<main class="py-5 bg-light min-vh-100">
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-800 h3 mb-0"><i class="bi bi-collection text-primary me-2"></i>Mes cours</h1>
    <a href="<?= BASE_URL ?>/controllers/FormateurController.php?action=create_cours"
       class="btn btn-primary rounded-pill px-4 fw-600">
      <i class="bi bi-plus-circle me-2"></i>Créer un cours
    </a>
  </div>

  <?php if (empty($mesCours)): ?>
  <div class="card border-0 rounded-4 shadow-sm text-center p-5">
    <i class="bi bi-journal-plus display-1 text-muted opacity-25"></i>
    <h4 class="mt-3 text-muted">Aucun cours créé</h4>
    <a href="<?= BASE_URL ?>/controllers/FormateurController.php?action=create_cours"
       class="btn btn-primary rounded-pill px-5 mt-3">Créer mon premier cours</a>
  </div>
  <?php else: ?>
  <div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th class="ps-4">Titre</th>
              <th>Catégorie</th>
              <th>Niveau</th>
              <th>Statut</th>
              <th>Apprenants</th>
              <th>Montant</th>
              <th class="pe-4 text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($mesCours as $c): ?>
            <tr>
              <td class="ps-4">
                <div class="fw-700"><?= e($c['titre']) ?></div>
                <div class="text-muted small"><?= formatDate($c['datecreation']) ?></div>
              </td>
              <td><span class="badge bg-light text-dark"><?= e($c['cat_libelle']) ?></span></td>
              <td><?= niveauBadge($c['niveau']) ?></td>
              <td><?= statutBadge($c['statut']) ?></td>
              <td><i class="bi bi-people text-muted me-1"></i><?= (int)$c['nb_apprenants'] ?></td>
              <td class="fw-600 text-primary"><?= $c['montant'] > 0 ? formatMontant($c['montant']) : '<span class="text-success">Gratuit</span>' ?></td>
              <td class="pe-4 text-end">
                <div class="d-flex justify-content-end gap-1">
                  <a href="<?= BASE_URL ?>/controllers/FormateurController.php?action=modules&idcours=<?= $c['id'] ?>"
                     class="btn btn-sm btn-outline-primary rounded-3" title="Modules">
                    <i class="bi bi-list-check"></i>
                  </a>
                  <a href="<?= BASE_URL ?>/controllers/FormateurController.php?action=edit_cours&idcours=<?= $c['id'] ?>"
                     class="btn btn-sm btn-outline-secondary rounded-3" title="Modifier">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form method="POST" action="<?= BASE_URL ?>/controllers/FormateurController.php?action=delete_cours"
                        onsubmit="return confirm('Supprimer ce cours ?')" class="d-inline">
                    <?= csrfField() ?>
                    <input type="hidden" name="idcours" value="<?= $c['id'] ?>">
                    <button class="btn btn-sm btn-outline-danger rounded-3" title="Supprimer">
                      <i class="bi bi-trash"></i>
                    </button>
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
  <?php endif; ?>
</div>
</main>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
