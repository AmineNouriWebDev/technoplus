# 🚀 Guide de Déploiement Sécurisé - TechnoPlus

Ce guide vous explique **étape par étape** comment tester la configuration sécurisée et pusher votre code sur GitHub.

## ⚠️ IMPORTANT : NE TOUCHEZ PAS AU SITE EN LIGNE

**Tout sera fait en LOCAL uniquement.** Le site en production (`technoplus.io`) ne sera pas affecté.

---

## 📝 Étape 1 : Tester la Configuration .env

### 1.1 Vérifier que les fichiers ont été créés

Dans votre dossier `c:\xampp\htdocs\technoplus\`, vous devriez avoir :

```
✅ .env                              (vos vrais credentials)
✅ .env.example                      (template sans credentials)
✅ .gitignore                        (liste des fichiers à exclure)
✅ env_loader.php                    (chargeur de .env)
✅ connec_secure.php                 (version sécurisée)
✅ config_secure.php                 (version sécurisée)
✅ config_google_facebook_secure.php (version sécurisée)
✅ test_env.php                      (script de test)
✅ README.md                         (documentation)
```

### 1.2 Tester le chargement du .env

1. Ouvrez votre navigateur
2. Allez sur : `http://localhost/technoplus/test_env.php`
3. Vous devriez voir :
   - ✅ "Fichier .env chargé avec succès"
   - ✅ "Connexion à la base de données réussie"
   - ✅ Toutes les variables marquées "Définie"

**Si vous voyez des erreurs**, contactez-moi avant de continuer.

---

## 🔄 Étape 2 : Remplacer les Fichiers Originaux (PRUDENT)

**⚠️ IMPORTANT : Nous allons faire des sauvegardes d'abord !**

### 2.1 Créer un dossier de sauvegarde

```powershell
# Dans le dossier technoplus
mkdir backup_old_config
```

### 2.2 Sauvegarder les anciens fichiers

```powershell
copy connec.php backup_old_config\connec.php.backup
copy config.php backup_old_config\config.php.backup
copy config_google_facebook.php backup_old_config\config_google_facebook.php.backup
```

### 2.3 Remplacer par les versions sécurisées

```powershell
# Supprimer les anciens (on a des backups)
del connec.php
del config.php  
del config_google_facebook.php

# Renommer les versions sécurisées
ren connec_secure.php connec.php
ren config_secure.php config.php
ren config_google_facebook_secure.php config_google_facebook.php
```

### 2.4 Tester que le site fonctionne toujours

1. Ouvrez `http://localhost/technoplus/`
2. **Vérifiez que le site s'affiche correctement**
3. Testez :
   - Navigation dans le site
   - Affichage des produits
   - Connexion au compte (si vous en avez un)

**Si tout fonctionne → Parfait ! Passez à l'étape suivante.**

**Si ça ne fonctionne pas :**
```powershell
# Restaurer les anciens fichiers
copy backup_old_config\connec.php.backup connec.php
copy backup_old_config\config.php.backup config.php
copy backup_old_config\config_google_facebook.php.backup config_google_facebook.php
```

---

## 🎯 Étape 3 : Initialiser Git (Local Uniquement)

### 3.1 Vérifier que Git est installé

```powershell
git --version
```

Si vous voyez `git version x.x.x` → OK

Sinon, installez Git : https://git-scm.com/download/win

### 3.2 Initialiser le repository local

```powershell
cd c:\xampp\htdocs\technoplus
git init
```

### 3.3 Vérifier ce qui sera versionné

```powershell
git status
```

**Vous devriez voir :**
- ✅ `.gitignore` (en vert ou "untracked")
- ✅ `.env.example` (en vert)
- ✅ `README.md`, `env_loader.php`, etc.

**Vous NE devriez PAS voir :**
- ❌ `.env` (doit être ignoré)
- ❌ `media/` (doit être ignoré)
- ❌ `error_log` (doit être ignoré)
- ❌ Les anciens fichiers `.php.backup`

### 3.4 Ajouter tous les fichiers

```powershell
git add .
```

### 3.5 Vérifier à nouveau

```powershell
git status
```

**Assurez-vous que `.env` n'apparaît PAS dans la liste !**

### 3.6 Créer le premier commit

```powershell
git commit -m "Initial commit - Configuration sécurisée avec .env"
```

---

## 🌐 Étape 4 : Créer le Repository sur GitHub

### 4.1 Se connecter à GitHub

1. Allez sur https://github.com
2. Connectez-vous avec votre compte

### 4.2 Créer un nouveau repository

1. Cliquez sur le bouton **[+]** en haut à droite
2. Sélectionnez **"New repository"**
3. Remplissez :
   - **Repository name :** `technoplus` (ou autre nom)
   - **Description :** "Site e-commerce TechnoPlus"
   - **Visibility :** 
     - ✅ **Private** (recommandé) - Seul vous pouvez voir
     - ⚠️ **Public** - Tout le monde peut voir (mais sans les mots de passe)
   - ⚠️ **NE COCHEZ PAS** "Initialize with README" (on a déjà le nôtre)
4. Cliquez sur **"Create repository"**

### 4.3 Lier votre repo local à GitHub

GitHub vous donnera des commandes. Utilisez celles-ci :

```powershell
git remote add origin https://github.com/VOTRE_USERNAME/technoplus.git
git branch -M main
```

### 4.4 Push vers GitHub

```powershell
git push -u origin main
```

**Authentification :**
- Username : votre username GitHub
- Password : utilisez votre **token** `github_pat_11BJKIJ.....`

---

## ✅ Étape 5 : Vérification Finale

### 5.1 Vérifier sur GitHub

1. Allez sur votre repository : `https://github.com/VOTRE_USERNAME/technoplus`
2. **Vérifiez que vous voyez :**
   - ✅ `.gitignore`
   - ✅ `.env.example`
   - ✅ `README.md`
   - ✅ `connec.php`, `config.php` (versions sécurisées)
   - ✅ La plupart des fichiers PHP

3. **Vérifiez que vous NE voyez PAS :**
   - ❌ `.env` (doit être absent)
   - ❌ Dossier `media/`
   - ❌ Dossier `_admin_site/`

### 5.2 Vérifier qu'aucun mot de passe n'est visible

1. Cliquez sur `connec.php` sur GitHub
2. **Vous devriez voir** :
   ```php
   $conn['user_pass'] = EnvLoader::get('DB_PASS_PROD');
   ```
   
3. **Vous NE devriez PAS voir** :
   ```php
   $conn['user_pass'] = "Techno+u2698iO$";  // ❌ PAS COMME ÇA !
   ```

---

## 🎉 C'est Terminé !

**Félicitations !** Votre code est maintenant sur GitHub de manière sécurisée :

- ✅ Aucun mot de passe n'est visible
- ✅ Les fichiers volumineux sont exclus
- ✅ Le site fonctionne toujours en local
- ✅ Votre code est versionné et sauvegardé

---

## 📌 Notes Pour le Futur

### Quand vous faites des modifications

```powershell
git add .
git commit -m "Description de vos modifications"
git push
```

### Si vous travaillez sur un autre PC

1. Cloner le repository :
   ```powershell
   git clone https://github.com/VOTRE_USERNAME/technoplus.git
   ```

2. Copier `.env.example` vers `.env` :
   ```powershell
   copy .env.example .env
   ```

3. Éditer `.env` avec vos credentials locaux
4. Créer la base de données
5. Télécharger le dossier `media/` séparément (5.8 GB)

### Pour le serveur de production

1. Upload des fichiers via Git ou FTP
2. Créer un fichier `.env` sur le serveur avec les credentials de PRODUCTION
3. Uploader le dossier `media/` via FTP

---

## 🆘 En Cas de Problème

### Le site ne fonctionne plus après le remplacement

```powershell
copy backup_old_config\*.backup .\
ren connec.php.backup connec.php
ren config.php.backup config.php
ren config_google_facebook.php.backup config_google_facebook.php
```

### Git refuse de push

Utilisez votre token GitHub comme mot de passe, pas votre mot de passe de compte.

### J'ai accidentellement commit .env

```powershell
git rm --cached .env
git commit -m "Remove .env from tracking"
git push
```

---

## 📞 Support

Si vous rencontrez des difficultés à n'importe quelle étape, arrêtez-vous et demandez de l'aide avant de continuer.

**Rappel : Le site en production reste intact. Toutes ces manipulations sont en LOCAL uniquement.**
