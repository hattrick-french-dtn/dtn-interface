<?php
// Variable paramétrage de la page
$nomFicPhpCourant = explode("?",$_SERVER['REQUEST_URI']);
$urlsource = $nomFicPhpCourant[0]; // Utilisé pour setlang.php
$callbackUrl="http://".$_SERVER['HTTP_HOST'].$nomFicPhpCourant[0]."?mode=retour"; // Url de retour après authentification sur HT
//$file="members"; // Nom du fichier pour include

require_once("dtn/interface/includes/head.inc.php");

include("init.php");
require("dtn/interface/includes/serviceJoueur.php");
require("dtn/interface/includes/serviceMatchs.php");
require("dtn/interface/includes/serviceiihelp.php");
require("dtn/interface/includes/serviceListesDiverses.php");
require_once "dtn/interface/_config/CstGlobals.php"; 
require_once "dtn/interface/fonctions/AccesBase.php"; // fonction de connexion a la base

?>
<div align="center"><img src="./img/A.PNG"><br><br></div>
<p>Le but du projet est tr&egrave;s simple : <strong>trouver des repreneurs pour les meilleurs potentiels et les joueurs des s&eacute;lections nationales fran&ccedil;aises </strong>
dont la vente interviendra &agrave; un moment ou &agrave; un autre. Si vous &ecirc;tes int&eacute;ress&eacute; par la formation d'un joueur fran&ccedil;ais 
de fa&ccedil;on &agrave; l'aider &agrave; atteindre une de nos &eacute;quipes nationales, vous &ecirc;tes ici chez vous. <br/><br/>
Comme vous pouvez l'imaginer, nous avons des exigences primordiales, comme le niveau de l'entra&icirc;neur (honorable mini) et 
le nombre d'entra&icirc;neurs adjoints, mais le fondement le plus important est votre volont&eacute; &agrave; aider la France-HT.
Si vous pensez que ce projet vous correspond, inscrivez-vous directement via le forum Hattrick en cliquant <strong><a href="https://www.hattrick.org/goto.ashx?path=/Forum/Read.aspx?t=17238176&n=1&v=0">ICI</a>.</strong></p>

<?php 
include("menu_bas.php");
?>
