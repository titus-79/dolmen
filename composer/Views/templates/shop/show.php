<?php
// composer/Views/templates/shop/show.php
?>
<div class="container print-details-page">
    <div class="row">
        <div class="col-md-6">
            <!-- Carrousel d'images -->
            <div id="printCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($images as $index => $image): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <img
                                src="<?= htmlspecialchars($image['path_picture']) ?>"
                                class="d-block w-100"
                                alt="<?= htmlspecialchars($image['alt_picture']) ?>"
                            >
                        </div>
                    <?php endforeach; ?>
                </div>
                <a class="carousel-control-prev" href="#printCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </a>
                <a class="carousel-control-next" href="#printCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="print-info">
                <h1><?= htmlspecialchars($print['title_print']) ?></h1>
                <p class="text-muted"><?= htmlspecialchars($print['description_print']) ?></p>

                <div class="print-metadata">
                    <p><strong>Date de capture :</strong> <?= date('d/m/Y', strtotime($print['date_print'])) ?></p>
                    <p><strong>État :</strong> <?= htmlspecialchars($print['state_print']) ?></p>
                </div>

                <div class="print-pricing mt-4">
                    <h3 class="text-primary">89,90 €</h3>
                    <form action="/shop/add-to-cart/<?= $print['id_print'] ?>" method="POST">
                        <div class="form-group">
                            <label for="print-size">Taille du tirage</label>
                            <select class="form-control" id="print-size" name="print-size">
                                <option>30x40 cm</option>
                                <option>50x70 cm</option>
                                <option>70x100 cm</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            <i class="fas fa-shopping-cart"></i> Ajouter au panier
                        </button>
                    </form>
                </div>

                <div class="print-description mt-4">
                    <h4>Description de l'œuvre</h4>
                    <p><?= nl2br(htmlspecialchars($print['description_print'])) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>