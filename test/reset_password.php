<?php
// reset_password.php

namespace Titus\Dolmen\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use Titus\Dolmen\Models\User;

function resetPassword($login, $newPassword) {
    try {
        echo "Recherche de l'utilisateur '{$login}'...\n";
        $user = User::findByLogin($login);

        if (!$user) {
            echo "Utilisateur non trouvé !\n";
            return false;
        }

        echo "Utilisateur trouvé. Mise à jour du mot de passe...\n";
        $user->setPasswordHash(password_hash($newPassword, PASSWORD_BCRYPT));

        if ($user->save()) {
            echo "Mot de passe mis à jour avec succès !\n";
            echo "\nNouvelles informations de connexion :\n";
            echo "Login: {$login}\n";
            echo "Mot de passe: {$newPassword}\n";
            return true;
        } else {
            echo "Erreur lors de la mise à jour du mot de passe.\n";
            return false;
        }
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage() . "\n";
        return false;
    }
}

// Réinitialisation du mot de passe pour chasseurdedolmens
$login = 'chasseurdedolmens';
$newPassword = 'Dolmen2024!'; // Mot de passe plus sécurisé

resetPassword($login, $newPassword);

//docker-compose exec php php test/reset_password.php