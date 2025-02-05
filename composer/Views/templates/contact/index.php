<?php
// composer/Views/templates/contact/index.php
?>
<div class="container contact-page">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h1 class="text-center mb-4">Contactez Chasseur de Dolmens</h1>

            <?php if (isset($_SESSION['contact_success'])): ?>
                <div class="alert alert-success">
                    <?= $_SESSION['contact_success'] ?>
                    <?php unset($_SESSION['contact_success']); ?>
                </div>
            <?php endif; ?>

            <form action="/contact/submit" method="POST">
                <div class="form-group">
                    <label for="name">Nom Complet *</label>
                    <input
                        type="text"
                        class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                        id="name"
                        name="name"
                        value="<?= htmlspecialchars($formData['name'] ?? '') ?>"
                        required
                    >
                    <?php if (isset($errors['name'])): ?>
                        <div class="invalid-feedback"><?= $errors['name'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input
                        type="email"
                        class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                        id="email"
                        name="email"
                        value="<?= htmlspecialchars($formData['email'] ?? '') ?>"
                        required
                    >
                    <?php if (isset($errors['email'])): ?>
                        <div class="invalid-feedback"><?= $errors['email'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="phone">Téléphone</label>
                    <input
                        type="tel"
                        class="form-control"
                        id="phone"
                        name="phone"
                        value="<?= htmlspecialchars($formData['phone'] ?? '') ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="subject">Sujet *</label>
                    <select
                        class="form-control <?= isset($errors['subject']) ? 'is-invalid' : '' ?>"
                        id="subject"
                        name="subject"
                        required
                    >
                        <option value="">Choisissez un sujet</option>
                        <option <?= ($formData['subject'] ?? '') === 'Tirage' ? 'selected' : '' ?>>Tirage</option>
                        <option <?= ($formData['subject'] ?? '') === 'Portfolio' ? 'selected' : '' ?>>Portfolio</option>
                        <option <?= ($formData['subject'] ?? '') === 'Événement' ? 'selected' : '' ?>>Événement</option>
                        <option <?= ($formData['subject'] ?? '') === 'Autre' ? 'selected' : '' ?>>Autre</option>
                    </select>
                    <?php if (isset($errors['subject'])): ?>
                        <div class="invalid-feedback"><?= $errors['subject'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea
                        class="form-control <?= isset($errors['message']) ? 'is-invalid' : '' ?>"
                        id="message"
                        name="message"
                        rows="5"
                        required
                    ><?= htmlspecialchars($formData['message'] ?? '') ?></textarea>
                    <?php if (isset($errors['message'])): ?>
                        <div class="invalid-feedback"><?= $errors['message'] ?></div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    Envoyer votre message
                </button>
            </form>
        </div>
    </div>
</div>