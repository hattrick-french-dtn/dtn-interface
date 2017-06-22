<?php

function getCalculAgeAnneeSQL($nomVarDate="CURRENT_DATE")
{
$sql="floor((datediff($nomVarDate,'1970-01-01')-(574729200/86400)-datenaiss)/112)";

return $sql;
}


function getCalculAgeJourSQL($nomVarDate="CURRENT_DATE")
{
$sql="round(mod(datediff($nomVarDate,'1970-01-01')-(574729200/86400)-datenaiss,112))";

return $sql;
}


function ageetjour($datenaissjoueur,$formatSortie=1)
{        
	$jouractuel = round((mktime(0,0,0,date("m"),date("d"),date("Y"))-574729200)/3600/24,0);
	$nbjourjoueur = $jouractuel-$datenaissjoueur;
	$jourjoueur = $nbjourjoueur % 112;
	$agejoueur = ($nbjourjoueur-$jourjoueur)/112;

	if ($formatSortie==2) {
		$resu["ageJoueur"] = $agejoueur;
		$resu["jourJoueur"] = $jourjoueur;
		return($resu);
	} else if ($formatSortie==3) {
		return($agejoueur.".".$jourjoueur);
	}

	return($agejoueur." - ".$jourjoueur);
}


function getTousJoueurSQL(){
	$sql = "SELECT 
        idJoueur,
        nomJoueur,
        prenomJoueur,
        dateSaisieJoueur,
        dateDerniereModifJoueur,
        date_modif_effectif,
        saisonApparitionJoueur,".
        getCalculAgeAnneeSQL()." as ageJoueur,".
        getCalculAgeJourSQL()." as jourJoueur,
        datenaiss,
        optionJoueur,
        idHattrickJoueur,
        idAggre_fk,
        idLeader_fk,
        idCaractere_fk,
        idHonnetete_fk,
        idExperience_fk,
        idEndurance,
        idGardien,
        idConstruction,
        idPasse,
        idAilier,
        idDefense,
        idButeur,
        idPA,
        AdminSaisieJoueur_fk,
        dtnSuiviJoueur_fk,
        archiveJoueur,
        adminSaisie.loginAdmin as loginAdminSaisie,
        adminSuiveur.loginAdmin as loginAdminSuiveur,
        ht_posteAssigne,
        idPosition,
        intitulePosition,
        descriptifPosition,
        entrainement_id,
        scoreGardien,
        scoreDefense,
        scoreAilierOff,
        scoreMilieu,
        scoreAttaquant,
        scoreAttaquantVersAile,
        scoreAttaquantDef,
        scoreDefCentralOff,
        scoreDefLat ,
        scoreDefLatOff,
        scoreMilieuOff,
        scoreMilieuDef,
        scoreAilier ,
        scoreAilierVersMilieu,
        salary,
        ROUND(salary/coefSalary) AS salaireDeBase,
        joueurActif,
        affJoueur ,
        dateLastScanMatch,
        numAggres,
        intituleAggresFR,
        intituleAggresUK,
        numLeader,
        intituleLeaderFR,
        intituleLeaderUK,
        numCaractere,
        intituleCaractereFR,
        intituleCaractereUK,
        numHonnetete,
        intituleHonneteteFR,
        intituleHonneteteUK ,
        xp.numCarac as numXP,
        xp.intituleCaracFR as nomXP_fr,
        xp.intituleCaracUK as nomXP_uk,
        endurance.numCarac as numEnd,
        endurance.intituleCaracFR as nomEndurance_fr,
        endurance.intituleCaracUK as nomEndurance_uk,
        gardien.numCarac as numGardien,
        gardien.intituleCaracFR as nomGardien_fr,
        gardien.intituleCaracUK as nomGardien_uk,
        construction.numCarac as numConstruction,
        construction.intituleCaracFR as nomConstruction_fr,
        construction.intituleCaracUK as nomConstruction_uk,
        passe.numCarac as numPasse,
        passe.intituleCaracFR as nomPasse_fr,
        passe.intituleCaracUK as nomPasse_uk,
        ailier.numCarac as numAilier,
        ailier.intituleCaracFR as nomAilier_fr,
        ailier.intituleCaracUK as nomAilier_uk,
        defense.numCarac as numDefense,
        defense.intituleCaracFR as nomDefense_fr,
        defense.intituleCaracUK as nomDefense_uk,
        buteur.numCarac as numButeur,
        buteur.intituleCaracFR as nomButeur_fr,
        buteur.intituleCaracUK as nomButeur_uk,
        CF.numCarac as numCF,
        CF.intituleCaracFR as nomCF_fr,
        CF.intituleCaracUK as nomCF_uk,
        teamid,
        nbSemaineConstruction,
        nbSemaineAilier,
        nbSemaineButeur,
        nbSemaineGardien,
        nbSemainePasses,
        nbSemaineDefense,
        valeurEnCours,
        commentaire,
        ht_clubs.nomClub,
        ht_clubs.idClub,
        ht_clubs.idClubHT,
        ht_clubs.niv_Entraineur,
        ht_clubs.isBot,
        ht_clubs.date_last_connexion 
      FROM
      ( ht_joueurs,
        ht_caracteristiques xp,
        ht_caracteristiques endurance,
        ht_caracteristiques gardien,
        ht_caracteristiques construction,
        ht_caracteristiques passe,
        ht_caracteristiques ailier,
        ht_caracteristiques defense,
        ht_caracteristiques buteur,
        ht_caracteristiques CF
      )            
      LEFT JOIN ht_entrainement hte ON hte.idJoueur_fk = idJoueur
      LEFT JOIN ht_admin adminSuiveur ON adminSuiveur.idAdmin = dtnSuiviJoueur_fk 
      LEFT JOIN ht_position htp ON ht_posteAssigne = htp.idPosition
      LEFT JOIN ht_admin adminSaisie ON adminSaisie.idAdmin = AdminSaisieJoueur_fk 
      LEFT JOIN ht_aggres hta ON idAggre_fk = hta.numAggres 
      LEFT JOIN ht_leadership htl ON idLeader_fk =  htl.numLeader 
      LEFT JOIN ht_caractere htc ON idCaractere_fk = htc.numCaractere 
      LEFT JOIN ht_honnetete hth ON idHonnetete_fk = hth.numHonnetete 
      LEFT JOIN ht_clubs ON  teamid = ht_clubs.idClubHT 
      LEFT JOIN ht_pays ON idPays_fk = idPays
      WHERE
        xp.idCarac = idExperience_fk AND
        endurance.idCarac = idEndurance AND
        gardien.idCarac = idGardien AND
        construction.idCarac = idConstruction AND
        passe.idCarac = idPasse AND
        ailier.idCarac = idAilier AND
        defense.idCarac = idDefense AND
        buteur.idCarac = idButeur AND
        CF.idCarac = idPA";
  return $sql;
}


function getJoueurSQL($joueur_id){
  $sql = getTousJoueurSQL()." AND idJoueur = $joueur_id";
  return $sql;
}


function getJoueur($joueur_id)
{
	global $conn;

	if (isset($joueur_id) && $joueur_id != "" && $joueur_id!=null) {
		$sql=getJoueurSQL($joueur_id);

		$result = $conn->query($sql);
		$tabS = $result->fetch();
		$result=NULL;
		return  $tabS;
	} else {
		echo ("Parametre non renseigne => Impossible d'extraire le joueur.");
		return false;
	}
}

// ajout gege recherche sur htId 
function getJoueurHtSQL($joueur_id){
  
  $sql = getTousJoueurSQL()." AND idHattrickJoueur = $joueur_id";
  return $sql;
  
}


function getJoueurHt($joueur_id)
{
	global $conn;

	if (isset($joueur_id) && $joueur_id != "" && $joueur_id!=null) {
		$sql=getJoueurHtSQL($joueur_id);

		$result = $conn->query($sql);
		$tabS = $result->fetch();
		$result=NULL;
		return  $tabS;
	} else {
		echo ("Parametre non renseigne => Impossible d'extraire le joueur.");
		return false;
	}
}


/******************************************************************************/
/* Objet : Requ�te SQL pour obtenir liste des joueurs archiv�s                */
/* Modifi� le 12/11/2012 par Musta - Ajout param�tres limite                  */
/******************************************************************************/
/* Sortie : $sql = Requ�te SQL                                                */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/* - ./dtn/interface/includes/serviceJoueurs.php (fct getListeJoueursArchives)*/
/******************************************************************************/
function getListeJoueursArchivesSQL($limitDeb=null,$limitFin=null){
  $sql = "SELECT idJoueur,idHattrickJoueur 
          FROM ht_joueurs 
          WHERE archiveJoueur = 1 ";
          
  if ($limitDeb != null && $limitFin != null) {
    $sql .= " LIMIT $limitDeb,$limitFin";
  } elseif ($limitDeb != null && $limitFin == null) {
    $sql .= " LIMIT $limitDeb";
  } elseif ($limitDeb == null && $limitFin != null) {
    $sql .= " LIMIT $limitFin";
  }

  return $sql;
}


/******************************************************************************/
/* Objet : Extraction de la liste des joueurs Archiv�s                        */
/* Modifi� le 12/11/2012 par Musta - Ajout param�tres limite                  */
/******************************************************************************/
/* Sortie : $tabS = Tableau des joueurs archiv�s extrait de la base DTN       */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/* - ./dtn/interface/maj/majJoeursArchives.php                                */
/******************************************************************************/
function getListeJoueursArchives($limitDeb=null,$limitFin=null)
{
	global $conn;
	$tabS = array();

	$sql=getListeJoueursArchivesSQL($limitDeb,$limitFin);

	foreach($conn->query($sql) as $row){
		array_push($tabS, $row);
	}

	return  $tabS;
}




/******************************************************************************/
/* Objet : Requ�te SQL pour obtenir liste des joueurs d'un DTN                */
/* Modifi� le 24/11/2011 par Musta56 - Cr�ation Fonction                      */
/******************************************************************************/
/* Entr�e : $id_dtn = Identifiant du dtn dans table ht_admin                  */
/* Sortie : $sql = Requ�te SQL                                                */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/* - ./dtn/interface/includes/serviceJoueur.php (fonction getJoueurByDTN)     */
/******************************************************************************/
function getJoueurByDTNSQL($id_dtn){
  $sql = getTousJoueurSQL()." AND dtnSuiviJoueur_fk = $id_dtn";
  $sql .= " ORDER BY idClubHT, idHattrickJoueur";
  return $sql;
}

/******************************************************************************/
/* Objet : Liste des joueurs d'un DTN                                         */
/* Modifi� le 24/11/2011 par Musta56 - Cr�ation Fonction                      */
/******************************************************************************/
/* Entr�e : $id_dtn = Identifiant du dtn dans table ht_admin                  */
/* Sortie : $tabS = tableau r�sultat                                          */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/* - ./dtn/interface/joueurs/liste_suivi.php                                  */
/******************************************************************************/
function getJoueurByDTN($id_dtn)
{
	global $conn;
	$tabS = array();

	$sql=getJoueurByDTNSQL($id_dtn);

	foreach($conn->query($sql) as $row){
		array_push($tabS, $row);
	}

	return  $tabS;
}


/******************************************************************************/
/* Objet : Liste des joueurs d'un club                                        */
/* Modifi� le 05/05/2011 par Musta56 - Cr�ation Fonction                      */
/* Modifi� le 05/05/2011 par Musta56 - Ajout param $tous                      */
/******************************************************************************/
/* Entr�e : $idClubHT = Identifiant Hattrick du club                          */
/* Entr�e : $tous = si false uniquement les non archiv�s si true alors tous   */
/* Sortie : $tabS = Tableau d'information joueurs, false si aucun joueur      */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/*           - ./dtn_scan_team.php                                            */
/******************************************************************************/
function getJoueurs_by_idClubHT($idClubHT, $tous=false)
{
	global $conn;
	$tabS = array();

	$sql = getTousJoueurSQL()." AND ht_clubs.idClubHT = $idClubHT ";

	if ($tous == false) {
		$sql .= " AND  ht_joueurs.affJoueur = 1 ";
	}
  
	foreach($conn->query($sql) as $row){
		array_push($tabS, $row);
	}

	return  $tabS;
}




/*
 * recuperation du nom du dtn
 */
function getDtnName($iddtn){
	global $conn;

	$sql = "SELECT loginAdmin FROM ht_admin  WHERE idAdmin = '".$iddtn."' ";

	$result= $conn->query($sql);

	if($result->rowCount()==0) {
		return "-";
	}

	$res = $result->fetch();
	$result = NULL;

	return $res["loginAdmin"];
}

function getScout($idJoueur){
	global $conn;

	$sql = "SELECT idHattrickJoueur ,dtnSuiviJoueur_fk,   loginAdmin , idAdminHT   FROM ht_joueurs, ht_admin  WHERE idHattrickJoueur = '".$idJoueur."' AND dtnSuiviJoueur_fk = idAdmin ";
	$result= $conn->query($sql);
	$tabS = $result->fetch();
	$result=NULL;
	return  $tabS;

}
// Mise a jour de l'historique :
function listJoueur($affArchive , $affPosition)
{
	global $conn;
	global $ordre, $sens;
	if($affArchive == "") $affArchive = 0;

	//correction bug age joueurs par jojoje86 le 20/07/09
	$AgeAnneeSQL=getCalculAgeAnneeSQL();
	$AgeJourSQL=getCalculAgeJourSQL();

	$sql = "SELECT *,".$AgeAnneeSQL." as AgeAn,".$AgeJourSQL." as AgeJour FROM ht_joueurs ";
    
	if($affPosition != "") $sql . ", ht_position  ";
	$sql .= "WHERE";
	if($affPosition != "") $sql ."     ht_posteAssigne = idPosition AND";
  
	$sql .= "     joueurActif = 1 
            AND affJoueur = 1 
            AND archiveJoueur = $affArchive 
            AND ht_posteAssigne  = $affPosition ";
	$sql .= " ORDER BY $ordre $sens";


//echo $sql;
	$tabS = array();
	foreach($conn->query($sql) as $row){
		array_push($tabS, $row);
	}
	return	$tabS;
}


function listJoueurSelection()
{
	global $conn;
	global $sesUser, $ordre, $sens;
  
	$sql = "SELECT s.id,  s.id_joueur,  s.selection, j.* 
          FROM ht_selection s, ht_joueurs j 
          WHERE s.id_joueur = j.idJoueur 
          AND s.selection = '".$sesUser["selection"]."' ";
	$sql .= "ORDER BY $ordre $sens";

	$tabs = array();
	foreach($conn->query($sql) as $row){
		$tabS[] = $row;
	}
	return	$tabS;
}



function joueursSelection($selection){
	global $conn;
	global $sesUser, $ordre, $sens;
  
	$sql = "SELECT s.id,  s.id_joueur,  s.selection, j.* 
          FROM ht_selection s, ht_joueurs j 
          WHERE s.id_joueur = j.idJoueur 
          AND s.selection = '".$selection."'";
	$sql .= "ORDER BY $ordre $sens";

	$tabs = array();
	$req = $conn->query($sql);
	if ($req) {
		foreach($req as $row){
			$tabS[] = $row;
		}
	}
	return	$tabS;
}


// clemchen : modifs en cours
function calculNote($joueur){
        
  // endurance
  if ($joueur["idEndurance"]==9){
    $alphaEndu=0.22+0.13*8;
    $betaEndu=0.7+(0.05*8);
  }else{
    $alphaEndu=0.22+0.13*($joueur["idEndurance"]);
    $betaEndu=0.7+(0.05*$joueur["idEndurance"]);
  }
  $gammaEndu = 1;
  
  // Primaires + XP
  $niveauGK=$joueur["idGardien"]+ $joueur["nbSemaineGardien"] * 0.1;
  $niveauDef=$joueur["idDefense"] + $joueur["nbSemaineDefense"]* 0.1;
  $niveauConstruction=$joueur["idConstruction"] + $joueur["nbSemaineConstruction"] * 0.1;
  $niveauAilier=$joueur["idAilier"] + $joueur["nbSemaineAilier"] * 0.1;
  $niveauAttaquant=$joueur["idButeur"] + $joueur["nbSemaineButeur"] * 0.1;
  $niveauPasse=$joueur["idPasse"] + $joueur["nbSemainePasses"] * 0.1;
  $xp=$joueur["idExperience_fk"];

  // Gardien
  $scoreGardien=((0.75*$niveauGK*$gammaEndu)+(0.25*$niveauDef*$betaEndu))*(0.94+$xp*0.01);
  
  // Defenseurs centraux
  $scoreDefense=(($niveauDef*0.7 + $niveauPasse*0.15)*$betaEndu + (0.15*$niveauConstruction)*$alphaEndu)*(0.94+$xp*0.01); 
  $scoreDefOff=(($niveauDef*0.6 + $niveauPasse*0.1)*$betaEndu + (0.3*$niveauConstruction)*$alphaEndu)*(0.94+$xp*0.01); 
  
  // Defenseurs lateraux
  $scoreDefLat=(($niveauDef*0.6 + $niveauAilier*0.3)*$betaEndu + (0.1*$niveauConstruction)*$alphaEndu)*(0.94+$xp*0.01);
  $scoreDefLatOff=(($niveauDef*0.5 + $niveauAilier*0.35)*$betaEndu + (0.15*$niveauConstruction)*$alphaEndu)*(0.94+$xp*0.01);
  $scoreDefLatDef=($niveauDef*0.8+$niveauAilier*0.2)* $betaEndu*(0.94+$xp*0.01); 
  
  // Milieux
  $scoreMilieu=(($niveauConstruction*0.7)*$alphaEndu + ($niveauPasse*0.15 + $niveauDef*0.15) *$betaEndu)*(0.94+$xp*0.01); 
  $scoreMilieuOff=(($niveauConstruction*0.65)*$alphaEndu + ($niveauPasse*0.25 + $niveauDef*0.1) *$betaEndu)*(0.94+$xp*0.01); 
  $scoreMilieuDef=(($niveauConstruction*0.65)*$alphaEndu + ($niveauDef*0.25 + $niveauPasse*0.1) *$betaEndu)*(0.94+$xp*0.01);
  $scoreMilieuVersAile=(($niveauConstruction*0.6)*$alphaEndu + ($niveauDef*0.1 + $niveauPasse*0.1+$niveauAilier*0.2) *$betaEndu)*(0.94+$xp*0.01); 
  
  // Ailiers
  $scoreAilier=(($niveauConstruction*0.2)*$alphaEndu +($niveauAilier*0.5 + $niveauPasse*0.2 + $niveauDef*0.1)*$betaEndu) *(0.94+$xp*0.01);
  $scoreAilierVersMilieu=(($niveauConstruction*0.35)*$alphaEndu +($niveauAilier*0.35 + $niveauPasse*0.15 + $niveauDef*0.15)*$betaEndu) *(0.94+$xp*0.01); 
  $scoreAilierOff=(($niveauConstruction*0.1)*$alphaEndu +($niveauAilier*0.6 + $niveauPasse*0.3)) *(0.94+$xp*0.01);
  $scoreAilierDef=(($niveauConstruction*0.15)*$alphaEndu +($niveauAilier*0.4 + $niveauPasse*0.15 + $niveauDef*0.3)) *(0.94+$xp*0.01); 
  
  // Attaquants
  $scoreAttaquant=(($niveauAttaquant*0.7 + $niveauPasse*0.2 + $niveauAilier*0.1) *$betaEndu)*(0.94+$xp*0.01);

  if ($joueur["optionJoueur"]==1) {
    $scoreAttaquantDef = (($niveauConstruction*0.5)*$alphaEndu + ($niveauPasse*0.3 + $niveauAttaquant*0.3) *$betaEndu)*(0.94+$xp*0.01);
  } else {
    $scoreAttaquantDef = (($niveauConstruction*0.5)*$alphaEndu + ($niveauPasse*0.2 + $niveauAttaquant*0.3) *$betaEndu)*(0.94+$xp*0.01);
  }
  $scoreAttaquantVersAile=($niveauAttaquant*0.4 + $niveauPasse*0.2 + $niveauAilier*0.4) *$betaEndu*(0.94+$xp*0.01);


  $score["gardien"] = round($scoreGardien,2);
  
  $score["defense"] = round($scoreDefense,2);
  $score["defCentralOff"] = round($scoreDefOff,2);
  $score["defenseLat"] = round($scoreDefLat,2);
  $score["defenseLatOff"] = round($scoreDefLatOff,2);
  
  $score["milieu"] = round($scoreMilieu,2);
  $score["milieuOff"] = round($scoreMilieuOff,2);
  $score["milieuDef"] = round($scoreMilieuDef,2);
  
  $score["ailier"] = round($scoreAilier,2);
  $score["ailierOff"] = round($scoreAilierOff,2);
  $score["ailierVersMilieu"] = round($scoreAilierVersMilieu,2);
  
  $score["attaquant"]= round($scoreAttaquant,2);
  $score["attaquantVersAile"]= round($scoreAttaquantVersAile,2);
  $score["attaquantDef"]= round($scoreAttaquantDef,2);
  
  return $score;
}


// modifs clemchen
function calculNotePotentiel($joueur){

  // matrice des vitesses
  $tabVitesses=array();
  
  $tabVitesses["Gardien"]=array();
  $tabVitesses["Gardien"][17]=5.000;
  $tabVitesses["Gardien"][18]=5.560;
  $tabVitesses["Gardien"][19]=6.120;
  $tabVitesses["Gardien"][20]=6.680;
  $tabVitesses["Gardien"][21]=7.240;
  $tabVitesses["Gardien"][22]=7.800;
  $tabVitesses["Gardien"][23]=8.360;
  $tabVitesses["Gardien"][24]=8.920;
  $tabVitesses["Gardien"][25]=9.480;
  for ($i=26; $i<100; $i++) {
    $tabVitesses["Gardien"][$i]=9.480;
  }
  
  $tabVitesses["Defense"]=array();
  $tabVitesses["Defense"][17]=8.000;
  $tabVitesses["Defense"][18]=8.640;
  $tabVitesses["Defense"][19]=9.280;
  $tabVitesses["Defense"][20]=9.920;
  $tabVitesses["Defense"][21]=10.560;
  $tabVitesses["Defense"][22]=11.200;
  $tabVitesses["Defense"][23]=11.840;
  $tabVitesses["Defense"][24]=12.480;
  $tabVitesses["Defense"][25]=13.120;
  for ($i=26; $i<100; $i++) {
    $tabVitesses["Defense"][$i]=13.120;
  }
  
  $tabVitesses["Construction"]=array();
  $tabVitesses["Construction"][17]=7.000;
  $tabVitesses["Construction"][18]=7.560;
  $tabVitesses["Construction"][19]=8.120;
  $tabVitesses["Construction"][20]=8.680;
  $tabVitesses["Construction"][21]=9.240;
  $tabVitesses["Construction"][22]=9.800;
  $tabVitesses["Construction"][23]=10.360;
  $tabVitesses["Construction"][24]=10.920;
  $tabVitesses["Construction"][25]=11.480;
  for ($i=26; $i<100; $i++) {
    $tabVitesses["Construction"][$i]=11.480;
  }
  
  $tabVitesses["Ailier"]=array();
  $tabVitesses["Ailier"][17]=5.000;
  $tabVitesses["Ailier"][18]=5.560;
  $tabVitesses["Ailier"][19]=6.120;
  $tabVitesses["Ailier"][20]=6.680;
  $tabVitesses["Ailier"][21]=7.240;
  $tabVitesses["Ailier"][22]=7.800;
  $tabVitesses["Ailier"][23]=8.360;
  $tabVitesses["Ailier"][24]=8.920;
  $tabVitesses["Ailier"][25]=9.480;
  for ($i=26; $i<100; $i++) {
    $tabVitesses["Ailier"][$i]=9.480;
  }
  
  $tabVitesses["Buteur"]=array();
  $tabVitesses["Buteur"][17]=5.000;
  $tabVitesses["Buteur"][18]=5.560;
  $tabVitesses["Buteur"][19]=6.120;
  $tabVitesses["Buteur"][20]=6.680;
  $tabVitesses["Buteur"][21]=7.240;
  $tabVitesses["Buteur"][22]=7.800;
  $tabVitesses["Buteur"][23]=8.360;
  $tabVitesses["Buteur"][24]=8.920;
  $tabVitesses["Buteur"][25]=9.480;
  for ($i=26; $i<100; $i++) {
    $tabVitesses["Buteur"][$i]=9.480;
  }
  
  $tabVitesses["Passe"]=array();
  $tabVitesses["Passe"][17]=6.000;
  $tabVitesses["Passe"][18]=6.576;
  $tabVitesses["Passe"][19]=7.151;
  $tabVitesses["Passe"][20]=7.724;
  $tabVitesses["Passe"][21]=8.297;
  $tabVitesses["Passe"][22]=8.870;
  $tabVitesses["Passe"][23]=9.442;
  $tabVitesses["Passe"][24]=10.013;
  $tabVitesses["Passe"][25]=10.584;
  for ($i=26; $i<100; $i++) {
    $tabVitesses["Passe"][$i]=10.584;
  }


  // calcul du nombre de semaine a rajouter avant l'anniversaire
  $nbSemRestant=(111-$joueur["jourJoueur"])/7;

  
  $semainesGK=$joueur["nbSemaineGardien"];
  $semainesDef=$joueur["nbSemaineDefense"];
  $semainesConstruction=$joueur["nbSemaineConstruction"];
  $semainesAilier=$joueur["nbSemaineAilier"];
  $semainesAttaquant=$joueur["nbSemaineButeur"];
  $semainesPasse=$joueur["nbSemainePasses"];
  

  $semainesGKPotentiel=$semainesGK+$nbSemRestant;
  $semainesDefPotentiel=$semainesDef+$nbSemRestant;
  $semainesConstructionPotentiel=$semainesConstruction+$nbSemRestant;
  $semainesAilierPotentiel=$semainesAilier+$nbSemRestant;
  $semainesAttaquantPotentiel=$semainesAttaquant+$nbSemRestant;
  $semainesPassePotentiel=$semainesPasse+$nbSemRestant;
  
  //$niveauGK=$infJ["idGardien"] + ($semainesGK/$tabVitesses["Gardien"][$infJ["ageJoueur"]]);
  $niveauDef=$joueur["idDefense"] + ($semainesDef/$tabVitesses["Defense"][$joueur["ageJoueur"]]);
  $niveauConstruction=$joueur["idConstruction"] + ($semainesConstruction/$tabVitesses["Construction"][$joueur["ageJoueur"]]);
  $niveauAilier=$joueur["idAilier"] + ($semainesAilier/$tabVitesses["Ailier"][$joueur["ageJoueur"]]);
  $niveauAttaquant=$joueur["idButeur"] + ($semainesAttaquant/$tabVitesses["Buteur"][$joueur["ageJoueur"]]);
  $niveauPasse=$joueur["idPasse"] + ($semainesPasse/$tabVitesses["Passe"][$joueur["ageJoueur"]]);
  
  $niveauGKPotentiel=$joueur["idGardien"] + ($semainesGKPotentiel/$tabVitesses["Gardien"][$joueur["ageJoueur"]]);
  $niveauDefPotentiel=$joueur["idDefense"] + ($semainesDefPotentiel/$tabVitesses["Defense"][$joueur["ageJoueur"]]);
  $niveauConstructionPotentiel=$joueur["idConstruction"] + ($semainesConstructionPotentiel/$tabVitesses["Construction"][$joueur["ageJoueur"]]);
  $niveauAilierPotentiel=$joueur["idAilier"] + ($semainesAilierPotentiel/$tabVitesses["Ailier"][$joueur["ageJoueur"]]);
  $niveauAttaquantPotentiel=$joueur["idButeur"] + ($semainesAttaquantPotentiel/$tabVitesses["Buteur"][$joueur["ageJoueur"]]);
  //$niveauPassePotentiel=$infJ["idPasse"] + ($semainesPassePotentiel/$tabVitesses["Passe"][$infJ["ageJoueur"]]);
  
  
  // application des formules
  $score["gardien"] = $niveauGKPotentiel + 1.6*$niveauDef  - (8-(min(8,$joueur["idEndurance"])))*(1/$tabVitesses["Gardien"][$joueur["ageJoueur"]]);
  $score["defense"] = $niveauDefPotentiel + 0.75*$niveauPasse + 0.87*$niveauConstruction - (8-(min(8,$joueur["idEndurance"])))*(1/$tabVitesses["Defense"][$joueur["ageJoueur"]]);
  $score["lateral"] = $niveauDefPotentiel + 0.63*$niveauAilier - (8-(min(8,$joueur["idEndurance"])))*(1/$tabVitesses["Defense"][$joueur["ageJoueur"]]);
  $score["milieu"] = $niveauConstructionPotentiel + 0.85*$niveauPasse + 1.14*$niveauDef - (8-(min(8,$joueur["idEndurance"])))*(1/$tabVitesses["Construction"][$joueur["ageJoueur"]]);
  $score["ailier"] = $niveauAilierPotentiel + 1.4*$niveauConstruction + 1.2*$niveauPasse - (8-(min(8,$joueur["idEndurance"])))*(1/$tabVitesses["Ailier"][$joueur["ageJoueur"]]);
  $score["attaquant"]= $niveauAttaquantPotentiel + 0.85*$niveauPasse + 0.71*$niveauAilier - (8-(min(8,$joueur["idEndurance"])))*(1/$tabVitesses["Buteur"][$joueur["ageJoueur"]]);
  
  $score["gardien"] = round($score["gardien"],2);
  $score["defense"] = round($score["defense"],2);
  $score["lateral"] = round($score["lateral"],2);
  $score["milieu"] = round($score["milieu"],2);
  $score["ailier"] = round($score["ailier"],2);
  $score["attaquant"] = round($score["attaquant"],2);
  
  $age2=$joueur["ageJoueur"]+1;
  switch ($age2) {
  case 18:
    if ($joueur["idGardien"] < 2) {
      $score["gardien"] = -1 * $score["gardien"];
    }
    break;
  case 19:
    if ($joueur["idGardien"] < 2) {
      $score["gardien"] = -1 * $score["gardien"];
    }
    if ($niveauDefPotentiel<9) {
      $score["defense"] = -1 * $score["defense"];
      $score["lateral"] = -1 * $score["lateral"];
    }
    break;
  case 20:
    if ($niveauGKPotentiel<10.77) {
      $score["gardien"] = -1 * $score["gardien"];
    }
    if ($niveauDefPotentiel<11.36) {
      $score["defense"] = -1 * $score["defense"];
      $score["lateral"] = -1 * $score["lateral"];
    }
    if ($niveauConstructionPotentiel<11.63) {
      $score["milieu"] = -1 * $score["milieu"];
    }
    if ($niveauAilierPotentiel<7.99) {
      $score["ailier"] = -1 * $score["ailier"];
    }
    if ($niveauAttaquantPotentiel<10.59) {
      $score["attaquant"] = -1 * $score["attaquant"];
    }
    break;
  case 21:
    if ($niveauGKPotentiel<12.92) {
      $score["gardien"] = -1 * $score["gardien"];
    }
    if ($niveauDefPotentiel<12.85) {
      $score["defense"] = -1 * $score["defense"];
      $score["lateral"] = -1 * $score["lateral"];
    }
    if ($niveauConstructionPotentiel<13) {
      $score["milieu"] = -1 * $score["milieu"];
    }
    if ($niveauAilierPotentiel<10.43) {
      $score["ailier"] = -1 * $score["ailier"];
    }
    if ($niveauAttaquantPotentiel<12.25) {
      $score["attaquant"] = -1 * $score["attaquant"];
    }
    break;
  case 22:
    if ($niveauGKPotentiel<13) {
      $score["gardien"] = -1 * $score["gardien"];
    }
    if ($niveauDefPotentiel<13) {
      $score["defense"] = -1 * $score["defense"];
      $score["lateral"] = -1 * $score["lateral"];
    }
    if ($niveauConstructionPotentiel<13) {
      $score["milieu"] = -1 * $score["milieu"];
    }
    if ($niveauAilierPotentiel<12.68) {
      $score["ailier"] = -1 * $score["ailier"];
    }
    if ($niveauAttaquantPotentiel<13) {
      $score["attaquant"] = -1 * $score["attaquant"];
    }
    break;
  case 23:
    if ($niveauGKPotentiel<13) {
      $score["gardien"] = -1 * $score["gardien"];
    }
    if ($niveauDefPotentiel<13) {
      $score["defense"] = -1 * $score["defense"];
      $score["lateral"] = -1 * $score["lateral"];
    }
    if ($niveauConstructionPotentiel<13) {
      $score["milieu"] = -1 * $score["milieu"];
    }
    if ($niveauAilierPotentiel<13) {
      $score["ailier"] = -1 * $score["ailier"];
    }
    if ($niveauAttaquantPotentiel<13) {
      $score["attaquant"] = -1 * $score["attaquant"];
    }
    break;
  case 24:
    if ($niveauGKPotentiel<13) {
      $score["gardien"] = -1 * $score["gardien"];
    }
    if ($niveauDefPotentiel<13) {
      $score["defense"] = -1 * $score["defense"];
      $score["lateral"] = -1 * $score["lateral"];
    }
    if ($niveauConstructionPotentiel<13) {
      $score["milieu"] = -1 * $score["milieu"];
    }
    if ($niveauAilierPotentiel<13) {
      $score["ailier"] = -1 * $score["ailier"];
    }
    if ($niveauAttaquantPotentiel<13) {
      $score["attaquant"] = -1 * $score["attaquant"];
    }
    break;
  case 25:
    if ($niveauGKPotentiel<13) {
      $score["gardien"] = -1 * $score["gardien"];
    }
    if ($niveauDefPotentiel<13) {
      $score["defense"] = -1 * $score["defense"];
      $score["lateral"] = -1 * $score["lateral"];
    }
    if ($niveauConstructionPotentiel<13) {
      $score["milieu"] = -1 * $score["milieu"];
    }
    if ($niveauAilierPotentiel<13) {
      $score["ailier"] = -1 * $score["ailier"];
    }
    if ($niveauAttaquantPotentiel<13) {
      $score["attaquant"] = -1 * $score["attaquant"];
    }
    break;
  case 26:
    if ($niveauGKPotentiel<13) {
      $score["gardien"] = -1 * $score["gardien"];
    }
    if ($niveauDefPotentiel<13) {
      $score["defense"] = -1 * $score["defense"];
      $score["lateral"] = -1 * $score["lateral"];
    }
    if ($niveauConstructionPotentiel<13) {
      $score["milieu"] = -1 * $score["milieu"];
    }
    if ($niveauAilierPotentiel<13) {
      $score["ailier"] = -1 * $score["ailier"];
    }
    if ($niveauAttaquantPotentiel<13) {
      $score["attaquant"] = -1 * $score["attaquant"];
    }
    break;
  }
  
  return $score;
}



function calculNoteDynamique($carac,$semaine,$coeff){
	$sum = $coeff["coeffGardien"] + $coeff["coeffDefense"] + $coeff["coeffPasse"] +  $coeff["coeffAilier"]+$coeff["coeffConstruction"]+ $coeff["coeffButeur"]+$coeff["coeffEndurance"]+$coeff["coeffXp"];

	$note =     (  

          (($carac["gardien"] + $semaine["gardien"] * 0.1) * $coeff["coeffGardien"])
      +      (($carac["defense"] + $semaine["defense"] * 0.1) * $coeff["coeffDefense"])
      +     (($carac["passe"] + $semaine["passe"] * 0.1) * $coeff["coeffPasse"])
      +     (($carac["ailier"] + $semaine["ailier"] * 0.1) * $coeff["coeffAilier"])
      +     (($carac["construction"] + $semaine["construction"] * 0.1) * $coeff["coeffConstruction"])
      +     (($carac["buteur"] + $semaine["buteur"] * 0.1) * $coeff["coeffButeur"])
      +   ($carac["endurance"] * $coeff["coeffEndurance"])
      +   ($carac["xp"] * $coeff["xp"]));
    
    echo round($note/$sum,2);
    
}

function getEntrainement($idJoueur){
	global $conn;

	$sql = "SELECT * FROM  ht_entrainement WHERE idJoueur_fk = $idJoueur";
  
	$result= $conn->query($sql);
	$tabS = $result->fetch();
	$result = NULL;

	return  $tabS;
}


function  verifSelection($idJ){
	global $conn;

	$sql = "SELECT * FROM ht_selection WHERE id_joueur = $idJ";

	$result= $conn->query($sql);
	$tabS = $result->fetch();
  
	$result = NULL;
	return  $tabS["selection"];
}


function toplist_new($position, $max, $ordre, $age, $debsaison, $nosaison){
	global $conn;

	$sql = "SELECT ht_joueurs.*, hte.*, cl1.nomClub as nomActuel 
          FROM (ht_joueurs ,ht_clubs cl1 ) 
                LEFT JOIN ht_entrainement hte ON hte.idJoueur_fk = ht_joueurs.idJoueur 
          WHERE 
              teamid = cl1.idClubHT 
          AND joueurActif = 1 
          AND affJoueur = 1 
          AND archiveJoueur = 0 AND ";

	$jouractuel = round((mktime(0,0,0,date("m"),date("d"),date("Y"))-574729200)/3600/24,0);
	if ($age == "SUP") 
	{
		$sql .= getCalculAgeAnneeSQL()." > '20'  "; 
	}
	else if($age < 6) 
	{
		if ($nosaison % 2 == 0)
		{
			$max1 = 108;
			$max2 = 106;
		}
		else
		{
			$max1 = 106;
			$max2 = 108;
		}
		$jourj0 = ($debsaison+3600-574729200)/3600/24;
    
		if ($age == 1)
		{
			$datenaissmin = $jourj0 - 1796 - $max1;
			$sql .= " datenaiss >= $datenaissmin  "; 
		}
		if ($age == 2)
		{
			$datenaissmin1 = $jourj0 - 1795 - $max1;
			$datenaissmin = $jourj0 - 1908 - $max2;
			$sql .= " datenaiss between $datenaissmin and $datenaissmin1  "; 
		}
		if ($age == 3)
		{
			$datenaissmin1 = $jourj0 - 1907 - $max2;
			$datenaissmin = $jourj0 - 2020 - $max1;
			$sql .= " datenaiss between $datenaissmin and $datenaissmin1  "; 
		}
		if ($age == 4)
		{
			$datenaissmin1 = $jourj0 - 2019 - $max1;
			$datenaissmin = $jourj0 - 2132 - $max2;
			$sql .= " datenaiss between $datenaissmin and $datenaissmin1  "; 
		}
		if ($age == 5)
		{
			$datenaissmin = $jourj0 - 2132 - $max2;
			$jourmax = $jouractuel - 21 * 112;
			$sql .= " datenaiss < $datenaissmin and datenaiss > $jourmax "; 
		}
	}
	else if (strpos($age,"|"))
	{
		$age2 = explode("|",$age);
		$jour1 = intval($age2[1]) - 56;
		$sql .= getCalculAgeAnneeSQL()." = '".$age2[0]."'  and ".getCalculAgeJourSQL()." between ".$jour1." and ".$age2[1]."  ";
	}
	else
	{
		$datenaissmin = $jouractuel - intval($age) * 112 - 1;
		$jourmax = $jouractuel - (intval($age)+1) * 112;
		$sql .= " datenaiss < $datenaissmin and datenaiss > $jourmax "; 
	}
     
	$sql .= "ORDER BY ".$ordre." DESC";
	$sql .= " LIMIT 0,$max";

	$tabS = array();
	foreach($conn->query($sql) as $row){
		array_push($tabS, $row);
	}
	return	$tabS;
}


function toplist_potentiels($position, $max, $ordre, $age){
	global $conn;

	$sql = "SELECT 
              ht_joueurs.*, 
              hte.*, htnp.*, 
              cl1.nomClub as nomActuel 
          FROM (ht_joueurs ,ht_clubs cl1 ) 
                LEFT JOIN ht_entrainement hte ON hte.idJoueur_fk = ht_joueurs.idJoueur 
                LEFT JOIN ht_notes_potentiel htnp ON htnp.idJoueur_fk = ht_joueurs.idJoueur 
          WHERE 
              teamid = cl1.idClubHT 
          AND joueurActif = 1 
          AND affJoueur = 1 
          AND archiveJoueur = 0 
          AND ".getCalculAgeAnneeSQL()." = '".$age."' 
          ORDER BY ".$ordre." DESC 
          LIMIT 0,$max";

	$tabS = array();
	foreach($conn->query($sql) as $row){
		array_push($tabS, $row);
	}
	return	$tabS;
}






/*
 * Validation d une valeur des  minimas lors de la soumission externe d un joueur
 */

function checkCarac($player,$carac,$level){
  
  if ($carac==-1){//pas de limite
    return true; 
  } 
  
  if ($carac==1){//Construction 
      if ($player["idConstruction"]>=$level){
        return true;
      }
  }        
  if ($carac==2){//Ailier 
        if ($player["idAilier"]>=$level){
        return true;
      }
  }        
  if ($carac==3){//Buteur 
      if ($player["idButeur"]>=$level){
        return true;
      }
  }        
  if ($carac==4){//Gardien 
      if ($player["idGardien"]>=$level){
        return true;
      }
  }        
  if ($carac==5){//Passe
      if ($player["idPasse"]>=$level){
        return true;
      }
  }        
  if ($carac==6){//Defense 
      if ($player["idDefense"]>=$level){
        return true;
      }
  }        
  if ($carac==9){//Endurance
      if ($player["idEndurance"]>=$level){
        return true;
      }
  }        

  return false;        
}



/******************************************************************************/
/* Objet : Validation des minimas lors de la soumission externe d un joueur   */
/* Modifi� le 05/05/2011 par Musta56 - Utilisation de la fonction ageetjour   */
/* Modifi� le 11/12/2011 par Musta56 - Suppr. param maBase. modif exec requete*/
/******************************************************************************/
/* Entr�e : $player = donn�es chpp joueur                                     */
/* Entr�e : $todaySeason = num�ro saison et semaine (NE SERT PLUS)            */
/* Sortie : $result = num�ro du poste ou                                      
                      -1 en dessous des minimas ou 
                      -2 (incoh�rence d�tect�e ou 2 multicarac => aucun poste)*/
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/*           - ./dtn/interface/includes/serviceJoueur.php                     */
/*           - ./dtn/interface/joueur/addPlayer.php                           */
/*           - ./form.php cas ajoutJoueur                                     */
/******************************************************************************/
function validateMinimaPlayer($player,$todaySeason)
{
	require("nomTables.inc.php");
	global $conn;

  $age=ageetjour($player["datenaiss"],"2");
  $week = floor($age["jourJoueur"]/7);
  
  if ($age["ageJoueur"]<23){
    $sql = "SELECT * FROM $tbl_requirements WHERE week = '$week' and age= '".$age["ageJoueur"]."' ";
  }else {
    $sql = "SELECT * FROM $tbl_requirements WHERE week = '15' and age= '22' ";
  }
  //echo("<br /> player=".$player['nomJoueur']."-".$age["ageJoueur"]." ans ".$age["jourJoueur"]." jours-".$sql);

  $reqValid = $conn->query($sql);
  
  if (!$reqValid) {
    return -1;
  } else {
    if($reqValid->rowCount() == 0){
      //** ATTENTION! dans ce cas, les joueurs sont tous acceptes, soit la semaine n'est pas valide (>16 ou negative)
      return -2;
    } elseif ($reqValid->rowCount() > 0) {
      $i=0;
      while($i<$reqValid->rowCount()) {
        $lstReq[$i] = $reqValid->fetch();
        $i++;
      }
  	}
    $reqValid=NULL;
  }
  
  $result=-1;
  for ($j=0;$j<count($lstReq);$j++){
    $requirement=$lstReq[$j];
    if (checkCarac(  $player,$requirement["carac_1"],$requirement["level_1"]) ){
      if (checkCarac(  $player,$requirement["carac_2"],$requirement["level_2"])){
        if($result==-1){
          $result=$requirement["position_id"];
        }else{
          return -2; // 2 caracs sont bonnes pas de categorie.
        }
        
      } 
    }
  }

  return $result;
}



function majCaracJoueur($joueur){
	global $conn;

	$sql = 'UPDATE ht_joueurs SET
      scoreGardien = "'.$joueur["score"]["gardien"].'", 
      scoreDefense  = "'.$joueur["score"]["defense"].'", 
      scoreDefCentralOff  = "'.$joueur["score"]["defCentralOff"].'", 
      scoreDefLat  = "'.$joueur["score"]["defenseLat"].'", 
      scoreDefLatOff  = "'.$joueur["score"]["defenseLatOff"].'", 
      scoreMilieu  = "'.$joueur["score"]["milieu"].'", 
      scoreMilieuOff  = "'.$joueur["score"]["milieuOff"].'", 
      scoreMilieuDef  = "'.$joueur["score"]["milieuDef"].'", 
      scoreAilier  = "'.$joueur["score"]["ailier"].'", 
      scoreAilierOff  = "'.$joueur["score"]["ailierOff"].'", 
      scoreAilierVersMilieu  = "'.$joueur["score"]["ailierVersMilieu"].'", 
      scoreAttaquant = "'.$joueur["score"]["attaquant"].'",
      scoreAttaquantVersAile = "'.$joueur["score"]["attaquantVersAile"].'",
      scoreAttaquantDef = "'.$joueur["score"]["attaquantDef"].'"
    WHERE idJoueur = "'.$joueur["idJoueur"].'"';
    
    $result= $conn->exec($sql);

    $sql = 'UPDATE ht_entrainement SET 
      nbSemaineConstruction = "'.$joueur["nbSemaineConstruction"].'",
      nbSemaineAilier = "'.$joueur["nbSemaineAilier"].'",
      nbSemaineButeur  = "'.$joueur["nbSemaineButeur"].'",
      nbSemaineGardien = "'.$joueur["nbSemaineGardien"].'",
      nbSemainePasses= "'.$joueur["nbSemainePasses"].'",
      nbSemaineDefense = "'.$joueur["nbSemaineDefense"].'"
      WHERE idJoueur_fk = "'.$joueur["idJoueur"].'"';
    $result= $conn->exec($sql);
    
    $sql = 'UPDATE ht_notes_potentiel SET
      noteGardien = "'.$joueur["scorePotentiel"]["gardien"].'",
      noteDefense = "'.$joueur["scorePotentiel"]["defense"].'",
      noteLateral = "'.$joueur["scorePotentiel"]["lateral"].'",
      noteMilieu = "'.$joueur["scorePotentiel"]["milieu"].'",
      noteAilier = "'.$joueur["scorePotentiel"]["ailier"].'",
      noteAttaquant = "'.$joueur["scorePotentiel"]["attaquant"].'"
      WHERE idJoueur_fk = "'.$joueur["idJoueur"].'"';
    $result= $conn->exec($sql);
}






/******************************************************************************/
/* Objet : Suppression des donn�es d'un joueur                                */
/* Modifi� le 04/03/2011 par Musta56 - Ajout purge ht_clubs_histo_joueurs     */
/* Modifi� le 21/12/2011 par Musta56 - correction bug purge ht_joueurs_histo  */
/*           ht_perfs_individuelle + ht_clubs_histo_joueurs                   */
/******************************************************************************/
/* Entr�e : $joueurDTN = tableau de type table ht_joueurs avec info joueur    */
/* Sortie : rien                                                              */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/*           - form.php                                                       */
/******************************************************************************/
function delJoueur($joueurDTN)
{
	global $conn;
	$sql = "DELETE FROM  ht_entrainement WHERE idJoueur_fk = '".$joueurDTN["idJoueur"]."' ";
	$req = $conn->exec($sql);

	$sql = "DELETE FROM  ht_histomodif WHERE idJoueur_fk = '".$joueurDTN["idJoueur"]."'";
	$req = $conn->exec($sql);

	$sql = "DELETE FROM  ht_perfs_individuelle WHERE id_joueur = '".$joueurDTN["idHattrickJoueur"]."'";
	$req = $conn->exec($sql);

	$sql = "DELETE FROM  ht_joueurs_histo WHERE id_joueur_fk = '".$joueurDTN["idHattrickJoueur"]."'";
	$req = $conn->exec($sql);

	$sql = "DELETE FROM  ht_clubs_histo_joueurs WHERE id_joueur = '".$joueurDTN["idJoueur"]."'";
	$req = $conn->exec($sql);

	// Insertion dans la table ht_obsolete_players
	$_POST["dateSuppression"] = date('Y-m-d H:i');
	$_POST["loginAdmin"] = $sesUser["loginAdmin"];
	$_POST["idHattrickJoueur"] = $joueurDTN["idHattrickJoueur"];
	$_POST["idEndurance"] = $joueurDTN["idEndurance"];
	$_POST["idGardien"] = $joueurDTN["idGardien"];
	$_POST["idConstruction"] = $joueurDTN["idConstruction"];
	$_POST["idPasse"] = $joueurDTN["idPasse"];
	$_POST["idAilier"] = $joueurDTN["idAilier"];
	$_POST["idDefense"] = $joueurDTN["idDefense"];
	$_POST["idButeur"] = $joueurDTN["idButeur"];
	$_POST["idPA"] = $joueurDTN["idPA"];
	$_POST["optionJoueur"] = $joueurDTN["optionJoueur"];
	$_POST["ageJoueur"] = $joueurDTN["ageJoueur"];
	$_POST["dateDerniereModifJoueur"] = $joueurDTN["dateDerniereModifJoueur"];

	$sql = insertDB("ht_obsolete_players");

	$sql = "DELETE FROM  ht_joueurs WHERE idJoueur = '".$joueurDTN["idJoueur"]."'";
	$req = $conn->exec($sql);
}



/********************************************************************************************/
/* Objet : MAJ e la date de dernier scan d'un joueur                                        */
/* Modifi� le ??/??/???? par Musta56 - Cr�ation fonction                                    */
/********************************************************************************************/
/* Entr�e : $idHTJoueur = identifiant hattrick du joueur                                    */
/* Sortie : $idHTJoueur = identifiant ht joueur si ok sinon false                           */
/********************************************************************************************/
/* Appel� par les scripts :                                                                 */
/*           - dtn/interface/includes/serviceJoueur.php (fonction scanlisteJoueur)          */
/********************************************************************************************/
function updateDateScanMatchJoueur($idHTJoueur)
{
	global $conn;

	$sql='UPDATE ht_joueurs 
        SET dateLastScanMatch=\''.date("Y-m-d H:i:s").'\' 
        WHERE idHattrickJoueur=\''.$idHTJoueur.'\' LIMIT 1';
  
	$reqValid= $conn->exec($sql);

  if (!$reqValid) {
    return false;
  } else {
    return $idHTJoueur;
  }

}





/********************************************************************************************/
/* Objet : R�cup�ration des donn�es d'un joueur (fichier CHPP PlayerDetail)                 */
/* Modifi� le 15/06/2010 par Musta56 - Cr�ation fonction                                    */
/* Modifi� le 09/03/2011 par Musta56 - Si joueur joue un match alors on mets blessure null  */
/* Modifi� le 15/03/2011 par Musta56 - Utilisation de PHT                                   */
/* Modifi� le 12/10/2012 par Musta56 - Lib�ration m�moire avec unset                        */
/* Modifi� le 12/10/2012 par Musta56 - Ajout IsAbroad                                       */
/********************************************************************************************/
/* Entr�e : $idJoueurHT = identifiant hattrick du joueur                                    */
/* Sortie : $row_joueur = tableau de type table ht_joueurs avec info joueur                 */
/********************************************************************************************/
/* Appel� par les scripts :                                                                 */
/*           - ./dtn/interface/maliste/miseajour.php                                        */
/*           - ./dtn/interface/admin/majplayerDetails.php                                   */
/*           - ./dtn_scan_team                                                              */
/*           - ./dtn/interface/maj/majJoueursArchives.php                                   */
/********************************************************************************************/
function getDataUnJoueurFromHT_usingPHT($idJoueurHT){
  // Indispensable pour utiliser existAutorisationClub
  require_once($_SERVER['DOCUMENT_ROOT'].'/dtn/interface/includes/serviceEquipes.php');

  $row_joueur=array();

  try {
  
    // On recherche dans la base dtn si le proprio du joueur nous a autoriser � utiliser son acc�s CHPP et si c'est acc�s est toujours valide, on l'utilisera
    $ht_session=existAutorisationClub($_SESSION['HT']->getPlayer($idJoueurHT)->getTeamId());
    
    if ($ht_session == false && isset($_SESSION['HT'])) {
      $ht_session = $_SESSION['HT'];
    }

    // On r�cup�re sur HT les informations du joueur
    $player = $ht_session->getPlayer($idJoueurHT);
    
    $row_joueur['idHattrickJoueur'] = $player->getId();
    $row_joueur['nomJoueur']        = utf8_decode(strtr($player->getName(),"'"," "));
    $row_joueur['prenomJoueur']     = utf8_decode(strtr($player->getFirstName(),"'"," "));
    $jouractuel                     = (mktime(0,0,0,date("m"),date("d"),date("Y"))-574729200)/3600/24;
    $agejoueurenj                   = $player->getAge()*112+$player->getDays();
    $row_joueur['datenaiss']        = round(($jouractuel - $agejoueurenj),0);
    $row_joueur['forme']            = $player->getForm();
    if ($player->getInjury()!=null) {$row_joueur['blessure'] = $player->getInjury();}
    $row_joueur['teamid']           = $player->getTeamId();
    $row_joueur['salary']           = $player->getSalary();
    $row_joueur['isAbroad']         = $player->isAbroad();
    $row_joueur['idCaractere_fk']   = $player->getAgreeability();
    $row_joueur['idAggre_fk']       = $player->getAggressiveness();
    $row_joueur['idHonnetete_fk']   = $player->getHonesty();
    $row_joueur['idExperience_fk']  = $player->getExperience();
    $row_joueur['idLeader_fk']      = $player->getLeadership();
    $row_joueur['optionJoueur']     = $player->getSpeciality();
    $row_joueur['tsi']              = $player->getTsi();
    $row_joueur['transferListed']   = $player->isTransferListed();
    if ($row_joueur['transferListed'] === null || empty($row_joueur['transferListed'])) 
    {
      $row_joueur['transferListed']=0;
    } else {
      $row_joueur['transferListed']=1;
    }
    $row_joueur['idEndurance']      = $player->getStamina();

//    $row_joueur['caracVisible']     = $player->isSkillsAvailable();
    $row_joueur['idGardien']        = $player->getKeeper();
    if($row_joueur['idGardien']=="")
    {
      $row_joueur['caracVisible'] = false;
//echo("non_visible");
    }
    else
    {
      $row_joueur['caracVisible'] = true;
//echo("visible");
    }

    if($row_joueur['caracVisible'])
    {
        $row_joueur['idGardien']        = $player->getKeeper();
        $row_joueur['idConstruction']   = $player->getPlaymaker();
        $row_joueur['idButeur']         = $player->getScorer();
        $row_joueur['idPasse']          = $player->getPassing();
        $row_joueur['idAilier']         = $player->getWinger();
        $row_joueur['idDefense']        = $player->getDefender();
        $row_joueur['idPA']             = $player->getSetPieces();
    }
    // informations non stock�es en base mais utilis�es par les scripts
    $row_joueur['AGE']              = $player->getAge();
    $row_joueur['AGEDAYS']          = $player->getDays();
    $row_joueur['NATIVELEAGUENAME'] = $player->getNativeLeagueName();

    // Lib�ration de la m�moire
    unset($ht_session);
    unset($player);
    unset($jouractuel);
    unset($agejoueurenj);
    
    return $row_joueur;
  }
  catch(HTError $e)
  {
    if ($e->getErrorCode()!= '56') { // Si le joueur n'existe pas sur HT on affiche pas de message
      echo $e->getMessage();
    } else { // erreur 56, le joueur a disparu de HT
      if (marqueJoueurDisparuHT(getJoueurHt($idJoueurHT))==false) {
        return false;
      }
    }
    return false;
  } 

}



/********************************************************************************************/
/* Objet : R�cup. des donn�es des joueurs d'1 �quipe (fichier CHPP Players)                 */
/* Modifi� le 15/06/2010 par Musta56 - Cr�ation fonction                                    */
/* Modifi� le 09/03/2011 par Musta56 - Si joueur joue un match alors on mets blessure null  */
/* Modifi� le 15/03/2011 par Musta56 - Utilisation de PHT                                   */
/* Modifi� le 12/10/2012 par Musta56 - Ajout IsAbroad                                       */
/********************************************************************************************/
/* Entr�e : Rien                                                                            */
/* Sortie : $row_joueur = tableau de type table ht_joueurs avec info joueurs                */
/********************************************************************************************/
/* Appel� par les scripts :                                                                 */
/*           - ./dtn_scan_team.php                                                          */
/********************************************************************************************/
function getDataMesJoueursFromHT_usingPHT($teamID){


  try
  {
    if ($teamID === null) {
      $teamID = $_SESSION['HT']->getTeam()->getTeamId();
    }
    
    $teamPlayers = $_SESSION['HT']->getTeamPlayers($teamID);
    $row_joueur=array();
    $i=0;
    $FRANCE_ID=5;
    
    for($j=1; $j<=$teamPlayers->getNumberPlayers(); $j++)
    {
      unset($player);
      $player = $teamPlayers->getPlayer($j);
      if ($FRANCE_ID == $player->getCountryId()) {
        $row_joueur[$i]['idHattrickJoueur'] = $player->getId();
        $row_joueur[$i]['nomJoueur']        = utf8_decode(strtr($player->getName(),"'"," "));
        $row_joueur[$i]['prenomJoueur']     = utf8_decode(strtr($player->getFirstName(),"'"," "));
        $jouractuel                         = (mktime(0,0,0,date("m"),date("d"),date("Y"))-574729200)/3600/24;
        $agejoueurenj                       = $player->getAge()*112+$player->getDays();
        $row_joueur[$i]['datenaiss']        = round(($jouractuel - $agejoueurenj),0);
        $row_joueur[$i]['forme']            = $player->getForm();
        if ($player->getInjury()!=null) {$row_joueur[$i]['blessure'] = $player->getInjury();}
        $row_joueur[$i]['teamid']           = $teamID;
        $row_joueur[$i]['salary']           = $player->getSalary();
        $row_joueur[$i]['isAbroad']         = $player->isAbroad();
        $row_joueur[$i]['idCaractere_fk']   = $player->getAgreeability();
        $row_joueur[$i]['idAggre_fk']       = $player->getAggressiveness();
        $row_joueur[$i]['idHonnetete_fk']   = $player->getHonesty();
        $row_joueur[$i]['idExperience_fk']  = $player->getExperience();
        $row_joueur[$i]['idLeader_fk']      = $player->getLeadership();
        $row_joueur[$i]['optionJoueur']     = $player->getSpeciality();
        $row_joueur[$i]['tsi']              = $player->getTsi();
        $row_joueur[$i]['transferListed']   = $player->isTransferListed();
        //echo("<br />Joueur=".$row_joueur[$i]['nomJoueur']."|transferListed=".$row_joueur[$i]['transferListed']);
        if ($row_joueur[$i]['transferListed'] === null || empty($row_joueur[$i]['transferListed'])) 
        {
          $row_joueur[$i]['transferListed']=0;
        } else {
          $row_joueur[$i]['transferListed']=1;
        }
        //echo(" | ".$row_joueur[$i]['transferListed']);  
        $row_joueur[$i]['idEndurance']      = $player->getStamina();
        $row_joueur[$i]['idGardien']        = $player->getKeeper();
        $row_joueur[$i]['idConstruction']   = $player->getPlaymaker();
        $row_joueur[$i]['idButeur']         = $player->getScorer();
        $row_joueur[$i]['idPasse']          = $player->getPassing();
        $row_joueur[$i]['idAilier']         = $player->getWinger();
        $row_joueur[$i]['idDefense']        = $player->getDefender();
        $row_joueur[$i]['idPA']             = $player->getSetPieces();
        $row_joueur[$i]['caracVisible']     = true;
        
        // informations non stock�es en base mais utilis�es par les scripts
        $row_joueur[$i]['AGE']      = $player->getAge();
        $row_joueur[$i]['AGEDAYS']  = $player->getDays();
        $row_joueur[$i]['nomUser']  = stripslashes(htmlspecialchars(utf8_decode(strtolower(str_replace("'"," ",$_SESSION['HT']->getTeam()->getLoginName())))));
        $i++;
      }
    }
    
    $teamPlayers = $_SESSION['HT']->clearTeamPlayers($teamID);
    
    return $row_joueur;
  }
  catch(HTError $e)
  {
    if ($e->getErrorCode()!= '56') { // Si le joueur n'existe pas sur HT on affiche pas de message
      echo $e->getMessage();
    }
    return false;
  } 

}




/******************************************************************************/
/* Objet : R�cup. des donn�es des joueurs d'1 �quipe nationnale (fichiers CHPP*/
/*         nationalplayers et PlayerDetail)                                   */
/* Modifi� le 30/05/2011 par Musta56 - Cr�ation fonction                      */
/* Modifi� le 19/12/2011 par Musta56 - On retourne juste un tableau des ids HT*/
/*        des joueurs de la s�lection                                         */
/******************************************************************************/
/* Entr�e : $idNT = Identifiant de la NT                                      */
/* Sortie : $row_joueur = tableau de type table ht_joueurs avec id joueurs    */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/*           - ./national_team/coach_dtn_submitting.php                       */
/******************************************************************************/
function getDataJoueursSelectionsFromHT_usingPHT($idNT){

  $row_joueur=array();

  try {

    // On r�cup�re sur HT les joueurs en NT
    $players = $_SESSION['HT']->getNationalPlayers($idNT);
    
    // On lib�re l'espace m�moire
    $_SESSION['HT']->clearNationalPlayers($idNT);

    for ($i=1;$i<=$players->getNumberPlayers();$i++) {
      $row_joueur[]=$players->getPlayer($i)->getId();
    }

    return $row_joueur;
  }
  catch(HTError $e)
  {
    echo $e->getMessage();
    return false;
  }

}


/******************************************************************************/
/* Objet : Ajout d'un joueur                                                  */
/* Modifi� le 17/06/2010 par Musta56 - Cr�ation fonction                      */
/* Modifi� le 22/11/2011 par Musta56 - utilisation majClub et param majClub   */
/* Modifi� le 10/12/2011 par Musta56 - suppression appel majClub, param maBase*/
/*         Ajout param joueurDTN                                              */
/******************************************************************************/
/* Entr�e : $maBase = instance d'objet de connexion � la base dtn             */
/* Entr�e : $ht_user = nom du manager (DTN ou proprio) connect�               */
/* Entr�e : $role_user = role du manager connect� : DTN (D) ou proprio (P)    */
/* Entr�e : $joueurHT = tableau joueur                                        */
/* Entr�e : $majClub = maj Club ? (1 oui)                                     */
/* Sortie : $posteAssigne = Poste d'assignation du joueur                     */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/* - .dtn/interface/form.php (case ajoutJoueur)                               */
/* - ./dtn/interface/includes/serviceJoueur.php (fonction scanListeJoueurs)   */
/* - ..\joueurs\addPlayer.php                                                 */
/* - ./form.php cas ajoutJoueur                                               */
/******************************************************************************/
function ajoutJoueur($ht_user,$role_user,$joueurHT,$joueurDTN,$posteAssigne) {
	global $conn;

  require($_SERVER['DOCUMENT_ROOT'].'/dtn/interface/includes/nomTables.inc.php');
  
  if ($role_user=='P') {$lib_role='Proprietaire';} 
  elseif ($role_user=='D') {$lib_role='DTN';} 
  elseif ($role_user=='S') {$lib_role='S&eacute;lectionneur';}
  else {$lib_role='???';}

  if ($joueurDTN['idHattrickJoueur']==$joueurHT['idHattrickJoueur']) {
    // le joueur existe dans la base DTN => on le met � jour
    return majJoueur($ht_user,$role_user,$joueurHT,$joueurDTN);
  } elseif (!$joueurDTN) {
    //******* INSERT dans la table HT_JOUEURS *******//
    // le joueur n'existe pas dans la base DTN => on l'ins�re
    
    // Tableau pour calcul des notes
    $joueurNote["idEndurance"] = $joueurHT["idEndurance"] ;
    $joueurNote["idDefense"] = $joueurHT["idDefense"] ;
    $joueurNote["idAilier"] = $joueurHT["idAilier"] ;
    $joueurNote["idGardien"] = $joueurHT["idGardien"] ;
    $joueurNote["idConstruction"] = $joueurHT["idConstruction"] ;
    $joueurNote["idPasse"] = $joueurHT["idPasse"] ;
    $joueurNote["idButeur"] = $joueurHT["idButeur"] ;
    $joueurNote["idExperience_fk"] = $joueurHT["idExperience_fk"] ;
    $joueurNote["idPA"] = $joueurHT["idPA"] ;

    $joueurNote["nbSemaineConstruction"] =  0.0;
    $joueurNote["nbSemaineGardien"] =  0.0;
    $joueurNote["nbSemainePasses"] =  0.0;
    $joueurNote["nbSemaineDefense"] =  0.0;
    $joueurNote["nbSemaineButeur"] =  0.0;
    $joueurNote["nbSemaineAilier"] =  0.0;
    $joueurNote["valeurEnCours"] = $joueurHT['tsi'];
    
    $joueurNote["optionJoueur"] = $joueurHT["optionJoueur"];
    $age=ageetjour($joueurHT["datenaiss"],"2");
    $joueurNote["ageJoueur"] = $age["ageJoueur"];
    $joueurNote["jourJoueur"] = $age["jourJoueur"];
    
    $joueurNote["score"] = calculNote($joueurNote);

    $sql = "INSERT INTO $tbl_joueurs (";
    $sql .= " nomJoueur,";
    $sql .= " idAggre_fk,";
    $sql .= " idLeader_fk,";
    $sql .= " idCaractere_fk,";
    $sql .= " idHonnetete_fk,";
    $sql .= " idExperience_fk,";
    $sql .= " idEndurance,";
    $sql .= " idGardien,";
    $sql .= " idConstruction,";
    $sql .= " idPasse,";
    $sql .= " idAilier,";
    $sql .= " idDefense,";;
    $sql .= " idButeur,";
    $sql .= " idPA,";
    $sql .= " optionJoueur,";
    $sql .= " ht_posteAssigne, ";
    $sql .= " teamid,";
    $sql .= " dateSaisieJoueur,";
    $sql .= " date_modif_effectif,";
    $sql .= " datenaiss,";
    $sql .= " idHattrickJoueur,";
    $sql .= " scoreGardien,";
    $sql .= " scoreDefense,";
    $sql .= " scoreDefCentralOff,";
    $sql .= " scoreDefLat,";
    $sql .= " scoreDefLatOff,";
    $sql .= " scoreMilieu,";
    $sql .= " scoreMilieuOff,";
    $sql .= " scoreMilieuDef,";
    $sql .= " scoreAilier,";
    $sql .= " scoreAilierOff,";
    $sql .= " scoreAilierVersMilieu,";
    $sql .= " scoreAttaquant,";
    $sql .= " scoreAttaquantDef,";
    $sql .= " scoreAttaquantVersAile,";
    $sql .= " salary";
    $sql .= ") VALUES (";
    $sql .= " '".$joueurHT['nomJoueur']."'";
    $sql .= ",'".$joueurHT['idAggre_fk']."'";
    $sql .= ",'".$joueurHT['idLeader_fk']."'";
    $sql .= ",'".$joueurHT['idCaractere_fk']."'";
    $sql .= ",'".$joueurHT['idHonnetete_fk']."'";
    $sql .= ",'".$joueurHT['idExperience_fk']."'";
    $sql .= ",'".$joueurHT['idEndurance']."'";
    $sql .= ",'".$joueurHT['idGardien']."'";
    $sql .= ",'".$joueurHT['idConstruction']."'";
    $sql .= ",'".$joueurHT['idPasse']."'";
    $sql .= ",'".$joueurHT['idAilier']."'";
    $sql .= ",'".$joueurHT['idDefense']."'";
    $sql .= ",'".$joueurHT['idButeur']."'";
    $sql .= ",'".$joueurHT['idPA']."'";
    $sql .= ",'".$joueurHT['optionJoueur']."'";
    $sql .= ",'".$posteAssigne."' ";
    $sql .= ",'".$joueurHT['teamid']."'";
    $sql .= ",'".date("Y-m-d")."'";
    $sql .= ",'".date("Y-m-d")."'";
    $sql .= ",'".$joueurHT['datenaiss']."'";
    $sql .= ",'".$joueurHT['idHattrickJoueur']."'";
    $sql .= ",'".$joueurNote["score"]["gardien"]."'";
    $sql .= ",'".$joueurNote["score"]["defense"]."'";
    $sql .= ",'".$joueurNote["score"]["defCentralOff"]."'";
    $sql .= ",'".$joueurNote["score"]["defenseLat"]."'";
    $sql .= ",'".$joueurNote["score"]["defenseLatOff"]."'";
    $sql .= ",'".$joueurNote["score"]["milieu"]."'";
    $sql .= ",'".$joueurNote["score"]["milieuOff"]."'";
    $sql .= ",'".$joueurNote["score"]["milieuDef"]."'";
    $sql .= ",'".$joueurNote["score"]["ailier"]."'";
    $sql .= ",'".$joueurNote["score"]["ailierOff"]."'";
    $sql .= ",'".$joueurNote["score"]["ailierVersMilieu"]."'";
    $sql .= ",'".$joueurNote["score"]["attaquant"]."'";
    $sql .= ",'".$joueurNote["score"]["attaquantDef"]."'";
    $sql .= ",'".$joueurNote["score"]["attaquantVersAile"]."'";
    $sql .= ",'".$joueurHT['salary']."'";
    $sql .= " ) ";
     
    $reqValid= $conn->exec($sql);
    
    if (!$reqValid) {
      return false;
    } else {
      $resu['idJoueur']=$conn->lastInsertId();
    }


      
    //******* INSERT dans la table HT_ENTRAINEMENT *******//
    $sql = "INSERT INTO $tbl_entrainement (idJoueur_fk, valeurEnCours) 
            VALUES ('".$resu['idJoueur']."','".$joueurHT['tsi']."') ";

    $reqValid= $conn->exec($sql);

    if (!$reqValid) {
      return false;
    } else {
      $resu['idEntrainement']=$conn->lastInsertId();
    }

    //******* INSERT dans la table HT_NOTES_POTENTIEL *******//
    $joueurNote["idJoueur"] = $resu['idJoueur'];
    $joueurNote["scorePotentiel"] = calculNotePotentiel($joueurNote);
    $_POST["idJoueur_fk"]   = $resu['idJoueur'];
    $_POST["noteGardien"]   = $joueurNote["scorePotentiel"]["gardien"];
    $_POST["noteDefense"]   = $joueurNote["scorePotentiel"]["defense"];
    $_POST["noteLateral"]   = $joueurNote["scorePotentiel"]["lateral"];
    $_POST["noteMilieu"]    = $joueurNote["scorePotentiel"]["milieu"];
    $_POST["noteAilier"]    = $joueurNote["scorePotentiel"]["ailier"];
    $_POST["noteAttaquant"] = $joueurNote["scorePotentiel"]["attaquant"];
    $sql = insertDB($tbl_notes_potentiel);
    
    
    //******* INSERT dans la table HT_HISTOMODIF *******//
    $histo  = " { endu ".$joueurHT['idEndurance']." / ";
    $histo .= " gard ".$joueurHT['idGardien']." / ";
    $histo .= " cons ".$joueurHT['idConstruction']." / ";
    $histo .= " pass ".$joueurHT['idPasse']." / ";
    $histo .= " aili ".$joueurHT['idAilier']." / ";
    $histo .= " defe ".$joueurHT['idDefense']." / ";
    $histo .= " bute ".$joueurHT['idButeur']." / ";
    $histo .= " c.fr ".$joueurHT['idPA']." } ";
  
    $sqlhisto = "INSERT INTO $tbl_histomodif (  
                        idJoueur_fk,
                        idAdmin_fk,
                        dateHisto, 
                        heureHisto, 
                        intituleHisto) 
                  VALUES (
                        '".$resu["idJoueur"]."',
                        '0',
                        '".date("Y-m-d")."',
                        '".date("H:i")."',
                        'Joueur cree automatiquement par ".$ht_user."(".$lib_role.") ".$histo."') ";

    $reqValid= $conn->exec($sqlhisto);
    
    if (!$reqValid) {
      return false;
    } else {
      $resu['idHisto']=$conn->lastInsertId();
    }
    
    
    //******* INSERT dans la table HT_CLUBS *******//
/*    if ($majClub==1) {
      $resuMajClub=majClub($joueurHT["teamid"]);
      $resu['idClub']=$resuMajClub['idClub'];
      unset ($resuMajClub);
    }*/
    
    
    //******* INSERT dans la table HT_JOUEURS_HISTO *******//
    // Num�ro de saison et semaine � la date du jour
    $actualSeason=getSeasonWeekOfMatch(mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')));
    $existHJ = existHistoJoueur($joueurHT["idHattrickJoueur"],$actualSeason["season"],$actualSeason["week"]);

    if ($existHJ==false) {
      $resu['joueurs_histo']=$update_joueurs_histo=getDataJoueurHisto($joueurHT,$actualSeason);
      $resu['joueurs_histo']['id_joueur_histo'] = insertJoueurHisto($update_joueurs_histo);
    } else {
      //Sinon, on remet � jour uniquement si le joueur est en vente
      if (($existHJ['transferListed']==0) && ($joueurHT['transferListed']==1)) {
          $update_joueurs_histo['transferListed']=$joueurHT['transferListed'];
          $update_joueurs_histo['id_joueur_histo']=$existHJ['id_joueur_histo'];
          $resu['id_joueur_histo'] = updateJoueurHisto($update_joueurs_histo);
      }
    }
    unset($update_joueurs_histo);
    unset($existHJ);

  
  $resu['idHattrickJoueur']=$joueurDTN['idHattrickJoueur'];
  if (isset($joueurHT['teamid'])) {$resu['teamid']=$joueurHT['teamid'];} else {$resu['teamid']=$joueurDTN['teamid'];}

  return $resu;
  
  }
}


/******************************************************************************/
/* Objet : Update d'un joueur dans la base de donn�es dtn                     */
/* Modifi� le 17/06/2010 par Musta56 - Cr�ation fonction                      */
/******************************************************************************/
/* Entr�e : $joueur = tableau joueur (uniquement les donn�es � mettre � jour) */
/* Sortie : $joueur["idJoueur"] = identifiant du joueur dans bdd dtn          */
/*          ou false = si �chec de l'update                                   */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/*           - ./dtn/interface/includes/serviceJoueur.php                     */
/******************************************************************************/
function updateJoueur($joueur) {
	global $conn;

	require($_SERVER['DOCUMENT_ROOT'].'/dtn/interface/includes/nomTables.inc.php');

	$sql="UPDATE $tbl_joueurs SET ";
	if (isset($joueur['nomJoueur']))                {$sql.="nomJoueur = '".$joueur['nomJoueur']."',";}
	if (isset($joueur['prenomJoueur']))             {$sql.="prenomJoueur = '".$joueur['prenomJoueur']."',";}
	if (isset($joueur['teamid']))                   {$sql.="teamid = '".$joueur['teamid']."',";}
	if (isset($joueur['idHattrickJoueur']))         {$sql.="idHattrickJoueur = '".$joueur['idHattrickJoueur']."',";}
	if (isset($joueur['idAggre_fk']))               {$sql.="idAggre_fk = '".$joueur['idAggre_fk']."',";}
	if (isset($joueur['idLeader_fk']))              {$sql.="idLeader_fk = '".$joueur['idLeader_fk']."',";}
	if (isset($joueur['idCaractere_fk']))           {$sql.="idCaractere_fk = '".$joueur['idCaractere_fk']."',";}
	if (isset($joueur['idHonnetete_fk']))           {$sql.="idHonnetete_fk = '".$joueur['idHonnetete_fk']."',";}
	if (isset($joueur['idExperience_fk']))          {$sql.="idExperience_fk = '".$joueur['idExperience_fk']."',";}
	if (isset($joueur['idEndurance']))              {$sql.="idEndurance = '".$joueur['idEndurance']."',";}
	if (isset($joueur['idGardien']))                {$sql.="idGardien = '".$joueur['idGardien']."',";}
	if (isset($joueur['idConstruction']))           {$sql.="idConstruction = '".$joueur['idConstruction']."',";}
	if (isset($joueur['idPasse']))                  {$sql.="idPasse = '".$joueur['idPasse']."',";}
	if (isset($joueur['idAilier']))                 {$sql.="idAilier = '".$joueur['idAilier']."',";}
	if (isset($joueur['idDefense']))                {$sql.="idDefense = '".$joueur['idDefense']."',";}
	if (isset($joueur['idButeur']))                 {$sql.="idButeur = '".$joueur['idButeur']."',";}
	if (isset($joueur['idPA']))                     {$sql.="idPA = '".$joueur['idPA']."',";}
	if (isset($joueur['optionJoueur']))             {$sql.="optionJoueur = '".$joueur['optionJoueur']."',";}
	if (isset($joueur['dateSaisieJoueur']))         {$sql.="dateSaisieJoueur = '".$joueur['dateSaisieJoueur']."',";}
	if (isset($joueur['dateDerniereModifJoueur']))  {$sql.="dateDerniereModifJoueur = '".$joueur['dateDerniereModifJoueur']."',";}
	if (isset($joueur['date_modif_effectif']))      {$sql.="date_modif_effectif = '".$joueur['date_modif_effectif']."',";}
	if (isset($joueur['saisonApparitionJoueur']))   {$sql.="saisonApparitionJoueur = '".$joueur['saisonApparitionJoueur']."',";}
	if (isset($joueur['ageJoueur']))                {$sql.="ageJoueur = '".$joueur['ageJoueur']."',";}
	if (isset($joueur['jourJoueur']))               {$sql.="jourJoueur = '".$joueur['jourJoueur']."',";}
	if (isset($joueur['datenaiss']))                {$sql.="datenaiss = '".$joueur['datenaiss']."',";}
	if (isset($joueur['AdminSaisieJoueur_fk']))     {$sql.="AdminSaisieJoueur_fk = '".$joueur['AdminSaisieJoueur_fk']."',";}
	if (isset($joueur['dtnSuiviJoueur_fk']))        {$sql.="dtnSuiviJoueur_fk = '".$joueur['dtnSuiviJoueur_fk']."',";}
	if (isset($joueur['ht_posteAssigne']))          {$sql.="ht_posteAssigne = '".$joueur['ht_posteAssigne']."',";}
	if (isset($joueur['scoreGardien']))             {$sql.="scoreGardien = '".$joueur['scoreGardien']."',";}
	if (isset($joueur['scoreDefense']))             {$sql.="scoreDefense = '".$joueur['scoreDefense']."',";}
	if (isset($joueur['scoreAilierOff']))           {$sql.="scoreAilierOff = '".$joueur['scoreAilierOff']."',";}
	if (isset($joueur['scoreMilieu']))              {$sql.="scoreMilieu = '".$joueur['scoreMilieu']."',";}
	if (isset($joueur['scoreAttaquant']))           {$sql.="scoreAttaquant = '".$joueur['scoreAttaquant']."',";}
	if (isset($joueur['scoreAttaquantVersAile']))   {$sql.="scoreAttaquantVersAile = '".$joueur['scoreAttaquantVersAile']."',";}
	if (isset($joueur['scoreAttaquantDef']))        {$sql.="scoreAttaquantDef = '".$joueur['scoreAttaquantDef']."',";}
	if (isset($joueur['scoreDefCentralOff']))       {$sql.="scoreDefCentralOff = '".$joueur['scoreDefCentralOff']."',";}
	if (isset($joueur['scoreDefLat']))              {$sql.="scoreDefLat = '".$joueur['scoreDefLat']."',";}
	if (isset($joueur['scoreDefLatOff']))           {$sql.="scoreDefLatOff = '".$joueur['scoreDefLatOff']."',";}
	if (isset($joueur['scoreMilieuOff']))           {$sql.="scoreMilieuOff = '".$joueur['scoreMilieuOff']."',";}
	if (isset($joueur['scoreMilieuDef']))           {$sql.="scoreMilieuDef = '".$joueur['scoreMilieuDef']."',";}
	if (isset($joueur['scoreAilier']))              {$sql.="scoreAilier = '".$joueur['scoreAilier']."',";}
	if (isset($joueur['scoreAilierVersMilieu']))    {$sql.="scoreAilierVersMilieu = '".$joueur['scoreAilierVersMilieu']."',";}
	if (isset($joueur['joueurActif']))              {$sql.="joueurActif = '".$joueur['joueurActif']."',";}
	if (isset($joueur['affJoueur']))                {$sql.="affJoueur = '".$joueur['affJoueur']."',";}
	if (isset($joueur['idopinion']))                {$sql.="idopinion = '".$joueur['idopinion']."',";}
	if (isset($joueur['archiveJoueur']))            {$sql.="archiveJoueur = '".$joueur['archiveJoueur']."',";}
	if (isset($joueur['entrainement_id']))          {$sql.="entrainement_id = '".$joueur['entrainement_id']."',";}
	if (isset($joueur['salary']))                   {$sql.="salary = '".$joueur['salary']."',";}
	if (isset($club["dateLastScanMatch"]))          {$sql.="dateLastScanMatch = '".$club["dateLastScanMatch"]."',";}
	$sql=substr($sql,0,strlen($sql)-1);
	$sql.=" WHERE idJoueur = ".$joueur["idJoueur"];
	$reqValid = $conn->exec($sql);

	if ($reqValid === FALSE) {
		return false;
	} else {
		return $joueur["idJoueur"];
	}

}


/******************************************************************************/
/* Objet : Update de l'entrainement d'un joueur dans la base de donn�es dtn   */
/* Modifi� le 05/11/2010 par Musta56 - Cr�ation fonction                      */
/******************************************************************************/
/* Entr�e : $entrainement = tableau entrainement(uniquement les donn�es � maj)*/
/* Sortie : $entrainement["idEntrainement"] = ident entrainement dans bdd dtn */
/*          ou false = si �chec de l'update                                   */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/*           - ./dtn/interface/includes/serviceJoueur.php                     */
/******************************************************************************/
function updateEntrainement($entrainement) {
	global $conn;

  require($_SERVER['DOCUMENT_ROOT'].'/dtn/interface/includes/nomTables.inc.php');
  
  $sql="UPDATE $tbl_entrainement SET ";
  if (isset($entrainement['nbSemaineConstruction']))  {$sql.="nbSemaineConstruction = '".$entrainement['nbSemaineConstruction']."',";}
  if (isset($entrainement['nbSemaineAilier']))        {$sql.="nbSemaineAilier = '".$entrainement['nbSemaineAilier']."',";}
  if (isset($entrainement['nbSemaineButeur']))        {$sql.="nbSemaineButeur = '".$entrainement['nbSemaineButeur']."',";}
  if (isset($entrainement['nbSemaineGardien']))       {$sql.="nbSemaineGardien = '".$entrainement['nbSemaineGardien']."',";}
  if (isset($entrainement['nbSemainePasses']))        {$sql.="nbSemainePasses = '".$entrainement['nbSemainePasses']."',";}
  if (isset($entrainement['nbSemaineDefense']))       {$sql.="nbSemaineDefense = '".$entrainement['nbSemaineDefense']."',";}
  if (isset($entrainement['valeurEnCours']))          {$sql.="valeurEnCours = '".$entrainement['valeurEnCours']."',";}
  $sql=substr($sql,0,strlen($sql)-1);
  $sql.=" WHERE idJoueur_fk  = ".$entrainement["idJoueur_fk"];
  $reqValid= $conn->exec($sql);

	if ($reqValid === FALSE) {
		return false;
	} else {
		return $entrainement['idJoueur_fk'];
	}

}




/******************************************************************************/
/* Objet : Mise � jour d'un joueur                                            */
/* Modifi� le 17/06/2010 par Musta56 - Cr�ation fonction                      */
/* Modifi� le 04/03/2011 par Musta56 - Ajout booleen est_maj en sortie        */
/* Modifi� le 13/05/2011 par Musta56 - Suppression affichage du pr�nom        */
/* Modifi� le 22/11/2011 par Musta56 - Utilisation majClub et suppression     */
/*                                     param�tre $clubHT                      */
/* Modifi� le 22/11/2011 par Musta56 - Suppression appel majClub              */
/* Modifi� le 10/12/2011 par Musta56 - Suppression param maBase et ajout param*/
/*         joueurDTN                                                          */
/******************************************************************************/
/* Entr�e : $ht_user = nom du manager (DTN ou proprio) connect�               */
/* Entr�e : $role_user = role du manager connect� : DTN (D) ou proprio (P)    */
/* Entr�e : $joueurHT = tableau joueur (donn�es provenant de HT)              */
/* Entr�e : $joueurDTN = tableau joueur (donn�es provenant de DTN)            */
/* Sortie : $posteAssigne = Poste d'assignation du joueur                     */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/* - ./dtn/interface/includes/serviceJoueur.php (fonction scanListeJoueurs)   */
/* - ..\maj\majJoueursArchives.php                                            */
/******************************************************************************/
function majJoueur($ht_user,$role_user,$joueurHT,$joueurDTN){
	global $conn;

  unset($update_joueur);

  if ($role_user=='P') {
    $lib_role='Proprietaire';
    //******** Date Saisie Joueur ************//
    $update_joueur['dateSaisieJoueur']=date('Y-m-d');
  } 
  elseif ($role_user=='D') {$lib_role='DTN';} 
  elseif ($role_user=='S') {
    $lib_role='S&eacute;lectionneur';
    $update_joueur['dateDerniereModifJoueur']=date('Y-m-d');
  }
  else {$lib_role='???';}

  if (!$joueurDTN) {
      //echo('joueur inexistant<br>');
      // le joueur n'existe pas dans la base dtn
      return false;
  } else {
    // le joueur existe dans la base DTN => on le met � jour
  
    // Appel script externes
    include_once($_SERVER['DOCUMENT_ROOT'].'/dtn/interface/CHPP/config.php');
    require($_SERVER['DOCUMENT_ROOT'].'/dtn/interface/includes/nomTables.inc.php');
  
    // Variable du traitement de MAJ
    $recalcul_note=false;
    $nbupdate=0;
    $histoModifMsg=null;
    $resMAJ['HTML']='';
    $resMAJ['joueur_est_maj']=false;
    //$resMAJ['club_est_maj']=false;
    unset($update_club);
    unset($update_joueur_entrainement);
  
    if ($joueurHT != false){ //check that correct player is fetched
      $resMAJ['HTML']="<tr valign=\"top\"> <td><a href=\"".$_SESSION['url']."/joueurs/ficheDTN.php?id=".$joueurDTN["idJoueur"]."\">".$joueurHT["idHattrickJoueur"]."</a><b><i> / " .$joueurHT["nomJoueur"]. "</b></i></td><td>";

            
      //******** correction des noms des joueurs ************//
      if ($joueurDTN['nomJoueur']!=$joueurHT['nomJoueur']){
        $update_joueur['nomJoueur']=$joueurHT['nomJoueur'];
      }
      
      
      //*******  DATE NAISSANCE *******//
      if ($joueurDTN['datenaiss']!=$joueurHT['datenaiss']){
        $update_joueur['datenaiss']=$joueurHT['datenaiss'];
        $affichageAge=ageetjour($joueurHT['datenaiss']);
        $resMAJ['HTML'].=$affichageAge.' /naiss='.$joueurDTN['datenaiss'].'-&gt;'.$joueurHT['datenaiss'];
        $joueurDTN['datenaiss']=$joueurHT['datenaiss'];
      } else {
          $resMAJ['HTML'].=ageetjour($joueurDTN['datenaiss']);
      }
      
      
      //*******  FORME *******//
      $resMAJ['HTML'].='</td><td>'.$joueurHT['forme'].'</td><td>';
  
  
      //*******  TSI *******//
      $nbupdate=0;
      if ($joueurDTN["valeurEnCours"]!=$joueurHT['tsi']){
        $update_joueur_entrainement['valeurEnCours']=$joueurHT['tsi'];
        $resMAJ['HTML'].=$joueurDTN['valeurEnCours'].'-&gt; '.$joueurHT['tsi'] ;  
      }else{
        $resMAJ['HTML'].=$joueurDTN['valeurEnCours'].' = ';
      }
        
      $resMAJ['HTML'].='</td><td>';
      

      //******* SALAIRE *******//
      if ($joueurDTN['salary']!=$joueurHT['salary']){
        $update_joueur['salary']=$joueurHT['salary'];
        $resMAJ['HTML'].=round(($joueurDTN["salary"]/10),2).'-&gt;'.round(($joueurHT['salary']/10),2);
        $histoModifMsg.=' salaire '.round(($joueurDTN["salary"]/10),2).' -> '.round(($joueurHT['salary']/10),2);
        $joueurDTN["salary"]=$joueurHT['salary'];
      }else{
        $resMAJ['HTML'].=round(($joueurDTN["salary"]/10),2);
      }
      
      $resMAJ['HTML'].='</td><td>';
      
      
      //******* EXPERIENCE *******//
      if ($joueurDTN['idExperience_fk']!=$joueurHT['idExperience_fk']){
        $update_joueur['idExperience_fk']=$joueurHT['idExperience_fk'];
        $resMAJ['HTML'].=$joueurDTN["idExperience_fk"].'-&gt; '.$joueurHT['idExperience_fk'] ;
        $histoModifMsg.=' xp '.$joueurDTN['idExperience_fk'].' -> '.$joueurHT['idExperience_fk'];
        $joueurDTN["idExperience_fk"]=$joueurHT['idExperience_fk'];
        $recalcul_note=true;
      }else{
          $resMAJ['HTML'].=$joueurHT['idExperience_fk'].' = ';
      }
      
      $resMAJ['HTML'].='</td><td>';
  

      //******* CHANGEMENT CLUB ? *******//
      if ($joueurHT['teamid']!= $joueurDTN['teamid']){
        $resMAJ['HTML'].='<font color=red><b>';
        $update_joueur['teamid']=$joueurHT['teamid'];
        $resMAJ['HTML'].='Transfert :'.$joueurDTN['teamid'] .'->'.$joueurHT['teamid'].'<br></b></font>';
        $histoModifMsg .='Transfert : teamid '.$joueurDTN["teamid"] .'->'.$joueurHT['teamid'];
        
      }
      
  
      //******* EN VENTE ?? *******//
      if ( $joueurHT['transferListed']==1 ){
        $resMAJ['HTML'].='<font color=green><b>Joueur en vente!</b><br></font>';
      }
    
  
      //******* AGRESSIVITE *******//
      if ($joueurDTN['idAggre_fk']!=$joueurHT['idAggre_fk']){
        $update_joueur['idAggre_fk']=$joueurHT['idAggre_fk'];
        $resMAJ['HTML'].=' Agressivit&eacute;: '.$joueurDTN["idAggre_fk"].'-&gt; '.$joueurHT['idAggre_fk'].'<br>' ;
        $histoModifMsg .=' Agressivit&eacute;: '.$joueurDTN['idAggre_fk'].' -> '.$joueurHT['idAggre_fk'];
        $joueurDTN["idAggre_fk"]=$joueurHT['idAggre_fk'];
      }
      
      
      //******* TDC *******//
      if ($joueurDTN['idLeader_fk']!=$joueurHT['idLeader_fk']){
        $update_joueur['idLeader_fk']=$joueurHT['idLeader_fk'];
        $resMAJ['HTML'].=' TDC: '.$joueurDTN["idLeader_fk"].'-&gt; '.$joueurHT['idLeader_fk'].'<br>' ;
        $histoModifMsg .=' TDC: '.$joueurDTN['idLeader_fk'].' -> '.$joueurHT['idLeader_fk'];
        $joueurDTN["idLeader_fk"]=$joueurHT['idLeader_fk'];
      }
  
  
      //******* CARACTERE *******//
      if ($joueurDTN['idCaractere_fk']!=$joueurHT['idCaractere_fk']){
        $update_joueur['idCaractere_fk']=$joueurHT['idCaractere_fk'];
        $resMAJ['HTML'].=' Caract&egrave;re: '.$joueurDTN["idCaractere_fk"].'-&gt; '.$joueurHT['idCaractere_fk'].'<br>' ;
        $histoModifMsg .=' Caract&egrave;re: '.$joueurDTN['idCaractere_fk'].' -> '.$joueurHT['idCaractere_fk'];
        $joueurDTN["idCaractere_fk"]=$joueurHT['idCaractere_fk'];
      }
  
  
      //******* HONNETETE *******//
      if ($joueurDTN['idHonnetete_fk']!=$joueurHT['idHonnetete_fk']){
        $update_joueur['idHonnetete_fk']=$joueurHT['idHonnetete_fk'];
        $resMAJ['HTML'].=' Honnetet&eacute;: '.$joueurDTN["idHonnetete_fk"].'-&gt; '.$joueurHT['idHonnetete_fk'].'<br>' ;
        $histoModifMsg .=' Honnetet&eacute;: '.$joueurDTN['idHonnetete_fk'].' -> '.$joueurHT['idHonnetete_fk'];
        $joueurDTN["idHonnetete_fk"]=$joueurHT['idHonnetete_fk'];
      }
  
      //******* ENDU *******//
      if ($joueurDTN['idEndurance']!=$joueurHT['idEndurance']){
        $update_joueur['idEndurance']=$joueurHT['idEndurance'];
        $resMAJ['HTML'].=' Endu: '.$joueurDTN["idEndurance"].'-&gt; '.$joueurHT['idEndurance'].'<br>' ;
        $histoModifMsg .=' Endurance: '.$joueurDTN['idEndurance'].' -> '.$joueurHT['idEndurance'];
        $joueurDTN["idEndurance"]=$joueurHT['idEndurance'];
        $recalcul_note=true;
        $update_joueur['date_modif_effectif']=date('Y-m-d');
      }
  
      //******* SKILL DU JOUEUR *******//
      if ($joueurHT['caracVisible']==true) {
        
        //******* GARDIEN *******//
        if ($joueurDTN['idGardien']!=$joueurHT['idGardien']){
          $update_joueur['idGardien']=$joueurHT['idGardien'];
          $resMAJ['HTML'].=' Gardien: '.$joueurDTN["idGardien"].'-&gt; '.$joueurHT['idGardien'].'<br>' ;
          $histoModifMsg .=' Gardien: '.$joueurDTN['idGardien'].' -> '.$joueurHT['idGardien'];
          $joueurDTN["idGardien"]=$joueurHT['idGardien'];
          $recalcul_note=true;
          $update_joueur['date_modif_effectif']=date('Y-m-d');
          $update_joueur_entrainement['nbSemaineGardien']=0;
        }  
    
        //******* CONSTRUCTION *******//
        if ($joueurDTN['idConstruction']!=$joueurHT['idConstruction']){
          $update_joueur['idConstruction']=$joueurHT['idConstruction'];
          $resMAJ['HTML'].=' Constru.: '.$joueurDTN["idConstruction"].'-&gt; '.$joueurHT['idConstruction'].'<br>' ;
          $histoModifMsg .=' Construction: '.$joueurDTN['idConstruction'].' -> '.$joueurHT['idConstruction'];
          $joueurDTN["idConstruction"]=$joueurHT['idConstruction'];
          $recalcul_note=true;
          $update_joueur['date_modif_effectif']=date('Y-m-d');
          $update_joueur_entrainement['nbSemaineConstruction']=0;
        }
  
        //******* PASSE *******//
        if ($joueurDTN['idPasse']!=$joueurHT['idPasse']){
          $update_joueur['idPasse']=$joueurHT['idPasse'];
          $resMAJ['HTML'].=' Passe: '.$joueurDTN["idPasse"].'-&gt; '.$joueurHT['idPasse'].'<br>' ;
          $histoModifMsg .=' Passe: '.$joueurDTN['idPasse'].' -> '.$joueurHT['idPasse'];
          $joueurDTN["idPasse"]=$joueurHT['idPasse'];
          $recalcul_note=true;
          $update_joueur['date_modif_effectif']=date('Y-m-d');
          $update_joueur_entrainement['nbSemainePasses']=0;
        }
  
        //******* AILIER *******//
        if ($joueurDTN['idAilier']!=$joueurHT['idAilier']){
          $update_joueur['idAilier']=$joueurHT['idAilier'];
          $resMAJ['HTML'].=' Ailier: '.$joueurDTN["idAilier"].'-&gt; '.$joueurHT['idAilier'].'<br>' ;
          $histoModifMsg .=' Ailier: '.$joueurDTN['idAilier'].' -> '.$joueurHT['idAilier'];
          $joueurDTN["idAilier"]=$joueurHT['idAilier'];
          $recalcul_note=true;
          $update_joueur['date_modif_effectif']=date('Y-m-d');
          $update_joueur_entrainement['nbSemaineAilier']=0;
        }
  
        //******* DEFENSE *******//
        if ($joueurDTN['idDefense']!=$joueurHT['idDefense']){
          $update_joueur['idDefense']=$joueurHT['idDefense'];
          $resMAJ['HTML'].=' Def.: '.$joueurDTN["idDefense"].'-&gt; '.$joueurHT['idDefense'].'<br>' ;
          $histoModifMsg .=' Defense: '.$joueurDTN['idDefense'].' -> '.$joueurHT['idDefense'];
          $joueurDTN["idDefense"]=$joueurHT['idDefense'];
          $recalcul_note=true;
          $update_joueur['date_modif_effectif']=date('Y-m-d');
          $update_joueur_entrainement['nbSemaineDefense']=0;
        }
  
        //******* BUTEUR *******//
        if ($joueurDTN['idButeur']!=$joueurHT['idButeur']){
          $update_joueur['idButeur']=$joueurHT['idButeur'];
          $resMAJ['HTML'].=' Buteur: '.$joueurDTN["idButeur"].'-&gt; '.$joueurHT['idButeur'].'<br>' ;
          $histoModifMsg .=' Buteur: '.$joueurDTN['idButeur'].' -> '.$joueurHT['idButeur'];
          $joueurDTN["idButeur"]=$joueurHT['idButeur'];
          $recalcul_note=true;
          $update_joueur['date_modif_effectif']=date('Y-m-d');
          $update_joueur_entrainement['nbSemaineButeur']=0;
        }
  
        //******* CF *******//
        if ($joueurDTN['idPA']!=$joueurHT['idPA']){
          $update_joueur['idPA']=$joueurHT['idPA'];
          $resMAJ['HTML'].=' CF: '.$joueurDTN["idPA"].'-&gt; '.$joueurHT['idPA'].'<br>' ;
          $histoModifMsg .=' CoupFranc: '.$joueurDTN['idPA'].' -> '.$joueurHT['idPA'];
          $joueurDTN["idPA"]=$joueurHT['idPA'];
          $recalcul_note=true;
          $update_joueur['date_modif_effectif']=date('Y-m-d');
        }
  
          
      }
 
  
      //******* BLESSE ?? *******//
      // Peut ne pas �tre d�fini si le joueur joue un match
      if (isset($joueurHT['blessure']) && $joueurHT['blessure']!=-1 && $joueurHT['blessure']!=null){
        $resMAJ['HTML'].='<font color=red><b>Joueur bless&eacute;(+'.$joueurHT['blessure'].')!</b></font><br>';
      }
      
      
      
      //*** Si carac du joueur mis � jour alors on recalcul les notes ***//  
      if ($recalcul_note) {
              
        if (isset($joueurHT["idEndurance"]))    {$joueurNote["idEndurance"]    = $joueurHT["idEndurance"] ;}    else {$joueurNote["idEndurance"]    = $joueurDTN["idEndurance"] ;}
        if (isset($joueurHT["idDefense"]))      {$joueurNote["idDefense"]      = $joueurHT["idDefense"] ;}      else {$joueurNote["idDefense"]      = $joueurDTN["idDefense"] ;}
        if (isset($joueurHT["idAilier"]))       {$joueurNote["idAilier"]       = $joueurHT["idAilier"] ;}       else {$joueurNote["idAilier"]       = $joueurDTN["idAilier"] ;}
        if (isset($joueurHT["idGardien"]))      {$joueurNote["idGardien"]      = $joueurHT["idGardien"] ;}      else {$joueurNote["idGardien"]      = $joueurDTN["idGardien"] ;}
        if (isset($joueurHT["idConstruction"])) {$joueurNote["idConstruction"] = $joueurHT["idConstruction"] ;} else {$joueurNote["idConstruction"] = $joueurDTN["idConstruction"] ;}
        if (isset($joueurHT["idPasse"]))        {$joueurNote["idPasse"]        = $joueurHT["idPasse"] ;}        else {$joueurNote["idPasse"]        = $joueurDTN["idPasse"] ;}
        if (isset($joueurHT["idButeur"]))       {$joueurNote["idButeur"]       = $joueurHT["idButeur"] ;}       else {$joueurNote["idButeur"]       = $joueurDTN["idButeur"] ;}
        if (isset($joueurHT["idPA"]))           {$joueurNote["idPA"]           = $joueurHT["idPA"] ;}           else {$joueurNote["idPA"]           = $joueurDTN["idPA"] ;}

        $joueurNote["idExperience_fk"] = $joueurHT["idExperience_fk"] ;
  
        if (isset($update_joueur_entrainement["nbSemaineConstruction"]))  {$joueurNote["nbSemaineConstruction"] = $update_joueur_entrainement["nbSemaineConstruction"]; } else {$joueurNote["nbSemaineConstruction"]  = $joueurDTN["nbSemaineConstruction"];}
        if (isset($update_joueur_entrainement["nbSemaineGardien"]))       {$joueurNote["nbSemaineGardien"]      = $update_joueur_entrainement["nbSemaineGardien"];      } else {$joueurNote["nbSemaineGardien"]       = $joueurDTN["nbSemaineGardien"];}
        if (isset($update_joueur_entrainement["nbSemainePasses"]))        {$joueurNote["nbSemainePasses"]       = $update_joueur_entrainement["nbSemainePasses"];       } else {$joueurNote["nbSemainePasses"]        = $joueurDTN["nbSemainePasses"];}
        if (isset($update_joueur_entrainement["nbSemaineDefense"]))       {$joueurNote["nbSemaineDefense"]      = $update_joueur_entrainement["nbSemaineDefense"];      } else {$joueurNote["nbSemaineDefense"]       = $joueurDTN["nbSemaineDefense"];}
        if (isset($update_joueur_entrainement["nbSemaineButeur"]))        {$joueurNote["nbSemaineButeur"]       = $update_joueur_entrainement["nbSemaineButeur"];       } else {$joueurNote["nbSemaineButeur"]        = $joueurDTN["nbSemaineButeur"];}
        if (isset($update_joueur_entrainement["nbSemaineAilier"]))        {$joueurNote["nbSemaineAilier"]       = $update_joueur_entrainement["nbSemaineAilier"];       } else {$joueurNote["nbSemaineAilier"]        = $joueurDTN["nbSemaineAilier"];}

        $joueurNote["valeurEnCours"] = $joueurDTN["valeurEnCours"];
        
        $joueurNote["optionJoueur"] = $joueurDTN["optionJoueur"];
        $joueurNote["ageJoueur"] = $joueurDTN["ageJoueur"];
        $joueurNote["jourJoueur"] = $joueurDTN["jourJoueur"];
        $joueurNote["idJoueur"] = $joueurDTN["idJoueur"];
        
        $joueurNote["score"] = calculNote($joueurNote);
        $joueurNote["scorePotentiel"] = calculNotePotentiel($joueurNote);
        $maj = majCaracJoueur($joueurNote);
      }

      //*** Si entrainement mis � jour ***//
      if (isset($update_joueur_entrainement)) {
        $update_joueur_entrainement['idJoueur_fk']=$joueurDTN["idJoueur"];
        $update_joueur_entrainement['idJoueur_fk_updated']=updateEntrainement($update_joueur_entrainement);
      }
                   
    //*** Joueur introuvable sur HT ***//  
    }else{
        //$resMAJ['HTML'].='<tr><td colspan=10><br><font color=orange>Joueur introuvable ou connexion ht termin&eacute;e : '.$joueurDTN['idHattrickJoueur'].'</font></td></tr>';
  
        return $resMAJ;
    }

  
    }
    
    //******* UPDATE dans la table HT_JOUEURS *******//
    if (isset($update_joueur)) {
		$resMAJ['idJoueur']=$update_joueur['idJoueur']=$joueurDTN['idJoueur'];
		//******** Date Derniere Modif Joueur ************//
		$update_joueur['dateDerniereModifJoueur']=date('Y-m-d');
		$idJoueur=updateJoueur($update_joueur);
    	if (!$idJoueur) {
			$resMAJ['HTML'].='<font color=orange>Echec de la MAJ du joueur : '.$joueurDTN["idHattrickJoueur"].'</font>';
			$filename = $_SERVER['DOCUMENT_ROOT'].'/log/gg.txt';
			$myfile=fopen($filename,'a+');
			if ($myfile != FALSE) {
				fputs($myfile, "Echec de la MAJ du joueur : ".$joueurDTN["idHattrickJoueur"]."\n");
				fclose($myfile);
            }
			return $resMAJ;  
       } else {
			$resMAJ['joueur_est_maj']=true;
       }
    }
    unset($update_joueur);
  
  
  
    
    //******* INSERT dans la table HT_JOUEURS_HISTO *******//
    // Num�ro de saison et semaine � la date du jour
    $actualSeason=getSeasonWeekOfMatch(mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')));
    $existHJ = existHistoJoueur($joueurDTN["idHattrickJoueur"],$actualSeason["season"],$actualSeason["week"]);

    if ($existHJ==false) {
		$resMAJ['joueurs_histo']=$update_joueurs_histo=getDataJoueurHisto($joueurHT,$actualSeason);
		$resMAJ['joueurs_histo']['id_joueur_histo'] = insertJoueurHisto($update_joueurs_histo);
    } else {
      //echo("<br />nom=".$joueurDTN["nomJoueur"]."|old=".$existHJ[0]['transferListed']."|new=".$joueurHT['transferListed']);
      //Sinon, on remet � jour uniquement si le joueur est en vente
      if (($existHJ['transferListed']==0) && ($joueurHT['transferListed']==1)) {
          $resMAJ["transferListed"]=$update_joueurs_histo["transferListed"]=$joueurHT["transferListed"];
          $update_joueurs_histo['id_joueur_histo']=$existHJ['id_joueur_histo'];
          $resMAJ['id_joueur_histo'] = updateJoueurHisto($update_joueurs_histo);
      }
    }
    unset($update_joueurs_histo);
    unset($existHJ);
    
    if ($role_user=="P" || $role_user=="S") {
      $DebutHistoModifMsg="Joueur mis &agrave; par le $lib_role";
      if ($histoModifMsg!=null) {
        $histoModifMsg = $DebutHistoModifMsg." / ".$histoModifMsg;
      } else {
        $histoModifMsg = $DebutHistoModifMsg." / Pas de up ou down visible";
      }
    }

    //******* INSERT dans la table HT_HISTOMODIF si il y a une modification *******//
    if ($histoModifMsg!=null){
		$histoModifMsg="[".$ht_user."](hattrick) ".$histoModifMsg;
		$_POST["idHisto"]="";    
		$_POST["idAdmin_fk"] ="";
		$_POST["idProgression_fk"]="";
		$_POST["idPerf_fk"]="";    
		$_POST["idJoueur_fk"] = $joueurDTN["idJoueur"];
		$_POST["dateHisto"] = date("Y-m-d");
		$_POST["heureHisto"] = date("H:i:s");
		$_POST["intituleHisto"]  = $histoModifMsg;
		$sql = insertDB("ht_histomodif");
		unset($_POST);
    }

	$resMAJ['idJoueur']=$joueurDTN['idJoueur'];
	$resMAJ['idHattrickJoueur']=$joueurDTN['idHattrickJoueur'];
	if (isset($joueurHT['teamid'])) {$resMAJ['teamid']=$joueurHT['teamid'];} else {$resMAJ['teamid']=$joueurDTN['teamid'];}
	return ($resMAJ);
        
}



/******************************************************************************/
/* Objet : R�cup�ration des donn�es joueur historique                         */
/* Modifi� le 24/06/2010 par Musta56 - Cr�ation fonction                      */
/* Modifi� le 10/12/2011 par Musta56 - Suppression param maBase               */
/* Modifi� le 12/10/2012 par Musta56 - ajout unset + isAbroad                 */
/******************************************************************************/
/* Entr�e : $clubHT = Donn�es club provenant de HT                            */
/* Entr�e : $joueurHT = Donn�es joueur provenant de HT                        */
/* Entr�e : $actualSeason (facultatif) = Num�ro de saison et semaine actuelle */
/* Sortie : $data_joueurs_histo = tableau de donn�es Joueur Historique        */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/*           - ./dtn/interface/includes/serviceJoueur.php                     */
/******************************************************************************/
function getDataJoueurHisto($joueurHT,$actualSeason=null) {
	global $conn;

	require($_SERVER['DOCUMENT_ROOT'].'/dtn/interface/includes/nomTables.inc.php');
	$coef=1;
	if (isset($joueurHT['teamid'])) {
		// Coefficient � utiliser pour le calcul du salaire hors charges
		$sql="SELECT 
              P.coefSalary as coefSalary
          FROM 
              $tbl_pays   P,
              $tbl_clubs  C
          WHERE 
              C.idClubHT=".$joueurHT['teamid']."
          AND C.idPays_fk = P.idPays
          LIMIT 1";

		$result= $conn->query($sql);
		$tabS = $result->fetch();
		$coef=$tabS["coefSalary"];
		$result=NULL;
		unset($sql);
		unset($tabS);
	} else { // teamid introuvable car le joueur n'a plus de club
		if ($joueurHT['isAbroad']) { // joue a l'etranger
			$coef=1.2;
		} else { // Joue en france
			$coef=1;
		}
	}
  
	if ($actualSeason==null) {
		$actualSeason=getSeasonWeekOfMatch(mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')));
	}
  
	$data_joueurs_histo["id_joueur_fk"]=$joueurHT["idHattrickJoueur"];
	$data_joueurs_histo["season"]=$actualSeason["season"];
	$data_joueurs_histo["week"]=$actualSeason["week"];
	$data_joueurs_histo["date_histo"]=date('Y-m-d H:i:s');
	$data_joueurs_histo["forme"]=$joueurHT["forme"];
	$data_joueurs_histo["tsi"]=$joueurHT["tsi"];
	$data_joueurs_histo["xp"]=$joueurHT["idExperience_fk"];
	$data_joueurs_histo["salaire"]=round((($joueurHT["salary"]/10)/$coef),2);
	if (isset($joueurHT["blessure"])) {$data_joueurs_histo["blessure"]=$joueurHT["blessure"];}
	$data_joueurs_histo["transferListed"]=$joueurHT["transferListed"];

	unset($actualSeason);
	unset($joueurHT);
	unset($coef);
  
	return $data_joueurs_histo;
}

/******************************************************************************/
/* Objet : Update d'un historique joueur dans la base de donn�es dtn          */
/* Modifi� le 17/06/2010 par Musta56 - Cr�ation fonction                      */
/******************************************************************************/
/* Entr�e : $row_joueurs_histo=tableau historique joueur (uniquement les      */
/*                            donn�es � mettre � jour)                        */
/* Sortie : $row_joueurs_histo["id_joueur_histo"]=id joueur histo dans bdd dtn*/
/*          ou false = si �chec de l'update                                   */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/*           - ./dtn/interface/includes/serviceJoueur.php                     */
/******************************************************************************/
function updateJoueurHisto($row_joueurs_histo) {
	global $conn;

  require($_SERVER['DOCUMENT_ROOT'].'/dtn/interface/includes/nomTables.inc.php');

  $sql="UPDATE $tbl_joueurs_histo SET ";
  if (isset($row_joueurs_histo['id_joueur_fk']))   {$sql.="id_joueur_fk = '".$row_joueurs_histo['id_joueur_fk']."',";}
  if (isset($row_joueurs_histo['season']))         {$sql.="season = '".$row_joueurs_histo['season']."',";}
  if (isset($row_joueurs_histo['week']))           {$sql.="week = '".$row_joueurs_histo['week']."',";}
  if (isset($row_joueurs_histo['date_histo']))     {$sql.="date_histo = '".$row_joueurs_histo['date_histo']."',";}
  if (isset($row_joueurs_histo['forme']))          {$sql.="forme = '".$row_joueurs_histo['forme']."',";}
  if (isset($row_joueurs_histo['tsi']))            {$sql.="tsi = '".$row_joueurs_histo['tsi']."',";}
  if (isset($row_joueurs_histo['xp']))             {$sql.="xp = '".$row_joueurs_histo['xp']."',";}
  if (isset($row_joueurs_histo['salaire']))        {$sql.="salaire = '".$row_joueurs_histo['salaire']."',";}
  if (isset($row_joueurs_histo['blessure']))       {$sql.="blessure = '".$row_joueurs_histo['blessure']."',";}
  if (isset($row_joueurs_histo["transferListed"])) {$sql.="transferListed = '".$row_joueurs_histo["transferListed"]."',";}
  $sql=substr($sql,0,strlen($sql)-1);
  $sql.=" WHERE
            id_joueur_histo  = ".$row_joueurs_histo["id_joueur_histo"]
        ;
  $reqValid= $conn->exec($sql);

  if (!$reqValid) {
    return false;
  } else {
      return $row_joueurs_histo["id_joueur_histo"];
  }


}


/******************************************************************************/
/* Objet : Insertion d'un historique joueur dans la base de donn�es dtn       */
/* Modifi� le 24/06/2010 par Musta56 - Cr�ation fonction                      */
/******************************************************************************/
/* Entr�e : $row_joueurs_histo=tableau historique joueur                      */
/* Sortie : $row_joueurs_histo["id_joueur_histo"]=id joueur histo dans bdd dtn*/
/*          ou false = si �chec de l'insert                                   */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/*           - ./dtn/interface/includes/serviceJoueur.php                     */
/******************************************************************************/
function insertJoueurHisto($row_joueurs_histo){
	global $conn;

  require($_SERVER['DOCUMENT_ROOT'].'/dtn/interface/includes/nomTables.inc.php');

  if (!isset($row_joueurs_histo["date_histo"])) {$row_joueurs_histo["date_histo"]=date('Y-m-d H:i:s');}
  if (!isset($row_joueurs_histo["forme"]))      {$row_joueurs_histo["forme"]='NULL';}
  if (!isset($row_joueurs_histo["tsi"]))        {$row_joueurs_histo["tsi"]='NULL';}
  if (!isset($row_joueurs_histo["xp"]))         {$row_joueurs_histo["xp"]='NULL';}
  if (!isset($row_joueurs_histo["salaire"]))    {$row_joueurs_histo["salaire"]='NULL';}
  if (!isset($row_joueurs_histo["blessure"]))   {$row_joueurs_histo["blessure"]='NULL';}


  $sql="INSERT INTO $tbl_joueurs_histo (
              id_joueur_fk,
              season,
              week,
              date_histo,
              forme,
              tsi,
              xp,
              salaire,
              blessure,
              transferListed)
        VALUES (".
              $row_joueurs_histo["id_joueur_fk"].",".
              $row_joueurs_histo["season"].",".
              $row_joueurs_histo["week"].",".
              "'".$row_joueurs_histo["date_histo"]."',".
              $row_joueurs_histo["forme"].",".
              $row_joueurs_histo["tsi"].",".
              $row_joueurs_histo["xp"].",".
              $row_joueurs_histo["salaire"].",".
              $row_joueurs_histo["blessure"].",".
              $row_joueurs_histo["transferListed"]."
        )";

 $reqValid= $conn->exec($sql);
  
  if (!$reqValid) {
    return -1;
  } else {
    return $conn->lastInsertId();
  }

}


/******************************************************************************/
/* Objet : Existence d'un historique joueur dans la base de donn�es dtn       */
/* Modifi� le ??/??/???? par Musta56 - Cr�ation fonction                      */
/* Modifi� le 10/12/2011 par Musta56 - Suppresion param maBase                */
/******************************************************************************/
/* Entr�e : $idHTjoueur = identifiant hattrick du joueur                      */
/* Entr�e : $season = Num�ro de saison                                        */
/* Entr�e : $week = Num�ro de semaine                                         */
/* Sortie : $result = r�sultat de la requete ou false si aucun r�sultat       */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/*           - ./includes/serviceJoueur.php                                   */
/******************************************************************************/
function existHistoJoueur($idHTjoueur,$season,$week){
	global $conn;

	require($_SERVER['DOCUMENT_ROOT'].'/dtn/interface/includes/nomTables.inc.php');
	  
	$sql = 'SELECT * 
          FROM  '.$tbl_joueurs_histo.' 
          WHERE id_joueur_fk='.$idHTjoueur.' 
          AND   season='.$season.' 
          AND   week='.$week.' 
          LIMIT 1';
	
	$result= $conn->query($sql);
	$tabS = $result->fetch(PDO::FETCH_ASSOC);
	$result = NULL;
  
	return $tabS;
}



/******************************************************************************/
/* Objet : Marquer un joueur comme disparu de HT                              */
/* Modifi� le 09/03/2011 par Musta56 - Cr�ation fonction                      */
/* Modifi� le 12/11/2012 par Musta56 - Ajout d'une ligne dans histo_modif     */
/******************************************************************************/
/* Entr�e : $joueurDTN = tableau joueur (donn�es provenant de DTN)            */
/* Sortie : $posteAssigne = Poste d'assignation du joueur                     */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/* - dtn/interface/includes/serviceJoueurs.php (getDataUnJoueurFromHT_usingPHT*/
/******************************************************************************/
function marqueJoueurDisparuHT($joueurDTN){
	global $conn;
  // Joueur n'existe plus
  $sql="UPDATE ht_joueurs SET joueurActif=0 WHERE idJoueur='".$joueurDTN["idJoueur"]."' LIMIT 1";
  $reqValid= $conn->exec($sql);
  unset($sql);

  if (!$reqValid) {
    return false;
  } else {
    unset($reqValid);

    $sqlhisto = "INSERT INTO ht_histomodif (  
                        idJoueur_fk,
                        dateHisto, 
                        heureHisto, 
                        intituleHisto) 
                  VALUES (
                        '".$joueurDTN["idJoueur"]."',
                        '".date("Y-m-d")."',
                        '".date("H:i:s")."',
                        '[MAJ auto](hattrick) Joueur introuvable sur HT')";
      $reqValid= $conn->exec($sqlhisto);
      unset($sqlhisto);
      unset($reqValid);
  
      return $joueurDTN["idJoueur"];
  }
  
}



/******************************************************************************/
/* Objet : Scan un ensemble de joueurs - insertion ou maj des joueurs fran�ais*/
/* Modifi� le 18/03/2010 par Musta56 - R�cup�ration % endu, intensit�, ... etc*/
/* Modifi� le 15/03/2011 par Musta56 - D�plac� dans serviceJoueurs            */
/* Modifi� le 05/05/2011 par Musta56 - Ajout param�tre faire MAJ              */
/* Modifi� le 10/12/2011 par Musta56 - Script g�n�rique car devient point     */
/*         entr�e unique pour maj joueur - Suppression param�tre $maBase      */
/******************************************************************************/
/* Entr�e : - $listeIDJoueur = tableau avec liste identifiant joueur Hattrick */
/*          - $utilisateur = user HT connect�                                 */
/*          - $role = role du user connect� : proprietaire(P), DTN (D)        */
/*          - $faireMAJ = booleen indiquant si une maj des joueurs doit �tre  */
/*                        faite dans la base de donn�es DTN                   */
/* Sortie : - $resuJ = tableau des joueurs ins�r�s ou maj                     */
/******************************************************************************/
/* Appel� par les scripts :                                                   */
/*           - ./natianal_team/coach_dtn_submitting.php                       */
/*           - ./dtn_scan_team.php                                            */
/*           - ./dtn/interface/maliste/miseajour.php                          */
/******************************************************************************/
function scanListeJoueurs($listeIDJoueur,$utilisateur,$role,$faireMAJ=true,$chargeMatch=false)
{
	$todaySeason=getSeasonWeekOfMatch(mktime(0,0,0,date('m'), date('d'),date('Y')));
	if (isset($resuJ)) {unset($resuJ);}
	$j=0;

	// Recherche du joueur sur HT et dans la base DTN
	foreach ($listeIDJoueur as $IDJoueur) {
		$trouveDTN=true;
		$trouveHT=true;
		$joueurDTN=getJoueurHt($IDJoueur);

		if ($faireMAJ==false) {

			if ($joueurDTN != false) { // joueur existe dans base DTN
				$resuJ[$j]['poste']=validateMinimaPlayer($joueurDTN,$todaySeason); // Est-ce que le joueur v�rifie les minimas ?
				$resuJ[$j]['idJoueur']=$joueurDTN['idJoueur'];
			}
      
		} elseif ($faireMAJ==true) {
    
			$joueurHT=getDataUnJoueurFromHT_usingPHT($IDJoueur);
//echo($joueurHT["idHattrickJoueur"]);            
			if ($joueurHT != false) {
				if ($joueurHT['caracVisible']==true) {
					$poste=validateMinimaPlayer($joueurHT,$todaySeason); // Est-ce que le joueur v�rifie les minimas ?
				} else {
					$poste=-2; // Tous les joueurs sont accept�s si on ne connait pas les caracs. Ne devrait arriver que lors d'une soumission par un dtn
				}

				if ($joueurDTN != false) { // joueur existe dans base DTN
					$resuJ[$j]=majJoueur($utilisateur,$role,$joueurHT,$joueurDTN); // Joueur mis � jour si d�j� existant en base. Sinon, on ne fait rien. 

				} else { /* Joueur inexistant dans base DTN */
					$trouveDTN=false;
        
					if ($poste == -2){ // multi ou incoh�rence d�tect�e => aucun poste assign�
						$resuJ[$j]=ajoutJoueur($utilisateur,$role,$joueurHT,$joueurDTN,0);
					} elseif ($poste != -1) {
						$resuJ[$j]=ajoutJoueur($utilisateur,$role,$joueurHT,$joueurDTN,$poste);
					}
				}
        
				$resuJ[$j]['poste']=$poste;
      
			} else { // Joueur inexistant sur HT ou connexion plant�e
				$trouveHT=false;
				$resuJ[$j]['HTML']='<tr><td colspan=10><br /><font color=orange>Joueur introuvable ou connexion ht termin&eacute;e : '.$IDJoueur.'</font></td></tr>';
			}

		} else {
			echo("Valeur param�tre faire MAJ incorrecte");
			exit;
		}


    // Chargement des matchs
    if ($chargeMatch==true && $joueurDTN != false) {
      $resuM[$j]=insererMatchsJoueur($joueurDTN["idHattrickJoueur"],$joueurDTN["teamid"],$joueurDTN["dateLastScanMatch"]);
      updateDateScanMatchJoueur($joueurDTN["idHattrickJoueur"]);
    } elseif ($joueurDTN = false) {
      $resuJ[$j]['HTML']='<tr><td colspan=10><br /><font color=orange>Impossible de charger les matchs car joueur absent bdd : '.$IDJoueur.'</font></td></tr>';
    }
    
    $resuJ[$j]['trouveDTN']=$trouveDTN;
    $resuJ[$j]['trouveHT']=$trouveHT;
    $resuJ[$j]['idHattrickJoueur']=$IDJoueur;
    
    if (isset($resuJ[$j]['idJoueur']) && $resuJ[$j]['idJoueur'] > 0 && $faireMAJ==true && $resuJ[$j]['trouveHT']==true) { // Il y a eu maj ou ajout du joueur
      $liste_clubs[$joueurHT['teamid']]=$joueurHT['teamid']; // Liste des clubs pour appel maj Clubs
    }
    $j++;

  } // Fin Boucle sur liste ID joueurs

	// maj Club
	if ($faireMAJ==true) {
		$liste_clubs=array_unique($liste_clubs); // Suppression des doublons

		foreach ($liste_clubs as $club) {
			$resuC[$club]['club']=majClub($club);
			$resuC[$club]['clubHisto']=majClubHisto($club,$utilisateur,$role);
		}

		// Concat�nation de l'affichage HTML & r�cup�ration des identifiants maj Club et histo club
		for ($j=0;$j<count($resuJ);$j++) {
			if (isset($resuJ[$j]['idJoueur']) && $resuJ[$j]['idJoueur'] > 0) { // Il y a eu maj ou ajout du joueur
				if (isset($resuC[$resuJ[$j]['teamid']]['club']['idClub'])) $resuJ[$j]['idClub']=$resuC[$resuJ[$j]['teamid']]['club']['idClub'];
				if (isset($resuC[$resuJ[$j]['teamid']]['clubHisto']['id_clubs_histo'])) $resuJ[$j]['id_clubs_histo']=$resuC[$resuJ[$j]['teamid']]['clubHisto']['id_clubs_histo'];
				if (!isset($resuJ[$j]['HTML'])) $resuJ[$j]['HTML']="";
				if (isset($resuC[$resuJ[$j]['teamid']]['club']['HTML'])) $resuJ[$j]['HTML'] .= $resuC[$resuJ[$j]['teamid']]['club']['HTML']; // Club
				if (isset($resuC[$resuJ[$j]['teamid']]['clubHisto']['HTML'])) $resuJ[$j]['HTML'] .= $resuC[$resuJ[$j]['teamid']]['clubHisto']['HTML']; // Club
				$resuJ[$j]['HTML'] .= "</td>";
				if (isset($resuM[$j]['HTML'])) $resuJ[$j]['HTML'] .= $resuM[$j]['HTML']; // Matchs
			}
		}
	}
  
	return $resuJ;	 					
}

?>
