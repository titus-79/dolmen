# Chasseur de Dolmens

Site web pour un photographe spécialisé dans la photographie de dolmens. Le site permet de présenter son portfolio, de vendre des tirages et de gérer des événements.

## Technologies utilisées

- PHP 8.2
- MariaDB 10.6
- Nginx
- Docker & Docker Compose
- PHPMyAdmin

## Prérequis

- Docker
- Docker Compose
- Git

## Installation

1. Clonez le dépôt :
```bash
git clone [URL_DU_REPO]
cd [NOM_DU_PROJET]
```

2. Copiez le fichier .env.example en .env et configurez les variables d'environnement :
```bash
cp .env.example .env
```

Variables d'environnement par défaut :
```
DB_ROOT_PASSWORD=p@ssw0rd
DB_NAME=dolmen
DB_USER=user
DB_PASSWORD=pass
```

3. Construisez et démarrez les conteneurs :
```bash
docker compose up -d --build
```

## Structure du projet

```
.
├── docker/
│   ├── nginx/
│   │   └── default.conf    # Configuration Nginx
│   └── php/
│       └── Dockerfile      # Configuration PHP
├── src/                    # Code source du site
├── database/              # Fichiers d'initialisation de la base de données
├── .env                   # Variables d'environnement
└── docker-compose.yml     # Configuration Docker Compose
```

## Services disponibles

Une fois le projet lancé, vous pouvez accéder aux services suivants :

- Site web : http://localhost
- PHPMyAdmin : http://localhost:8080
  - Serveur : mariadb
  - Utilisateur : user (ou root)
  - Mot de passe : pass (ou p@ssw0rd pour root)
- Base de données MariaDB : 
  - Port : 3306
  - Base de données : dolmen
  - Utilisateur : user
  - Mot de passe : pass

## Commandes utiles

Démarrer les conteneurs :
```bash
docker compose up -d
```

Arrêter les conteneurs :
```bash
docker compose down
```

Voir les logs :
```bash
docker compose logs -f
```

Accéder au shell PHP :
```bash
docker compose exec php bash
```

Accéder à la base de données :
```bash
docker compose exec mariadb mysql -u root -p
```

## Développement

Le code source du site se trouve dans le dossier `src/`. Les modifications sont automatiquement prises en compte grâce au montage du volume dans les conteneurs.

## Base de données

La base de données est automatiquement initialisée avec les scripts SQL présents dans le dossier `database/`. Les données sont persistantes grâce au volume Docker `mariadb_data`.

## Sécurité

- Les mots de passe de la base de données sont stockés dans le fichier .env
- Le fichier .env doit être ajouté au .gitignore
- Les variables d'environnement sont accessibles dans PHP

## Contribution

1. Fork le projet
2. Créez votre branche (`git checkout -b feature/AmazingFeature`)
3. Committez vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Push sur la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

## License

[À définir]

## Contact

[À définir]
