<?php
// composer/Views/templates/event/index.php
?>
<div class="container events-list">
    <h1>Nos Événements</h1>

    <?php if (empty($events)): ?>
        <p class="no-events">Aucun événement prévu actuellement.</p>
    <?php else: ?>
        <div class="row">
            <?php foreach ($events as $event): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($event['title_event']) ?></h5>
                            <p class="card-text">
                                <strong>Date :</strong> <?= date('d/m/Y', strtotime($event['date_event'])) ?><br>
                                <strong>Lieu :</strong>
                                <?= htmlspecialchars($event['city_adress'] ?? 'Non spécifié') ?>,
                                <?= htmlspecialchars($event['country_adress'] ?? '') ?>
                            </p>
                            <p class="card-text"><?= htmlspecialchars(substr($event['description_event'] ?? '', 0, 100)) ?>...</p>
                            <a href="/events/<?= $event['id_event'] ?>" class="btn btn-primary">Voir les détails</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>