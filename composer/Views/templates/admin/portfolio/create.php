<!-- Views/templates/admin/portfolio/create.php -->
<div class="album-form-container">
    <div class="page-header">
        <h1>Créer un nouvel album</h1>
        <a href="/admin/portfolio" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form action="/admin/portfolio/create" method="POST" enctype="multipart/form-data" class="album-form">
                <div class="form-group">
                    <label for="title">Titre de l'album *</label>
                    <input type="text"
                           id="title"
                           name="title"
                           class="form-control"
                           required
                           value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="region">Région *</label>
                    <select id="region" name="region" class="form-control" required>
                        <option value="">Sélectionnez une région</option>
                        <?php if (!empty($regions)): ?>
                            <?php foreach ($regions as $regionData): ?>
                                <option value="<?= htmlspecialchars($regionData['region']) ?>"
                                    <?= isset($_POST['region']) && $_POST['region'] === $regionData['region'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($regionData['region']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <option value="nouvelle">+ Ajouter une nouvelle région</option>
                    </select>
                </div>

                <!-- Champ pour la nouvelle région (initialement caché) -->
                <div class="form-group" id="nouvelle-region" style="display: none;">
                    <label for="new-region">Nouvelle région *</label>
                    <input type="text"
                           id="new-region"
                           name="new_region"
                           class="form-control"
                           value="<?= htmlspecialchars($_POST['new_region'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="parent_id">Album parent</label>
                    <select id="parent_id" name="parent_id" class="form-control">
                        <option value="">Aucun (Album principal)</option><?php
                        foreach ($mainAlbums as $album):
                            $selected = isset($_POST['parent_id']) && $_POST['parent_id'] == $album['id'] ? 'selected' : '';
                            echo sprintf('<option value="%d" %s>%s</option>',
                                $album['id'],
                                $selected,
                                htmlspecialchars($album['title'])
                            );
                        endforeach;?></select>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description"
                              name="description"
                              class="form-control"
                              rows="4"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="thumbnail">Image de couverture *</label>
                    <input type="file"
                           id="thumbnail"
                           name="thumbnail"
                           class="form-control"
                           accept="image/jpeg,image/png,image/webp"
                           required>
                    <small class="form-text text-muted">
                        Formats acceptés : JPG, PNG ou WebP<br>
                        Taille maximale : 10 MB<br>
                        Dimensions recommandées : minimum 800x600px
                    </small>
                    <div id="preview-container"></div>
                </div>

                <div class="form-group">
                    <label for="photos">Photos de l'album</label>
                    <input type="file"
                           id="photos"
                           name="photos[]"
                           multiple
                           accept="image/jpeg,image/png,image/webp"
                           class="form-control">
                    <small class="form-text text-muted">
                        Vous pouvez sélectionner plusieurs photos à la fois
                    </small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Créer l'album
                    </button>
                    <a href="/admin/portfolio" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .album-form-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .page-header {
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
        font-size: 1rem;
    }

    .form-text {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    .form-actions {
        margin-top: 2rem;
        display: flex;
        gap: 1rem;
    }

    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 4px;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const regionSelect = document.getElementById('region');
        const nouvelleRegionDiv = document.getElementById('nouvelle-region');
        const newRegionInput = document.getElementById('new-region');
        const form = document.querySelector('.album-form');
        const fileInput = document.getElementById('thumbnail');


        function toggleNouvelleRegion() {
            if (regionSelect.value === 'nouvelle') {
                nouvelleRegionDiv.style.display = 'block';
                newRegionInput.required = true;
            } else {
                nouvelleRegionDiv.style.display = 'none';
                newRegionInput.required = false;
                newRegionInput.value = ''; // Effacer la valeur si on change
            }
        }

        regionSelect.addEventListener('change', toggleNouvelleRegion);
        // Exécuter au chargement pour gérer les valeurs par défaut
        toggleNouvelleRegion();

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            const maxSize = 10 * 1024 * 1024; // 10 MB
            const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

            if (file) {
                if (file.size > maxSize) {
                    alert('Le fichier est trop volumineux (maximum 10 MB)');
                    this.value = ''; // Réinitialise l'input
                    return;
                }

                if (!allowedTypes.includes(file.type)) {
                    alert('Type de fichier non autorisé (JPG, PNG ou WebP uniquement)');
                    this.value = '';
                    return;
                }

                // Optionnel : Prévisualisation de l'image
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Créer ou mettre à jour la prévisualisation
                    let preview = document.getElementById('image-preview');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.id = 'image-preview';
                        preview.style.maxWidth = '200px';
                        preview.style.marginTop = '10px';
                        fileInput.parentNode.appendChild(preview);
                    }
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
