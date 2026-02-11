<?php
include("include.php");

echo "<h1>Correction des liens (Base de données)</h1>";

// Fonction pour convertir les URLs
function corriger_url($matches) {
    global $chemin_absolu; // Défini dans include.php/config.php (ex: http://localhost/technoplus/)
    
    $url_complete = $matches[0];
    $url_path = $matches[1]; // La partie après le domaine
    
    // Logique de détection
    // Pattern attendu: boutique/categorie/sous-categ/produit-slug/ ou boutique/produit-slug/
    
    $parts = explode('/', trim($url_path, '/'));
    $slug = end($parts); // Le dernier élément est souvent le slug/link
    
    if (empty($slug)) return $url_complete; // Sécurité

    // Est-ce une image ?
    if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $slug)) {
       // C'est une image, on la rend relative ou absolue locale
       // Si c'était https://technoplus.tn/media/site/img.png -> media/site/img.png
       if (strpos($url_path, 'media/') !== false) {
           return 'media/' . substr($url_path, strpos($url_path, 'media/') + 6);
       }
       return $chemin_absolu . $url_path;
    }

    // Est-ce un produit ou une catégorie ?
    // Dans l'ancien système avec htaccess, c'était souvent /boutique/...
    if (strpos($url_path, 'boutique') !== false) {
        // On suppose que le dernier segment est le LINK du produit ou catégorie
        // Idéalement on devrait vérifier dans la BDD si c'est un produit ou une categ
        // Mais pour simplifier, on assume produit si c'est profond, ou on garde le lien générique
        
        return $chemin_absolu . "produit.php?link=" . $slug;
    }
    
    // Autres cas (contact, etc)
    return $chemin_absolu . $slug . ".php";
}

// 1. Récupérer le contenu accueil
$req = "SELECT id, contenu FROM site_menu WHERE link = 'accueil'";
$res = executeRequete($req);
$row = mysqli_fetch_assoc($res);

if ($row) {
    $id = $row['id'];
    $contenu = $row['contenu'];
    
    echo "<h3>Contenu Original (Extrait)</h3>";
    echo "<textarea style='width:100%;height:100px;'>" . htmlspecialchars(substr($contenu, 0, 500)) . "</textarea>";
    
    // 2. Remplacer les domaines
    // On cherche https://technoplus.tn/QUELQUECHOSE ou http://technoplus.tn/QUELQUECHOSE
    // Regex pour capturer l'URL
    $pattern = '/https?:\/\/(?:www\.)?technoplus\.tn\/([a-zA-Z0-9\-\.\/]+)/';
    
    $contenu_corrige = preg_replace_callback($pattern, 'corriger_url', $contenu);
    
    // Correction spécifique pour localhost déjà présent mais mauvais format
    // ex: http://localhost/technoplus/boutique/... -> http://localhost/technoplus/produit.php?link=...
    $pattern_local = '/http:\/\/localhost\/technoplus\/([a-zA-Z0-9\-\.\/]+)/';
    $contenu_corrige = preg_replace_callback($pattern_local, 'corriger_url', $contenu_corrige);

    echo "<h3>Contenu Corrigé (Extrait)</h3>";
    echo "<textarea style='width:100%;height:100px;'>" . htmlspecialchars(substr($contenu_corrige, 0, 500)) . "</textarea>";

    if ($contenu != $contenu_corrige) {
        // 3. Update
        $contenu_sql = mysqli_real_escape_string($connexion, $contenu_corrige);
        $update = "UPDATE site_menu SET contenu = '$contenu_sql' WHERE id = '$id'";
        if (executeRequete($update)) {
            echo "<h2 style='color:green'>Mise à jour réussie !</h2>";
        } else {
            echo "<h2 style='color:red'>Erreur lors de la mise à jour.</h2>";
        }
    } else {
        echo "<h2>Aucun changement nécessaire trouvé.</h2>";
    }

}
?>
