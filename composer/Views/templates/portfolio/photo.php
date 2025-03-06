<?php
// views/templates/portfolio/photo.php
?>
<div class="photo-viewer min-h-screen bg-gray-100">
    <!-- Navigation en fil d'Ariane -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <nav class="breadcrumb mb-8 text-sm">
            <a href="/portfolio" class="text-blue-600 hover:text-blue-800 transition-colors">Portfolio</a>
            <span class="mx-2">/</span>
            <a href="/portfolio/region/<?= htmlspecialchars($photo['region']) ?>"
               class="text-blue-600 hover:text-blue-800 transition-colors">
                <?= htmlspecialchars($photo['region']) ?>
            </a>
            <span class="mx-2">/</span>
            <a href="/portfolio/album/<?= $photo['album_id'] ?>"
               class="text-blue-600 hover:text-blue-800 transition-colors">
                <?= htmlspecialchars($photo['album_title']) ?>
            </a>
        </nav>

        <!-- Conteneur principal de la photo -->
        <div class="photo-content bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-0">
                <!-- Section de la photo - prend 2/3 de l'espace sur les grands écrans -->
                <div class="lg:col-span-2 relative group">
                    <!-- Image principale avec gestion du zoom -->
                    <div id="photo-container" class="relative overflow-hidden">
                        <img
                            src="<?= htmlspecialchars($photo['path_picture']) ?>"
                            alt="<?= htmlspecialchars($photo['alt_picture']) ?>"
                            class="w-full h-auto object-cover transition-transform duration-300"
                            id="main-photo"
                        />

                        <!-- Contrôles de zoom -->
                        <div class="absolute bottom-4 right-4 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button class="zoom-control bg-white/90 p-2 rounded-full shadow-md hover:bg-white transition-colors"
                                    onclick="zoomIn()">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </button>
                            <button class="zoom-control bg-white/90 p-2 rounded-full shadow-md hover:bg-white transition-colors"
                                    onclick="zoomOut()">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Navigation entre les photos -->
                    <div class="absolute inset-y-0 left-0 flex items-center">
                        <?php if (isset($previousPhoto)): ?>
                            <a href="/portfolio/photo/<?= $previousPhoto['id'] ?>"
                               class="bg-white/80 p-2 rounded-r-lg shadow-md hover:bg-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="absolute inset-y-0 right-0 flex items-center">
                        <?php if (isset($nextPhoto)): ?>
                            <a href="/portfolio/photo/<?= $nextPhoto['id'] ?>"
                               class="bg-white/80 p-2 rounded-l-lg shadow-md hover:bg-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Section des informations - prend 1/3 de l'espace sur les grands écrans -->
                <div class="p-8 border-l border-gray-200">
                    <h1 class="text-2xl font-bold mb-4">
                        <?= htmlspecialchars($photo['nom_picture']) ?>
                    </h1>

                    <?php if (!empty($photo['texte_picture'])): ?>
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold mb-2">Description</h2>
                            <p class="text-gray-600">
                                <?= nl2br(htmlspecialchars($photo['texte_picture'])) ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <!-- Métadonnées techniques -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold">Informations techniques</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <?php if (!empty($photo['auteur_picture'])): ?>
                                <div>
                                    <span class="text-gray-500">Photographe</span>
                                    <p class="font-medium"><?= htmlspecialchars($photo['auteur_picture']) ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($photo['created_at'])): ?>
                                <div>
                                    <span class="text-gray-500">Date de prise</span>
                                    <p class="font-medium">
                                        <?= (new DateTime($photo['created_at']))->format('d/m/Y') ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Localisation -->
                    <?php if (!empty($photo['gps_cordinate'])): ?>
                        <div class="mt-6">
                            <h2 class="text-lg font-semibold mb-2">Localisation</h2>
                            <div class="h-48 bg-gray-100 rounded-lg" id="photo-map">
                                <!-- La carte sera initialisée ici via JavaScript -->
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Gestion du zoom de l'image
    let currentZoom = 1;
    const mainPhoto = document.getElementById('main-photo');
    const photoContainer = document.getElementById('photo-container');

    function zoomIn() {
        if (currentZoom < 3) {  // Limite le zoom maximum
            currentZoom += 0.5;
            updateZoom();
        }
    }

    function zoomOut() {
        if (currentZoom > 1) {  // Ne permet pas de zoomer en dessous de la taille normale
            currentZoom -= 0.5;
            updateZoom();
        }
    }

    function updateZoom() {
        mainPhoto.style.transform = `scale(${currentZoom})`;
    }

    // Gestion du déplacement de l'image en zoom
    let isDragging = false;
    let startX, startY, initialX, initialY;

    photoContainer.addEventListener('mousedown', startDragging);
    photoContainer.addEventListener('mousemove', drag);
    photoContainer.addEventListener('mouseup', stopDragging);
    photoContainer.addEventListener('mouseleave', stopDragging);

    function startDragging(e) {
        if (currentZoom > 1) {
            isDragging = true;
            startX = e.clientX - initialX;
            startY = e.clientY - initialY;
        }
    }

    function drag(e) {
        if (isDragging && currentZoom > 1) {
            e.preventDefault();
            const x = e.clientX - startX;
            const y = e.clientY - startY;

            // Limiter le déplacement en fonction du zoom
            const maxX = (currentZoom - 1) * photoContainer.offsetWidth / 2;
            const maxY = (currentZoom - 1) * photoContainer.offsetHeight / 2;

            initialX = Math.min(Math.max(x, -maxX), maxX);
            initialY = Math.min(Math.max(y, -maxY), maxY);

            mainPhoto.style.transform = `scale(${currentZoom}) translate(${initialX}px, ${initialY}px)`;
        }
    }

    function stopDragging() {
        isDragging = false;
    }

    <?php if (!empty($photo['gps_cordinate'])): ?>
    // Initialisation de la carte si des coordonnées GPS sont disponibles
    function initMap() {
        const coordinates = <?= json_encode($photo['gps_cordinate']) ?>;
        // Code d'initialisation de la carte (à adapter selon votre bibliothèque de cartographie)
    }

    // Chargement de la carte quand le DOM est prêt
    document.addEventListener('DOMContentLoaded', initMap);
    <?php endif; ?>
</script>