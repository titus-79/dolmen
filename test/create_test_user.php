<?php
// create_test_user.php

namespace Titus\Dolmen\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use Titus\Dolmen\Models\User;
use Titus\Dolmen\Models\Connexion;

// Créer un nouvel utilisateur test
$user = new User();
$password = 'Test123!'; // Mot de passe clair
$hash = password_hash($password, PASSWORD_BCRYPT);

$user->setName('Test')
    ->setLogin('testuser')
    ->setPasswordHash($hash)
    ->setEmail('test@example.com')
    ->setFirstname('User')
    ->setTel('0123456789');

// Sauvegarder l'utilisateur
if ($user->save()) {
    echo "Utilisateur test créé avec succès\n";
    echo "Login: testuser\n";
    echo "Mot de passe: {$password}\n";
    echo "Hash: {$hash}\n";
} else {
    echo "Erreur lors de la création de l'utilisateur\n";
}

// Vérifier que l'utilisateur peut être retrouvé
$foundUser = User::findByLogin('testuser');
if ($foundUser) {
    echo "\nTest de recherche utilisateur : OK\n";
    echo "Test de mot de passe : " .
        (password_verify($password, $foundUser->getPasswordHash()) ? 'OK' : 'ÉCHEC') . "\n";
} else {
    echo "\nImpossible de retrouver l'utilisateur créé\n";
}