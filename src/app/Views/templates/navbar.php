<nav>
        <div id="burger" class="menu-icon">
            <span></span>
        </div>
        <div>
            <a href="../home/index.php">
                <img id="logo" src="../../../ressources/images/Icones/chasseur_dolmen.svg" alt="Logo" width="50" height="50">
            </a>
        </div>
        <div id="links">
            <a href="../about/about.php">À propos</a>
            <a href="../portfolio/portfolio.php">Porfolio</a>
            <a href="../shop/tirage.php">Tirage</a>
            <a href="../event/event.php">Evènement</a>
            <a href="../contact/contact.php">Contact</a>
        </div>
        <div id="user">
            <a href="../connexion.php">
                <?php
                if (isset($_SESSION['user'])) {
                ?>
                <img id="account" src="../../../ressources/images/Icones/person.svg" alt="Compte" width="50" height="50">
                    <?php
                } else {
                ?>
                <img id="account" src="../../../ressources/images/Icones/person-circle.svg" alt="Compte" width="50" height="50">
                    <?php
                }
                ?>
            </a>
            <a href="../shop/panier.php">
                <?php //todo a modifier icone panier plein
                if (isset($_SESSION['user'])) {
                ?>
                <img id="panier" src="../../../ressources/images/Icones/panier.png" alt="Panier">
                 <?php
                } else {
                    ?>
                <img id="panier" src="../../../ressources/images/Icones/panier.png" alt="Panier">
                <?php
                }
                ?>
            </a>
        </div>
    </nav>