<?php

// Mise a jour de l'historique :
function majHistorique($joueur_id,$codeHisto){
	global $conn;
	global $sesUser;
	global $idProgression_fk ;
	global $idPerf_fk  ;
	
	switch($codeHisto){
		case "chgTraining":
		$intituleHisto  = "Modification de l'entrainement ";
		break;
	}
	

	$date = date("Y-m-d");
	$heure = date("H:i");

	$sql = ' 
		INSERT INTO  ht_histomodif (
		idJoueur_fk,
		idAdmin_fk,
	    dateHisto,
		heureHisto,
		intituleHisto ,
		idProgression_fk ,
		idPerf_fk
		) VALUES (
		"'.$joueur_id.'",
		"'.$sesUser["idAdmin"].'",
		"'.$date.'",
		"'.$heure.'",
		"'.addslashes($intituleHisto).'",
		"'.$idProgression_fk.'",
		"'.$idPerf_fk.'"
		)
		';
	$req = $conn->exec($sql);
}

?>