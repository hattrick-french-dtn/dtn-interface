<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=iso-8859-1" />

<link rel="stylesheet" type="text/css" href="../css/stylePopup.css" />
<script src="../../../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../../../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />

</head>
<body>

<?php
require("../includes/connect.inc.php");
require("../includes/serviceEntrainement.php");
require("../includes/serviceEquipes.php");

// Dernier historique club
$lastHistoClub=getLastHistoClub($_GET['idClubHT']);

// Liste des entrainements
$lstEntrainementPossible = listEntrainement();
?>

<table class="subcenter">
  <form name="formAddInfoClub" method="post" target="_parent" action="../form.php">
    <tr><td>Entrainement :</td><td>
      <select name="idEntrainement">
    	<?php
   	  foreach($lstEntrainementPossible as $l){
        if ($l["afficher"]==1) {
        	if($lastHistoClub["idEntrainement"] == $l["id_type_entrainement"]) $etat = " selected"; else $etat = " ";?>
        	<option value=<?=$l["id_type_entrainement"].$etat?>><?=$l["libelle_type_entrainement"]?></option>;
      	<?php }
    	}
    	?> 
      </select></td></tr>
    <tr>
    <td>Intensit&eacute; :</td>
    <td>
      <span id="sprytextfield1">
      <input name="intensite" type="text" id="intensite" size=2 value="<?=$lastHistoClub["intensite"]?>">
      <span class="textfieldInvalidFormatMsg">Entrez uniquement des chiffres et des nombres non n&eacute;gatifs</span>
      <span class="textfieldMinValueMsg">Doit etre sup&eacute;rieure &agrave; 0</span>
      <span class="textfieldMaxValueMsg">Doit etre inf&eacute;rieure &agrave; 100</span>
      </span>
    </td>
    </tr>
    <tr>
    <td>Endurance :</td>
    <td>
      <span id="sprytextfield2">
      <input name="endurance" type="text" id="endurance" size=2 value="<?=$lastHistoClub["endurance"]?>">
      <span class="textfieldInvalidFormatMsg">Entrez uniquement des chiffres et des nombres non n&eacute;gatifs</span>
      <span class="textfieldMinValueMsg">Doit etre sup&eacute;rieure &agrave; 5</span>
      <span class="textfieldMaxValueMsg">Doit etre inf&eacute;rieure &agrave; 100</span>
      </span>
    </td>
    </tr>
    <tr>
    <td>Qualit&eacute; des adjoints [0 &agrave; 10] :</td>
    <td>
      <span id="sprytextfield3">
      <input name="adjoints" type="text" id="adjoints" size=2 value="<?=$lastHistoClub["adjoints"]?>">
      <span class="textfieldInvalidFormatMsg">Entrez uniquement des chiffres et des nombres non n&eacute;gatifs</span>
      <span class="textfieldMinValueMsg">Doit etre sup&eacute;rieure &agrave; 0</span>
      <span class="textfieldMaxValueMsg">Doit etre inf&eacute;rieure &agrave; 10</span>
      </span>
    </td>
    </tr>
    <tr>
    <td>Qualit&eacute; du m&eacute;decins [0 &agrave; 5] :</td>
    <td>
      <span id="sprytextfield3">
      <input name="medecin" type="text" id="medecins" size=2 value="<?=$lastHistoClub["medecin"]?>">
      <span class="textfieldInvalidFormatMsg">Entrez uniquement des chiffres et des nombres non n&eacute;gatifs</span>
      <span class="textfieldMinValueMsg">Doit etre sup&eacute;rieure &agrave; 0</span>
      <span class="textfieldMaxValueMsg">Doit etre inf&eacute;rieure &agrave; 5</span>
      </span>
    </td>
    </tr>
    <tr>
    <td>Qualit&eacute; du pr&eacute;pa phys [0 &agrave; 5] :</td>
    <td>
      <span id="sprytextfield3">
      <input name="physio" type="text" id="physio" size=2 value="<?=$lastHistoClub["physio"]?>">
      <span class="textfieldInvalidFormatMsg">Entrez uniquement des chiffres et des nombres non n&eacute;gatifs</span>
      <span class="textfieldMinValueMsg">Doit etre sup&eacute;rieure &agrave; 0</span>
      <span class="textfieldMaxValueMsg">Doit etre inf&eacute;rieure &agrave; 5</span>
      </span>
    </td>
    </tr>
    <tr><td valign="top">Commentaire sur le club :</td><td><textarea name="Commentaire" rows=10 cols=40 id="Commentaire"></textarea></td></tr>
    <input name="idClubHT" type="hidden" id="idClubHT" value="<?=$_GET['idClubHT']?>">
    <input name="idJoueur" type="hidden" id="idJoueur" value="<?=$_GET['idJoueur']?>">
    <input name="OldEntrainementId" type="hidden" id="OldEntrainementId" value="<?=$_GET['entrainement_id']?>">
    <input name="role_createur" type="hidden" id="role_createur" value="<?=$_GET['role_createur']?>">
    <input name="mode" type="hidden" id="mode" value="addHistoClub">
    <tr><td><input type="submit" value="Ajouter"></td></tr>
  </form>
  
  <script type="text/javascript">
  <!--
	 var sprytextfield1= new Spry.Widget.ValidationTextField("sprytextfield1", "integer", {validateOn:["blur"],allowNegative:false,minValue:0,maxValue:100,isRequired:false});
	 var sprytextfield2= new Spry.Widget.ValidationTextField("sprytextfield2", "integer", {validateOn:["blur"],allowNegative:false,minValue:5,maxValue:100,isRequired:false});
	 var sprytextfield3= new Spry.Widget.ValidationTextField("sprytextfield3", "integer", {validateOn:["blur"],allowNegative:false,minValue:0,maxValue:10,isRequired:false});
  //-->
  </script>
</table>
</body>
</html>
