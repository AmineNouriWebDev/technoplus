<?php
// Désactiver l'affichage des erreurs temporairement
error_reporting(0);
ini_set('display_errors', 0);

// Corriger les variables manquantes dans la session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = array(
        'idcart' => array(),
        'qte' => array(),
        'prix' => array()
    );
}
