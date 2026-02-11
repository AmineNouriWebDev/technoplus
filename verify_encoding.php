<?php
// Script de vérification de l'encodage
include("connec.php");

echo "<h2>Vérification de l'encodage de la base de données</h2>";

// Vérifier l'encodage de la connexion
$result = mysqli_query($connexion, "SHOW VARIABLES LIKE 'character_set%'");
echo "<h3>Variables d'encodage:</h3>";
echo "<table border='1' cellpadding='5'>";
while ($row = mysqli_fetch_array($result)) {
    echo "<tr><td>{$row[0]}</td><td>{$row[1]}</td></tr>";
}
echo "</table>";

// Vérifier l'encodage de la base de données
$result = mysqli_query($connexion, "SELECT DEFAULT_CHARACTER_SET_NAME, DEFAULT_COLLATION_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = 'monsite_db'");
echo "<h3>Encodage de la base de données 'monsite_db':</h3>";
echo "<table border='1' cellpadding='5'>";
while ($row = mysqli_fetch_array($result)) {
    echo "<tr><td>Character Set</td><td>{$row[0]}</td></tr>";
    echo "<tr><td>Collation</td><td>{$row[1]}</td></tr>";
}
echo "</table>";

// Test d'affichage de caractères accentués
echo "<h3>Test d'affichage:</h3>";
echo "<p>Caractères accentués: é à è ê ç ù</p>";
echo "<p>Si vous voyez des � au lieu des lettres accentuées, il y a un problème d'encodage.</p>";

// Vérifier l'encodage des tables
$result = mysqli_query($connexion, "SELECT TABLE_NAME, TABLE_COLLATION FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'monsite_db' LIMIT 10");
echo "<h3>Encodage des 10 premières tables:</h3>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Table</th><th>Collation</th></tr>";
while ($row = mysqli_fetch_array($result)) {
    echo "<tr><td>{$row[0]}</td><td>{$row[1]}</td></tr>";
}
echo "</table>";
?>
