<div class="admin-container">
    <div class="admin-header">
        <h1>Modifier l'album : <?= htmlspecialchars($album['title'] ?? '') ?></h1>
        <div class="admin-actions">
            <a href="/admin/portfolio" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form action="/admin/portfolio/edit/<?= $album['id'] ?>" method="POST" enctype="multipart/form-data" class="album-form">
                <div class="form-group">
                    <label for="title">Titre de l'album *</label>
                    <input type="text"
                           id="title"
                           name="title"
                           class="form-control"
                           required
                           value="<?= htmlspecialchars($album['title'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="region">Région *</label>
                    <select id="region" name="region" class="form-control" required>
                        <option value="">Sélectionnez une région</option>
                        <?php foreach ($regions as $region): ?>
                            <option value="<?= htmlspecialchars($region['region']) ?>"
                                <?= ($album['region'] ?? '') === $region['region'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($region['region']) ?>
                            </option>
                        <?php endforeach; ?>
                        <option value="nouvelle" <?= ($album['region'] ?? '') === 'nouvelle' ? 'selected' : '' ?>>
                            + Ajouter une nouvelle région
                        </option>
                    </select>
                </div>

                <div class="form-group" id="nouvelle-region" style="display: none;">
                    <label for="new-region">Nouvelle région *</label>
                    <input type="text"
                           id="new-region"
                           name="new_region"
                           class="form-control">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description"
                              name="description"
                              class="form-control"
                              rows="4"><?= htmlspecialchars($album['description'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label>Image de couverture actuelle</label>
                    <?php if (!empty($album['thumbnail_path'])): ?>
                        <img src="<?= htmlspecialchars($album['thumbnail_path']) ?>"
                             alt="Couverture actuelle"
                             class="img-thumbnail"
                             style="max-width: 200px;">
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="thumbnail">Nouvelle image de couverture</label>
                    <input type="file"
                           id="thumbnail"
                           name="thumbnail"
                           class="form-control"
                           accept="image/jpeg,image/png,image/webp">
                    <small class="form-text text-muted">
                        Laissez vide pour conserver l'image actuelle.<br>
                        Formats acceptés : JPG, PNG ou WebP<br>
                        Taille maximale : 10 MB
                    </small>
                </div>

                <div class="photos-management">
                    <h3>Photos de l'album</h3>

                    <!-- Upload de nouvelles photos -->
                    <div class="form-group">
                        <label for="new_photos">Ajouter des photos</label>
                        <input type="file"
                               id="new_photos"
                               name="new_photos[]"
                               multiple
                               accept="image/jpeg,image/png,image/webp"
                               class="form-control">
                        <small class="form-text text-muted">
                            Vous pouvez sélectionner plusieurs photos à la fois
                        </small>
                    </div>

                    <!-- Liste des photos existantes -->
                    <div class="existing-photos" id="sortable-photos">
                        <?php
                        if (!empty($album['photos'])):
                            error_log("Photos dans l'album : " . print_r($album['photos'], true));
                            ?>
                            <?php foreach ($album['photos'] as $photo): ?>
                            <?php
                            $imagePath = htmlspecialchars($photo['path_picture']);
                            error_log("Chemin de l'image : " . $imagePath);
                            ?>
                            <div class="photo-item" data-photo-id="<?= $photo['id_picture'] ?>">
                                <img src="<?= $imagePath ?>"
                                     alt="<?= htmlspecialchars($photo['alt_picture']) ?>"
                                     class="thumbnail"
                                     onerror="this.src='/assets/images/placeholder.jpg'">

                                <div class="photo-actions">
                                    <div class="photo-info">
                                        <span class="photo-name"><?= htmlspecialchars($photo['nom_picture']) ?></span>
                                        <?php if (!empty($photo['texte_picture'])): ?>
                                            <span class="photo-description"><?= htmlspecialchars($photo['texte_picture']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <label class="delete-photo">
                                        <input type="checkbox"
                                               name="delete_photos[]"
                                               value="<?= $photo['id_picture'] ?>">
                                        Supprimer
                                    </label>

                                    <input type="hidden"
                                           name="photo_order[]"
                                           value="<?= $photo['id_picture'] ?>">

                                    <span class="drag-handle" title="Réorganiser">
                        <i class="fas fa-grip-lines"></i>
                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">Aucune photo dans cet album</p>
                            <?php error_log("Aucune photo trouvée dans l'album"); ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                    <a href="/admin/portfolio" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .admin-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .card-body {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .photos-management {
        margin: 2rem 0;
    }

    .existing-photos {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .photo-item {
        position: relative;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 1rem;
        background: white;
        transition: all 0.3s ease;
    }

    .photo-item:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .photo-item img.thumbnail {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 4px;
        margin-bottom: 1rem;
    }

    .photo-actions {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .photo-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .photo-name {
        font-weight: 500;
        font-size: 0.9rem;
    }

    .photo-description {
        font-size: 0.8rem;
        color: #666;
    } }

    .drag-handle {
        cursor: move;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const regionSelect = document.getElementById('region');
        const nouvelleRegionDiv = document.getElementById('nouvelle-region');
        const newRegionInput = document.getElementById('new-region');

        function toggleNouvelleRegion() {
            if (regionSelect.value === 'nouvelle') {
                nouvelleRegionDiv.style.display = 'block';
                newRegionInput.required = true;
            } else {
                nouvelleRegionDiv.style.display = 'none';
                newRegionInput.required = false;
            }
        }

        regionSelect.addEventListener('change', toggleNouvelleRegion);
        toggleNouvelleRegion(); // Exécuter au chargement
    });

    // Initialisation du tri par glisser-déposer
    new Sortable(document.getElementById('sortable-photos'), {
        handle: '.drag-handle',
        animation: 150,
        onEnd: function() {
            // Mise à jour des champs cachés pour l'ordre
            updatePhotoOrder();
        }
    });

    function updatePhotoOrder() {
        const photoItems = document.querySelectorAll('.photo-item');
        const orderInputs = document.querySelectorAll('input[name="photo_order[]"]');

        photoItems.forEach((item, index) => {
            orderInputs[index].value = item.dataset.photoId;
        });
    }
</script>