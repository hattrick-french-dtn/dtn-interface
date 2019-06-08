<?php 
require_once("../includes/head.inc.php");
require("../includes/serviceListesDiverses.php");

if(!$sesUser["idAdmin"])
{
	header("location: index.php?ErrorMsg=Session Expir�");
}
if(!isset($ordre)) $ordre = "nomJoueur";
if(!isset($sens)) $sens = "ASC";
if(!isset($lang)) $lang = "FR";
if(!isset($masque)) $masque = 0;
if(!isset($affPosition)) $affPosition = 0;

require("../includes/langue.inc.php");

switch($sesUser["idNiveauAcces"]){
	case "1":
		require("../menu/menuAdmin.php");
		break;
		
	case "2":
		require("../menu/menuSuperviseur.php");
		break;

	case "3":
		require("../menu/menuDTN.php");
		break;
		
	case "4":
		require("../menu/menuCoach.php");
		break;
		
	default;
		break;

}





?><title>Liens</title>
<body >
<br>
<br>

<center><h3>Liens pratiques</h3></center>
<p>

<ul>
<li><a href="http://www.htfff.free.fr/dtn/forum/index.php">Forum de la DTN</a><br>
<br>
<li><a href="https://github.com/hattrick-french-dtn/dtn-interface/issues">Signaler un bug, demander une am&eacute;lioration</a><br>
<br>
<li><a href="http://www.htfff.free.fr/dtn/forum/viewtopic.php?f=15&t=9307">Organigramme de la DTN</a><br>
<br>
<li><a href="https://www.hattrick.org/goto.ashx?path=/Forum/Read.aspx?t=17238176&n=1&v=0">Sujet Recherche Entra&icirc;neurs sur HT</a><br>
<br>
<li><a href="http://www.htfff.free.fr/dtn/forum/viewtopic.php?f=15&t=7725">Le manuel non-officiel de HT</a><br>
<br>
<li><a href="http://www.htfff.free.fr/dtn/forum/viewtopic.php?f=33&t=11667">Les cibles U20</a><br>
<br>
<li><a href="http://www.htfff.free.fr/dtn/forum/viewtopic.php?f=33&t=10229">Lees cibles A</a><br>
<br>
<?php 
switch($sesUser["idNiveauAcces"]){
    /* case 1 = Admin DTN si besoin */

	case "2": /* DTN+ */
?>
<li><a href="http://www.htfff.free.fr/dtn/forum/viewtopic.php?f=13&t=7827">Le manuel du parfait DTN+</a><br>
<br>
<?php
		break;

	case "3": /* DTN */
?>
<li><a href="http://www.htfff.free.fr/dtn/forum/viewtopic.php?f=15&t=7725">Le manuel du parfait DTN</a><br>
<br>
<?php
		break;
		
	case "4": /* Coach */
?>
<li><a href="http://s64-65.leforum.eu/f6-Les-outils.htm">Les outils</a><br>
<br>
<li><a href="http://www.htfff.free.fr/dtn/forum/viewtopic.php?f=3&t=9551">Mettre à jour la BDD en tant que s&eacute;lectionneur</a><br>
<br>
<?php
		break;
		
	default;
		break;
}
?>
</ul>
</body>
</html>
