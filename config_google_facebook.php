<?php
/**
 * VERSION SÉCURISÉE DE config_google_facebook.php
 * Utilise les variables d'environnement depuis .env
 * 
 * IMPORTANT: Une fois testé et validé, renommer ce fichier:
 * 1. Sauvegarder l'ancien: config_google_facebook.php -> config_google_facebook.php.old
 * 2. Renommer celui-ci: config_google_facebook_secure.php -> config_google_facebook.php
 */

// Charger les variables d'environnement
require_once __DIR__ . '/env_loader.php';

try {
    EnvLoader::load(__DIR__ . '/.env');
} catch (Exception $e) {
    die("Erreur de configuration: " . $e->getMessage());
}

// Détecter l'environnement
$is_local = (
    $_SERVER['SERVER_NAME'] == 'localhost' 
    || $_SERVER['REMOTE_ADDR'] == '127.0.0.1' 
    || $_SERVER['REMOTE_ADDR'] == '::1'
);

// Configuration Base de Données
if ($is_local) {
    define('DB_HOST', EnvLoader::get('DB_HOST_LOCAL', 'localhost'));
    define('DB_USERNAME', EnvLoader::get('DB_USER_LOCAL', 'root'));
    define('DB_PASSWORD', EnvLoader::get('DB_PASS_LOCAL', ''));
    define('DB_NAME', EnvLoader::get('DB_NAME_LOCAL', 'technopl_db'));
} else {
    define('DB_HOST', EnvLoader::get('DB_HOST_PROD', 'localhost'));
    define('DB_USERNAME', EnvLoader::get('DB_USER_PROD'));
    define('DB_PASSWORD', EnvLoader::get('DB_PASS_PROD'));
    define('DB_NAME', EnvLoader::get('DB_NAME_PROD', 'technopl_db'));
}

define('DB_USER_TBL', 'clients');

// Connexion à la base de données
$connexion = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if (!$connexion) {
    die("Erreur de connexion: " . mysqli_connect_error());
}

// Récupérer les IDs depuis la BDD si nécessaire
$req = 'SELECT * FROM `site_configuration`';
$res = mysqli_query($connexion, $req);
$data = mysqli_fetch_array($res);

// Utiliser les valeurs de .env par défaut, ou celles de la BDD si disponibles
$GOOGLE_CLIENT_ID = !empty($data['GOOGLE_CLIENT_ID']) 
    ? $data['GOOGLE_CLIENT_ID'] 
    : EnvLoader::get('GOOGLE_CLIENT_ID');

$GOOGLE_CLIENT_SECRET = !empty($data['GOOGLE_CLIENT_SECRET']) 
    ? $data['GOOGLE_CLIENT_SECRET'] 
    : EnvLoader::get('GOOGLE_CLIENT_SECRET');

// Configuration Google API
define('GOOGLE_CLIENT_ID', $GOOGLE_CLIENT_ID);
define('GOOGLE_CLIENT_SECRET', $GOOGLE_CLIENT_SECRET);
define('GOOGLE_REDIRECT_URL', EnvLoader::get('GOOGLE_REDIRECT_URL'));

// Include Google API client library
require_once 'google-plus-api-client/src/Google_Client.php';
require_once 'google-plus-api-client/src/contrib/Google_Oauth2Service.php';

// Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('Technoplus');
$gClient->setClientId(GOOGLE_CLIENT_ID);
$gClient->setClientSecret(GOOGLE_CLIENT_SECRET);
$gClient->setRedirectUri(GOOGLE_REDIRECT_URL);

$google_oauthV2 = new Google_Oauth2Service($gClient);
?>
