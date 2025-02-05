<?php

namespace Titus\Dolmen\Controllers;

class AboutController extends BaseController
{
    public function about(): void
    {
        $data = [
            'pageTitle' => 'Accueil - Chasseur de Dolmens',
            'welcomeMessage' => 'Bienvenue chez Chasseur de Dolmens',
            'aboutText' => 'Découvrez l\'univers fascinant des dolmens à travers mon objectif...'
        ];

        $this->render('about/about',$data);
    }


}