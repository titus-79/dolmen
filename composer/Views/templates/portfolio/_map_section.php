<?php
// views/templates/portfolio/_map_section.php

// Préparation des données pour la carte
$mapLocations = [];
foreach ($albums as $album) {
    // Vérification que l'album a des coordonnées GPS
    if (!empty($album['gps_cordinate'])) {
        // Conversion de la chaîne GPS en coordonnées
        list($lat, $lng) = explode(',', $album['gps_cordinate']);
        $mapLocations[] = [
            'id' => $album['id'],
            'title' => $album['title'],
            'lat' => trim($lat),
            'lng' => trim($lng),
            'thumbnail' => $album['thumbnail_path'],
            'url' => "/portfolio/album/{$album['id']}"
        ];
    }
}
?>

<div class="portfolio-map mt-16">
    <h2 class="text-2xl font-bold text-center mb-6">Carte des Sites</h2>
    <div id="portfolio-map" class="h-96 rounded-lg shadow-md"></div>
</div>

<!-- Inclusion de Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""/>

<!-- Inclusion de Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialisation de la carte
        const map = L.map('portfolio-map');

        // Ajout de la couche de tuiles OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Récupération des données des marqueurs
        const locations = <?= json_encode($mapLocations) ?>;
        const markers = [];
        const bounds = L.latLngBounds();

        // Création d'une icône personnalisée pour les marqueurs
        const dolmenIcon = L.icon({
            iconUrl: '/assets/images/marker-dolmen.png', // Créez cette icône
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        // Ajout des marqueurs à la carte
        locations.forEach(location => {
            const marker = L.marker([location.lat, location.lng], {
                icon: dolmenIcon,
                title: location.title
            }).addTo(map);

            // Création du contenu de la popup
            const popupContent = `
            <div class="map-popup">
                <img src="${location.thumbnail}"
                     alt="${location.title}"
                     class="w-32 h-24 object-cover mb-2 rounded"
                />
                <h3 class="font-semibold">${location.title}</h3>
                <a href="${location.url}"
                   class="text-blue-600 hover:text-blue-800 text-sm">
                    Voir l'album
                </a>
            </div>
        `;

            // Ajout de la popup au marqueur
            marker.bindPopup(popupContent);

            // Ajout du marqueur à la liste et extension des limites
            markers.push(marker);
            bounds.extend([location.lat, location.lng]);
        });

        // Ajustement de la vue de la carte pour montrer tous les marqueurs
        if (markers.length > 0) {
            map.fitBounds(bounds, {
                padding: [50, 50],
                maxZoom: 12
            });
        } else {
            // Vue par défaut si aucun marqueur (centrée sur la France)
            map.setView([46.603354, 1.888334], 6);
        }

        // Gestion du clic sur les marqueurs
        markers.forEach(marker => {
            marker.on('click', function(e) {
                map.setView(e.latlng, Math.max(map.getZoom(), 10));
            });
        });

        // Gestion du redimensionnement
        window.addEventListener('resize', function() {
            map.invalidateSize();
            if (markers.length > 0) {
                map.fitBounds(bounds, {
                    padding: [50, 50],
                    maxZoom: 12
                });
            }
        });
    });
</script>

<style>
    .map-popup {
        min-width: 200px;
        padding: 4px;
    }

    .map-popup img {
        width: 100%;
        height: auto;
        margin-bottom: 8px;
    }

    .leaflet-popup-content-wrapper {
        border-radius: 8px;
    }

    .leaflet-popup-content {
        margin: 8px;
    }
</style>