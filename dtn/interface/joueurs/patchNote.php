<?php
require("../includes/head.inc.php");
require("../includes/nomTables.inc.php");
require("../includes/serviceJoueur.php");
require("../includes/serviceEntrainement.php");
require("../includes/serviceDTN.php");




if(!$sesUser["idAdmin"])
	{
	header("location: index.php?ErrorMsg=Session Expiree");
	return;
	}

if(!isset($ageJoueur)) return;

// Modification du niveau
$sql ="select idJoueur from ht_joueurs where ageJoueur='$ageJoueur' order by idJoueur";

  $result= mysql_query($sql) or die("Erreur : ".$sql);;
  $cpt=0;
	while($lst = mysql_fetch_array($result)){
		$cpt=$cpt+1;
		$tabS[] = $lst;
	}
	

$nb=0;
?>
	<html>
	<body>
<?php

while ($nb<$cpt){

// Calcul des nouvelles valeurs du joueurs :
	$infJoueur = getJoueur($tabS[$nb]["idJoueur"]);

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

$score = calculNote($tabS[$nb]["idJoueur"],$carac,$semaine);
$maj = majCaracJoueur($tabS[$nb]["idJoueur"], $carac, $semaine, $score);

	$infJoueur = getJoueur($tabS[$nb]["idJoueur"]);
if ($ga!=$score["gardien"] || $de!=$score["defense"] || $mi!=$score["milieu"] || $ai!=$score["ailierOff"] || $at!=$score["attaquant"]
||$ava!= $score["attaquantVersAile"]){
?>
GA : <?=$ga?> -> <?=$score["gardien"]?>/DE : <?=$de?> -><?=$score["defense"]?>  /
MI : <?=$mi?> -><?=$score["milieu"]?>/AI : <?=$ai?> -><?=$score["ailierOff"]?>/
AVA : <?=$ava?> -> <?=$score["attaquantVersAile"]?> / 
AT : <?=$at?> -> <?=$score["attaquant"]?> for <?=$tabS[$nb]["idJoueur"]?> / <?=$infJoueur["idHattrickJoueur"]?> <br>
<?php
}

$nb++;
}
deconnect();
?>