<?php
namespace Titus\Dolmen\Controllers;

use Titus\Dolmen\Models\Prints as PrintModel;
use Titus\Dolmen\Models\Order;

class ShopController extends BaseController
{
    private $printModel;
    private $orderModel;

    public function __construct()
    {
        $this->printModel = new PrintModel();
        $this->orderModel = new Order();
    }

    /**
     * Affiche la liste des tirages disponibles
     */
    public function index()
    {
        $prints = $this->printModel->getAvailablePrints();

        $data = [
            'pageTitle' => 'Boutique - Tirages',
            'prints' => $prints
        ];

        $this->render('shop/index', $data);
    }

    /**
     * Affiche les détails d'un tirage
     * @param int $id Identifiant du tirage
     */
    public function show($id)
    {
        $print = $this->printModel->getPrintById($id);
        $images = $this->printModel->getPrintImages($id);

        if (!$print) {
            // Gestion de l'erreur si le tirage n'existe pas
            header('Location: /shop');
            exit;
        }

        $data = [
            'pageTitle' => $print['title_print'],
            'print' => $print,
            'images' => $images
        ];

        $this->render('shop/show', $data);
    }

    /**
     * Traite l'ajout d'un tirage au panier
     * Nécessite une session utilisateur active
     */
    public function addToCart($printId)
    {
        // Vérification de la connexion utilisateur
        if (!isset($_SESSION['user'])) {
            // Redirection vers la connexion
            header('Location: /login');
            exit;
        }

        $print = $this->printModel->getPrintById($printId);

        if (!$print) {
            // Gestion de l'erreur si le tirage n'existe pas
            header('Location: /shop');
            exit;
        }

        // Logique d'ajout au panier (à implémenter selon votre besoin)
        // Exemple simple utilisant la session
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $_SESSION['cart'][] = $printId;

        // Redirection vers le panier
        header('Location: /shop/cart');
        exit;
    }

    /**
     * Affiche et gère le panier
     */
    public function cart()
    {
        // Vérification de la connexion utilisateur
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $cartItems = [];
        $total = 0;

        // Récupération des détails des tirages dans le panier
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $printId) {
                $print = $this->printModel->getPrintById($printId);
                if ($print) {
                    $cartItems[] = $print;
                    // Ajoutez une logique pour calculer le total
                }
            }
        }

        $data = [
            'pageTitle' => 'Votre Panier',
            'cartItems' => $cartItems,
            'total' => $total
        ];

        $this->render('shop/cart', $data);
    }

    /**
     * Traite la commande finale
     */
    public function checkout()
    {
        // Vérification de la connexion utilisateur
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        // Vérification du panier
        if (empty($_SESSION['cart'])) {
            header('Location: /shop');
            exit;
        }

        // Créer la commande
        $orderId = $this->orderModel->createOrder([
            'user_id' => $_SESSION['user']['id'],
            'total_amount' => 0, // Calculer le total réel
            'status' => 'en cours'
        ]);

        // Ajouter les tirages à la commande
        foreach ($_SESSION['cart'] as $printId) {
            $this->orderModel->addPrintToOrder($orderId, $printId);
        }

        // Vider le panier
        unset($_SESSION['cart']);

        // Redirection vers la confirmation
        header('Location: /shop/confirmation');
        exit;
    }
}