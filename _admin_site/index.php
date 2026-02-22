<?php
ob_start();
session_start();

/* Connexion PDO */
include("includes/include.php");

/* Protection login */
include("includes/security.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<!-- Responsive -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta name="description" content="">
	<meta name="author" content="">
	<meta http-equiv="Content-Language" content="ar">

	<title>Tableau de bord</title>

	<?php include("includes/scripts.php"); ?>

	<style>
		.sidebar-nav>ul>li.active>a {
			background: #e9ecef !important;
		}
	</style>

</head>

<body class="fix-header fix-sidebar card-no-border">

	<!-- Preloader -->
	<div class="preloader">
		<svg class="circular" viewBox="25 25 50 50">
			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
		</svg>
	</div>

	<div id="main-wrapper">

		<!-- Header -->
		<?php include("includes/header.php"); ?>

		<!-- Sidebar -->
		<?php include("includes/left.php"); ?>

		<!-- Page wrapper -->
		<div class="page-wrapper">

			<!-- Breadcrumb -->
			<div class="row page-titles">
				<div class="col-md-5 align-self-center">
					<h3 class="text-themecolor"><?php echo $nomSite ?? ''; ?></h3>
				</div>
				<?php include("includes/fils_ariane.php"); ?>
			</div>

			<!-- Container -->
			<div class="container-fluid">

				<?php
				switch ($_GET['r'] ?? '') {

					case "pages":
						include("includes/fonctions/fction_pages.php");
						include("includes/pages.php");
						break;

					case "npage":
						include("includes/fonctions/fction_pages.php");
						include("includes/ajouter_page.php");
						break;

					case "mpage":
						include("includes/fonctions/fction_pages.php");
						include("includes/modifier_page.php");
						break;

					case "commandes":
						include("includes/fonctions/fction_clients.php");
						include("includes/fonctions/fction_commandes.php");
						include("includes/fonctions/fction_produits.php");
						include("includes/fonctions/fction_emails.php");
						include("includes/commandes.php");
						break;

					case "dcommande":
						include("includes/fonctions/fction_clients.php");
						include("includes/fonctions/fction_commandes.php");
						include("includes/fonctions/fction_produits.php");
						include("includes/fonctions/fction_emails.php");
						include("includes/details_commande.php");
						break;

					case "produits":
						include("includes/fonctions/fction_produits.php");
						include("includes/fonctions/fction_blogs.php");
						include("includes/produits.php");
						break;

					case "addproduit":
						include("includes/fonctions/fction_produits.php");
						include("includes/add_produit.php");
						break;

					case "addproduitssimilaire":
						include("includes/fonctions/fction_produits.php");
						include("includes/add_produits_similaire.php");
						break;

					case "fichesTechniques":
						include("includes/fonctions/fction_produits.php");
						include("includes/fichesTechniques.php");
						break;

					case "facilitePaiement":
						include("includes/fonctions/fction_produits.php");
						include("includes/facilitePaiement.php");
						break;

					case "nproduits":
						include("includes/fonctions/fction_produits.php");
						include("includes/fonctions/fction_blogs.php");
						include("includes/ajouter_produits.php");
						break;

					case "mproduits":
						include("includes/fonctions/fction_produits.php");
						include("includes/fonctions/fction_blogs.php");
						include("includes/modifier_produits.php");
						break;

					case "clients":
						include("includes/fonctions/fction_clients.php");
						include("includes/client.php");
						break;

					case "admins":
						include("includes/admins.php");
						break;

					case "setting":
						include("includes/setting.php");
						break;

					case "messages":
						include("includes/fonctions/fction_messages.php");
						include("includes/messages.php");
						break;

					case "dmessage":
						include("includes/fonctions/fction_messages.php");
						include("includes/detail_message.php");
						break;

					case "optimisationSeo":
						include("includes/optimisation_seo.php");
						break;

					case "bloc_accueil":
						include("includes/fonctions/fction_pages.php");
						include("includes/fonctions/fction_bloc_accueil.php");
						include("includes/bloc_accueil.php");
						break;

					case "mbloc_accueil":
						include("includes/fonctions/fction_pages.php");
						include("includes/fonctions/fction_bloc_accueil.php");
						include("includes/modifier_bloc_accueil.php");
						break;

					case "nbloc_accueil":
						include("includes/fonctions/fction_pages.php");
						include("includes/fonctions/fction_bloc_accueil.php");
						include("includes/ajouter_bloc_accueil.php");
						break;

					case "addSectionContent":
						include("includes/fonctions/fction_pages.php");
						include("includes/fonctions/fction_bloc_accueil.php");
						include("includes/add_section_content.php");
						break;

					case "editSectionContent":
						include("includes/fonctions/fction_pages.php");
						include("includes/fonctions/fction_bloc_accueil.php");
						include("includes/edit_section_content.php");
						break;

					case "listeSection":
						include("includes/fonctions/fction_pages.php");
						include("includes/fonctions/fction_bloc_accueil.php");
						include("includes/liste_sections.php");
						break;

					case "nlisteSection":
						include("includes/fonctions/fction_pages.php");
						include("includes/ajouter_liste_sections.php");
						break;

					case "mlisteSection":
						include("includes/fonctions/fction_pages.php");
						include("includes/fonctions/fction_bloc_accueil.php");
						include("includes/modifier_liste_sections.php");
						break;

					case "services":
						include("includes/fonctions/fction_services.php");
						include("includes/services.php");
						break;

					case "nservice":
						include("includes/ajouter_service.php");
						break;

					case "mservice":
						include("includes/fonctions/fction_services.php");
						include("includes/modifier_service.php");
						break;

					case "sliders":
						include("includes/sliders.php");
						break;

					case "nslider":
						include("includes/ajouter_slider.php");
						break;

					case "mslider":
						include("includes/fonctions/fction_sliders.php");
						include("includes/modifier_slider.php");
						break;

					case "categories_blog":
						include("includes/fonctions/fction_blogs.php");
						include("includes/categories_blog.php");
						break;

					case "ncategorie_blog":
						include("includes/fonctions/fction_blogs.php");
						include("includes/ajouter_categorie_blog.php");
						break;

					case "mcategorie_blog":
						include("includes/fonctions/fction_blogs.php");
						include("includes/modifier_categorie_blog.php");
						break;

					case "categoriesMarques":
						include("includes/fonctions/fction_produits.php");
						include("includes/categories_marques.php");
						break;

					case "caracteristiques":
						include("includes/fonctions/fction_produits.php");
						include("includes/caracteristiques.php");
						break;

					case "ncaracteristiques":
						include("includes/fonctions/fction_produits.php");
						include("includes/ajouter_caracteristiques.php");
						break;

					case "mcaracteristiques":
						include("includes/fonctions/fction_produits.php");
						include("includes/modifier_caracteristiques.php");
						break;

					case "valeurcaracteristiques":
						include("includes/valeurs_caracteristique.php");
						break;

					case "clients":
						include("includes/fonctions/fction_clients.php");
						include("includes/client.php");
						break;

					case "nclient":
						include("includes/fonctions/fction_clients.php");
						include("includes/ajouter_client.php");
						break;

					case "mclient":
						include("includes/fonctions/fction_clients.php");
						include("includes/modifier_client.php");
						break;

					case "applications":
						include("includes/fonctions/fction_applications.php");
						include("includes/applications.php");
						break;

					case "napplications":
						include("includes/fonctions/fction_applications.php");
						include("includes/ajouter_application.php");
						break;

					case "mapplications":
						include("includes/fonctions/fction_applications.php");
						include("includes/modifier_application.php");
						break;

					case "marques":
						include("includes/fonctions/fction_produits.php");
						include("includes/marques.php");
						break;

					case "nmarque":
						include("includes/fonctions/fction_produits.php");
						include("includes/ajouter_marque.php");
						break;

					case "mMarque":
						include("includes/fonctions/fction_produits.php");
						include("includes/modifier_marque.php");
						break;

					case "commandes":
						include("includes/fonctions/fction_commandes.php");
						include("includes/commandes.php");
						break;

					case "etat_commandes":
						include("includes/fonctions/fction_commandes.php");
						include("includes/etat_commandes.php");
						break;

					case "netatcommande":
						include("includes/fonctions/fction_commandes.php");
						include("includes/ajouter_etat_commande.php");
						break;

					case "metatcommande":
						include("includes/fonctions/fction_commandes.php");
						include("includes/modifier_etat_commande.php");
						break;

					case "moyens_paiement":
						include("includes/fonctions/fction_moyens_paiement.php");
						include("includes/moyens_paiement.php");
						break;

					case "nmoyenpaiement":
						include("includes/fonctions/fction_moyens_paiement.php");
						include("includes/ajouter_moyen_paiement.php");
						break;

					case "mmoyenpaiement":
						include("includes/fonctions/fction_moyens_paiement.php");
						include("includes/modifier_moyen_paiement.php");
						break;

					case "fraislivraison":
						include("includes/fonctions/fction_moyens_paiement.php");
						include("includes/frais_livraison.php");
						break;

					case "nfraislivraison":
						include("includes/fonctions/fction_moyens_paiement.php");
						include("includes/ajouter_frais_livraison.php");
						break;

					case "mfraislivraison":
						include("includes/fonctions/fction_moyens_paiement.php");
						include("includes/modifier_frais_livraison.php");
						break;

					case "pagesIntrouvables":
						include("includes/pages_introuvables.php");
						break;

					case "nadmin":
						include("includes/ajouter_admin.php");
						break;

					case "madmin":
						include("includes/modifier_admin.php");
						break;

					case "icones":
						include("includes/fonctions/fction_bloc_accueil.php");
						include("includes/icones.php");
						break;

					case "nicone":
						include("includes/fonctions/fction_bloc_accueil.php");
						include("includes/ajouter_icone.php");
						break;

					case "micone":
						include("includes/fonctions/fction_bloc_accueil.php");
						include("includes/modifier_icone.php");
						break;

					case "templatesemail":
						include("includes/fonctions/fction_emails.php");
						include("includes/templates_email.php");
						break;

					case "mtemplatesemail":
						include("includes/fonctions/fction_emails.php");
						include("includes/modifier_template_email.php");
						break;

					case "social_network":
						include("includes/social_network.php");
						break;

					case "nsocial_network":
						include("includes/ajouter_social_network.php");
						break;

					case "msocial_network":
						include("includes/modifier_social_network.php");
						break;

					default:
						include("includes/home.php");
				}
				?>

			</div>
			<!-- End container -->

			<!-- Footer -->
			<?php include("includes/footer.php"); ?>

		</div>
		<!-- End page wrapper -->

	</div>
	<!-- End main wrapper -->

	<?php include("includes/scripts_footer.php"); ?>
</body>

</html>