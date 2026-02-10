<?php
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