<div class="admin-container">
    <div class="admin-header">
        <h1>Gestion des utilisateurs</h1>
        <div class="admin-actions">
            <a href="/admin" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour au dashboard
            </a>
            <a href="/admin/users/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouvel utilisateur
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

    <div class="admin-filters">
        <input type="text" id="userSearch" placeholder="Rechercher un utilisateur..." class="form-control">
        <select id="roleFilter" class="form-control">
            <option value="">Tous les rôles</option>
            <option value="Admin">Administrateurs</option>
            <option value="Member">Membres</option>
        </select>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Abonné Newsletter</th>
                    <th>Rôle</th>
                    <th>Date création</th>
                    <th>Dernière connexion</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user->getId()) ?></td>
                        <td><?= htmlspecialchars($user->getName()) ?></td>
                        <td><?= htmlspecialchars($user->getFirstname()) ?></td>
                        <td><?= htmlspecialchars($user->getEmail()) ?></td>
                        <td><?= htmlspecialchars($user->getTel())?></td>
                        <td><?= $user->isSubscribedToNewsletter() ? 'Oui' : 'Non' ?></td>
                        <td>
                            <?php
                            $groups = $user->getGroups();
                            foreach ($groups as $group): ?>
                                <span class="badge badge-<?= $group === 'Admin' ? 'primary' : 'secondary' ?>">
                                    <?= htmlspecialchars($group) ?>
                                </span>
                            <?php endforeach; ?>
                        </td>
                        <td><?= $user->getCreatedAt()->format('d/m/Y') ?></td>
                        <td>
                            <?= $user->getLastConn() ? $user->getLastConn()->format('d/m/Y H:i') : 'Jamais' ?>
                        </td>
                        <td class="actions">
                            <a href="/admin/users/edit/<?= $user->getId() ?>"
                               class="btn btn-sm btn-info"
                               title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button"
                                    class="btn btn-sm btn-danger"
                                    onclick="confirmDelete(<?= $user->getId() ?>)"
                                    title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.admin-container {
    padding: 2rem;
    max-width: 1400px;
    margin: 0 auto;
}

.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.admin-actions {
    display: flex;
    gap: 1rem;
}

.admin-filters {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.form-control {
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
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

.badge {
    padding: 0.3rem 0.6rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.badge-primary {
    background: #007bff;
    color: white;
}

.badge-secondary {
    background: #6c757d;
    color: white;
}

.actions {
    display: flex;
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
    transition: all 0.2s;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
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

@media (max-width: 768px) {
    .admin-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .admin-filters {
        flex-direction: column;
    }

    .table {
        display: block;
        overflow-x: auto;
    }
}
</style>

<script>
function confirmDelete(userId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
        window.location.href = `/admin/users/delete/${userId}`;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const userSearch = document.getElementById('userSearch');
    const roleFilter = document.getElementById('roleFilter');
    const tableRows = document.querySelectorAll('tbody tr');

    function filterTable() {
        const searchTerm = userSearch.value.toLowerCase();
        const selectedRole = roleFilter.value;

        tableRows.forEach(row => {
            const name = row.children[1].textContent.toLowerCase();
            const firstname = row.children[2].textContent.toLowerCase();
            const email = row.children[3].textContent.toLowerCase();
            const role = row.children[4].textContent;

            const matchesSearch = name.includes(searchTerm) ||
                                firstname.includes(searchTerm) ||
                                email.includes(searchTerm);
            const matchesRole = !selectedRole || role.includes(selectedRole);

            row.style.display = matchesSearch && matchesRole ? '' : 'none';
        });
    }

    userSearch.addEventListener('input', filterTable);
    roleFilter.addEventListener('change', filterTable);
});
</script>