<?php
// Variable paramétrage de la page
$nomFicPhpCourant = explode("?",$_SERVER['REQUEST_URI']);
$urlsource = $nomFicPhpCourant[0]; // Utilisé pour setlang.php
$callbackUrl="http://".$_SERVER['HTTP_HOST'].$nomFicPhpCourant[0]."?mode=retour"; // Url de retour après authentification sur HT

include("init.php");
require("dtn/interface/includes/serviceMatchs.php");
//require("dtn/interface/includes/connect.inc.php");
require("inc/fct_requirements.php"); 


switch($_SESSION['lang']) {
	case "fr" :
	$titre = "ht-fff.org| DTN| U20&amp;A | pr&eacute;-requis.";
	break;
	case "de" :
	$titre = "ht-fff.org| DTN| U20&amp;A | requirements.";
	break;
	default :
	$titre = "ht-fff.org| DTN| U20&amp;A | requirements.";
	break;
}
?>

<table width="100%" border="0" cellspacing="0" cellpadding="5" >

      <div align="center"><img src="./img/A.PNG"><br><br></div>

<p> Les minimas sont mis à jour régulièrement par la DTN. Ils fonctionnent à partir des potentiels HTMS de vos joueurs. En cas de doute, n'hésitez pas à contacter la DTN pour en savoir plus. </p>

</table>	 

<?php
include("menu_bas.php");
?>