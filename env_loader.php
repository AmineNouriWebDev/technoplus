<?php
/**
 * Simple .env file loader
 * Charge les variables d'environnement depuis le fichier .env
 * 
 * Usage:
 *   require_once 'env_loader.php';
 *   EnvLoader::load(__DIR__ . '/.env');
 *   $dbHost = EnvLoader::get('DB_HOST_LOCAL');
 */
class EnvLoader {
    private static $loaded = false;
    
    /**
     * Charge le fichier .env et met les variables dans $_ENV
     * 
     * @param string $path Chemin vers le fichier .env
     * @throws Exception Si le fichier .env n'existe pas
     */
    public static function load($path = '.env') {
        // Charger une seule fois
        if (self::$loaded) {
            return;
        }
        
        if (!file_exists($path)) {
            throw new Exception(
                "Le fichier .env n'existe pas à l'emplacement: $path\n" .
                "Veuillez copier .env.example vers .env et configurer vos valeurs."
            );
        }
        
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Ignorer les commentaires et lignes vides
            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }
            
            // Parser la ligne KEY=VALUE
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                // Nettoyer les guillemets si présents
                $value = trim($value, '"\'');
                
                // Définir dans $_ENV si pas déjà défini
                if (!array_key_exists($key, $_ENV)) {
                    $_ENV[$key] = $value;
                    putenv("$key=$value");
                }
            }
        }
        
        self::$loaded = true;
    }
    
    /**
     * Récupère une variable d'environnement
     * 
     * @param string $key Nom de la variable
     * @param mixed $default Valeur par défaut si la variable n'existe pas
     * @return mixed
     */
    public static function get($key, $default = null) {
        // Chercher dans $_ENV d'abord, puis getenv()
        if (array_key_exists($key, $_ENV)) {
            return $_ENV[$key];
        }
        
        $value = getenv($key);
        if ($value !== false) {
            return $value;
        }
        
        return $default;
    }
    
    /**
     * Vérifie si une variable existe
     * 
     * @param string $key Nom de la variable
     * @return bool
     */
    public static function has($key) {
        return array_key_exists($key, $_ENV) || getenv($key) !== false;
    }
}
