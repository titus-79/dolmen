<?php
namespace Titus\Dolmen\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use Titus\Dolmen\Models\User;
use Exception;

class PasswordResetTest
{
    public function __construct()
    {
        // Initialisation si nécessaire
    }

    public function testResetPassword(): void
    {
        echo "Test de réinitialisation de mot de passe...\n\n";

        // Paramètres de test
        $login = 'chasseurdedolmens';
        $newPassword = 'Dolmen2024!';

        try {
            echo "Recherche de l'utilisateur '{$login}'...\n";
            $user = User::findByLogin($login);

            if (!$user) {
                echo "❌ Test échoué : Utilisateur non trouvé\n";
                return;
            }

            echo "✅ Utilisateur trouvé\n";
            echo "Test de la mise à jour du mot de passe...\n";

            $user->setPasswordHash(password_hash($newPassword, PASSWORD_BCRYPT));

            if ($user->save()) {
                echo "✅ Mot de passe mis à jour avec succès\n";
                echo "\nNouvelles informations de connexion :\n";
                echo "Login: {$login}\n";
                echo "Mot de passe: {$newPassword}\n";
            } else {
                echo "❌ Test échoué : Erreur lors de la mise à jour du mot de passe\n";
            }
        } catch (Exception $e) {
            echo "❌ Test échoué : " . $e->getMessage() . "\n";
        }
    }
}

// Exécution du test
$test = new PasswordResetTest();
$test->testResetPassword();

//docker-compose exec php php test/PasswordResetTest.php