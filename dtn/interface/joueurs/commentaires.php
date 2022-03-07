<?php

require_once("../includes/head.inc.php");
require("../includes/serviceEntrainement.php");
require("../includes/serviceJoueur.php");
require("../includes/serviceEquipes.php");
require("../includes/langue.inc.php");



require("../includes/serviceListesDiverses.php");
require("../includes/serviceMatchs.php");
require("../CHPP/config.php");

if(!$sesUser["idAdmin"])
	{
	header("location: ../index.php?ErrorMsg=Session Expiree");
	}



if(!isset($lang)) $lang = "FR";
if(!isset($url)) $url = "rapportDetaille.php";

if (isset($htid))
{
	$infJ = getJoueurHt($htid);
	$id = $infJ["idJoueur"];
} else {
	$infJ = getJoueur($id);
	$id = $infJ["idJoueur"];
}

$showLink=true;
//switch($sesUser["idNiveauAcces"]){
//		case "2":
//			if ($sesUser["idPosition_fk"]!= $infJ["ht_posteAssigne"]
//				&& $infJ["ht_posteAssigne"]!=0) {
//				$showLink=false;
//			}
//		break;
//		case "3":
//			if ($sesUser["idAdmin"]!= $infJ["dtnSuiviJoueur_fk"]) {
//				$showLink=false;
//			 }
//		break;
		
//		default;
//		break;
//}



?><html>
<head>
<title>Fiche joueur</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<?php
switch($sesUser["idNiveauAcces"]){
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
		break;
		
		default;
		break;

}
$idHT=$infJ['idHattrickJoueur'];
$idClubHT=$infJ['teamid'];
require("../menu/menuJoueur_autres_onglets.php");
?>
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<table width="85%"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr> 
    <td height="15" bgcolor="#000000">
<div align="center"><font color="#FFFFFF"><strong>Etoiles</strong></font></div></td>
  </tr>
  <tr>
    <td valign="top">
<table width="85%" border="0" cellpadding="0" cellspacing="0">

        <tr> 
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr> 
          <td width="50%" align="left">&nbsp; <font color="#000099"><b><?=$infJ["idHattrickJoueur"]?>&nbsp;-&nbsp;<?=$infJ["nomJoueur"]?> <?=$infJ["prenomJoueur"]?>&nbsp;-&nbsp;<?=$infJ["ageJoueur"]?>&nbsp;ans&nbsp;-&nbsp;<?=$infJ["intitulePosition"]?></b></font></td>
          <td width="20%" align="left"colspan="2"><b>Club Actuel : </b><a href="<?=$url?>/clubs/fiche_club.php?idClubHT=<?=$infJ["teamid"]?>"><?=$infJ["nomClub"]?></a>
          </td>
        </tr>
      </table>
      <br>      <br>
        <form name="form1" method="post" action="../form.php">
          Votre Commentaire :
          <input name="mode" type="hidden" id="mode" value="updateComment">
          <input name="idJoueur_fk" type="hidden" id="idJoueur_fk" value="<?=$id?>">
          <textarea name="joueurComment" id="joueurComment" style="font-size:8pt;font-family:Arial" cols=220 rows=20><?php echo $infJ["commentaire"]?></textarea>
        <?php 
		if ($showLink==true) { ?>
		<font size="-1"><input type="submit" name="Submit" value="Enregistrer"></font>
	<?php 	} ?>	
        </form>


     <p align="center"><a href="javascript:history.go(-1);">Retour</a> </p>
</body>
</html><?php  deconnect(); ?>

