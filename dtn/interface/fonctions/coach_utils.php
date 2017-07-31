<?php 
    
function selectionNationalTeamOn($idJoueur,$selectionFrance,$maBase){
	
	$sql = "insert INTO ht_selection ( id_joueur,   selection  )";
	$sql .= " VALUES ('".$idJoueur."','".$selectionFrance."')";
	
	$retourGood = $maBase->insert($sql);
	if ($retourGood==true){
		$sql2 = "insert INTO ht_histomodif (idJoueur_fk , idAdmin_fk , dateHisto  ,heureHisto  ,intituleHisto )";
		$sql2 .= " VALUES ('".$idJoueur."','". $sesUser["idAdmin"]."','".date("Y-m-d")."','".date("H:i")."','Appele avec les selectionnables en ".$selectionFrance." ! ')";
		$retour2Good = $maBase->insert($sql2);
		return $retour2Good;
	}
	return false;
	
}
     
     
function selectionNationalTeamOff($idJoueur,$selectionFrance,$maBase){

	$sql = "delete FROM ht_selection WHERE id_joueur = ".$idJoueur."";
	$retourGood = $maBase->delete($sql);
	if ($retourGood==true){
		$sql2 = "insert INTO ht_histomodif (idJoueur_fk , idAdmin_fk , dateHisto  ,heureHisto  ,intituleHisto )";
		$sql2 .= " VALUES ('".$idJoueur."','". $sesUser["idAdmin"]."','".date("Y-m-d")."','".date("H:i")."','Ce joueur quitte les selectionnables en ".$selectionFrance." ! ')";
		$retour2Good = $maBase->insert($sql2);
		return $retour2Good;
	}
	return false;
	
}
?>