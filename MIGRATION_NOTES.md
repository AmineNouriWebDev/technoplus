# Journal de Migration et Suivi de Projet - Technoplus

Ce document sert à suivre l'historique du projet, les problèmes rencontrés lors de la migration, et l'état actuel des corrections.

## 1. Contexte du Projet

- **Origine :** Site e-commerce existant pour un client (Technoplus).
- **Hébergement Initial :** OVH.
- **État Initial :** Le site fonctionnait sous PHP 7.2.
- **Migration :** Transfert vers un nouvel hébergement suite à des problèmes techniques chez OVH.
- **État Actuel :**
    - **Localhost :** Fonctionnel (version PHP incertaine, probable 8.1 ou 8.2).
    - **Production (Nouvel Hébergeur) :** En ligne, sous PHP 8.1.
    - **Git :** Push depuis Local vers GitHub -> Pull depuis GitHub vers cPanel (Production).

## 2. Historique des Problèmes et Corrections

### Problème d'Accès Administration (`_admin_site`)
- **Symptôme :** Blocage de l'accès à l'interface d'administration depuis le frontend.
- **Observation :** L'accès fonctionnait, puis s'est bloqué, puis a fonctionné à nouveau après l'ajout/configuration du fichier `.env`, sans modification du code source PHP de connexion.
- **Hypothèse :** Problème lié à la configuration de l'environnement (base de données, URL de base) ou à la gestion des sessions PHP entre les versions (7.2 vs 8.1).

### Problème "Ajouter au Panier"
- **Symptôme :** Erreur lors du clic sur le bouton "Ajouter au panier".
- **Statut :** Avait été corrigé, mais le problème est réapparu.
- **Impact potentiel de la version PHP :**
    - Le passage de PHP 7.2 à 8.1+ rend le code plus strict.
    - Des avertissements (Warnings) en 7.2 peuvent devenir des Erreurs Fatales en 8.1.
    - Fonctions dépréciées ou gestion différente des variables non définies.
- **Analyse du Code (11/02/2026) :**
    - **`fonctions_panier.php` :**
        - Présence de code de débogage bloquant (`echo ...; exit;`). **[CORRIGÉ]**
        - Incohérence des clés de session (mélange entre `libelleProduit` et `idcart`). Harmonisation faite sur `idcart`, `qte_prd`, `price` pour correspondre au reste du site. **[CORRIGÉ]**

    - **`fction_db.php` (Administration) :** 
        - Utilisation de `ereg_replace` (fonction supprimée depuis PHP 7.0) dans `rewriteTitre`. **[CORRIGÉ]**
        - Inversion des arguments dans `mysqli_query` dans la fonction `extraire`. **[CORRIGÉ]**
        - Faute de frappe sur une variable (`$résultat` au lieu de `$resultat`) dans `lister`. **[CORRIGÉ]**
    - **Session :** `session_start()` est bien géré via `include.php` de manière conditionnelle.


### Problème de Session / Cookie (Admin & Front)
- **Observation :** Difficultés à maintenir la connexion admin ou le panier.
- **Hypothèse :** La configuration des cookies de session (domaine, secure flag) peut différer entre localhost (http) et prod (https).
- **Solution temporaire :** L'ajout de `.env` a stabilisé la connexion en forçant probablement les bonnes URL de base (`SITE_URL_PROD` vs `SITE_URL_LOCAL` dans `connec.php`).


## 3. Environnement Technique

| Environnement | Version PHP | Notes |
| :--- | :--- | :--- |
| **Ancien (OVH)** | 7.2 | Fonctionnel avant migration. |
| **Nouveau (Prod)** | 8.1 | Actuel. Erreurs strictes possibles. |
| **Localhost** | 8.1/8.2 (à confirmer) | Utilisation de XAMPP (probablement). |

## 4. Workflow de Déploiement

1.  Développement/Correction en **Local**.
2.  `git push origin master` vers **GitHub**.
3.  `git pull origin master` sur le **Serveur de Production**.

### Problème Intermittent "Ajouter au Panier" (11/02/2026)
- **Symptôme :** Message d'erreur "Erreur lors de l'ajout au panier. Veuillez réessayer." s'affiche de manière intermittente sur localhost uniquement.
- **Observations :**
    - ✅ **Production (https://technoplus.io)** : Fonctionne correctement
    - ❌ **Localhost (http://localhost/technoplus)** : Erreur intermittente
    - 🔄 **Après rafraîchissement** : Le même produit peut s'ajouter avec succès
    - 🔁 **Récurrence** : Problème déjà corrigé dans le passé mais réapparu après quelques jours
- **Analyse Technique :**
    - **AJAX Handler** : `includes/cart.php` fait appel à plusieurs requêtes DB par produit :
        - `titreProduits($idr)` - ligne 38
        - `prixPromoProduits($idr)` - ligne 42
        - `PrixVenteProduits($idr)` - ligne 46
    - **Session Management** : Le panier dépend de `$_SESSION['panier']` initialisé dans `include.php`
    - **JavaScript** : `includes/script_panier.php` utilise AJAX avec gestion d'erreur qui affiche le message à l'utilisateur (ligne 89)
    - **Pas d'erreur PHP visible** : `error_reporting(E_ALL)` activé mais `display_errors = Off` dans php.ini
- **Hypothèses Principales :**
    1. **Problème de Cache OPcache** : PHP peut cacher le bytecode avec OPcache. Sur localhost, le cache peut devenir obsolète ou corrompu
    2. **Session Race Condition** : Multiples requêtes simultanées peuvent causer des conflits de session
    3. **Connexion DB Intermittente** : Les requêtes DB dans `includes/cart.php` peuvent échouer silencieusement
    4. **Différence PHP.ini** : Le fichier `php.ini` montre `session.save_path = "/var/cpanel/php/sessions/ea-php72"` qui indique PHP 7.2, mais localhost utilise probablement PHP 8.x
    5. **Output Buffering** : `ob_start()` dans `cart.php` peut masquer certaines erreurs
- **À Investiguer :**
    - ⚠️ Vérifier les logs PHP : `error_log` pour voir les erreurs non affichées
    - ⚠️ Ajouter logging détaillé dans `includes/cart.php` pour tracker les échecs de requêtes DB
    - ⚠️ Vérifier l'état d'OPcache sur localhost : `opcache_reset()` après modifications
    - ⚠️ Tester avec `session.save_handler` identique entre local et prod

### Correction des Icônes d'Action Produits (13/02/2026)
- **Symptôme :** Les 6 icônes d'action sur la page produits étaient visibles, mais cliquer dessus produisait des erreurs PHP :
    - `Warning: Undefined array key "ids"` 
    - `Warning: Trying to access array offset on value of type null`
- **Pages Affectées :**
    - `_admin_site/includes/add_produit.php` (Ajouter images supplémentaires)
    - `_admin_site/includes/add_produits_similaire.php` (Produits similaires)
    - `_admin_site/includes/fichesTechniques.php` (Fiches techniques PDF)
    - `_admin_site/includes/facilitePaiement.php` (Détails de paiement)
- **Causes Identifiées :**
    1. **Paramètres URL manquants** : Les pages tentaient d'accéder à `$_GET['ids']` et `$_GET['start']` sans vérifier leur existence
    2. **Erreur de casse** : Appel à `imagesProduitSite()` au lieu de `imagesproduitSite()`
    3. **PHP 8.1+ Strict** : PHP 8.1 génère des warnings pour les accès à des clés non définies (contrairement à PHP 7.2)
- **Corrections Appliquées :**
    - **`add_produit.php` :**
        - Ligne 71 : `imagesProduitSite()` → `imagesproduitSite()`
        - Ligne 110 : Ajout de `isset($_GET['start'])` avec valeur par défaut
    - **`add_produits_similaire.php` :**
        - Ligne 113 : Ajout de `isset($_GET['start'])` avec valeur par défaut
    - **`fichesTechniques.php` :**
        - Ligne 153 : Ajout de `isset($_GET['ids'])` avec valeur par défaut
        - Ligne 161 : Ajout de `isset($_GET['start'])` avec valeur par défaut
    - **`facilitePaiement.php` :**
        - Ligne 111 : Ajout de `isset($_GET['ids'])` dans textarea detail
        - Ligne 121 : Ajout de `isset($_GET['ids'])` dans input remarque
        - Ligne 131 : Ajout de `isset($_GET['ids'])` dans input prix
        - Ligne 139 : Ajout de `isset($_GET['start'])` avec valeur par défaut
    - ~~**`fonctions/fction_produits.php` :**~~
        - ~~Ajout d'une fonction alias `imagesProduitSite()` qui appelle `imagesproduitSite()` pour maintenir la compatibilité~~
        - **ERREUR DÉTECTÉE**: Tentative de créer un alias a causé une erreur fatale `Cannot redeclare imagesProduitSite()`
        - **CAUSE**: PHP est **insensible à la casse** pour les noms de fonctions. `imagesProduitSite()` et `imagesproduitSite()` sont considérés comme la même fonction
        - **HOTFIX**: Suppression de l'alias. La correction dans `add_produit.php` (ligne 71) suffit car PHP appellera la bonne fonction peu importe la casse
- **Résultat :** Les 6 icônes d'action (Modifier, Produits similaires, Images, Fiches PDF, Paiement, Supprimer) fonctionnent maintenant sans erreurs. ✅

## 5. Notes de Maintenance

- **Attention :** Une erreur corrigée qui réapparaît peut signaler un problème de gestion de cache (navigateur ou serveur), une écrasement de fichier non intentionnel, ou une différence de configuration entre Local et Prod non gérée par le code.
- **Priorité :** Stabiliser le panier et l'accès admin pour la production PHP 8.1.
- **Cache OPcache :** Potentiellement responsable des problèmes qui "reviennent" après correction. Sur localhost, il est recommandé de désactiver OPcache ou le recharger fréquemment pendant le développement.
- **PHP 8.1+ Bonnes Pratiques :**
    - Toujours utiliser `isset()` avant d'accéder à `$_GET`, `$_POST`, ou `$_SESSION`
    - Utiliser l'opérateur null coalescent `??` pour les valeurs par défaut : `$_GET['param'] ?? ''`
    - Attention aux noms de fonctions sensibles à la casse
- **⚠️ IMPORTANT - Noms de Fonctions PHP :**
    - PHP est **insensible à la casse** pour les noms de fonctions
    - `imagesproduitSite()`, `imagesProduitSite()`, et `IMAGESPRODUITSITE()` sont **la même fonction**
    - Tenter de déclarer deux fonctions avec des casses différentes causera : `Fatal error: Cannot redeclare`
    - **Bonne pratique** : Utiliser toujours la casse exacte de la déclaration pour la lisibilité du code


