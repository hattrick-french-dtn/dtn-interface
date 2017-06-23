<?php
require_once ("../includes/head.inc.php");
require ("../includes/serviceListesDiverses.php");
require ("../includes/serviceJoueur.php");
require ("../includes/serviceEntrainement.php");

if (!$sesUser["idAdmin"]) {
	header("location: ../index.php?ErrorMsg=Session Expiree");
}
	header("Content-type: text/csv");
if (!isset ($ordre)) 	$ordre = "idHattrickJoueur";
if (!isset ($sens)) 	$sens = "ASC";
if (!isset ($lang)) $lang = "FR";
if (!isset ($masque)) 	$masque = 0;
if (!isset ($affPosition)) 	$affPosition = 0;
if (!isset ($typeExport)) $typeExport = "maliste";

require ("../includes/langue.inc.php");

$infPos = getPosition($sesUser["idPosition_fk"]);
$huit = 60 * 60 * 24 * 8; //time_0
$quinze = 60 * 60 * 24 * 15; //time_1
$trente = 60 * 60 * 24 * 30; //time_2
$twomonths = 60 * 60 * 24 * 60; //time_3
$fourmonths = 60 * 60 * 24 * 120; //time_4

// Date du jour
$mkday = mktime(0,0,0,date('m'), date('d'),date('Y'));
  
header("Content-Disposition: attachment; filename = liste_".date('d')."-".date('m')."-".date('Y').".csv");
  
switch ($sesUser["idPosition_fk"]) {

	case "1" :
		//gK
		$k = 1;
		$keeperColor = "#9999CC";
		break;

	case "2" :
		// cD
		$d = 1;
		$defense = 1;
		$defenseColor = "#9999CC";
		break;

	case "3" :
		// Wg
		$construction = 1;
		$constructionColor = "#CCCCCC";
		$ailier = 1;
		$ailierColor = "#9999CC";
		$defense = 1;
		$defenseColor = "#CCCCCC";
		$passe = 1;
		$passeColor = "#CCCCCC";

		$wing = 1;
		$wingoff = 1;
		$wingwtm = 1;

		break;
	case "4" :
		//IM
		$m = 1;
		$moff = 1;
		$construction = 1;
		$constructionColor = "#9999CC";
		$defense = 1;
		$defenseColor = "#CCCCCC";
		$passe = 1;
		$passeColor = "#CCCCCC";
		break;

	case "5" :
		// Fw

		$att = 1;
		$passe = 1;
		$passeColor = "#9999CC";
		$buteur = 1;
		$buteurColor = "#9999CC";
		break;

	default :
		$font = "<font color = black>";
		$$font = "</font>";
		break;

}


switch ($sens) {

	case "ASC" :
		$tri = "Tri croissant";
		break;

	case "DESC" :
		$tri = "Tri decroissant";
		break;
}
?>NomJoueur;idHattrick;Date Maj DTN;Date Maj Proprio;last maj(jours);age;jours;xp;leader;spe;endu;construction;+;ailier;+;buteur;+;gardien;+;passe;+;defenseur;+;coup francs;entraineur;entrainement;DTN;note<?php
switch ($sesUser["idPosition_fk"]) {
	case "1" : //gK
?> gardien;<?php
		break;
	case "2" : // cD
?> cD;cD off;wB;wB off<?php
		break;
	case "3" : // Wg
?> Wg;Wg towards;Wg off<?php
		break;
	case "4" : //IM 
?> iM def;iM;iM off<?php
		break;
	case "5" : // Fw
?> Fw def;Fw<?php
		break;
	default :
?> gK;cD;iM;Wg off;Fw<?php
		break;
}
echo "\n";

//correction bug age joueurs par jojoje86 le 20/07/09
$AgeAnneeSQL=getCalculAgeAnneeSQL();
$AgeJourSQL=getCalculAgeJourSQL();

$sql = "select $tbl_joueurs.*,".$AgeAnneeSQL." as AgeAn,".$AgeJourSQL." as AgeJour ";

$listEntrainement = listEntrainement();

if ($typeExport=="maliste") {$sql .=" from $tbl_joueurs where dtnSuiviJoueur_fk  = ".$sesUser["idAdmin"]." and affJoueur = 1  order by $ordre $sens";}
if ($typeExport=="recherche") {$sql .=stripslashes(urldecode($laSelection)).$ordre;}
if ($typeExport=="unjoueur") {$sql .="from $tbl_joueurs where idHattrickJoueur = ".$idPlayer;}

foreach ($conn->query($sql) as $l) {
	$infJ = getJoueur($l["idJoueur"]);
	$date = explode("-",$infJ["dateDerniereModifJoueur"]);

	$mkJoueur =  mktime(0,0,0,$date[1],$date[2],$date[0]); 
	$datesaisie = explode("-",$infJ["dateSaisieJoueur"]);
	$mkSaisieJoueur= mktime(0,0,0,$datesaisie[1],$datesaisie[2],$datesaisie[0]);
	if ($mkSaisieJoueur>$mkJoueur){
		$datemaj=$mkSaisieJoueur;
	}else{
		$datemaj=$mkJoueur;
	}

?><?=strtolower($l["prenomJoueur"])?><?=strtolower($l["nomJoueur"])?>;<?php
	echo $l["idHattrickJoueur"].";";
	echo $l["dateDerniereModifJoueur"].";";
	echo $l["dateSaisieJoueur"].";";
	echo round(($mkday - $datemaj)/(60*60*24) ).";";
	echo $l["AgeAn"].";";
	echo $l["AgeJour"].";";
	echo $l["idExperience_fk"].";";
	echo $l["idLeader_fk"].";";
	echo $specabbrevs[$l["optionJoueur"]].";";
	echo $l["idEndurance"].";";


	echo $l["idConstruction"].";".$infJ["nbSemaineConstruction"].";"; 
	echo  $l["idAilier"].";".$infJ["nbSemaineAilier"].";";
	echo  $l["idButeur"].";".$infJ["nbSemaineButeur"].";";
	echo  $l["idGardien"].";".$infJ["nbSemaineGardien"].";";
	echo  $l["idPasse"].";".$infJ["nbSemainePasses"].";"; 
	echo  $l["idDefense"].";".$infJ["nbSemaineDefense"].";";
	echo  $l["idPA"].";";

	echo $infJ["niv_Entraineur"].";";
	$nom_entrainement=getEntrainementName($infJ["entrainement_id"],$listEntrainement);
	echo $nom_entrainement.";";
	if ($infJ["dtnSuiviJoueur_fk"] != 0)	{echo $infJ["loginAdminSuiveur"].";";}
	else echo ";";

	switch ($sesUser["idPosition_fk"]) {
		case "1" : //gK
			echo $l["scoreGardien"].";";
			break;
		case "2" : // cD
			echo $l["scoreDefense"].";";
			echo $l["scoreDefCentralOff"].";";
			echo $l["scoreDefLat"].";";
			echo $l["scoreDefLatOff"].";";
			break;
		case "3" : // Wg
			echo $l["scoreAilier"].";";
			echo $l["scoreAilierVersMilieu"].";";
			echo $l["scoreAilierOff"].";";
			break;
		case "4" : //IM 
			echo $l["scoreMilieuDef"].";";
			echo $l["scoreMilieu"].";";
			echo $l["scoreMilieuOff"].";";
			break;
		case "5" : // Fw
			echo $l["scoreAttaquantDef"].";";
			echo $l["scoreAttaquant"].";";
			break;
		default :
			echo $l["scoreGardien"].";";
			echo $l["scoreDefense"].";";
			echo $l["scoreMilieu"].";";
			echo $l["scoreAilierOff"].";";
			echo $l["scoreAttaquant"].";";
			break;
	}
	echo "\n";
}

deconnect();