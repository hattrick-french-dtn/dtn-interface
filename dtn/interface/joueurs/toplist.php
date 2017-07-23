<?php 
require_once("../includes/head.inc.php");
require("../includes/serviceListesDiverses.php");
require("../includes/serviceJoueur.php");


if(!$sesUser["idAdmin"])
{
	header("location: ../index.php?ErrorMsg=Session Expiree");
	exit();
}

?>
<link href="../css/ht.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<?php
switch($sesUser["idNiveauAcces"]){
	case "1":
		require("../menu/menuAdmin.php");
		require("../menu/menuSuperviseurConsulter.php");
		break;
		
	case "2":
		require("../menu/menuSuperviseur.php");
		require("../menu/menuSuperviseurConsulter.php");
		break;

	case "3":
		require("../menu/menuDTN.php");
		require("../menu/menuDTNConsulter.php");
		break;
		
	case "4":
		require("../menu/menuCoach.php");
		require("../menu/menuCoachConsulter.php");
		break;
		
	default;
		break;
}


if(!isset($nb) || $nb == "") $nb = "15";
if(!isset($age) || $age == "") $age = "17";
if (!isset($masque)) $masque = 0;

if(!isset($affPosition) || $affPosition == "") {
	if (($sesUser["idPosition_fk"] == "") or ($sesUser["idPosition_fk"] == "0")){
		$affPosition ="1";	
	}else{
		$affPosition = $sesUser["idPosition_fk"] ;
	}
}

switch($affPosition){
case "1";
	$titre = "gardiens";
	$colonne = "scoreGardien";
	break;

case "2";
	$titre = "defenseurs";
	$colonne = "scoreDefense";
	break;

case "4";
	$titre = "milieux";
	$colonne = "scoreMilieu";
	break;

case "3";
	$titre = "ailiers";
	$colonne = "scoreAilierOff";
	break;

case "5";
	$titre = "attaquants";
	$colonne = "scoreAttaquant";
	break;

default;
	break;
}
if(!isset($ordre) || $ordre == "") $ordre = $colonne;
if(!isset($sens) || $sens == "") $sens = "DESC";


switch($ordre){
case "valeurEnCours":
$tri = "valeur";
break;


case $colonne;
$tri = $colonne;
break;
}

$lstPos = listAllPosition();

?><title>Top liste</title>
<style type="text/css">
<!--
.Style2 {color: #FFFFFF}
-->
</style><script language="javascript" src="../includes/javascript/navigation.js">


</script>
<form name="form1" method="post" action="toplist.php" onSubmit="return verifNb()">  
  <table width="600"  border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="216">Nombre d'enregistrement</td>
      <td width="48"><div align="center">
        <input name="nb" type="text" size="3" value="<?=$nb?>">
      </div></td>
      <td width="51"><div align="center">Age</div></td>
      <td width="52"><input name="age" type="text" id="age" value="<?=$age?>" size="2"></td>
      <td width="104">Position</td>
      <td width="153"><select name="affPosition">
	  <?php
	  foreach($lstPos as $l){
		if($l["idPosition"] == $affPosition) $etat = "selected"; else $etat ="";
		echo "<option value = '".$l["idPosition"]."' ".$etat.">".$l["intitulePosition"]."</option>";
	  }
	  ?>
      </select></td>
      <td width="154"><input type="submit" name="Submit" value="Afficher"></td>
    </tr>
  </table>
</form>


<table width="600"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><div align="center" class="titre"><strong>Liste des 
    <?=$nb?>&nbsp;meilleurs&nbsp;<?=$titre?>&nbsp;de&nbsp;<?=$age?> ans tri&eacute; par <?=$tri?></strong></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="100%"  border="0" cellpadding="0" cellspacing="0">
      <tr bgcolor="#000000">
        <td colspan="1"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div>          </td>
        </tr>
      <tr>
        <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td colspan="4" bgcolor="#000000"><div align="center" class="Style2">Identit&eacute;</div></td>
        <td width="50" bgcolor="#000000">&nbsp;</td>
        <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td width="92" bgcolor="#000000"  onClick="chgTri('valeurEnCours','<?=$sens?>','<?=$masque?>','<?=$affPosition?>','<?=$age?>','<?=$nb?>')"><div align="center" class="Style2">TSI</div></td>
        <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td width="65" bgcolor="#000000"  onClick="chgTri('<?=$colonne?>','<?=$sens?>','<?=$masque?>','<?=$affPosition?>','<?=$age?>','<?=$nb?>')"><div align="center" class="Style2">Note</div></td>
        <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
      </tr>
	    <tr bgcolor="#000000">
        <td colspan="1"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div>          </td>
        </tr>
		<?php
		
		$sql = "SELECT ht_joueurs.*, hte.valeurEnCours, ht_clubs.* FROM
		 (ht_joueurs ,ht_clubs)
				LEFT JOIN ht_entrainement hte ON hte.idJoueur_fk = ht_joueurs.idJoueur
		
		WHERE
		 teamid = idClubHT AND 
		 joueurActif = 1 AND
		 affJoueur = 1 AND
		 archiveJoueur = 0 AND
		 floor((datediff(CURRENT_DATE,'1970-01-01')-(574729200/86400)-datenaiss)/112) = '".$age."' AND
		 ht_posteAssigne  = '".$affPosition."'
		 ";
		$sql .= "ORDER BY ".$ordre." ".$sens."";
		$sql .= " LIMIT 0,$nb";
		 
		$huit = 60 * 60 * 24 * 8; //time_0
		$quinze = 60 * 60 * 24 * 15; //time_1
		$trente = 60 * 60 * 24 * 30; //time_2
		$twomonths = 60 * 60 * 24 * 60; //time_3
		$fourmonths = 60 * 60 * 24 * 120; //time_4
			  
		// Date du jour
		$mkday = mktime(0,0,0,date('m'), date('d'),date('Y'));

		foreach($conn->query($sql) as $lst){
		 
			$verifInternational = verifSelection($lst["idJoueur"]);
		 
		 	 $date = explode("-",$lst["dateDerniereModifJoueur"]);
			 
			 $mkJoueur =  mktime(0,0,0,$date[1],$date[2],$date[0]); 
			 $datesaisie = explode("-",$lst["dateSaisieJoueur"]);
		 	 $mkSaisieJoueur= mktime(0,0,0,$datesaisie[1],$datesaisie[2],$datesaisie[0]);
			 if ($mkSaisieJoueur>$mkJoueur){
			 	$datemaj=$mkSaisieJoueur;
			 }else{
			 	$datemaj=$mkJoueur;
			 }
			
			 $img_nb=0;
			 if ($datemaj >$mkday -$huit){
			 	$img_nb=0;
			 	$strtiming="moins de 8 jours";	
			 }else if ($datemaj >$mkday -$quinze){
			 	$img_nb=1;
			 	$strtiming="moins de 15 jours";
			 }else if ($datemaj >$mkday -$trente){
			 	$img_nb=2;
			 	$strtiming="moins de 30 jours";
			 	
			 }else if ($datemaj >$mkday -$twomonths){
			 	$img_nb=3;
			 	$strtiming="moins de 2 mois";
			 	
			 }else if ($datemaj >$mkday -$fourmonths){
			 	$img_nb=4;
			 	$strtiming="moins de 4 mois";
			 
			 }else{
			 		$img_nb=5;
			 	$strtiming="plus que 4 mois";
			 }
			 
			 // Date de la dernier modif de ce joueur
			 $d1 =  $mkday - $quinze;
			 $d2 =  $mkday - $trente;
			 $zealt=" Date dtn : ".$lst["dateDerniereModifJoueur"].
					"<br> Date proprio : ".$lst["dateSaisieJoueur"].
					"<br> [ Mis &agrave; jour il y a  ".round(($mkday - $datemaj)/(60*60*24) )." jours ]";
			 
		
		?>
      <tr>
        <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td  nowrap>&nbsp;
    <img src="../images/time_<?=$img_nb?>.gif" onmouseover="return escape('<?=$zealt?>')" >&nbsp;        
    <a href ="<?=$url?>/joueurs/fiche.php?id=<?=$lst["idJoueur"]?>" class="bred1">
	    <?=strtolower($lst["prenomJoueur"])?> <?=strtolower($lst["nomJoueur"])?>
		<?php if (isset($infJ["surnomJoueur"])) echo " (".$infJ["surnomJoueur"].")"; ?>
    </a>
        
        
        
        </td>
        <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td width="194">&nbsp;<?=$lst["nomClub"]?></td>
		        <td width="1" bgcolor="#FFFFFF"><img src="../images/spacer.gif" width="1" height="1"></td>


        <td width="50" valign="middle"><center><?php if($verifInternational != ""){?>
          <img src="../images/fr.gif" alt="<?=$verifInternational?>">
          <?php } ?></center></td>
        <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td width="92"><div align="center"><?=$lst["valeurEnCours"]?></div></td>
        <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td width="65"><div align="center"><?=$lst[$colonne]?></div></td>
        <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
      </tr>	 
	     <tr bgcolor="#000000">
        <td colspan="12"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div>          </td>
        </tr><?php } ?>

    </table></td>
  </tr>
</table>


<br>
 <table width="450"  border="0" align="center" cellspacing=0 >
    <tr>
      <td colspan=5 width="450" ><center>L&eacute;gende : </center></td>
      
    </tr>
    <tr>
      <td><img src="../images/time_0.gif"></td>
      <td>Joueur mis &agrave; jour r&eacute;cemment </td>
      <td width="1" bgcolor="#000000"></td>
      <td>&nbsp;<img src="../images/time_3.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 30 jours </td>
    </tr>
    <tr>
      <td><img src="../images/time_1.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 8 jours </td>
      <td width="1" bgcolor="#000000"></td>
      <td>&nbsp;<img src="../images/time_4.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 60 jours </td>
    </tr>
    <tr>
      <td><img src="../images/time_2.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 15 jours </td>
      <td width="1" bgcolor="#000000"></td>
      <td>&nbsp;<img src="../images/time_5.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 120 jours </td>
    </tr>
  </table>


<p>&nbsp;</p>
<script language="JavaScript" type="text/javascript" src="../includes/javascript/tooltips.js"></script>
</body>
</html>
