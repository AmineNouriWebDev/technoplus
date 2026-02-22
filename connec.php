<?php
/**
 * VERSION SÉCURISÉE DE connec.php
 * Utilise les variables d'environnement depuis .env
 * 
 * IMPORTANT: Une fois testé et validé, renommer ce fichier:
 * 1. Sauvegarder l'ancien: connec.php -> connec.php.old
 * 2. Renommer celui-ci: connec_secure.php -> connec.php
 */

// Charger les variables d'environnement
require_once __DIR__ . '/env_loader.php';

// Désactiver le rapport d'erreurs mysqli strict (par défaut en PHP 8.1) 
// pour éviter les Erreurs 500 sur des requêtes non critiques
if (function_exists('mysqli_report')) {
    mysqli_report(MYSQLI_REPORT_OFF);
}

try {
    EnvLoader::load(__DIR__ . '/.env');
} catch (Exception $e) {
    die("Erreur de configuration: " . $e->getMessage());
}

/**
 * Fonction de connexion à la base de données
 * Utilise les credentials depuis .env selon l'environnement
 */
function connexionBDD() {
    $conn = array();
    
    // Détecter l'environnement (local ou production)
    $is_local = (
        $_SERVER['SERVER_NAME'] == 'localhost'
        || $_SERVER['REMOTE_ADDR'] == '127.0.0.1'
        || $_SERVER['REMOTE_ADDR'] == '::1'
    );
    
    if ($is_local) {
        // ENVIRONNEMENT LOCAL - Charger depuis .env
        $conn['serveur']   = EnvLoader::get('DB_HOST_LOCAL', 'localhost');
        $conn['user_bdd']  = EnvLoader::get('DB_USER_LOCAL', 'root');
        $conn['user_pass'] = EnvLoader::get('DB_PASS_LOCAL', '');
        $conn['name_bdd']  = EnvLoader::get('DB_NAME_LOCAL', 'technopl_db');
    } else {
        // ENVIRONNEMENT PRODUCTION - Charger depuis .env
        $conn['serveur']   = EnvLoader::get('DB_HOST_PROD', 'localhost');
        $conn['user_bdd']  = EnvLoader::get('DB_USER_PROD');
        $conn['user_pass'] = EnvLoader::get('DB_PASS_PROD');
        $conn['name_bdd']  = EnvLoader::get('DB_NAME_PROD', 'technopl_db');
    }
    
    return $conn;
}

// Connexion à la base de données
$conn = connexionBDD();
$connexion = mysqli_connect($conn['serveur'], $conn['user_bdd'], $conn['user_pass'], $conn['name_bdd']);

if (!$connexion) {
    die("Erreur connexion DB : " . mysqli_connect_error());
}

mysqli_set_charset($connexion, "utf8");

/**
 * Fonction de sanitisation des données
 */
function sanitize($data) {
    global $connexion;
    
    if (!$connexion || !($connexion instanceof mysqli)) {
        $conn = connexionBDD();
        $connexion = mysqli_connect($conn['serveur'], $conn['user_bdd'], $conn['user_pass'], $conn['name_bdd']);
        
        if (!$connexion) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        mysqli_set_charset($connexion, "utf8");
    }
    
    $data = trim($data ?? '');
    $data = mysqli_real_escape_string($connexion, $data);
    
    return $data;
}

// ========================================
// FONCTIONS UTILITAIRES
// ========================================

function afficher($texte) { return $texte; }
function timestamp($date) { list($day, $month, $year) = explode('/', $date); return mktime(0, 180, 0, $month, $day, $year); }
function timestampus($date) { list($year, $month, $day) = explode('-', $date); return mktime(0, 0, 0, $month, $day, $year); }
function timestamptodate($timestamp) { return date("d/m/Y", $timestamp); }
function timestamptodate2($timestamp) { return date("Y-m-d", $timestamp); }
function timestampTDtodate($timestamp) { return date("d/m/Y H:i:s", $timestamp); }
function datefr($date) { list($year, $month, $day) = explode('-', $date); return $day . "/" . $month . "/" . $year; }
function datehtfr($date) { $split = explode(" ", $date); $date = $split[0]; $time = $split[1]; $exp = explode("-", $date); $annee = $exp[0]; $mois = $exp[1]; $jour = $exp[2]; return "$jour/$mois/$annee $time"; }
function dateSanshtfr($date) { $split = explode(" ", $date); $date = $split[0]; $exp = explode("-", $date); $annee = $exp[0]; $mois = $exp[1]; $jour = $exp[2]; return "$jour/$mois/$annee"; }
function datehtus($date) { $split = explode(" ", $date); $date = $split[0]; $time = $split[1]; $exp = explode("/", $date); $annee = $exp[2]; $mois = $exp[1]; $jour = $exp[0]; return "$annee-$mois-$jour $time"; }
function datemois($datefr) { list($day, $month, $year) = explode('/', $datefr); $mois = array("", "Janvier", "F&eacute;vrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Ao&ucirc;t", "Septembre", "Octobre", "Novembre", "D&eacute;cembre"); return $day . " " . $mois[ltrim($month, "0")] . " " . $year; }
function dateus($date) { list($day, $month, $year) = explode('/', $date); return $year . "-" . $month . "-" . $day; }
function timestampTD($date) { list($date1, $time) = explode(' ', $date); list($heure, $minutes, $secondes) = explode(':', $time); list($day, $month, $year) = explode('/', $date1); return mktime($heure, $minutes, $secondes, $month, $day, $year); }
function extraire_jour($date) { $split = explode("/", $date); return $split[0]; }
function extraire_mois($date) { $split = explode("/", $date); return $split[1]; }
function extraire_annee($date) { $split = explode("-", $date); return $split[0]; }
function random($car) { $string = ""; $chaine = "abcdefghijklmnpqrstuvwxy1234567890"; srand((float)microtime() * 1000000); for ($i = 0; $i < $car; $i++) { $string .= $chaine[rand() % strlen($chaine)]; } return $string; }
function randomnb($car) { $string = ""; $chaine = "1234567890"; srand((float)microtime() * 1000000); for ($i = 0; $i < $car; $i++) { $string .= $chaine[rand() % strlen($chaine)]; } return $string; }
function tronquer1($texte, $taille, $lien) { if (strlen($texte) >= $taille) { $texte = substr($texte, 0, $taille); $espace = strrpos($texte, " "); $texte = substr($texte, 0, $espace) . '...'; } return $texte; }
function tronquer($texte, $taille) { if (strlen($texte) >= $taille) { $texte = substr($texte, 0, $taille); $espace = strrpos($texte, " "); $texte = substr($texte, 0, $espace) . '...'; } return $texte; }
function formatage($txt) { return strtolower($txt); }
function majuscule($Chaine) { $pos = $Chaine[0]; $maj = strtoupper($pos); $i = 1; $Suite = ""; while ($Chaine[$i]) { $Suite .= $Chaine[$i]; $i++; } return $maj . $Suite; }
function nettrecherche($chaine) { $chaine = trim($chaine); }
function nettflux($chaine) { $chaine = trim($chaine); $new = str_replace(array("&amp;", "&nbsp;", "&agrave;", "&acirc;", "&eacute;", "&Eacute;", "&egrave;", "&ecirc;", "&icirc;", "&ccedil;", "&iuml;", "&oelig;", "&ugrave;", "&ucirc;", "&ocirc;", "&lt;", "&gt;", "&laquo;", "&raquo;", "&quot;", "&rsquo;", "&euro;"), array("&", " ", "", "", "", "", "", "", "", "", "", "", "", "", "", "<", ">", "", "", "'", "'", ""), $chaine); return $new; }
function nett($chaine) { $chaine = trim($chaine); $chaine = strtolower($chaine); $chaine = url_rewrite($chaine, 'utf-8'); $new = str_replace(array(" ", "#216;", "&amp;#200;", "&amp;#201;", "&amp;#202;", "&agrave;", "&acirc;", "&eacute;", "&egrave;", "&ecirc;", "&icirc;", "&ccedil;", "&iuml;", "&oelig;", "&ugrave;", "&ucirc;", "&ocirc;", "&Agrave;", "&Acirc;", "&Eacute;", "&Egrave;", "&Ecirc;", "&Icirc;", "&Ccedil;", "&Iuml;", "&Oelig;", "&Ugrave;", "&Ucirc;", "&Ocirc;", "&lt;", "&gt;", "&laquo;", "&raquo;", "&quot;", "&amp;", "'", "*", "&", ":", "+", "_", ")", "\\'", "/", "\\", "(", "%", ",", "!", " ", "--"), "-", $chaine); $new = rtrim($new, '-'); return strtolower($new); }
function url_rewrite($text, $charset = 'utf-8') { $text = htmlentities($text, ENT_NOQUOTES, $charset); $text = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\\1', $text); $text = preg_replace('#&([A-za-z]{2})(?:lig);#', '\\1', $text); $text = preg_replace('#&[^;]+;#', '', $text); $text = mb_strtolower($text, 'UTF-8'); $text = preg_replace('#[^a-zA-Z0-9]#', '-', $text); while (strstr($text, '--')) $text = str_replace('--', '-', $text); return $text; }
function nettinverse($chaine) { $new = str_replace(array("", ""), array("&agrave;", "&acirc;"), $chaine); return $new; }

// ========================================
// CHARGEMENT CONFIGURATION SITE
// ========================================

$req = 'SELECT * FROM `site_configuration`';
$res = mysqli_query($connexion, $req);
$data = mysqli_fetch_array($res);

if ($data) {
    foreach ($data as $key => $value) {
       if (!is_numeric($key)) { $$key = afficher($value); }
    }
}

// SEO Data
$req1  = "SELECT * FROM `optimisation_seo` WHERE 1";
$res1  = mysqli_query($connexion, $req1);
$data1 = mysqli_fetch_array($res1);

if ($data1) {
    foreach ($data1 as $key => $value) {
       if (!is_numeric($key)) { $$key = afficher($value); }
    }
}

/**
 * Fonction de chemin absolu
 */
function cheminAbsolu() {
    $chemin = array();
    
    if ($_SERVER['SERVER_ADDR'] == "127.0.0.1") {
        $chemin['chemin_absolu'] = "https://clients.onlytech.tn/motaawebsite/";
        $chemin['chemin_admin'] = "_admin_site/";
        $chemin['chemin_media'] = "media";
        $chemin['chemin_functions'] = "fonctions";
    } else {
        $chemin['chemin_absolu'] = "localhost";
        $chemin['chemin_admin'] = "root";
        $chemin['chemin_media'] = "media";
        $chemin['chemin_functions'] = "fonctions";
    }
    
    return $chemin;
}

// Détection environnement et chemins
$is_local = ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1');

if ($is_local) {
    $chemin_absolu = EnvLoader::get('SITE_URL_LOCAL', 'http://localhost/technoplus/');
} else {
    $chemin_absolu = EnvLoader::get('SITE_URL_PROD', !empty($chemin_absolu) ? $chemin_absolu : 'https://technoplus.io/');
}

$chemin_admin = '_admin_site/';
$chemin_functions = 'fonctions';
$chemin_media = 'media/';
