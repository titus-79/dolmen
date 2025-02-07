<div class="edit-profile-container">
    <h1>Modifier mon profil</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="/account/edit" method="POST" class="edit-profile-form">
        <div class="form-group">
            <label for="name_user">Nom</label>
            <input
                type="text"
                id="name_user"
                name="name_user"
                value="<?= htmlspecialchars($user->getName()) ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="firstname_user">Prénom</label>
            <input
                type="text"
                id="firstname_user"
                name="firstname_user"
                value="<?= htmlspecialchars($user->getFirstname()) ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="user_email">Email</label>
            <input
                type="email"
                id="user_email"
                name="user_email"
                value="<?= htmlspecialchars($user->getEmail()) ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="tel_user">Téléphone</label>
            <input
                type="tel"
                id="tel_user"
                name="tel_user"
                value="<?= htmlspecialchars($user->getTel()) ?>"
                pattern="[0-9]{10}"
            >
        </div>

        <div class="form-group">
            <label for="password_hash_user">
                Nouveau mot de passe
                <small>(Laissez vide pour conserver l'actuel)</small>
            </label>
            <input
                type="password"
                id="password_hash_user"
                name="password_hash_user"
                minlength="8"
            >
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            <a href="/account" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<style>
    .edit-profile-container {
        max-width: 600px;
        margin: 2rem auto;
        padding: 2rem;
    }

    .edit-profile-form {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .form-group input {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 4px;
        text-decoration: none;
        cursor: pointer;
        border: none;
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

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>