<?php
/**
 * Modèle Certificat — Génération HTML/PDF (TCPDF optionnel)
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';

class Certificat {
    private PDO $pdo;
    public function __construct() { $this->pdo = getPDO(); }

    public function getByCode(string $code): array|false {
        $stmt = $this->pdo->prepare(
            "SELECT cert.*, c.titre AS cours_titre,
                    f.nom AS form_nom, f.prenom AS form_prenom,
                    a.nom AS app_nom, a.prenom AS app_prenom,
                    o.libelle AS opt_libelle
             FROM certificat cert
             JOIN cours c     ON cert.idcours=c.id
             JOIN formateur f ON c.idformateur=f.id
             JOIN apprenant a ON cert.idapprenant=a.id
             JOIN `option` o  ON a.codopt=o.codopt
             WHERE cert.code_verif=?"
        );
        $stmt->execute([$code]);
        return $stmt->fetch();
    }

    /**
     * Génère le HTML du certificat (pour impression / screenshot mémoire)
     */
    public function renderHtml(array $cert): string {
        $appNom    = htmlspecialchars($cert['app_prenom'] . ' ' . $cert['app_nom'], ENT_QUOTES, 'UTF-8');
        $coursTitre= htmlspecialchars($cert['cours_titre'], ENT_QUOTES, 'UTF-8');
        $formNom   = htmlspecialchars($cert['form_prenom'] . ' ' . $cert['form_nom'], ENT_QUOTES, 'UTF-8');
        $date      = date('d/m/Y', strtotime($cert['date_emis']));
        $code      = htmlspecialchars($cert['code_verif'], ENT_QUOTES, 'UTF-8');
        $opt       = htmlspecialchars($cert['opt_libelle'], ENT_QUOTES, 'UTF-8');

        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Certificat — {$coursTitre}</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap');
  body { margin:0; background:#f0f4f8; font-family:'Inter',sans-serif; }
  .cert-wrap { max-width:900px; margin:40px auto; background:#fff;
               border:8px double #1a73e8; padding:60px; text-align:center;
               box-shadow:0 20px 60px rgba(0,0,0,.15); position:relative; }
  .cert-logo { font-size:2rem; font-weight:700; color:#1a73e8; margin-bottom:10px; }
  .cert-logo span { color:#f59e0b; }
  .cert-title { font-family:'Playfair Display',serif; font-size:2.8rem;
                color:#1a2332; margin:20px 0 10px; }
  .cert-sub   { color:#555; font-size:1rem; margin-bottom:30px; }
  .cert-name  { font-family:'Playfair Display',serif; font-size:2.2rem;
                color:#1a73e8; border-bottom:2px solid #1a73e8;
                display:inline-block; padding-bottom:6px; margin:20px 0; }
  .cert-course{ font-size:1.3rem; color:#333; margin:20px 0; }
  .cert-course strong { color:#1a2332; }
  .cert-meta  { display:flex; justify-content:space-around; margin-top:40px;
                padding-top:30px; border-top:1px solid #e0e0e0; }
  .cert-meta div { text-align:center; }
  .cert-meta .label { font-size:.75rem; color:#888; text-transform:uppercase;
                       letter-spacing:1px; }
  .cert-meta .value { font-size:.95rem; font-weight:600; color:#333; margin-top:4px; }
  .cert-seal  { position:absolute; top:20px; right:30px; width:80px; opacity:.15;
                font-size:4rem; }
  .cert-code  { margin-top:30px; font-size:.75rem; color:#aaa;
                font-family:monospace; }
  .cert-badge { display:inline-block; background:#1a73e8; color:#fff;
                padding:6px 20px; border-radius:20px; font-size:.8rem;
                margin-bottom:20px; }
  @media print {
    body { background:#fff; }
    .cert-wrap { box-shadow:none; border:4px double #1a73e8; }
  }
</style>
</head>
<body>
<div class="cert-wrap">
  <div class="cert-seal">🏆</div>
  <div class="cert-logo">E4<span>Afrika</span></div>
  <div class="cert-badge">MOOC PLATFORM — CERTIFICAT OFFICIEL</div>
  <div class="cert-title">Certificat de Réussite</div>
  <p class="cert-sub">Cette attestation certifie que</p>
  <div class="cert-name">{$appNom}</div>
  <p class="cert-course">
    a complété avec succès la formation<br>
    <strong>« {$coursTitre} »</strong>
  </p>
  <p style="color:#555;font-size:.95rem;">Option : {$opt}</p>
  <div class="cert-meta">
    <div>
      <div class="label">Formateur</div>
      <div class="value">{$formNom}</div>
    </div>
    <div>
      <div class="label">Date d'émission</div>
      <div class="value">{$date}</div>
    </div>
    <div>
      <div class="label">Plateforme</div>
      <div class="value">E4Afrika MOOC</div>
    </div>
  </div>
  <div class="cert-code">Code de vérification : {$code}</div>
</div>
<div style="text-align:center;margin:20px 0">
  <button onclick="window.print()"
    style="background:#1a73e8;color:#fff;border:none;padding:12px 32px;
           border-radius:8px;font-size:1rem;cursor:pointer;">
    🖨️ Imprimer / Télécharger PDF
  </button>
</div>
</body>
</html>
HTML;
    }
}
