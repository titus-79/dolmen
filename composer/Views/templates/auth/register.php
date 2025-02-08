<div class="registration-container">
    <h1>Créer un compte</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?= $_SESSION['error'] ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="/register" method="POST" class="registration-form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

        <div class="form-group">
            <label for="name_user">Nom *</label>
            <input type="text"
                   id="name_user"
                   name="name_user"
                   required
                   value="<?= htmlspecialchars($_SESSION['form_data']['name'] ?? '') ?>"
                   class="form-control">
        </div>

        <div class="form-group">
            <label for="firstname_user">Prénom *</label>
            <input type="text"
                   id="firstname_user"
                   name="firstname_user"
                   required
                   value="<?= htmlspecialchars($_SESSION['form_data']['firstname'] ?? '') ?>"
                   class="form-control">
        </div>

        <div class="form-group">
            <label for="login_user">Login *</label>
            <input type="text"
                   id="login_user"
                   name="login_user"
                   required
                   pattern="[a-zA-Z0-9_-]{3,20}"
                   title="Entre 3 et 20 caractères (lettres, chiffres, tirets et underscores)"
                   value="<?= htmlspecialchars($_SESSION['form_data']['login'] ?? '') ?>"
                   class="form-control">
        </div>

        <div class="form-group">
            <label for="user_email">Email *</label>
            <input type="email"
                   id="user_email"
                   name="user_email"
                   required
                   value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '') ?>"
                   class="form-control">
        </div>

        <div class="form-group">
            <label for="tel_user">Téléphone</label>
            <input type="tel"
                   id="tel_user"
                   name="tel_user"
                   pattern="[0-9]{10}"
                   value="<?= htmlspecialchars($_SESSION['form_data']['tel'] ?? '') ?>"
                   class="form-control">
        </div>

        <div class="form-group">
            <label for="password_hash_user">Mot de passe *</label>
            <input type="password"
                   id="password_hash_user"
                   name="password_hash_user"
                   required
                   minlength="8"
                   class="form-control">
            <small class="form-text text-muted">
                Le mot de passe doit contenir au moins 8 caractères
            </small>
        </div>

        <div class="form-group">
            <label for="password_confirm">Confirmer le mot de passe *</label>
            <input type="password"
                   id="password_confirm"
                   required
                   minlength="8"
                   class="form-control">
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

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Créer mon compte</button>
            <a href="/login" class="btn btn-link">Déjà inscrit ? Se connecter</a>
        </div>
    </form>
</div>

<style>
    .registration-container {
        max-width: 600px;
        margin: 2rem auto;
        padding: 2rem;
    }

    .registration-form {
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
    }

    .form-control {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }

    .newsletter-option {
        display: flex;
        gap: 0.5rem;
        align-items: flex-start;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 4px;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 2rem;
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

    .text-muted {
        color: #6c757d;
    }

    .btn {
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
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.registration-form');
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