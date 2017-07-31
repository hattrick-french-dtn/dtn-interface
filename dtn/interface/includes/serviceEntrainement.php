<?php
if (!function_exists('serviceEntrainement')) {
function serviceEntrainement() {
	return 0;
}

// Liste les entrainement spossible.
function listEntrainement(){
	global $conn;
	$tabP = array();
	
	$sql = "select id_type_entrainement,code_type_entrainement,libelle_type_entrainement,afficher from ht_type_entrainement order by code_type_entrainement";

	foreach ($conn->query($sql) as $result){
    	$arP = array( 'id_type_entrainement' => $result["id_type_entrainement"],
                    'code_type_entrainement' => $result["code_type_entrainement"],
                    'libelle_type_entrainement' => stripslashes($result["libelle_type_entrainement"]),
                    'afficher' => $result["afficher"]
					);
		array_push($tabP, $arP);
	}			

	return	$tabP;
}

function getEntrainementName($training_id,$trainingList){
	foreach($trainingList as $l){
		if($training_id == $l["id_type_entrainement"]){
			return $l["libelle_type_entrainement"];
		} 
	}
	return "??";
}

function getEntrainementId($code_type_entrainement,$trainingList){
	foreach($trainingList as $l){
		if($code_type_entrainement == $l["code_type_entrainement"]){
			return $l["id_type_entrainement"];
		} 
	}
	return "??";
}

function getEntrainementCode($id_type_entrainement,$trainingList){
	foreach($trainingList as $l){
		if($id_type_entrainement == $l["id_type_entrainement"]){
			return $l["code_type_entrainement"];
		} 
	}
	return "??";
}

//Change l'entrainement d'un joueur
function chgTraining($joueur_id, $training_id)
{
	global $conn;
	
	$sql2 = "
			UPDATE ht_joueurs
			SET entrainement_id = '".$training_id."'
			WHERE idJoueur = '".$joueur_id."'";
	$result = $conn->exec($sql2);

	require("serviceHistorique.php");
	majHistorique($joueur_id,"chgTraining");
		
}
}
?>