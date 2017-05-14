<?php

$monServeur=$_SERVER["DOCUMENT_ROOT"];
global $sesUser;

// Initialisation des variables suivant environnement
if ($_SERVER["HTTP_HOST"] == "www.ht-fff.org")
{
	$cheminComplet = $monServeur."/dtn/interface/";
	$url = "http://".$_SERVER["HTTP_HOST"]."/dtn/interface";
	$db_c = "dtn_utf8";
}
else if ($_SERVER["HTTP_HOST"] == "dev.ht-fff.org")
{
	$cheminComplet = $monServeur."/dtn/interface/";
	$url = "http://".$_SERVER["HTTP_HOST"]."/dtn/interface";
	$db_c = "ht_dtn";
}
else
{
	$cheminComplet = $monServeur."/dtn/interface/";
	$url = "http://".$_SERVER["HTTP_HOST"]."/dtn/interface"; 
	$db_c = "dtn_htfff";
}


require($cheminComplet."includes/connect.inc.php");
require($cheminComplet."includes/nomTables.inc.php");
require($cheminComplet."/includes/fonctions.php");
require($cheminComplet."/fonctions/HT_Client.php");
include($monServeur."/framework/PHT/PHT.php");

// Variables de session
session_start();
$_SESSION['cheminComplet']=$cheminComplet;
$_SESSION['url']=$url;


// On réaffecte les variables globales. Essayer de supprimer celà à terme
foreach ($_GET as $k=>$v) {
	$$k = $_GET[$k];
}

foreach ($_POST as $k=>$v) {
	$$k = $_POST[$k];
}

foreach ($_SESSION as $k=>$v) {
	$$k = $_SESSION[$k];
}

// Contrôle authentification
$url_courante="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
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

// Affectation numéro de semaine. A déplacer ailleurs à terme.
$numeroSemaine = explode(".",((((mktime(0,0,0,date('m'),date('d'),date('Y'))) - $sesUser["dateSemaine0"]) / 86400 ) / 7)+1);
$numeroSemaine = $numeroSemaine[0];

?>
