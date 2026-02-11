<?php
// Script pour corriger l'encodage de la page promotions

// Configuration de la base de données
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'monsite_db';

// Connexion à la base de données
$conn = new mysqli($host, $username, $password, $database);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Définir l'encodage UTF-8
$conn->set_charset("utf8mb4");

// Récupérer le contenu actuel
$query = "SELECT id, titre, contenu FROM site_menu WHERE id = 23";
$result = $conn->query($query);

if ($result && $row = $result->fetch_assoc()) {
    echo "=== AVANT CORRECTION ===\n";
    echo "Titre: " . $row['titre'] . "\n";
    echo "Contenu (100 premiers caractères): " . substr($row['contenu'], 0, 100) . "\n\n";
    
    // Nouveau contenu corrigé manuellement avec encodage UTF-8 propre
    $nouveau_contenu = '<h3>🎁 <strong>Promotions exclusives sur IPTV & High-Tech – Seulement chez Technoplus.tn</strong></h3>
<p>Ne ratez pas nos meilleures offres en cours sur une sélection d\'abonnements IPTV premium, de box Android, de télécommandes, et d\'accessoires tech. Des prix cassés, une qualité garantie, et un service client toujours disponible pour vous conseiller.</p>

<h4>🔥 Ce que vous pouvez retrouver dans nos promos :</h4>
<ul>
<li>📺 Abonnements IPTV 12 mois (Zen, Iron, Orca, Forever, etc.) à prix réduit</li>
<li>📦 Packs box Android + IPTV à prix promo</li>
<li>🎮 Télécommandes, câbles HDMI, Firestick, dongles…</li>
<li>⚡ Offres limitées en quantité et dans le temps</li>
</ul>

<h4>✅ Pourquoi acheter chez Technoplus.tn ?</h4>
<ul>
<li>Paiement sécurisé</li>
<li>Livraison rapide partout en Tunisie</li>
<li>Assistance après-vente 7j/7</li>
<li>Test IPTV gratuit sur demande</li>
<li>Produits testés et approuvés par nos clients</li>
</ul>';
    
    // Mettre à jour la base de données
    $stmt = $conn->prepare("UPDATE site_menu SET contenu = ? WHERE id = 23");
    $stmt->bind_param("s", $nouveau_contenu);
    
    if ($stmt->execute()) {
        echo "=== APRÈS CORRECTION ===\n";
        echo "✓ Contenu mis à jour avec succès!\n";
        echo "\nNouvel aperçu (100 premiers caractères):\n";
        echo substr($nouveau_contenu, 0, 100) . "\n";
    } else {
        echo "Erreur lors de la mise à jour : " . $stmt->error . "\n";
    }
    
    $stmt->close();
} else {
    echo "Erreur : Page promotions non trouvée (ID 23)\n";
}

$conn->close();
echo "\n✓ Script terminé!\n";
?>
