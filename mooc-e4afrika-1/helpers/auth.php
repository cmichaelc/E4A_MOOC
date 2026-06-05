<?php
/**
 * Contrôle d'accès par rôle — E4Afrika MOOC
 */

require_once __DIR__ . '/../config/session.php';

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']) && isset($_SESSION['role']);
}

function getRole(): string {
    return $_SESSION['role'] ?? '';
}

function requireLogin(string $redirect = '/mooc-e4afrika/index.php'): void {
    if (!isLoggedIn()) {
        header("Location: " . BASE_URL . "/views/auth/login.php");
        exit;
    }
}

function requireRole(string ...$roles): void {
    requireLogin();
    if (!in_array(getRole(), $roles)) {
        header("Location: " . BASE_URL . "/index.php?error=acces_refuse");
        exit;
    }
}

function requireAdmin(): void {
    requireRole('admin');
}

function requireFormateur(): void {
    requireRole('formateur', 'admin');
}

function requireApprenant(): void {
    requireRole('apprenant', 'admin');
}

function currentUserId(): int {
    return (int)($_SESSION['user_id'] ?? 0);
}

function currentUserName(): string {
    return htmlspecialchars($_SESSION['user_nom'] ?? 'Utilisateur', ENT_QUOTES, 'UTF-8');
}

function isAdmin(): bool {
    return getRole() === 'admin';
}

function isFormateur(): bool {
    return getRole() === 'formateur';
}

function isApprenant(): bool {
    return getRole() === 'apprenant';
}
