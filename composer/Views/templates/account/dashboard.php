<?php
// composer/Views/templates/account/dashboard.php
?>
<div class="dashboard-container">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>


    <div class="user-info">
        <h1>Bienvenue, <?= htmlspecialchars($user->getFirstname()) ?></h1>
        <div class="profile-card">
            <div class="dashboard-container">
                <!-- Section d'administration - visible uniquement pour les administrateurs -->
                <?php if ($user->hasRole('Admin')): ?>
                    <div class="admin-access-card">
                        <h2>Administration</h2>
                        <p>En tant qu'administrateur, vous avez accès au panneau de gestion du site.</p>
                        <div class="admin-actions">
                            <a href="/admin" class="btn btn-admin">
                                <i class="fas fa-cog"></i> Accéder à l'interface d'administration
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            <h2>Informations personnelles</h2>
            <table class="info-table">
                <tr>
                    <th>Nom :</th>
                    <td><?= htmlspecialchars($user->getName()) ?></td>
                </tr>
                <tr>
                    <th>Prénom :</th>
                    <td><?= htmlspecialchars($user->getFirstname()) ?></td>
                </tr>
                <tr>
                    <th>Email :</th>
                    <td><?= htmlspecialchars($user->getEmail()) ?></td>
                </tr>
                <tr>
                    <th>Téléphone :</th>
                    <td><?= htmlspecialchars($user->getTel() ?? 'Non renseigné') ?></td>
                </tr>
                <tr>
                    <th>Membre depuis :</th>
                    <td><?= $memberSince ? $memberSince->format('d/m/Y') : 'Non disponible' ?></td>
                </tr>
                <tr>
                    <th>Abonné à la NewsLetters</th>
                    <td><?= $user->isSubscribedToNewsletter() ? 'Oui' : 'Non' ?></td>
                </tr>
            </table>
            <div class="actions">
                <a href="/account/edit" class="btn btn-primary">Modifier mon profil</a>
            </div>
        </div>

        <?php if (!empty($orders)): ?>
            <div class="orders-card">
                <h2>Dernières commandes</h2>
                <div class="orders-list">
                    <?php foreach ($orders as $order): ?>
                        <div class="order-item">
                            <div class="order-header">
                                <span class="order-number">Commande #<?= htmlspecialchars($order['id_order']) ?></span>
                                <span class="order-date"><?= (new DateTime($order['created_at']))->format('d/m/Y') ?></span>
                            </div>
                            <div class="order-details">
                                <span class="order-status"><?= htmlspecialchars($order['status']) ?></span>
                                <span class="order-total"><?= number_format($order['total_amount'], 2) ?> €</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<style>
    .dashboard-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 4px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .profile-card, .orders-card {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .info-table {
        width: 100%;
        margin: 1rem 0;
    }

    .info-table th {
        text-align: left;
        padding: 0.5rem;
        width: 30%;
    }

    .info-table td {
        padding: 0.5rem;
    }

    .btn {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        text-decoration: none;
        margin-top: 1rem;
    }

    .btn-primary {
        background: #007bff;
        color: white;
    }

    .orders-list {
        display: grid;
        gap: 1rem;
    }

    .order-item {
        border: 1px solid #eee;
        padding: 1rem;
        border-radius: 4px;
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .order-details {
        display: flex;
        justify-content: space-between;
        color: #666;
    }

    @media (max-width: 768px) {
        .info-table th {
            width: 40%;
        }
    }
</style>