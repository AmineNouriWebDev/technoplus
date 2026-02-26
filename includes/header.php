	
<?php include('includes/menu.php');?>
	<?php // include('includes/sidebar.php'); // Removed to prevent duplicate menu display ?>
	
	<style>
		/* Custom Carousel Caption CSS (Text only) */
		.custom-carousel-caption {
			position: absolute;
			left: 5%; /* Moved to left */
			bottom: 15%; /* Higher to avoid the button */
			background: rgba(15, 23, 42, 0.65); /* Dark blue/slate tint */
			backdrop-filter: blur(12px); /* Glassmorphism effect */
			-webkit-backdrop-filter: blur(12px);
			padding: 2rem 2.5rem;
			border-radius: 16px;
			border: 1px solid rgba(255, 255, 255, 0.1);
			text-align: left;
			max-width: 500px;
			box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
			
			/* Initial state for animation */
			opacity: 0;
			transform: translateX(-30px);
			transition: all 0.8s cubic-bezier(0.25, 1, 0.5, 1);
		}

		/* Animation triggered by Bootstrap's 'active' class on carousel-item */
		.carousel-item.active .custom-carousel-caption {
			opacity: 1;
			transform: translateX(0);
			transition-delay: 0.3s;
		}

		.custom-carousel-caption h1 {
			color: #ffffff;
			font-size: 2.8rem;
			font-weight: 800;
			margin-bottom: 0.5rem;
			text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
			line-height: 1.1;
			letter-spacing: -0.5px;
		}

		.custom-carousel-caption h6 {
			color: #e2e8f0;
			font-size: 1.25rem;
			font-weight: 400;
			margin-bottom: 0;
			text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
			line-height: 1.4;
		}

		/* Button Wrapper CSS */
		.custom-btn-wrapper {
			position: absolute;
			right: 3%;
			bottom: 5%;
			z-index: 10;
			
			/* Initial state for animation */
			opacity: 0;
			transform: translateY(30px);
			transition: all 0.8s cubic-bezier(0.25, 1, 0.5, 1);
		}

		.carousel-item.active .custom-btn-wrapper {
			opacity: 1;
			transform: translateY(0);
			transition-delay: 0.5s; /* Appears slightly after the text */
		}

		/* Trendy Modern Button */
		.custom-btn-header {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			background: linear-gradient(135deg, #0ea5e9, #3b82f6); /* Vibrant blue gradient */
			color: #ffffff;
			font-weight: 600;
			font-size: 1.1rem;
			padding: 0.8rem 2rem;
			border-radius: 50px; /* Pill shape */
			text-decoration: none;
			box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.4);
			transition: all 0.3s ease;
			border: 1px solid rgba(255,255,255,0.2);
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}

		.custom-btn-header:hover {
			transform: translateY(-3px) scale(1.02);
			box-shadow: 0 20px 25px -5px rgba(59, 130, 246, 0.5);
			color: #ffffff;
			text-decoration: none;
			background: linear-gradient(135deg, #3b82f6, #60a5fa);
		}

		.custom-btn-header span {
			position: relative;
			z-index: 2;
		}

		/* Optional Full Link Overlay */
		.custom-carousel-link-overlay {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			z-index: 5;
		}

		/* Responsive adjustments */
		@media (max-width: 768px) {
			.custom-carousel-caption {
				left: 50%;
				bottom: 25%;
				transform: translateX(-50%) translateY(20px);
				text-align: center;
				width: 90%;
				max-width: none;
				padding: 1.5rem;
				background: rgba(15, 23, 42, 0.75);
			}
			.carousel-item.active .custom-carousel-caption {
				transform: translateX(-50%) translateY(0);
			}
			.custom-btn-wrapper {
				right: 50%;
				transform: translateX(50%) translateY(20px);
				bottom: 8%;
			}
			.carousel-item.active .custom-btn-wrapper {
				transform: translateX(50%) translateY(0);
			}

			.custom-carousel-caption h1 { font-size: 2rem; }
			.custom-carousel-caption h6 { font-size: 1rem; }
			.custom-btn-header { padding: 0.6rem 1.5rem; font-size: 1rem; }
		}
	</style>

	<!---------------- Header ----------------------->
	<header class="header">
	
		<div id="myCarousel" class="carousel slide" data-ride="carousel">
			
	    <?php 
			$requete1   = "SELECT * FROM `sliders` WHERE `etat` = '1'";
			$resultat1  = executeRequete($requete1);
			$counter   = 0;
			$count	   = mysqli_num_rows($resultat1);			
	    ?>
		
			<!--Slides-->
			<div class="carousel-inner" role="listbox">
			<?php while($data1 = mysqli_fetch_array($resultat1)) { 
				$id_slider = $data1['id'];
				$titre = titreSlider($id_slider);
				$soustitre = titre1Slider($id_slider);
				$textBtn = textBtnSlider($id_slider);
				$lien = lienSlider($id_slider);
			?>
			
                <div class="carousel-item <?php echo ( $counter == 0 ? ' active' : '' ) ?>" <?php echo ( $counter == 0 ? ' data-interval="3000"' : 'data-interval="2000"' ) ?>>
                      <img class="d-block img-fluid" src="<?php echo photoSliderSite($data1['id']); ?>" alt="Slide" style="max-height:660px;margin:auto;width:100%">
					  
					  <?php if($titre != '' || $soustitre != '') { ?>
					  <div class="custom-carousel-caption">
						  <?php if($titre != '') { ?>
						  <h1><?php echo $titre; ?></h1>
						  <?php } ?>
						  
						  <?php if($soustitre != '') { ?>
						  <h6><?php echo $soustitre; ?></h6>
						  <?php } ?>
					  </div>
					  <?php } ?>
					  
					  <?php if($textBtn != '') { ?>
					  <div class="custom-btn-wrapper">
					      <a href="<?php echo ($lien != '' ? $lien : '#'); ?>" class="custom-btn-header">
						      <span><?php echo $textBtn; ?></span>
					      </a>
					  </div>
					  <?php } else { ?>
						  <?php if($lien != '') { ?>
						  <a href="<?php echo $lien; ?>" class="custom-carousel-link-overlay"></a>
						  <?php } ?>
					  <?php } ?>
                </div>
                
			<?php $counter++;  } ?>
			</div>
			<a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev" style="background: rgb(40 32 88 / 50%);width: 3%;">
			  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
			  <span class="sr-only">Previous</span>
			</a>
			<a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next" style="background: rgb(40 32 88 / 50%);width: 3%;">
			  <span class="carousel-control-next-icon" aria-hidden="true"></span>
			  <span class="sr-only">Next</span>
			</a>
		</div>
	</header>
	<!--------------- Fin header ----------------->

