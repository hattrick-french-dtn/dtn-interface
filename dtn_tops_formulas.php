<?php
  session_start();
	$lang = $_SESSION["lang"];

  if (!isset($lang)){
		$lang="en";
		$_SESSION["lang"]=$lang;
	}
	
	$urlsource="dtn_tops_formulas.php";
	switch($lang) {
		case "fr" :
		include("menu_haut.php");
		include("formulas.php");
		break;
		case "de" :
		include("menu_haut_de.php");
		include("formulas_uk.php");
		break;
		default :
		include("menu_haut_uk.php");
		include("formulas_uk.php");
		break;
	}
	include("menu_bas.php");
?>