	
<?php include('includes/menu.php');?>
	<?php // include('includes/sidebar.php'); // Removed to prevent duplicate menu display ?>
	
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
					  
					  <?php if($titre != '' || $soustitre != '' || $textBtn != '') { ?>
					  <div class="carousel-caption d-none d-md-block" style="bottom: 20%; background: rgba(0,0,0,0.4); padding: 20px; border-radius: 10px;">
						  <div class="full-bg-img">
							  <?php if($titre != '') { ?>
							  <h1 style="color: #fff; font-size: 3rem; margin-bottom: 10px;"><?php echo $titre; ?></h1>
							  <?php } ?>
							  
							  <?php if($soustitre != '') { ?>
							  <h6 class="head-h6" style="color: #fff; font-size: 1.5rem; margin-bottom: 20px;"><?php echo $soustitre; ?></h6>
							  <?php } ?>
							  
							  <?php if($textBtn != '') { ?>
							  <a href="<?php echo ($lien != '' ? $lien : '#'); ?>" class="btn-header">
								  <span><?php echo $textBtn; ?></span>
							  </a>
							  <?php } ?>
						  </div>
					  </div>
					  <?php } else { ?>
						  <?php if($lien != '') { ?>
						  <a href="<?php echo $lien; ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 5;"></a>
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

