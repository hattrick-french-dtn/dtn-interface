<?php
require_once("../includes/head.inc.php");




if(!$sesUser["idAdmin"])
{
	header("location: ../index.php?ErrorMsg=Session Expiree");
	exit();
}


if(!isset($lang)) $lang = "FR";




if($lang == "fr") $lang = "FR";
if($lang == "en") $lang = "EN";


require("../includes/serviceEntrainement.php");
require("../includes/serviceJoueur.php");
require("../includes/serviceListesDiverses.php");
require("../includes/langue.inc.php");

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

$lstPos = listPositionResume();



?><html>
<head>
<title>Récupération </title>
<script src="../../../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../../../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">


<style type="text/css">



<!--
.Style1 {color: #FF0000}
-->
</style>

<script type='text/javascript'>
function verifta()
  {
  if(document.forms.form1.listID.value!="") || (document.forms.form2.listID.value!="") || (document.forms.form3.listID.value!="")
    {
    return true;
    }
  else
    {
    alert("Veuillez fournir un ou plusieurs ID valides SVP");
    return false;
    }
  }
 
</script>

</head>

<?php

//$origine=="selection" si vient de "Base de données:Recherche:Export Fiche Résumé des joueurs de la page"
//$origine=="maliste" si vient de l'option "Ma Liste:Export Fiche Résumé"
//$origine=="menu" si vient de l'option "Base de données:Fiches résumé" du menu
global $infJ;
if(!isset($origine)) $origine ="menu";
$listID=$infJ["commentaire"]; //initialise la valeur par défaut de textarea

if ($origine=="selection")
{
	$lstJ=$_SESSION['ListeFicheResume'];
	$i=0;
	$listID=null;
	while ($i<count($lstJ))
    {
		$listID=$listID.$lstJ[$i]["idHattrickJoueur"].";"; // concatène les Id avec séparateurs ;
		$i+=1;
    }
	$listID=substr($listID,0,-1); // enlève le dernier ;
}

if ($origine=="maliste")
{
	$listID=$_SESSION['ListeFicheResume'];
}
   
?>


<form name="form1" method="post" action="ficheresumechoix.php" onsubmit="return verifta()">
  <br>Entrez la liste des IDs (s&eacute;par&eacute;s par un ";") :<br>
    <textarea name="listID" id="listID" style="font-size:7pt;font-family:Arial" cols=220 rows=10><?php echo $listID?></textarea><br>

  <input type="submit" name="button" id="button" value="Fiche R&eacute;sum&eacute;">
  <input name="id" type="hidden" id="id" value="<?=$listID?>">
</form>

<form name="form1" method="post" action="fichediscordchoix.php" onsubmit="return verifta()">
  <br>Entrez la liste des IDs (s&eacute;par&eacute;s par un ";") :<br>
    <textarea name="listID" id="listID" style="font-size:7pt;font-family:Arial" cols=220 rows=10><?php echo $listID?></textarea><br>

  <input type="submit" name="button" id="button" value="Fiche Discord">
  <input name="id" type="hidden" id="id" value="<?=$listID?>">
</form>

<form name="form1" method="post" action="fichehattrickchoix.php" onsubmit="return verifta()">
  <br>Entrez la liste des IDs (s&eacute;par&eacute;s par un ";") :<br>
    <textarea name="listID" id="listID" style="font-size:7pt;font-family:Arial" cols=220 rows=10><?php echo $listID?></textarea><br>

  <input type="submit" name="button" id="button" value="Fiche Hattrick">
  <input name="id" type="hidden" id="id" value="<?=$listID?>">
</form>

    </body>
</html>

