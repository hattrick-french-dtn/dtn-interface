<?php
// Affiche toutes les erreurs
error_reporting(E_ALL);

require_once("../_config/CstGlobals.php"); // fonctions d'admin
//require_once "../fonctions/HT_Client.php"; // Hattrick Client New (with Advanced HTTP Client)
//require_once "../fonctions/phpxml.php"; // XML to Tree converter
require_once("../fonctions/AccesBase.php"); // fonction de connexion � la base
require_once("../fonctions/AdminDtn.php"); // fonctions d'admin
require_once("../includes/head.inc.php");

$maBase = initBD();

if(!$sesUser["idAdmin"])
{
	header("location: ../index.php?ErrorMsg=Session_Expire");
}

if (isset($action) && "purge"==$action && isset($week)&& isset($season)){
	
	header("location: matchsOverview.php?week=".$week."&season=".$season."&action=".$action);
}


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
		require("../menu/menuCoachConsulter.php");
		break;

	default;
		break;
}



?>
<link href="../css/ht.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>

<title>Superviseur</title>
<?php
if (isset($redirect)){
	include($redirect);
}else{
  include("majplayerDetails.php");
}
?>
</body>
</html>