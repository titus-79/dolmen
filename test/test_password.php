<?php
// test_password.php

namespace Titus\Dolmen\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

$password = 'test123'; // Le mot de passe que vous utilisez pour tester
$hash = '$2y$10$EmvrF39NIGPVmn5WofZ0NOf/NNbZYyxG79JRghRA4QkRSgPeUar8W';

echo "Test de vérification du mot de passe\n";
echo "Mot de passe testé : " . $password . "\n";
echo "Hash stocké : " . $hash . "\n";
echo "Résultat de password_verify : " . (password_verify($password, $hash) ? 'VALIDE' : 'INVALIDE') . "\n";

// Générons un nouveau hash pour comparaison
$newHash = password_hash($password, PASSWORD_BCRYPT);
echo "Nouveau hash généré : " . $newHash . "\n";