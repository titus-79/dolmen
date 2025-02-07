<?php
// verify_auth.php
namespace Titus\Dolmen\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use Titus\Dolmen\Models\User;

$login = 'chasseurdedolmens';
$password = 'Dolmen2024!';

echo "Vérification des nouvelles informations de connexion...\n\n";

try {
    $user = User::findByLogin($login);
    if ($user && password_verify($password, $user->getPasswordHash())) {
        echo "✅ Authentification réussie !\n";
        echo "Détails de l'utilisateur :\n";
        echo "- ID : " . $user->getId() . "\n";
        echo "- Nom : " . $user->getName() . "\n";
        echo "- Prénom : " . $user->getFirstname() . "\n";
        echo "- Email : " . $user->getEmail() . "\n";
    } else {
        echo "❌ Échec de l'authentification\n";
    }
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}