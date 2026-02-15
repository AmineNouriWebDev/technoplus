<?php

	//session_start();
	include("include.php");
	
		
	$requete = "SELECT * FROM `site_menu` WHERE `id` = '14'";
    //echo $requete;
    $resultat = executeRequete($requete);
    $data = mysqli_fetch_array($resultat);
    if($data['id']!=""){
        $id=afficheChamp($data['id']);
        $titre=afficheChamp($data['titre']);		        
        $contenu=afficheChamp($data['contenu']);
        $description_page=afficheChamp($data['description']);
        $title_page=afficheChamp($data['titre_page']);
        $keywords_page=afficheChamp($data['keywords']);


    }else{
        $url = current_url();
        $date = timestampTD(date("d/m/Y H:i:s"));
        executeRequete("INSERT INTO `pages_introuvables`(`url_page`, `date`) VALUES ('".$url."','".$date."')");
        ?>
	<script language="javascript">
	<!--
		window.location = '/error404.html';
	-->
	</script>
	<?php
	//echo $strSQL;
	exit;
    }

	/* DEBUG: FUNCTION LOG */
	function log_debug($msg) {
		$logFile = __DIR__ . '/debug_inscription.log';
		$date = date('Y-m-d H:i:s');
		file_put_contents($logFile, "[$date] $msg" . PHP_EOL, FILE_APPEND);
	}

	if(isset($_POST['action']) && $_POST['action']=="add" ){
        log_debug("Action ADD started.");
        
        try {

            $nom=sanitize($_POST['nom']);
            
            $prenom=sanitize($_POST['prenom']);

            $email=sanitize($_POST['email']);

            $tel=sanitize($_POST['phone']);

            $password=sanitize($_POST['password']);

            $confirm_password=sanitize($_POST['confirm_password']);

            log_debug("Inputs sanitized. Email: $email");

            //exit;

            if($password!=$confirm_password){ // mot de passe non identiques erreur
                $erreur="Les mot de passe et sa confirmation ne sont pas identiques!";
                log_debug("Error: Passwords do not match.");
            }else
            { // mots de passes identiques c'est ok

                $req="SELECT * FROM `clients` where `email` ='".$email."'";    
                log_debug("Checking existing email: $req");

                $res=executeRequete($req);

                $data1 = mysqli_fetch_array($res);

                if(isset($data1['id']) && $data1['id']!=""){ // compte existe avec l'adresse email 
                
                $erreur="Un compte existe déjà avec cette adresse e-mail!";
                log_debug("Error: Account already exists.");

                }

                else{ // inscription
                
                log_debug("Proceeding to insert new client.");

                $date_creation=time();

                $confirm_key=random(40);

                // Fix default values for missing fields in production DB
                $adresse = "";
                $ville = "";
                $code_postale = "0"; // int(20)
                $commentaire = "";
                $mpc = "";
                $sess_id = "";
                $oauth_provider = "google"; // enum default
                $oauth_uid = "";
                $link = "";
                $date_modif = time();

                $req="INSERT INTO `clients`(`nom`,`prenom`,`email`,`tel`,`password`,`date_creation`,`etat`, `adresse`, `ville`, `code_postale`, `commentaire`, `mpc`, `sess_id`, `oauth_provider`, `oauth_uid`, `link`, `date_modif`, `confirm_key`) VALUES('".$nom."','".$prenom."','".$email."','".$tel."','".$password."','".$date_creation."','1', '".$adresse."', '".$ville."', '".$code_postale."', '".$commentaire."', '".$mpc."', '".$sess_id."', '".$oauth_provider."', '".$oauth_uid."', '".$link."', '".$date_modif."', '".$confirm_key."')";
                
                log_debug("Insert Query: $req");

                //echo $req; exit;

                $connexion=ouvrirCnx() or die("erreur cnx");
                log_debug("DB Connection opened.");

                $result  = mysqli_query($connexion, $req);
                
                if (!$result) {
                    log_debug("DB ERROR: " . mysqli_error($connexion));
                    throw new Exception("DB Insert Failed: " . mysqli_error($connexion));
                }
                log_debug("Client inserted successfully.");


                // envoi email
                // Initialisation sécurisée de $email_contact
                if (!isset($email_contact)) {
                    $email_contact = "";
                }
                log_debug("Email contact safe: $email_contact");

                $email_contacts = array();
                if (!empty($email_contact)) {
                    $email_contacts = explode(';', $email_contact);
                }

                // Si aucun email de contact n'est défini, on ajoute une valeur par défaut ou on gère l'erreur silencieusement pour ne pas bloquer l'inscription
                if (empty($email_contacts)) {
                    // Optionnel: logger l'erreur
                    error_log("Attention: Aucun email de contact défini dans la configuration pour l'inscription.");
                    log_debug("Warning: No contact email defined.");
                }
                
                // Envoi de l'email au client
                if (!empty($email)) {
                    log_debug("Preparing client email.");
                    $clientmail = $prenom . " " . $nom;
                    $sujetmail = sujetEmail(4);
                    log_debug("Client Subject: $sujetmail");
                    $messagemail = str_replace("%%NOMCLT%%", $clientmail, messageEmail(4));
                    log_debug("Client Message prepared.");

                    $headers  = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
                    $nomSite_safe = isset($nomSite) ? $nomSite : 'Technoplus';
                    $from_email = !empty($email_contacts) ? $email_contacts[0] : 'no-reply@technoplus.io';
                    $headers .= 'From: ' . $nomSite_safe . ' <' . $from_email . '>' . "\r\n";

                    if ($_SERVER['SERVER_NAME'] != 'localhost') {
                        log_debug("Sending email to $email from $from_email");
                        @mail($email, $sujetmail, $messagemail, $headers, "-f " . $from_email);
                        log_debug("Email sent (supposedly).");
                    } else {
                        log_debug("Localhost: Skipping mail.");
                    }
                }

                // Envoi des alertes aux administrateurs
                if (!empty($email_contacts)) {
                    log_debug("Preparing admin alerts.");
                    $sujetmailadmin = sujetEmail(7);
                    $detailsclt = "Nom :" . $prenom . " " . $nom . "<br />";
                    $detailsclt .= "Tél :" . $tel . "<br />";
                    $detailsclt .= "E-mail :" . $email . "<br />";
                    $messagemailadmin = str_replace("%%DETAILSCLT%%", $detailsclt, messageEmail(7));

                    foreach ($email_contacts as $emc) {
                        if (!empty($emc)) {
                            $headers_admin = 'MIME-Version: 1.0' . "\r\n";
                            $headers_admin .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
                            $headers_admin .= 'From: ' . $nomSite_safe . ' <' . $emc . '>' . "\r\n";

                            if ($_SERVER['SERVER_NAME'] != 'localhost') {
                                log_debug("Sending admin alert to $emc");
                                @mail($emc, $sujetmailadmin, $messagemailadmin, $headers_admin, "-f " . $emc);
                            }
                        }
                    }
                }



                $new_id = mysqli_insert_id($connexion);
                log_debug("New ID: $new_id");
                $sess_id = md5(microtime());
                
                // Update sess_id in DB
                $strSQL1 = "UPDATE `clients` SET sess_id='".$sess_id."' WHERE id='".$new_id."'";
                executeRequete($strSQL1);
                log_debug("Session ID updated in DB.");

                // Set SESSION variables (Auto-login)
                $_SESSION['client_id'] = $new_id; 
                $_SESSION['client_login'] = $email;
                $_SESSION['client_nom'] = $nom;
                $_SESSION['sess_id'] = $sess_id;
                
                log_debug("Session set. Redirecting...");

                // Redirect
                ?>
                    <script language="javascript">
                    window.location = '<?php echo lienCompte();?>';
                    </script>
                <?php
                exit;


                }

            }
        
        } catch (Exception $e) {
            log_debug("CRITICAL EXCEPTION: " . $e->getMessage());
            $erreur = "Une erreur est survenue: " . $e->getMessage();
        } catch (Throwable $t) {
            log_debug("CRITICAL THROWABLE: " . $t->getMessage() . " in " . $t->getFile() . ":" . $t->getLine());
            $erreur = "Erreur fatale: " . $t->getMessage();
        }

	}
	?>

<!DOCTYPE html>

<html lang="en">

<head>

	<?php include('includes/script-header.php');?>
    <?php include('includes/script_panier.php');?>
	
	<link rel="stylesheet" href="dist/scss/style.css" />
	
</head>

<body>
	<?php include('includes/feedback.php');?>
	
	<?php include('includes/top-bar.php');?>
	
	<?php include('includes/banniere.php');?>

	
	<?php 
	$variable2='<li class="breadcrumb-item active" aria-current="page">'.titrePage(14).'</li>';
	include('includes/breadcrumb.php');
	
	?>
	
    <div class="container login-container mt-5 mb-5">
		<div class="row align-items-center" style="background: #29215a;">
        <div class="col-md-6 ads2">
		 
          <!--h1><span id="fl">Company</span><span id="sl">Name</span></h1-->
		  <span id="span">Si vous avez déjà un compte, connectez maintenant !</span>
		  <hr class="hr"/>
		  <a href="<?php echo lienConnexion();?>" class="btn btn-primary" id="btn">Connexion</a>
        </div>
        <div class="col-md-6 login-form">
        
          <div class="profile-img text-center">
            <img src="media/site/<?php echo $logo;?>" alt="profile_img" class="img-fluid">
          </div> 
          
          <?php if(isset($erreur) && $erreur!="") { ?>
            <div class="alert alert-danger text-center" role="alert">
                <?php echo $erreur; ?>
            </div>
          <?php } ?>

          <?php if(isset($success_msg) && $success_msg!="") { ?>
            <div class="alert alert-success text-center" role="alert">
                <?php echo $success_msg; ?> <br>
                <a href="<?php echo lienConnexion(); ?>" class="font-weight-bold">Cliquez ici pour vous connecter.</a>
            </div>
          <?php } ?>

          <h3>Informations client</h3>
          <form action="<?php echo lienInscription(); ?>" method="post" id="myform" enctype="multipart/form-data">
            <div class="form-group">
              <input type="text" class="form-control" name="nom" placeholder="Nom" required>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="prenom" placeholder="Prénom" required>
            </div>
            <div class="form-group">
              <input type="email" class="form-control" name="email" placeholder="E-mail" required>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="phone" placeholder="Téléphone" required>
            </div>
            <div class="form-group">
              <input type="password" class="form-control" name="password" placeholder="Mot de passe" required>
            </div>
            <div class="form-group">
              <input type="password" class="form-control" name="confirm_password" placeholder="Confirmer mot de passe" required>
            </div>
            <div class="form-group">
				<button type="submit" class="btn btn-primary btn-lg btn-block">S'inscrire</button>			  
				<input type="hidden" name="action" value="add">
            </div>
            <div class="form-group forget-password">
                <a href="<?php echo lienforget();?>">Mot de passe oublié !</a>
            </div>
          </form>
        </div>
      </div>
    </div>





      <!-- ======= Footer ======= -->
      <?php include('includes/footer.php');?>


 	 <?php include('includes/script-footer.php');?>

	
</body>

</html>