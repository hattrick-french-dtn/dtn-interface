
<table border=0 width=100%>
<tr><td align="left" nospan>          
	&nbsp;<a class="smliensorange" href="<?=$url?>/equipe/mesdtns.php" alt="Mes DTNs">Mes DTNs</a>&nbsp;|
	&nbsp;<a class="smliensorange" href="<?=$url?>/joueurs/liste.php" alt="Liste">Joueurs avec poste ind&eacute;fini</a>&nbsp;|
	&nbsp;<a class="smliensorange" href="<?=$url?>/joueurs/attribution.php" alt="Attributions">Attributions aux DTN</a>&nbsp;|
	&nbsp;<a class="smliensorange" href="<?=$url?>/settings.php?affinfoPerso=1" alt="configuration">Mon Profil</a>
  <?php
  if($sesUser["idNiveauAcces_fk"] == 1 || $sesUser["idNiveauAcces_fk"] == 2 || $sesUser["selection"] != ""){
  ?>&nbsp;|&nbsp;<a class="smliensorange" href="<?=$url?>/settings.php?affCoeff=1" alt="configuration">Mes formules de calcul</a>
  <?php } ?>  
</td></tr></table>