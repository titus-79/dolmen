<?php
// views/templates/portfolio/album.php
?>
<div class="album-container px-4 py-8">
    <div class="breadcrumb mb-8">
        <a href="/portfolio" class="text-blue-600 hover:text-blue-800">Portfolio</a>
        <?php if ($album['parent_id']): ?>
            <span class="mx-2">/</span>
            <a href="/portfolio/region/<?= htmlspecialchars($album['parent_title']) ?>"
               class="text-blue-600 hover:text-blue-800">
                <?= htmlspecialchars($album['parent_title']) ?>
            </a>
        <?php endif; ?>
        <span class="mx-2">/</span>
        <span class="text-gray-600"><?= htmlspecialchars($album['title']) ?></span>
    </div>

    <header class="album-header mb-12">
        <h1 class="text-4xl font-bold mb-4"><?= htmlspecialchars($album['title']) ?></h1>
        <?php if ($album['description']): ?>
            <p class="text-xl text-gray-600 max-w-3xl">
                <?= htmlspecialchars($album['description']) ?>
            </p>
        <?php endif; ?>
    </header>

    <div class="album-photos grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($album['photos'] as $photo): ?>
            <div class="photo-card group relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-300">
                <a href="/portfolio/photo/<?= $album['id'] ?>/<?= $photo['id_picture'] ?>"
                   class="block aspect-square">
                    <img
                            src="<?= htmlspecialchars($photo['path_picture']) ?>"
                            alt="<?= htmlspecialchars($photo['alt_picture']) ?>"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                    />
                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white p-4 transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                        <h3 class="text-lg font-semibold">
                            <?= htmlspecialchars($photo['nom_picture']) ?>
                        </h3>
                        <?php if (!empty($photo['texte_picture'])): ?>
                            <p class="text-sm mt-1">
                                <?= htmlspecialchars($photo['texte_picture']) ?>
                            </p>
                        <?php endif; ?>
                        <?php if (!empty($photo['city_adress']) || !empty($photo['gps_cordinate'])): ?>
                            <p class="text-xs mt-2">
                                <i class="fas fa-map-marker-alt"></i>
                                <?= htmlspecialchars($photo['city_adress'] . ', ' . $photo['country_adress']) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($album['photos'])): ?>
        <div class="text-center py-12">
            <p class="text-gray-600">Aucune photo n'est disponible dans cet album pour le moment.</p>
        </div>
    <?php endif; ?>

    <div class="album-location mt-12">
        <h2 class="text-2xl font-bold mb-6">Localisation des Sites</h2>
        <div class="h-96 bg-gray-100 rounded-lg shadow-md">
            <!-- Carte à implémenter -->
        </div>
    </div>
</div>