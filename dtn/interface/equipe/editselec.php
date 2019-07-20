<?php 
require_once("../includes/head.inc.php");
require_once("../includes/connect.inc.php");
require("../includes/serviceListesDiverses.php");

if(!$sesUser["idAdmin"])
{
	header("location: ../index.php?ErrorMsg=Session Expir?");
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


?><title>Sélectionneurs</title>
<table width="700" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr><br/><br/><td height="20" bgcolor="#000000"><div align="center"><font color="#FFFFFF">Gestion des s&eacute;lectionneurs</font></div>
            </td>
            <td height="20" ><div align="center">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
            
<?php
    if (isset($_POST["loginAdmin"]) && isset($_POST["passAdmin"]) && isset($_POST["idAdminHT"]) && isset($_POST["emailAdmin"])) {
        $req = $conn->prepare('UPDATE ht_admin SET passAdmin = :nvpass, idAdminHT = :nvid, emailAdmin = :nvmail WHERE loginAdmin = :login');
        $req->execute(array(
            'nvpass' => sha1($_POST["passAdmin"]),
            'nvid' => $_POST["idAdminHT"],
            'nvmail' => $_POST["emailAdmin"],
            'login' => $_POST["loginAdmin"]
            ));
            ?> <p>Entre valid&eacute;e !</p> <?php
    }
            
            $sql = "SELECT * FROM ht_admin WHERE idNiveauAcces_fk = '4' AND loginAdmin = 'CoachA'";
            $req = $conn->query($sql);
            $ligne = $req->fetch(PDO::FETCH_ASSOC);
            if (is_array($ligne))
            extract($ligne);

            ?><p>Liste des coachs :</p><?php
            foreach($conn->query($sql) as $lstNA){
					echo "ID HT CoachA = ".$lstNA["idAdminHT"];?><br/><?php
            }
            
            $sql2 = "SELECT * FROM ht_admin WHERE idNiveauAcces_fk = '4' AND loginAdmin = 'CoachU20'";
            $req2 = $conn->query($sql2);
            $ligne2 = $req2->fetch(PDO::FETCH_ASSOC);
            if (is_array($ligne2))
            extract($ligne2);

            foreach($conn->query($sql2) as $lstNU20){
					echo "ID HT CoachU20 = ".$lstNU20["idAdminHT"];?><br/><?php
            }
            ?>
            <p></p>
        </tr>
        </table>
      </div>
    </td>
  </tr>
</table>



