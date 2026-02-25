<div class="main main-content-wrapper">
    <!-- Product Details Area Start -->
    <div class="single-product-area section-padding-20 clearfix">
        <div class="container">
            <!-- Main Product Row -->
            <div class="row">
                <!-- Left Column: Product Images & Info -->
                <div class="col-12 col-lg-8 mb-4">
                    <!-- Product Image Carousel -->
                    <div class="product-images-section mb-4">
                        <div id="product_details_slider" class="carousel slide" data-ride="carousel">
                            <ul class="carousel-indicators">
                                <li data-target="#product_details_slider" data-slide-to="0" class="active" style="background: url('media/products/<?php echo $photo; ?>') no-repeat center;background-size: contain;"></li>
                                <?php 
                                if(imgproduit($id)){
                                    $req1 = "SELECT * FROM `images_produit` WHERE `id_produit` = '".$id."'";
                                    $res1 = executeRequete($req1);
                                    $c="1";
                                    while($dataip = mysqli_fetch_array($res1)){    
                                ?>
                                    <li data-target="#product_details_slider" data-slide-to="<?php echo $c; ?>" style="background: url('<?php echo imagesproduitSite($dataip['id']); ?>') no-repeat center;background-size: contain;"></li>
                                <?php $c++; }} 
                                
                                // Initialize variables for order form
                                $sous_total = isset($sous_total) ? $sous_total : 0;
                                $total = isset($total) ? $total : 0;
                                $frais = isset($frais) ? $frais : 0;
                                ?>
                            </ul>
                            <div class="carousel-inner text-center bg-light rounded">
                                <div class="carousel-item active">
                                    <img class="img-fluid myImage p-3" src="media/products/<?php echo $photo; ?>" alt="<?php echo $titre; ?>" style="max-height: 500px; object-fit: contain;">
                                </div>
                                <?php 
                                if(imgproduit($id)){
                                    $req2 = "SELECT * FROM `images_produit` WHERE `id_produit` = '".$id."'";
                                    $res2 = executeRequete($req2);
                                    while($dataip2 = mysqli_fetch_array($res2)){    
                                ?>
                                    <div class="carousel-item">
                                        <img class="img-fluid myImage p-3" src="<?php echo imagesproduitSite($dataip2['id']); ?>" alt="<?php echo $titre; ?>" style="max-height: 500px; object-fit: contain;">
                                    </div>
                                <?php }} ?>
                            </div>
                        </div>
                    </div>

                    <!-- Product Information -->
                    <div class="product-info-section bg-white p-4 rounded shadow-sm">
                        <div class="line mb-3"></div>
                        <h2 class="product-title mb-3"><?php echo $titre; ?></h2>
                        
                        <?php if(marquesProduits($id) != '0' && ApercuMarque(marquesProduits($id)) !='') {  ?>
                            <div class="mb-3" style="height:60px;overflow:hidden">
                                <img src="<?php echo photoMarqueSite(marquesProduits($id)); ?>" class="img-fluid" style="width: 120px;height: -webkit-fill-available; object-fit: contain;" alt="<?php echo raisonMarque(marquesProduits($id)); ?>">
                            </div>
                        <?php } ?>
                        
                        <?php if ($etatStock == '1'){  ?>
                            <p class="avaibility mb-3"><i class="fa fa-circle"></i> En Stock</p>
                        <?php }else{?>
                            <p class="avaibility mb-3"><i class="fa fa-circle rupture"></i> En Rupture</p>
                        <?php } ?>
                        
                        <div class="short_overview my-3">
                            <?php echo courtContenuProduits($id); ?>
                        </div>
                        
                        <?php if(rqProduits($id)){ ?>
                            <div class="remarque bg-light p-3 rounded mb-3" style="font-size:14px;font-weight:500">
                                <?php echo rqProduits($id); ?>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- Detailed Description - Directly Below Images -->
                    <?php if(!empty($caracteristique)) { ?>
                    <div class="product-description-section mt-4">
                        <div class="bg-white p-4 rounded shadow-sm">
                            <h4 class="mb-3">Description détaillée</h4>
                            <div class="content-text">
                                <?php echo $caracteristique; ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <!-- Right Column: Sticky Sidebar -->
                <div class="col-12 col-lg-4">
                    <div class="product-sidebar-sticky">
                        <div class="bg-light p-4 rounded shadow-sm">
                            <!-- Price Display -->
                            <div class="product-price-section mb-4 text-center">
                                <p class="product-price-display mb-0">
                                    <?php if($PrixPromo != '0.000') { 
                                        echo '<span class="price-promo">'.$PrixPromo.' DT</span><br/>';
                                        echo '<span class="price-original">'.$PrixVente.' DT</span>'; 
                                    } else { 
                                        echo '<span class="price-promo">'.$PrixVente.' DT</span>'; 
                                    } ?>
                                </p>
                            </div>
                            
                            <?php if ($etatStock == '1'){  ?>
                            <!-- Add to Cart Form -->
                            <form class="cart-form" method="post">
                                <div class="quantity-selector d-flex justify-content-center align-items-center mb-3">
                                    <label class="mr-2 mb-0">Qté</label>
                                    <div class="quantity">
                                        <span class="qty-minus" onclick="var effect = document.getElementById('qty'); var qty = effect.value; if( !isNaN( qty ) &amp;&amp; qty &gt; 1 ) effect.value--;return false;">
                                            <i class="fa fa-caret-down" aria-hidden="true"></i>
                                        </span>
                                        <input type="number" class="qty-text" id="qty" step="1" min="1" max="300" name="quantity" value="1">
                                        <span class="qty-plus" onclick="var effect = document.getElementById('qty'); var qty = effect.value; if( !isNaN( qty )) effect.value++;return false;">
                                            <i class="fa fa-caret-up" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                                
                                <button type="button" name="addtocart" value="5" class="btn amado-btn btn-block text-uppercase mb-3" onclick="addToCart(<?php echo $id; ?>, document.getElementById('qty').value);">
                                    <i class="fa fa-shopping-bag mr-2"></i>Ajouter au panier
                                </button>
                            </form>
                            <?php }else{?>
                                <button type="button" class="btn amado-btn btn-block text-uppercase mb-3" disabled>
                                    <i class="fa fa-shopping-bag mr-2"></i>En rupture de stock
                                </button>
                            <?php } ?>
                            
                            <!-- Services Section -->
                            <div class="services-section mt-4">
                                <?php 
                                    $reqs ="SELECT * FROM `services` ORDER BY `ordre` ASC";
                                    $ress =executeRequete($reqs);
                                    while($datas = mysqli_fetch_array($ress)){
                                ?>
                                <div class="service-item bg-white p-2 mb-2 rounded d-flex align-items-center" style="font-size:13px">
                                    <img src="<?php echo photoServiceSite($datas['id']); ?>" width="40px" class="bg-white mr-2 p-2 rounded" alt="<?php echo titreService($datas['id']); ?>" />
                                    <span><?php echo titreService($datas['id']); ?></span>
                                </div>    
                                <?php } ?>
                            </div>
                            
                            <!-- Express Order Link (Collapsed by default) -->
                            <?php if ($etatStock == '1'){  ?>
                            <div class="mt-4">
                                <button class="btn btn-outline-secondary btn-block" type="button" data-toggle="collapse" data-target="#commandeExpressCollapse" aria-expanded="false" aria-controls="commandeExpressCollapse">
                                    <i class="fa fa-bolt mr-2"></i>Commande Express
                                </button>
                                
                                <div class="collapse mt-3" id="commandeExpressCollapse">
                                    <div class="card card-body">
                                        <h6 class="mb-3">COMMANDE EXPRESS</h6>
                                        <form id="commandeExpressForm" method="post" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label>Nom <span class="text-danger">*</span></label>
                                                <input type="text" name="nom" class="form-control form-control-sm" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Prénom <span class="text-danger">*</span></label>
                                                <input type="text" name="prenom" class="form-control form-control-sm" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Téléphone <span class="text-danger">*</span></label>
                                                <input type="text" name="tel" class="form-control form-control-sm" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Email <span class="text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control form-control-sm" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Plateforme de confirmation:</label>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="platform" value="whatsapp" id="platform_whatsapp" class="custom-control-input" required>
                                                    <label class="custom-control-label" for="platform_whatsapp">
                                                        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp" style="width:20px;height:20px;"> WhatsApp
                                                    </label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="platform" value="messenger" id="platform_messenger" class="custom-control-input">
                                                    <label class="custom-control-label" for="platform_messenger">
                                                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/be/Facebook_Messenger_logo_2020.svg" alt="Messenger" style="width:18px;height:18px;"> Messenger
                                                    </label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="platform" value="telegram" id="platform_telegram" class="custom-control-input">
                                                    <label class="custom-control-label" for="platform_telegram">
                                                        <img src="https://upload.wikimedia.org/wikipedia/commons/8/82/Telegram_logo.svg" alt="Telegram" style="width:18px;height:18px;"> Telegram
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check" style="color:#000; font-size: 12px;">
                                                    <input type="checkbox" class="form-check-input" id="cgv" required>
                                                    <label class="form-check-label" for="cgv">
                                                        J'accepte les <a href="#politique" data-toggle="modal" class="politique">CGV</a>
                                                    </label>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="payment-method">
<?php
if(typeProduits($id)=="A") {
    $requetepay = 'SELECT * FROM `moyens_paiement` WHERE `etat` = "1" AND `type` ="1" AND id <>"9"';
} else {
    $requetepay = 'SELECT * FROM `moyens_paiement` WHERE `etat` = "1" AND `type` ="1"';
}
$respay = executeRequete($requetepay);
$first = true;
while($datapay = mysqli_fetch_array($respay)){
?>
    <div class="custom-control custom-radio">
        <input 
            type="radio" 
            name="paymentMethod" 
            class="custom-control-input" 
            value="<?php echo $datapay['id']; ?>" 
            id="payment_<?php echo $datapay['id'];?>" 
            <?php if ($first) { echo 'required'; $first = false; } ?>
        >
        <label class="custom-control-label small" for="payment_<?php echo $datapay['id']; ?>">
            <?php 
            $label = moyen_paiement($datapay['id']);
            echo $label;

            // Display corresponding image if matched
            switch ($label) {
                case "Paiement Paypal":
                    echo '<img class="ml-2" src="dist/img/paypal.png" alt="" width="40px">';
                    break;
                case "Paiement par D17":
                    echo '<img class="ml-2" src="dist/img/d17.png" alt="" width="40px">';
                    break;
                case "Western Union":
                    echo '<img class="ml-2" src="dist/img/paymentmethode/2.png" alt="" width="40px">';
                    break;
                case "Ria":
                    echo '<img class="ml-2" src="dist/img/paymentmethode/3.png" alt="" width="40px">';
                    break;
                case "Carte Bancaire Tunisienne":
                    echo '<img class="ml-2" src="dist/img/paymentmethode/5.png" alt="" width="40px">';
                    break;
                case "Virement bancaire international":
                    echo '<img class="ml-2" src="dist/img/paymentmethode/1.png" alt="" width="40px">';
                    break;
                case "Wise":
                    echo '<img class="ml-2" src="dist/img/paymentmethode/4.png" alt="" width="40px">';
                    break;
                case "CoinBase":
                    echo '<img class="ml-2" src="dist/img/paymentmethode/7.png" alt="" width="40px">';
                    break;
                case "Binance":
                    echo '<img class="ml-2" src="dist/img/paymentmethode/6.png" alt="" width="40px">';
                    break;
                case "Revolut":
                    echo '<img class="ml-2" src="dist/img/paymentmethode/8.png" alt="" width="40px">';
                    break;
            }
            ?>
        </label>
    </div>
<?php } ?>
</div>
                                            <hr>
                                            <button type="submit" class="btn amado-btn btn-sm btn-block">Confirmer</button>
                                            <input type="hidden" name="action" value="cmd_express" />
                                            <input type="hidden" name="soustotal" id="stotal_commande" value="<?php echo $sous_total; ?>" />
                                            <input type="hidden" name="total" id="total_commande" value="<?php echo $total; ?>" />
                                            <input type="hidden" name="frais_livraison" id="frais_commande" value="<?php echo $frais; ?>" />
                                            <input type="hidden" name="qte_cmd" id="qte_cmd" value="1" />
                                            <input type="hidden" name="prod_cmd" id="prod_cmd" value="<?php echo $id; ?>" />
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for CGV -->
<div class="modal fade" id="politique" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel"><?php echo titrePage(26); ?></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo contenuPage(26); ?>
            </div>
        </div>
    </div>
</div>

<!-- Scripts for Express Order -->
<script>
document.getElementById('commandeExpressForm').addEventListener('submit', function(event) {
    event.preventDefault();
    if (!document.getElementById('cgv').checked) {
        alert('Veuillez accepter les Conditions Générales de Ventes.');
        return;
    }
    const now = new Date();
    const date = now.toLocaleDateString('fr-TN');
    const time = now.toLocaleTimeString('fr-TN', { hour: '2-digit', minute: '2-digit' });
    const nom = document.querySelector('input[name="nom"]').value;
    const prenom = document.querySelector('input[name="prenom"]').value;
    const tel = document.querySelector('input[name="tel"]').value;
    const Quantity = document.querySelector('input[name="qte_cmd"]').value;
    const email = document.querySelector('input[name="email"]').value;
    const platform = document.querySelector('input[name="platform"]:checked').value;
    const payment = document.querySelector(`label[for="${document.querySelector('input[name="paymentMethod"]:checked').id}"]`).textContent.trim();
    const productTitle = '<?php echo addslashes($titre); ?>';
    const productPrice = '<?php if($PrixPromo != "0.000") { echo $PrixPromo; } else { echo $PrixVente; } ?>';
    const productUrl = window.location.href;
    const message = `
🌟 *Nouvelle Commande Express* 🌟

🛍️ *Produit:* _${productTitle}_ (Lien: ${productUrl} )
💵 *Prix:* _${productPrice} DT_
📦 *Quantité:* ${Quantity}

───────────────

👤 *Informations Client:*
👨‍💼 *Nom:* ${nom} ${prenom}
📞 *Téléphone:* ${tel}
📧 *Email:* ${email}
💳 *Paiement:* ${payment}

───────────────

✅ Merci de *confirmer* cette commande dès que possible.
🗓️ *Date de commande:* ${date}
⏰ *Heure:* ${time}
    `.trim();
    const encodedMessage = encodeURIComponent(message);
    let url;
    if (platform === 'whatsapp') {
        url = `https://wa.me/<?php echo $cmd_num_whatsapp; ?>?text=${encodedMessage}`;
    } else if (platform === 'telegram') {
        url = `https://t.me/technoplusfr?text=${encodedMessage}`;
    } else if (platform === 'messenger') {
        url = '<?php echo $lien_cmd_messenger; ?>'.replace('%%TXT%%', encodedMessage);
    }
    window.location.href = url;
});
</script>

<!-- Facebook Pixel -->
<script>
    fbq('track','ViewContent',{
        content_type: 'product',
        content_ids:['<?php echo $id;?>'],
        content_name:'<?php echo $titre;?>',
        value:'<?php echo $price;?>',
        currency: 'TND'
    });
</script>

<!-- Similar Products Section -->
<?php 
    $requete = 'SELECT * FROM `produits_similaire` WHERE `id_produit` ="'.$id.'" AND id_categ !="0"';
    $resultat = executeRequete($requete);
    $num = mysqli_num_rows($resultat);
    if ($num > 0 ) {?>
    <div class="container my-5">
        <h2 class="mb-4">Produits similaires</h2>
        <div class="prod-similaire slider justify-content-center">
            <?php
            while ($datapr = mysqli_fetch_array($resultat))  {
                if($datapr['id_categ']){   
                    $id_categ = $datapr['id_categ'];  
                    $requete1 = 'SELECT * FROM `produits` WHERE `categorie` ="'.$id_categ.'"';
                    $resultat1 = executeRequete($requete1);
                    while ($datapr1 = mysqli_fetch_array($resultat1))  {
                        $id_p = $datapr1['id']; 
                        $link_p  = linkProduits($id_p); 
            ?>
                    <div class="mb-4 slide px-2">
                        <div class="single-product-wrapper border p-2 text-center hoverDiv h-100">
                            <!-- Product Image -->
                            <a href="<?php echo lienProduits($link_p); ?>" class="product-img hover-zoom">
                                <img src="<?php echo photoProduitsSite($id_p); ?>" alt="<?php echo titreProduits($id_p); ?>" class="img-fluid">
                            </a>

                            <!-- Product Description -->
                            <div class="product-description d-flex flex-column align-items-center justify-content-between">
                                <!-- Product Meta Data -->
                                <div class="product-meta-data text-center">
                                    <a href="<?php echo lienProduits($link_p); ?>">
                                        <h6><?php echo titreProduits($id_p); ?></h6>
                                    </a>
                                    <div class="line mx-auto my-2 h-auto"></div>
                                    <?php
                                    if(marquesProduits($id_p) != '0' && ApercuMarque(marquesProduits($id_p)) !='') { 
                                        $output = '<div style="height:60px;overflow:hidden"><img src="'.photoMarqueSite(marquesProduits($id_p)).'" class="img-fluid mx-auto" style="width: 120px;height: -webkit-fill-available; object-fit: contain;"> </div>';
                                    }
                                    echo $output;
                                    ?>
                                    <?php if (etatStockProduits($id_p) == '1'){ ?> 
                                        <p class="avaibility m-0" style="color:#20d34a;font-size:14px;text-transform:uppercase"><i class="fa fa-circle"></i> En Stock</p>
                                    <?php }else{?>
                                        <p class="avaibility m-0" style="color:#6b6b6b;font-size:14px;text-transform:uppercase"><i class="fa fa-circle rupture"></i> En Rupture</p>
                                    <?php } ?>
                                    <?php if(prixPromoProduits($id_p) != '0.000') { ?>
                                        <p class="product-price"><?php echo prixPromoProduits($id_p); ?> DT  <br/> <span style="text-decoration:line-through;color:#aaa;font-size: 18px;"><?php echo prixVenteProduits($id_p); ?> DT</span> </p>
                                    <?php }else{?>
                                        <p class="product-price"><?php echo prixVenteProduits($id_p); ?> DT </p>
                                    <?php }?>
                                </div>
                                <!-- Ratings & Cart -->
                                <div class="ratings-cart d-flex w-100 justify-content-between">
                                    <div class="cart">
                                        <?php if(etatStockProduits($id_p) == '1'){  ?>
                                        <button data-toggle="tooltip" data-placement="top"  onclick="addToCart(<?php echo afficheChamp($id_p) ?>,'1')" id="AddCart" class="btn btn-primary-outline" title="Ajouter au panier"  style="font-size: 12px;padding: 0px 1px;"><img src="dist/img/cart.png" alt="" class="img-fluid" style="width:15px!important;display:inline-block;"> Ajouter au panier</button>
                                        <?php }else{ ?>
                                        <button data-toggle="tooltip" data-placement="top"  onclick="addToCart(<?php echo afficheChamp($id_p) ?>,'1')" id="AddCart" style="background: unset;opacity: 0.5; font-size: 12px;padding: 0px 1px;" class="btn btn-primary-outline" disabled title="En rupture"><img src="dist/img/cart.png" alt="" class="img-fluid" style="width:15px!important;display:inline-block;"> Ajouter au panier</button>
                                        <?php } ?>
                                    </div>
                                    <div class="cart" data-toggle="tooltip" data-placement="top" title="Détails produit">
                                        <a href="<?php echo lienProduits($link_p); ?>"  class="btn btn-primary-outline"  style="font-size: 12px;padding: 0px 1px;"><i class="fa fa-eye" style="color:#ababab"></i> Voir détails</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php } } } ?>
        </div>
    </div>
    <?php } ?>