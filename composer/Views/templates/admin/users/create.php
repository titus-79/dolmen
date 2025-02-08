<div class="admin-container">
    <div class="admin-header">
        <h1>Créer un nouvel utilisateur</h1>
        <div class="admin-actions">
            <a href="/admin/users" class="btn btn-secondary">
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
            <form action="/admin/users/create" method="POST" class="user-form">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name_user">Nom *</label>
                        <input type="text"
                               class="form-control"
                               id="name_user"
                               name="name_user"
                               required
                               value="<?= htmlspecialchars($_POST['name_user'] ?? '') ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="firstname_user">Prénom *</label>
                        <input type="text"
                               class="form-control"
                               id="firstname_user"
                               name="firstname_user"
                               required
                               value="<?= htmlspecialchars($_POST['firstname_user'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="login_user">Login *</label>
                        <input type="text"
                               class="form-control"
                               id="login_user"
                               name="login_user"
                               required
                               pattern="[a-zA-Z0-9_-]{3,20}"
                               title="Le login doit contenir entre 3 et 20 caractères (lettres, chiffres, tirets et underscores)"
                               value="<?= htmlspecialchars($_POST['login_user'] ?? '') ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email_user">Email *</label>
                        <input type="email"
                               class="form-control"
                               id="email_user"
                               name="email_user"
                               required
                               value="<?= htmlspecialchars($_POST['email_user'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="password_hash_user">Mot de passe *</label>
                        <input type="password"
                               class="form-control"
                               id="password_hash_user"
                               name="password_hash_user"
                               required
                               minlength="8">
                        <small class="form-text text-muted">
                            Le mot de passe doit contenir au moins 8 caractères
                        </small>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="password_confirm">Confirmer le mot de passe *</label>
                        <input type="password"
                               class="form-control"
                               id="password_confirm"
                               required
                               minlength="8">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="tel_user">Téléphone</label>
                        <input type="tel"
                               class="form-control"
                               id="tel_user"
                               name="tel_user"
                               pattern="[0-9]{10}"
                               value="<?= htmlspecialchars($_POST['tel_user'] ?? '') ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Groupes *</label>
                        <div class="groups-checkboxes">
                            <?php foreach ($groups as $group): ?>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="group_<?= $group->getId() ?>"
                                           name="groups[]"
                                           value="<?= $group->getId() ?>"
                                        <?= $group->getName() === 'Member' ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="group_<?= $group->getId() ?>">
                                        <?= htmlspecialchars($group->getName()) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="newsletter-option">
                            <input type="checkbox"
                                   id="newsletter_subscription"
                                   name="newsletter_subscription"
                                   checked
                                   value="1">
                            <label for="newsletter_subscription">
                                Je souhaite recevoir la newsletter et les actualités
                                <small class="text-muted d-block">
                                    Vous pourrez vous désabonner à tout moment depuis votre espace personnel
                                </small>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Créer l'utilisateur
                    </button>
                    <a href="/admin/users" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .admin-container {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
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

    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
        margin-bottom: 1rem;
    }

    .form-group {
        padding-right: 15px;
        padding-left: 15px;
        margin-bottom: 1rem;
    }

    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
    }

    .form-control {
        display: block;
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }

    .form-control:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    }

    .form-text {
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .groups-checkboxes {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .custom-control {
        position: relative;
        padding-left: 1.5rem;
    }

    .form-actions {
        margin-top: 2rem;
        display: flex;
        gap: 1rem;
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

    .btn-primary {
        background: #007bff;
        color: white;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
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

    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
        }

        .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .admin-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.user-form');
        const password = document.getElementById('password_hash_user');
        const confirm = document.getElementById('password_confirm');

        form.addEventListener('submit', function(e) {
            if (password.value !== confirm.value) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas');
            }
        });
    });
</script>