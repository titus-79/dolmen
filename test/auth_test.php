<?php
// auth_test.php
namespace Titus\Dolmen\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use Titus\Dolmen\Models\User;

echo "Test d'authentification\n\n";

$login = 'chasseurdedolmens';
$password = 'test123'; // Le mot de passe que vous utilisez

try {
    echo "Recherche de l'utilisateur '{$login}'...\n";
    $user = User::findByLogin($login);

    if ($user) {
        echo "Utilisateur trouvé !\n";
        echo "Hash stocké : " . $user->getPasswordHash() . "\n";

        $isValid = password_verify($password, $user->getPasswordHash());
        echo "Vérification du mot de passe : " . ($isValid ? 'SUCCÈS' : 'ÉCHEC') . "\n";

        if (!$isValid) {
            // Créons un nouveau hash pour ce mot de passe
            $newHash = password_hash($password, PASSWORD_BCRYPT);
            echo "\nPour mettre à jour le mot de passe dans la base de données, exécutez :\n";
            echo "UPDATE users SET password_hash_user = '$newHash' WHERE login_user = '$login';\n";
        }
    } else {
        echo "Utilisateur non trouvé !\n";
    }
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}