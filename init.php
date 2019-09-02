<?php

include_once ($_SERVER["DOCUMENT_ROOT"]."/dtn/interface/includes/head.inc.php");
$_SESSION['acces']="PORTAIL"; // sert à avoir un affichage personnalisé pour les composants utilisés dans le portail et l'interface Doit être positionné avec gestion_session
require_once ($_SERVER["DOCUMENT_ROOT"]."/gestion_session_HT.php");


/******************************************************************************/
/******************************************************************************/
/*      GESTION DE LA LANGUE                                                  */
/******************************************************************************/
/******************************************************************************/
if (!isset($_SESSION['lang'])){
	//recupere la langue par defaut du navigateur
	$lang_browser= substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);
	switch($lang_browser) {
		//lister toutes les langues acceptees : langue = langue navigateur
		case "en" :
		case "de" :
		case "fr" :
		case "es" :
		case "it" :
		case "sv" :
			$_SESSION['lang']=$lang_browser;
		break;
		//sinon anglais
		default :
			$_SESSION['lang']="en";
		break;
	}
}

// Chargement du fichier des traductions
switch($_SESSION['lang']){

	case "fr";
	require("language/fr.php");
	break;

	case "de";
	require("language/de.php");
	break;

	case "en";
	require("language/uk.php");
	break;

	case "es";
	require("language/es.php");
	break;

	case "it";
	require("language/it.php");
	break;

	case "sv";
	require("language/sv.php");
	break;

	default;
	require("language/uk.php");
	break;

}


/******************************************************************************/
/******************************************************************************/
/*      AFFICHAGE CONTENU HTML                                                */
/******************************************************************************/
/******************************************************************************/
include("header.html");

include("menu_haut_".$_SESSION['lang'].".php");
if (isset($file)) {
  include($file."_".$_SESSION['lang'].".php");
}

?>
