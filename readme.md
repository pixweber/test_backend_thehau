# CSV Importer
[![PHP](https://img.shields.io/badge/php->=8.1-8892BF.svg)](https://packagist.org/packages/php-tmdb/api)

## Description
Ces résultats de test réalisés par The Hau LE pour un poste de développeur backend PHP chez Futur Digital. 

## Installation
- Copier, coller le contenu du fichier .zip dans un répertoire et puis créer un virtual host pour ce répertoire
- Editer le fichier src/Config.php pour configurer la connexion à la base de données
- Importer la base de données data/db.sql dans la base de données créée

## Utilisation
- Ouvrir le navigateur et accéder à l'URL du virtual host
- Uploader un fichier CSV et puis voir les résultats

## Base de données
- La base de données est composée de 3 tables: `imports`, `log_appels`, `log_appels_import`
- `imports` stocke les informations des fichiers CSV importés
- `log_appels` stocke les informations des appels, constrain unique sur #[calldate, src] pour éviter les doublons
- `log_appels_import` stocke les informations des appels API pour chaque import
