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
<!--        <div id="user">-->
<!--            <a href="/login">-->
<!--                --><?php
//                if (isset($_SESSION['user'])) {
//                ?>
<!--                <img id="account" src="/assets/images/Icones/person.svg" alt="Compte" width="50" height="50">-->
<!--                    --><?php
//                } else {
//                ?>
<!--                <img id="account" src="/assets/images/Icones/person-circle.svg" alt="Compte" width="50" height="50">-->
<!--                    --><?php
//                }
//                ?>
<!--            </a>-->
<!--            <a href="/shop/cart">-->
<!--                --><?php ////todo a modifier icone panier plein
//                if (isset($_SESSION['user'])) {
//                ?>
<!--                <img id="panier" src="/assets/images/Icones/panier.png" alt="Panier">-->
<!--                 --><?php
//                } else {
//                    ?>
<!--                <img id="panier" src="/assets/images/Icones/panier.png" alt="Panier">-->
<!--                --><?php
//                }
//                ?>
<!--            </a>-->
<!--        </div>-->
    <?php
    // Dans navbar.php, section #user
    ?>
    <div id="user">
        <div class="user-menu">
            <?php if (isset($_SESSION['user'])): ?>
                <img id="account" src="/assets/images/Icones/person.svg" alt="Compte" width="50" height="50" class="user-icon">
                <div class="dropdown-menu">
                    <div class="user-info">
                        <?php
                        $user = unserialize(base64_decode($_SESSION['user']));
                        echo htmlspecialchars($user->getFirstname() . ' ' . $user->getName());
                        ?>
                    </div>
                    <a href="/account" class="menu-item">
                        <img src="/assets/images/Icones/user-circle.svg" alt="Mon compte" class="menu-icon">
                        Mon compte
                    </a>
                    <?php if ($user->hasRole('Admin')): ?>
                        <a href="/admin" class="menu-item">
                            <img src="/assets/images/Icones/settings.svg" alt="Administration" class="menu-icon">
                            Administration
                        </a>
                    <?php endif; ?>
                    <a href="/logout" class="menu-item logout">
                        <img src="/assets/images/Icones/logout.svg" alt="Déconnexion" class="menu-icon">
                        Se déconnecter
                    </a>
                </div>
            <?php else: ?>
                <a href="/login">
                    <img id="account" src="/assets/images/Icones/person-circle.svg" alt="Compte" width="50" height="50">
                </a>
            <?php endif; ?>
        </div>

        <a href="/shop/cart">
            <img id="panier" src="/assets/images/Icones/panier.png" alt="Panier">
        </a>
    </div>

    <style>
        .user-menu {
            position: relative;
            display: inline-block;
        }

        .user-icon {
            cursor: pointer;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            min-width: 200px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            z-index: 1000;
        }

        .dropdown-menu.show {
            display: block;
        }

        .user-info {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            font-weight: bold;
            color: #333;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #333;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .menu-item:hover {
            background-color: #f8f9fa;
        }

        .menu-icon {
            width: 20px;
            height: 20px;
            margin-right: 0.75rem;
        }

        .logout {
            border-top: 1px solid #eee;
            color: #dc3545;
        }

        /* Ajout d'une petite flèche en haut du menu */
        .dropdown-menu::before {
            content: '';
            position: absolute;
            top: -8px;
            right: 20px;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-bottom: 8px solid white;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userIcon = document.querySelector('.user-icon');
            const dropdownMenu = document.querySelector('.dropdown-menu');

            if (userIcon && dropdownMenu) {
                // Ouvrir/fermer le menu au clic sur l'icône
                userIcon.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('show');
                });

                // Fermer le menu si on clique ailleurs sur la page
                document.addEventListener('click', function(e) {
                    if (!dropdownMenu.contains(e.target) && !userIcon.contains(e.target)) {
                        dropdownMenu.classList.remove('show');
                    }
                });

                // Demander confirmation avant la déconnexion
                const logoutLink = dropdownMenu.querySelector('.logout');
                if (logoutLink) {
                    logoutLink.addEventListener('click', function(e) {
                        if (!confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                            e.preventDefault();
                        }
                    });
                }
            }
        });
    </script>
    </nav>