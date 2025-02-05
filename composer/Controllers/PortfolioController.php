<?php

namespace Titus\Dolmen\Controllers;
use BaseController;
use Portfolio;

require_once 'app/Models/BaseModel.php';


class PortfolioController extends BaseController
{
    private $portfolioModel;

    public function __construct()
    {
        $this->portfolioModel = new Portfolio();
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