<?php

// Mise a jour de l'historique :
function listCarac($sens,$limit)
{
	global $conn;
	$tabS = array();
	$sql = "select * from ht_caracteristiques";
	$sql .= " limit 0, $limit"; 

	foreach($conn->query($sql) as $row){
		array_push($tabS, $row);
	}
	
	if($sens == "ASC") array_multisort($tabS,SORT_ASC); else array_multisort($tabS,SORT_DESC);
	return	$tabS;
}


function listTypeCarac()
{
	global $conn;
	$tabS = array();
	$sql = "select * from ht_typecarac";
	$sql .= " order by idTypeCarac Asc"; 

	foreach($conn->query($sql) as $row){
		array_push($tabS, $row);
	}
	return	$tabS;
}


/******************************************************************************/
/* Objet : Renvoie la liste des caracs entrainables                           */
/* Modifié le 10/05/2011 par Musta56 - Création fonction                      */
/******************************************************************************/
/* Entrée : aucun                                                             */
/* Sortie : $tabS = tableau avec liste des caracs                             */
/******************************************************************************/
/* Appelé par les scripts :                                                   */
/*           - fff_help.php                                                   */
/******************************************************************************/
function listTypeCaracEntrainable()
{
	global $conn;
	$tabS = array();
	$sql = "SELECT * FROM ht_typecarac WHERE isEntrainable = 1 
          ORDER BY tri_carac Asc"; 

	foreach($conn->query($sql) as $row){
		array_push($tabS, $row);
	}
	return	$tabS;
}


/******************************************************************************/
/* Objet : Renvoie les infos d'une carac en fonction de son ID                */
/* Modifié le 10/05/2011 par Musta56 - Création fonction                      */
/******************************************************************************/
/* Entrée : $id = Identifiant carac                                           */
/* Entrée : $liste = Liste des caracs                                         */
/* Sortie : $tabS = tableau avec infos carac                                  */
/******************************************************************************/
/* Appelé par les scripts :                                                   */
/*           - ./dtn/interface/admin/liste_repreneur_iiihelp.php              */
/******************************************************************************/
function get_Carac_byID($id,$liste=null)
{
	global $conn;
	if (empty($liste)) {

		$sql = "SELECT * FROM ht_typecarac WHERE idTypeCarac = $id";
  
		$result = $conn->query($sql);
		if ($result->rowCount()==1) { $tabS = $result->fetch(); }
		else { $tabS=false; }
		$result=NULL;
		return	$tabS;
	} else {
		// On a la liste des rôles en paramètres
		foreach($liste as $l){
			if($id == $l["idTypeCarac"]){
				return $l;
			}
		}
		return null;
	}
}



/******************************************************************************/
/* Objet : Renvoie la liste des ordres individuels (behaviour)                */
/* Modifié le 24/05/2011 par Musta56 - Création fonction                      */
/******************************************************************************/
/* Entrée : aucun                                                             */
/* Sortie : $tabS = tableau avec liste des caracs                             */
/******************************************************************************/
/* Appelé par les scripts :                                                   */
/*           - dtn/interface/maliste/miseajour.php                            */
/******************************************************************************/
function list_behaviour()
{
	global $conn;
	$tabS = array();
	$sql = "SELECT * FROM ht_behaviour"; 

	foreach($conn->query($sql) as $row){
		array_push($tabS, $row);
	}
	return	$tabS;
}



/******************************************************************************/
/* Objet : Renvoie les infos d'une carac en fonction de son ID                */
/* Modifié le 24/05/2011 par Musta56 - Création fonction                      */
/******************************************************************************/
/* Entrée : $id = Identifiant carac                                           */
/* Entrée : $liste = Liste des roles                                          */
/* Sortie : $tabS = tableau avec infos carac                                  */
/******************************************************************************/
/* Appelé par les scripts :                                                   */
/*           - ./dtn/interface/maliste/miseajour.php                          */
/******************************************************************************/
function get_behaviour_byID($id,$liste=null)
{
	global $conn;
	if (empty($liste)) {

		$sql = "SELECT * FROM ht_behaviour WHERE id_behaviour = $id";
  
		$result = $conn->query($sql);
		if ($result->rowCount()==1) { $tabS = $result->fetch(); }
		else { $tabS=false; }
		$result=NULL;
		return	$tabS;
	} else {
		// On a la liste des rôles en paramètres
		foreach($liste as $l){
			if($id == $l["id_behaviour"]){
				return $l;
			} 
		}
		return null;
	}
}


/******************************************************************************/
/* Objet : Renvoie la liste des positions possibles dans un match             */
/* Modifié le 24/05/2011 par Musta56 - Création fonction                      */
/******************************************************************************/
/* Entrée : aucun                                                             */
/* Sortie : $tabS = tableau avec liste des caracs                             */
/******************************************************************************/
/* Appelé par les scripts :                                                   */
/*           - dtn/interface/maliste/miseajour.php                            */
/******************************************************************************/
function list_role()
{
	global $conn;
	$tabS = array();
	$sql = "SELECT * FROM ht_role"; 

	foreach($conn->query($sql) as $row){
		array_push($tabS, $row);
	}
	return	$tabS;
}



/******************************************************************************/
/* Objet : Renvoie les infos d'une carac en fonction de son ID                */
/* Modifié le 24/05/2011 par Musta56 - Création fonction                      */
/******************************************************************************/
/* Entrée : $id = Identifiant carac                                           */
/* Entrée : $liste = Liste des roles                                          */
/* Sortie : $tabS = tableau avec infos carac                                  */
/******************************************************************************/
/* Appelé par les scripts :                                                   */
/*           - ./dtn/interface/maliste/miseajour.php                          */
/******************************************************************************/
function get_role_byID($id,$liste=null)
{
	global $conn;
	if (empty($liste)) {
	
		$sql = "SELECT * FROM ht_role WHERE id_role = $id";
  
		$result = $conn->query($sql);
		if ($result->rowCount()==1) { $tabS = $result->fetch(); }
		else { $tabS=false; }
		$result = NULL;
		return	$tabS;
	} else {
		// On a la liste des rôles en paramètres
		foreach($liste as $l){
			if($id == $l["id_role"]){
				return $l;
			} 
		}
		return null;
	}
}


// here we select only the positions available
// for the connected user.


function listPoste()
{
	global $conn;
	$tabS = array();

	$sql = "SELECT * FROM ht_poste ORDER BY idPosition,idPoste ASC ";
  
	foreach($conn->query($sql) as $row){
		array_push($tabS, $row);
	}
	return	$tabS;
}


// here we select only the positions available
// for the connected user.


function listPosition()
{
	global $conn;
	$tabS = array();
	global $sesUser;
	$sql = "SELECT * FROM ht_position WHERE idPosition!=0";
    
	switch($sesUser["idNiveauAcces_fk"]){
  	case "1":
  		break;
  	case "2":
  		if($sesUser["idPosition_fk"] != 0){
			$sql .= " AND idPosition = ".$sesUser["idPosition_fk"];
  		}
  		break;
  	case "3":
  		if($sesUser["idPosition_fk"] != 0){
			$sql .= " AND idPosition = ".$sesUser["idPosition_fk"];
  		}
  		break;
	}

	$sql .= " ORDER BY idPosition ASC";
	foreach($conn->query($sql) as $row){
		array_push($tabS, $row);
	}
	return	$tabS;

}

// here we select all existing positions available GK iM Wg CD Fw...
// Needed for top available for everyone.

function listAllPosition()
{
	global $conn;
	$tabS = array();

	global $sesUser;
	$sql = "SELECT * FROM ht_position WHERE idPosition!=0";
    
	$sql .= " ORDER BY idPosition ASC";
	foreach($conn->query($sql) as $row){
		array_push($tabS, $row);
	}
	return	$tabS;

}



function getPosition($id)
{
	global $conn;
	$tabS = array();

	$sql = "SELECT * FROM ht_position WHERE idPosition = '".$id."' ";
	$res = $conn->query($sql);
	$tabS = $res->fetch(PDO::FETCH_BOTH);

	return	$tabS;

}


function listAggres()
{
	global $conn;
	$tabS = array();

	$sql = "select * from ht_aggres  order by numAggres";
	foreach($conn->query($sql) as $row){
		array_push($tabS, $row);
	}
	return	$tabS;
}


function listCaractere()
{
	global $conn;
	$tabS = array();

	$sql = "select * from ht_caractere  order by numCaractere";
	foreach($conn->query($sql) as $row){
		array_push($tabS, $row);
	}
	return	$tabS;
}

function listHonnetete()
{
	global $conn;
	$tabS = array();

	$sql = "select * from ht_honnetete  order by numHonnetete";
	foreach($conn->query($sql) as $row){
		array_push($tabS, $row);
	}
	return	$tabS;
}

function listLeadership()
{
	global $conn;
	$tabS = array();

	$sql = "select * from ht_leadership  order by numLeader";
	foreach($conn->query($sql) as $row){
		array_push($tabS, $row);
	}
	return	$tabS;
}
function afficheLesPlus($zeJoueur,$nomColonne)
{
	if ($zeJoueur[$nomColonne]==null ||$zeJoueur[$nomColonne]==0){
		echo "&nbsp;&nbsp;";
	}else{
		echo "+".$zeJoueur[$nomColonne];
	}
}

function listPositionResume() {

  $tabS = Array('Gk', 'cD', 'iM', 'Wg', 'Fw', 'Fwtw', 'cDoff', 'wB', 'Wgpm', 'Fwdef');

  return	$tabS;
}


function listGrilleMinima() {
	global $conn;
	$tabS = array();

	$sql = "select  ht_grille_minima.*,
                  ht_typecarac1.nomTypeCarac      as nomTypeCarac1,
                  ht_typecarac2.nomTypeCarac      as nomTypeCarac2,
                  ht_typecarac3.nomTypeCarac      as nomTypeCarac3,
                  ht_position.descriptifPosition,
                  ht_typecarac1.dureeUp17         as dureeUp17Carac1,
                  ht_typecarac1.malusParAnnee     as malusParAnneeCarac1,
                  ht_typecarac2.dureeUp17         as dureeUp17Carac2,
                  ht_typecarac2.malusParAnnee     as malusParAnneeCarac2,
                  ht_typecarac3.dureeUp17         as dureeUp17Carac3,
                  ht_typecarac3.malusParAnnee     as malusParAnneeCarac3
          from  (((ht_grille_minima,
                ht_typecarac ht_typecarac1,
                ht_position) left outer join
                ht_typecarac ht_typecarac2 on ht_grille_minima.id_TypeCarac2=ht_typecarac2.idTypeCarac) left outer join
                ht_typecarac ht_typecarac3 on ht_grille_minima.id_TypeCarac3=ht_typecarac3.idTypeCarac)
          where ht_grille_minima.id_TypeCarac1=ht_typecarac1.idTypeCarac
          and ht_position.idPosition=ht_grille_minima.id_positionAssigne
          order by id_grille";

	foreach($conn->query($sql) as $row){
		array_push($tabS, $row);
	}
	return	$tabS;
}


function listSeasonWeek() {
	global $conn;
	$tabS = array();

	$sql = "SELECT  distinct concat('S',cast(season as char),'.W',if(week<10,concat('0',cast(week as char)),cast(week as char))) as seasonWeek
          FROM  ht_calendrier
          WHERE date_fin<adddate(CURRENT_DATE(), interval 7 day)
          AND   date_deb>(select min(date_match) from ht_perfs_individuelle)
          ORDER BY season DESC, week DESC";

	foreach($conn->query($sql) as $row){
		array_push($tabS, $row);
	}
	return	$tabS;
}



/******************************************************************************/
/* Objet : Renvoie la liste des matchs U20                                    */
/* Modifié le 07/06/2011 par Musta56 - Création fonction                      */
/******************************************************************************/
/* Entrée : $season (facultatif) - à partir de la saison                      */
/* Sortie : $tabS = tableau avec liste des matchs                             */
/******************************************************************************/
/* Appelé par les scripts :                                                   */
/*           - dtn_u20.php                                                    */
/******************************************************************************/
function listMatchsU20($season=null)
{
	global $conn;
	$tabS = array();

	$sql = "SELECT 
             matchs.season,
             matchs.week,
             matchs.tour,
             matchs.journee,
             DATE_FORMAT(matchs.DateJournee,'%d/%m/%Y') as date_match,
             20 - floor(datediff(DateJournee,CURDATE())/112) AS ageAnMaxi,
             111 - datediff(DateJournee,CURDATE()) + (floor(datediff(DateJournee,CURDATE())/112)*112) AS ageJourMaxi,
             floor((matchs.season-13)/2) AS numWC /* Numero world cup (-13 car ca a commence en saison 13) */ 
          FROM (
            /* Matchs du vendredi */
            SELECT 
                season,
                week,
                CASE 
                  when mod(season,2)=1 then '1' 
                  when mod(season,2)=0 AND week IN (8,9) then '2' 
                  when mod(season,2)=0 AND week IN (13,14) then '3'
                  when mod(season,2)=0 AND week=15 then '4'   
                  when mod(season,2)=0 AND week=16 then 'DEMI'  
                END AS tour,
                CASE 
                  when mod(season,2)=1 AND week>2 then cast(week-2 as char)  
                  when mod(season,2)=0 AND week=8 then '1' 
                  when mod(season,2)=0 AND week=9 then '3' 
                  when mod(season,2)=0 AND week=13 then '1' 
                  when mod(season,2)=0 AND week=14 then '3' 
                  when mod(season,2)=0 AND week=15 then '2'   
                  when mod(season,2)=0 AND week=16 then 'DEMI'  
                END AS journee,
                CASE 
                  when mod(season,2)=1 AND week>2 then ADDDATE(date_deb, 6)  
                  when mod(season,2)=0 AND week IN (8,9,13,14,15,16) then ADDDATE(date_deb, 6)  
                END AS DateJournee
            FROM ht_calendrier
            WHERE 
                (mod(season,2)=1 and week>2)
            OR  (mod(season,2)=0 and week in (8,9,13,14,15,16))
            UNION
            /* Matchs du lundi */
            SELECT 
                season,
                week,
                case week
                  when 9 then '2'
                  when 14 then '3'
                  when 15 then '4'
                  when 16 then '4'
                end AS tour,
                case week
                  when 9 then '2'
                  when 14 then '2'
                  when 15 then '1'
                  when 16 then '3'
                end AS journee,
                ADDDATE(date_deb,2) AS DateJournee
            FROM ht_calendrier
            WHERE 
                mod(season,2)=0 
            AND week in (9,14,15,16)
            UNION
            /* FINALE : Dimanche*/
            SELECT 
                season,
                week,
                'FINALE' AS tour,
                'FINALE' AS journee,
                ADDDATE(date_deb, 8) AS DateJournee
            FROM ht_calendrier
            WHERE 
                mod(season,2)=0 
            AND week=16) matchs
          WHERE DateJournee>=CURDATE() 
          AND   20-floor(datediff(DateJournee,CURDATE())/112) >= 15";
          
	if ($season==null) {
		$sql .= " AND season <= truncate((UNIX_TIMESTAMP(sysdate())-UNIX_TIMESTAMP('1997-05-31'))/86400/112,0)+6";
	} else {
		$sql .= " AND season <= $season+6";
	}
	$sql .= " ORDER BY DateJournee";

	foreach($conn->query($sql) as $row){
		array_push($tabS, $row);
	}
	return	$tabS;
}



?>
