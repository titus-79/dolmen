<!-- Views/templates/admin/portfolio/index.php -->
<div class="portfolio-admin">
    <div class="page-header">
        <h1>Gestion du Portfolio</h1>
        <div class="header-actions">
            <a href="/admin" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour au dashboard
            </a>
            <a href="/admin/portfolio/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouvel Album
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="portfolio-grid">
        <?php if (empty($albums)): ?>
            <div class="empty-state">
                <p>Aucun album n'a été créé pour le moment.</p>
                <a href="/admin/portfolio/create" class="btn btn-primary">
                    Créer votre premier album
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($albums as $album): ?>
                <div class="album-card">
                    <div class="album-thumbnail">
                        <img src="<?= htmlspecialchars($album['thumbnail_path']) ?>"
                             alt="<?= htmlspecialchars($album['title']) ?>"
                             class="album-image">
                    </div>
                    <div class="album-info">
                        <h3><?= htmlspecialchars($album['title']) ?></h3>
                        <p class="region"><?= htmlspecialchars($album['region']) ?></p>
                        <?php if (!empty($album['subAlbums'])): ?>
                            <p class="sub-albums">
                                <?= count($album['subAlbums']) ?> sous-albums
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="album-actions">
                        <a href="/admin/portfolio/edit/<?= $album['id'] ?>"
                           class="btn btn-sm btn-info"
                           title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="confirmDelete(<?= $album['id'] ?>)"
                                class="btn btn-sm btn-danger"
                                title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    function confirmDelete(albumId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cet album ?')) {
            window.location.href = `/admin/portfolio/delete/${albumId}`;
        }
    }
</script>

<style>
    .portfolio-admin {
        padding: 2rem;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .portfolio-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
    }

    .album-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .album-thumbnail {
        height: 200px;
        overflow: hidden;
    }

    .album-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .album-info {
        padding: 1rem;
    }

    .album-actions {
        padding: 1rem;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.875rem;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
    }

    .btn-primary { background: #007bff; color: white; }
    .btn-secondary { background: #6c757d; color: white; }
    .btn-info { background: #17a2b8; color: white; }
    .btn-danger { background: #dc3545; color: white; }
</style>

<script>
    function confirmDelete(albumId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cet album ?')) {
            window.location.href = `/admin/portfolio/delete/${albumId}`;
        }
    }
</script>