<?php

/************************************************************************************************/
/* Objet : Liste des clubs                                                                      */
/* Modifi� le ??/??/???? par XXXX - Cr�ation fonction                                           */
/* Modifi� le 18/08/2011 par Musta56 - Ajout Param�tres limit - modification tri                */
/************************************************************************************************/
/* Entr�e : $limitDeb = Facultatif - Limite de d�but de la requ�te                              */
/* Entr�e : $limitFin = Facultatif - Limite de fin de la requ�te                                */
/* Sortie : $tabS = tableau r�sultats                                                           */
/************************************************************************************************/
/* Appel� par les scripts :                                                                     */
/*           - ./maj/majEquipesAuto.php                                                         */
/************************************************************************************************/
function listClubs($limitDeb=null,$limitFin=null)
{
	global $conn;
	$tabS = array();
  
	$sql = "SELECT 
                idClub,
                idClubHT,
                idUserHT,
                nomClub,
                nomUser,
                idPays_fk,
                niv_Entraineur,
                isBot,
                date_last_connexion,
                AES_DECRYPT(userToken,'userToken_HT_DTN') AS userToken,
                AES_DECRYPT(userTokenSecret,'userTokenSecret_HT_DTN') AS userTokenSecret
           FROM ht_clubs 
           ORDER BY idClubHT ASC";
	if ($limitDeb != null && $limitFin != null) {
		$sql .= " LIMIT $limitDeb,$limitFin";
	} elseif ($limitDeb != null && $limitFin == null) {
		$sql .= " LIMIT $limitDeb";
	} elseif ($limitDeb == null && $limitFin != null) {
		$sql .= " LIMIT $limitFin";
	}
   
	foreach ($conn->query($sql) as $row) {
		array_push($tabS, $row);
	}
	
	return	$tabS;
}


function getClub($id)
{
	global $conn;
	$tabS = array();

	$sql = "SELECT idClub,
             idClubHT,
             idUserHT,
             nomClub,
             nomUser,
             idPays_fk,
             niv_Entraineur,
             isBot,
             date_last_connexion,
             AES_DECRYPT(userToken,'userToken_HT_DTN') AS userToken,
             AES_DECRYPT(userTokenSecret,'userTokenSecret_HT_DTN') AS userTokenSecret
        FROM ht_clubs WHERE idClub = $id";

	foreach ($conn->query($sql) as $row) {
		array_push($tabS, $row);
	}
	
	return	$tabS;
}

/********************************************************************************************/
/* Objet : extraction donn�es club � partir de l'ID club ou User HT                         */
/* Modifi� le ../../.. par xxx - Description                                                */
/********************************************************************************************/
/* Entr�e : $idClub = identifiant club HT                                                   */
/* Entr�e : $idUser = identifiant user HT                                                   */
/* Sortie : $tabS = Tableau de donn�es club base DTN                                        */
/********************************************************************************************/
/* Appel� par les scripts :                                                                 */
/*           - ./dtn/interface/maj/includes/serviceEquipes.php                              */
/********************************************************************************************/
function getClubID($idClub,$idUser=null) 
{
	global $conn;
	$tabS=array();
	$sql = "SELECT 
            idClub,
            idClubHT,
            idUserHT,
            nomClub,
            nomUser,
            idPays_fk,
            niv_Entraineur,
            isBot,
            date_last_connexion,
            AES_DECRYPT(userToken,'userToken_HT_DTN') AS userToken,
            AES_DECRYPT(userTokenSecret,'userTokenSecret_HT_DTN') AS userTokenSecret
          FROM ht_clubs ";
          
	if ($idClub!==null) {$sql .= "WHERE idClubHT = $idClub";}
	elseif ($idUser!==null) {$sql .= "WHERE idUserHT = $idUser";}
	elseif ($idClub===null && $idUser===null) {return $tabS;}

	foreach ($conn->query($sql) as $row) {
		array_push($tabS, $row);
	}
	
	return	$tabS;
}

/********************************************************************************************/
/* Objet : Cr�ation req SQL � partir de l'ID club ou User HT                                */
/* Modifi� le 12/10/2012 par Musta - Ajout param�tre idUserHT                               */
/********************************************************************************************/
/* Entr�e : $idClub = identifiant club HT                                                   */
/* Entr�e : $idUser = identifiant user HT                                                   */
/* Sortie : $sql = requ�te sql                                                              */
/********************************************************************************************/
/* Appel� par les scripts :                                                                 */
/*           - ./dtn/interface/maj/includes/serviceEquipes.php                              */
/********************************************************************************************/
function getClubSQL($idClubHT=null,$idUserHT=null,$ht_session=null){
	$sql="SELECT idClub,
             idClubHT,
             idUserHT,
             nomClub,
             nomUser,
             idPays_fk,
             niv_Entraineur,
             isBot,
             date_last_connexion,
             AES_DECRYPT(userToken,'userToken_HT_DTN') AS userToken,
             AES_DECRYPT(userTokenSecret,'userTokenSecret_HT_DTN') AS userTokenSecret
      FROM ht_clubs ";

	if ($idClubHT!==null) {$sql .= "WHERE idClubHT = $idClubHT";}
	elseif ($idUserHT!==null) {$sql .= "WHERE idUserHT = $idUserHT";}
	elseif ($idClubHT===null && $idUserHT===null) {return $tabS;}

	return $sql;
}


/********************************************************************************************/
/* Objet : Retourne un lien vers la fiche du club                                           */
/* Modifi� le 14/12/2011 par Musta56 - Cr�ation fonction                                    */
/********************************************************************************************/
/* Entr�e : $idClubHT = identifiant club sur HT                                             */
/* Entr�e : $nomClub = nom du club                                                          */
/* Sortie : lien HTML                                                                       */
/********************************************************************************************/
/* Appel� par les scripts :                                                                 */
/*           - ./dtn/interface/includes/serviceEquipes.php majClub                          */
/********************************************************************************************/
function getClubHREF($idClubHT, $nomClub=null)
{
	$lien = '<a href="http://'.$cheminComplet.'/clubs/fiche_club.php?idClubHT='.$idClubHT.'">'.$idClubHT;
	if ($nomClub != null) {$lien .= '-'.$nomClub;}
	$lien .= '</a>';
	return $lien;
}


/********************************************************************************************/
/* Objet : Existe t'il dans la bdd DTN une autorisation pour acc�der aux donn�es d'un club  */
/* Modifi� le 11/05/2011 par Musta56 - Cr�ation fonction                                    */
/********************************************************************************************/
/* Entr�e : $idClubHT = identifiant club sur HT                                             */
/* Entr�e : $idUserHT = identifiant User sur HT                                             */
/* Sortie : $HT_proprio = Objet repr�sentant la session HT du proprio                       */
/********************************************************************************************/
/* Appel� par les scripts :                                                                 */
/*           - ./dtn/interface/includes/serviceJoueurs.php                                  */
/********************************************************************************************/
function existAutorisationClub($idClubHT,$idUserHT=null)
{
//echo ("<br> $idClubHT ");
  if ($idClubHT===null && $idUserHT===null) {return false;} // Param�tres non renseign�s
  $clubDTN = getClubID($idClubHT,$idUserHT); // Extraction du club dans la bdd DTN
//echo("<br />club=");print_r($clubDTN);

  if (isset($clubDTN['userToken']) && isset($clubDTN['userTokenSecret']) && !empty($clubDTN['userToken']) && !empty($clubDTN['userTokenSecret']) ) {
/*    echo("<br />consumerKey=".CONSUMERKEY);
    echo("<br />consumerSecret=".CONSUMERSECRET);
    echo("<br />userToken=".$clubDTN['userToken']);
    echo("<br />userTokenSecret=".$clubDTN['userTokenSecret']);*/
    $HT_proprio = new CHPPConnection(CONSUMERKEY,CONSUMERSECRET);
  
    $HT_proprio->setOauthToken($clubDTN['userToken']);
    $HT_proprio->setOauthTokenSecret($clubDTN['userTokenSecret']);
    
  } else {
//echo ("Pas d'info connexion en base DTN");
    return false;
  }
  
  //echo("<br />HT_proprio=");var_dump($HT_proprio);
  if (isset($HT_proprio)) {

    /*      VERIFICATION VALIDITE SESSION                                         */
    // V�rifier que la session est valide
    $check = $HT_proprio->checkToken();
    //var_dump($check);echo("<br><br>".$check->isValid());exit;
    if (!$check->isValid()) 
    {
//echo ("Autorisation non valide");
      // Si session non Valide alors retourner false
      return false;
    } else {
      return $HT_proprio;
    }
  }

}




/******************************************************************************/
/* Objet : R�cup�ration des donn�es du club                                   */
/* Modifi� le 11/06/2010 par Musta56 - Cr�ation fonction                      */
/* Modifi� le 15/03/2011 par Musta56 - Utilisation du framework PHT           */
/* Modifi� le 05/05/2011 par Musta56 - Ajout leagueLevel                      */
/* Modifi� le 13/12/2011 par Musta56 - r�cup�ration des donn�es pour les bots */
/* Modifi� le 11/10/2012 par Musta56 - ajout param idUserHT pour acc�s par    */
/*                                     idUser + modif calcul isBot            */
/******************************************************************************/
/* Entr�e : $idClubHT = identifiant hattrick du club                          */
/* Sortie : $row_club = tableau avec info club                                */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/*           - ./dtn_scan_team.php                                            */
/*           - gestion_session_HT.php                                         */
/*           - fff_help.php                                                   */
/*           - maj/majEquipesAuto.php                                         */
/*           - includes/serviceEquipes.php (majClub)                          */
/******************************************************************************/
function getDataClubFromHT_usingPHT($idClubHT=null, $idUserHT=null){

  $row_club=array();

  try
  {
    if ($idClubHT === null && $idUserHT === null) {
      $team = $_SESSION['HT']->getTeam();
    } elseif ($idClubHT != null) {
      $team = $_SESSION['HT']->getTeam($idClubHT);
    } elseif ($idUserHT != null) {
      $team = $_SESSION['HT']->getTeamByUserId($idUserHT);
    }

    $_SESSION['HT']->clearTeam(); // On vide le cache de l'objet
    
    $row_club["idClubHT"] = $team->getTeamId();
    $row_club["idUserHT"] = $team->getUserId();   
    if (($row_club["idUserHT"] == "0") || ($row_club["idClubHT"]===null)) {
      $row_club['isBot']=2; // Pas de manager Humain ou Pas de club
    } else {
      if ($team->isBot()==false) {$row_club['isBot']=0;} // �quipe active non botifi�e
      if ($team->isBot()==true)  {$row_club['isBot']=1;} // �quipe botifi�e
    }

    if ( ($row_club['isBot']==0) || ($row_club['isBot']==1) ) {
      $row_club["nomClub"]              = stripslashes(htmlspecialchars(utf8_decode(strtolower(str_replace("'"," ",$team->getTeamName())))));
      $row_club["nomUser"]              = stripslashes(htmlspecialchars(utf8_decode(strtolower(str_replace("'"," ",$team->getLoginName())))));
      $row_club["idPays_fk"]            = $team->getLeagueId();
      $row_club["niv_Entraineur"]       = $_SESSION['HT']->getPlayer($team->getTrainerId())->getTrainerSkill();
      $_SESSION['HT']->clearPlayer($team->getTrainerId());
      $row_club["date_last_connexion"]  = substr($team->getLastLoginDate(), 0, -9);
      
      // Donn�es pour iiihelp
      $row_club["leagueLevel"] = $team->getLeagueLevel();
    }

    // D�sallocation variables
    unset($team);

  	return $row_club;
  }
  catch(HTError $e)
  {
    echo $e->getMessage();
    return false;
  } 
}




/******************************************************************************/
/* Objet : Insertion Club dans la bdd                                         */
/* Modifi� le 11/06/2010 par Musta56 - Cr�ation fonction                      */
/* Modifi� le 04/05/2011 par Musta56 - Ajout des userToken                    */
/* Modifi� le 12/10/2012 par Musta56 - appel getClubSQL avec param idUserHT   */
/******************************************************************************/
/* Entr�e : $club = tableau avec info club                                    */
/* Sortie : False si �chec, Idclub de la table ht_clubs si ok                 */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/*           - ./dtn_scan_team.php                                            */
/*           - ./dtn/interface/includes/serviceJoueurs.php                    */
/******************************************************************************/
function insertionClub($club){
	global $conn;

	require($cheminComplet.'includes/nomTables.inc.php');

	$sql = getClubSQL($club['idClubHT'], $club['idUserHT']);
	$req = $conn->query($sql);
	if(!$req){
		return false;
	} elseif ($req->rowCount() == 0) { /* le club n'existe pas dans la base => on l'ins�re*/
		if (!isset($club['userToken'])) $club['userToken']='NULL';
		if (!isset($club['userTokenSecret'])) $club['userTokenSecret']='NULL';
  
		$sql = "INSERT INTO $tbl_clubs 
                ( idClubHT,
                  idUserHT,
                  nomClub,
                  nomUser,
                  idPays_fk,
                  niv_Entraineur,
                  isBot,
                  date_last_connexion,
                  userToken,
                  userTokenSecret) 
              VALUES 
                ( ".$club['idClubHT'].",
                  ".$club['idUserHT'].",
                  '".$club['nomClub']."',
                  '".$club['nomUser']."',".
                  $club['idPays_fk'].",".
                  $club['niv_Entraineur'].",".
                  $club['isBot'].",
                  '".$club['date_last_connexion']."',
                  AES_ENCRYPT('".$club['userToken']."','userToken_HT_DTN'),
                  AES_ENCRYPT('".$club['userTokenSecret']."','userTokenSecret_HT_DTN')
                )";
                 
		$reqValid= $conn->exec($sql);
      
		if (!$reqValid) {
			return false;
		} else {
			return $conn->lastInsertId();
		}

	} elseif(mysql_num_rows($req) == 1){ /* le club existe dans la base => on le met � jour */
        $tab = $req->fetch(PDO::FETCH_ASSOC);
        $club['idClub'] = $tab['idClub'];
        return updateClub($club);
	}

    return false;
}


/******************************************************************************/
/* Objet : Mise � jour du Club dans la bdd                                    */
/* Modifi� le 11/06/2010 par Musta56 - Cr�ation fonction                      */
/* Modifi� le 04/05/2011 par Musta56 - Ajout des userToken                    */
/******************************************************************************/
/* Entr�e : $club = tableau avec info club                                    */
/* Sortie : False si �chec, Idclub de la table ht_clubs si ok                 */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/*           - ./dtn_scan_team.php                                           */
/*           - ./dtn/interface/includes/serviceJoueurs.php                    */
/*           - ./dtn/interface/maj/majEquipesAuto.php                         */
/******************************************************************************/
function updateClub($club){
	global $conn;

	require($cheminComplet.'includes/nomTables.inc.php');
	//print_r($club);
	$sql="UPDATE $tbl_clubs SET ";
	if (isset($club["idUserHT"]))             {$sql.="idUserHT = '".$club["idUserHT"]."',";}
	if (isset($club["nomClub"]))              {$sql.="nomClub = '".$club["nomClub"]."',";}
	if (isset($club["nomUser"]))              {$sql.="nomUser = '".$club["nomUser"]."',";}
	if (isset($club["idPays_fk"]))            {$sql.="idPays_fk = ".$club["idPays_fk"].",";}
	if (isset($club["niv_Entraineur"]))       {$sql.="niv_Entraineur = ".$club["niv_Entraineur"].",";}
	if (isset($club["isBot"]))                {$sql.="isBot = ".$club["isBot"].",";}
	if (isset($club["date_last_connexion"]))  {$sql.="date_last_connexion = '".$club["date_last_connexion"]."',";}
	if (isset($club["userToken"]))            {$sql.="userToken = AES_ENCRYPT('".$club['userToken']."','userToken_HT_DTN'),";}
	if (isset($club["userTokenSecret"]))      {$sql.="userTokenSecret = AES_ENCRYPT('".$club['userTokenSecret']."','userTokenSecret_HT_DTN'),";}
	$sql=substr($sql,0,strlen($sql)-1);

	if (isset($club["idClub"])) {
		$sql.=" WHERE
              idClub  = ".$club["idClub"];
	} elseif (isset($club["idClubHT"])) {
		$sql.=" WHERE
              idClubHT = ".$club["idClubHT"];
	} else return false;
        
	$reqValid = $conn->exec($sql);

	if (!$reqValid) {
		return false;
	} else {
		return $club["idClub"];
	}

}


/******************************************************************************/
/* Objet : Insertion d'une nouvelle ligne dans la table ht_clubs_histo_joueurs*/
/* Modifi� le 21/01/2011 par Musta56 - Cr�ation fonction                      */
/******************************************************************************/
/* Entr�e : $id_Clubs_Histo = tableau contenant les informations � ins�rer    */
/* Sortie : $row["id_Clubs_Histo"] = identifiant clubs_histo ligne ins�r�e    */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/*           - ./serviceEquipes.php                                           */
/******************************************************************************/
function insertHistoClub_Joueurs($id_Clubs_Histo,$idClubHT)
{
	global $conn;

	require($cheminComplet.'includes/nomTables.inc.php');

	$sql="INSERT INTO $tbl_clubs_histo_joueurs (id_clubs_histo,id_joueur)
        SELECT
            $id_Clubs_Histo,
            idJoueur
        FROM
            $tbl_joueurs
        WHERE
            teamid=$idClubHT
        AND archivejoueur = 0";

	$reqValid= $conn->exec($sql);
  
	if (!$reqValid) {
		return -1;
	} else {
		$id_clubs_histo_joueurs=$conn->lastInsertId();
		if (update_entrainementJoueursDeEquipe($id_Clubs_Histo)) {
			return $id_clubs_histo_joueurs;
		}
	}

}


/******************************************************************************/
/* Objet : Insertion d'une nouvelle ligne dans la table ht_clubs_histo_joueurs*/
/* Modifi� le 21/01/2011 par Musta56 - Cr�ation fonction                      */
/******************************************************************************/
/* Entr�e : $id_Clubs_Histo = tableau contenant les informations � ins�rer    */
/* Sortie : $row["id_Clubs_Histo"] = identifiant clubs_histo ligne ins�r�e    */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/*           - ./serviceEquipes.php                                           */
/******************************************************************************/
function update_entrainementJoueursDeEquipe($id_Clubs_Histo){
	global $conn;

	require($cheminComplet.'includes/nomTables.inc.php');

	$sql="UPDATE $tbl_joueurs 
        SET
            entrainement_id= (SELECT idEntrainement FROM $tbl_clubs_histo WHERE id_Clubs_Histo=$id_Clubs_Histo)
        WHERE
            idJoueur IN (SELECT id_joueur FROM $tbl_clubs_histo_joueurs WHERE id_Clubs_Histo=$id_Clubs_Histo)";

	$reqValid= $conn->exec($sql);
  
	if (!$reqValid) {
		return false;
	} else {
		return true;
	}

}


/******************************************************************************/
/* Objet : R�cup�ration des donn�es du club                                   */
/* Modifi� le 27/03/2010 par Musta56 - R�cup�ration % endu, intensit�, ... etc*/
/******************************************************************************/
/* Entr�e : $idClubHT = identifiant hattrick du club                          */
/* Entr�e : $ht_session = session HT du proprio                               */
/* Entr�e : (facultatif) $team = tableau contenant les infos du club          */
/* Sortie : $niv_entraineur = niveau de l'entraineur                          */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/*           - ./dtn/interface/includes/serviceJoueurs.php                    */
/*           - ./dtn/interface/includes/serviceEquipes.php                    */
/******************************************************************************/
function getNivEntraineurHT($idClubHT,$ht_session,$team=null){
	$niv_entraineur=0;
  
	if ($team==null) {
		$team = GetXMLTree($ht_session->GetTeamDetails($idClubHT));
	}
	$identraineur = $team["HATTRICKDATA"][0]["TEAM"][0]["TRAINER"][0]["PLAYERID"][0]["VALUE"];
	
	$xmlEntraineur=GetXMLTree($ht_session->GetSinglePlayer($identraineur));
	$niv_entraineur=$xmlEntraineur["HATTRICKDATA"][0]["PLAYER"][0]["TRAINERDATA"][0]["TRAINERSKILL"][0]["VALUE"];

	return $niv_entraineur;
}




/*******************************************************************************/
/* Objet : R�cup�ration des donn�es du club                                    */
/* Modifi� le 18/03/2010 par Musta56 - R�cup�ration % endu, intensit�, ... etc */
/* Modifi� le 15/03/2011 par Musta56 - Utilisation du Framework PHT            */
/* Modifi� le 11/05/2011 par Musta56 - Utilisation de la session proprio si    */
/*                                     autorisation en base                    */
/* Modifi� le 15/11/2011 par Musta56 - Retourne 0 si on a pas autorisation CHPP*/   
/*******************************************************************************/
/* Entr�e : $idClubHT = Identifiant club sur HT                                */
/* Sortie : $row_clubs_histo = tableau avec info histo club ou 0 si on a pas   */
/*          autorisation                                                       */
/*******************************************************************************/
/* Appel� par les scripts :                                                    */
/*           - ./dtn_scan_team.php                                             */
/*           - ./fff_help.php                                                  */
/*******************************************************************************/
function getDataClubsHistoFromHT_usingPHT($idClubHT=null){
//echo('toto');

	  require_once("serviceEntrainement.php");
	  $row_clubs_histo=array();
	  $lTraining=listEntrainement();
  
	try
	{
		if ($idClubHT != null) {
			// On recherche dans la base dtn si le proprio du joueur nous a autoriser � utiliser son acc�s CHPP et si c'est acc�s est toujours valide, on l'utilisera
			$ht_session=existAutorisationClub($idClubHT);
		} else {
			// On utilise la connexion du membre DTN connect� si aucun idClubHT envoy�
			$ht_session=$_SESSION['HT'];
		}
    
		if (isset($ht_session) && $ht_session!=false) {

			$club = $ht_session->getClub($idClubHT);
//      $staff = $club->getSpecialists();   // Personnel du club
			$entrainement = $ht_session->getTraining($idClubHT); // Entrainement du club

			// On vide le cache
			$ht_session->clearClub();
			$ht_session->clearTraining();
			unset($ht_session);
    
			$row_clubs_histo["idClubHT"]       = $club->getTeamId($idClubHT);
			$row_clubs_histo["idEntrainement"] = getEntrainementId($entrainement->getTrainingType(),$lTraining);
			$row_clubs_histo["intensite"]      = $entrainement->getTrainingLevel();
			$row_clubs_histo["endurance"]      = $entrainement->getStaminaTrainingPart();
//      $row_clubs_histo["adjoints"]       = $staff->getAssistantTrainers();
			$row_clubs_histo["adjoints"]       = $club->getAssistantTrainerLevels();
			$row_clubs_histo["medecin"]       = $club->getMedicLevels();
			$row_clubs_histo["physio"]       = $club->getFormCoachLevels();
    
			// D�sallocation des variables
			unset($club);
			unset($staff);
			unset($entrainement);
      
			return $row_clubs_histo;
		} else {
			return 0;
		}
	}
	catch(HTError $e)
	{
		echo $e->getMessage();
		return false;
	} 
}



/******************************************************************************/
/* Objet : Insertion d'une nouvelle ligne dans la table ht_clubs_histo        */
/* Modifi� le 18/03/2010 par Musta56 - Cr�ation fonction                      */
/******************************************************************************/
/* Entr�e : $row_clubs_histo = tableau contenant les informations � ins�rer   */
/* Sortie : $id_Clubs_Histo = identifiant clubs_histo ligne ins�r�e           */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/*           - ./form.php                                                     */
/******************************************************************************/
function insertHistoClub($row_clubs_histo){
	global $conn;

	require($cheminComplet.'includes/nomTables.inc.php');

	if (!isset($row_clubs_histo["idEntrainement"])||$row_clubs_histo["idEntrainement"]=='') {$row_clubs_histo["idEntrainement"]='NULL';}
	if (!isset($row_clubs_histo["intensite"])||$row_clubs_histo["intensite"]=='')           {$row_clubs_histo["intensite"]='NULL';}
	if (!isset($row_clubs_histo["endurance"])||$row_clubs_histo["endurance"]=='')           {$row_clubs_histo["endurance"]='NULL';}
	if (!isset($row_clubs_histo["adjoints"])||$row_clubs_histo["adjoints"]=='')             {$row_clubs_histo["adjoints"]='NULL';}
	if (!isset($row_clubs_histo["medecin"])||$row_clubs_histo["medecin"]=='')             {$row_clubs_histo["medecin"]='NULL';}
	if (!isset($row_clubs_histo["physio"])||$row_clubs_histo["physio"]=='')             {$row_clubs_histo["physio"]='NULL';}
	if (!isset($row_clubs_histo["Commentaire"])||$row_clubs_histo["Commentaire"]=='')       {$row_clubs_histo["Commentaire"]=NULL;}
	$row_clubs_histo["date_histo"] = date('Y-m-d H:i:s');

	$sql="INSERT INTO $tbl_clubs_histo (
              date_histo,
              idClubHT,
              idEntrainement,
              intensite,
              endurance,
              adjoints,
              medecin,
              physio,
              cree_par,
              role_createur,
              Commentaire)
        VALUES (
              '".$row_clubs_histo["date_histo"]."',
              '".$row_clubs_histo["idClubHT"]."',
              ".$row_clubs_histo["idEntrainement"].",
              ".$row_clubs_histo["intensite"].",
              ".$row_clubs_histo["endurance"].",
              ".$row_clubs_histo["adjoints"].",
              ".$row_clubs_histo["medecin"].",
              ".$row_clubs_histo["physio"].",
              '".$row_clubs_histo["cree_par"]."',
              '".$row_clubs_histo["role_createur"]."',
              '".$row_clubs_histo["Commentaire"]."'
        )";

	$reqValid= $conn->exec($sql);
  
	if (!$reqValid) {
		return -1;
	} else { 
		$id_Clubs_Histo = $conn->lastInsertId();
		if (insertHistoClub_Joueurs($id_Clubs_Histo,$row_clubs_histo["idClubHT"]) != -1) {
			return $id_Clubs_Histo;
		} else {
			return -1;
		}
	}

}



/******************************************************************************/
/* Objet : Selection d'une ligne dans la table ht_clubs_histo                 */
/* Modifi� le 18/03/2010 par Musta56 - Cr�ation fonction                      */
/******************************************************************************/
/* Entr�e : $id_clubs_histo = Identifiant clubs histo                         */
/* Sortie : $row["id_Clubs_Histo"] = identifiant clubs_histo ligne ins�r�e    */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/*           - ./dtn_scan_team.php                                           */
/******************************************************************************/
function selectHistoClub($id_clubs_histo){
	global $conn;

	$sql="SELECT
          *
        FROM 
          $tbl_clubs_histo
        WHERE
          id_clubs_histo=$id_clubs_histo";
  
	$reqValid= $conn->query($sql);
  
	if (!$reqValid) {
		return -1;
	} else {
		return $reqValid->rowCount();
	}

}


/******************************************************************************/
/* Objet : Mise � jour d'une ligne dans la table ht_clubs_histo               */
/* Modifi� le 18/03/2010 par Musta56 - Cr�ation fonction                      */
/******************************************************************************/
/* Entr�e : $row_clubs_histo = tableau contenant les informations � ins�rer   */
/* Sortie : $row["id_Clubs_Histo"] = identifiant clubs_histo ligne updat�     */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/*           - ./dtn_scan_team.php                                           */
/******************************************************************************/
function updateHistoClub($row_clubs_histo){
	global $conn;

	require($cheminComplet.'includes/nomTables.inc.php');

	$sql="UPDATE $tbl_clubs_histo 
		SET ";
	if (isset($row_clubs_histo["idEntrainement"]))  {$sql.="idEntrainement = '".$row_clubs_histo["idEntrainement"]."',";}
	if (isset($row_clubs_histo["intensite"]))       {$sql.="intensite = '".$row_clubs_histo["intensite"]."',";}
	if (isset($row_clubs_histo["endurance"]))       {$sql.="endurance = '".$row_clubs_histo["endurance"]."',";}
	if (isset($row_clubs_histo["adjoints"]))        {$sql.="adjoints = '".$row_clubs_histo["adjoints"]."',";}
	if (isset($row_clubs_histo["medecin"]))        {$sql.="medecin = '".$row_clubs_histo["medecin"]."',";}
	if (isset($row_clubs_histo["physio"]))        {$sql.="physio = '".$row_clubs_histo["physio"]."',";}
	if (isset($row_clubs_histo["Commentaire"]))     {$sql.="Commentaire = '".$row_clubs_histo["Commentaire"]."',";}
  
	$sql=substr($sql,0,strlen($sql)-1);
	$sql.=" WHERE
           id_clubs_histo  = ".$row_clubs_histo["id_clubs_histo"]
        ;
  
	$reqValid= $conn->exec($sql);
  
	if (!$reqValid) {
		return false;
	} else {
		return $row_clubs_histo["id_clubs_histo"];
	}

}

/************************************************************************************************/
/* Objet : Nombre de jours depuis derni�re connexion du proprio                                 */
/* Modifi� le 11/06/2010 par Musta56 - Cr�ation fonction                                        */
/* Modifi� le 09/03/2011 par Musta56 - Gestion du cas ou $date_last_connexion est non renseign� */
/************************************************************************************************/
/* Entr�e : $date_last_connexion = date de derni�re connexion (aaaa-mm-dd)                      */
/* Sortie : $nbJours = nombre de jours                                                          */
/************************************************************************************************/
/* Appel� par les scripts :                                                                     */
/*           - ./includes/serviceJoueurs.php                                                    */
/************************************************************************************************/
function getNbJourLastConnexion($date_last_connexion){

	if ($date_last_connexion=="") {
	 return false;
	} else {
    /* Date du jour*/
  	$mkday = mktime(0,0,0,date('m'), date('d'),date('Y'));
  
  	/* Date de la derni�re connexion du club */
    $date = explode("-",$date_last_connexion);
  	$datemaj =  mktime(0,0,0,$date[1],$date[2],$date[0]);
  
    $nbJours=($mkday-$datemaj)/(60*60*24);
    
    return $nbJours;
  }
}

/******************************************************************************/
/* Objet : Selection du dernier historique club d'un club                     */
/* Modifi� le 21/01/2011 par Musta56 - Cr�ation fonction                      */
/******************************************************************************/
/* Entr�e : $idClubHT = Identifiant Hattrick du clubs                         */
/* Sortie : $row["id_Clubs_Histo"] = identifiant clubs_histo ligne ins�r�e    */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/*           - ./dtn_scan_team.php                                           */
/******************************************************************************/
function getLastHistoClub($idClubHT){
	global $conn;

	require($cheminComplet.'includes/nomTables.inc.php');

	$sql="SELECT
            $tbl_clubs_histo.*
        FROM 
            $tbl_clubs_histo,
            (SELECT MAX(id_Clubs_Histo) AS id_Clubs_Histo FROM $tbl_clubs_histo WHERE idClubHT=$idClubHT) lastHC
        WHERE
            lastHC.id_Clubs_Histo=$tbl_clubs_histo.id_Clubs_Histo";

	$reqValid = $conn->query($sql);
  
	if (!$reqValid) {
		return NULL;
	} else {
		if($reqValid->rowCount() == 1){
			$tabS = $reqValid->fetch(PDO::FETCH_ASSOC);
		} else {
			$tabS = NULL;
		}
		$reqValid=NULL;
		return $tabS;
	}

}


/********************************************************************************************/
/* Objet : Cr�er une connexion � HT en utilisant l'autorisation d'un membre de la dtn       */
/* Il faut obligatoirement appeler le script gestion_session.php pour que �a fonctionne     */
/* Modifi� le 10/06/2011 par Musta56 - Cr�ation fonction                                    */
/********************************************************************************************/
/* Sortie : $connexionHT = Objet repr�sentant la session HT                                 */
/********************************************************************************************/
/* Appel� par les scripts :                                                                 */
/*           - ./dtn/interface/maj/majEquipesAuto.php                                       */
/*           - ./dtn/interface/maj/majJoueursArchives.php                                   */
/********************************************************************************************/
function creerConnexionHT()
{
	global $conn;
	$connexionActive==false;
	$i=0;
  
	$clubConnexion[0]=getClubID(296241); // On utilise l'autorisation de Musta56 par d�faut
  
  
	if (!isset($clubConnexion[0]['userToken']) || 
		!isset($clubConnexion[0]['userTokenSecret']) || 
		empty($clubConnexion[0]['userToken']) || 
		empty($clubConnexion[0]['userTokenSecret']) ) {

		$sql="SELECT idAdminHT FROM ht_admin WHERE idNiveauAcces_fk IN ('1','2') AND affAdmin = '1'";
  
		$reqValid = $conn->query($sql);
    
		if (!$reqValid) {
			return false;
		} else {
			$clubConnexion[] = $reqValid->fetch(PDO::FETCH_ASSOC);;
			$reqValid=NULL;
		}
	}
  
	while ($i<count($clubConnexion) && $connexionActive==false) {
		$connexionHT = new CHPPConnection(CONSUMERKEY,CONSUMERSECRET);
  
		$connexionHT->setOauthToken($clubConnexion[$i]['userToken']);
		$connexionHT->setOauthTokenSecret($clubConnexion[$i]['userTokenSecret']);
    
		//echo("<br />HT_proprio=");var_dump($HT_proprio);
		if (isset($connexionHT)) {
  
			/*      VERIFICATION VALIDITE SESSION                                         */
			// V�rifier que la session est valide
			$check = $connexionHT->checkToken();
			//var_dump($check);echo("<br><br>".$check->isValid());exit;
			if ($check->isValid()===false) {
				unset($connexionHT);
				$i++;
			} else {
				$connexionActive=true;
			}
		}
    
	}

	return $connexionHT;

}



/********************************************************************************************/
/* Objet : maj d'un club (Ajout si n'existe pas)                                            */
/* Modifi� le 13/11/2011 par Musta56 - Cr�ation fonction                                    */
/* Modifi� le 11/10/2012 par Musta56 - Ajout Unset+ajout idUserHT getDataClubFromHT_usingPHT*/
/********************************************************************************************/
/* Entr�e : $idClubHT = identifiant club HT                                                 */
/* Entr�e : $idUserHT = identifiant user HT                                                 */
/* Entr�e : $clubDTN = Tableau de donn�es club base DTN                                     */
/* Sortie : $resu = tableau avec log modif et id club base DTN                              */
/********************************************************************************************/
/* Appel� par les scripts :                                                                 */
/*           - ./dtn/interface/maj/majEquipesAuto.php                                       */
/********************************************************************************************/
function majClub($idClubHT=null,$idUserHT=null,$clubDTN=null)
{
	unset($clubHT);
	unset($resu);
	$modifclub=False;
	if ($idClubHT === null && $idUserHT === null) {
		return false;
	}
  
	// Infos du club dans base DTN 
	if ($clubDTN === null) {
		$clubDTN = getClubID($idClubHT,$idUserHT);
	}
   
	// r�cup�ration des donn�es du club sur HT
	$clubHT=getDataClubFromHT_usingPHT($idClubHT,$idUserHT);

	if ($clubHT == false) {
		$resu["logModif"] .= "==NON CONNECTE A HATTRICK==\n";
	}

	$resu["logModif"]="";
	$resu["HTML"]="";
	$resu["histoModifMsg"]="";
	$resu["maj"]=false;

	if ($clubDTN != false) {
 
		//Test idUserHT
		if ($clubDTN["idUserHT"]!=$clubHT["idUserHT"])
		{
			$modifclub=True;
			$resu["logModif"] .= "ID user : ".$clubDTN["idUserHT"]." -> ".$clubHT["idUserHT"]."\n";
			$resu["HTML"] .= "Changement proprio-ID user :".$clubDTN["idUserHT"]." -&gt; ".$clubHT["idUserHT"]."<br />";
		}
		//Test status de bot
		if ($clubDTN["isBot"]!=$clubHT["isBot"])
		{
			$modifclub=True;
			$resu["logModif"] .= "Bot : ".$clubDTN["isBot"]." -> ".$clubHT["isBot"]."\n";
			if ($clubHT["isBot"]==1) {
				$resu["HTML"] .= "Club Botifi&eacute;<br />";
				$resu["histoModifMsg"].= "Club Botifie";
			} elseif ($clubHT["isBot"]==2) {
				$resu["HTML"] .= "Club sans manager humain<br />";
				$resu["histoModifMsg"].= "Club sans manager humain";
			}
		}

		if ($clubHT["idUserHT"]!=0 && $clubHT["idClubHT"]!=null) {
			//Test nomClub
			if ($clubDTN["nomClub"]!=$clubHT["nomClub"])
			{
				$modifclub=True;
				$resu["logModif"] .= "Nom du Club : ".$clubDTN["nomClub"]." -> ".$clubHT["nomClub"]."\n";
			}      
			//Test nomUser
			if ($clubDTN["nomUser"]!=$clubHT["nomUser"])
			{
				$modifclub=True;
				$resu["logModif"] .= "Nom de l'utilisateur : ".$clubDTN["nomUser"]." -> ".$clubHT["nomUser"]."\n";      
			}      
			//Test idPays_fk
			if ($clubDTN["idPays_fk"]!=$clubHT["idPays_fk"])
			{
				$modifclub=True;
				$resu["logModif"] .= "Pays : ".$clubDTN["idPays_fk"]." -> ".$clubHT["idPays_fk"]."\n";
			}     
			//Test niv_Entraineur
			if ($clubDTN["niv_Entraineur"]!=$clubHT["niv_Entraineur"])
			{
				$modifclub=True;
				$resu["logModif"] .= "Niveau de l'entraineur : ".$clubDTN["niv_Entraineur"]." -> ".$clubHT["niv_Entraineur"]."\n";
				$resu["HTML"] .= 'Entraineur : '.$clubDTN['niv_Entraineur'].' -&gt; '.$clubHT['niv_Entraineur'].'<br />';
				$resu["histoModifMsg"].= "Entraineur : ".$clubDTN["niv_Entraineur"]." -> ".$clubHT["niv_Entraineur"];
			}
			// Test date_last_connexion
			if($clubDTN['date_last_connexion']!=$clubHT['date_last_connexion'])
			{
				$modifclub=True;
				$nb_jour_last_connexion=getNbJourLastConnexion($clubHT['date_last_connexion']);
				if ($nb_jour_last_connexion>=28) {
					$resu['HTML'] .= ' Pas de connexion du proprio depuis '.$nb_jour_last_connexion.' jours !<br />';
				}
			}
		} // Fin Si : Club Actif sur HT
	} else { // $clubDTN n'existe pas
		if ($clubHT["idUserHT"]!=0 && $clubHT["idClubHT"]!=null) { // Existence d'un manager humain et d'un club
			$modifclub=True;
			$resu['HTML'].='<font color=red><b>(ajout club - '.getClubHREF($clubHT['idClubHT']).')</b></font><br />';
		} else {
			$modifclub=False;
		}
	} // Fin comparaison $clubDTN et $clubHT
    
	//si modification d'une ou plusieurs donn�es du club, alors maj base DTN
	if ($modifclub==True)
	{
		$resu["logModif"] .= "-id=".$clubDTN["idClubHT"]."-\n";
		//$clubHT["idClub"]=$clubDTN["idClub"]; //n�cessaire � la fonction update qui rep�re le club sur son idClub et non son idClubHT
		$resu["idClub"]=insertionClub($clubHT);

		if ($resu["idClub"]==False)
		{
			//�chec de la maj
			$resu["logModif"] .= "=> Erreur : �chec de la MAJ en base\n";
			$resu["HTML"] .= '&Eacute;chec de la MAJ club<br />';
		}
		else
		{
			//r�ussite de la maj
			$resu["logModif"] .= "=> MAJ OK\n";
			$resu["maj"]=true;
		}
		$resu["logModif"] .= "___________________\n";
	}
    
	if (!isset($resu["idClub"]) && isset($clubDTN["idClub"])) {$resu["idClub"]=$clubDTN["idClub"];}
  
	unset($clubDTN);
	unset($clubHT);
	unset($modifclub);
	unset($nb_jour_last_connexion);

	return $resu;

}


/********************************************************************************************/
/* Objet : maj d'un histo club                                                              */
/* Modifi� le 13/11/2011 par Musta56 - Cr�ation fonction                                    */
/* Modifi� le 11/10/2012 par Musta56 - Ajout unset($row_clubs_histo)                        */
/********************************************************************************************/
/* Entr�e : $idClubHT = identifiant club HT (si null alors id du club connect�)             */
/* Entr�e : $cree_par = nom user HT                                                         */
/* Entr�e : $role_createur = P (proprio), D (DTN), S (S�lectionneur)                        */
/* Sortie : $resu = tableau avec log modif et id club base DTN                              */
/********************************************************************************************/
/* Appel� par les scripts :                                                                 */
/*           - ./dtn/interface/maj/majEquipesAuto.php                                       */
/********************************************************************************************/
function majClubHisto($idClubHT,$cree_par,$role_createur)
{
  require_once($cheminComplet.'includes/serviceEntrainement.php');

  unset ($resu);
  $modif=false;
  $trainingList = listEntrainement();
  // Collecte information sur HT
  if ($idClubHT==null) {
    $row_clubs_histo = getDataClubsHistoFromHT_usingPHT();
  } else { // Utilisateur connect�
    $row_clubs_histo = getDataClubsHistoFromHT_usingPHT($idClubHT);
  }
  if ($row_clubs_histo == false) { // Erreur lors de la r�cup�ration des donn�es 
    unset($trainingList);
    unset($row_clubs_histo);
    unset($modif);
    return false;
  } 
  
  if ($row_clubs_histo != 0) { // Si on a r�cup�r� les donn�es
    $row_clubs_histo['cree_par']=$cree_par;
    $row_clubs_histo['role_createur']=$role_createur;
    $resu["HTML"] = "<font color=\"blue\"><b>";
    $resu["histoModifMsg"] = "";
    
    $row_last_clubs_histo=getLastHistoClub($idClubHT);
    
    if ($row_last_clubs_histo != 0 && $row_last_clubs_histo != -1) { // Il existe d�j� un histo club
      if ($row_last_clubs_histo["idEntrainement"] != $row_clubs_histo["idEntrainement"]) {
        $resu["HTML"] .= "Entrainement : ".getEntrainementName($row_last_clubs_histo["idEntrainement"],$trainingList)."-&gt;".getEntrainementName($row_clubs_histo["idEntrainement"],$trainingList)."<br />";
        $resu["histoModifMsg"] .= "Entrainement : ".getEntrainementName($row_last_clubs_histo["idEntrainement"],$trainingList)."-&gt;".getEntrainementName($row_clubs_histo["idEntrainement"],$trainingList);
        $modif=true;
      }
      if ($row_last_clubs_histo["intensite"] != $row_clubs_histo["intensite"]) {
        $resu["HTML"] .= "intensite : ".$row_last_clubs_histo["intensite"]."-&gt;".$row_clubs_histo["intensite"]."<br />";
        $modif=true;
      }
      if ($row_last_clubs_histo["endurance"] != $row_clubs_histo["endurance"]) {
        $resu["HTML"] .= "% endu : ".$row_last_clubs_histo["endurance"]."-&gt;".$row_clubs_histo["endurance"]."<br />";
        $modif=true;
      }
      if ($row_last_clubs_histo["adjoints"] != $row_clubs_histo["adjoints"]) {
        $resu["HTML"] .= "Nbre adjoints : ".$row_last_clubs_histo["adjoints"]."-&gt;".$row_clubs_histo["adjoints"]."<br />";
        $modif=true;
      }
      if ($row_last_clubs_histo["medecin"] != $row_clubs_histo["medecin"]) {
        $resu["HTML"] .= "Medecin : ".$row_last_clubs_histo["medecin"]."-&gt;".$row_clubs_histo["medecin"]."<br />";
        $modif=true;
      }
      if ($row_last_clubs_histo["physio"] != $row_clubs_histo["physio"]) {
        $resu["HTML"] .= "Prepa physique : ".$row_last_clubs_histo["physio"]."-&gt;".$row_clubs_histo["physio"]."<br />";
        $modif=true;
      }
    } elseif ($row_last_clubs_histo == 0) { // C'est le premier histo d'un club
      $modif=true;
    }
  
    // Si la maj est faite par le proprio, on ins�re syst�matiquement l'histo
    if ($role_createur = 'P') {
      $modif=true;
    }
  
    if ($modif==true) {
    	// Insertion HistoClub
    	$resu["id_clubs_histo"]=insertHistoClub($row_clubs_histo);
    	$resu["HTML"] .= "</b></font>";
    } else {
      unset ($resu["HTML"]);
      unset ($resu["histoModifMsg"]);
      $resu=false;
    }
	} else { // On a pas l'autorisation du proprio
    $resu=0;
  }
	
	unset($trainingList);
	unset($row_clubs_histo);
	unset($modif);
   
	return $resu;

}
?>
