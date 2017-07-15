<?php
require_once("../includes/head.inc.php");
require("../includes/serviceJoueur.php");
?><title>Mise &agrave; jour des semaines d'entrainements</title>
<?php

// 1. Liste des joueurs avec un entrainement de selectionner. 
$sql =  "SELECT * from ht_joueurs, ht_entrainement where ht_joueurs.idJoueur = ht_entrainement.idJoueur_fk ";
		
foreach($conn->query($sql) as $result){

	if (isset($result["prenomJoueur"]) && strlen($result["prenomJoueur"]) > 0) {
		echo $result["prenomJoueur"]."&nbsp;";
	}
	echo $result["nomJoueur"]."<br>";
	$semaine["construction"] = $result["nbSemaineConstruction"];
	$semaine["gardien"] = $result["nbSemaineGardien"];
	$semaine["passe"] = $result["nbSemainePasses"];
	$semaine["defense"] = $result["nbSemaineDefense"];
	$semaine["buteur"] = $result["nbSemaineButeur"];
	$semaine["ailier"] = $result["nbSemaineAilier"] ;

/*
switch($result["entrainement_id"]){

case "4": // Défense
$semaine["defense"] += 1;
break;
case "5": // Buteur
$semaine["buteur"] += 1;
break;
case "6": // Ailier
$semaine["ailier"] += 1;
break;
case "8": // Passe
$semaine["passe"] += 1;
break;
case "9": // Construction
$semaine["construction"] += 1;
break;
case "10":
$semaine["gardien"] += 1;
break;

default:
break;
}
*/

	$carac["endurance"] = $result["idEndurance"] ;
	$carac["defense"] = $result["idDefense"] ;
	$carac["ailier"] = $result["idAilier"] ;
	$carac["gardien"] = $result["idGardien"] ;
	$carac["construction"] = $result["idConstruction"] ;
	$carac["passe"] = $result["idPasse"] ;
	$carac["buteur"] = $result["idButeur"] ;
	$carac["xp"] = $result["idExperience_fk"] ;
	$carac["cf"] = $result["idPA"] ;
			  
			
	$score = calculNote($result["idJoueur"],$carac,$semaine);

	$valeur = $result["valeurEnCours"];
	$fin = $result["finFormation"];
	$maj = majCaracJoueur($result["idJoueur"], $carac, $semaine, $score);
}
// 2. +1 semaine pour l'entrainement en question

?>
