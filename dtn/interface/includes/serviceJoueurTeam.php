<?php
		ini_set("include_path", $_SERVER['DOCUMENT_ROOT'] . "/dtn/interface/fonctions" . PATH_SEPARATOR . ini_get("include_path"));
        require_once "../fonctions/phpxml.php"; // XML to Tree converter
 //       require_once "../fonctions/HTML/Table.php"; // fcontions pour faire des tables HTML

function TreeGetTeamDetails($HTCli,$teamid) {
	unset($xml);
	$xml = $HTCli->GetTeamDetails($teamid);
	$tree = GetXMLTree($xml);
	
	// printa ($tree);
	
	return $tree;
}
        
          
function getTeamidForPlayerFromTransfer($HTCli,$playerid) {
	// ram�ne la teamid du joueur en question si celui-ci a deja ete transfere
	unset($xml);
	$xml = $HTCli->TransferHistory($playerid);
	$tree = GetXMLTree($xml);
	
	// printa ($tree);
	// mise a zero de teamid pour initialisation
	$teamid=0;
	
	// ce joueur a-t-il ete transfere ?
	$numberoftransfers=$tree['HATTRICKDATA'][0]['TRANSFERS'][0]['ATTRIBUTES']['COUNT'];
	
	// si oui, equipe actuelle
	if ($numberoftransfers <> 0) {
		$teamid= $tree['HATTRICKDATA'][0]['TRANSFERS'][0]['TRANSFER'][0]['BUYER'][0]['BUYERTEAMID'][0]['VALUE']; 
	}
	
	// verifie que le joueur existe bien dans l'equipe attendue (le joueur a pu disparaitre avec son equipe)
	$isplayerset=checkPlayeronTeam($HTCli,$playerid,$teamid);
	
	if ($isplayerset == true) {
		return $teamid;
	} else {
		return 0;
	}
	
}


function checkPlayeronTeam($HTCli,$playerid,$teamid) {
	// v�rifie que le joueur test� est bien dans l'�quipe que l'on attend
	unset($xml);
	$xml = $HTCli->GetPlayers($teamid);
	$tree = GetXMLTree($xml);
	
	// mise a zero du flag
	$checkplayeronteam = false;
	
	// printa ($tree);
	
	//nombre de joueurs dans l'equipe en question
	$nombredejoueursdanslequipe=$tree['HATTRICKDATA'][0]['TEAM'][0]['PLAYERLIST'][0]['ATTRIBUTES']['COUNT'];

	// verification de la presence du joueur dans l'effectif de l'equipe
	for ($i=0; $i<$nombredejoueursdanslequipe; $i++) {
		if ($tree['HATTRICKDATA'][0]['TEAM'][0]['PLAYERLIST'][0]['PLAYER'][$i]['PLAYERID'][0]['VALUE'] == $playerid) {
			$checkplayeronteam = true;
		}		
	}
	
	return $checkplayeronteam;
	
}

?>