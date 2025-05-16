# 🐳 Docker Vélib WebApp - Environnement de Conteneurisation

## 🔍 À propos de la configuration Docker

Ce projet Docker fournit un environnement de développement et de déploiement entièrement conteneurisé pour l'application Vélib WebApp. La configuration permet de déployer rapidement tous les services nécessaires (serveur web Apache avec PHP, API Flask, base de données MySQL, et scripts d'initialisation) dans des conteneurs isolés mais interconnectés.

> **Projet principal**: Le code source principal de l'application est disponible sur GitHub: [https://github.com/aariisto/Velib_WebApp](https://github.com/aariisto/Velib_WebApp)

> **Architecture Docker** : Cette configuration est composée de 4 services principaux: un conteneur Apache-PHP pour le frontend, un conteneur Flask pour l'API Python qui sert de proxy, un conteneur MySQL pour la base de données, et un conteneur Python pour l'initialisation de la base de données.

## 🔧 Services Docker

- 🌐 &nbsp;**apache-php**
  ![Apache](https://img.shields.io/badge/-Apache-333333?style=flat&logo=apache)
  ![PHP 8.2](https://img.shields.io/badge/-PHP%208.2-333333?style=flat&logo=php)
  ![Port 80](https://img.shields.io/badge/-Port%2080-333333?style=flat&logo=serverfault)
  ![Web Server](https://img.shields.io/badge/-Web%20Server-333333?style=flat&logo=internetexplorer)

- 🐍 &nbsp;**flask-app**
  ![Flask](https://img.shields.io/badge/-Flask-333333?style=flat&logo=flask)
  ![Python](https://img.shields.io/badge/-Python-333333?style=flat&logo=python)
  ![API Proxy](https://img.shields.io/badge/-API%20Proxy-333333?style=flat&logo=api)
  ![Port 5000](https://img.shields.io/badge/-Port%205000-333333?style=flat&logo=serverfault)

- 🗄️ &nbsp;**mysql**
  ![MySQL 8.0](https://img.shields.io/badge/-MySQL%208.0-333333?style=flat&logo=mysql)
  ![Database](https://img.shields.io/badge/-Database-333333?style=flat&logo=database)
  ![Data Storage](https://img.shields.io/badge/-Data%20Storage-333333?style=flat&logo=mysql)
  ![Port 3306](https://img.shields.io/badge/-Port%203306-333333?style=flat&logo=serverfault)

- 📊 &nbsp;**python-script**
  ![Python](https://img.shields.io/badge/-Python-333333?style=flat&logo=python)
  ![Data Import](https://img.shields.io/badge/-Data%20Import-333333?style=flat&logo=database)
  ![Initializer](https://img.shields.io/badge/-Initializer-333333?style=flat&logo=docker)
  ![Scheduler](https://img.shields.io/badge/-Scheduler-333333?style=flat&logo=clockify)

## 🔨 Technologies Docker utilisées

- 🐋 &nbsp;**Docker & Docker Compose**
  ![Docker](https://img.shields.io/badge/-Docker-333333?style=flat&logo=docker)
  ![Docker Compose](https://img.shields.io/badge/-Docker%20Compose-333333?style=flat&logo=docker)
  ![YAML](https://img.shields.io/badge/-YAML-333333?style=flat&logo=yaml)

- 🌐 &nbsp;**Serveur Web**
  ![Apache](https://img.shields.io/badge/-Apache-333333?style=flat&logo=apache)
  ![PHP](https://img.shields.io/badge/-PHP%208.2-333333?style=flat&logo=php)
  ![MySQLi](https://img.shields.io/badge/-MySQLi-333333?style=flat&logo=mysql)
  ![mod_rewrite](https://img.shields.io/badge/-mod__rewrite-333333?style=flat&logo=apache)

- 🐍 &nbsp;**Python Services**
  ![Python](https://img.shields.io/badge/-Python-333333?style=flat&logo=python)
  ![Flask](https://img.shields.io/badge/-Flask-333333?style=flat&logo=flask)
  ![Flask-CORS](https://img.shields.io/badge/-Flask--CORS-333333?style=flat&logo=flask)
  ![Requests](https://img.shields.io/badge/-Requests-333333?style=flat&logo=python)
  ![MySQL Connector](https://img.shields.io/badge/-MySQL%20Connector-333333?style=flat&logo=mysql)

- 🗄️ &nbsp;**Base de données**
  ![MySQL](https://img.shields.io/badge/-MySQL%208.0-333333?style=flat&logo=mysql)
  ![Docker Volumes](https://img.shields.io/badge/-Docker%20Volumes-333333?style=flat&logo=docker)
  ![SQL](https://img.shields.io/badge/-SQL-333333?style=flat&logo=database)

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

- 🚀 &nbsp;**Déploiement**
  ![Docker Compose](https://img.shields.io/badge/-Docker%20Compose-333333?style=flat&logo=docker)
  ![Terminal](https://img.shields.io/badge/-Terminal-333333?style=flat&logo=gnubash)

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
