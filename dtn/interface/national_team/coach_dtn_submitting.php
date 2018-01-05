<?php
require_once("../includes/head.inc.php");
require("../includes/serviceJoueur.php");
require("../includes/serviceEquipes.php");
require("../includes/serviceDTN.php");
require_once("../includes/langue.inc.php");
require_once("../../../language/fr.php");
require("../includes/serviceMatchs.php");
require_once "../_config/CstGlobals.php"; 

if(!isset($_SESSION['sesUser']["idAdmin"]))
{
	header("location: https://".$_SERVER['SERVER_NAME']."/dtn/interface/index.php?ErrorMsg=Session Expire");
}

// Menu
switch($_SESSION['sesUser']["idNiveauAcces"]){
		case "1":
		require("../menu/menuAdmin.php");
		break;
		
		case "2":
		require("../menu/menuSuperviseur.php");
		break;


		case "3":
		require("../menu/menuDTN.php");
		break;
		
		case "4":
		require("../menu/menuCoach.php");
		require("../menu/menuCoachSubmit.php");
		break;
		
		default;
		break;
}


// Initialisation des variables
if(!isset($ordre)) $ordre = "nomJoueur";
if(!isset($sens)) $sens = "ASC";
$lang = "FR";
if(!isset($masque)) $masque = 0;
if(!isset($affPosition)) $affPosition = 0;
if ($sesUser["loginAdmin"]=="CoachA")
	$id_edf = 3004;
else
	$id_edf = 3045;
unset($scan_code);
unset($resuScan);

?>
<title>S&eacute;lectionneur</title>
<script language="JavaScript" type="text/JavaScript">
<!--

//-->


function init()
{
var scrollPos = "<?=$scrollPos?>";
document.body.scrollTop = scrollPos;

}//-->
</script>
<link href="../css/ht2.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<style type="text/css">
<!--
.Style1 {color: #FFFFFF}
.Style3 {color: #FF9933}
.Style4 {color: #FF0000}
-->
</style>
<body onLoad = "init();">
<?php 
error_reporting (E_ALL & ~E_NOTICE);
//

/******************************************************************************/
/******************************************************************************/
/*      DEFINITION FONCTIONS                                                  */
/******************************************************************************/
/******************************************************************************/



/******************************************************************************/
/******************************************************************************/
/*      GESTION AFFICHAGE DU CONTENU DE LA PAGE                               */
/******************************************************************************/
/******************************************************************************/

// Initialisation variables
if (!isset($action))	$action=null;

if (isset($_SESSION['HT'])) {

  if (isset($_POST['load']) && $_POST['load']==1) { // On met à jour les informations clubs et les informations joueurs 
  
    // Initialisation variables
    if (!isset($scan_code))	$scan_code=0;
    
    
    // Récupération de la liste des joueurs
    $listeID = getDataJoueursSelectionsFromHT_usingPHT($id_edf);

    if ($listeID==false) {
      $scan_code=-1;
    } else {
      
      // Insertion ou mise à jour Club (si joueur français détecté)
      if (count($listeID)>0) {
        
        // Login User sur HT 
        $userHT=$_SESSION['HT']->getTeam()->getLoginName();
        
        // Insertion ou mise à jour joueurs
    		$resuScan=scanListeJoueurs($listeID,$userHT,'S',true,false);

    	} else {
    	   $scan_code=-2; // pas de français
    	}
    }
  
  } else { // On affiche un formulaire pour charger les données ?>

    <br />
    <!-- FORMULAIRE LOAD -->
    <div class="ContenuCentrer">
      <br />
      <form name="formLoad" method="post" action="">
        <input name="load" type="hidden" value="1">
        <input type="submit" value="CHARGER DONN&Eacute;ES" class="bouton" /> 
        <br />
      </form>
      
      <br /> 
    </div>
  
  
  <?php }
  
} else { // On affiche le formulaire de connexion à HT ?>

  <br />
  <!-- FORMULAIRE AUTORISATION -->
  <div class="ContenuCentrer">
  
    <?php if ( isset($_SESSION['HT']) ) {?>
      
      Votre compte DTN est li&eacute; &agrave; votre compte Hattrick : <?php echo($_SESSION['nomUser']." [".$_SESSION['idUserHT']."]"); ?>
      
    <?php } else {?>
      <br />
      Vous devez etre connect&eacute; &agrave; Hattrick.&nbsp;&nbsp;
      <br />
      
      <form name="formConnexionHT" method="get" action="">
        <input name="mode" type="hidden" value="redirectionHT">
        <input type="submit" value="AUTORISER ACC&Egrave;S CHPP" class="bouton" /> <br /><br />
      </form>
    
    <?php }?>
    <br /> 
  </div>

<?php }




/******************************************************************************/
/*      GESTION AFFICHAGE LISTE JOUEURS                                       */
/******************************************************************************/
if (isset($resuScan) && $resuScan!=false && !empty($resuScan)) { 
  $scan_code=count($resuScan);

  if ($scan_code==-1){
  	$msg = REPONSE_ERREUR_CXION;
  }elseif ($scan_code==-2){
    $msg = REPONSE_PASDEFRANCAIS;
  }elseif ($scan_code==0){
    $msg = REPONSE_PASDEJOUEUR;
  } else {?>

    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
    <td rowspan="3" width="20">&nbsp;</td>
  	<td class="style40">
  
    <p align="justify"><br>
    
    <font size="2" face="Century Gothic">
  	<?=RETENU_PRESENTATION?>
  	
    <table border=0 width=95%>	
    	<?php

    	for($i=0;$i<$scan_code;$i++) {
    	  if (isset ($resuScan[$i]["idJoueur"])) {
      	  $joueurDTN=getJoueur($resuScan[$i]["idJoueur"]); ?>
      		
          <tr>
          <td width=5% nowrap valign="top">
            <font size="2" face="Century Gothic">&nbsp;<b>-&nbsp;<?=$joueurDTN["prenomJoueur"]?> <?=$joueurDTN["nomJoueur"]?></b></font>
          </td>
          <td align="left" width=95% nowrap valign="top">
            <font size="2" face="Century Gothic">
            <?php
          	if ( $joueurDTN["dtnSuiviJoueur_fk"]!=0){
          				echo " - <b><i>".$joueurDTN["loginAdminSuiveur"]."</i></b>".SUIVIMSG."<br>";
          				
          			}
          	if ($resuScan[$i]["minima"]<=0){
          		if ($_SESSION['lang']=="en"){
          		?>[This player does not satisfy our requirements.]<?php
          		}else if ($_SESSION['lang']=="de"){
          		?>[Dieser Spieler entspricht nicht unseren Anforderungen.]<?php
          		}else{
          		?>[Joueur en dessous de nos minimas.]<?php
          		}
          	}?>
            </font>
          </td>
          </tr>
        <?php
        } else {
          echo '<br />Joueur inexistant en base et en dessous des minimas - ID = '.$resuScan[$i]["idHattrickJoueur"].
          ' <a href="https://'.$_SERVER["HTTP_HOST"].'/dtn/interface/joueurs/addPlayer.php?listID='.$resuScan[$i]["idHattrickJoueur"].'">Cliquez ici pour forcer l\'ajout du joueur dans la base</a>';
        }
        
      }// Fin boucle joueurs ?>


    <?php } ?>
  
    <p align="justify"><br />
    <font size="2" face="Century Gothic">
    <?php if (isset($msg)){?><?=$msg?>
    <?php } ?>
    </font>
  
  
    </td>
    </tr>
	</table>
<?php }?>