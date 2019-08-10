
<table border=0 width=100%>
<tr><td align="left" nospan>[gestion]  <br>        
<A class="smliensorange" href="<?=$url?>/equipe/superviseur.php">DTN +</a>&nbsp;|
	&nbsp;<A class="smliensorange" href="<?=$url?>/equipe/dtn.php">DTN</a>&nbsp;|
    &nbsp;<A class="smliensorange" href="<?=$url?>/equipe/selectionneur.php">S&eacute;lectionneur</a>&nbsp;|
	&nbsp;<A class="smliensorange" href="<?=$url?>/joueurs/liste.php" alt="Liste">Joueurs avec poste ind&eacute;fini</a>&nbsp;|
	&nbsp;<A class="smliensorange" href="<?=$url?>/joueurs/attribution.php" alt="Attributions">Attributions</a>&nbsp;|
	&nbsp;<A class="smliensorange" href="<?=$url?>/settings.php?affinfoPerso=1" alt="configuration">Mon Profil</a>&nbsp;|
  	&nbsp;<A class="smliensorange" href="<?=$url?>/admin/requirements.php" alt="minima">Minima</a>&nbsp;  
  
  <?php
  if($sesUser["idNiveauAcces_fk"] == 1 || $sesUser["idNiveauAcces_fk"] == 2 || $sesUser["selection"] != ""){
  ?>&nbsp;|&nbsp;<A class="smliensorange" href="<?=$url?>/settings.php?affCoeff=1" alt="configuration">Mes formules de calcul</a>&nbsp;
  <?php } ?>
  </td>
</tr></table>
