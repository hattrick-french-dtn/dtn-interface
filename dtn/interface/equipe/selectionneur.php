<?php 
require_once("../includes/head.inc.php");
require("../includes/serviceListesDiverses.php");

if(!$sesUser["idAdmin"])
{
	header("location: ../index.php?ErrorMsg=Session Expir�");
}


?>
<link href="../css/ht.css" rel="stylesheet" type="text/css">
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
}


?><title>Modifier les sélectionneurs</title>
<form name="form1" method="post" action="editselec.php">  
<table width="700" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td height="20" ><div align="center">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="20" bgcolor="#000000"><div align="center"><font color="#FFFFFF">Gestion
                  des sélectionneurs</font></div>
            </td>
          </tr>
          <tr>
            <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
          </tr>
          <tr>
            <td><br><table width="50%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td height="20">Compte à modifier</td>
                  <td height="20"><select name="loginAdmin" id="loginAdmin">
                  <option value="CoachA" selected="selected">CoachA</option>
                  <option value="CoachU20">CoachU20</option>
                <tr>
                  <td height="20">Nouveau mot de passe</td>
                  <td height="20"><input name="passAdmin" type="text" id="passAdmin"></td>
                </tr>
                <tr>
                  <td height="10">Nouvel ID User Hattrick</td>
                  <td height="10"><input name="idAdminHT" type="text" id="idAdminHT"></td>
                </tr>
                <tr>
                  <td height="10">Nouvel E-Mail</td>
                  <td height="10"><input name="emailAdmin" type="text" id="emailAdmin"></td>
                </tr>

                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2"><div align="center">
                    <input type="submit" name="Submit" value="Modifier ce sélectionneur">

</div></td>
                  </tr>
              </table>
</td>	
          </tr>
        </table>
      </div>
    </td>
  </tr>
</table>
</form>
<p>&nbsp;</p>

<p>&nbsp;</p>
<?php  deconnect(); ?>
