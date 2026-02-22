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

// 1b. Configuration MySQL
echo "<h2>1b. Configuration MySQL</h2>";
$db_file = __DIR__ . '/includes/include.php';
if (file_exists($db_file)) {
    include_once($db_file);
    if (isset($connexion) && $connexion) {
        $res = mysqli_query($connexion, "SELECT @@sql_mode as mode");
        $row = mysqli_fetch_assoc($res);
        echo "SQL Mode: <b style='color:blue;'>" . ($row['mode'] ?: "<i>(vide)</i>") . "</b><br>";
    }
}

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

// 4. Test de la base de données
echo "<h2>4. Diagnostic de la Base de Données</h2>";
$db_file = __DIR__ . '/includes/include.php';
if (file_exists($db_file)) {
    include_once($db_file);
    if (isset($connexion) && $connexion) {
        echo "<b style='color:green;'>SUCCÈS:</b> Connexion BDD établie.<br>";
        
        // Vérifier les tables clés
        $tables = ['editor', 'editor_state', 'produits', 'categories_blog'];
        foreach ($tables as $table) {
            $check = mysqli_query($connexion, "SHOW TABLES LIKE '$table'");
            if (mysqli_num_rows($check) > 0) {
                echo "Table '$table': <b style='color:green;'>OK</b><br>";
                if ($table == 'produits') {
                    echo "<h4>Structure de la table 'produits' :</h4><pre>";
                    $struct = mysqli_query($connexion, "DESCRIBE produits");
                    while($f = mysqli_fetch_assoc($struct)) {
                        print_r($f);
                    }
                    echo "</pre>";
                    
                    echo "<h4>Triggers sur 'produits' :</h4><pre>";
                    $triggers = mysqli_query($connexion, "SHOW TRIGGERS LIKE 'produits'");
                    while($t = mysqli_fetch_assoc($triggers)) {
                        print_r($t);
                    }
                    echo "</pre>";
                }
            } else {
                echo "Table '$table': <b style='color:red;'>ABSENTE</b><br>";
            }
        }
    } else {
        echo "<b style='color:red;'>ERREUR:</b> Connexion BDD échouée.<br>";
    }
} else {
    echo "Fichier include.php absent.<br>";
}

// 5. Test d'insertion simple
echo "<h2>5. Test d'insertion simple</h2>";
if (isset($connexion) && $connexion) {
    // Test 1: editor_state
    $test_val = "Test_" . time();
    $q1 = "INSERT INTO `editor_state` (`editor_id`, `entree`, `sess_id`, `ip`) VALUES ('1', '".time()."', '$test_val', '127.0.0.1')";
    if (mysqli_query($connexion, $q1)) {
        echo "<b style='color:green;'>SUCCÈS:</b> Insertion dans editor_state réussie.<br>";
        mysqli_query($connexion, "DELETE FROM `editor_state` WHERE `sess_id` = '$test_val'");
    } else {
        echo "<b style='color:red;'>ERREUR:</b> Insertion dans editor_state échouée : " . mysqli_error($connexion) . "<br>";
    }
    
    // Test 2: produits (Attention aux NOT NULL sans défaut)
    echo "<h3>Test d'insertion dans 'produits' :</h3>";
    $test_titre = "TEST_DEBUG_" . time();
    $q2 = "INSERT INTO `produits` 
    (`titre`, `court_contenu`, `caracteristique`, `remarque`, `photo`, `link`, `categorie`, `idparent_categ`, `prix_vente`, `prix_promo`, `etat_stock`, `quantite`, `marque`, `type`, `afficher_accueil`, `video`, `delai`, `nbr_vod`, `nbr_chaine_hd`, `ancre`, `ordre`, `etat`, `titre_page`, `description`, `keywords`, `auteur`, `datecreation`) 
    VALUES 
    ('$test_titre', '', '', '', '', 'test-link', '0', '0', '0', '0', '1', '0', '', 'E', '1', '', '', '0', '0', '', '1', '1', '', '', '', '1', '".time()."')";
    
    $start = microtime(true);
    $res2 = mysqli_query($connexion, $q2);
    $end = microtime(true);
    
    if ($res2) {
        echo "<b style='color:green;'>SUCCÈS:</b> Insertion dans produits réussie en " . round($end - $start, 4) . "s.<br>";
        $new_id = mysqli_insert_id($connexion);
        mysqli_query($connexion, "DELETE FROM `produits` WHERE `id` = '$new_id'");
        echo "Record de test supprimé.<br>";
    } else {
        echo "<b style='color:red;'>ERREUR:</b> Insertion dans produits échouée : " . mysqli_error($connexion) . "<br>";
    }

    // Test 3: produits avec HTML (Test WAF/ModSecurity)
    echo "<h3>Test d'insertion dans 'produits' avec HTML (Test WAF) :</h3>";
    $test_html = "<div>Contenu test avec <b>HTML</b> et un 'quote'.</div>";
    $q3 = "INSERT INTO `produits` 
    (`titre`, `court_contenu`, `caracteristique`, `remarque`, `photo`, `link`, `categorie`, `idparent_categ`, `prix_vente`, `prix_promo`, `etat_stock`, `quantite`, `marque`, `type`, `afficher_accueil`, `video`, `delai`, `nbr_vod`, `nbr_chaine_hd`, `ancre`, `ordre`, `etat`, `titre_page`, `description`, `keywords`, `auteur`, `datecreation`) 
    VALUES 
    ('TEST_WAF_".time()."', '" . mysqli_real_escape_string($connexion, $test_html) . "', '', '', '', 'test-waf', '0', '0', '0', '0', '1', '0', '', 'E', '1', '', '', '0', '0', '', '1', '1', '', '', '', '1', '".time()."')";
    
    $start = microtime(true);
    $res3 = mysqli_query($connexion, $q3);
    $end = microtime(true);
    
    if ($res3) {
        echo "<b style='color:green;'>SUCCÈS:</b> Insertion HTML réussie en " . round($end - $start, 4) . "s.<br>";
        $new_id = mysqli_insert_id($connexion);
        mysqli_query($connexion, "DELETE FROM `produits` WHERE `id` = '$new_id'");
    } else {
        echo "<b style='color:red;'>ERREUR:</b> Insertion HTML échouée : " . mysqli_error($connexion) . "<br>";
    }
}

// 6. Diagnostic du répertoire local
echo "<h2>6. Diagnostic du répertoire local</h2>";
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
