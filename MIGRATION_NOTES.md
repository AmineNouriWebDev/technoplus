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

## 5. Notes de Maintenance

- **Attention :** Une erreur corrigée qui réapparaît peut signaler un problème de gestion de cache (navigateur ou serveur), une écrasement de fichier non intentionnel, ou une différence de configuration entre Local et Prod non gérée par le code.
- **Priorité :** Stabiliser le panier et l'accès admin pour la production PHP 8.1.
- **Cache OPcache :** Potentiellement responsable des problèmes qui "reviennent" après correction. Sur localhost, il est recommandé de désactiver OPcache ou le recharger fréquemment pendant le développement.
