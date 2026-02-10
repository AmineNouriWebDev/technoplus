<?php
/*
 * Configuration PRODUCTION pour technoplus.io
 * Remplace config.php après upload sur le serveur
 */

// Database configuration - PRODUCTION
define('DB_HOST', 'localhost'); // Ne pas changer pour cPanel
define('DB_USERNAME', 'VOTRE_USER_CPANEL'); // ⚠️ À REMPLACER
define('DB_PASSWORD', 'VOTRE_PASSWORD_CPANEL'); // ⚠️ À REMPLACER
define('DB_NAME', 'VOTRE_DBNAME_CPANEL'); // ⚠️ À REMPLACER (format: username_monsite_db)
define('DB_USER_TBL', 'clients');

// OAuth Configuration - PRODUCTION
$config = array(
	"base_url" => "https://technoplus.io/login-with.php",
	"providers" => array(

		"Google" => array(
			"enabled" => true,
			"keys"    => array(
				"id" => "1017263532819-n3snn8413ancceh0ph6gccvbnevma952.apps.googleusercontent.com", 
				"secret" => "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"
			),
			"redirect_uri" => "https://technoplus.io/login-with.php?hauth.done=Google",
		),

		"Facebook" => array(
			"enabled" => true,
			"keys"    => array(
				"id" => "1088373559184938", 
				"secret" => "31298d7b34bb9e34371e675849031ce0"
			),
			"redirect_uri" => "https://technoplus.io/login-with.php?hauth.done=Facebook",
		),
	),
	// Désactiver le debug en production
	"debug_mode" => false,
	"debug_file" => "",
);

/*
 * INSTRUCTIONS :
 * 1. Créer la base de données dans cPanel → MySQL Databases
 * 2. Noter le nom d'utilisateur, mot de passe et nom de la BDD
 * 3. Remplacer les valeurs marquées ⚠️ ci-dessus
 * 4. Renommer ce fichier en 'config.php' après upload
 * 5. Mettre à jour les redirect_uri dans Google Cloud Console et Facebook Developers
 */
?>
