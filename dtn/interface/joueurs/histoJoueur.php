<?php
require("../includes/head.inc.php");
require("../includes/serviceJoueur.php");

if(!$sesUser["idAdmin"])
{
	header("location: index.php?ErrorMsg=Session Expiree");
}

if(!isset($lang)) $lang = "FR";
global $from;

require("../includes/langue.inc.php");

$sql = "select * from $tbl_config where nomConfig = \"rapport\"";
$req = $conn->query($sql);
$lstRapport = $req->fetch();
$valeurConfig = $lstRapport["valeurConfig"];

if (isset($htid))
{
	$infJ = getJoueurHt($htid);
	$idJoueur = $infJ["idJoueur"];
}
else
	$infJ = getJoueur($idJoueur);

switch($sesUser["idNiveauAcces"]){
		
	/*	case "2":
			if ($sesUser["idPosition_fk"]!= $infJ["ht_posteAssigne"]
				&& $infJ["ht_posteAssigne"]!=0 
			){
				print("<html><body><center>Ce joueur est associ&eacute; &agrave; un autre secteur de jeu.</center></body></html>");
				return;
			}
		break; */


		case "3":
			if ($sesUser["idPosition_fk"]!= $infJ["ht_posteAssigne"]
				  && $infJ["ht_posteAssigne"]!=0 
          && $sesUser["idPosition_fk"]!=0)
			{
				print("<html><body><center>Ce joueur est associ&eacute; &agrave; un autre DTN.</center></body></html>");
				return;
			 }
		break;
		
		default;
		break;

}

$sql = $conn->query("select * from $tbl_histomodif where idJoueur_fk = $idJoueur ");
$numMaxHisto = $sql->rowCount();


function ht_stripos($string,$word)
{
   $retval = false;
   for($i=0;$i<=strlen($string);$i++)
   {
       if (strtolower(substr($string,$i,strlen($word))) == strtolower($word))
       {
           $retval = true;
       }
   }
   return $retval;
}

// Mise en evidence de certaines parties de l'historique :
function formatHisto($text){

if ($text!=""){
	$a=ht_stripos($text,"transfert : teamid");
	
	if ( $a == true ){
		return "<b><font color=\"#ff0f2f\" >".$text."</font></b>";
	}
}
return	$text;
}

if(!isset($nbParPage)) $nbParPage = 25;
if(!isset($numEnr)) $numEnr = 0;

$next = $numEnr+$nbParPage;
$prev = $numEnr-$nbParPage;
?><html>
<head>
<title>Fiche joueur </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
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
$id=$idJoueur;
$idHT=$infJ['idHattrickJoueur'];
$idClubHT=$infJ['teamid'];

require("../menu/menuJoueur.php");


?>
<table width="85%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr> 
    <td height="15" bgcolor="#000000">
<div align="center"><font color="#FFFFFF"><strong>Histo modifs</strong></font></div></td>
  </tr>
  <tr>
    <td valign="top">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
             <tr> 
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr> 
          <td width="50%" align="left">&nbsp; <font color="#000099"><b><?=$infJ["idHattrickJoueur"]?>&nbsp;-&nbsp;<?=$infJ["nomJoueur"]?> <?=$infJ["prenomJoueur"]?>&nbsp;-&nbsp;<?=$infJ["ageJoueur"]?>&nbsp;ans&nbsp;-&nbsp;<?=$infJ["intitulePosition"]?></b></font></td>
          <td width="20%" align="left"><b>Club Actuel : </b><?=$infJ["nomClub"]?></td>
          <td width="30%" align="left">&nbsp;</td>
        </tr>
      </table>
      <table width="98%" border="0" align="center" cellpadding="00" cellspacing="0">
        <tr> 
          <td width="1" bgcolor="#000000"><font color="#000000"><img src="../images/spacer.gif" width="1" height="1"></font></td>
          <td  bgcolor="#000000"></td>
          <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        </tr>
        <tr> 
          <td bgcolor="#000000"width="1"><img src="../images/spacer.gif" width="1" height="1"></td>
          <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr> 
                <td width="86"> <div align="center">date</div></td>
                <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width="91"><div align="center">heure</div></td>
                <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width="918"><div align="center">libell&eacute;</div></td>
                <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width="124"><div align="center">auteur</div></td>
              </tr>
              <tr> 
                <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
              </tr>
           
		   <?php
		   $sql = "select * from $tbl_histomodif LEFT JOIN ht_admin ON idAdmin = idAdmin_fk where idJoueur_fk = $idJoueur order by dateHisto desc, heureHisto desc ";
			$sql .= " limit $numEnr, $nbParPage";
			
			foreach($conn->query($sql) as $l){
		   
		   
		   ?>
		      <tr> 
                <td width="86" height="20"> 
                  <div align="center"><?=dateToHTML($l["dateHisto"])?></div></td>
                <td width="1" height="20" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width="91" height="20"> 
                  <div align="center"><?=$l["heureHisto"]?></div></td>
                <td width="1" height="20" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width="918" height="20"> <div align="left">&nbsp;<?=formatHisto($l["intituleHisto"])?></div></td>
                <td width="1" height="20" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width="124" height="20"> 
                  <div align="center"><?=$l["loginAdmin"]?></div></td>
              </tr>
 <?php } ?>
            </table>
			
			
			
		  </td>
          <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        </tr>
        <tr> 
          <td bgcolor="#000000" width="1"><font color="#000000"><img src="../images/spacer.gif" width="1" height="1"></font></td>
          <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
          <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        </tr>
      </table>
      <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="26%" height="18">
		  <?php
		  
if($numEnr >= $nbParPage){
echo "<a href = '?url=$url&from=$from&idJoueur=$idJoueur&numEnr=$prev'>Pr&eacute;c&eacute;dent</A>";
}
		  ?>

		  
		  </td>
          <td width="36%">
</td>
          <td width="38%"><div align="right"><?php
if($numEnr +$nbParPage < $numMaxHisto){
echo "<a href = '?url=$url&from=$from&idJoueur=$idJoueur&numEnr=$next'>Suivant</A>";
}
?> </div></td>
        </tr>
      </table>
      <br>
    </td>
  </tr>
</table>            
	<p align="center"><a href="javascript:history.go(-1);">Retour</a> <br>
</body>
</html>
<?php  deconnect(); ?>
