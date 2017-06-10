<?php

//viens ici tout le code d'amin et login du site

function login($maBase,$login,$password) {
	$sqlLogin=stripslashes($login);
	$sqlPassword=stripslashes($password);
	$maRequete =  "select * from ht_admin where loginAdmin='$login' and passAdmin='$password' and idAdminHT>0";
		
	$retour = $maBase->select($maRequete);
	if (count($retour)==0) {
		//erreur de login
		return false;
	}
	//sinon
	$_SESSION['login']=$login;
	$_SESSION['teamid']=$retour[0]['teamid'];
	//$_SESSION['leagueid']=$retour[0]['cd_pays'];
	
	return true;
}

function logout() {
	session_unset();
}

// code session ht
function HT_isConnected() {
	return isset($_SESSION['ht_session']);
}

function HT_getConnection() {
	$HTCli = unserialize($_SESSION['ht_session']);
	return $HTCli; 
}

function HT_connect($ht_user, $ht_password) {
	$HTCli = &new HT_Client();
	$connected=$HTCli->Login($ht_user, $ht_password);
	
	if ($connected) {
		$_SESSION['ht_session']=serialize($HTCli);
		
		return $HTCli;
	} else {
		return false;
	}
}	

//fonctions manip base
function majTeam($maBase,$HTCli,$teamid) {
// recupere info equipe

	unset($xml);
	$xml = $HTCli->GetTeamDetails($teamid);
/*	$tmp_tab = @file("E:/Log/Sites/HattrickChallenge/Travail/CHPP/teamDetails.xml");
	$tmp="";
	for($i=0;$i<count($tmp_tab);$i++) {
		$buf = trim($tmp_tab[$i]);
		$tmp .= $buf;
	}
	$xml=$tmp;
*/	
	$tree = GetXMLTree($xml);
	
	if (!isset($tree['HATTRICKDATA'][0]['TEAM'][0]['TEAMNAME'][0]['VALUE'])) {
		printErr("Equipe ".$teamid." inexistante sur HT");
	} else {
		$teamname = $tree['HATTRICKDATA'][0]['TEAM'][0]['TEAMNAME'][0]['VALUE'];
		$leagueid = $tree['HATTRICKDATA'][0]['TEAM'][0]['LEAGUE'][0]['LEAGUEID'][0]['VALUE'];
		// echo "<br/>Teamid : ".$teamid;
		// echo "<br/>Nom d'équipe : ".$teamname;
		// echo "<br/>Id du pays : ".$leagueid;
		
		$maRequete="select * from ht_pays where idPays=".$leagueid;
		$retour = $maBase->select($maRequete);
		if(count($retour)>0) {
		//	print("<br/>Nom du pays : ".$retour[0]['nomPays']);
		} else {
			printErr("<br/>Nom du pays : Pays inexistant en base : Contactez un administrateur pour le rajouter");
		}
		//maj base
		$maRequete="select * from ht_clubs where idClubHT=".$teamid;
		$retour = $maBase->select($maRequete);
		if(count($retour)>0) {
			//update
			$maRequete="update ht_clubs set nomClub ='".addslashes($teamname)."', idPays_fk=$leagueid where idClubHT=".$teamid;
			$retour = $maBase->update($maRequete);
			if ($retour) {
			//	print "<br/>Equipe mise à jour";
			} else {
				printErr("<br/>Erreur base de données");
			}	
		} else {
			//création
			$maRequete="insert into ht_clubs(idClubHT, nomClub, idPays_fk) values($teamid, '".addslashes($teamname)."', $leagueid)";
			$retour = $maBase->insert($maRequete);
			if ($retour) {
			//	print "<br/>Equipe créée";
			} else {
				printErr("<br/>Erreur base de données");
			}	
		}
	}
}


//divers
function strip2($saisie) {
//	return stripslashes(htmlentities($saisie, ENT_QUOTES));
	return addslashes(htmlentities(strip_tags($saisie,""), ENT_QUOTES));
}

function printErr($msg) {
	print("<font color=red>$msg</font>");
}
?>