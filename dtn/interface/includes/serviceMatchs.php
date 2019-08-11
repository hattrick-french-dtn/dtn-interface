<?php

// This function returns last match played by a given player  
// with playerid=$joueur_id

function listMatchJoueur($joueur_id){
	global $conn;
  	global $limitMatch;
  	$sql =  "SELECT *
          FROM ht_perfs_individuelle where id_joueur = $joueur_id  ORDER BY date_match DESC ";

  	if($limitMatch != "") $sql .= $limitMatch;

  	$tabS = array();
  	foreach ($conn->query($sql) as $row){
	 	array_push($tabS, $row);
	}

  	return $tabS;
}

// This function checkif a match exists in database for a specific week and a specific player  
// with playerid=$joueur_id
function checkMatchJoueur($joueur_id,$season,$week,$maBase){
  $sql =  "SELECT	count(*) from ht_perfs_individuelle where id_joueur = $joueur_id  and ".
	        " week='$week' and season='$season' LIMIT 1";

  $resMatch = $maBase->select($sql);
  $countMatch  = $resMatch[0][0];
  if ($countMatch==1) return true;
  
  return false;
}

// This function is used when an automated update has to be made to the 
// ht_perfs_individuelle table.
// sample : makWeekSeasonMatch
// return array of 100 matchs from the match $lot-100 to $lot
// @author gwedalou
function getMatchByPacket($lot){
	global $conn;
	$lot2=$lot-100;

	$sql =  "SELECT	* from ht_perfs_individuelle  ORDER BY date_match DESC LIMIT $lot2,100";
	
	$tabS = array();
	foreach($conn->query($sql) as $row){
		array_push($tabS, $row);
	}
	return $tabS; 
}

// This function is used tu get already updated form and tsi values 
// from ht_perfs_individuelle table.
// 
// return array with form and tsi 
// MUSTA56 : NE DOIT PLUS SERVIR NORMALEMENT
/*function getForme($week,$season,$idjoueur){
	
	$sql =  "SELECT	tsi, forme from ht_perfs_individuelle  where week=$week and season=$season and id_joueur=$idjoueur LIMIT 1";
	
    $result= mysql_query($sql);
	if ($row =  mysql_fetch_array($result)){
	return $row;
	}
	return null;
}*/
//
// check if match is already include in database
// MUSTA56 : NE PLUS S'EN SERVIR, UTILISER existMatchBaseDTN
function isMatchRecorded($id_match,$idjoueur){
	global $conn;
	$sql =  "SELECT	id_match from ht_perfs_individuelle  where id_match=$id_match and id_joueur=$idjoueur LIMIT 1";
	
    $result= $conn->query($sql);
	if ($row = $result->fetch()){
		return true;
	}
	return false;
}


// This function is used to insert match if needed 
// return true if insert is success
// MUSTA56 : NE PLUS S'EN SERVIR, UTILISER insert_perfs_individuelle
function insertMatchAutomatic($perf){
	
		if( isMatchRecorded($perf["id_match"],$perf["id_joueur"])){
			return false;
		}
		
    $_POST["season"] = $perf["season"];
    $_POST["week"] = $perf["week"];
		$_POST["id_joueur"] = $perf["id_joueur"];
		$_POST["id_match"] = $perf["id_match"];
		$_POST["date_match"] = $perf["date_match"];
    $_POST["id_club"] = $perf["id_club"];	 		
		$_POST["id_role"] = $perf["id_role"];
		$_POST["id_position"] = $perf["id_position"];
		$_POST["id_behaviour"] = $perf["id_behaviour"];
		$_POST["etoile"] = $perf["etoile"];
		$_POST["etoileFin"] = $perf["etoileFin"];
		$_POST["idTypeMatch_fk"] = $perf["idTypeMatch_fk"];
		

		insertDB("ht_perfs_individuelle");
		return true;

}

// This function is only a one shot update for the  
// ht_perfs_individuelle table.
// it updates Week and Season for a given match 
// @return number of row updated
// After the week and season are updated directly when new matchs are entered (in form.php)
// @author gwedalou

function updateSeasonWeekMatch($matchid,$playerid,$season,$week){
	global $conn;
	$sql =  "update ht_perfs_individuelle  set season='$season',week='$week' where id_joueur='$playerid' and id_match='$matchid' LIMIT 1";
	$result= $conn->exec($sql);

	return $result;
}



/******************************************************************************************/
/* Objet : Renvoi le numéro de saison et le numéro de semaine de la date passé en entrée  */
/* Modifié le 10/02/2010 par jojoje86  - semaine de 1 à 16 au lieu de 0 à 15              */
/******************************************************************************************/
/* Entrée : - teamid = Identifiant Hattrick du club                                       */
/* Sortie : tableau contenant les numéros de saison et semaine                            */
/******************************************************************************************/
/* Appelé par les scripts :                                                               */
/*           - dtn/interface/includes/serviceMatchs.php                                   */
/******************************************************************************************/
function getSeasonWeekOfMatch($unixTime){
	global $conn;

	$sql = " SELECT
		UNIX_TIMESTAMP('1997-05-31') as date0
		FROM dual";

	$req = $conn->query($sql);
	$res = $req->fetch();
	
	$dateInitial = $res["date0"];
	$moisDebutSaison=date("m",$dateInitial);
	$jourDebutSaison=date("d",$dateInitial);
	$anneeDebutSaison=date("Y",$dateInitial);
	
	$difference = round(($unixTime - $dateInitial)/(24*60*60),0); //différence entre la date de la semaine 0(1) saison 1 et la date en paramètre
	$res["season"]=floor($difference/112);
	
	$datedebutsaison = mktime(0, 0, 0, $moisDebutSaison, $jourDebutSaison+(112*$res["season"]), $anneeDebutSaison);
	$difference = round(($unixTime - $datedebutsaison)/(24*60*60),0); //différence entre la date du début de saison de la date en paramètre et la date en paramètre
	$res["week"]=floor(1+$difference/7);
	
	return $res;
	
}



/******************************************************************************************/
/* Objet : Renvoi les derniers matchs terminés de l'équipe à partir des données Hattrick  */
/* Modifié le ??/??/2009 par Musta  - Création                                            */
/******************************************************************************************/
/* Entrée : - teamid = Identifiant Hattrick du club                                       */
/*          - ht_session = instance objet session hattrick                                */
/* Sortie : tableau contenant les dernier matchs (id match, date match, type match...)    */
/******************************************************************************************/
/* Appelé par les scripts :                                                               */
/*           - dtn/interface/maliste/miseajour.php                                        */
/******************************************************************************************/
/*function getDerniersMatchsTeam($teamid,$ht_session)
{
  $xmlm=$ht_session->GetMatchesBeforeToday($teamid);
  $treem = GetXMLTree($xmlm);
  $matchToLoad=array();
  $z=0;
  //   iterate through match list to find appropriate game.
  foreach($treem["HATTRICKDATA"][0]["TEAM"][0]["MATCHLIST"][0]["MATCH"] as $match){
    if ($match["STATUS"][0]["VALUE"]=="FINISHED"){ //only already finished game
      $matchToLoad[$z]["MATCHID"]=$match["MATCHID"][0]["VALUE"];
      $matchToLoad[$z]["MATCHDATE"]=$match["MATCHDATE"][0]["VALUE"];
      $matchToLoad[$z]["MATCHTYPE"]=$match["MATCHTYPE"][0]["VALUE"];
      $z++;
    }
  }
  unset($treem);
  unset($xmlm);
  return $matchToLoad;
}
*/


/******************************************************************************************/
/* Objet : Renvoi les derniers matchs terminés de l'équipe à partir des données Hattrick  */
/* Modifié le 11/05/2011 par Musta  - Création                                            */
/* Modifié le 19/10/2012 par Musta  - Ajout filtre pour écarter les matchs de tournois    */
/******************************************************************************************/
/* Entrée : - $teamid = Identifiant Hattrick du club                                      */
/* Entrée : - $date_mini_match = On ne prend que les matchs postérieurs à la Date minimum */
/* Sortie : tableau contenant les dernier matchs (id match, date match, type match...)    */
/******************************************************************************************/
/* Appelé par les scripts :                                                               */
/*           - dtn/interface/include/serviceMatchs.php                                    */
/******************************************************************************************/
function getDataDerniersMatchsTeam_usingPHT($teamid,$date_mini_match=null)
{
  $lesMatchs=$_SESSION['HT']->getSeniorTeamMatches($teamid)->getLastMatches();
  $matchToLoad=array();
  $i=0;

  //   iterate through match list to find appropriate game.
  foreach($lesMatchs as $match){
    if (($match->getDate() >= $date_mini_match || $date_mini_match === null) && ($match->isTournament()==false)  && $match->getType() != 62){
      $matchToLoad[$i]["MATCHID"]=$match->getId();
      $matchToLoad[$i]["MATCHDATE"]=$match->getDate();
      $matchToLoad[$i]["MATCHTYPE"]=$match->getType();
      $i++;
    }
  }
  $_SESSION['HT']->clearSeniorTeamMatches();
  unset($lesMatchs);
  return $matchToLoad;
}


/******************************************************************************************/
/* Objet : test l'existence d'un match dans la base                                       */
/* Modifié le ??/??/???? par Musta  - Création                                            */
/* Modifié le 11/05/2011 par Musta  - $maBase devient facultatif                          */
/******************************************************************************************/
/* Entrée : - $matchid = Identifiant Hattrick du match                                    */
/* Entrée : - $joueurid = Identifiant Hattrick du joueur                                  */
/* Entrée : - $maBase = Objet connexion à la base (éviter son utilisation)                */
/* Sortie : booléen : true si le match existe sinon false                                 */
/******************************************************************************************/
/* Appelé par les scripts :                                                               */
/*           - dtn/interface/serviceMatchs.php                                            */
/******************************************************************************************/
function existMatchBaseDTN($matchid,$joueurid,$maBase=null)
{
	global $conn;
	$sql =  "SELECT	count(id_match) as nbMatch
           FROM ht_perfs_individuelle 
           WHERE id_match = $matchid 
           AND id_joueur = $joueurid
           LIMIT 1";

	if ($maBase!=null) {
		$res = $maBase->select($sql);
		$countMatch  = $res['nbMatch'];
	} else {
		$req= $conn->query($sql);
    
		if ($req!=false) {
			$res = $req->fetch();
			$countMatch = $res['nbMatch'];
		}
	}
	if ($countMatch==1) {return true;}
  
	return false;
}



// Insert les lineUp d'un match joué par un joueur
// Si matchID non renseigné alors on prend le dernier match de l'équipe
/*function insererMatchJoueur($ht_session,$playerid,$teamid,$matchID="LAST")
{
  $perfIndividuelle=array();
  $matchToLoad=array();
  $z=0;
  $joueurTrouve=0;
  
  if ($matchID=="LAST") {$xmlLineUp=$ht_session->GetMatchLineup($teamid);}
  else {$xmlLineUp=$ht_session->GetMatchLineup($matchID,$teamid);}
  $lineUp = GetXMLTree($xmlLineUp);
  
  foreach($lineUp["HATTRICKDATA"][0]["TEAM"][0]["LINEUP"][0]["PLAYER"] as $l){
    if ($playerid==$l["PLAYERID"][0]["VALUE"]){

//      if (!isset($l["ROLEID"][0]["VALUE"])) {echo('Role sur le terrain non defini pour le joueur : '.$playerid.' au cours du match : '.$lineUp["HATTRICKDATA"][0]["MATCHID"][0]["VALUE"].'-<br>');}
      if ( ((isset($l["ROLEID"][0]["VALUE"])) && ($l["ROLEID"][0]["VALUE"]>18 || $l["ROLEID"][0]["VALUE"]<12)) ||
         (!isset($l["ROLEID"][0]["VALUE"])) )
      {
        //  (dans ce cas il a vraiment joue sur le terrain ou a voulu jouer mais a ete blesse)
        // On exclut les roles remplacant, capitaine et tireur de CF
        $joueurTrouve=1;

        $dateYYYYMMDD = explode("-",substr($lineUp["HATTRICKDATA"][0]["MATCHDATE"][0]["VALUE"],0,10));
        $hourHHMMSS = explode(":",substr($lineUp["HATTRICKDATA"][0]["MATCHDATE"][0]["VALUE"],10));
        $matchSeason=getSeasonWeekOfMatch(mktime($hourHHMMSS[0],$hourHHMMSS[1],$hourHHMMSS[2],$dateYYYYMMDD[1],$dateYYYYMMDD[2],$dateYYYYMMDD[0]));  

        $perfIndividuelle["season"]=$matchSeason["season"];
        $perfIndividuelle["week"]=$matchSeason["week"];
        $perfIndividuelle["id_joueur"]=$playerid;
        $perfIndividuelle["id_match"]=$lineUp["HATTRICKDATA"][0]["MATCHID"][0]["VALUE"];
        $perfIndividuelle["date_match"]=$lineUp["HATTRICKDATA"][0]["MATCHDATE"][0]["VALUE"];
        $perfIndividuelle["id_club"]=$teamid;
        if (isset($l["ROLEID"][0]["VALUE"])){
          $perfIndividuelle["id_role"]=$l["ROLEID"][0]["VALUE"];
        }else{
          $perfIndividuelle["id_role"]="0";
        }
        if (isset($l["POSITIONCODE"])){
          $perfIndividuelle["id_position"]=$l["POSITIONCODE"][0]["VALUE"];
        }else{
          $perfIndividuelle["id_position"]="0";
        }
        if (isset($l["BEHAVIOUR"])){
          $perfIndividuelle["id_behaviour"]=$l["BEHAVIOUR"][0]["VALUE"];
        }else{
          $perfIndividuelle["id_behaviour"]="0";
        }
        $perfIndividuelle["etoile"]=$l["RATINGSTARS"][0]["VALUE"];
        $perfIndividuelle["etoileFin"]=$l["RATINGSTARSENDOFMATCH"][0]["VALUE"];
        $perfIndividuelle["idTypeMatch_fk"]=$lineUp["HATTRICKDATA"][0]["MATCHTYPE"][0]["VALUE"];
        
        if (insertMatchAutomatic($perfIndividuelle)){
          return $perfIndividuelle;
        }
      }
    }
  } // Fin Boucle
}
*/


/******************************************************************************************/
/* Objet : Renvoie les données d'une perf individuelle en provenance de la base dtn       */
/* Modifié le 12/05/2011 par Musta  - Création                                            */
/******************************************************************************************/
/* Entrée : - $id_joueur = Identifiant Hattrick du joueur                                 */
/* Entrée : - $id_match = Identifiant Hattrick du match                                   */
/* Entrée : - $id_perfs_individuelle = Identifiant perf (PK)                              */
/* Sortie : - $tabS : tableau contenant les données perfs                                 */
/******************************************************************************************/
/* Appelé par les scripts :                                                               */
/*           - dtn/interface/serviceMatchs.php                                            */
/******************************************************************************************/
function get_perfs_individuelle_byID ($id_joueur=null,$id_match=null,$id_perfs_individuelle=null)
{
	global $conn;
	require($_SERVER['DOCUMENT_ROOT'].'/dtn/interface/includes/nomTables.inc.php');
  
	// Si les paramètres d'entrée sont mal renseignés
	if (  ($id_joueur===null && $id_match===null && $id_perfs_individuelle===null) ||
        ($id_joueur!==null && $id_match===null && $id_perfs_individuelle===null) ||
        ($id_joueur===null && $id_match!==null && $id_perfs_individuelle===null) )
	{
		return false;
	}
  
	$sql = "SELECT
              season,
              week,
              id_joueur,
              id_match,
              date_match,
              id_club,
              id_role,
              id_position,
              id_behaviour,
              etoile,
              etoileFin,
              idTypeMatch_fk
          FROM
              $tbl_perf";

	if ($id_joueur!=null && $id_match!=null) {
		$sql .= " WHERE id_joueur = $id_joueur AND id_match = $id_match";
	} elseif ($id_perfs_individuelle!=null) {
		$sql .= " WHERE id_perfs_individuelle = $id_perfs_individuelle";
	}

	$result = $conn->query($sql);
	$tabS = $result->fetch();
	
	$result = NULL;
	return	$tabS;
}


/******************************************************************************************/
/* Objet : Calcul de la position en fonction du role. N'existe plus dans HT CHPP          */
/* Créé le 19/10/2012 par Musta  - Création                                               */
/******************************************************************************************/
/* Entrée : - $roleID = Identifiant Role                                                  */
/* Sortie : - $position : Id Position                                                     */
/******************************************************************************************/
/* Appelé par les scripts :                                                               */
/*           - dtn/interface/serviceMatchs.php                                            */
/******************************************************************************************/
function getPositionFromRole($roleID)
{
   if ($roleID == 100) {
      $position = 1; // Gardien
   } elseif ( ($roleID == 101) || ($roleID == 102) || ($roleID == 103) || ($roleID == 104) || ($roleID == 105)) {
      $position = 2; // Défenseur
   } elseif ( ($roleID == 106) || ($roleID == 110) ) {
      $position = 3; // Ailier
   } elseif ( ($roleID == 107) || ($roleID == 108) || ($roleID == 109) ) {
      $position = 4; // Milieu
   } elseif ( ($roleID == 111) || ($roleID == 112) || ($roleID == 113) ) {
      $position = 5; // Attaquant
   } else {
      $position = 0; // Inconnu
   }
   
   return $position;
   //return 0;
}


/******************************************************************************************/
/* Objet : Récupère sur HT le détail match d'un joueur et insère dans la base             */
/* Modifié le 11/05/2011 par Musta  - Création                                            */
/* Modifié le 10/12/2011 par Musta  - Appel getFinalLineup suite à bascule sur PHT 2.10.1 */
/* Modifié le 19/10/2012 par Musta  - Appel getPosition                                   */
/******************************************************************************************/
/* Entrée : - $playerid = Identifiant Hattrick du joueur                                  */
/* Entrée : - $teamid = Identifiant Hattrick du club                                      */
/* Entrée : - $matchID = Identifiant Hattrick du match (si null alors dernier match       */
/* Sortie : - $perfIndividuelle : tableau contenant les infos du match du joueur          */
/******************************************************************************************/
/* Appelé par les scripts :                                                               */
/*           - dtn/interface/serviceMatchs.php                                            */
/******************************************************************************************/
function getDataMatchJoueur($playerid,$teamid,$matchID=null)
{
  //echo('<br />joueur='.$playerid.'-match='.$matchID.'-teamid='.$teamid.'<br />');
  // Initialisation des variables
  $perfIndividuelle=array();
  $i=1;
  $joueurTrouve=false;
  
  // On récupère sur HT la compo du match
  $lineUp=$_SESSION['HT']->getSeniorLineup($matchID, $teamid);

  // On supprime les compos du cache
  $_SESSION['HT']->clearSeniorLineups();

  // Boucle sur les joueurs de la compo
  while ($i <= $lineUp->getFinalLineup()->getPlayersNumber() && $joueurTrouve==false) {

    if ($playerid==$lineUp->getFinalLineup()->getPlayer($i)->getId()){
      $joueurTrouve=true; // Pour sortir de la boucle à la prochaine itération

      $id_role=$lineUp->getFinalLineup()->getPlayer($i)->getRole();     
      $matchDate=$lineUp->getMatchDate(); // Obligé de stocker dans une variable parce que le framework ne permet pas d'écraser la valeur d'1 propriété
      
      if (  ( isset($id_role) && 
              $id_role!=12 &&
              $id_role!=13 &&
              $id_role!=14 &&
              $id_role!=15 &&
              $id_role!=16 &&
              $id_role!=17 &&
              $id_role!=18 &&
              $id_role!=114 &&
              $id_role!=115 &&
              $id_role!=116 &&
              $id_role!=117 &&
              $id_role!=118
            ) ||
            (!isset($id_role)) )
      {
        // dans ce cas il a vraiment joue sur le terrain ou a voulu jouer mais a ete remplace
        // On exclut les roles remplacant, capitaine et tireur de CF

        $matchSeason=getSeasonWeekOfMatch(mktime( HTFunction::convertDate($matchDate, "H"),
                                                  HTFunction::convertDate($matchDate, "i"),
                                                  HTFunction::convertDate($matchDate, "s"),
                                                  HTFunction::convertDate($matchDate, "n"),
                                                  HTFunction::convertDate($matchDate, "j"),
                                                  HTFunction::convertDate($matchDate, "Y")));  
        $perfIndividuelle["season"]=$matchSeason["season"];
        $perfIndividuelle["week"]=$matchSeason["week"];
        $perfIndividuelle["id_joueur"]=$playerid;
        $perfIndividuelle["id_match"]=$lineUp->getMatchId();
        $perfIndividuelle["date_match"]=$matchDate;
        $perfIndividuelle["id_club"]=$teamid;
        if (!isset($perfIndividuelle["id_role"])) {$perfIndividuelle["id_role"]=0;}
        $perfIndividuelle["id_position"]=getPositionFromRole($id_role);
        if (!isset($perfIndividuelle["id_position"]) || empty($perfIndividuelle["id_position"])) {$perfIndividuelle["id_position"]=0;}
        $perfIndividuelle["id_behaviour"]=$lineUp->getFinalLineup()->getPlayer($i)->getIndividualOrder(); 
        if (!isset($perfIndividuelle["id_behaviour"]) || empty($perfIndividuelle["id_behaviour"])) {$perfIndividuelle["id_behaviour"]=0;}
        $perfIndividuelle["etoile"]=$lineUp->getFinalLineup()->getPlayer($i)->getRatingStars();
        $perfIndividuelle["etoileFin"]=$lineUp->getFinalLineup()->getPlayer($i)->getRatingStarsAtEndOfMatch();
        $perfIndividuelle["idTypeMatch_fk"]=$lineUp->getMatchType();
        $perfIndividuelle["id_role"]=$id_role;
        
        unset ($matchSeason);
      }
      
      unset ($id_role);
      unset ($matchDate);
    }
    $i++;
    
  } // Fin Boucle
  
  unset ($lineUp);
  unset ($joueurTrouve);
  unset ($i);
  
  return $perfIndividuelle;
}


/******************************************************************************************/
/* Objet : MAJ dans la base DTN de la perf d'un joueur                                    */
/* Modifié le 12/05/2011 par Musta  - Création                                            */
/******************************************************************************************/
/* Entrée : - $row_perf = Tableau contenant le match à charger en base                    */
/* Sortie : - Identifiant de la perf MAJ en base                                          */
/******************************************************************************************/
/* Appelé par les scripts :                                                               */
/*           - dtn/interface/includes/serviceMatchs.php                                   */
/******************************************************************************************/
function update_perfs_individuelle($row_perf)
{
	global $conn;
	require($_SERVER['DOCUMENT_ROOT'].'/dtn/interface/includes/nomTables.inc.php');

	$sql="UPDATE $tbl_perf SET ";
	if (isset($row_perf["season"]))         {$sql.="season = ".$row_perf["season"].",";}
	if (isset($row_perf["week"]))           {$sql.="week = ".$row_perf["week"].",";}
	if (isset($row_perf["date_match"]))     {$sql.="date_match = '".$row_perf["date_match"]."',";}
	if (isset($row_perf["id_club"]))        {$sql.="id_club = ".$row_perf["id_club"].",";}
	if (isset($row_perf["id_role"]))        {$sql.="id_role = ".$row_perf["id_role"].",";}
	if (isset($row_perf["id_position"]))    {$sql.="id_position = ".$row_perf["id_position"].",";}
	if (isset($row_perf["id_behaviour"]))   {$sql.="id_behaviour = ".$row_perf["id_behaviour"].",";}
	if (isset($row_perf["etoile"]))         {$sql.="etoile = ".$row_perf["etoile"].",";}
	if (isset($row_perf["etoileFin"]))      {$sql.="etoileFin = ".$row_perf["etoileFin"].",";}
	if (isset($row_perf["idTypeMatch_fk"])) {$sql.="idTypeMatch_fk = ".$row_perf["idTypeMatch_fk"].",";}
  
	$sql=substr($sql,0,strlen($sql)-1); // On enlève la dernière virgule

	if (isset($row_perf["id_perfs_individuelle"])) {
		$sql.=" WHERE id_perfs_individuelle  = ".$row_perf["id_perfs_individuelle"];
	} elseif (isset($row_perf["id_joueur"]) && isset($row_perf["id_match"])) {
		$sql.=" WHERE id_joueur = ".$row_perf["id_joueur"]." AND id_match  = ".$row_perf["id_match"];
	} else return false;
	//echo($sql);
	$reqValid= $conn->exec($sql);

	if (!$reqValid) {
		return false;
	} else {
		return $row_perf["id_perfs_individuelle"];
	}
}


/******************************************************************************************/
/* Objet : Insertion dans la base DTN de la perf d'un joueur                              */
/* Modifié le 12/05/2011 par Musta  - Création                                            */
/******************************************************************************************/
/* Entrée : - $row_perf = Tableau contenant le match à charger en base                    */
/* Sortie : - Identifiant de la perf chargée en base                                      */
/******************************************************************************************/
/* Appelé par les scripts :                                                               */
/*           - dtn/interface/includes/serviceMatchs.php                                   */
/******************************************************************************************/
function insert_perfs_individuelle($row_perf)
{
	global $conn;
	require($_SERVER['DOCUMENT_ROOT'].'/dtn/interface/includes/nomTables.inc.php');

	if (!isset($row_perf['id_club']))       $row_perf['id_club']='NULL';
	if (!isset($row_perf['id_role']))       $row_perf['id_role']='NULL';
	if (!isset($row_perf['id_position']))   $row_perf['id_position']='NULL';
	if (!isset($row_perf['id_behaviour']))  $row_perf['id_behaviour']='NULL';

	$rech_perf=get_perfs_individuelle_byID($row_perf['id_match'],$row_perf['id_joueur']);

	if ($rech_perf==false) { /* la perf n'existe pas dans la base => on l'insère*/
		$sql = "INSERT INTO $tbl_perf 
                ( season,
                  week,
                  id_joueur,
                  id_match,
                  date_match,
                  id_club,
                  id_role,
                  id_position,
                  id_behaviour,
                  etoile,
                  etoileFin,
                  idTypeMatch_fk) 
              VALUES 
                ( ".$row_perf['season'].",
                  ".$row_perf['week'].",
                  ".$row_perf['id_joueur'].",
                  ".$row_perf['id_match'].",
                  '".$row_perf['date_match']."',
                  ".$row_perf['id_club'].",
                  ".$row_perf['id_role'].",
                  ".$row_perf['id_position'].",
                  ".$row_perf['id_behaviour'].",
                  ".$row_perf['etoile'].",
                  ".$row_perf['etoileFin'].",
                  ".$row_perf['idTypeMatch_fk']."
                )";
                 
		$reqValid= $conn->exec($sql);
      
		if (!$reqValid) {
			return false;
		} else {
			return $conn->lastInsertId();
		}

	} elseif (isset($row_perf['id_perfs_individuelle']) == 1){ /* la perf existe dans la base => on le met à jour */
		$row_perf['id_perfs_individuelle'] = $rech_perf['id_perfs_individuelle'];
		return update_perfs_individuelle($row_perf);
	} else {
		return false;
	}
}


/******************************************************************************************/
/* Objet : Insère dans la base DTN les matchs récents d'un joueur                         */
/* Modifié le 12/05/2011 par Musta  - Création                                            */
/******************************************************************************************/
/* Entrée : - $playerid = Identifiant Hattrick du joueur                                  */
/* Entrée : - $teamid = Identifiant Hattrick du club                                      */
/* Entrée : - $dateLastScanMatchJoueur = Date de dernier scan des matchs du joueur        */
/* Sortie : - $perf : tableau contenant les matchs chargés                                */
/******************************************************************************************/
/* Appelé par les scripts :                                                               */
/*           - dtn/interface/maliste/miseajour.php                                        */
/******************************************************************************************/
function insererMatchsJoueur($playerid,$teamid,$dateLastScanMatchJoueur=null)
{

  // Rechercher matchs du joueur sur Hattrick
  $listMatchs=getDataDerniersMatchsTeam_usingPHT($teamid,$dateLastScanMatchJoueur);
  $perf=array();
  $perf['HTML']="";

  // Boucle sur les matchs
  $i=0;
  $nbMatchsInsere=0;
  while ($i < count($listMatchs)) {
    if (!existMatchBaseDTN($listMatchs[$i]["MATCHID"],$playerid)) { // Si le match n'existe pas dans la bdd dtn => on l'insère
      //echo("<br />matchid=".$listMatchs[$i]["MATCHID"]."///playerid=".$playerid);
      $perf[$nbMatchsInsere]=getDataMatchJoueur($playerid,$teamid,$listMatchs[$i]["MATCHID"]);

      if (!empty($perf[$nbMatchsInsere])) { // Le joueur a été aligné dans le match => On insère

        if ($nbMatchsInsere>0) {
          $perf['HTML'].="<tr><td colspan=7>&nbsp;</td>";
        }
//echo('01');
        $role=get_role_byID($perf[$nbMatchsInsere]["id_role"]);
//echo('toto');
//        $behaviour=get_behaviour_byID($perf[$nbMatchsInsere]["id_behaviour"]);
//echo('02');
        $perf['HTML'].="<td>".$perf[$nbMatchsInsere]['id_match']."</td>
                        <td align=center bgcolor=#fded84><b>".$perf[$nbMatchsInsere]['etoile']."/".$perf[$nbMatchsInsere]['etoileFin']."</b></td>
                        <td>".$role['nom_role_abbrege']."</td></tr>";

        $perf[$nbMatchsInsere]['id_perfs_individuelle'] = insert_perfs_individuelle($perf[$nbMatchsInsere]);
        $nbMatchsInsere++;
      } else {
        unset($perf[$nbMatchsInsere]);
      }
    }
    $i++;
  }
  if ($nbMatchsInsere==0) {
    $perf['HTML'].="<td colspan=3>Pas de nouveau match.</td></tr>";
  }
  
  return $perf;
}

?>
