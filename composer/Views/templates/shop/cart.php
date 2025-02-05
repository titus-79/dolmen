<?php
// composer/Views/templates/shop/cart.php
?>
<div class="container cart-page">
    <div class="row">
        <div class="col-12">
            <h1 class="display-4 text-center mb-5">Votre Panier</h1>
        </div>
    </div>

    <?php if (empty($cartItems)): ?>
        <div class="row">
            <div class="col-12 text-center">
                <p class="alert alert-info">
                    Votre panier est vide.
                    <a href="/shop" class="alert-link">Découvrez nos tirages</a>
                </p>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-8">
                <?php foreach ($cartItems as $item): ?>
                    <div class="card mb-3">
                        <div class="row no-gutters">
                            <div class="col-md-4">
                                <img
                                    src="<?= htmlspecialchars($item['image_url'] ?? '/assets/images/default-print.jpg') ?>"
                                    class="card-img"
                                    alt="<?= htmlspecialchars($item['title_print']) ?>"
                                >
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($item['title_print']) ?></h5>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            Date : <?= date('d/m/Y', strtotime($item['date_print'])) ?>
                                        </small>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong class="text-primary">89,90 €</strong>
                                        <button class="btn btn-danger btn-sm">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Résumé de la commande</h5>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span>Sous-total</span>
                            <strong><?= number_format($total, 2) ?> €</strong>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span>Livraison</span>
                            <strong>Gratuite</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <h4>Total</h4>
                            <h4 class="text-primary"><?= number_format($total, 2) ?> €</h4>
                        </div>
                        <form action="/shop/checkout" method="POST">
                            <button type="submit" class="btn btn-primary btn-lg btn-block mt-3">
                                Procéder au paiement
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>