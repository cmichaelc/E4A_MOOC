<?php
$pageTitle = isset($results) ? 'Résultats du quiz' : 'Quiz — ' . e($module['titre']);
include __DIR__ . '/../layouts/header.php';
?>
<main class="py-5 bg-light min-vh-100">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-8">

      <?php if (isset($results)): ?>
      <!-- ===== RÉSULTATS ===== -->
      <?php $total = count($questions); $pct = $total > 0 ? round($score/$total*100) : 0; ?>
      <div class="card border-0 rounded-4 shadow-sm text-center mb-4 overflow-hidden">
        <div class="quiz-result-header p-5">
          <div class="quiz-score-circle mx-auto mb-3">
            <span class="fw-800 display-5"><?= $pct ?>%</span>
          </div>
          <h2 class="fw-800 text-white mb-1">
            <?= $pct >= 80 ? '🎉 Excellent !' : ($pct >= 50 ? '👍 Bien !' : '💪 Continuez !') ?>
          </h2>
          <p class="text-white-75"><?= $score ?>/<?= $total ?> bonnes réponses</p>
        </div>
        <div class="card-body p-4">
          <div class="d-flex justify-content-center gap-3">
            <a href="<?= BASE_URL ?>/controllers/ApprenantController.php?action=progression&idcours=<?= $_POST['idcours'] ?? '' ?>"
               class="btn btn-primary rounded-3 px-4">
              <i class="bi bi-arrow-left me-2"></i>Retour au cours
            </a>
            <a href="<?= BASE_URL ?>/controllers/CoursController.php?action=quiz&idmodule=<?= $module['id'] ?>"
               class="btn btn-outline-secondary rounded-3 px-4">
              <i class="bi bi-arrow-repeat me-2"></i>Recommencer
            </a>
          </div>
        </div>
      </div>

      <!-- Correction -->
      <h5 class="fw-700 mb-3">Correction détaillée</h5>
      <?php foreach ($questions as $q):
        $r = $results[$q['id']];
        $isCorrect = $r['correct'];
      ?>
      <div class="card border-0 rounded-3 shadow-sm mb-3 overflow-hidden border-start border-4
                  <?= $isCorrect ? 'border-success' : 'border-danger' ?>">
        <div class="card-body p-4">
          <div class="d-flex align-items-start gap-3">
            <div class="quiz-result-icon <?= $isCorrect ? 'text-success' : 'text-danger' ?>">
              <i class="bi bi-<?= $isCorrect ? 'check-circle-fill' : 'x-circle-fill' ?> fs-4"></i>
            </div>
            <div class="flex-grow-1">
              <p class="fw-600 mb-2"><?= e($q['question']) ?></p>
              <div class="row g-2">
                <?php foreach(['A','B','C','D'] as $letter):
                  $key = 'choix_'.strtolower($letter);
                  if (empty($q[$key])) continue;
                  $isAnswer = ($letter === $q['reponse']);
                  $isChosen = ($r['rep'] === $letter);
                ?>
                <div class="col-md-6">
                  <div class="quiz-option p-2 rounded-3 small
                    <?= $isAnswer ? 'bg-success-subtle text-success fw-600' : '' ?>
                    <?= $isChosen && !$isAnswer ? 'bg-danger-subtle text-danger' : '' ?>">
                    <span class="fw-700"><?= $letter ?>.</span> <?= e($q[$key]) ?>
                    <?php if ($isAnswer): ?><i class="bi bi-check2-circle ms-1"></i><?php endif; ?>
                    <?php if ($isChosen && !$isAnswer): ?><i class="bi bi-x-circle ms-1"></i><?php endif; ?>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>

      <?php else: ?>
      <!-- ===== QUIZ ===== -->
      <div class="card border-0 rounded-4 shadow-sm mb-4">
        <div class="card-header bg-white border-0 p-4 pb-3">
          <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/controllers/ApprenantController.php?action=mes_cours">Mes cours</a></li>
              <li class="breadcrumb-item active">Quiz</li>
            </ol>
          </nav>
          <h2 class="fw-700 h4 mb-1"><i class="bi bi-question-circle-fill text-warning me-2"></i>Quiz — <?= e($module['titre']) ?></h2>
          <p class="text-muted small mb-0"><?= count($questions) ?> question(s) — Répondez à toutes les questions</p>
        </div>
        <div class="card-body p-4">
          <?php if (empty($questions)): ?>
            <div class="text-center py-4">
              <i class="bi bi-emoji-smile display-3 text-muted opacity-25"></i>
              <p class="text-muted mt-2">Aucun quiz disponible pour ce module.</p>
            </div>
          <?php else: ?>
          <form method="POST" action="<?= BASE_URL ?>/controllers/CoursController.php?action=submit_quiz" id="quizForm">
            <?= csrfField() ?>
            <input type="hidden" name="idmodule" value="<?= $module['id'] ?>">
            <input type="hidden" name="idcours" value="<?= $_GET['idcours'] ?? '' ?>">

            <!-- Barre de progression quiz -->
            <div class="quiz-progress mb-4">
              <div class="d-flex justify-content-between mb-1">
                <small class="text-muted">Question <span id="qCurrent">1</span>/<?= count($questions) ?></small>
                <small class="text-muted fw-600" id="qPct">0%</small>
              </div>
              <div class="progress rounded-pill" style="height:6px">
                <div class="progress-bar bg-warning rounded-pill" id="quizProgressBar" style="width:0%"></div>
              </div>
            </div>

            <?php foreach ($questions as $i => $q): ?>
            <div class="quiz-question mb-4 p-4 bg-light rounded-3" id="question-<?= $q['id'] ?>">
              <p class="fw-600 mb-3">
                <span class="badge bg-primary me-2"><?= $i+1 ?></span>
                <?= e($q['question']) ?>
              </p>
              <div class="row g-2">
                <?php foreach (['A','B','C','D'] as $letter):
                  $key = 'choix_'.strtolower($letter);
                  if (empty($q[$key])) continue;
                ?>
                <div class="col-md-6">
                  <label class="quiz-option-label d-block p-3 rounded-3 border cursor-pointer w-100"
                         for="q<?= $q['id'] ?>_<?= $letter ?>">
                    <input type="radio" name="q_<?= $q['id'] ?>" id="q<?= $q['id'] ?>_<?= $letter ?>"
                           value="<?= $letter ?>" class="quiz-radio me-2" required>
                    <span class="fw-600"><?= $letter ?>.</span> <?= e($q[$key]) ?>
                  </label>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endforeach; ?>

            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
              <a href="javascript:history.back()" class="btn btn-outline-secondary rounded-3 px-4">
                <i class="bi bi-arrow-left me-2"></i>Annuler
              </a>
              <button type="submit" class="btn btn-warning btn-lg rounded-3 fw-700 px-5" id="submitQuiz">
                <i class="bi bi-send-fill me-2"></i>Soumettre le quiz
              </button>
            </div>
          </form>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>

    </div>
  </div>
</div>
</main>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
