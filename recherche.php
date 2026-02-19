<?php

	session_start();


	include("include.php");


        $Request = 'SELECT * FROM `site_menu` WHERE `id` = "20" AND `etat` = "1" ';
		
    	$Result  = executeRequete($Request) ;
		
    	$Datum = mysqli_fetch_array($Result);
            
        if(isset($_GET['marque']) && isset($_GET['categorie'])) { $title_page = str_replace("%%CATEGORIE%%",titreCategories($_GET['categorie']),$title_marque);  $title_page = str_replace("%%MARQUE%%",$_GET['marque'],$title_page); }elseif($Datum['titre_page'] != '') $title_page=afficheChamp($Datum['titre_page']); 
            
        if(isset($_GET['marque']) && isset($_GET['categorie'])) { $keywords_page = str_replace("%%CATEGORIE%%",titreCategories($_GET['categorie']),$keywords_marque); $keywords_page = str_replace("%%MARQUE%%",$_GET['marque'],$keywords_page);  }elseif($Datum['keywords'] != '') $keywords_page=afficheChamp($Datum['keywords']);  
            
        if(isset($_GET['marque']) && isset($_GET['categorie'])) { $description_page = str_replace("%%CATEGORIE%%",titreCategories($_GET['categorie']),$description_marque); $description_page = str_replace("%%MARQUE%%",$_GET['marque'],$description_page);  }elseif($Datum['description'] != '') $description_page=afficheChamp($Datum['description']); 
		
        $contenu = afficheChamp($Datum['contenu']);
		
    	$titre   = afficheChamp($Datum['titre']);
		
    	$id = $Datum['id'];
    	
        $img=afficheChamp($Datum['image']);
        
        $img_entete = photoPageSite($id);
    	
    	
	$variable2='<li class="breadcrumb-item active" aria-current="page">'.$titre.'</li>';
?>


<!DOCTYPE html>

<html lang="en">

<head>

	<?php include('includes/script-header.php');?>
    <?php include('includes/script_panier.php');?>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
	
	<link rel="stylesheet" href="dist/scss/style.css" />
	
	<style>
    	.marque-logo .card {
            width: 100%;
            height: 100%;
            overflow: hidden;
            justify-content: center;
        }
        .marque-logo .card img {
            width: 100%;
            object-fit: contain;
            height: -webkit-fill-available;
            background: #e4e4e4;
        }
        .select2-container{ width:100%!important;}
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid #ced4da;
            height: 38px;
        }
    	.select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 38px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
        }

        /* Premium Pagination Styling - Fixed Ergonomics */
        .pagination .page-item {
            margin: 0 4px; 
            display: inline-block; /* Ensure items don't collapse */
        }

        .pagination .page-item .page-link {
            color: #2b2b2b;
            border: 1px solid #ebebeb;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 600;
            transition: all 300ms ease;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            width: auto !important; /* Force auto width */
            white-space: nowrap; 
            overflow: visible; /* Ensure content is not clipped */
        }

        /* Adjust width for text-heavy buttons (Précédent / Suivant) */
        .pagination .page-item.mx-2 {
            margin-right: 15px !important;
            margin-left: 15px !important;
        }

        .pagination .page-item.mx-2 .page-link {
            min-width: 120px !important; /* Give them enough space */
            width: auto !important;
            padding: 0 20px !important;
        }

        .pagination .page-link i {
            font-size: 18px;
            line-height: 1;
        }

        /* Spacing for icons */
        .pagination .page-link i.fa-angle-left {
            margin-right: 10px;
        }

        .pagination .page-link i.fa-angle-right {
            margin-left: 10px;
        }

        .pagination .page-item.active .page-link {
            background-color: #fbb710;
            border-color: #fbb710;
            color: #fff;
        }

        .pagination .page-item:not(.active):hover .page-link {
            background-color: #2b2b2b;
            border-color: #2b2b2b;
            color: #fff;
        }

        .pagination .page-item.disabled .page-link {
            color: #ccc;
            background-color: #f9f9f9;
            border-color: #ebebeb;
        }
	</style>
    <link rel="stylesheet" href="dist/scss/mobile-grid.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="dist/scss/reset-overflow.css?v=<?php echo time(); ?>">
    
</head>

<body>
	<?php include('includes/feedback.php');?>
	
	<?php include('includes/top-bar.php');?>
	
	<?php include('includes/banniere.php');?>
	
	<?php include('includes/recherche.php');?>


      <!-- ======= Footer ======= -->
      <?php include('includes/footer.php');?>


 	 <?php include('includes/script-footer.php');?>
	 
	 
	 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.js"></script>
	 
	 
	<?php
    if ((isset($_GET['categorie']) && $_GET['categorie'] != '')){ 
    $reqprice = 'SELECT MIN(prix_vente) as min, MAX(prix_vente) as max FROM `produits` WHERE categorie="'.idCategBlog($_GET['categorie']).'" || idparent_categ="'.idCategBlog($_GET['categorie']).'"';
    $resprice = executeRequete($reqprice);
    $dataprice = mysqli_fetch_array($resprice);
	}else{
    $reqprice = 'SELECT MIN(prix_vente) as min, MAX(prix_vente) as max FROM `produits`';
    $resprice = executeRequete($reqprice);
    $dataprice = mysqli_fetch_array($resprice);
	}
    ?>	
	
 	<script type="text/javascript">
 	
    $(document).ready(function(){

		filter_data();

		function filter_data(page = 1)
		{
			var action = 'fetch_data';
            var minimum_price = $('#hidden_minimum_price').val();
            var maximum_price = $('#hidden_maximum_price').val();
			var search    = "<?php if ((isset($_POST['recherche']) && $_POST['recherche'] != '')){ echo $_POST['recherche']; } /*elseif ((isset($_GET['marque']) && $_GET['marque'] != '')){ echo $_GET['marque']; }*/elseif((isset($_POST['action']) && $_POST['action'] == 'search') || (isset($_POST['action']) && $_POST['action'] == 'search1')){ echo addslashes($_POST['recherche']); }else{ echo  ''; }  ?>";
			var brand = get_filter('brand') ;
            var promo         = "<?php if(isset($_GET['promo'])) echo 'promo';else echo ''; ?>";
			var marque = "<?php if((isset($_GET['marque']) && $_GET['marque'] != '')){ echo $_GET['marque']; }else{ echo  ''; }  ?>";
			var type = document.getElementById('typeProd').value;
			var link = document.getElementById('linkProd').value;
			var category = get_filter('category');
			var caracteristique = get_filter('caracteristique');
			var categoryByTitre = '<?php if ((isset($_GET['categorie']) && $_GET['categorie'] != '')){ echo $_GET['categorie']; }elseif ((isset($_POST['categorie']) && $_POST['categorie'] != '')){ echo linkCategBlog($_POST['categorie']); }else{ echo ''; } ?>';
			
			$('.filter_data').html('<div class="row"> <div class="col-12"><div id="loading"></div></div></div>');
			
			$.ajax({
				url:"includes/fetch_data_test.php",
				method:"POST",
				data:{action:action,brand:brand, category:category,caracteristique:caracteristique, type:type,link:link,search:search, minimum_price:minimum_price, maximum_price:maximum_price,categoryByTitre:categoryByTitre,marque:marque,promo:promo, page:page },
				
				success:function(data){
					$('.filter_data').html(data);
                    init_price_slider();
                    // Scroll to top of products after page change
                    if (page > 1) {
                        $('html, body').animate({
                            scrollTop: $('.filter_data').offset().top - 100
                        }, 500);
                    }
				}
			});
		}

		function get_filter(class_name)
		{
			var filter = [];
			$('.'+class_name+':checked:visible').each(function(){
				filter.push($(this).val());
			});
            // Handle select elements for mobile
            $('select.'+class_name+':visible').each(function(){
                if($(this).val() != ''){
                    filter.push($(this).val());
                }
            });
			return filter;
		}

		$(document).on('change', '.common_selector', function(){
			filter_data();
		});

        // ========== PAGINATION CLICK HANDLER ==========
        $(document).on('click', '.pagination-link', function(e){
            e.preventDefault();
            var page = $(this).data('page');
            if (page && !$(this).parent().hasClass('disabled') && !$(this).parent().hasClass('active')) {
                // Modified filter_data to accept page parameter
                filter_data(page);
            }
        });
        // ==============================================

        function init_price_slider() {
            var min = <?php echo $dataprice['min']; ?>;
            var max = <?php echo $dataprice['max']; ?>;
            var cur_min = $('#hidden_minimum_price').val() || min;
            var cur_max = $('#hidden_maximum_price').val() || max;

            var slider_options = {
                range:true,
                min: min,
                max: max,
                values:[cur_min, cur_max],
                format:"DT",
                step:0.001,
                unit:'DT',
                stop:function(event, ui)
                {
                    $('#price_show, #price_show_mobile').html(ui.values[0] + ' DT - ' + ui.values[1] +' DT');
                    $('#hidden_minimum_price').val(ui.values[0]);
                    $('#hidden_maximum_price').val(ui.values[1]);
                    filter_data();
                },
                slide: function(event, ui) {
                    $('#price_show, #price_show_mobile').html(ui.values[0] + ' DT - ' + ui.values[1] +' DT');
                }
            };

            if ($('#price_range').length > 0) {
                $('#price_range').slider(slider_options);
                $('#price_show').html(cur_min + ' DT - ' + cur_max +' DT');
            }
            if ($('#price_range_mobile').length > 0) {
                $('#price_range_mobile').slider(slider_options);
                $('#price_show_mobile').html(cur_min + ' DT - ' + cur_max +' DT');
            }
        }

        init_price_slider();
		$('.slect2').select2();

    });
    
    </script>
	
</body>

</html>