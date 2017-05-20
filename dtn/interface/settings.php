<?php 
require("includes/head.inc.php");
require("includes/serviceDTN.php");
require("includes/serviceListesDiverses.php");

switch($_SESSION['sesUser']["idNiveauAcces"]){
	case "1":
		require("menu/menuAdmin.php");
		require("menu/menuAdminGestion.php");
		break;
		
	case "2":
		require("menu/menuSuperviseur.php");
		require("menu/menuSuperviseurGestion.php");
		break;

	case "3":
		require("menu/menuDTN.php");
		require("menu/menuDTNGestion.php");
		break;
		
	case "4":
		require("menu/menuCoach.php");
		require("menu/menuCoachGestion.php");
		break;
		
	default;
		break;
}

$affCoeff = 0;
$affinfoPerso = 0;
if (isset($_GET['affCoeff'])
	$affCoeff = $_GET['affCoeff'];
if (isset($_GET['affinfoPerso'])
	$affinfoPerso = $_GET['affinfoPerso'];

$lstPos = listPosition();
if(!isset($id_postes)) $id_postes = 1;?>


<head>
<title>Document sans titre</title>
<link href="css/ht.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>

<script language="javascript">
function chgPos(){
document.location = "settings.php?affCoeff=<?=$affCoeff?>&id_postes="+window.document.form1.id_postes.value;
}
</script>
</head>


<body>
<br />

<?php
if ($affinfoPerso == 1) {
?>
<div class="ContenuCentrer">
<table width="50%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><h3 align="center">Param&egrave;tres</h3></td>
  </tr>
  <tr>
    <td>
    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<td>
		<form name="form1" method="post" action="form.php">
		<table class="grid">
		  <tr>
			<td>Login :</td>
			<td><?=$_SESSION['sesUser']["loginAdmin"]?>
				<input name="login" type="hidden" id="login" value="<?=$_SESSION['sesUser']["loginAdmin"]?>">   </td> 
		  </tr>
		  <tr>
			<td>Mot de passe :</td>
			<td> <input name="mdp" type="text" id="mdp" value="<?=$_SESSION['sesUser']["passAdmin"]?>"></td>
		  </tr>
		  <tr>
			<td>Email :</td>
			<td><input name="email" type="text" id="email" size="40" value="<?=$_SESSION['sesUser']["emailAdmin"]?>"></td>
		  </tr>
		   
		  <tr>
			<td>Pseudo et ID sur HT :</td>
			<td><?php echo($_SESSION['sesUser']['club']['nomUser']." [".$_SESSION['sesUser']['idAdminHT']."]");?></td>
		  </tr>
		  
		  <tr>
			<td>Nom Equipe et ID sur HT :<br /><br /></td>
			<td><?php echo($_SESSION['sesUser']['club']['nomClub']." [".$_SESSION['sesUser']['club']['idClubHT']."]");?><br /><br /></td>
		  </tr>

		  <tr>
			<td colspan="2"><center>
			  <input type="submit" name="Submit" value="MODIFIER MON PROFIL" class="boutonGris">
			  <input name="mode" type="hidden" id="mode" value="chgInfoPerso">
			</center></td>
		  </tr>
		</table>
		</form>
		</td>
	  </tr>
	  <tr>
		<td>&nbsp;
		<center>
        <font color="#FF0000">
<?php if($mod=="ok") echo "Modifications correctements effectu&eacute;es";?>
<?php if($modperso=="ok") echo "Modifications correctement effectu&eacute;es, vous devez vous d&eacute;connecter pour que les changements prennent effet";?>
		</font>
		</center>
		</td>
	  </tr>
	</table>
  </tr>
</table>
</div>

<br />
<br />

<!-- FORMULAIRE AUTORISATION -->
<div class="ContenuCentrer">
  <br />

  <?php if ( isset($_SESSION['HT']) ) {?>
    
    Votre compte DTN est li&eacute; &agrave; votre compte Hattrick : <?php echo($_SESSION['nomUser']." [".$_SESSION['idUserHT']."]"); ?>
    
  <?php } else {?>
    
    <form name="formConnexionHT" method="get" action="">
      <input name="mode" type="hidden" value="redirectionHT">
      <input type="submit" value="AUTORISER ACC&Egrave;S CHPP" class="bouton" /> <br /><br />
    </form>
  
  <?php }?>
  <br /> <br />
</div>

<?php
} 
  
if($affCoeff==1){
?>
  
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><i>G&eacute;rez les coefficients afin d'affiner les notes des joueurs selon vos
      pr&eacute;f&eacute;rences</i></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><form name="form1" method="post" action="form.php">
     
	 
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="24%">Choisissez un poste : </td>
          <td width="65%"><select name="id_postes" onChange="javascript:chgPos()">
            <?php
	  foreach($lstPos as $l){
	  if($l["idPosition"] == $id_postes) $etat = "selected"; else $etat ="";
	  echo "<option value = '".$l["idPosition"]."' ".$etat.">".$l["intitulePosition"]."</option>";
	  }
	  ?>
          </select></td>
          <td width="5%">&nbsp;</td>
          <td width="6%">&nbsp;</td>
        </tr>
      </table>
      <?php if($id_postes != ""){
	  $infC = getCoeffSelectionneur($id_postes);


	  ?>
      <table width="64%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="27%">Construction</td>
          <td width="24%"><center>
            <input name="coeffConstruction" type="text" id="coeffConstruction" value="<?=$infC["coeffConstruction"]?>" size="5">
          </center></td>
          <td width="21%">Passe</td>
          <td width="28%"><center>
            <input name="coeffPasse" type="text" id="coeffPasse" value="<?=$infC["coeffPasse"]?>" size="5">
          </center></td>
        </tr>
        <tr>
          <td>Ailier</td>
          <td><center>
            <input name="coeffAilier" type="text" id="coeffAilier" value="<?=$infC["coeffAilier"]?>" size="5">
          </center></td>
          <td>D&eacute;fense</td>
          <td><center>
            <input name="coeffDefense" type="text" id="coeffDefense" value="<?=$infC["coeffDefense"]?>" size="5">
          </center></td>
        </tr>
        <tr>
          <td height="27">Buteur</td>
          <td><center>
            <input name="coeffButeur" type="text" id="coeffButeur" value="<?=$infC["coeffButeur"]?>" size="5">
          </center></td>
          <td>Gardien</td>
          <td><center>
            <input name="coeffGardien" type="text" id="coeffGardien" value="<?=$infC["coeffGardien"]?>" size="5">
          </center></td>
        </tr>
        <tr>
          <td>Exp&eacute;rience</td>
          <td><center>
            <input name="coeffXp" type="text" id="coeffXp" value="<?=$infC["coeffXp"]?>" size="5">
          </center></td>
          <td>Endurance</td>
          <td><center>
            <input name="coeffEndurance" type="text" id="coeffEndurance" value="<?=$infC["coeffEndurance"]?>" size="5">
          </center></td>
        </tr>
        <tr><?php if($infC["useit"] == 1) $etat = "checked"; else $etat = "";?>
          <td colspan="4"><input name="use" type="checkbox" id="use" value="1" <?=$etat?>>
            Utiliser les coefficients ci dessus</td>
          </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4"><center>
            <input type="submit" name="Submit" value="Valider">
            <input name="mode" type="hidden" id="mode" value="coeffSelectionneur">
            <input name="id_p" type="hidden" value="<?=$id_postes?>">
            <input name="affCoeff" type="hidden" id="affCoeff" value="<?=$affCoeff?>">
          </center></td>
        </tr>
      </table>
  <?php } ?>
  
    </form></td><?php }?>

</body>
</html>
