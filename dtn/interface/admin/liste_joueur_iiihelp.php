<?php
// Affiche toutes les erreurs
error_reporting(E_ALL);

require_once "../_config/CstGlobals.php"; // fonctions d'admin
//require_once "../fonctions/HT_Client.php"; // Hattrick Client New (with Advanced HTTP Client)
//require_once "../fonctions/phpxml.php"; // XML to Tree converter
require_once "../fonctions/AccesBase.php"; // fonction de connexion � la base
require_once "../fonctions/AdminDtn.php"; // fonctions d'admin
require_once("../includes/head.inc.php");
require_once("../includes/serviceListesDiverses.php");

$maBase = initBD();

if(!$sesUser["idAdmin"])
{
  header("location: ../index.php?ErrorMsg=Session_Expire");
}


/******************************************************************************/
/*      INITIALISATION VARIABLES                                              */
/******************************************************************************/
// Liste des caractéristiques
$liste_carac = listTypeCaracEntrainable();


/******************************************************************************/
/*      PURGE JOUEURS                                                         */
/******************************************************************************/
// Joueurs avec statut vendu depuis plus de 1 journée
$datevieille = time()-3600*24*7;
$datetest = date("Y-m-d",$datevieille);
$sql = "delete from ht_iiihelp_joueur where etat = 3 and date_vente < '$datetest'";
$req=  $conn->exec($sql);

// Joueurs supprimés manuellement avec le lien supprimer
if (isset($_REQUEST['joueurhelpsupr']))
{
	 $sqlsupression= "delete from ht_iiihelp_joueur where id_HT=".$_REQUEST['joueurhelpsupr'];
	 $req2 = $conn->exec($sqlsupression);
}

// Joueurs supprimés manuellement avec le lien supprimer
if (isset($_REQUEST['vendu']))
{
	 $sql = "update ht_iiihelp_joueur set etat = 3, date_vente = NOW() where ht_iiihelp_joueur.id_HT = ".$_REQUEST['vendu'];
	 $req = $conn->exec($sql);
}	  





/******************************************************************************/
/*      AFFICHAGE CONTENU PAGE                                                */
/******************************************************************************/
?>
<link href="../css/ht2.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<?php
switch($sesUser["idNiveauAcces"]){
    case "1":
		require("../menu/menuAdmin.php");
		require("../menu/menuAdminGestion.php");
		break;

	case "2":
		require("../menu/menuSuperviseur.php");
		require("../menu/menuSuperviseurGestion.php");
		break;

	case "3":
		require("../menu/menuDTN.php");
		require("../menu/menuDTNGestion.php");
		break;

	case "4":
		require("../menu/menuCoach.php");
		require("../menu/menuCoachGestion.php");
		break;

	default;
		break;
}
?>
<title>Joueurs</title>
<body>
<center>
<br>
    <b><span class="breadvar">Liste des Joueurs iiiHelp!</span></b>
    <br><br>
</center>
<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
  <td>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#000000" width="13%"><font color="#FFFFFF">idHT 	 	 	 	 	 	 	 	 	 	 	 	 	</font></td>
    <td bgcolor="#000000" width="13%"><font color="#FFFFFF">Nom</font></td>
    <td bgcolor="#000000" width="10%"><font color="#FFFFFF">Date ajout</font></td>
    <td bgcolor="#000000" width="15%"><font color="#FFFFFF">entrainement_souhait&eacute;</font></td>
	<td bgcolor="#000000" width="13%"><font color="#FFFFFF">cat&eacute;gorie age</font></td>
    <td bgcolor="#000000" width="13%"><font color="#FFFFFF">MAP</font></td>
    <td bgcolor="#000000" width="13%"><font color="#FFFFFF">etat</font></td>
    <td bgcolor="#000000" width="10%"><font color="#FFFFFF">Action</font></td>
  </tr>
    <tr>
    <td ALIGN="center" bgcolor="#9600B4" colspan="8"><font color="#FFFFFF">Nouveau joueurs :</font></td>
  </tr>
<?php             
				 
		$sql = "select * from ht_iiihelp_joueur, ht_joueurs where ht_iiihelp_joueur.id_dtn = ht_joueurs.idJoueur and etat = 0 order by id_HT";
		$i = 0;
		while ($conn->query($sql) as $res)
		{
			$i++;
?>  
<tr <?php if ($i % 2 == 0) echo "bgcolor=#CCCCCC";  else echo "bgcolor=#FFFFFF";?> >
  <td><a href="<?=$url?>/joueurs/fiche.php?htid=<?=$res['id_HT']?>"><?=$res['id_HT']?> </td>
  <td><a href="<?=$url?>/joueurs/fiche.php?htid=<?=$res['id_HT']?>"><?=$res['nomJoueur']?></td>
  <td><?=$res['date']?></td>
  <td><?php
  $carac=get_Carac_byID($res['entrainement_souhaite'],$liste_carac);
  echo $carac['nomTypeCarac'];
  ?></td>
  <td><?=str_replace("+21 ans","21 ans et +",$res['cat_age'])?></td>
  <td><?=$res['map']?> &euro;</td>
  <td><?php
  switch($res['etat'])
  {
  	case "0":
		echo "ajout&eacute;";
		break;
  	case "1":
		echo "vente pr&eacute;-annonc&eacute;e";
		break;
  	case "2":
		echo "vente en cours annonc&eacute;e";
		break;
  	case "3":
		echo "vendu";
		break;
  }?></td>
	<td><a href="liste_repreneur_mail1.php?id_HT=<?=$res['id_HT']?>&training=<?=$res['entrainement_souhaite']?>">Envoi Mail Repreneur</a><br>
      <a href="?vendu=<?=$res['id_HT']?>">Vendu</a><br>
	  <a href="?joueurhelpsupr=<?=$res['id_HT']?>">Supprimer</a></td>
	</tr>
  <?php } ?>

  <tr>
    <td ALIGN="center" bgcolor="#9600B4" colspan="8"><font color="#FFFFFF">Mailing effectu&eacute; pour :</font></td>
  </tr>
<?php 
	$sql = "select * from ht_iiihelp_joueur, ht_joueurs where ht_iiihelp_joueur.id_dtn = ht_joueurs.idJoueur and etat > 0 and etat < 3 order by id_HT";
	$i = 0;
	foreach ($conn->query($sql) as $res)
	{
		$i++;
?>  
<tr <?php if ($i % 2 == 0) echo "bgcolor=#CCCCCC";  else echo "bgcolor=#FFFFFF";?> >
  <td><a href="<?=$url?>/joueurs/fiche.php?htid=<?=$res['id_HT']?>"><?=$res['id_HT']?> </td>
  <td><a href="<?=$url?>/joueurs/fiche.php?htid=<?=$res['id_HT']?>"><?=$res['nomJoueur']?></td>
  <td><?=$res['date']?></td>
  <td><?php
  $carac=get_Carac_byID($res['entrainement_souhaite'],$liste_carac);
  echo $carac['nomTypeCarac'];
  ?></td>
  <td><?=str_replace("+21 ans","21 ans et +",$res['cat_age'])?></td>
  <td><?=$res['map']?> &euro;</td>
  <td><?php
  switch($res['etat'])
  {
  	case "0":
		echo "ajout&eacute;";
	break;
  	case "1":
		echo "vente pr&eacute;-annonc&eacute;e";
	break;
  	case "2":
		echo "vente en cours annonc&eacute;e";
	break;
  	case "3":
		echo "vendu";
	break;
  }?></td>
  <td><a href="liste_repreneur_mail1.php?id_HT=<?=$res['id_HT']?>&training=<?=$res['entrainement_souhaite']?>">R&eacute;-envoi Mail Repreneur</a><br>
      <a href="vendu.php?id_HT=<?=$res['id_HT']?>">Vendu</a></td>
</tr>
  <?php } ?>

  <tr>
    <td ALIGN="center" bgcolor="#9600B4" colspan="8"><font color="#FFFFFF">Vendu :</font></td>
  </tr>
<?php 
	$sql = "select * from ht_iiihelp_joueur, ht_joueurs where ht_iiihelp_joueur.id_dtn = ht_joueurs.idJoueur and etat = 3 order by id_HT";
	$i = 0;
	foreach ($conn->query($sql) as $res)
	{
		$i++;
?>  
<tr <?php if ($i % 2 == 0) echo "bgcolor=#CCCCCC";  else echo "bgcolor=#FFFFFF";?> >
  <td><a href="<?=$url?>/joueurs/fiche.php?htid=<?=$res['id_HT']?>"><?=$res['id_HT']?> </td>
  <td><a href="<?=$url?>/joueurs/fiche.php?htid=<?=$res['id_HT']?>"><?=$res['nomJoueur']?></td>
  <td><?=$res['date']?></td>
  <td><?php 
  $carac=get_Carac_byID($res['entrainement_souhaite'],$liste_carac);
  echo $carac['nomTypeCarac'];
  ?></td>
  <td><?=str_replace("+21 ans","21 ans et +",$res['cat_age'])?></td>
  <td><?=$res['map']?> &euro;</td>
  <td><?php
  switch($res['etat'])
  {
  	case "0":
		echo "ajout&eacute;";
	break;
  	case "1":
		echo "vente pr&eacute;-annonc&eacute;e";
	break;
  	case "2":
		echo "vente en cours annonc&eacute;e";
	break;
  	case "3":
		echo "vendu";
	break;
  }?></td>
  <td><a href="liste_joueur_iiihelp.php?joueurhelpsupr=<?=$res['id_HT']?>">Supprimer</a>
</tr>
  <?php } ?>
</table>
</td></tr>
</table>
<br/>
<div align="center"><a href="javascript:history.go(-1);">Retour</a></div>
</body>
</html>