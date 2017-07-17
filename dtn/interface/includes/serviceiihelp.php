<?php



/******************************************************************************/
/* Objet : Requête SQL pour récupérer les infos d'un repreneur iiihelp        */
/* Modifié le 06/05/2011 par Musta56 - Création fonction                      */
/******************************************************************************/
/* Entrée : $idClubHT = Identifiant du club sur HT                            */
/* Sortie : $sql - Requête SQL                                                */
/******************************************************************************/
/* Appelé par les scripts :                                                   */
/*           - fff_help.php                                                   */
/*           - dtn/interface/includes/serviceiihelp.php                       */
/******************************************************************************/
function get_iiihelp_repreneurSQL($idClubHT)
{
  require($_SERVER['DOCUMENT_ROOT'].'/dtn/interface/includes/nomTables.inc.php');
  
  $sql="SELECT 
              id_iiihelp_repreneur,
              idClubHT,
              leagueLevel,
              email,
              commentaire,
              etat,	 
              entrainement_voulu1,	 
              age_voulu1,
              entrainement_voulu2,	 
              age_voulu2
        FROM $tbl_iiihelp_repreneur
        WHERE idClubHT=".$idClubHT;
        
  return $sql;
}

/******************************************************************************/
/* Objet : Requête SQL pour récupérer les infos d'un repreneur iiihelp + club */
/* Modifié le 07/05/2011 par Musta56 - Création fonction                      */
/******************************************************************************/
/* Entrée : Aucun                                                             */
/* Sortie : $sql - Requête SQL                                                */
/******************************************************************************/
/* Appelé par les scripts :                                                   */
/*           - dtn/interface/admin/liste_repreneur_iiihelp.php                */
/******************************************************************************/
function get_iiihelp_repreneur_clubs_SQL()
{
  require($_SERVER['DOCUMENT_ROOT'].'/dtn/interface/includes/nomTables.inc.php');
  
  $sql="SELECT 
              R.id_iiihelp_repreneur,
              R.idClubHT,
              R.leagueLevel,
              R.email,
              R.commentaire,
              R.etat,	 
              R.entrainement_voulu1,	 
              R.age_voulu1,
              R.entrainement_voulu2,	 
              R.age_voulu2,
              C.idUserHT,
              C.nomClub,
              C.nomUser,
              C.niv_Entraineur,
              C.idPays_fk
        FROM  $tbl_iiihelp_repreneur R,
              $tbl_clubs C
        WHERE R.idClubHT=C.idClubHT ";

  return  $sql;
}


/******************************************************************************/
/* Objet : Requête SQL pour récupérer les infos d'un repreneur iiihelp + club */
/* Modifié le 07/05/2011 par Musta56 - Création fonction                      */
/******************************************************************************/
/* Entrée : $idClubHT = Identifiant du club sur HT                            */
/* Sortie : $reqValid - booleen. True si ok, False si Echec                   */
/******************************************************************************/
/* Appelé par les scripts :                                                   */
/*           - dtn/interface/admin/liste_repreneur_iiihelp.php                */
/******************************************************************************/
function del_iiihelp_repreneur($idClubHT)
{
	global $conn;
	require($_SERVER['DOCUMENT_ROOT'].'/dtn/interface/includes/nomTables.inc.php');
  
	$sql = " DELETE FROM $tbl_iiihelp_repreneur WHERE idClubHT = $idClubHT";
	$reqValid = $conn->exec($sql);
  
	return $reqValid;
}


/******************************************************************************/
/* Objet : Insertion Club dans la bdd                                         */
/* Modifié le 06/05/2011 par Musta56 - Création fonction                      */
/******************************************************************************/
/* Entrée : $row_iiihelp_repreneur = tableau avec info repreneur iiihelp      */
/* Sortie : False si échec,                                                   */
/*          id_iiihelp_repreneur de la table ht_iiihelp_repreneur si ok       */
/******************************************************************************/
/* Appelé par les scripts :                                                   */
/*           - fff_help.php                                                   */
/******************************************************************************/
function insertionRepreneuriiiHelp($row_iiihelp_repreneur){
	global $conn;
	require($_SERVER['DOCUMENT_ROOT'].'/dtn/interface/includes/nomTables.inc.php');

	if (!isset($row_iiihelp_repreneur["entrainement_voulu2"]) || (empty($row_iiihelp_repreneur["entrainement_voulu2"])) ) {$row_iiihelp_repreneur["entrainement_voulu2"]=0;}
	if (!isset($row_iiihelp_repreneur["age_voulu2"]) || (empty($row_iiihelp_repreneur["age_voulu2"])) ) {$row_iiihelp_repreneur["age_voulu2"]='';}

	$sql = get_iiihelp_repreneurSQL($row_iiihelp_repreneur['idClubHT']);
	$req = $conn->query($sql);

	if(!$req){
		return false;
	} elseif ($req->rowCount() == 0) { /* le club n'existe pas dans la base => on l'insère*/
		if (!isset($row_iiihelp_repreneur["entrainement_voulu2"]))  {$row_iiihelp_repreneur["entrainement_voulu2"]=0;}
		if (!isset($row_iiihelp_repreneur["age_voulu2"]))           {$row_iiihelp_repreneur["age_voulu2"]='';}
      
		$sql = "INSERT INTO $tbl_iiihelp_repreneur 
                ( idClubHT,
                  leagueLevel,
                  email,
                  commentaire,
                  etat,	 
                  entrainement_voulu1,	 
                  age_voulu1,
                  entrainement_voulu2,	 
                  age_voulu2) 
              VALUES 
                ( ".$row_iiihelp_repreneur['idClubHT'].",
                  ".$row_iiihelp_repreneur['leagueLevel'].",
                  '".$row_iiihelp_repreneur['email']."',
                  '".$row_iiihelp_repreneur['commentaire']."',".
                  $row_iiihelp_repreneur['etat'].",".
                  $row_iiihelp_repreneur['entrainement_voulu1'].",
                  '".$row_iiihelp_repreneur['age_voulu1']."',".
                  $row_iiihelp_repreneur['entrainement_voulu2'].",
                  '".$row_iiihelp_repreneur['age_voulu2']."')";
                 
		$reqValid= $conn->exec($sql);
      
		if (!$reqValid) {
			return false;
		} else {
			return $conn->lastInsertId();
		}

	} elseif($req->rowCount() == 1){ /* le repreneur existe dans la base => on le met à jour */
        $tab = $req->fetch();
        $row_iiihelp_repreneur['id_iiihelp_repreneur'] = $tab['id_iiihelp_repreneur'];
        return updateRepreneuriiiHelp($row_iiihelp_repreneur);
	} else {
        return false;
	}

}


/******************************************************************************/
/* Objet : Mise à jour Club dans la bdd                                       */
/* Modifié le 06/05/2011 par Musta56 - Création fonction                      */
/******************************************************************************/
/* Entrée : $row_iiihelp_repreneur = tableau avec info repreneur iiihelp      */
/* Sortie : False si échec,                                                   */
/*          id_iiihelp_repreneur de la table ht_iiihelp_repreneur si ok       */
/******************************************************************************/
/* Appelé par les scripts :                                                   */
/*           - dtn/interface/includes/serviceiihelp.php                       */
/******************************************************************************/
function updateRepreneuriiiHelp($row_iiihelp_repreneur){
	global $conn;
	require($_SERVER['DOCUMENT_ROOT'].'/dtn/interface/includes/nomTables.inc.php');
   
	$sql="UPDATE $tbl_iiihelp_repreneur SET ";
	if (isset($row_iiihelp_repreneur["leagueLevel"]))          {$sql.="leagueLevel = ".$row_iiihelp_repreneur["leagueLevel"].",";}
	if (isset($row_iiihelp_repreneur["email"]))                {$sql.="email = '".$row_iiihelp_repreneur["email"]."',";}
	if (isset($row_iiihelp_repreneur["commentaire"]))          {$sql.="commentaire = '".$row_iiihelp_repreneur["commentaire"]."',";}
	if (isset($row_iiihelp_repreneur["etat"]))                 {$sql.="etat = ".$row_iiihelp_repreneur["etat"].",";}
	if (isset($row_iiihelp_repreneur["entrainement_voulu1"]))  {$sql.="entrainement_voulu1 = ".$row_iiihelp_repreneur["entrainement_voulu1"].",";}
	if (isset($row_iiihelp_repreneur["age_voulu1"]))           {$sql.="age_voulu1 = '".$row_iiihelp_repreneur["age_voulu1"]."',";}
	if (isset($row_iiihelp_repreneur["entrainement_voulu2"]))  {$sql.="entrainement_voulu2 = ".$row_iiihelp_repreneur["entrainement_voulu2"].",";}
	if (isset($row_iiihelp_repreneur["age_voulu2"]))           {$sql.="age_voulu2 = '".$row_iiihelp_repreneur["age_voulu2"]."',";}


	$sql=substr($sql,0,strlen($sql)-1);

	if (isset($row_iiihelp_repreneur["id_iiihelp_repreneur"])) {
		$sql.=" WHERE id_iiihelp_repreneur  = ".$row_iiihelp_repreneur["id_iiihelp_repreneur"];
	} elseif (isset($row_iiihelp_repreneur["idClubHT"])) {
		$sql.=" WHERE idClubHT = ".$row_iiihelp_repreneur["idClubHT"];
	} else return false;
  
	$reqValid = $conn->exec($sql);

	if (!$reqValid) {
		return false;
	} else {
		return $row_iiihelp_repreneur["id_iiihelp_repreneur"];
	}

}


/******************************************************************************/
/* Objet : Décodage des tranches d'age du formulaire d'inscription iiihelp    */
/* Modifié le ??/??/???? par ??? - Création fonction                          */
/* Modifié le 06/05/2011 par Musta56 - Ajout Implode dans switch              */
/******************************************************************************/
/* Entrée : $age_Formulaire = tableau avec age saisi dans formiulaire         */
/* Sortie : $lib_Age : Libellé age                                            */
/******************************************************************************/
/* Appelé par les scripts :                                                   */
/*           - fff_help.php                                                   */
/******************************************************************************/
function FormulaireLibelleAge($age_Formulaire)
{
  switch(implode("",$age_Formulaire))
  {
  	case "1" :
  		$lib_Age = "17-20 ans";
  	break;
  	case "2" :
  		$lib_Age = "+21 ans";
  	break;
  	case "12" :
  		$lib_Age = "Tous";
  	break;
  }
  return $lib_Age;
}


/******************************************************************************/
/* Objet : Décodage des tranches d'age du formulaire d'inscription iiihelp    */
/* Modifié le 06/05/2011 par Musta56 - Création fonction                      */
/******************************************************************************/
/* Entrée : $age_voulu = Libelle age                                          */
/* Sortie : $AgeFormulaire : Tableau Age                                      */
/******************************************************************************/
/* Appelé par les scripts :                                                   */
/*           - fff_help.php                                                   */
/******************************************************************************/
function libelleAgeFormulaire($age_voulu)
{
  switch($age_voulu)
  {
  	case "17-20 ans" :
	default:
  		$AgeFormulaire[] = "1";
  	break;
  	case "+21 ans" :
  		$AgeFormulaire[] = "1";
  	break;
  	case "Tous" :
  		$AgeFormulaire[] = "1";
  		$AgeFormulaire[] = "2";
  	break;
  }
  return $AgeFormulaire;
}

?>
