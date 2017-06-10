<?php 
require_once("includes/head.inc.php");






if(!$sesUser["idAdmin"])
{
	header("location: index.php?ErrorMsg=Session Expiree");
}


switch($sesUser["idNiveauAcces"]){
		case "1":
		require("menu/menuAdmin.php");
		require("outils/monTableauBord.php");
		require("menu/menuAdminDesc.php");
		break;
		
		case "2":
		require("menu/menuSuperviseur.php");
		require("outils/monTableauBord.php");
		require("menu/menuSuperviseurDesc.php");
		break;


		case "3":
		require("menu/menuDTN.php");
		require("outils/monTableauBord.php");
		require("menu/menuDTNDesc.php");
		break;


		
		case "4":
		require("menu/menuCoach.php");
		require("outils/monTableauBord.php");
		require("menu/menuCoachDesc.php");
		break;
		
		default;
		break;
}




if(isset($Msg)){
?><font size=+2 color=red><?=$Msg?></font>
<?php } ?>