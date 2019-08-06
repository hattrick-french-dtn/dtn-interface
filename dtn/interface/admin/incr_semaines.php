<?php
ini_set('max_execution_time',600);
/* Mise à jour des semaines d'entraînement */
require_once("../includes/serviceJoueur.php");
require_once("../includes/serviceEquipes.php");
require_once("../includes/serviceListesDiverses.php");
require_once("../includes/serviceMatchs.php");
require_once("../CHPP/config.php");
error_reporting(E_ALL);
    
// Service réservé aux DTN#
if ($_SESSION['sesUser']["idNiveauAcces_fk"] == 1){

  $lstPos = listAllPosition();
  if (!isset($affPosition)) {$affPosition="";}   	
    
  if($affPosition == "") {
  	if ($_SESSION['sesUser']["idPosition_fk"] == ""){
      $affPosition = "0";
  	}else{
      $affPosition = $_SESSION['sesUser']['idPosition_fk'];
  	}
  }
} else {
	echo("Fonction impossible.");
	return;
}
$lstBehaviour=list_behaviour();
$lstRole=list_role();

/******************************************************************************/
/*      AFFICHAGE CONTENU PAGE                                                */
/******************************************************************************/
?>
<br />
<center>
<a href="index.php?redirect=matchsOverview.php"><u>Acc&eacute;der aux options de gestion/administration des matchs</u></a>
</center>

<!-- FORMULAIRE AUTORISATION -->
<div class="ContenuCentrer">
  <br />
<?php
if ( isset($_SESSION['HT']) ) { ?>

Votre compte DTN est li&eacute; &agrave; votre compte Hattrick : <?php echo($_SESSION['nomUser']." [".$_SESSION['idUserHT']."]"); ?>

<?php } else {?>
Vous devez etre connect&eacute; &agrave; Hattrick.&nbsp;&nbsp;
  <br />
    <form name="formConnexionHT" method="get" action="">
      <input name="mode" type="hidden" value="redirectionHT">
      <input type="submit" value="AUTORISER ACC&Egrave;S CHPP" class="bouton" /> <br /><br />
    </form>
<?php }?>
<br /> 
</div>
<?php
if ( isset($_SESSION['HT']) ) {
  ?><p>
  <center>  
  <table border=1 width=90% >  
  <tr>
  <td align="left">
    <img src="../images/greenball.jpg"> Votre session hattrick est active ! 
    <br />
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
      <td height="20" >
        <form name="majSemainesDetails" method="post" action="<?=$_SERVER['REQUEST_URI']?>">
        <div align="center"><font color="blue">Mise &agrave; jour des semaines d'entrainement</font></div>
        <br />
        <b>Secteur de jeu : </b>
					
        <?php if (isset($_REQUEST['ht_posteAssigne'])) {
				  // $_REQUEST['ht_posteAssigne'] est défini seulement lorsqu'on a chargé déjà une page de joueur?>
				  <INPUT TYPE="hidden" NAME="ht_posteAssigne" VALUE="<?=$_REQUEST['ht_posteAssigne']?>" >
          <?php if ($_REQUEST['ht_posteAssigne']==-1) {
						$ht_posteAssigne = "Tous les joueurs"; 
          		} else {
						$ht_posteAssigne = $lstPos[($_REQUEST['ht_posteAssigne']-1)]["descriptifPosition"];
          		} 
				echo $ht_posteAssigne;
     		 } else { ?>
                    <div align="center"><font color="red">Attention ! Ne lancez cette mise à jour (en mode auto) que le jeudi soir ou le vendredi matin, et v&eacute;rifiez bien que personne d'autre ne l'a lanc&eacute;e avant vous
                    <br />sous peine de doubler ses effets sans possibilit&eacute; de retour en arrière...</font></div>
                    <br />
					<SELECT NAME="ht_posteAssigne" SIZE=1>
						<OPTION VALUE="-1" SELECTED>-- Tous les joueurs --</OPTION>
						<OPTION VALUE="0">Non assign&eacute;s</OPTION>
						<OPTION VALUE="1">Gardiens</OPTION>
						<OPTION VALUE="2">D&eacute;fenseurs</OPTION>
						<OPTION VALUE="4">Milieux de terrain</OPTION>
						<OPTION VALUE="3">Ailiers</OPTION>
						<OPTION VALUE="5">Attaquants</OPTION>
						<OPTION VALUE="7">Tireurs de Loin</OPTION>
					</SELECT>
  <?php } ?>
        <div align="center">
        <?php if(!isset($_REQUEST['ht_posteAssigne'])) { // Si on a pas encore soumis le formulaire donc pas encore chargé de maj ?>
          <br />
          Nombre de joueurs maximum par page : <input type="text" name="nbrePlayersMax" value="20" />
          <br />
          <br />
          <input type="submit" name="Submit" value="Incrémenter les semaines" />
        <?php } ?>
        </div>
      </form>
      </td>
      </tr>
      <!-- AFFICHAGE DES JOUEURS -->
      <tr>
      <td align="center">
        <?php 
        if (isset($_REQUEST['ht_posteAssigne'])) {
        
          // Initialisation des variables
         	if(!isset($_REQUEST['nbrePlayersMax'])) { // Transmis par le formulaire
         		$_REQUEST['nbrePlayersMax']=50;
         	}
         	if(!isset($_GET['startingPlayer'])) { // Transmis dans l'URL sauf au premier chargement de la page
         		$_GET['startingPlayer']=0; 
         	}
            
		    $todaySeason=getSeasonWeekOfMatch(mktime(0,0,0,date('m'), date('d'),date('Y')));
			
			// Extraction des joueurs
            $SqlAgeJoueur=getCalculAgeAnneeSQL(); // Récupère l'âge (année) dans la BDD
        
            // On récupère tous les joueurs potentiellement scannables
        if ($_REQUEST['ht_posteAssigne'] != -1) {
            $sql = "SELECT $tbl_joueurs.idHattrickJoueur
                    FROM $tbl_joueurs 
      		        WHERE $tbl_joueurs.ht_posteAssigne = '".$_REQUEST['ht_posteAssigne']."'
      		        AND $tbl_joueurs.affJoueur = '1'
                    AND $tbl_joueurs.isScannable = 1 
                    ORDER BY idHattrickJoueur DESC,prenomJoueur,nomJoueur 
                    LIMIT ".$_GET['startingPlayer'].",".$_REQUEST['nbrePlayersMax'];
		} else {
            $sql = "SELECT $tbl_joueurs.idHattrickJoueur
                    FROM $tbl_joueurs 
      		        WHERE $tbl_joueurs.affJoueur = '1'
                    AND $tbl_joueurs.isScannable = 1 
                    ORDER BY idHattrickJoueur DESC,prenomJoueur,nomJoueur 
                    LIMIT ".$_GET['startingPlayer'].",".$_REQUEST['nbrePlayersMax'];		
		}		
                    $req = $conn->query($sql);
          
			if(!$req){
				echo("Erreur lors de l'extraction des joueurs. Contactez un d&eacute;veloppeurs ou les administrateurs de la DTN.");
				exit;
			} elseif ($req->rowCount() == 0) {
				echo("Pas de joueur trouv&eacute;");
			} else { // Création liste id joueur à scanner
                $i=0;
                while($i<$req->rowCount()) {
                    $joueur = $req->fetch(PDO::FETCH_ASSOC);
                    $listeID[]=$joueur["idHattrickJoueur"];
                    $i++;
                }
				$req=NULL;
            
            // MAJ des joueurs
            unset($resUpdateJoueur);
            $resUpdateJoueur=scanHebdoJoueurs($listeID,$_SESSION['sesUser']["loginAdmin"],"D",true,true);
            ?>

            <p><b>Chargement des donn&eacute;es depuis hattrick. Attendez la fin de l'op&eacute;ration.</b></p>
            <table class="cadre" width="97%">
            <tr class="activ">
              <td>JOUEUR</td>
              <td>AGE</td>
              <td>VENTE/BLESSURE/ENTR.</td>
              <td>MATCH(ID)</td>
              <td>ETOILES</td>
              <td>POSTE</td>
            </tr>
            <?php

            for ($j=0;$j<count($resUpdateJoueur);$j++) {

				echo($resUpdateJoueur[$j]['HTML']);

            	if ($j%20==0 && $j>0){?>
            		</table><br />.
            		<table class="cadre" width="97%">
            		<tr class="activ">
                        <td>JOUEUR</td>
                        <td>AGE</td>
                        <td>VENTE/BLESSURE/ENTR.</td>
                        <td>MATCH(ID)</td>
                        <td>ETOILES</td>
                        <td>POSTE</td>
                </tr>
            	<?php }
              flush();
              if ($j >= $_REQUEST['nbrePlayersMax']){
                break;
              }
            } // Fin Boucle
            ?>

          </table>
          </font>
          <br />
          <font color=#229922> Op&eacute;ration termin&eacute;e.</font><br />
          <?php if ($j==$_REQUEST['nbrePlayersMax']) {
            $newLimit=$_GET['startingPlayer']+$_REQUEST['nbrePlayersMax'];
            $lien="index3.php?ht_posteAssigne=".$_REQUEST['ht_posteAssigne']."&nbrePlayersMax=".$_REQUEST['nbrePlayersMax']."&startingPlayer=$newLimit";?>
            <center>
        		<font color=#red> Attention ! Il reste probablement encore des joueurs &agrave; mettre &agrave; jour ! (nombre d'update max : <?php echo($_REQUEST['nbrePlayersMax']);?>)</font><p>
        		<font color=#222299>
            &gt;&gt; <a href="<?php echo($lien);?>">Continuer les mises &agrave; jour </a>&lt;&lt;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            &gt;&gt; <a href="<?php echo($lien);?>&modemajauto=1">Mode automatique de mise &agrave; jour </a>&lt;&lt;
            </font>
            </center>
            <br />
            <?php if (isset($_GET['modemajauto']) && $_GET['modemajauto']==1) {?>
              <script language="JavaScript" type="text/javascript">
          		function recharge()
          		{
          			document.location.href = "<?=$lien?>&modemajauto=1";
          		}
          		setTimeout("recharge()",3000);
              </script>
            <?php }
          }
        }
      }?>
    </td>
    </tr>
    </table>
  </td>
  </tr>
</table>
</center>
<?php }?>
