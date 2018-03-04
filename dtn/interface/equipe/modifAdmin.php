<?php 
require_once("../includes/head.inc.php");
require("../includes/serviceListesDiverses.php");
require("../includes/serviceDTN.php");

if(!$sesUser["idAdmin"])
{
	header("location: ../index.php?ErrorMsg=Session Expiree");
}


?>
<link href="../css/ht.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>

 <script language="JavaScript">
 windowWidth=530; // largeur du popup
 windowHeight=268; // hauteur du popup
 window.moveTo((screen.width/2)-(windowWidth/2+10),(screen.height/2)-(windowHeight/2+20));
 </script>


<?php


$lstPosition = listPosition();


if(!isset($ordre)) $ordre = "loginAdmin";
if(!isset($sens)) $sens = "ASC";

$infDTN = getDTN($idAdmin);


?><title>Superviseur</title>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="window.focus()"><form name="form1" method="post" action="../form.php">  
<table width="530" height="266" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td height="20" valign="top" ><div align="center">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="20" bgcolor="#000000"><div align="center"><font color="#FFFFFF">Modification
                  du superviseur</font></div>
            </td>
          </tr>
          <tr>
            <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
          </tr>
          <tr>
            <td><br>              <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td height="20">Login</td>
                  <td height="20"><input name="loginAdmin" type="text" id="loginAdmin" value = "<?=$infDTN["loginAdmin"]?>"></td>
                </tr>
                <tr>
                  <td height="20">Mot de passe</td>
                  <td height="20"><input name="passAdmin" type="text" id="passAdmin" value = "<?=$infDTN["passAdmin"]?>"></td>
                </tr>
                <tr>
                  <td height="10">ID Hattrick</td>
                  <td height="10"><input name="idAdminHT" type="text" id="idAdminHT" value = "<?=$infDTN["idAdminHT"]?>"></td>
                </tr>
                <tr>
                  <td height="10">E-Mail</td>
                  <td height="10"><input name="emailAdmin" type="text" id="emailAdmin" value = "<?=$infDTN["emailAdmin"]?>"></td>
                </tr>
                <tr>
                  <td height="20">Niveau d'acc&egrave;s</td>
                  <td height="20"><select name="idNiveauAcces_fk" >
				  <?php
				$sql = "select * from $tbl_niveauAcces WHERE idNiveauAcces != 1";
				
				
				foreach($conn->query($sql) as $lstNA){
					if($lstNA["idNiveauAcces"] == $infDTN["idNiveauAcces_fk"]) $etat = "selected"; else $etat = "";
					echo "<option value = ".$lstNA["idNiveauAcces"]." $etat>".$lstNA["IntituleNiveauAcces"]."</option>";
				
				}
				  
					
				  ?></select>
                 </td>
                </tr>
                <tr>
                  <td height="20">Position supervis&eacute;</td>
                  <td height="20">
				  
				  <select name="idPosition_fk" id="idPosition_fk"><option value = "">Superviseur general</option>
				  <?php
				foreach($lstPosition as $l){  
								  
					if($l["idPosition"] == $infDTN["idPosition_fk"]) $etat = "selected"; else $etat = "";
					echo "<option value = ".$l["idPosition"]." $etat>".$l["intitulePosition"]."</option>";
				  
				}
				  ?>
				  
                  </select></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2"><div align="center">
                    <input type="submit" name="Submit" value="Modifier">
                    <input name="mode" type="hidden" id="mode2" value="modifAdmin">
                    <input name="idAdmin" type="hidden" id="mode3" value="<?=$idAdmin?>">
		          <input name="from" type="hidden" id="mode3" value="<?=$from?>">
                  </div></td>
                  </tr>
              </table>
              <br><?php
			  if (isset($msg)) echo "<center><font color = red>".stripslashes($msg)."</font></center>";
			  ?></td>
          </tr>
        </table>
      </div>
    </td>
  </tr>
</table>
</form>
