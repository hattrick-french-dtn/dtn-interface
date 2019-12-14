  <!--   
  ************************************************************************************************************************
  MENU PRINCIPAL
  ************************************************************************************************************************
  -->
<!--    <div id="menu_htfff"> 
            <a href="http://www.ht-fff.org"><img src="img/bann_htfff.gif" width="248" height="80" align="left" border="0" alt="contact" title="contact" /></a> 
            <a href="fff_index.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('FFF','','img/bann_fff_over.gif',1)"> <img src="img/bann_fff.gif" name="FFF" width="53" height="80" border="0" alt="FFF" title="FFF" /></a>&nbsp;&nbsp; 
            <a href="dtn_index.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('DTN','','img/bann_dtn_over.gif',1)"> <img src="img/bann_dtn.gif" name="DTN" width="53" height="80" border="0" alt="DTN" title="DTN" /></a>
            <a href="http://www.hattrick.org" target="_blank" onMouseOver="MM_swapImage('hattrick','','img/bann_hattrick_over.gif',1)" onMouseOut="MM_swapImgRestore()"> <img src="img/bann_hattrick.gif" name="hattrick" width="79" height="80" border="0" alt="hattrick" title="hattrick" /></a></div> -->

<div id="menu_htfff">
  <a href="index.php">
		<img src="img/bann_htfff.gif" width="248" height="80" align="left" border="0" />
  </a>
  <a href="index.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('DTN','','img/bann_dtn_over.gif',1)"> 
		<img src="img/bann_dtn.gif" name="DTN" width="53" height="80" border="0" alt="DTN" title="DTN" />
  </a>
  <a href="http://www.hattrick.org" target="_blank" onMouseOver="MM_swapImage('hattrick','','img/bann_hattrick_over.gif',1)" onMouseOut="MM_swapImgRestore()"> 
		<img src="img/bann_hattrick.gif" name="hattrick" width="79" height="80" border="0" alt="hattrick" title="hattrick" />
  </a>
</div>

    <?php include("menu_connexion.php");?>
    <!--   
  ************************************************************************************************************************
  MENU FFF | DTN ...
  ************************************************************************************************************************
  -->
    <!--<table width="100%" border="0" cellpadding="0" cellspacing="0" style="background-image:url(img/sous-menu_cadre_bg.gif)">
      <tr>
        <td width="40" align="left"><img src="img/sous-menu_cadre_left.gif" width="10" height="40" /></td>
        <td width="16%" align="center" valign="middle" class="style11"><a href="index.php"><span style="color:#000000">[<span class="style12">HOME</span>]</font></span></a></td>
        <td width="16%" align="center" valign="middle" class="style12"><script type="text/javascript">SPop('layer0','<span class="style31">FFF| <\/span><span style="color:#000000">former <\/span>','Status','mouse','fff_index.php');</script></td>
        <td width="16%" align="center" valign="middle" class="style12"><script type="text/javascript">SPop('layer1','<span class="style17">DTN| <\/span><span style="color:#000000">suivre <\/span>','Status','mouse','dtn_index.php');</script></td>
        <td width="16%" align="center" valign="middle" class="style11"><a href="http://www.htfff.free.fr/dtn/forum" target="_blank"><span style="color:#000000">[<span class="style12">FORUM</span>]</span></a></td>
        <td width="16%" align="center" valign="middle" class="style11"><a href="mailto:dtn@ht-fff.org"><span class="style36">[<span class="style12">CONTACT</span>]</span></a></div></td>
        <td width="16%" align="center" valign="middle" class="style11"><a href="links.php" class="style11"><span style="color:#000000">[<span class="style12">LIENS</span>]</span></a></td>
        <td width="40" align="right"><img src="img/sous-menu_cadre_right.gif" width="10" height="40" /></td>
      </tr>
    </table>-->

    <div id="menu">
    <ul>
      <li<?php if ($nomFicPhpCourant[0]=="/index.php") {?> class="active"<?php }?>><a href="index.php" class="info"> Aanmelden <span>Meld je team aan om interessante Franse spelers te laten volgen</span></a></li>
      <li<?php if ($nomFicPhpCourant[0]=="/dtn_u20age.php") {?> class="active"<?php }?>><a href="dtn_u20age.php" class="info"> U20|Maximumleeftijd <span>Bereken de laatste wedstrijd die je speler voor de U20 kan spelen</span></a></li>
      <li<?php if ($nomFicPhpCourant[0]=="/dtn_requirement.php") {?> class="active"<?php }?>><a href="dtn_requirement.php" class="info"> Vereisten <span>Vereisten om gevolgd te worden in de Franse database</span></a></li>
      <li<?php if ($nomFicPhpCourant[0]=="/fff_help.php") {?> class="active"<?php }?>><a href="fff_help.php" class="info"> Trainers gevraagd <span>Ontvang per HT-mail bericht over interessante Franse spelers op de transfermarkt</span></a></li>
      <li<?php if ($nomFicPhpCourant[0]=="/contact.php" || $nomFicPhpCourant[0]=="/dtn_members.php" || $nomFicPhpCourant[0]=="/fff_federation.php") {?> class="active"<?php }?>><a href="contact.php" class="sous_menu"> Contact </a>
        <ul>
          <li><a href="http://www.htfff.free.fr/dtn/forum"> Forum </a></li>
          <li><a href="dtn_members.php" class="info"> Leden <span>Lijst van DTN-leden</span></a></li>
          <li><a href="fff_federation.php"> Scoutingapparaat </a></li>
        </ul>
      </li>
    </ul>
    </div>


    <?php
/*    
  if (isset($_SESSION['menutype'])){
  	if ($_SESSION['menutype']=="fff"){
  		include("menu_fff.php");
  	}else{
    		include("menu_dtn.php");
    }
  }*/
  ?>
	
<!-- TABLEAU POUR SEPARER LE MENU LANGUE DU CONTENU ET DU MENU DU BAS -->
<table border="0" cellpadding="0" cellspacing="0" align="left">
	<tr>
	  
  <!--   
  ************************************************************************************************************************
  MENU LANGUE
  ************************************************************************************************************************
  -->
  <?php
  $redir="";
  if (isset($urlsource)){
  	$redir="&urlsource=".$urlsource;
  }
  ?>
  <td valign="top" rowspan="2">
  <table border="0" cellpadding="0" cellspacing="0">
  <tr><td width="30" height="40"><img src="img/lang_01_border.gif" width="30" height="40" /></td></tr>
  <tr><td width="30" height="25"><a href="setlang.php?language=en<?=$redir?>"><img src="img/lang_02_us.gif" alt="en" title="en" width="30" height="25" border="0" /></a></td></tr>
  <tr><td width="30" height="6"><img src="img/lang_06_spacer.gif" width="30" height="6" /></td></tr>
  <tr><td width="30" height="25"><a href="setlang.php?language=es<?=$redir?>"><img src="img/lang_03_sp.gif" alt="es" title="es" width="30" height="25" border="0" /></a></td></tr>
  <tr><td width="30" height="6"><img src="img/lang_06_spacer.gif" width="30" height="6" /></td></tr>
  <!-- <tr><td width="30" height="25"><img src="img/lang_03_sp_gr.gif" alt="es" title="es" width="30" height="25" border="0" /></td></tr>-->
  <tr><td width="30" height="25"><a href="setlang.php?language=fr<?=$redir?>"><img src="img/lang_04_fr.gif" alt="fr" title="fr" width="30" height="25" border="0" /></a></td></tr>
  <tr><td width="30" height="6"><img src="img/lang_06_spacer.gif" width="30" height="6" /></td></tr>
    <tr><td width="30" height="25"><a href="setlang.php?language=de<?=$redir?>"><img src="img/lang_07_ge.gif" alt="de" title="de" width="30" height="25" border="0" /></a></td>
  </tr>
  <tr><td width="30" height="6"><img src="img/lang_06_spacer.gif" width="30" height="6" /></td></tr>
  <tr><td width="30" height="25"><a href="setlang.php?language=it<?=$redir?>"><img src="img/lang_10_it.gif" alt="it" title="it" width="30" height="25" border="0" /></a></td></tr>
  <tr><td width="30" height="6"><img src="img/lang_06_spacer.gif" width="30" height="6" /></td></tr>
  <tr><td width="30" height="25"><a href="setlang.php?language=nl<?=$redir?>"><img src="img/lang_08_nl.gif" alt="nl" title="it" width="30" height="25" border="0" /></a></td></tr>
  <tr><td width="30" height="6"><img src="img/lang_06_spacer.gif" width="30" height="6" /></td></tr>
  <tr><td width="30" height="25"><a href="setlang.php?language=sv<?=$redir?>"><img src="img/lang_05_sw.gif" alt="sv" title="sv" width="30" height="25" border="0" /></a></td></tr>
  <tr><td width="30" height="6"><img src="img/lang_06_spacer.gif" width="30" height="6" /></td></tr>
  <!-- <tr><td width="30" height="25"><a href="setlang.php?language=zh<?=$redir?>"><img src="img/lang_08_ch.gif" alt="zh" title="zh" width="30" height="25" border="0" /></a></td></tr> -->
  <!-- <tr><td width="30" height="25"><img src="img/lang_08_ch_gr.gif" alt="zh" title="zh" width="30" height="25" border="0" /></td></tr>-->
  <!-- <tr><td width="30" height="25"><a href="setlang.php?language=pt<?=$redir?>"><img src="img/lang_09_br.gif" alt="pt" title="pt" width="30" height="25" border="0" /></a></td></tr> -->
  <!-- <tr><td width="30" height="25"><img src="img/lang_09_br_gr.gif" alt="pt" title="pt" width="30" height="25" border="0" /></td></tr>-->
  <tr><td width="30" height="224"><img src="img/lang_10_version.gif" width="30" height="224" /></td></tr>
  </table>
  </td>
  
  <!--   
  ************************************************************************************************************************
  DEBUT CONTENU PAGE
  ************************************************************************************************************************
  -->
  <td height="30">&nbsp;</td></tr>
  <tr><td valign="top">
