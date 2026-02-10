# ⚡ QUICK START - Sécurisation GitHub

## 🎯 Objectif
Pusher votre code TechnoPlus sur GitHub **sans exposer les mots de passe**.

---

## ✅ Actions Réalisées

J'ai créé pour vous :

1. **`.env`** - Vos vrais credentials (jamais sur Git)
2. **`.gitignore`** - Exclut `.env`, `media/`, logs
3. **`env_loader.php`** - Chargeur de variables d'environnement
4. **`connec_secure.php`** - Version sécurisée de connec.php
5. **`config_secure.php`** - Version sécurisée de config.php
6. **`config_google_facebook_secure.php`** - Version sécurisée
7. **`test_env.php`** - Script de test
8. **Documentation complète** (README, DEPLOYMENT_GUIDE)

---

## 🚀 PROCHAINES ÉTAPES (À FAIRE)

### 1️⃣ TESTER (5 minutes)

Ouvrez dans votre navigateur :
```
http://localhost/technoplus/test_env.php
```

**Résultat attendu :**
- ✅ "Fichier .env chargé avec succès"
- ✅ "Connexion à la base de données réussie"

❌ **Si vous voyez des erreurs** → Arrêtez-vous et contactez-moi !

---

### 2️⃣ REMPLACER LES FICHIERS (10 minutes)

#### A. Créer un backup

```powershell
cd c:\xampp\htdocs\technoplus
mkdir backup_old_config
copy connec.php backup_old_config\
copy config.php backup_old_config\
copy config_google_facebook.php backup_old_config\
```

#### B. Remplacer

```powershell
del connec.php
del config.php
del config_google_facebook.php

ren connec_secure.php connec.php
ren config_secure.php config.php
ren config_google_facebook_secure.php config_google_facebook.php
```

#### C. Tester le site

Ouvrez : `http://localhost/technoplus/`

✅ **Le site fonctionne** → Continuez
❌ **Le site ne marche plus** → Restaurez le backup (voir DEPLOYMENT_GUIDE.md section "En cas de problème")

---

### 3️⃣ INITIALISER GIT (5 minutes)

```powershell
cd c:\xampp\htdocs\technoplus
git init
git add .
git commit -m "Initial commit - Configuration sécurisée"
```

---

### 4️⃣ CRÉER LE REPO GITHUB (5 minutes)

1. Allez sur https://github.com
2. Cliquez **[+]** → **New repository**
3. Nom : `technoplus`
4. Visibilité : **Private** (recommandé)
5. **NE PAS** cocher "Initialize with README"
6. Cliquez **Create repository**

---

### 5️⃣ PUSH SUR GITHUB (2 minutes)

Remplacez `VOTRE_USERNAME` par votre username GitHub :

```powershell
git remote add origin https://github.com/VOTRE_USERNAME/technoplus.git
git branch -M main
git push -u origin main
```

**Authentification :**
- Username : votre username GitHub
- Password : votre token `github_pat_11BJKIJ.....`

---

### 6️⃣ VÉRIFIER (2 minutes)

1. Allez sur : `https://github.com/VOTRE_USERNAME/technoplus`

2. **Vérifiez que vous VOYEZ :**
   - ✅ README.md
   - ✅ .gitignore
   - ✅ .env.example
   - ✅ connec.php

3. **Vérifiez que vous NE VOYEZ PAS :**
   - ❌ `.env`
   - ❌ Dossier `media/`

4. **Cliquez sur `connec.php`** et vérifiez **qu'aucun mot de passe n'apparaît**

---

## 📚 Documentation Complète

- **Guide étape par étape :** [DEPLOYMENT_GUIDE.md](file:///c:/xampp/htdocs/technoplus/DEPLOYMENT_GUIDE.md)
- **Documentation projet :** [README.md](file:///c:/xampp/htdocs/technoplus/README.md)
- **Récapitulatif complet :** Voir l'artifact walkthrough.md

---

## ⚠️ IMPORTANT

- Le **site en production** (`technoplus.io`) n'est **PAS affecté**
- Toutes les modifications sont **uniquement en LOCAL**
- Vous pouvez toujours **revenir en arrière** avec les backups

---

## 🆘 Besoin d'Aide ?

Consultez la section "En Cas de Problème" dans [DEPLOYMENT_GUIDE.md](file:///c:/xampp/htdocs/technoplus/DEPLOYMENT_GUIDE.md)

---

## ✨ Une fois terminé

Votre code sera sur GitHub de manière **100% sécurisée** :
- ✅ Aucun mot de passe visible
- ✅ Pas de fichiers volumineux (5.8 GB exclus)
- ✅ Versionné et sauvegardé
- ✅ Le site fonctionne toujours

**Bon courage ! 🚀**
