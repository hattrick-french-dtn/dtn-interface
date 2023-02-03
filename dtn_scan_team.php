<?php
require_once("dtn/interface/includes/head.inc.php");
require_once("dtn/interface/includes/serviceMatchs.php");
require_once("dtn/interface/includes/serviceJoueur.php");
//require("dtn/interface/includes/serviceEquipes.php");
require_once "dtn/interface/_config/CstGlobals.php"; 


ini_set('max_execution_time',360); // 360 secondes = 6 minutes -> à la main on se met un timeout de 6 minutes
error_reporting (E_ALL);



/******************************************************************************/
/******************************************************************************/
/*      DEFINITION FONCTIONS                                                  */
/******************************************************************************/
/******************************************************************************/

$_SESSION['commentaire'] = "";
$_SESSION['idClubComment'] = "";
$_SESSION['ajoutCommentaire'] = "";

// Si le proprio a ajouté un commentaire non null
if ((isset($_POST['ht_comment']) && isset($_POST['idClubHT'])))  
{
  $_SESSION['commentaire'] = $_POST['ht_comment'];
  $_SESSION['idClubComment'] = $_POST['idClubHT'];
}
if (isset($_POST['action']) && $_POST['action']=="ajoutCommentaire") 
{
  $_SESSION['ajoutCommentaire'] = "1";
}

/******************************************************************************/
/******************************************************************************/
/*      GESTION AFFICHAGE DU CONTENU DE LA PAGE                               */
/******************************************************************************/
/******************************************************************************/
// Initialisation variables
$scan_code=0;
$userId = $_SESSION['HT']->getClub()->getUserId();
$team = $_SESSION['HT']->getInternationalTeam($userId);
if ($team != NULL) {
	// Récupération de la liste des joueurs
	$list_joueur_HT = getDataMesJoueursFromHT_usingPHT($team->getTeamId());
	if ((isset($_SESSION['newVisit']) && $_SESSION['newVisit']==1))  
	{ // On met à jour les informations clubs et les informations joueurs 
		if ($list_joueur_HT==false) 
		{
			$scan_code=-1;
			$resuScan = null;
		}
		else
		{
			// Insertion ou mise à jour 
			if (count($list_joueur_HT)>0)
			{
				foreach($list_joueur_HT as $joueur)
				{
					$listeID[]=$joueur["idHattrickJoueur"];
				}
				$listeID=array_unique($listeID); // Suppression des doublons
				// Insertion ou mise à jour des joueurs
				$resuScan=scanListeJoueurs($listeID, $_SESSION['nomUser'], 'P');
				$scan_code=count($resuScan);
			} 
			else
			{
				$scan_code=-2; // pas de français
			}
		}
	} 
	else 
	{
		// Scan des joueurs sans mise à jour
		$resuScan=scanListeJoueurs($listeID, $_SESSION['nomUser'], 'P', false, false);
		$scan_code=count($resuScan);
	}
	$teams[2] = array('id' => $team->getTeamId(), 'name' => $team->getTeamName(), 'scan' => $resuScan, 'code' => $scan_code);
}

$listeID = null;
$team = $_SESSION['HT']->getSecondaryTeam($userId);
if ($team != NULL) {
	// Récupération de la liste des joueurs
	$list_joueur_HT = getDataMesJoueursFromHT_usingPHT($team->getTeamId());
	if ((isset($_SESSION['newVisit']) && $_SESSION['newVisit']==1))  
	{ // On met à jour les informations clubs et les informations joueurs 
		if ($list_joueur_HT==false) 
		{
			$scan_code=-1;
			$resuScan = null;
		}
		else
		{
			// Insertion ou mise à jour 
			if (count($list_joueur_HT)>0)
			{
				foreach($list_joueur_HT as $joueur)
				{
					$listeID[]=$joueur["idHattrickJoueur"];
				}
				$listeID=array_unique($listeID); // Suppression des doublons
				// Insertion ou mise à jour des joueurs
				$resuScan=scanListeJoueurs($listeID, $_SESSION['nomUser'], 'P');
				$scan_code=count($resuScan);
			} 
			else
			{
				$scan_code=-2; // pas de français
			}
		}
	} 
	else 
	{
		// Scan des joueurs sans mise à jour
		$resuScan=scanListeJoueurs($listeID, $_SESSION['nomUser'], 'P', false, false);
		$scan_code=count($resuScan);
	}
	$teams[1] = array('id' => $team->getTeamId(), 'name' => $team->getTeamName(), 'scan' => $resuScan, 'code' => $scan_code);
}
$listeID = null;
$team = $_SESSION['HT']->getQuaternyTeam($userId);
if ($team != NULL) {
	// Récupération de la liste des joueurs
	$list_joueur_HT = getDataMesJoueursFromHT_usingPHT($team->getTeamId());
	if ((isset($_SESSION['newVisit']) && $_SESSION['newVisit']==1))  
	{ // On met à jour les informations clubs et les informations joueurs 
		if ($list_joueur_HT==false) 
		{
			$scan_code=-1;
			$resuScan = null;
		}
		else
		{
			// Insertion ou mise à jour 
			if (count($list_joueur_HT)>0)
			{
				foreach($list_joueur_HT as $joueur)
				{
					$listeID[]=$joueur["idHattrickJoueur"];
				}
				$listeID=array_unique($listeID); // Suppression des doublons
				// Insertion ou mise à jour des joueurs
				$resuScan=scanListeJoueurs($listeID, $_SESSION['nomUser'], 'P');
				$scan_code=count($resuScan);
			} 
			else
			{
				$scan_code=-2; // pas de français
			}
		}
	} 
	else 
	{
		// Scan des joueurs sans mise à jour
		$resuScan=scanListeJoueurs($listeID, $_SESSION['nomUser'], 'P', false, false);
		$scan_code=count($resuScan);
	}
	$teams[3] = array('id' => $team->getTeamId(), 'name' => $team->getTeamName(), 'scan' => $resuScan, 'code' => $scan_code);
}

$listeID = null;
$team = $_SESSION['HT']->getPrimaryTeam($userId);
if ($team != NULL) {
	// Récupération de la liste des joueurs
	$list_joueur_HT = getDataMesJoueursFromHT_usingPHT($team->getTeamId());
	if ((isset($_SESSION['newVisit']) && $_SESSION['newVisit']==1))  
	{ // On met à jour les informations clubs et les informations joueurs 
		if ($list_joueur_HT==false) 
		{
			$scan_code=-1;
			$resuScan = null;
		}
		else
		{
			// Insertion ou mise à jour 
			if (count($list_joueur_HT)>0)
			{
				foreach($list_joueur_HT as $joueur)
				{
					$listeID[]=$joueur["idHattrickJoueur"];
				}
				$listeID=array_unique($listeID); // Suppression des doublons
				// Insertion ou mise à jour des joueurs
				$resuScan=scanListeJoueurs($listeID, $_SESSION['nomUser'], 'P');
				$scan_code=count($resuScan);
			} 
			else
			{
				$scan_code=-2; // pas de français
			}
		}
	} 
	else 
	{
		// Scan des joueurs sans mise à jour
		$resuScan=scanListeJoueurs($listeID, $_SESSION['nomUser'], 'P', false, false);
		$scan_code=count($resuScan);
	}
	$teams[0] = array('id' => $team->getTeamId(), 'name' => $team->getTeamName(), 'scan' => $resuScan, 'code' => $scan_code);
}

/******************************************************************************/
/*      GESTION AFFICHAGE LISTE JOUEURS                                       */
/******************************************************************************/

foreach($teams as $te) {
	if ($te['code'] == -1)
	{
		$msg = REPONSE_ERREUR_CXION;
	}
	else if ($te['code'] == -2)
	{
		$msg = REPONSE_PASDEFRANCAIS;
	}
	else if ($te['code'] == 0)
	{
		$msg = REPONSE_PASDEJOUEUR;
	} 
	else 
	{
		$resuScan = $te['scan'];
		$scan_code= $te['code'];
?>
	<font size='4' color='red' face='Century Gothic'><U><?=$te['name']?></U></font>
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td rowspan="3" width="20">&nbsp;</td>
		<td class="style40">

		<p align="justify"><br />

		<font size="2" face="Century Gothic">
		<?=RETENU_PRESENTATION?>

		<table border=0 width=95%>	
<?php
		for($i=0;$i<$scan_code;$i++) 
		{
			if (isset ($resuScan[$i]["idJoueur"])) 
			{
				$joueurDTN=getJoueur($resuScan[$i]["idJoueur"]);
?>
		<tr>
		<td width=5% nowrap valign="top"><font size="2" face="Century Gothic">&nbsp;<b>-&nbsp;<?=$joueurDTN["prenomJoueur"]?>&nbsp;<?=$joueurDTN["nomJoueur"]?></b></font></td>
		<td align="left" width=95% nowrap valign="top"><font size="2" face="Century Gothic">
<?php
				if ( $joueurDTN["dtnSuiviJoueur_fk"]!=0){
					echo " - <b><i>".$joueurDTN["loginAdminSuiveur"]."</i></b>".SUIVIMSG."<br />";
				}
				if ($resuScan[$i]["poste"]<=0){
					if ($_SESSION['lang']=="en"){
					?>[Scan OK.]<?php
					}else if ($_SESSION['lang']=="de"){
					?>[Scan OK.]<?php
					}else{
					?>[Le joueur a bien &eacute;t&eacute; scann&eacute;.]<?php
					}
				}
?>
		</font>
		</td>
		</tr>
<?php
			}
		} // Fin boucle joueurs
   ?> 
	</table>	
	</td>
	</tr>
	</table>	
<?php
	}   
	echo"<p align=\"justify\"><br />";
	echo"<font size=\"2\" face=\"Century Gothic\">";
	if (isset($msg))
	{
	?><?=$msg?><?php

	}

	echo"</font>";
} // end foreach

/******************************************************************************/
/*      GESTION FORMULAIRE COMMENTAIRE                                        */
/******************************************************************************/
$commentaire = $_SESSION['commentaire'];
$idClubComment = $_SESSION['idClubComment'];
$commentSaved=false;

if ($idClubComment != "")
{
    $sql="select max(id_Clubs_Histo) id_Clubs_Histo from ht_clubs_histo where idClubHT = '$idClubComment'";
    $result= $conn->query($sql);
    $ligne = $result->fetch(PDO::FETCH_ASSOC);
    extract($ligne);
    $nblig = $result->rowCount();
    if ($nblig > 0)
    {
        $sql="update ht_clubs_histo set Commentaire = '".addslashes($commentaire)."' where id_Clubs_Histo = '$id_Clubs_Histo'";
        $result= $conn->exec($sql);
    }
  	$commentSaved=true;
} 

$resuScan = $teams[0]['scan'];

// gestion erreur
if (isset($resuScan[0]["id_clubs_histo"])) 
{
	$_SESSION['id_clubs_histo']=$resuScan[0]["id_clubs_histo"];
}
elseif (isset($_POST['id_clubs_histo'])) 
{
	$_SESSION['id_clubs_histo']=$_POST['id_clubs_histo'];
}
/*   else
   {
      $_SESSION['id_clubs_histo']=$teamID1;
   }*/
/*   elseif (!isset($_SESSION['id_clubs_histo'])) 
   {
      echo("<font color='red'><b>Erreur : Joueurs mis &agrave; mais impossible de poster un commentaire</b></font>"); 
      exit;
   }*/
if (isset($_POST['ht_comment'])) {
    $_SESSION['ht_comment'] = $commentaire;
}
?>

   <table>	
   <br />
   <b><?=REMERCIEMENT_SUBMIT?></b><br /><br />
   <?=AJOUT_COMMENTAIRE?><br />
   <form name="form_comment" method="post" action="<?=$_SERVER['PHP_SELF']."?lang=".$_SESSION['lang']?>" onSubmit="return verifComment();">
   <?php
	if (count($teams) == 1)
	{
		$teamId = $teams[0]['id'];
?>
        <input name="idClubHT" type="hidden" value="<?=$teamId?>">
<?php
    }
	else {
		foreach($teams as $teamId) {
?>
        <input name="idClubHT" type="radio" value="<?=$teamId['id']?>" ><?=$teamId['name']?>
<?php
		}
    }
	
?>
         <br /><textarea name="ht_comment"  cols=60 rows=2>
<?php
         echo"$commentaire";
?></textarea>
         <input name="action" type="hidden" value="ajoutCommentaire">
         <input name="id_clubs_histo" type="hidden" value="<?php echo ($_SESSION['id_clubs_histo']);?>">
         <input name="printedPlayers" type="hidden" value="">
         <input type="submit" name="Submit2" value="OK"></td>	
   </form>
   <br />	
   </font>

<?php
if ($_SESSION['ajoutCommentaire'] == "1") 
{
  echo"<p align=\"justify\"><br />
  <font size=\"2\" face=\"Century Gothic\">";
  	
 	echo"<font color=\"green\"><b>";
  if ($commentSaved) 
  {
    echo(MERCI_COMMENT);
  }
  else
  {
    echo(PAS_DE_COMMENT);
  }
  echo"</b></font></font><br />";
}
?>

   <?=EVITER_QUESTIONS?>
   </p>		
   </td></tr>
   </table>
   <script>
   function verifComment() {
	   return true;
   }
   </script>