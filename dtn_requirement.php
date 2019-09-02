<?php
// Variable paramétrage de la page
$nomFicPhpCourant = explode("?",$_SERVER['REQUEST_URI']);
$urlsource = $nomFicPhpCourant[0]; // Utilisé pour setlang.php
$callbackUrl="http://".$_SERVER['HTTP_HOST'].$nomFicPhpCourant[0]."?mode=retour"; // Url de retour après authentification sur HT

include("init.php");
require("dtn/interface/includes/serviceMatchs.php");
//require("dtn/interface/includes/connect.inc.php");
require("inc/fct_requirements.php"); 


switch($_SESSION['lang']) {
	case "fr" :
	$titre = "https://www.ht-dtnfff.fr/| DTN| U20&amp;A | pr&eacute;-requis.";
	$phraseMinima1 = "Les minimas sont mis à jour r&eacute;guli&egrave;rement par la DTN.";
	$phraseMinima2 = "Ils fonctionnent &agrave; partir des comp&eacute;tences et potentiels HTMS de vos joueurs.";
	$phraseMinima3 = "En cas de doute, ne pas h&eacute;siter pas à contacter la DTN du secteur de votre joueur pour savoir comment il se situe vis-&agrave;-vis de nos pr&eacute;requis";
	break;
	case "de" :
	$titre = "https://www.ht-dtnfff.fr/| DTN| U20&amp;A | Anforderungen.";
	$phraseMinima1 = "Die Mindesteanforderungen werden vom Scout-Team regelm&auml;ßig aktualisiert.";
	$phraseMinima2 = "Sie basieren auf den HTMS-Fähigkeiten und -Potenzialen Ihrer Spieler.";
	$phraseMinima3 = "Im Zweifelsfalle wenden Sie sich bitte an der Scout des Sektors deines Spielers. Er kann Ihnen sagen, wie Ihr Spieler in Bezug auf unsere Mindestanforderungen steht (siehe Men&uuml; 'Kontakt/Mitglieder').";
	break;
	case "it" :
	$titre = "https://www.ht-dtnfff.fr/| DTN| U20&amp;A | requisiti.";
	$phraseMinima1 = "I requisiti sono aggiornati regolarmente dagli capiscout.";
	$phraseMinima2 = "Sono basati sui valori e i potenziali HTMS dei vostri giocatori.";
	$phraseMinima3 = "In caso di dubbio, non esitare a contattare lo scout del settore del vostro giocatore, per sapere come si trova il tuo giocatore riguardo ai nostri requisiti (nel menu Contattaci / Membri).";
	break;
	case "es" :
	$titre = "https://www.ht-dtnfff.fr/| DTN| U20&amp;A | requisitos.";
	$phraseMinima1 = "La DTN actualiza con regularidad los requisitos mínimos.";
	$phraseMinima2 = "Funcionan con base en las habilidades y potenciales de tus jugadores.";
	$phraseMinima3 = "Si tienes alguna duda, no dudes en contactar la DTN del sector du jugador para obtener m&aacute;s información (Contacto / Miembros).";
	break;
	case "sv" :
	$titre = "https://www.ht-dtnfff.fr/| DTN| U20&amp;A | krav.";
	$phraseMinima1 = "Kraven uppdateras av och till av huvudscouterna.";
	$phraseMinima2 = "De &auml;r baserade p&aring; HTMS-v&auml;rden och potentialer hos dina spelare.";
	$phraseMinima3 = "Om du undrar n&aring;got, tveka inte att kontakta aktuell scout f&ouml;r din spelares lagdel f&ouml;r att f&aring; reda p&aring; mer om hur din spelare motsvarar v&aring;ra krav (se menyn Kontakt / Medlemmar).";
	break;
	default :
	$titre = "https://www.ht-dtnfff.fr/| DTN| U20&amp;A | requirements.";
	$phraseMinima1 = "Requirements are periodically updated by head scouts.";
	$phraseMinima2 = "They are based on HTMS values and potentials of your players.";
	$phraseMinima3 = "In case of doubt, do not hesitate to contact the scout of the sector of your player to know more about the position of your player vs our requirements (see menu Contact / Members).";
	break;
}
?>

<table width="100%" border="0" cellspacing="0" cellpadding="5" >

      <div align="center"><img src="./img/A.PNG"><br><br></div>

		<p> <?php echo($phraseMinima1)?> </p>
		<p> <?php echo($phraseMinima2)?> </p>
		<p> <?php echo($phraseMinima3)?> </p>

</table>	 

<?php
include("menu_bas.php");
?>