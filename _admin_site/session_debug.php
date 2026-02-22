<?php
/**
 * Outil de diagnostic des sessions PHP
 * À exécuter en production pour identifier les problèmes de persistance
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Diagnostic des Sessions - Technoplus Admin</h1>";

// 1. Informations de base
echo "<h2>1. Configuration PHP</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Interface: " . php_sapi_name() . "<br>";
echo "session.save_handler: " . ini_get('session.save_handler') . "<br>";
echo "session.save_path (initial): " . (session_save_path() ?: "<i>(vide - utilise system temp)</i>") . "<br>";

// 2. Test du chargement de session_config.php
echo "<h2>2. Test du fallback (session_config.php)</h2>";
$config_file = __DIR__ . '/includes/session_config.php';
if (file_exists($config_file)) {
    include($config_file);
    echo "Fichier session_config.php chargé.<br>";
    echo "session.save_path (après chargement): " . session_save_path() . "<br>";
} else {
    echo "<b style='color:red;'>ERREUR:</b> Fichier session_config.php introuvable à : $config_file<br>";
}

// 3. Test d'écriture
echo "<h2>3. Test d'écriture de session</h2>";
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "Session active.<br>";
    $_SESSION['test_time'] = time();
    echo "Donnée écrite en session: " . $_SESSION['test_time'] . "<br>";
    
    // Vérifier l'ID de session
    echo "Session ID: " . session_id() . "<br>";
    
    // Vérifier le fichier sur disque si possible
    $save_path = session_save_path();
    if ($save_path) {
        $session_file = $save_path . '/sess_' . session_id();
        if (file_exists($session_file)) {
            echo "<b style='color:green;'>SUCCÈS:</b> Le fichier de session existe sur le disque : $session_file<br>";
            echo "Permissions du fichier: " . substr(sprintf('%o', fileperms($session_file)), -4) . "<br>";
        } else {
            echo "<b style='color:orange;'>AVERTISSEMENT:</b> Le fichier de session n'est pas encore visible sur le disque (possible si PHP attend la fin du script ou utilise un autre handler).<br>";
        }
    }
} else {
    echo "<b style='color:red;'>ERREUR:</b> La session n'a pas pu être démarrée.<br>";
}

// 4. Test du répertoire local
echo "<h2>4. Diagnostic du répertoire local</h2>";
$local_sessions = __DIR__ . '/sessions';
echo "Chemin: $local_sessions<br>";
if (is_dir($local_sessions)) {
    echo "Répertoire présent.<br>";
    echo "Writable: " . (is_writable($local_sessions) ? "<b style='color:green;'>OUI</b>" : "<b style='color:red;'>NON</b>") . "<br>";
    echo "Permissions: " . substr(sprintf('%o', fileperms($local_sessions)), -4) . "<br>";
} else {
    echo "Répertoire absent.<br>";
}

echo "<hr><p>Si vous voyez des erreurs ci-dessus, vérifiez les permissions via cPanel (doit être 0755 pour les dossiers).</p>";
?>
