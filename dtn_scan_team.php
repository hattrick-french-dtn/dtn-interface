<?php
require("dtn/interface/includes/head.inc.php");
require("dtn/interface/includes/serviceMatchs.php");
require("dtn/interface/includes/serviceJoueur.php");
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
$teams = array();
// Récupération de la liste des joueurs
$list_joueur_HT = getDataMesJoueursFromHT_usingPHT($_SESSION['HT']->getTeam()->getTeamId());
$team1 = ('id' => $_SESSION['HT']->getTeam()->getTeamId(), 'name' => $_SESSION['HT']->getTeam()->getTeamName());
array_push($teams, $team1);
// Si c'est la première visite avec ce browser
if ((isset($_SESSION['newVisit']) && $_SESSION['newVisit']==1))  
{ // On met à jour les informations clubs et les informations joueurs 
  if ($list_joueur_HT==false) 
  {
    $scan_code=-1;
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
        $resuScan=scanListeJoueurs($listeID,$_SESSION['nomUser'],'P');
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
  $resuScan=scanListeJoueurs($listeID,$_SESSION['nomUser'],'P',false,false);
}

//echo('titi');


/******************************************************************************/
/*      GESTION AFFICHAGE LISTE JOUEURS                                       */
/******************************************************************************/
echo"<font size=\"4\" color=\"red\" face=\"Century Gothic\"><U>$team1['name']</U></font>";

if (isset($resuScan) && $resuScan!=false) 
{
  $scan_code=count($resuScan);
}

if ($scan_code==-1)
{
	$msg = REPONSE_ERREUR_CXION;
}
else if ($scan_code==-2)
{
  $msg = REPONSE_PASDEFRANCAIS;
}
else if ($scan_code==0)
{
  $msg = REPONSE_PASDEJOUEUR;
} 
else 
{
  ?>

   <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
   <tr>
   <td rowspan="3" width="20">&nbsp;</td>
   <td class="style40">

   <p align="justify"><br />

   <font size="2" face="Century Gothic">
   <?=RETENU_PRESENTATION?>

   <table border=0 width=95%>	
   <?php
   $printedPlayers=""; 

   for($i=0;$i<$scan_code;$i++) 
   {
     if (isset ($resuScan[$i]["idJoueur"])) 
     {
       $joueurDTN=getJoueur($resuScan[$i]["idJoueur"]);

       $printedPlayers.="{".$joueurDTN["nomJoueur"]."}";
       ?>
       <tr>
       <td width=5% nowrap valign="top"><font size="2" face="Century Gothic">&nbsp;<b>-&nbsp;<?=$joueurDTN["nomJoueur"]?></b></font></td>
       <td align="left" width=95% nowrap valign="top"><font size="2" face="Century Gothic">
       <?php
        if ( $joueurDTN["dtnSuiviJoueur_fk"]!=0){
                 echo " - <b><i>".$joueurDTN["loginAdminSuiveur"]."</i></b>".SUIVIMSG."<br />";
           }
        if ($resuScan[$i]["poste"]<=0){
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
?>



<?php
/******************************************************************************/
/******************************************************************************/
/*      GESTION AFFICHAGE DU CONTENU DE LA PAGE  2E EQUIPE                    */
/******************************************************************************/
/******************************************************************************/
// Initialisation variables
$scan_code=0;
unset($listeID);
unset($list_joueur_HT);
unset($resuScan);
$msg = "";
$userId = $_SESSION['HT']->getClub()->getUserId();
$teamNb = $_SESSION['HT']->getNumberOfTeams($userId);
for ($tsidx=$teamNb-1; $tsidx >= 0; $tsidx--) {
	$team2 = $_SESSION['HT']->getSecondaryTeam($userId, $tsidx);
	if ($team2 != null)
	{
	  // Récupération de la liste des joueurs
	  $list_joueur_HT = getDataMesJoueursFromHT_usingPHT($team2->getTeamId());
	  $teamid2 = ('id' => $team2->getTeamId(), 'name' => $team2->getTeamName());
	  array_push($teams, $teamid2);
	  
	  // Si c'est la première visite avec ce browser
	  if ((isset($_SESSION['newVisit']) && $_SESSION['newVisit']==1))  
	  { // On met à jour les informations clubs et les informations joueurs 
		if ($list_joueur_HT==false) 
		{
		  $scan_code=-2;
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
			  $resuScan=scanListeJoueurs($listeID,$_SESSION['nomUser'],'P');
		
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
		$resuScan=scanListeJoueurs($listeID,$_SESSION['nomUser'],'P',false,false);
	  }
	  
	  /******************************************************************************/
	  /*      GESTION AFFICHAGE LISTE JOUEURS                                       */
	  /******************************************************************************/
	  echo"<font size=\"4\" color=\"red\" face=\"Century Gothic\"><U>$teamid2['name']</U></font>";
	  
	  if (isset($resuScan) && $resuScan!=false) 
	  {
		$scan_code=count($resuScan);
	  }
	  
	  if ($scan_code==-1)
	  {
		$msg = REPONSE_ERREUR_CXION;
	  }
	  else if ($scan_code==-2)
	  {
		$msg = REPONSE_PASDEFRANCAIS;
	  }
	  else if ($scan_code==0)
	  {
		$msg = REPONSE_PASDEJOUEUR;
	  } 
	  else 
	  {
		?>
	  
		 <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		 <tr>
		 <td rowspan="3" width="20">&nbsp;</td>
		 <td class="style40">
	  
		 <p align="justify"><br />
	  
		 <font size="2" face="Century Gothic">
		 <?=RETENU_PRESENTATION?>
	  
		 <table border=0 width=95%>	
		 <?php
		 $printedPlayers=""; 
	  
		 for($i=0;$i<$scan_code;$i++) 
		 {
		   if (isset ($resuScan[$i]["idJoueur"])) 
		   {
			 $joueurDTN=getJoueur($resuScan[$i]["idJoueur"]);
	  
			 $printedPlayers.="{".$joueurDTN["nomJoueur"]."}";
			 ?>
			 <tr>
			 <td width=5% nowrap valign="top"><font size="2" face="Century Gothic">&nbsp;<b>-&nbsp;<?=$joueurDTN["nomJoueur"]?></b></font></td>
			 <td align="left" width=95% nowrap valign="top"><font size="2" face="Century Gothic">
			 <?php
			  if ( $joueurDTN["dtnSuiviJoueur_fk"]!=0){
					   echo " - <b><i>".$joueurDTN["loginAdminSuiveur"]."</i></b>".SUIVIMSG."<br />";
				 }
			  if ($resuScan[$i]["poste"]<=0){
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
	}
}

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
        $sql="update ht_clubs_histo set Commentaire = '$commentaire' where id_Clubs_Histo = '$id_Clubs_Histo'";
        $result= $conn->exec($sql);
    }
  	$commentSaved=true;
} 

  	
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
	foreach($teamIDs as $teamId)
	{
		
	}
      if ($teamID2 == "")
      {
?>
         <input name="idClubHT" type="hidden" value="<?=$teamID1?>">
<?php
      }
      else
      {
       if ($idClubComment != "")
       {
        if ($idClubComment == $teamID1)
        {
?>
         <input name="idClubHT" type="radio" value="<?=$teamID1?>" checked><?php echo"$nomeq";?>
         <input name="idClubHT" type="radio" value="<?=$teamID2?>"><?php echo"$nomeq2";?>
<?php
        }
        else
        {
?>
         <input name="idClubHT" type="radio" value="<?=$teamID1?>"><?php echo"$nomeq";?>
         <input name="idClubHT" type="radio" value="<?=$teamID2?>" checked><?php echo"$nomeq2";?>
<?php
        }
       }
       else
       {
?>
         <input name="idClubHT" type="radio" value="<?=$teamID1?>" checked><?php echo"$nomeq";?>
         <input name="idClubHT" type="radio" value="<?=$teamID2?>"><?php echo"$nomeq2";?>
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
