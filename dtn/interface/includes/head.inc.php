<?php

$monServeur=$_SERVER["DOCUMENT_ROOT"];
global $sesUser;

// Initialisation des variables suivant environnement
$cheminComplet = $monServeur."/dtn/interface/";
$url = "https://".$_SERVER["HTTP_HOST"]."/dtn/interface"; 
$db_c = $_SERVER["DTNHTFFF_DATABASE"];

require($_SERVER["DOCUMENT_ROOT"]."/dtn/interface/includes/connect.inc.php");
require($_SERVER["DOCUMENT_ROOT"]."/dtn/interface/includes/nomTables.inc.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/dtn/interface/includes/fonctions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/dtn/interface/fonctions/HT_Client.php");
//include_once($monServeur."/framework/PHTv3/PHT/autoload.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/framework/PHTv2/PHTv2.php");

// Variables de session
session_start();
$_SESSION['cheminComplet']=$cheminComplet;
$_SESSION['url']=$url;


// On r�affecte les variables globales. Essayer de supprimer cel� � terme
foreach ($_GET as $k=>$v) {
	$$k = $_GET[$k];
}

foreach ($_POST as $k=>$v) {
	$$k = $_POST[$k];
}

foreach ($_SESSION as $k=>$v) {
	$$k = $_SESSION[$k];
}

// Contr�le authentification
$url_courante="https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$pos = strpos($url_courante, "dtn/interface");

if ($pos !== false) { //l'url courante contient dtn/interface
  $pos_maj = strpos($url_courante, "dtn/interface/maj");
  if ($pos_maj === false) { //l'url courante ne contient pas dtn/interface/maj 
    if (!isset($_SESSION['sesUser']) && ($url_courante != $url && $url_courante != $url."/index.php" && $url_courante != $url."/index2.php") )
    {
      echo ("Acc&egrave;s interdit - Aucune session ouverte");
      echo ("<a href=\"$url\"> Cliquez ici pour vous authentifier </a>");
      exit;
    }
  }
}

// Affectation num�ro de semaine. A d�placer ailleurs � terme.
$numeroSemaine = explode(".",((((mktime(0,0,0,date('m'),date('d'),date('Y'))) - $sesUser["dateSemaine0"]) / 86400 ) / 7)+1);
$numeroSemaine = $numeroSemaine[0];

?>
