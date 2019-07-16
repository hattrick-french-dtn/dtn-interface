
En tant que superviseur DTN+, vous pouvez acc&eacute;der aux diff&eacute;rentes rubriques suivantes :<p>

<?php if ($sesUser["idPosition_fk"]!=8)
	{ ?>
<span class="breadvar">Via le menu <a href="<?=$url?>/equipe/mesdtns.php">"Gestion"</a></span> : <br>
<ul>
<li>Consulter la liste de vos DTN (et leurs derni&egrave;res actions)
<li>Affecter des postulants DTN dans votre secteur
<li>Assigner un nouveau joueur &agrave; votre secteur de jeu 
<li>Attribuer un joueur &agrave; un DTN
<li>Modifier vos informations personnelles
<li>Modifier le param&eacute;trage des notes de joueurs
<li>Acc&eacute;der &agrave; iiihelp!
</ul>


<span class="breadvar">Via le menu <a href="<?=$url?>/national_team/team.php?selection=A">"&Eacute;quipes Nationales"</a></span> : <br>
<ul>
<li>Consulter les joueurs de l'&eacute;quipe nationale
<li>Consulter les joueurs de l'&eacute;quipe U20
</ul>
<?php } ?>

<span class="breadvar">Via le menu <a href="<?=$url?>/joueurs/toplist.php">"Base de donn&eacute;es"</a></span> : <br>
<ul>
<li>Retrouver les Listes des tops de votre secteur
<li>Ajouter un joueur
<li>Tester l'existence d'un joueur dans la base 
<li>Consulter les joueurs de la base
<li>Modifier les joueurs de votre secteur de jeu
<li>Consulter / modifier les listes de Clubs de la base
<li>Consulter / modifier les listes de Pays de la base
</ul>

</p>
<span class="breadvar">Via le menu <a href="<?=$url?>/consulter/messagesMaListe.php">"Messages Proprios"</a></span> : <br>
<ul>
<li>Consulter les messages des propri&eacute;taires de joueurs que j'ai en suivi
<li>Consulter les messages des propri&eacute;taires de joueurs assign&eacute;s &agrave; mon secteur
</ul>
</p>

<?php if ($sesUser["idPosition_fk"]!=8)
	{ ?><span class="breadvar">Via le lien <a href="<?=$url?>/maliste/maliste.php">"Ma liste"</a></span> : <br>
<ul>
<li>Retrouver les joueurs que je surveille
</ul>
</p><?php } ?>
<span class="breadvar">Via le lien <a href="<?=$url?>/aide/contact.php">"Liens utiles"</a></span> : <br>
<ul>
<li>Retrouver les liens / contacts / aides utiles...
</ul>
</p>
</td></tr></table>
</center>
</body>
</html>
