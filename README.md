# 🐳 Docker Vélib WebApp - Environnement de Conteneurisation

## 🔍 À propos de la configuration Docker

Ce projet Docker fournit un environnement de développement et de déploiement entièrement conteneurisé pour l'application Vélib WebApp. La configuration permet de déployer rapidement tous les services nécessaires (serveur web Apache avec PHP, API Flask, base de données MySQL, et scripts d'initialisation) dans des conteneurs isolés mais interconnectés.

> **Projet principal**: Le code source principal de l'application est disponible sur GitHub: [https://github.com/aariisto/Velib_WebApp](https://github.com/aariisto/Velib_WebApp)

> **Architecture Docker** : Cette configuration est composée de 4 services principaux: un conteneur Apache-PHP pour le frontend, un conteneur Flask pour l'API Python qui sert de proxy, un conteneur MySQL pour la base de données, et un conteneur Python pour l'initialisation de la base de données.

## 🔧 Services Docker

- 🌐 &nbsp;**apache-php**

  - Serveur web Apache avec PHP 8.2
  - Héberge les fichiers frontend et les APIs backend PHP
  - Exposé sur le port 80

- 🐍 &nbsp;**flask-app**

  - API Python Flask
  - Sert de proxy pour récupérer et formater les données des stations Vélib
  - Exposé sur le port 5000

- 🗄️ &nbsp;**mysql**

  - Base de données MySQL 8.0
  - Stocke les données utilisateur, les recherches et les réservations
  - Exposé sur le port 3306

- 📊 &nbsp;**python-script**
  - Script Python pour l'initialisation et la mise à jour des données
  - S'exécute après le démarrage de la base de données

## 🔨 Technologies Docker utilisées

- 🐋 &nbsp;**Docker & Docker Compose**

  - Docker Compose version 3.8
  - Multi-conteneurs orchestrés

- 🌐 &nbsp;**Serveur Web**

  - Apache 2 avec PHP 8.2
  - Extension MySQLi pour la connexion à la base de données
  - Module Apache rewrite activé

- 🐍 &nbsp;**Python Services**

  - Python 3.9 (Flask API) et Python 3.8 (Script d'initialisation)
  - Flask, Flask-CORS et Requests pour l'API
  - mysql-connector-python pour le script d'initialisation

- 🗄️ &nbsp;**Base de données**
  - MySQL 8.0
  - Volumes persistants
  - Script d'initialisation SQL automatique

## 📂 Structure du projet Docker

```
Docker_Velib_WebApp/
├── docker-compose.yml         # Configuration principale des services Docker
├── apache-php/                # Configuration du serveur web
│   ├── Dockerfile             # Instructions de build pour Apache+PHP
│   ├── site.conf              # Configuration du virtualhost Apache
│   ├── html/                  # Fichiers frontend (montés depuis le host)
│   └── private/               # Fichiers backend PHP (montés depuis le host)
├── flask-app/                 # Service API Python
│   ├── Dockerfile             # Instructions de build pour Flask
│   ├── app.py                 # Application Flask (proxy API Vélib)
│   └── requirements.txt       # Dépendances Python pour Flask
├── mysql/                     # Configuration de la base de données
│   ├── Dockerfile             # Instructions pour le conteneur du script Python
│   ├── database.sql           # Script d'initialisation de la BDD
│   ├── insersion.py           # Script d'importation des données Vélib
│   └── requirements.txt       # Dépendances Python pour le script
```

## 🚀 Installation et démarrage

### Prérequis

- [Docker](https://www.docker.com/get-started) (version 19.03.0+)
- [Docker Compose](https://docs.docker.com/compose/install/) (version 1.27.0+)
- Au moins 1 Go de RAM disponible pour les conteneurs

### Étapes de déploiement

1. **Cloner le dépôt Git**

   ```bash
   git clone https://github.com/aariisto/Docker_Velib_WebApp
   cd Docker_Velib_WebApp
   ```

2. **Lancer les conteneurs**

   ```bash
   cd Docker_Velib_WebApp
   docker-compose up -d
   ```

3. **Vérifier le statut des conteneurs**

   ```bash
   docker-compose ps
   ```

4. **Accéder à l'application**

   Ouvrez votre navigateur et accédez à `http://localhost`

## ⚙️ Fonctionnalités de la configuration Docker

### Volumes persistants

La configuration inclut un volume Docker nommé `mysql_data` qui assure la persistance des données de la base de données, même après l'arrêt ou la suppression des conteneurs.

### Networking automatique

Docker Compose crée automatiquement un réseau interne qui permet aux conteneurs de communiquer entre eux en utilisant leurs noms comme noms d'hôte (par exemple, le service Flask peut se connecter à la base de données via le nom d'hôte `mysql`).

### Hot-reload pour le développement

Les volumes montés pour les fichiers sources permettent de modifier le code sur la machine hôte et de voir les changements immédiatement reflétés dans l'application sans avoir à reconstruire les images.

### Script d'initialisation automatique

Le conteneur `python-script` exécute automatiquement le script qui importe les données des stations Vélib depuis l'API Flask vers la base de données MySQL.

## 🛠️ Commandes Docker utiles

```bash
# Démarrer les services
docker-compose up -d

# Arrêter les services
docker-compose down

# Voir les logs des conteneurs
docker-compose logs

# Voir les logs d'un conteneur spécifique
docker-compose logs apache-php

# Reconstruire les images (après modifications des Dockerfiles)
docker-compose build

# Se connecter au shell d'un conteneur
docker-compose exec apache-php bash
docker-compose exec flask-app bash
docker-compose exec mysql bash
```

---

Fait avec ❤️ et 🐳 Docker pour simplifier le déploiement de Vélib WebApp.
