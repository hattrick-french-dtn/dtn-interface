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

switch($_SESSION['lang']) {
	case "fr" :
	$phrase1 = "Le but du projet est tr&egrave;s simple : <strong>trouver des repreneurs pour les meilleurs potentiels et les joueurs des s&eacute;lections nationales fran&ccedil;aises </strong>
	dont la vente interviendra &agrave; un moment ou &agrave; un autre. Si vous &ecirc;tes int&eacute;ress&eacute; par la formation d'un joueur fran&ccedil;ais 
	de fa&ccedil;on &agrave; l'aider &agrave; atteindre une de nos &eacute;quipes nationales, vous &ecirc;tes ici chez vous.";
	$phrase2 = "Comme vous pouvez l'imaginer, nous avons des exigences primordiales, comme le niveau de l'entra&icirc;neur (honorable mini) et 
le nombre d'entra&icirc;neurs adjoints, mais le fondement le plus important est votre volont&eacute; &agrave; aider la France-HT.
Si vous pensez que ce projet vous correspond, inscrivez-vous directement via le forum Hattrick en cliquant sur le lien suivant :";
	$lien = "[U20|A] Recherche Entraineurs Internationaux (sujet sur le forum France)";
	break;
	case "de" :
	$phrase1 = "Das Ziel des Projekts ist ganz einfach: <strong>Erwerber zu finden, f&uuml;r Spieler, die das Potenzial f&uuml;r franz&ouml;sischen Nationalmannschaften </strong>
	haben und verkauft werden. Wenn Sie daran interessiert sind, einen franz&ouml;sischen Spieler zu trainieren, der ihm hilft, 
	eine unserer Nationalmannschaften zu erreichen, sind Sie hier am richtigen Ort.";
	$phrase2 = "Nat&uuml;rlich haben wir einige sehr wichtige Anforderungen, z.B. die Trainerf&auml;higkeit (mindestens gut) 
une die F&aumlhigkeit der Assistenztrainer, aber die wichtigste Anforderung ist deine Bereitschaft, HT-Frankreich zu hilfen.
Wenn du an diesem Projekt teilnehmen möchtest, melden Sie sich direkt im Hattrick-Forum an, indem Sie auf den folgenden Link klicken :";
	$lien = "[U20|A] Recherche Entraineurs Internationaux (Thema im Forum Frankreich)";
	break;
	case "it" :
		$phrase1 = "L'obiettivo del progetto &egrave; molto semplice: <strong>trovare acquirenti per i migliori potenziali giocatori e giocatori delle selezioni nazionali francesi </strong>
	la cui vendita avverr&agrave; in un momento o nell'altro. Se sei interessato ad allenare un giocatore francese e 
	ad aiutarlo a raggiungere una delle nostre squadre nazionali, sei qui a casa.";
	$phrase2 = "Come puoi immaginare, abbiamo alcuni requisiti di base, come il livello dell'allenatore (mini buono, eccellente essendo il top)  
ed uno staff perfetto, ma la base pi&ugrave; importante &egrave; la tua volont&agrave; di aiutare la HT-Francia.
Se ritieni che questo progetto sia adatto a te, registrati direttamente tramite il forum Hattrick cliccando sul seguente link:";
	$lien = "[U20|A] Recherche Entraineurs Internationaux (thread sul foro nazionale della Francia)";
	break;
	case "es" :
		$phrase1 = "El objetivo del proyecto es muy simple: <strong>encontrar compradores para los mejores potenciales y tambi&eacute;n los jugadores de las selecciones nacionales francesas</strong>
	que se encontraran en venta en alg&uacute;n momento futuro. Si est&aacute;s interesado en entrenar un jugador franc&eacute;s 
	e manera que le ayude a llegar a unos de nuestros equipos nacionales, te sentir&aacute;s como en casa.";
	$phrase2 = "Como podr&aacute;s imaginar, tenemos exigencias principales, como el nivel del entrenador (bueno m&iacute;nimo) y 
el n&uactute;mero de entrenadores asistentes, pero el fundamento el m&aacute;s importante es tu voluntad de ayudar HT-Francia.
Si te parece que coincides con este proyecto, regístrate directamente en el foro Hattrick haciendo clic en el siguiente enlace:";
	$lien = "[U20|A] Recherche Entraineurs Internationaux (hilo en el foro Francia)";
	break;
	default :
		$phrase1 = "The goal of this project is very simple : <strong>find owners for the best potential players and the current players of French national teams</strong>,
	who may be sold sometimes. If you are interested by the training of a French player, in order to help him to reach one of our national teams, make yourself at home.";
	$phrase2 = "As you can imagine, we have very high demands, such as the level of the coach (solid is a very strict minimum, excellent is better) and a perfect staff,
	but the most important is the will to help HT-France.
	If you think this project may suit you, you can subscribe directly in Hattrick thanks to the following link:";
	$lien = "[U20|A] Recherche Entraineurs Internationaux (thread on France national forum)";
	break;
}
?>

<div align="center"><img src="./img/A.PNG"><br><br></div>
<p><?php echo($phrase1)?> <br/><br/>
	<?php echo($phrase2)?><br/>
	<strong><u><a href="https://www.hattrick.org/goto.ashx?path=/Forum/Read.aspx?t=17238176&n=1&v=0"><?php echo($lien)?></a></u></strong></p>

<?php 
include("menu_bas.php");
?>
