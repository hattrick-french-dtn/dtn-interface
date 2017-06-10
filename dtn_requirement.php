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
$rqr_u20 = afficheRequirements( "U20", $_SESSION['lang'] );
$rqr_a = afficheRequirements( "A", $_SESSION['lang']);

?>

<table width="100%" border="0" cellspacing="0" cellpadding="5" >
    <tr>
      <td rowspan="3" width="10">&nbsp;</td>
    </tr>
    <tr><td>
      <div align="center"><img src="./img/U20.PNG"><br><br></div>
      <?php echo( $rqr_u20 ); ?>
    </td></tr>
	 <tr><td>
    <br><hr>
      <div align="center"><img src="./img/A.PNG"><br><br></div>
      <?php echo( $rqr_a ); ?>
    </td></tr>
</table>	 

<?php
include("menu_bas.php");
?>