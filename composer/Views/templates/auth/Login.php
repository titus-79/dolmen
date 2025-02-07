<?php
// composer/Views/templates/auth/login.php
?>
<div class="auth-container">
    <h1>Connexion</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <form action="/login" method="post" class="auth-form">
        <!-- Token CSRF -->
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

        <div class="form-group">
            <label for="login_user">Login</label>
            <input type="text"
                   name="login_user"
                   id="login_user"
                   required
                   autofocus
                   autocomplete="username"
                   pattern="[a-zA-Z0-9_-]{3,20}"
                   title="Le login doit contenir entre 3 et 20 caractères (lettres, chiffres, tirets et underscores)"
                   placeholder="Votre login">
        </div>

        <div class="form-group">
            <label for="password_hash_user">Mot de passe</label>
            <input type="password"
                   name="password_hash_user"
                   id="password_hash_user"
                   required
                   minlength="8"
                   autocomplete="current-password"
                   placeholder="Votre mot de passe">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Se connecter</button>
            <a href="/register" class="btn btn-link">Créer un compte</a>
        </div>

        <div class="form-footer">
            <a href="/password/reset" class="forgot-password">Mot de passe oublié ?</a>
        </div>
    </form>
</div>

<style>
    .auth-container {
        max-width: 400px;
        margin: 2rem auto;
        padding: 2rem;
    }

    .auth-form {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
    }

    .form-group input {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .form-actions {
        margin-top: 1.5rem;
    }

    .btn {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        text-decoration: none;
    }

    .btn-primary {
        background: #007bff;
        color: white;
        border: none;
        cursor: pointer;
    }

    .btn-link {
        color: #007bff;
        margin-left: 1rem;
    }

    .form-footer {
        margin-top: 1rem;
        text-align: center;
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

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
</style>