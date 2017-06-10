<div class="<?php if ($_SESSION['CHPP_KO']==true) {?>statutConnexionKO<?php } else {?>statutConnexion<?php }?>">

<?php
  if ($_SESSION['acces']=="INTERFACE") { echo ("Hattrick &gt;"); }
  
  if (isset($_SESSION['horsConnexion']) && $_SESSION['horsConnexion']=="1") {
      echo("Mode Hors Connexion s&eacute;lectionn&eacute;");
  } elseif ($_SESSION['CHPP_KO']==true) {
      echo("Donn&eacute;es CHPP inaccessibles sur HT !");
  } else {
  
    if (isset($_SESSION['idUserHT']) && !empty($_SESSION['idUserHT']) && 
        isset($_SESSION['nomUser']) && !empty($_SESSION['nomUser']) )
    {
      echo(CONNECTE);
      echo($_SESSION['nomUser']." [".$_SESSION['idUserHT']."]");?>
      &nbsp;|&nbsp;<a href="<?php echo($_SERVER['REQUEST_URI']);if (!empty($_GET)) {echo("&");} else {echo("?");}?>mode=logout"><?php echo(DECONNECTER);?></a>
    <?php } else {?>
      <!-- S'il manque une variable de session alors on affiche le statut non connecté -->
      <a href="<?php $_SERVER['DOCUMENT_ROOT']?><?php if ($_SESSION['acces']=="PORTAIL") {?>/index.php <?php } else {?> /dtn/interface/settings.php <?php } ?>" class="infoGauche">
      <img src="<?php $_SERVER['DOCUMENT_ROOT']?>/images/info.PNG" title="Information" alt="Information" height="10" width="10" />
      &nbsp;<?php echo(NON_CONNECTE);?>
      <span><?php if ($_SESSION['acces']=="PORTAIL") { echo(INFOBULLE_NON_CONNECTE); } else {echo("Vous devez autoriser la DTN &agrave; utiliser votre acc&egrave;s CHPP sur Hattrick pour effectuer des mises &agrave; jour automatique");} ?></span>
      </a>
    <?php }
  }?>

</div>
