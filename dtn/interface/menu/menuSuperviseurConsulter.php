
<table border=0 width=100%>
<tr><td align="left" nospan> [Base de donn&eacute;es]<br>
	&nbsp;<A class="smliensorange" href="<?=$url?>/joueurs/toplist.php" alt="Top Liste">Top Listes</a>&nbsp;|
	&nbsp;<A class="smliensorange" href="<?=$url?>/consulter/topsPublics.php" alt="clubs">Top publics</a>&nbsp;|
	&nbsp;<A class="smliensorange" href="<?=$url?>/consulter/topsPotentiels.php" alt="clubs">Top potentiels</a>&nbsp;|
	&nbsp;<A class="smliensorange" href="<?=$url?>/joueurs/addPlayer.php" >Ajouter joueur</a>&nbsp;|
	&nbsp;<A class="smliensorange" href="<?=$url?>/consulter/rechercheJoueur.php" >Recherche</a>&nbsp;|
	&nbsp;<A class="smliensorange" href="<?=$url?>/joueurs/verifPlayer.php" >Chercher joueur</a>&nbsp;|
	&nbsp;
	<?php if ($sesUser["idPosition_fk"]!=0) { ?>
		<A class="smliensorange" href="<?=$url?>/joueurs/purgeJoueurs.php" >Purge</a>&nbsp;|
	&nbsp;
	<?php } ?>
	<A class="smliensorange" href="<?=$url?>/joueurs/ficherecupchoix.php?origine=<?php echo "menu"?>" alt="Fiches r&eacute;sum&eacute;s">Fiches r&eacute;sum&eacute;s</a>&nbsp;|
	&nbsp;
	<?php if ($sesUser["idPosition_fk"]!=0) { ?>
		<A class="smliensorange" href="<?=$url?>/joueurs/checkPlayer.php"  >Modifier joueur</a>&nbsp;|
        &nbsp;<A class="smliensorange" href="<?=$url?>/admin/index.php" >M&agrave;J Secteur</a>&nbsp;|
        &nbsp;<A class="smliensorange" href="<?=$url?>/admin/index2.php" >M&agrave;J Archiv&eacute;s</a>&nbsp;|
	<?php }
	      if($sesUser["idNiveauAcces_fk"] == 1) { ?>
	&nbsp;<A class="smliensorange" href="<?=$url?>/admin/index3.php" >M&agrave;J Hebdo (+1)</a>&nbsp;|
	&nbsp;<A class="smliensorange" href="<?=$url?>/admin/datemaj.php" >Dates M&agrave;J DTNs+</a>&nbsp;|
	<?php }?>
	&nbsp;<A class="smliensorange" href="<?=$url?>/clubs/liste_clubs.php">Clubs</a>&nbsp;|
    &nbsp;<A class="smliensorange" href="<?=$url?>/consulter/rapportMatchs.php">Rapports Matchs</a>&nbsp;|&nbsp;
	<?php if($sesUser["idNiveauAcces_fk"] == 1 || $sesUser["idNiveauAcces_fk"] == 2 || $sesUser["selection"] != "") { ?>
	<A class="smliensorange" href="<?=$url?>/pays/index.php"  >Pays</a>&nbsp;
	<?php } ?>
</td></tr></table>
