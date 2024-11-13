# test_technique_laravel - Backend

Suivez les étapes suivantes pour lancer le projet Backend de l'application.

## Installation

Cloner le projet :

```bash
#SSH
git@github.com:johannadznd/test_technique_laravel.git
```
ou
```bash
#HTTP
https://github.com/johannadznd/test_technique_laravel.git
```

Accédez au dossier du projet :

```bash
cd test_technique_laravel
```

## Installation des dépendances

Installez les dépendances via Composer :

```bash
composer install
```

## Environnement
Il est nécessaire de configurer les variables d'environnement pour que le projet fonctionne correctement. Créez un fichier .env à la racine du projet et ajoutez les variables suivantes :

```bash
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nom_de_la_base
DB_USERNAME=utilisateur
DB_PASSWORD=mot_de_passe

FIREBASE_API_KEY=...
FIREBASE_AUTH_DOMAIN=...
FIREBASE_PROJECT_ID=...
FIREBASE_STORAGE_BUCKET=...
FIREBASE_MESSAGING_SENDER_ID=...
FIREBASE_APP_ID=...
FIREBASE_PRIVATE_KEY=...
FIREBASE_CLIENT_EMAIL=…
```


## Lancer le projet en développement

Une fois les dépendances installées et l'environnement configuré, vous pouvez lancer le projet :

```bash
php artisan serve
```

Cela va démarrer le serveur Laravel localement à l'adresse http://localhost:8000.

## Seeders
Pour remplir la base de données avec des données initiales, exécutez les commandes suivantes :

Migrer la base de données :

```bash
php artisan migrate
```

Lancer les seeders :

```bash
php artisan db:seed
```

Cela remplira votre base de données avec les données par défaut définies dans les seeders.

## Tests
Pour lancer les tests de l'application, utilisez :

```bash
php artisan test
```

Cela exécutera tous les tests définis dans le répertoire tests.
