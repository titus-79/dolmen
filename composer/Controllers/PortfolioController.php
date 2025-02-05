<?php

namespace Titus\Dolmen\Controllers;

use Titus\Dolmen\Models\Portfolio;


class PortfolioController extends BaseController
{
    private Portfolio $portfolioModel;

    public function __construct()
    {
        $this->portfolioModel = new Portfolio();
    }

    public function portfolio(){
        $data = [
            'pageTitle' => 'Accueil - Chasseur de Dolmens',
            'welcomeMessage' => 'Bienvenue chez Chasseur de Dolmens',
            'aboutText' => 'Découvrez l\'univers fascinant des dolmens à travers mon objectif...'
        ];

        $this->render('portfolio/index', $data);
    }
    public function index()
    {
        $images = $this->portfolioModel->getAllImages();
        $this->render('portfolio/index', ['images' => $images]);
    }

    public function show($id)
    {
        $image = $this->portfolioModel->getById($id);
        $this->render('portfolio/show', ['image' => $image]);
    }
}