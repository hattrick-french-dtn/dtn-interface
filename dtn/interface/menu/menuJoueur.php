<?php
if(isset($idHT)) {
	
	global $infJ;

/***********************************************
* Type de menus :
* 1- Menu Complet (Fiche consultation |  Fiche DTN |  Graphiques |  Histo modifs |  Club |  Fiche Forum |  Fiche Résumé  |  Etoiles |  Commentaires / Notes   |  Chercher un joueur )
* 2- Menu Complet sans "chercher un joueur" ( Fiche consultation |  Fiche DTN |  Graphiques |  Histo modifs |  Club |  Fiche Forum |  Fiche Résumé  |  Etoiles |  Commentaires / Notes)
* 3- Menu de consultation  ( Fiche consultation |  Fiche Forum |  Fiche Résumé )
* Menu 1 pour les sélectionneur et les admins        
* Menu 2 - Si le joueur appartient au secteur du dtn ou dtn+
*        - Si le joueur n'appartient à aucun secteur
*        - Si le dtn + a accès à tous les secteurs
* Menu 3 - Si joueur n'appartient pas au secteur du dtn ou dtn+
*        - Si le dtn a accès à tous les secteurs             
***********************************************/
if (($sesUser["idNiveauAcces"]=="1") or ($sesUser["idNiveauAcces"]=="4")) {$TypeMenu=1;}
else {
      if (($sesUser["idPosition_fk"] == $infJ["ht_posteAssigne"]) or ($infJ["ht_posteAssigne"]==0) or ($sesUser["idPosition_fk"]==0 and $sesUser["idNiveauAcces"]=="2") ) {$TypeMenu=2;}
      else {$TypeMenu=3;}
}
//Un DTN ne doit pas pouvoir modifier la fiche de son propre joueur par Musta56 le 28/07/2009 => http://www.ht-fff.org/bug/view.php?id=98
if ($sesUser["idAdminHT"] == $idClubHT) {$TypeMenu=3;}



?>
<table border=0 width=100%>
<tr><td align="left" nospan>&nbsp;<a href="<?=$url?>/joueurs/fiche.php?htid=<?=$idHT?>" class="smliensorange" alt="consulter">Fiche consultation</a>&nbsp;|
<?php // On affiche le menu fiche dtn que si le joueur appartient au secteur du dtn ou si c'est le sélectionneur qui est connecté
if ($TypeMenu <= 2) { ?>
	&nbsp;<A class="smliensorange" href="<?=$url?>/joueurs/ficheDTN.php?htid=<?=$idHT?>" alt="modifier">Fiche DTN</a>&nbsp;|
	&nbsp;<A class="smliensorange" href="<?=$url?>/joueurs/graphProgression.php?id=<?=$id?>" alt="graphiques">Graphiques</a>&nbsp;|
<?php } ?>
	&nbsp;<A class="smliensorange" href="<?=$url?>/joueurs/histoJoueur.php?htid=<?=$idHT?>" alt="Histos">Histo modifs</a>&nbsp;|
<?php if ($TypeMenu <= 2) { ?>
	&nbsp;<A class="smliensorange" href="<?=$url?>/clubs/fiche_club.php?idClubHT=<?=$idClubHT?>" alt="Club">Club</a>&nbsp;|
<?php } ?>
	&nbsp;<A class="smliensorange" href="<?=$url?>/joueurs/ficheForum.php?htid=<?=$idHT?>" alt="forum">Fiche Forum</a>&nbsp;|
	&nbsp;<a class="smliensorange" href="<?=$url?>/joueurs/ficheresumechoix.php?htid=<?=$idHT?>&origine=<?php echo "unique"?>" alt="resume">Fiche R&eacute;sum&eacute;</a>&nbsp;
<?php if ($TypeMenu <= 2) { ?>
  | &nbsp;<A class="smliensorange" href="<?=$url?>/joueurs/rapportDetaille.php?htid=<?=$idHT?>" alt="etoiles">Matchs</a>&nbsp;|
    &nbsp;<A class="smliensorange" href="<?=$url?>/joueurs/commentaires.php?htid=<?=$idHT?>" alt="commentaires">Commentaires / Notes</a>
<?php }

if ($TypeMenu <= 1) {  ?>

&nbsp;|
	&nbsp;<A class="smliensorange" href="<?=$url?>/joueurs/verifPlayer.php" >Chercher un joueur</a>&nbsp;<?php } ?><!-- &nbsp;|
	&nbsp;<A class="smliensorange" href="<?=$url?>/joueurs/rapportDetailleModif.php?fonction=insert&id=<?=$id?>" class="btn" alt="ajout match">Ajout Match</a>&nbsp;-->
</td></tr></table>
<?php
}
?>
