<?php
//session_start();
/**
 * Verifie si le panier existe, le créé sinon
 * @return booleen
 */
function creationPanier(){
   if (!isset($_SESSION['panier'])){
      $_SESSION['panier']=array();
      $_SESSION['panier']['idcart'] = array();
      $_SESSION['panier']['name'] = array();
      $_SESSION['panier']['price'] = array();
      $_SESSION['panier']['total'] = array();
      $_SESSION['panier']['promo'] = array();
      $_SESSION['panier']['nbre_prd'] = array();
      $_SESSION['panier']['qte_prd'] = array();
      $_SESSION['panier']['frais'] = array();
      $_SESSION['panier']['typeCMD'] = array();
      $_SESSION['panier']['verrou'] = false;
   } else {
      // CORRECTION CRITIQUE : Vérifier que tous les tableaux existent
      // Ceci corrige le problème où certains tableaux sont NULL
      if (!isset($_SESSION['panier']['idcart']) || !is_array($_SESSION['panier']['idcart'])) {
         $_SESSION['panier']['idcart'] = array();
      }
      if (!isset($_SESSION['panier']['name']) || !is_array($_SESSION['panier']['name'])) {
         $_SESSION['panier']['name'] = array();
      }
      if (!isset($_SESSION['panier']['price']) || !is_array($_SESSION['panier']['price'])) {
         $_SESSION['panier']['price'] = array();
      }
      if (!isset($_SESSION['panier']['total']) || !is_array($_SESSION['panier']['total'])) {
         $_SESSION['panier']['total'] = array();
      }
      if (!isset($_SESSION['panier']['promo']) || !is_array($_SESSION['panier']['promo'])) {
         $_SESSION['panier']['promo'] = array();
      }
      if (!isset($_SESSION['panier']['nbre_prd']) || !is_array($_SESSION['panier']['nbre_prd'])) {
         $_SESSION['panier']['nbre_prd'] = array();
      }
      if (!isset($_SESSION['panier']['qte_prd']) || !is_array($_SESSION['panier']['qte_prd'])) {
         $_SESSION['panier']['qte_prd'] = array();
      }
      if (!isset($_SESSION['panier']['frais']) || !is_array($_SESSION['panier']['frais'])) {
         $_SESSION['panier']['frais'] = array();
      }
      if (!isset($_SESSION['panier']['typeCMD']) || !is_array($_SESSION['panier']['typeCMD'])) {
         $_SESSION['panier']['typeCMD'] = array();
      }
      if (!isset($_SESSION['panier']['verrou'])) {
         $_SESSION['panier']['verrou'] = false;
      }
   }
   return true;
}


function ajouterArticle($idProduit,$qteProduit,$prixProduit){

   //Si le panier existe
   if (creationPanier() && !isVerrouille())
   {
      //Si le produit existe déjà on ajoute seulement la quantité
      $positionProduit = array_search($idProduit,  $_SESSION['panier']['idcart']);

      if ($positionProduit !== false)
      {
         $_SESSION['panier']['qte_prd'][$positionProduit] += $qteProduit ;
      }
      else
      {
         //Sinon on ajoute le produit
         array_push( $_SESSION['panier']['idcart'],$idProduit);
         array_push( $_SESSION['panier']['qte_prd'],$qteProduit);
         array_push( $_SESSION['panier']['price'],$prixProduit);
      }
   }
   else
   echo "Un problème est survenu veuillez contacter l'administrateur du site.";
}



function modifierQTeArticle($idProduit,$qteProduit){
   //Si le panier éxiste
   if (creationPanier() && !isVerrouille())
   {
      //Si la quantité est positive on modifie sinon on supprime l'article
      if ($qteProduit > 0)
      {
         //Recharche du produit dans le panier
         $positionProduit = array_search($idProduit,  $_SESSION['panier']['idcart']);

         if ($positionProduit !== false)
         {
            $_SESSION['panier']['qte_prd'][$positionProduit] = $qteProduit ;
         }
      }
      else
      supprimerArticle($idProduit);
   }
   else
   echo "Un problème est survenu veuillez contacter l'administrateur du site.";
}

function supprimerArticlePanier($idProduit){
   //Si le panier existe
   //Si le panier existe
   if (creationPanier() && !isVerrouille())
   {
	   
      //Nous allons passer par un panier temporaire
      $temp=array();
      $temp['idProduit'] = array();
      $temp['qteProduit'] = array();
      $temp['prixProduit'] = array();
      $temp['verrou'] = $_SESSION['panier']['verrou'];

      for($i = 0; $i < count($_SESSION['panier']['idcart']); $i++)
      { 
 
         if ($_SESSION['panier']['idcart'][$i] !== $idProduit)
         {

            array_push( $temp['idProduit'],$_SESSION['panier']['idcart'][$i]);
            array_push( $temp['qteProduit'],$_SESSION['panier']['qte_prd'][$i]);
            array_push( $temp['prixProduit'],$_SESSION['panier']['price'][$i]);
         }

      }
	  //On remplace le panier en session par notre panier temporaire à jour
      $_SESSION['panier'] =  $temp;

      //On efface notre panier temporaire
      unset($temp);
   }
   else
   echo "Un problème est survenu veuillez contacter l'administrateur du site.";
}


/**
 * Montant total du panier
 * @return int
 */
function MontantGlobal(){
   $total=0;
   for($i = 0; $i < count($_SESSION['panier']['idcart']); $i++)
   {
	   $prix_commande1 = str_replace(',','.',$_SESSION['panier']['price'][$i]);
	   $prix_commande  = number_format($prix_commande1, 3, '.', '');	
      $total          += $_SESSION['panier']['qte_prd'][$i] * $prix_commande;
   }
   $prix_total = str_replace('.',',',$total);
   return $prix_total;
}


/**
 * Fonction de suppression du panier
 * @return void
 */
function supprimePanier(){
   unset($_SESSION['panier']);
}

/**
 * Permet de savoir si le panier est verrouillé
 * @return booleen
 */
function isVerrouille(){
   if (isset($_SESSION['panier']) && $_SESSION['panier']['verrou'])
   return true;
   else
   return false;
}

/**
 * Compte le nombre d'articles différents dans le panier
 * @return int
 */
function compterArticles()
{
   if (isset($_SESSION['panier']))
   return count($_SESSION['panier']['idcart']);
   else
   return 0;

}

?>