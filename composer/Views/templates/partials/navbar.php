<nav>
        <div id="burger" class="menu-icon">
            <span></span>
        </div>
        <div>
            <a href="/">
                <img id="logo" src="/assets/images/Icones/chasseur_dolmen.svg" alt="Logo" width="50" height="50">
            </a>
        </div>
        <div id="links">
            <a href="/about">À propos</a>
            <a href="/portfolio">Porfolio</a>
            <a href="/shop">Tirage</a>
            <a href="/events">Evènement</a>
            <a href="/contact">Contact</a>
        </div>
        <div id="user">
            <a href="/login">
                <?php
                if (isset($_SESSION['user'])) {
                ?>
                <img id="account" src="/assets/images/Icones/person.svg" alt="Compte" width="50" height="50">
                    <?php
                } else {
                ?>
                <img id="account" src="/assets/images/Icones/person-circle.svg" alt="Compte" width="50" height="50">
                    <?php
                }
                ?>
            </a>
            <a href="/shop/cart">
                <?php //todo a modifier icone panier plein
                if (isset($_SESSION['user'])) {
                ?>
                <img id="panier" src="/assets/images/Icones/panier.png" alt="Panier">
                 <?php
                } else {
                    ?>
                <img id="panier" src="/assets/images/Icones/panier.png" alt="Panier">
                <?php
                }
                ?>
            </a>
        </div>
    </nav>