# 🌐 Guide de Déploiement en PRODUCTION (Hébergeur)

## Comment ça marche maintenant

Votre site utilise un fichier `.env` pour les mots de passe.
- **En local** → `.env` avec credentials LOCAL (root, pas de mot de passe)
- **En production** → `.env` avec credentials PRODUCTION (vrai user, vrai mot de passe)

Le **même code PHP** fonctionne partout. Seul le `.env` change.

---

## 📋 Étapes pour déployer sur votre hébergeur

### Étape 1 : Uploader les fichiers modifiés

Vous avez 2 options :

#### Option A : Via FTP / cPanel File Manager (RECOMMANDÉ pour le moment)

Uploadez ces fichiers **un par un** sur votre hébergeur (via cPanel File Manager ou FileZilla) :

```
connec.php                    → remplace l'ancien sur le serveur
config.php                    → remplace l'ancien sur le serveur
config_google_facebook.php    → remplace l'ancien sur le serveur
env_loader.php                → NOUVEAU fichier à uploader
.env                          → NOUVEAU fichier à créer sur le serveur
```

#### Option B : Via Git sur le serveur (plus avancé)

Si votre hébergeur supporte Git (ex: cPanel → Terminal ou Git Version Control) :

```bash
cd ~/public_html
git clone https://github.com/VOTRE_USERNAME/technoplus.git .
```

Puis créer le `.env` manuellement (voir étape 2).

---

### Étape 2 : Créer le fichier `.env` sur le serveur

**⚠️ CRITIQUE : Le .env de production est DIFFÉRENT de celui en local !**

#### Via cPanel File Manager :

1. Allez dans **cPanel** → **File Manager**
2. Naviguez vers le dossier racine de votre site (ex: `public_html/`)
3. Cliquez sur **+ File** → Nom : `.env`
4. Cliquez sur `.env` → **Edit**
5. Collez ce contenu :

```env
# ========================================
# Configuration Base de Données - LOCAL
# (pas utilisé sur le serveur, mais nécessaire)
# ========================================
DB_HOST_LOCAL=localhost
DB_USER_LOCAL=root
DB_PASS_LOCAL=
DB_NAME_LOCAL=technopl_db

# ========================================
# Configuration Base de Données - PRODUCTION
# ⚠️ METTEZ VOS VRAIS CREDENTIALS ICI
# ========================================
DB_HOST_PROD=localhost
DB_USER_PROD=technopl_dbuser19985
DB_PASS_PROD=Techno+u2698iO$
DB_NAME_PROD=technopl_db

# ========================================
# Chemins du Site
# ========================================
SITE_URL_LOCAL=http://localhost/technoplus/
SITE_URL_PROD=https://technoplus.io/

# ========================================
# Google OAuth
# ========================================
GOOGLE_CLIENT_ID=1017263532819-n3snn8413ancceh0ph6gccvbnevma952.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=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
GOOGLE_REDIRECT_URL=https://technoplus.io/login-with.php?hauth.done=Google

# ========================================
# Facebook OAuth
# ========================================
FACEBOOK_APP_ID=1088373559184938
FACEBOOK_APP_SECRET=31298d7b34bb9e34371e675849031ce0
FACEBOOK_REDIRECT_URL=https://technoplus.io/login-with.php?hauth.done=Facebook
```

6. Sauvegardez

---

### Étape 3 : Protéger le fichier `.env` sur le serveur

**⚠️ TRÈS IMPORTANT :** Empêcher l'accès au `.env` via le navigateur !

#### Via .htaccess (si vous en avez un) :

Ajoutez ces lignes **au début** de votre `.htaccess` :

```apache
# Protéger les fichiers sensibles
<FilesMatch "^\.env">
    Order allow,deny
    Deny from all
</FilesMatch>
```

Cela empêche quiconque d'accéder à `https://technoplus.io/.env` dans un navigateur.

#### Vérification :

Après avoir ajouté les lignes, testez en tapant dans votre navigateur :
```
https://technoplus.io/.env
```

Vous devriez voir une **erreur 403 Forbidden**. Si vous voyez le contenu du fichier → IL Y A UN PROBLÈME, contactez-moi immédiatement !

---

### Étape 4 : Tester le site en production

1. Allez sur `https://technoplus.io/`
2. Vérifiez que le site s'affiche correctement
3. Testez la connexion utilisateur
4. Testez la connexion Google/Facebook si applicable

---

## 🔄 Workflow quotidien : Local → GitHub → Production

### Quand vous faites des modifications en local :

```
1. Modifier le code en local (XAMPP)
2. Tester en local : http://localhost/technoplus/
3. Si ça marche :
   git add .
   git commit -m "Description des modifications"
   git push
4. Sur le serveur : télécharger les fichiers modifiés via FTP ou git pull
```

### Résumé visuel :

```
  LOCAL (XAMPP)
       |
  Modifier + Tester
       |
  git push → GitHub (code sans mots de passe)
       |
  FTP/git pull → SERVEUR PRODUCTION
                  (avec son propre .env)
```

---

## ⚠️ Fichiers à NE PAS uploader sur le serveur

- `test_env.php` (script de test - seulement en local)
- `QUICK_START.md`, `DEPLOYMENT_GUIDE.md`, `PRODUCTION_DEPLOY.md`
- `README.md`
- `backup_old_config/`

Ces fichiers sont utiles en local mais ne servent à rien en production.

---

## 🆘 En cas de problème sur le serveur

Si le site ne fonctionne plus après le déploiement :

1. **Restaurez les anciens fichiers** depuis votre backup cPanel
2. **Vérifiez le .env** sur le serveur (les credentials sont-ils corrects ?)
3. **Vérifiez les permissions** du fichier `.env` (644 minimum)
4. **Regardez les logs** : cPanel → Error Log

---

## 📌 Recommandation : Déployer progressivement

Ne remplacez **pas tout d'un coup** sur le serveur. Procédez ainsi :

1. **D'abord** : Uploadez `env_loader.php` et créez `.env` sur le serveur
2. **Ensuite** : Remplacez `connec.php` (le plus critique)
3. **Testez** le site
4. **Si OK** : Remplacez `config.php` et `config_google_facebook.php`
5. **Testez** à nouveau
6. **Protégez** `.env` via `.htaccess`

Comme ça, si quelque chose ne marche pas, vous savez exactement quel fichier a causé le problème.
