<?php
require("../includes/head.inc.php");
require("../includes/serviceListesDiverses.php");
require("../includes/serviceJoueur.php");


if(!$sesUser["idAdmin"])
{
	header("location: ../index.php?ErrorMsg=Session Expiree");
}


?>
<link href="../css/ht.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
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
require("../menu/menuMessages.php");

$affPosition = $sesUser["idPosition_fk"] ;
$lstPos = listAllPosition();
$FromMenu="Mon Secteur";

?><title>Messages Proprios</title>
<style type="text/css">
<!--
.Style2 {color: #FFFFFF}
-->
</style><script language="javascript" src="../includes/javascript/navigation.js">


</script>
<form name="form1" method="post" action="<?=$_SERVER['PHP_SELF']?>">  
  <table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
      <td width="20%">&nbsp;
		     <B><div align="left">Secteur de jeu : </B></div>
		     <?php if ($sesUser["idNiveauAcces"] == 1) {?>
		            <!-------------- DTN# -------------->
		            <SELECT NAME="ht_posteAssigne" SIZE=1>
    								<OPTION VALUE="0" <?php if ((!isset($_POST['ht_posteAssigne']))||($_POST['ht_posteAssigne']==0)) {?>SELECTED<?php }?> >Non assign&eacute;</OPTION>
                    <?php foreach($lstPos as $l) {
                    	if (($l["idPosition"]!=6)&&($l["idPosition"]!=8)) {?>
								      <OPTION VALUE="<?=$l["idPosition"]?>" <?php if ($_POST['ht_posteAssigne']==$l["idPosition"]) {?>SELECTED<?php }?> ><?=$l["descriptifPosition"]?></OPTION>
								      <?php }
                    }?>
    						</SELECT>
    			<?php }
					else if (($affPosition!=0) && ($sesUser["idNiveauAcces"] == 2)  ){
						?>
						  <!-------------- DTN+ -------------->
						  <SELECT NAME="ht_posteAssigne" SIZE=1>
    								<OPTION VALUE="0" <?php if ((!isset($_POST['ht_posteAssigne']))||($_POST['ht_posteAssigne']==0)) {?>SELECTED<?php }?> >Non assign&eacute;</OPTION>
    								<OPTION VALUE="<?=$affPosition?>" <?php if ($_POST['ht_posteAssigne']==$affPosition) {?>SELECTED<?php }?> ><?=$lstPos[$affPosition-1]["descriptifPosition"]?></OPTION>
    					</SELECT>
						<?php
					} else {?>
					           <!-------------- DTN -------------->
    							   <INPUT TYPE="hidden" NAME="ht_posteAssigne" VALUE="<?=$affPosition?>" >
    							   <b><font color="#CC2233"><?=$lstPos[($affPosition-1)]["descriptifPosition"]?></font></b>
    						  	<?php
          } ?>
      </td>

    <td width="35%">
      <div align="left">
      <INPUT TYPE="radio" NAME="TypeFiltre" VALUE="1" <?php if (isset($_POST['TypeFiltre']) && $_POST['TypeFiltre']==1) {?>CHECKED<?php }?> >Tous
      <br>
			<INPUT TYPE="radio" NAME="TypeFiltre" VALUE="2" <?php if ((!isset($_POST['TypeFiltre']))||($_POST['TypeFiltre']==2)) {?>CHECKED<?php }?> >Depuis derni&egrave;re connexion
			<br>
			<INPUT TYPE="radio" NAME="TypeFiltre" VALUE="3" <?php if (isset($_POST['TypeFiltre']) && $_POST['TypeFiltre']==3) {?>CHECKED<?php }?> >Depuis le
			<INPUT TYPE="text" NAME="DateFiltre" <?php if (isset($_POST['DateFiltre'])) {?>VALUE="<?=$_POST['DateFiltre']?>"<?php } else {?>VALUE="JJ/MM/AAAA"<?php }?> > <br> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>(Saisir la date au format:<b>JJ/MM/AAAA</b> !!)</i>
			<br>
			</div>
		</td>
    <td rowspan="3" >
      <div align="left"><input type="submit" name="Submit" value="Afficher"></div>
    </td>
    <td width="25%">&nbsp;</td>
  </table>
</form>

  <?php require("messages.php");?>

</body>
</html>
