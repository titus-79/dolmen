<?php
// views/templates/portfolio/region.php
?>
<div class="region-container px-4 py-8">
    <div class="breadcrumb mb-8">
        <a href="/portfolio" class="text-blue-600 hover:text-blue-800">Portfolio</a>
        <span class="mx-2">/</span>
        <span class="text-gray-600"><?= htmlspecialchars($region) ?></span>
    </div>

    <header class="region-header text-center mb-12">
        <h1 class="text-4xl font-bold mb-4">Dolmens en <?= htmlspecialchars($region) ?></h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
            Découvrez les monuments mégalithiques de <?= htmlspecialchars($region) ?>,
            témoins de notre histoire ancienne.
        </p>
    </header>

    <div class="region-info grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <div class="region-stats bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4">Aperçu de la Région</h2>
            <ul class="space-y-3">
                <li class="flex justify-between">
                    <span class="text-gray-600">Nombre de sites</span>
                    <span class="font-semibold"><?= count($albums) ?></span>
                </li>
                <li class="flex justify-between">
                    <span class="text-gray-600">Départements couverts</span>
                    <span class="font-semibold">
                        <?= count(array_unique(array_column($albums, 'department'))) ?>
                    </span>
                </li>
            </ul>
        </div>
        <div class="region-map bg-gray-100 rounded-lg shadow-md">
            <!-- Carte de la région à implémenter -->
        </div>
    </div>

    <div class="albums-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($albums as $album): ?>
            <div class="album-card group relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-300">
                <a href="/portfolio/album/<?= $album['id'] ?>" class="block">
                    <img
                        src="<?= htmlspecialchars($album['thumbnail_path']) ?>"
                        alt="<?= htmlspecialchars($album['title']) ?>"
                        class="w-full h-64 object-cover transition-transform duration-300 group-hover:scale-105"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent">
                        <div class="absolute bottom-0 left-0 right-0 p-6">
                            <h3 class="text-xl font-bold text-white mb-2">
                                <?= htmlspecialchars($album['title']) ?>
                            </h3>
                            <?php if (!empty($album['description'])): ?>
                                <p class="text-white/80 text-sm line-clamp-2">
                                    <?= htmlspecialchars($album['description']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($albums)): ?>
        <div class="text-center py-12">
            <p class="text-gray-600">
                Aucun album n'est disponible pour cette région pour le moment.
            </p>
        </div>
    <?php endif; ?>
</div>