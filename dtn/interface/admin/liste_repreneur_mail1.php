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
require_once ("../includes/serviceJoueur.php");
require_once("../includes/serviceiihelp.php");

$maBase = initBD();

if(!$sesUser["idAdmin"])
{
  header("location: ../index.php?ErrorMsg=Session_Expire");
}

$AgeAnneeSQL=getCalculAgeAnneeSQL();
$AgeJourSQL=getCalculAgeJourSQL();		
		
$sql = "SELECT *,ht_iiihelp_joueur.commentaire as comment,".$AgeAnneeSQL." as AgeAn,".$AgeJourSQL." as AgeJour 
        FROM  
            ht_iiihelp_joueur, 
            ht_joueurs 
        WHERE ht_iiihelp_joueur.id_dtn = ht_joueurs.idJoueur 
        AND ht_iiihelp_joueur.id_HT = ".$_REQUEST['id_HT']." 
        AND ht_iiihelp_joueur.id_HT = ht_joueurs.idHattrickJoueur 
        AND ht_iiihelp_joueur.entrainement_souhaite = ".$_REQUEST['training'];
$req=  $conn->query($sql);
$res = $req->fetch(PDO::FETCH_OBJ);


?>
<link href="../css/ht.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<script src="../../../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>

<script language="JavaScript" type="text/JavaScript">
<!--
//-->
function lockSubmit(){

document.form1.button.disabled = true;
}
</script>

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
<title>Repreneurs</title>
<link href="../../../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<body>
<p>Vous allez envoyer un mail aux repreneurs potentiels du joueur : <?=$res->nomJoueur?> (<?=$id_HT?>)<br>
</p>
<table border="0" cellspacing="0" cellpadding="4">
  <tr>
	<td>Age :</td>
    <td><?=$res->AgeAn?> ans et <?=$res->AgeJour?> jours</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>XP :</td>
    <td><?=$res->idExperience_fk?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>endurance :</td>
    <td><?=$res->idEndurance?></td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>gardien :</td>
    <td><?=$res->idGardien?></td>
  </tr>
  <tr>
    <td>Construction : </td>
    <td><?=$res->idConstruction?></td>
    <td>&nbsp;</td>
    <td>Passe :</td>
    <td><?=$res->idPasse?></td>
  </tr>
  <tr>
    <td>Ailier : </td>
    <td><?=$res->idAilier?></td>
    <td>&nbsp;</td>
    <td>D&eacute;fense :</td>
    <td><?=$res->idDefense?></td>
  </tr>
  <tr>
    <td>Buteur :</td>
    <td><?=$res->idButeur?>                   </td>
    <td>&nbsp;</td>
    <td>CF :</td>
    <td><?=$res->idPA?> </td>
  </tr>
</table>
<form name="form1" method="post" action="../mailing/liste_repreneur_mail.php?id_HT=<?=$id_HT?>&training=<?=$training?>" onsubmit="lockSubmit()">
  <table border="1" cellpadding="4" cellspacing="0" bordercolor="#000000">
    <tr>
      <td bgcolor="#000000"><font color="#FFFFFF">club </font></td>
      <td bgcolor="#000000"><font color="#FFFFFF">utilisateur</font></td>
    </tr>
<?php

	$sql  = get_iiihelp_repreneur_clubs_SQL();
	$sql .= " AND etat = 0 
	          AND (
                (entrainement_voulu1 in ($res->entrainement_souhaite,-1) AND age_voulu1='Tous')
            OR  (entrainement_voulu2 in ($res->entrainement_souhaite,-1) AND age_voulu2='Tous')";
	if ($res->cat_age=="+21 ans")
	{
  	$sql .= " OR (entrainement_voulu1 in ($res->entrainement_souhaite,-1) AND age_voulu1='+21 ans')";
  	$sql .= " OR (entrainement_voulu2 in ($res->entrainement_souhaite,-1) AND age_voulu2='+21 ans')";
	}
	if ($res->cat_age=="17-20 ans")
	{
  	$sql .= " OR (entrainement_voulu1 in ($res->entrainement_souhaite,-1) AND age_voulu1='17-20 ans')";
  	$sql .= " OR (entrainement_voulu2 in ($res->entrainement_souhaite,-1) AND age_voulu2='17-20 ans')";
	}
	$sql .= ") ORDER BY idClubHT";
	$result = $conn->query($sql);
	while ($restest = $result->fetch()) {
?>
    <tr>
      <td><?=$restest['nomClub']?></td>
      <td><?=$restest['nomUser']?></td>
    </tr>
<?php
	}
?>
  </table>
 <br> 
  <input type="radio" name="type" id="type" value=1>Vente prochaine
  <input checked="true" type="radio" name="type" id="type" value=2>Vente actuelle
  <p>MAP pr&eacute;vue : 
    <input name="map" type="text" id="map" value="<?=$res->map?>"> 
&euro;  </p>
  <p>DTN+ suivant la vente : 
    <span id="sprytextfield1">
    <input type="text" name="dtn" id="dtn">
  <span class="textfieldRequiredMsg">Une valeur est requise.</span></span>  </p>
  <p>Commentaire :
    <input name="id_HT" type="hidden" id="id_HT" value="<?=$res->id_HT?>">
  </p>
  <p>
    <textarea name="commentaire" cols="60" rows="5" id="commentaire"><?=$res->comment?></textarea>
  </p>
  <p>
    <input type="submit" name="button" id="button" value="Envoyer" id="monsubmit">
  </p>
</form>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
-->
</script>
</body>
</html>
