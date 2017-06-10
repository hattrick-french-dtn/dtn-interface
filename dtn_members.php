<?php
// Variable paramétrage de la page
$nomFicPhpCourant = explode("?",$_SERVER['REQUEST_URI']);
$urlsource = $nomFicPhpCourant[0]; // Utilisé pour setlang.php
$callbackUrl="http://".$_SERVER['HTTP_HOST'].$nomFicPhpCourant[0]."?mode=retour"; // Url de retour après authentification sur HT
$file="members"; // Nom du fichier pour include

include("init.php");
require_once("dtn/interface/includes/connect.inc.php");
require("inc/fct_members.php");
?>

<table>
  <?php
  switch($_GET['poste']) {
  	case "gk" :
  	?>
  	<tr><td><?php echo(afficheMembres(1, "gK|", "goalKeepers")); ?></td></tr>
  	<?php
  	break;
  	case "cdwb" :
  	?>
  	<tr><td><?php echo(afficheMembres(2, "cD+wB|", "defenders")); ?></td></tr>
  	<?php
  	break;
  	case "im" :
  	?>
  	<tr><td><?php echo(afficheMembres(4, "iM|", "innerMidfielders")); ?></td></tr>
  	<?php
  	break;
  	case "wg" :
  	?>
  	<tr><td><?php echo(afficheMembres(3, "Wg|", "Wingers")); ?></td></tr>
  	<?php	
  	break;
  	case "fw" :
  	?>
  	<tr><td><?php echo(afficheMembres(5, "Fw|", "Forwards")); ?></td></tr>
  	<?php
  	break;
  	case "staff" :
  	?>
  	<tr><td><? echo(afficheMembres(0, "", "[staff]")); ?></td></tr>
  	<?php
  	break;
  	default :
  	break;
  	?>
  	<tr><td height="5">&nbsp;</td></tr>
  	<?php
  }?>
  <tr><td height="5">&nbsp;</td></tr>
</table>

<?php 
include("menu_bas.php");
?>
