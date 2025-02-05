<?php
// composer/Views/templates/shop/index.php
?>
<div class="container shop-page">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h1 class="display-4">Galerie de Tirages</h1>
            <p class="lead text-muted">
                Découvrez une collection unique de photographies capturées par Chasseur de Dolmens
            </p>
        </div>
    </div>

    <div class="row gallery-filters mb-4">
        <div class="col-12 text-center">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary active" data-filter="all">Tous</button>
                <button type="button" class="btn btn-outline-primary" data-filter="landscape">Paysages</button>
                <button type="button" class="btn btn-outline-primary" data-filter="dolmen">Dolmens</button>
                <button type="button" class="btn btn-outline-primary" data-filter="historic">Sites Historiques</button>
            </div>
        </div>
    </div>

    <div class="row prints-gallery">
        <?php if (empty($prints)): ?>
            <div class="col-12 text-center">
                <p class="alert alert-info">
                    Aucun tirage n'est actuellement disponible.
                    Revenez bientôt pour de nouvelles découvertes !
                </p>
            </div>
        <?php else: ?>
            <?php foreach ($prints as $print): ?>
                <div class="col-md-4 mb-4 print-item" data-category="<?= htmlspecialchars($print['category'] ?? 'other') ?>">
                    <div class="card print-card shadow-sm">
                        <div class="card-image-container">
                            <!-- Supposons que vous ayez une méthode pour récupérer l'image principale -->
                            <img
                                src="<?= htmlspecialchars($print['image_url'] ?? '/assets/images/default-print.jpg') ?>"
                                alt="<?= htmlspecialchars($print['title_print']) ?>"
                                class="card-img-top"
                            >
                            <div class="card-image-overlay">
                                <a href="/shop/tirage/<?= $print['id_print'] ?>" class="btn btn-light">
                                    Voir les détails
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($print['title_print']) ?></h5>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="print-details">
                                    <small class="text-muted">
                                        Date : <?= date('d/m/Y', strtotime($print['date_print'])) ?>
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        État : <?= htmlspecialchars($print['state_print']) ?>
                                    </small>
                                </div>
                                <div class="print-price">
                                    <strong class="text-primary">89,90 €</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="row mt-4">
        <div class="col-12 text-center">
            <p class="text-muted">
                <i class="fas fa-info-circle"></i>
                Chaque tirage est unique et numéroté,
                imprimé sur du papier de haute qualité.
            </p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.gallery-filters .btn');
        const printItems = document.querySelectorAll('.print-item');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');

                // Désactiver tous les boutons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                printItems.forEach(item => {
                    const category = item.getAttribute('data-category');

                    if (filter === 'all' || category === filter) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    });
</script>

<style>
    .card-image-container {
        position: relative;
        overflow: hidden;
    }

    .card-image-container img {
        transition: transform 0.3s ease;
    }

    .card-image-container:hover img {
        transform: scale(1.1);
    }

    .card-image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.4);
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .card-image-container:hover .card-image-overlay {
        opacity: 1;
    }
</style>