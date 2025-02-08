<script>
    // Définition sécurisée de la clé API
    window.TINYMCE_API_KEY = <?= json_encode($_ENV['TINYMCE_API_KEY']) ?>;
</script>

<div class="newsletter-editor">
    <div class="editor-header">
        <h1>Créer une nouvelle newsletter</h1>
        <div class="subscriber-count">
            <span><?= $subscriberCount ?> abonnés actifs</span>
        </div>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="/admin/newsletters/create" method="POST" class="newsletter-form">
        <div class="form-group">
            <label for="title">Titre de la newsletter *</label>
            <input type="text"
                   id="title"
                   name="title"
                   required
                   class="form-control"
                   placeholder="Ex: Newsletter de février 2024"
                   value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="content">Contenu de la newsletter *</label>
            <textarea id="content"
                      name="content"
                      required
                      class="form-control editor"
                      rows="15"><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
        </div>

        <div class="form-actions">
            <a href="/admin/newsletters" class="btn btn-link">Annuler</a>
            <div class="action-buttons">
                <button type="submit" name="action" value="draft" class="btn btn-secondary">
                    <i class="fas fa-save"></i> Enregistrer comme brouillon
                </button>
                <button type="submit" name="action" value="send" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Envoyer la newsletter
                </button>
            </div>
        </div>
    </form>
</div>

<style>
    .newsletter-editor {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
    }

    .editor-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .subscriber-count {
        background: #e9ecef;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        font-weight: 500;
    }

    .newsletter-form {
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

    .form-control {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 1px solid #eee;
    }

    .action-buttons {
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

    .btn-link {
        color: #6c757d;
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
    // Création de l'URL de manière dynamique
    const script = document.createElement('script');
    script.src = `https://cdn.tiny.cloud/1/${window.TINYMCE_API_KEY}/tinymce/6/tinymce.min.js`;
    document.head.appendChild(script);

    // Charger le fichier de langue français
    const langScript = document.createElement('script');
    langScript.src = `https://cdn.tiny.cloud/1/${window.TINYMCE_API_KEY}/tinymce/6/langs/fr_FR.js`;
    document.head.appendChild(langScript);

    // Initialisation de TinyMCE une fois le script chargé
    script.onload = function() {
        tinymce.init({
            selector: '.editor',
            height: 500,
            language: 'fr_FR',
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | styles | bold italic | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | link image | preview',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial; font-size: 16px; }',
        });
    };
</script>

<!--<script src="https://cdn.tiny.cloud/1/${tiny-api-key}/tinymce/6/tinymce.min.js"></script>-->
<!--<script>-->
<!--    tinymce.init({-->
<!--        selector: '.editor',-->
<!--        height: 500,-->
<!--        plugins: [-->
<!--            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',-->
<!--            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',-->
<!--            'insertdatetime', 'media', 'table', 'help', 'wordcount'-->
<!--        ],-->
<!--        toolbar: 'undo redo | styles | bold italic | ' +-->
<!--            'alignleft aligncenter alignright alignjustify | ' +-->
<!--            'bullist numlist outdent indent | link image | preview',-->
<!--        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial; font-size: 16px; }',-->
<!--    });-->
<!--</script>-->