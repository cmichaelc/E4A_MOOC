<footer class="footer mt-auto py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        <div class="brand-logo mb-2" style="font-size:1.5rem">E4<span style="color:#f59e0b">Afrika</span> <span style="font-size:.9rem;font-weight:400;color:#94a3b8">MOOC</span></div>
        <p class="text-muted small">Plateforme de formation en ligne accessible, interactive et certifiante, adaptée aux réalités africaines.</p>
        <div class="d-flex gap-3 mt-3">
          <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
          <a href="#" class="social-icon"><i class="bi bi-twitter-x"></i></a>
          <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
          <a href="#" class="social-icon"><i class="bi bi-youtube"></i></a>
        </div>
      </div>
      <div class="col-lg-2 col-6">
        <h6 class="fw-600 mb-3">Plateforme</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="<?= BASE_URL ?>/index.php">Accueil</a></li>
          <li><a href="<?= BASE_URL ?>/controllers/CoursController.php">Catalogue</a></li>
          <li><a href="<?= BASE_URL ?>/controllers/AuthController.php?action=register">S'inscrire</a></li>
          <li><a href="<?= BASE_URL ?>/controllers/AuthController.php?action=login">Connexion</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-6">
        <h6 class="fw-600 mb-3">Formation</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="#">Python</a></li>
          <li><a href="#">Développement Web</a></li>
          <li><a href="#">Base de données</a></li>
          <li><a href="#">Réseaux</a></li>
        </ul>
      </div>
      <div class="col-lg-4">
        <h6 class="fw-600 mb-3">Contact</h6>
        <ul class="list-unstyled text-muted small">
          <li class="mb-2"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Cotonou, République du Bénin</li>
          <li class="mb-2"><i class="bi bi-envelope-fill text-primary me-2"></i>contact@e4afrika.com</li>
          <li class="mb-2"><i class="bi bi-telephone-fill text-primary me-2"></i>+229 XX XX XX XX</li>
        </ul>
      </div>
    </div>
    <hr class="my-4 border-secondary">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
      <p class="text-muted small mb-2 mb-md-0">
        &copy; <?= date('Y') ?> E4Afrika. Tous droits réservés. 
        Développé dans le cadre d'un mémoire de licence — ESSF Bénin.
      </p>
      <div class="d-flex gap-3">
        <a href="#" class="text-muted small text-decoration-none">Confidentialité</a>
        <a href="#" class="text-muted small text-decoration-none">Conditions</a>
      </div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
