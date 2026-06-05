<?php
/**
 * Script de réinitialisation des mots de passe de démo
 * Exécuter UNE SEULE FOIS via : http://localhost/mooc-e4afrika/reset_passwords.php
 * SUPPRIMER ce fichier après utilisation !
 */

require_once __DIR__ . '/config/database.php';
$pdo = getPDO();

$passwords = [
    ['table' => 'administrateur', 'password' => 'Admin@1234'],
    ['table' => 'formateur',      'password' => 'Formateur@1'],
    ['table' => 'apprenant',      'password' => 'Apprenant@1'],
];

echo '<h2>Réinitialisation des mots de passe de démo</h2>';
foreach ($passwords as $item) {
    $hash = password_hash($item['password'], PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("UPDATE `{$item['table']}` SET password = ?");
    $stmt->execute([$hash]);
    echo "<p>✅ Table <b>{$item['table']}</b> — mot de passe : <code>{$item['password']}</code></p>";
}
echo '<hr><p style="color:red"><strong>⚠️ Supprimez ce fichier immédiatement !</strong></p>';
