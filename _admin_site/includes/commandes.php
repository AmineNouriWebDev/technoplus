<!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <!-- Row -->
 <?php	if (isset($_GET['action']) && $_GET['action'] == 'supp' ) {
		//supprimerFormation($_GET['id']);
		  ?>
	<script language="javascript">
	<!--
		window.location = 'index.php?r=commandes';
	-->
	</script>
	<?php } 
	
	if (isset($_POST['action_cmd'])) {
	    if ($_POST['action_cmd'] == 'supp_multiple' && isset($_POST['ids'])) {
	        supprimerCommandesMultiples($_POST['ids']);
	        exit; // Important since it's an AJAX call
	    }
	}
	?>
	
	<?php
	if(isset($_GET['id'])){
	$idc = $_GET['id'];
	?>
                <div class="row">
				<div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                <div class="col-4">
                                    <h4>Liste des commandes(<a href="index.php?r=mclient&id=<?php echo $idc; ?>" style="text-decoration:underline;"><?php echo prenomClient($idc).' '.nomClient($idc); ?></a>)</h4>
                                </div>
                                <div class="col-8 text-right">
                                    <button class="btn btn-danger delete_all_cmd"><i class="fa fa-trash text-white"></i> Supprimer la sélection</button>
                                </div>
                                </div>
                                <div class="table-responsive">
                                    <table id='tableCmd' class="table color-table info-table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="background-color: #1976d2; text-align:center"><input type="checkbox" id="checkAllCmd" style="position:relative;left:0;opacity:1"></th>
                                                <th style="background-color: #1976d2;">id</th>
                                                <th style="background-color: #1976d2;">N° Commande</th>
                                                <th style="background-color: #1976d2;">Client</th>
                                                <th style="background-color: #1976d2;">Montant</th>
                                                <th style="background-color: #1976d2;">Etat</th>
                                                <th class="text-nowrap" style="background-color: #1976d2;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
								          $requete = 'SELECT * FROM `commandes` WHERE `idclient`='.$idc.'  ORDER BY `commandes`.`date` DESC  ';
                                          $resultat = executeRequete($requete);
	                                      $num = mysqli_num_rows($resultat);
		                                  if ($num == 0 ) {
                                        ?>
                                            <tr>
                                               <td colspan=5>Aucune commande enregistrée pour l'instant</td>
                                            </tr>
                                        <?php
                                        } 
                                          if ($num > 0 ) { 
			                               while ($data = mysqli_fetch_array($resultat))  {
								         ?>
                                            <tr>
                                                <td style="text-align:center"><input type="checkbox" class="sub_chk_cmd" data-id="<?php echo $data['id']; ?>" style="position:relative;left:0;opacity:1"></td>
                                                <td><?php echo $data['id']; ?></td>
                                                <td><?php echo "#".numCommande($data['id'])."<br />".dateCommande($data['id']); ?></td>
                                                <td><?php echo clientCommande($data['id']); ?></td>
                                                <td><?php echo totalCommande($data['id']); ?></td>
                                                <td><?php echo etatCommande($data['id']); if(cmd_expressCommande($data['id']) !='') echo " | <span class='badge badge-success' style='background:#28a745!important'>Commande express</span>"; ?></td>

                                                <td class="text-nowrap">
                                                    <a href="index.php?r=dcommande&id=<?php echo afficheChamp($data['id']); ?>&idc=<?php echo $idc; ?>" data-toggle="tooltip" data-original-title="Consulter les détails"> <i class="fa fa-search text-inverse m-r-10"></i> </a>
                                                    <a href="javascript:void(0)" onclick="confirmDeleteCmd(<?php echo $data['id']; ?>)" data-toggle="tooltip" data-original-title="Supprimer"> <i class="fa fa-trash text-danger m-r-10"></i> </a>
                                                </td>
                                            </tr>
                                         <?php } ?>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                    <div class="col-md-12 mt-4">
                                        <div class="text-right">
                                            <a href="index.php?r=clients" class="btn btn-info"> Retour à la liste </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        
        <?php }else{ ?>
        
                     <div class="row">
				<div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <h4>Liste des commandes</h4>
                                    </div>
                                    <div class="col-8 text-right">
                                        <button class="btn btn-danger delete_all_cmd"><i class="fa fa-trash text-white"></i> Supprimer la sélection</button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="tableCmd" class="table color-table info-table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="background-color: #1976d2; text-align:center"><input type="checkbox" id="checkAllCmd2" style="position:relative;left:0;opacity:1"></th>
                                                <th style="background-color: #1976d2;">id</th>
                                                <th style="background-color: #1976d2;">N° Commande</th>
                                                <th style="background-color: #1976d2;">Client</th>
                                                <th style="background-color: #1976d2;">Montant</th>
                                                <th style="background-color: #1976d2;">Etat</th>
                                                <th class="text-nowrap" style="background-color: #1976d2;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
								          $requete = 'SELECT * FROM `commandes` ORDER BY `id` DESC ';
                                          $resultat = executeRequete($requete);
	                                      $num = mysqli_num_rows($resultat);
		                                  if ($num == 0 ) {
                                        ?>
                                            <tr>
                                               <td colspan=5>Aucune commande enregistrée pour l'instant</td>
                                            </tr>
                                        <?php
                                        } 
                                          if ($num > 0 ) { 
			                               while ($data = mysqli_fetch_array($resultat))  {
								         ?>
                                            <tr>
                                                <td style="text-align:center"><input type="checkbox" class="sub_chk_cmd" data-id="<?php echo $data['id']; ?>" style="position:relative;left:0;opacity:1"></td>
                                                <td><?php echo $data['id']; ?></td>
                                                <td><?php echo "#".numCommande($data['id'])."<br />".dateCommande($data['id']); ?></td>
                                                <td><?php echo clientCommande($data['id']); ?></td>
                                                <td><?php echo totalCommande($data['id']); ?></td>
                                                <td><?php echo etatCommande($data['id']); if(cmd_expressCommande($data['id']) !='') echo " | <span class='badge badge-success' style='background:#28a745!important'>Commande express</span>"; ?></td>

                                                <td class="text-nowrap">
                                                    <a href="index.php?r=dcommande&id=<?php echo afficheChamp($data['id']); ?>" data-toggle="tooltip" data-original-title="Consulter les détails"> <i class="fa fa-search text-inverse m-r-10"></i> </a>
                                                    <a href="javascript:void(0)" onclick="confirmDeleteCmd(<?php echo $data['id']; ?>)" data-toggle="tooltip" data-original-title="Supprimer"> <i class="fa fa-trash text-danger m-r-10"></i> </a>
                                                </td>
                                            </tr>
                                         <?php } ?>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <?php } ?>
        