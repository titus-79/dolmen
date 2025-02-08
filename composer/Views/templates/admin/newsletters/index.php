<div class="container newsletters-page">
    <div class="page-header">
        <h1>Gestion des Newsletters</h1>
        <div class="header-actions">
            <a href="/admin" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour au dashboard
            </a>
            <a href="/admin/newsletters/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouvelle Newsletter
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="newsletters-list">
        <?php if (empty($newsletters)): ?>
            <div class="empty-state">
                <p>Aucune newsletter n'a été créée pour le moment.</p>
                <a href="/admin/newsletters/create" class="btn btn-primary">
                    Créer votre première newsletter
                </a>
            </div>
        <?php else: ?>
            <table class="table">
                <thead>
                <tr>
                    <th>Titre</th>
                    <th>Statut</th>
                    <th>Créée le</th>
                    <th>Envoyée le</th>
                    <th>Créée par</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($newsletters as $newsletter): ?>
                    <tr>
                        <td><?= htmlspecialchars($newsletter['title']) ?></td>
                        <td>
                                <span class="badge badge-<?= $newsletter['status'] === 'draft' ? 'warning' : 'success' ?>">
                                    <?= $newsletter['status'] === 'draft' ? 'Brouillon' : 'Envoyée' ?>
                                </span>
                        </td>
                        <td><?= (new DateTime($newsletter['created_at']))->format('d/m/Y H:i') ?></td>
                        <td>
                            <?= $newsletter['sent_at']
                                ? (new DateTime($newsletter['sent_at']))->format('d/m/Y H:i')
                                : '-'
                            ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($newsletter['firstname_user'] . ' ' . $newsletter['name_user']) ?>
                        </td>
                        <td class="actions">
                            <?php if ($newsletter['status'] === 'draft'): ?>
                                <a href="/admin/newsletters/edit/<?= $newsletter['id_newsletter'] ?>"
                                   class="btn btn-sm btn-info"
                                   title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/admin/newsletters/preview/<?= $newsletter['id_newsletter'] ?>"
                                   class="btn btn-sm btn-secondary"
                                   title="Prévisualiser"
                                   target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-sm btn-primary"
                                        onclick="confirmSend(<?= $newsletter['id_newsletter'] ?>)"
                                        title="Envoyer">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            <?php else: ?>
                                <a href="/admin/newsletters/stats/<?= $newsletter['id_newsletter'] ?>"
                                   class="btn btn-sm btn-info"
                                   title="Statistiques">
                                    <i class="fas fa-chart-bar"></i>
                                </a>
                            <?php endif; ?>
                            <button type="button"
                                    class="btn btn-sm btn-danger"
                                    onclick="confirmDelete(<?= $newsletter['id_newsletter'] ?>)"
                                    title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        background: #f8f9fa;
        border-radius: 8px;
        margin: 2rem 0;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-top: 1rem;
    }

    .table th, .table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .table th {
        background: #f8f9fa;
        font-weight: 600;
    }

    .actions {
        display: flex;
        gap: 0.5rem;
    }

    .badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.875rem;
    }

    .badge-warning {
        background: #ffc107;
        color: #000;
    }

    .badge-success {
        background: #28a745;
        color: white;
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
        font-size: 0.75rem;
    }

    .btn-primary {
        background: #007bff;
        color: white;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-info {
        background: #17a2b8;
        color: white;
    }

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 4px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>

<script>
    function confirmSend(id) {
        if (confirm('Êtes-vous sûr de vouloir envoyer cette newsletter ?')) {
            window.location.href = `/admin/newsletters/send/${id}`;
        }
    }

    function confirmDelete(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette newsletter ?')) {
            window.location.href = `/admin/newsletters/delete/${id}`;
        }
    }
</script>