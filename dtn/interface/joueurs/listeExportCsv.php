<?php 
require("../includes/head.inc.php");
require("../includes/serviceJoueur.php");
require("../includes/serviceListesDiverses.php");
require("../includes/serviceDTN.php");
if(!$sesUser["idAdmin"])
	{
	header("location: ../index.php?ErrorMsg=Session Expiree");
	}
	header("Content-type: text/csv");
if(!isset($ordre)) $ordre = "nomJoueur";
if(!isset($sens)) $sens = "ASC";
if(!isset($lang)) $lang = "FR";
if(!isset($masque)) $masque = 0;
if(!isset($affPosition)) $affPosition = 0;

require("../includes/langue.inc.php");



//

			  $huit = 60 * 60 * 24 * 8; //time_0
			  $quinze = 60 * 60 * 24 * 15; //time_1
			  $trente = 60 * 60 * 24 * 30; //time_2
			  $twomonths = 60 * 60 * 24 * 60; //time_3
			  $fourmonths = 60 * 60 * 24 * 120; //time_4
			  
			  // Date du jour
			 $mkday = mktime(0,0,0,date('m'), date('d'),date('Y'));

$lstPosition = listPosition();
$lstJoueurs = listJoueur($affArchive, $affPosition);
$filename="undef";
switch($affPosition){

		case "1":
		$filename="gardien";		
		//gK
		$k = 1;
		$keeperColor = "#9999CC";
		break;
		
		case "2":
		$filename="defenseur";		
		// cD
		$d = 1;
		$defense = 1;
		$defenseColor = "#9999CC";
		break;
		
		case "3":
		$filename="ailier";		
		
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
		case "4":
		$filename="milieu";		
		
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
		
		case "5":
		// Fw
		$filename="buteur";		
				
		$att = 1;
		$passe = 1;
		$passeColor = "#999999";
		$buteur = 1;
		$buteurColor = "#9999CC";
		break;
	
		default:
		$filename="undef";		
		
		$font = "<font color = black>";
		$ffont = "</font>";
		break;
		
}
header("Content-Disposition: attachment; filename = liste_".date('d')."-".date('m')."-".date('Y')."_".$filename.".csv");

switch($sesUser["idNiveauAcces"]){
		case "1":
		break;
		
		case "2":
		break;


		case "3":
			echo "<html><body>operation interdite</body></html>";
			return ;
		
		case "4":
		break;
		
		default;
		break;

}


switch($sens){

case "ASC":
$tri = "Tri croissant";
break;

case "DESC":
$tri = "Tri decroissant";
break;
}

switch($ordre){

case "nomJoueur":
$intitule = "identite";
break;

case "ageJoueur":
$intitule = "age";
break;

case "idExperience_fk":
$intitule = "experience";
break;

case "idLeader_fk":
$intitule = "leadership";
break;

case "idCaractere_fk":
$intitule = "popularite";
break;


case "idAggre_fk":
$intitule = "agressivite";
break;

case "idHonnetete_fk":
$intitule = "honnetete";
break;

case "optionJoueur":
$intitule = "specialite";
break;


case "idEndurance":
$intitule = "endurance";
break;


case "idConstruction":
$intitule = "construction";
break;

case "idAilier":
$intitule = "ailier";
break;
case "idButeur":
$intitule = "buteur";
break;

case "idGardien":
$intitule = "gardien";
break;

case "idPasse":
$intitule = "passe";
break;

case "idDefense":
$intitule = "defense";
break;

case "idPA":
$intitule = "coup de pieds arrete";
break;

case "scoreGardien":
$intitule = "score Gardien";
break;

case "scoreDefense":
$intitule = "score Gardien";
break;
case "scoreAilierOff":
$intitule = "score ailier offensif";
break;

case "scoreMilieu":
$intitule = "score milieu";
break;
case "scoreAttaquant":
$intitule = "score attaquant";
break;


}

?>NomJoueur;idHattrick;maj(jours);dateModifProprio;dateModifDtn;TSI;age;Xp;Ld;Pop;Agg;Hon;Spe;Sta;Pla;Wn;Sco;Kee;pass;def;set;DTN;K;D;W;M;F;
<?php
				$lst = 1;

			if(is_array($lstJoueurs)) foreach($lstJoueurs as $l){
			
			  
			  $infTraining = getEntrainement($l["idJoueur"]);
			  
		switch($lst){
			case 1:
			$bgcolor = "#EEEEEE";
			$lst = 0;
			break;
			
			case 0:
			$bgcolor = "white";
			$lst = 1;
			break;
			}

 $val = array($l["scoreGardien"],$l["scoreDefense"],$l["scoreAilierDef"],$l["scoreAilierOff"],$l["scoreWtm"],$l["scoreMilieu"],$l["scoreMilieuOff"],$l["scoreAttaquant"]);
sort($val);
$valMax =  $val[7];
$val2 = $val[6];
			  
			   	$date = explode("-",$l["dateDerniereModifJoueur"]);
			 $mkJoueur =  mktime(0,0,0,$date[1],$date[2],$date[0]); 
			 $datesaisie = explode("-",$l["dateSaisieJoueur"]);
			 $mkSaisieJoueur= mktime(0,0,0,$datesaisie[1],$datesaisie[2],$datesaisie[0]);
			 if ($mkSaisieJoueur>$mkJoueur){
			 	$datemaj=$mkSaisieJoueur;
			 }else{
			 	$datemaj=$mkJoueur;
			 }
			
			  
?><?=strtolower($l["nomJoueur"])?> <?=strtolower($l["prenomJoueur"])?>;<?=$l["idHattrickJoueur"]?>;<?=round(($mkday - $datemaj)/(60*60*24) )?>;<?php
echo $l["dateSaisieJoueur"].";";
echo $l["dateDerniereModifJoueur"].";";
echo $infTraining["valeurEnCours"].";";
echo $l["ageJoueur"].";";
echo $l["idExperience_fk"].";";
echo $l["idLeader_fk"].";";
echo $l["idAggre_fk"].";";
echo $l["idCaractere_fk"].";";
echo $l["idHonnetete_fk"].";";
echo $specabbrevs[$l["optionJoueur"]].";";
echo $l["idEndurance"].";";
echo $l["idConstruction"].";";
echo $l["idAilier"].";";
echo $l["idButeur"].";";
echo $l["idGardien"].";";
echo $l["idPasse"].";";
echo $l["idDefense"].";";
echo $l["idPA"].";";
$dtn=getDtnName($l["dtnSuiviJoueur_fk"]);
echo $dtn.";";

				   
				   
$carac["endurance"] = $l["idEndurance"];
$carac["construction"] = $l["idConstruction"];
$carac["ailier"] = $l["idAilier"];
$carac["buteur"] = $l["idButeur"];
$carac["gardien"] = $l["idGardien"];
$carac["passe"] = $l["idPasse"];
$carac["defense"] = $l["idDefense"];
$carac["xp"] = $l["idExperience_fk"];


$semaine["construction"] = $infTraining["nbSemaineConstruction"];
$semaine["ailier"] = $infTraining["nbSemaineAilier"];
$semaine["buteur"] = $infTraining["nbSemaineButeur"];
$semaine["gardien"] = $infTraining["nbSemaineGardien"];
$semaine["passe"] = $infTraining["nbSemainePasse"];
$semaine["defense"] = $infTraining["nbSemaineDefense"];

			  $coeff = getCoeffSelectionneur(1);
				if($useFormule == 1 && $coeff["useit"] == 1){
					
					$score = calculNoteDynamique($carac,$semaine,$coeff);
				echo $score.";";
				   } else{
				   
					echo $l["scoreGardien"].";";
				}

					
$carac["endurance"] = $l["idEndurance"];
$carac["construction"] = $l["idConstruction"];
$carac["ailier"] = $l["idAilier"];
$carac["buteur"] = $l["idButeur"];
$carac["gardien"] = $l["idGardien"];
$carac["passe"] = $l["idPasse"];
$carac["defense"] = $l["idDefense"];
$carac["xp"] = $l["idExperience_fk"];


$semaine["construction"] = $infTraining["nbSemaineConstruction"];
$semaine["ailier"] = $infTraining["nbSemaineAilier"];
$semaine["buteur"] = $infTraining["nbSemaineButeur"];
$semaine["gardien"] = $infTraining["nbSemaineGardien"];
$semaine["passe"] = $infTraining["nbSemainePasse"];
$semaine["defense"] = $infTraining["nbSemaineDefense"];

			  $coeff = getCoeffSelectionneur(2);
				if($useFormule == 1 && $coeff["useit"] == 1){
					
					$score = calculNoteDynamique($carac,$semaine,$coeff);
				echo $score.";";
				   } else{
				   
					echo $l["scoreDefense"].";";
				}

					 
$carac["endurance"] = $l["idEndurance"];
$carac["construction"] = $l["idConstruction"];
$carac["ailier"] = $l["idAilier"];
$carac["buteur"] = $l["idButeur"];
$carac["gardien"] = $l["idGardien"];
$carac["passe"] = $l["idPasse"];
$carac["defense"] = $l["idDefense"];
$carac["xp"] = $l["idExperience_fk"];


$semaine["construction"] = $infTraining["nbSemaineConstruction"];
$semaine["ailier"] = $infTraining["nbSemaineAilier"];
$semaine["buteur"] = $infTraining["nbSemaineButeur"];
$semaine["gardien"] = $infTraining["nbSemaineGardien"];
$semaine["passe"] = $infTraining["nbSemainePasse"];
$semaine["defense"] = $infTraining["nbSemaineDefense"];

			  $coeff = getCoeffSelectionneur(3);
				if($useFormule == 1 && $coeff["useit"] == 1){
					
					$score = calculNoteDynamique($carac,$semaine,$coeff);
					echo $score.";";
				   } else{
				   
					echo $l["scoreAilierOff"].";";
				}

				   
$carac["endurance"] = $l["idEndurance"];
$carac["construction"] = $l["idConstruction"];
$carac["ailier"] = $l["idAilier"];
$carac["buteur"] = $l["idButeur"];
$carac["gardien"] = $l["idGardien"];
$carac["passe"] = $l["idPasse"];
$carac["defense"] = $l["idDefense"];
$carac["xp"] = $l["idExperience_fk"];


$semaine["construction"] = $infTraining["nbSemaineConstruction"];
$semaine["ailier"] = $infTraining["nbSemaineAilier"];
$semaine["buteur"] = $infTraining["nbSemaineButeur"];
$semaine["gardien"] = $infTraining["nbSemaineGardien"];
$semaine["passe"] = $infTraining["nbSemainePasse"];
$semaine["defense"] = $infTraining["nbSemaineDefense"];

			  $coeff = getCoeffSelectionneur(4);
				if($useFormule == 1 && $coeff["useit"] == 1){
					
					$score = calculNoteDynamique($carac,$semaine,$coeff);
				echo $score.";";
				   } else{
				   
					echo $l["scoreMilieu"].";";
				}
				   
					 
$carac["endurance"] = $l["idEndurance"];
$carac["construction"] = $l["idConstruction"];
$carac["ailier"] = $l["idAilier"];
$carac["buteur"] = $l["idButeur"];
$carac["gardien"] = $l["idGardien"];
$carac["passe"] = $l["idPasse"];
$carac["defense"] = $l["idDefense"];
$carac["xp"] = $l["idExperience_fk"];


$semaine["construction"] = $infTraining["nbSemaineConstruction"];
$semaine["ailier"] = $infTraining["nbSemaineAilier"];
$semaine["buteur"] = $infTraining["nbSemaineButeur"];
$semaine["gardien"] = $infTraining["nbSemaineGardien"];
$semaine["passe"] = $infTraining["nbSemainePasse"];
$semaine["defense"] = $infTraining["nbSemaineDefense"];

			  $coeff = getCoeffSelectionneur(5);
				if($useFormule == 1 && $coeff["useit"] == 1){
					
					$score = calculNoteDynamique($carac,$semaine,$coeff);
				echo $score.";";
				   } else{
				   
					echo $l["scoreAttaquant"].";";
				}

echo "\n";
}
				?><?php  deconnect(); ?>