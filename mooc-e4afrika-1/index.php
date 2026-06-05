<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/helpers/auth.php';
require_once __DIR__ . '/helpers/functions.php';
require_once __DIR__ . '/models/Cours.php';

$coursM     = new Cours();
$vedette    = $coursM->getAllPublished();
$vedette    = array_slice($vedette, 0, 3);
$categories = $coursM->getCategories();
$pageTitle  = 'Accueil';
include __DIR__ . '/views/layouts/header.php';
?>

<!-- ============================================================ HERO -->
<section class="hero-section">
  <div class="hero-bg-shapes">
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>
  </div>
  <div class="container position-relative">
    <div class="row align-items-center min-vh-90">
      <div class="col-lg-6 text-white py-5">
        <div class="badge bg-warning text-dark fw-600 mb-3 px-3 py-2 rounded-pill">
          <i class="bi bi-star-fill me-1"></i> Plateforme N°1 en Afrique de l'Ouest
        </div>
        <h1 class="display-4 fw-800 lh-sm mb-4">
          Apprenez. <span class="text-warning">Progressez.</span><br>Réussissez.
        </h1>
        <p class="lead text-white-80 mb-5">
          E4Afrika MOOC est la première plateforme de formation en ligne certifiante 
          adaptée aux réalités africaines. Des cours de qualité, accessibles partout, à tout moment.
        </p>
        <div class="d-flex flex-wrap gap-3">
          <a href="<?= BASE_URL ?>/controllers/CoursController.php?action=catalogue" class="btn btn-warning btn-lg px-5 fw-700 rounded-pill shadow">
            <i class="bi bi-play-circle-fill me-2"></i>Explorer les cours
          </a>
          <a href="<?= BASE_URL ?>/controllers/AuthController.php?action=register" class="btn btn-outline-light btn-lg px-4 rounded-pill">
            <i class="bi bi-person-plus me-2"></i>S'inscrire gratuitement
          </a>
        </div>
        <!-- Stats -->
        <div class="row g-3 mt-5">
          <div class="col-4">
            <div class="hero-stat">
              <div class="hero-stat-num">500+</div>
              <div class="hero-stat-label">Apprenants</div>
            </div>
          </div>
          <div class="col-4">
            <div class="hero-stat">
              <div class="hero-stat-num">30+</div>
              <div class="hero-stat-label">Cours</div>
            </div>
          </div>
          <div class="col-4">
            <div class="hero-stat">
              <div class="hero-stat-num">95%</div>
              <div class="hero-stat-label">Satisfaction</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center py-5">
        <div class="hero-illustration">
          <div class="hero-card floating">
            <i class="bi bi-laptop text-primary"></i>
            <span>Développement Web</span>
          </div>
          <div class="hero-card floating" style="animation-delay:.5s">
            <i class="bi bi-database text-success"></i>
            <span>Base de données</span>
          </div>
          <div class="hero-card floating" style="animation-delay:1s">
            <i class="bi bi-code-slash text-warning"></i>
            <span>Python & IA</span>
          </div>
          <div class="hero-card floating" style="animation-delay:1.5s">
            <i class="bi bi-award-fill text-danger"></i>
            <span>Certifications</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================ FEATURES -->
<section class="py-6 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-700 display-6">Pourquoi choisir <span class="text-primary">E4Afrika</span> ?</h2>
      <p class="text-muted lead">Une expérience d'apprentissage conçue pour l'Afrique</p>
    </div>
    <div class="row g-4">
      <?php
      $features = [
        ['bi-wifi-off','text-primary','Accessible hors ligne','Contenu optimisé pour les connexions lentes, téléchargeable pour une utilisation hors ligne.'],
        ['bi-phone','text-success','100% Mobile','Interface responsive adaptée aux smartphones. Apprenez depuis n\'importe quel appareil.'],
        ['bi-award','text-warning','Certifiants','Obtenez des certificats reconnus à la fin de chaque formation pour valoriser vos compétences.'],
        ['bi-people','text-danger','Communauté','Échangez avec des formateurs experts et d\'autres apprenants à travers toute l\'Afrique.'],
        ['bi-translate','text-info','En Français','Tous nos contenus sont en français, adaptés au contexte africain et à la réalité locale.'],
        ['bi-shield-check','text-dark','Sécurisé','Plateforme sécurisée avec authentification robuste et protection de vos données.'],
      ];
      foreach ($features as [$icon, $color, $title, $desc]): ?>
      <div class="col-lg-4 col-md-6">
        <div class="feature-card card border-0 h-100 rounded-4 p-4 shadow-hover">
          <div class="feature-icon <?= $color ?> mb-3">
            <i class="bi <?= $icon ?>"></i>
          </div>
          <h5 class="fw-700 mb-2"><?= $title ?></h5>
          <p class="text-muted mb-0 small"><?= $desc ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============================================================ COURS EN VEDETTE -->
<section class="py-6">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-5">
      <div>
        <h2 class="fw-700 display-6 mb-1">Cours en <span class="text-primary">vedette</span></h2>
        <p class="text-muted mb-0">Les formations les plus populaires du moment</p>
      </div>
      <a href="<?= BASE_URL ?>/controllers/CoursController.php?action=catalogue" class="btn btn-outline-primary rounded-pill px-4">
        Voir tout <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>
    <div class="row g-4">
      <?php foreach ($vedette as $c): ?>
      <div class="col-lg-4 col-md-6">
        <div class="cours-card card border-0 rounded-4 shadow-hover h-100 overflow-hidden">
          <div class="cours-card-img d-flex align-items-center justify-content-center">
            <i class="bi bi-laptop-fill display-1 text-white opacity-50"></i>
          </div>
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <span class="badge bg-primary-soft text-primary rounded-pill"><?= e($c['cat_libelle']) ?></span>
              <?= niveauBadge($c['niveau']) ?>
            </div>
            <h5 class="fw-700 mb-2 lh-sm"><?= e($c['titre']) ?></h5>
            <p class="text-muted small mb-3"><?= e(truncate($c['description'])) ?></p>
            <div class="d-flex align-items-center gap-2 mb-3">
              <div class="avatar-xs"><?= strtoupper(substr($c['form_prenom'], 0, 1)) ?></div>
              <span class="small text-muted"><?= e($c['form_prenom'] . ' ' . $c['form_nom']) ?></span>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
              <div class="d-flex gap-3 text-muted small">
                <span><i class="bi bi-people me-1"></i><?= (int)$c['nb_apprenants'] ?></span>
                <span><i class="bi bi-clock me-1"></i><?= $c['duree_totale'] ?> min</span>
              </div>
              <span class="fw-700 text-primary"><?= $c['montant'] > 0 ? formatMontant($c['montant']) : '<span class="text-success">Gratuit</span>' ?></span>
            </div>
          </div>
          <div class="card-footer bg-white border-0 px-4 pb-4">
            <a href="<?= BASE_URL ?>/controllers/CoursController.php?action=detail&id=<?= $c['id'] ?>"
               class="btn btn-primary w-100 rounded-3 fw-600">
              Voir le cours <i class="bi bi-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============================================================ CTA -->
<section class="cta-section py-6 text-white text-center">
  <div class="container">
    <h2 class="display-5 fw-800 mb-3">Prêt à commencer votre parcours ?</h2>
    <p class="lead text-white-80 mb-5">Rejoignez des milliers d'apprenants qui transforment leur carrière avec E4Afrika</p>
    <a href="<?= BASE_URL ?>/controllers/AuthController.php?action=register"
       class="btn btn-warning btn-lg px-6 fw-700 rounded-pill shadow-lg">
      <i class="bi bi-rocket-takeoff-fill me-2"></i>Commencer maintenant — C'est gratuit !
    </a>
  </div>
</section>

<?php include __DIR__ . '/views/layouts/footer.php'; ?>
