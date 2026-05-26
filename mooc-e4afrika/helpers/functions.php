<?php
/**
 * Fonctions utilitaires globales — E4Afrika MOOC
 */

function e(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void
{
    header("Location: " . $url);
    exit;
}

function flashMessage(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function formatDate(string $date, string $format = 'd/m/Y'): string
{
    return date($format, strtotime($date));
}

function formatMontant(int $montant): string
{
    return number_format($montant, 0, ',', ' ') . ' FCFA';
}

function progressColor(int $pct): string
{
    if ($pct >= 100)
        return 'success';
    if ($pct >= 60)
        return 'info';
    if ($pct >= 30)
        return 'warning';
    return 'danger';
}

function truncate(string $text, int $limit = 120): string
{
    return strlen($text) > $limit ? substr($text, 0, $limit) . '...' : $text;
}

function logAction(string $action): void
{
    // if (!isLoggedIn()) return;
    $pdo = getPDO();
    $stmt = $pdo->prepare("INSERT INTO logs_actions (utilisateur, role, action, ip) VALUES (?,?,?,?)");
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $name = $_SESSION['user_nom'] ?? 'inconnu';
    // $role = getRole();
    $stmt->execute([$name, $role, $action, $ip]);
}

function niveauBadge(string $niveau): string
{
    $map = [
        'DEBUTANT' => 'success',
        'INTERMEDIAIRE' => 'warning',
        'AVANCE' => 'danger',
    ];
    $color = $map[$niveau] ?? 'secondary';
    return '<span class="badge bg-' . $color . '">' . e($niveau) . '</span>';
}

function statutBadge(string $statut): string
{
    $map = [
        'ACTIF' => 'success',
        'SUSPENDU' => 'warning',
        'TERMINE' => 'primary',
        'ABANDON' => 'secondary',
        'PUBLIE' => 'success',
        'BROUILLON' => 'secondary',
        'ARCHIVE' => 'dark',
    ];
    $color = $map[$statut] ?? 'secondary';
    return '<span class="badge bg-' . $color . '">' . e($statut) . '</span>';
}
