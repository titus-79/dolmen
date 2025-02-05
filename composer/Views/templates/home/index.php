<div id="presentation">
    <?php
    $videoFile = '/assets/video/DJI_0820 - Trim_2.mp4';
    $videoPath = $_SERVER['DOCUMENT_ROOT'] . $videoFile;
    $videoExists = file_exists($videoPath);

    ?>

    <?php if ($videoExists): ?>
        <video class="bg" autoplay muted loop preload="auto"
               onloadeddata="console.log('Vidéo chargée avec succès')"
               onerror="console.error('Erreur de chargement de la vidéo')">
            <source src="<?= htmlspecialchars($videoFile) ?>" type="video/mp4">
            <p class="video-fallback">Votre navigateur ne supporte pas l'élément vidéo.</p>
        </video>
    <?php else: ?>
        <div class="video-error">
            <p>La vidéo n'a pas pu être chargée. Veuillez réessayer plus tard.</p>
            <!-- Vous pourriez ajouter une image de fallback ici -->
            <img src="/assets/images/fallback-video.jpg" alt="Image de remplacement">
        </div>
    <?php endif; ?>
    <h1>Chasseur de Dolmens</h1>
<!--        <h1>--><?php //= htmlspecialchars($welcomeMessage) ?><!--</h1>-->
</div>
<div id="about">
    <img src="/assets/images/Cromlechs%20de%20Mandale,%20Urrugne_1.jpg" alt="photo de l'artiste">
    <div class="content">
        <h2>Sebastien Joffre</h2>
        <p>Lorem projet_dolmen_data ipsum dolor sit amet, consectetur adipisicing elit. Accusantium alias aliquid fugiat laboriosam
            magni minima quidem repellat tempora. Aperiam at aut facilis, itaque recusandae reiciendis similique
            voluptas. Aliquid amet animi dicta distinctio, ducimus error facilis fuga id ipsam maiores mollitia
            nobis odio, odit quam quibusdam soluta sunt suscipit voluptas voluptatum.</p>
    </div>
</div>
<div id="portfolio">
    <h2>Portfolio</h2>
    <img src="/assets/images/portfolio2.jpg" alt="Portfolio">
</div>
<div id="tirage">
    <h2>Tirage</h2>
    <img src="/assets/images/portfolio.jpg" alt="Tirage">
</div>
<div id="event">
    <h2>Evènement</h2>
    <img src="/assets/images/tusson.jpg" alt="Tirage">
</div>
<div id="contact">
    <h2>Contact</h2>
    <a href="../contact/contact.php">
        <img src="/assets/images/contact.jpg" alt="Tirage">
    </a>

</div>
