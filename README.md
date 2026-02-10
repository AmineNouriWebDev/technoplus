# TechnoPlus - Site E-Commerce

Site e-commerce pour TechnoPlus développé en PHP/MySQL.

## 🔒 Sécurité

Ce repository ne contient **aucun mot de passe ni clé API**. Toutes les informations sensibles sont gérées via des variables d'environnement (fichier `.env`).

## 📋 Prérequis

- PHP 7.4+
- MySQL 5.7+
- Serveur web (Apache/Nginx)
- Composer (optionnel)

## 🚀 Installation Locale (XAMPP)

### 1. Cloner le repository

```bash
git clone https://github.com/VOTRE_USERNAME/technoplus.git
cd technoplus
```

### 2. Configuration de l'environnement

```bash
# Copier le fichier d'exemple
copy .env.example .env

# Éditer .env avec vos credentials locaux
notepad .env
```

**Remplir au minimum ces variables dans `.env` :**
```env
DB_HOST_LOCAL=localhost
DB_USER_LOCAL=root
DB_PASS_LOCAL=
DB_NAME_LOCAL=technopl_db
```

### 3. Importer la base de données

1. Créer une base de données `technopl_db` dans phpMyAdmin
2. Importer le fichier SQL (à obtenir séparément)

### 4. Dossier Media

⚠️ **Le dossier `media/` n'est pas inclus dans Git** (trop volumineux - 5.8 GB).

Pour développer en local :
- Télécharger le dossier `media/` séparément (via FTP depuis le serveur ou archive)
- Le placer à la racine du projet : `c:\xampp\htdocs\technoplus\media\`

### 5. Accéder au site

```
http://localhost/technoplus/
```

## 🌐 Déploiement en Production

### 1. Sur le serveur, créer le fichier `.env`

```bash
nano .env
```

### 2. Remplir avec les credentials de production

```env
DB_HOST_PROD=localhost
DB_USER_PROD=technopl_dbuser19985
DB_PASS_PROD=VOTRE_MOT_DE_PASSE_PROD
DB_NAME_PROD=technopl_db

GOOGLE_CLIENT_ID=...
GOOGLE_CLIENT_SECRET=...
FACEBOOK_APP_ID=...
FACEBOOK_APP_SECRET=...
```

### 3. Upload des fichiers

- Via Git : `git pull origin main`
- Ou via FTP/cPanel

### 4. Gérer le dossier media

Le dossier `media/` doit être uploadé séparément via :
- FTP
- rsync
- cPanel File Manager

## 📁 Structure du Projet

```
technoplus/
├── .env                  # Configuration locale (NON VERSIONNÉ)
├── .env.example         # Template de configuration
├── .gitignore           # Fichiers exclus de Git
├── env_loader.php       # Utilitaire de chargement .env
├── connec.php           # Connexion BDD (utilise .env)
├── config.php           # Configuration OAuth (utilise .env)
├── index.php            # Page d'accueil
├── media/               # Images et médias (NON VERSIONNÉ)
├── _admin_site/         # Panel d'administration (NON VERSIONNÉ)
└── ...
```

## 🔧 Fichiers de Configuration

### Fichiers utilisant les variables d'environnement

- `connec.php` - Connexion base de données
- `config.php` - Configuration Google/Facebook OAuth
- `config_google_facebook.php` - Configuration API Google

Ces fichiers chargent automatiquement les bonnes credentials selon l'environnement (local/production).

## ⚠️ Important

- **Ne jamais commit le fichier `.env`** (déjà dans `.gitignore`)
- **Ne jamais pusher le dossier `media/`** (trop volumineux)  
- **Toujours tester en local avant de déployer**

## 🛠️ Développement

### Tester la connexion .env

Un fichier de test est disponible :
```
http://localhost/technoplus/test_env.php
```

### Activer le mode débug

Uniquement en développement local, dans votre `.env` :
```env
DEBUG_MODE=true
```

## 📞 Support

Pour toute question, contacter l'équipe TechnoPlus.

## 📄 Licence

Propriétaire - TechnoPlus © 2026
