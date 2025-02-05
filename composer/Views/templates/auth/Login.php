<?php
?>

<main class="auth-container">
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
        <div class="form-group">
            <label for="login_user">Login</label>
            <input type="text"
                   name="login_user"
                   id="login_user"
                   required
                   autofocus
                   placeholder="Votre login">
        </div>

        <div class="form-group">
            <label for="password_hash_user">Mot de passe</label>
            <input type="password"
                   name="password_hash_user"
                   id="password_hash_user"
                   required
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
</main>