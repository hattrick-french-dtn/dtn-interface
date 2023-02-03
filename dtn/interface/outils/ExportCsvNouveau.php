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
?>NomJoueur;idHattrick;last maj(jours);age;jours;tsi;salaire;xp;leader;spe;endu;tx endu;forme;intensite;gardien;+;defense;+;construction;+;ailier;+;passe;+;buteur;+;coup francs;+;entraineur;entrainement;DTN;id manager;Nom manager;Date de dernière connexion;Pays du club;Nombre adjoints;préparateur physique;Médecin;Joueur en vente;Date du jour;<?php
?>comp HTMS;pot HTMS<?php
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
    
    // Extraction tsi et transferListed
            $sql4= "SELECT tsi,transferListed,forme FROM $tbl_joueurs_histo
                   WHERE id_joueur_fk=".$l["idHattrickJoueur"]." 
                   ORDER BY date_histo DESC LIMIT 1";
            $req4 = $conn->query($sql4);
            $ligne4 = $req4->fetch(PDO::FETCH_ASSOC);
            if (is_array($ligne4))
            extract($ligne4);

    // Extraction taux d'endurance du joueur
            $endurance="-";
            $sql2 = "SELECT endurance,intensite,adjoints,physio,medecin FROM $tbl_clubs_histo 
                    WHERE idClubHT = ".$l["teamid"]."
                    ORDER BY date_histo DESC LIMIT 1";
            $req2 = $conn->query($sql2);
            $ligne2 = $req2->fetch(PDO::FETCH_ASSOC);
            if (is_array($ligne2))
            extract($ligne2);
           
		     // Extraction Id manager,date de dernière connexion et le nom du pays du club
            $idUserHT="-";
            $sql5 = "SELECT idUserHT,date_last_connexion,nomAnglais,idPays_fk,nomUser
				FROM 
					$tbl_pays   P,
					$tbl_clubs  C
				WHERE idClubHT = ".$l["teamid"]."
				AND C.idPays_fk = P.idPays
				LIMIT 1";

            $req5 = $conn->query($sql5);
            $ligne5 = $req5->fetch(PDO::FETCH_ASSOC);
            if (is_array($ligne5))
            extract($ligne5);
		
		  
		   
		   $mkJoueur =  mktime(0,0,0,$date[1],$date[2],$date[0]); 
	$datesaisie = explode("-",$infJ["dateSaisieJoueur"]);
	$mkSaisieJoueur= mktime(0,0,0,$datesaisie[1],$datesaisie[2],$datesaisie[0]);
	if ($mkSaisieJoueur>$mkJoueur){
		$datemaj=$mkSaisieJoueur;
	}else{
		$datemaj=$mkJoueur;
	}
	$htms = htmspoint($l["AgeAn"], $l["AgeJour"], $l["idGardien"], $l["idDefense"], $l["idConstruction"], $l["idAilier"], $l["idPasse"], $l["idButeur"], $l["idPA"]);
	
	
?><?=utf8_decode($l["prenomJoueur"])?> <?=utf8_decode($l["nomJoueur"])?>;<?php
	echo $l["idHattrickJoueur"].";";
	echo round(($mkday - $datemaj)/(60*60*24) ).";";
	echo $l["AgeAn"].";";
	echo $l["AgeJour"].";";
	echo $tsi.";";
	echo $l["salary"].";";
	echo $l["idExperience_fk"].";";
	echo $l["idLeader_fk"].";";
	echo $specabbrevs[$l["optionJoueur"]].";";
	
	if ($l['isScannable']==0) { //Si on ne possède pas le scan du joueur
        $l["idEndurance"]="??";
		$endurance="??";
		$forme="??";
		$intensite="??";
    }
	
	echo $l["idEndurance"].";";
    echo $endurance."%;";
	echo $forme.";";
	echo $intensite.";";
	
	echo  $l["idGardien"].";".$infJ["nbSemaineGardien"].";";
	echo  $l["idDefense"].";".$infJ["nbSemaineDefense"].";";
	echo  $l["idConstruction"].";".$infJ["nbSemaineConstruction"].";"; 
	echo  $l["idAilier"].";".$infJ["nbSemaineAilier"].";";
	echo  $l["idPasse"].";".$infJ["nbSemainePasses"].";";
	echo  $l["idButeur"].";".$infJ["nbSemaineButeur"].";";
	echo  $l["idPA"].";".$infJ["nbSemaineCoupFranc"].";";

	echo $infJ["niv_Entraineur"].";";
	if ($l['isScannable']==0) { //Si on ne possède pas le scan du joueur
        $nom_entrainement="??";
    } else {
        $nom_entrainement=utf8_decode(getEntrainementName($infJ["entrainement_id"],$listEntrainement));
    }
	echo $nom_entrainement.";";
	if ($infJ["dtnSuiviJoueur_fk"] != 0)	{echo utf8_decode($infJ["loginAdminSuiveur"]).";";}
	else echo ";";

	//Idt manager
	echo $idUserHT.";";
	echo $nomUser.";";
	echo $date_last_connexion.";";
	
	if ($idPays_fk == 126) echo "Cote d'Ivoire;";
	else echo $nomAnglais.";";	
	
	
	echo $adjoints.";";
	echo $physio.";";
	echo $medecin.";";
	if($transferListed==1) {
		echo "En vente;";
	} else {
		echo ";";
	}
	
	//echo $mkday.";";
	$date = date("d-m-Y").";";
	echo $date;
		
	echo $htms["value"].";".$htms["potential"].";";
		
	echo "\n";
}

deconnect();
