<?php require_once 'app/Views/templates/navbar.php'; ?>

    <main class="portfolio">
        <?php foreach($images as $image): ?>
            <article class="portfolio-item">
                <h2><?= htmlspecialchars($image->title) ?></h2>
                <img src="<?= htmlspecialchars($image->url) ?>" alt="<?= htmlspecialchars($image->alt) ?>">
            </article>
        <?php endforeach; ?>
    </main>

<?php require_once 'app/Views/templates/footer.php'; ?>