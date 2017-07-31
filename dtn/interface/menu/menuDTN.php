<?php 
// Variable paramétrage de la page
$nomFicPhpCourant = explode("?",$_SERVER['REQUEST_URI']);
$callbackUrl="http://".$_SERVER['HTTP_HOST'].$nomFicPhpCourant[0]."?mode=retour"; // Url de retour après authentification sur HT
include($_SERVER['DOCUMENT_ROOT'].'/gestion_session_HT.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/language/fr.php');
$_SESSION['acces']="INTERFACE"; // sert à avoir un affichage personnalisé pour les composants utilisés dans le portail et l'interface

if(!isset($_SESSION['sesUser']["idAdmin"]))
{
	header("location: http://".$_SERVER['SERVER_NAME']."/dtn/interface/index.php?ErrorMsg=Session Expire");
}
?>

<html>
<head>
<title>DTN [ht-fff]</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="<?=$url?>/css/ht2.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="<?=$url?>/css/style.css" />
</head>


<body>
<br><center>
<table cellpadding="0" cellspacing="0" width="90%" border="0">
<tr><td>
<table cellpadding="0" cellspacing="2" width="100%" border="0">
<tr align="left">
	<td width="1%" class="bred" nowrap nospan><a href="<?=$url?>/index2.php"><?=$_SESSION['sesUser']["IntituleNiveauAcces"]?></a>&nbsp;&gt;<span class="breadvar"> <?=$_SESSION['sesUser']["loginAdmin"]?></span></td>

	<td colspan=2 align="right" valign="bottom" nowrap width="1%">
	<A class="smliensorange" href="<?=$url?>/settings.php?affinfoPerso=1" class="btn" alt="configuration">Mon Profil</a>&nbsp;|&nbsp;
	<A class="smliensorange" href="<?=$url?>/national_team/team.php?selection=A" class="btn">Equipes Nationales</a>&nbsp;|&nbsp;
	<A class="smliensorange" href="<?=$url?>/joueurs/toplist.php" class="btn" alt="Chercher">Base de donn&eacute;es</a>&nbsp;|&nbsp;
	<a class="smliensorange" href="<?=$url?>/consulter/messagesMaListe.php" alt="Messages Proprios">Messages Proprios</a>&nbsp;|&nbsp;
	<A class="smliensorange" href="<?=$url?>/maliste/maliste.php" class="btn" alt="ma liste">Ma Liste</a>&nbsp;|&nbsp;
	<A class="smliensorange" href="<?=$url?>/aide/contact.php" class="btn" alt="aide">Aide</a>&nbsp;&nbsp;
	<?php include($_SERVER['DOCUMENT_ROOT'].'/menu_connexion.php');?>
	
	
	</td>
</tr>
<tr>
	<td  colspan=3 background="<?=$url?>/images/point.gif" bgcolor="#cccccc" ><img src="<?=$url?>/images/block.gif" width="1" height="1"></td>
</tr>

<tr align="left">
	<td valign="top" width="99%"><span class="small">Saison <?=$_SESSION['sesUser']["saison"]?>, semaine <?=$numeroSemaine?></span></td>
	<td align="left" valign="top" ><A class="smliensorange" href="<?=$url?>/index.php?action=logout" class="btn">Quitter Interface</a></td>
	<td align="right" valign="top" ><img src="<?=$url?>/images/logo_dtn.jpg" align="right" valign="top"></td>
</tr>
</table>



