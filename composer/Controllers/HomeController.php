<?php

namespace Titus\Dolmen\Controllers;

class HomeController extends BaseController {
    public function index() {
        $data = [
            'pageTitle' => 'Accueil - Chasseur de Dolmens',
            'welcomeMessage' => 'Bienvenue chez Chasseur de Dolmens',
            'aboutText' => 'Découvrez l\'univers fascinant des dolmens à travers mon objectif...'
        ];

        $this->render('home/index', $data);
    }
}