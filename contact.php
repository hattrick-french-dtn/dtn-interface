<?php
// Variable param�trage de la page
$nomFicPhpCourant = explode("?",$_SERVER['REQUEST_URI']);
$urlsource = $nomFicPhpCourant[0]; // Utilis� pour setlang.php
$callbackUrl="http://".$_SERVER['HTTP_HOST'].$nomFicPhpCourant[0]."?mode=retour"; // Url de retour apr�s authentification sur HT
//$file="contact"; // Nom du fichier pour include

include("init.php");
?>


<div class="ContenuCentrer">
  <br /><br />
  <!--
  <a href="http://www.htfff.free.fr/dtn/forum"> 1- <?php echo(FORUM)?> </a>
  <br /><br /><br />
  -->
  <a href="dtn_members.php"> 1- <?php echo(HT_MAIL)?></a>
  <br /><br /><br />
  <a href="fff_federation.php"> 2- <?php echo(CONF_HT)?> </a>
  <br /><br /><br />
</div>



<?php 
include("menu_bas.php");
?>
