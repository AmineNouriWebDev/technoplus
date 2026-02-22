<?php
/**
 * Configuration robuste des sessions PHP
 * 
 * Résout le problème de session_start() qui échoue en production
 * quand le répertoire de sessions par défaut n'existe pas
 * (ex: /var/cpanel/php/sessions/ea-php72/)
 */

if (session_status() === PHP_SESSION_NONE) {
    
    // Vérifier si le répertoire de sessions par défaut est accessible
    $default_save_path = session_save_path();
    
    if (empty($default_save_path)) {
        $default_save_path = sys_get_temp_dir();
    }
    
    // Si le répertoire par défaut n'existe pas ou n'est pas accessible en écriture
    if (!is_dir($default_save_path) || !is_writable($default_save_path)) {
        
        // Utiliser un répertoire local dans _admin_site/
        $custom_save_path = __DIR__ . '/../sessions';
        
        // Créer le répertoire s'il n'existe pas
        if (!is_dir($custom_save_path)) {
            @mkdir($custom_save_path, 0700, true);
        }
        
        // Protéger le répertoire avec un .htaccess
        $htaccess_path = $custom_save_path . '/.htaccess';
        if (!file_exists($htaccess_path)) {
            @file_put_contents($htaccess_path, "Deny from all\n");
        }
        
        // Configurer PHP pour utiliser ce répertoire
        if (is_dir($custom_save_path) && is_writable($custom_save_path)) {
            session_save_path($custom_save_path);
        }
    }
    
    session_start();
}
?>
