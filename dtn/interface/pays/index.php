<?php
        // Affiche toutes les erreurs
//        error_reporting(E_ALL);

require_once("../includes/head.inc.php");
require_once "../_config/CstGlobals.php"; // fonctions d'admin
require_once "../fonctions/HT_Client.php"; // Hattrick Client New (with Advanced HTTP Client)
require_once "../fonctions/phpxml.php"; // XML to Tree converter
require_once "../fonctions/AccesBase.php"; // fonction de connexion � la base
require_once "../fonctions/AdminDtn.php"; // fonctions d'admin
require_once "maj_pays.php"; // fonctions de mise � jour pays

$maBase = initBD();

/*if(!$sesUser["idAdmin"])
{
    header("location: index.php?ErrorMsg=Session Expire");
}
*/



?>
<link href="../css/ht.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<?php
switch($sesUser["idNiveauAcces"]){
                case "1":
                require("../menu/menuAdmin.php");
                require("../menu/menuSuperviseurConsulter.php");
                break;

                case "2":
                require("../menu/menuSuperviseur.php");
                require("../menu/menuSuperviseurConsulter.php");
                break;

                case "3":
                require("../menu/menuDTN.php");
                require("../menu/menuDTNConsulter.php");
                break;

                case "4":
                require("../menu/menuCoach.php");
                break;

                default;
                break;
}

?>
<title>Superviseur</title>
<form name="form1" method="post" action="index.php">
<table width="700" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td height="20" ><div align="center">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
              <td height="20" bgcolor="#000000">
<div align="center"><font color="#FFFFFF">Gestion des Pays</font></div>
            </td>
          </tr>
          <tr>
            <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
          </tr>
          <tr>
            <td><br>
            <div align="center">Merci de ne mettre &agrave; jour les pays qu'&agrave; l'apparition de nouveaux pays dans Hattrick.</div>
            <br/>         
              <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td height="20">(Votre) Login Hattrick </td>
                  <td height="20"><input name="ht_user" type="text"></td>
                </tr>
                <tr>
                  <td height="20">(Votre) Security code</td>
                  <td height="20"><input name="ht_password" type="password"></td>
                </tr>
                <tr>
                  <td colspan="2"><div align="center">
                    <input type="submit" name="Submit" value="Mettre &agrave; jour tous les pays">
</div></td>
                  </tr>
              </table>
                          <br />
                                        <br>
<div><?php

                // param�tres
			if (isset($_POST['ht_user'])) {
	                $ht_user=$_POST['ht_user'];
	                $ht_password=$_POST['ht_password'];
	                $HTCli= &new HT_Client();
	                if ($HTCli->Login($ht_user, $ht_password)) {
						echo "maj";
	                        majPays($maBase,$HTCli);
	                } else {
	                        printErr("<center>Erreur de connexion � HT</center>");
	                }
                }
?>
</div>
</td>
          </tr>
        </table>
      </div>
    </td>
  </tr>
</table>
</form>

</body>
</html>