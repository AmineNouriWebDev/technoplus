<?php
/**
 * VERSION SÉCURISÉE DE config.php
 * Utilise les variables d'environnement depuis .env
 * 
 * IMPORTANT: Une fois testé et validé, renommer ce fichier:
 * 1. Sauvegarder l'ancien: config.php -> config.php.old
 * 2. Renommer celui-ci: config_secure.php -> config.php
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

// Configuration HybridAuth
$config = array(
    "base_url" => $is_local 
        ? EnvLoader::get('SITE_URL_LOCAL', 'http://localhost/technoplus/') . 'login-with.php'
        : EnvLoader::get('SITE_URL_PROD', 'https://technoplus.io/') . 'login-with.php',
    
    "providers" => array(
        "Google" => array(
            "enabled" => true,
            "keys" => array(
                "id"     => EnvLoader::get('GOOGLE_CLIENT_ID'),
                "secret" => EnvLoader::get('GOOGLE_CLIENT_SECRET')
            ),
            "redirect_uri" => EnvLoader::get('GOOGLE_REDIRECT_URL'),
        ),

        "Facebook" => array(
            "enabled" => true,
            "keys" => array(
                "id"     => EnvLoader::get('FACEBOOK_APP_ID'),
                "secret" => EnvLoader::get('FACEBOOK_APP_SECRET')
            ),
            "redirect_uri" => EnvLoader::get('FACEBOOK_REDIRECT_URL'),
        ),
    ),
    
    // Mode debug désactivé par défaut
    "debug_mode" => false,
    "debug_file" => "",
);
