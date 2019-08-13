<?php 
// Variable paramétrage de la page
$_SESSION['acces']="INTERFACE"; // sert à avoir un affichage personnalisé pour les composants utilisés dans le portail et l'interface
$nomFicPhpCourant = explode("?",$_SERVER['REQUEST_URI']);
$callbackUrl="https://".$_SERVER['HTTP_HOST'].$nomFicPhpCourant[0]."?mode=retour"; // Url de retour après authentification sur HT
include($_SERVER['DOCUMENT_ROOT'].'/gestion_session_HT.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/language/fr.php');

if(!isset($_SESSION['sesUser']["idAdmin"]))
{
	header("location: https://".$_SERVER['SERVER_NAME']."/dtn/interface/index.php?ErrorMsg=Session Expire");
}
?>

<html>
<head>
<title>DTN # [ht-fff]</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link rel="stylesheet" type="text/css" href="<?=$url?>/css/ht2.css" />
<link rel="stylesheet" type="text/css" href="<?=$url?>/css/style.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body class="interface">
<br />
<center>
<table cellpadding="0" cellspacing="0" width="90%" border="0">
<tr><td>
<table cellpadding="0" cellspacing="2" width="100%" border="0">
<tr align="left" nowrap>
	<td width="1%" class="bred" nowrap nospan><a href="<?=$url?>/index2.php"><?=$sesUser["IntituleNiveauAcces"]?></a>&nbsp;&gt;<span class="breadvar"> <?=$sesUser["loginAdmin"]?></span></td>

	<td colspan=2 align="right" valign="bottom" nowrap width="1%">
		<form method="get" action="<?=$url?>/formJoueurDirect.php" style="display: inline;">
		<label for "idJoueurHT">Acc&egrave;s Direct : </label>
		<input type="text" name="idJoueurHT" value="ID Joueur" size="8" onfocus="if(this.value=='ID Joueur'){this.value=''}" onblur="if(this.value==''){this.value = 'ID Joueur'}"/>
        <input type="submit" value="Voir" />
        </form>
	&nbsp;| <a class="smliensorange" href="<?=$url?>/equipe/superviseur.php">Gestion</a>&nbsp;|&nbsp;
	<a class="smliensorange" href="<?=$url?>/pays/listeMaJpays.php">Liste Pays & MaJ</a>&nbsp;|&nbsp;
	<a class="smliensorange" href="<?=$url?>/national_team/team.php?selection=A">Equipes Nationales</a>&nbsp;|&nbsp;
	<a class="smliensorange" href="<?=$url?>/joueurs/toplist.php" alt="Chercher">Base de donn&eacute;es</a>&nbsp;|&nbsp;
	<a class="smliensorange" href="<?=$url?>/consulter/messagesMaListe.php" alt="Messages Proprios">Messages Proprios</a>&nbsp;|&nbsp;
	<a class="smliensorange" href="<?=$url?>/maliste/maliste.php" alt="ma liste">Ma Liste</a>&nbsp;|&nbsp;
	<a class="smliensorange" href="<?=$url?>/aide/contact.php" alt="aide">Aide</a>&nbsp;
	<?php include($_SERVER['DOCUMENT_ROOT'].'/menu_connexion.php');?>
	</td>
</tr>
<tr>
	<td  colspan=3 background="<?=$url?>/images/point.gif" bgcolor="#cccccc" ><img src="<?=$url?>/images/block.gif" width="1" height="1"></td>
</tr>


<tr align="left">
	<td valign="top" width="99%"><span class="small">Saison <?=$_SESSION['sesUser']['saison']?>, semaine <?=$numeroSemaine?></span></td>
	<td align="right" valign="top" ><A class="smliensorange" href="<?=$url?>/index.php?action=logout" class="btn">Quitter Interface</a></td>
	<td align="right" valign="top" ><img src="<?=$url?>/images/logo_dtn.jpg" align="right" valign="top"></td>
</tr>
</table>
