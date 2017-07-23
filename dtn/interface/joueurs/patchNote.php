<?php
require_once("../includes/head.inc.php");
require("../includes/nomTables.inc.php");
require("../includes/serviceJoueur.php");
require("../includes/serviceEntrainement.php");
require("../includes/serviceDTN.php");




if(!$sesUser["idAdmin"])
{
	header("location: ../index.php?ErrorMsg=Session Expiree");
	return;
}

if(!isset($ageJoueur)) return;

// Modification du niveau
$sql ="select idJoueur from ht_joueurs where ageJoueur='$ageJoueur' order by idJoueur";

?>
	<html>
	<body>
<?php

foreach($conn->query($sql) as $lst){

// Calcul des nouvelles valeurs du joueurs :
	$infJoueur = getJoueur($lst["idJoueur"]);

	$ga=round($infJoueur["scoreGardien"],2);
	$de=round($infJoueur["scoreDefense"],2);
	$mi=round($infJoueur["scoreMilieu"],2);
	$ai=round($infJoueur["scoreAilierOff"],2);
	$at=round($infJoueur["scoreAttaquant"],2);
	$ava=round($infJoueur["scoreAttaquantVersAile"],2);

	$carac["endurance"] = $infJoueur["idEndurance"] ;
	$carac["defense"] = $infJoueur["idDefense"] ;
	$carac["ailier"] = $infJoueur["idAilier"] ;
	$carac["gardien"] = $infJoueur["idGardien"] ;
	$carac["construction"] = $infJoueur["idConstruction"] ;
	$carac["passe"] = $infJoueur["idPasse"] ;
	$carac["buteur"] = $infJoueur["idButeur"] ;
	$carac["xp"] = $infJoueur["idExperience_fk"] ;
	$carac["cf"] = $infJoueur["idPA"] ;

	$semaine["construction"] = $infJoueur["nbSemaineConstruction"];
	$semaine["gardien"] = $infJoueur["nbSemaineGardien"];
	$semaine["passe"] = $infJoueur["nbSemainePasses"];
	$semaine["defense"] = $infJoueur["nbSemaineDefense"];
	$semaine["buteur"] = $infJoueur["nbSemaineButeur"];
	$semaine["ailier"] = $infJoueur["nbSemaineAilier"] ;

	$valeur  = $infJoueur["valeurEnCours"];
	$fin = $infJoueur["finFormation"];

	$score = calculNote($lst["idJoueur"],$carac,$semaine);
	$maj = majCaracJoueur($lst["idJoueur"], $carac, $semaine, $score);

	$infJoueur = getJoueur($lst["idJoueur"]);
if ($ga!=$score["gardien"] || $de!=$score["defense"] || $mi!=$score["milieu"] || $ai!=$score["ailierOff"] || $at!=$score["attaquant"]
||$ava!= $score["attaquantVersAile"]){
?>
GA : <?=$ga?> -> <?=$score["gardien"]?>/DE : <?=$de?> -><?=$score["defense"]?>  /
MI : <?=$mi?> -><?=$score["milieu"]?>/AI : <?=$ai?> -><?=$score["ailierOff"]?>/
AVA : <?=$ava?> -> <?=$score["attaquantVersAile"]?> / 
AT : <?=$at?> -> <?=$score["attaquant"]?> for <?=$lst["idJoueur"]?> / <?=$infJoueur["idHattrickJoueur"]?> <br>
<?php
}

}

?>