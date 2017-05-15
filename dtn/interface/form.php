<?php
ini_set('display_errors','1');
require("includes/head.inc.php");
require("includes/nomTables.inc.php");
require("includes/serviceJoueur.php");
require("includes/serviceEquipes.php");
require("includes/serviceEntrainement.php");
require("includes/serviceDTN.php");
require("includes/serviceMatchs.php");
require_once("fonctions/AccesBase.php");
require_once("fonctions/HT_Client.php");
require_once("CHPP/config.php");
require_once("_config/CstGlobals.php"); 

switch($mode){
/*
case "loginAdmin":

$sql = "SELECT 
              $tbl_admin.*,
              $tbl_niveauAcces.*,
              $tbl_clubs.idClubHT,
              $tbl_clubs.nomClub,
              $tbl_clubs.nomUser
        FROM  $tbl_admin, 
              $tbl_niveauAcces,
              $tbl_clubs 
        WHERE $tbl_admin.loginAdmin = \"$login\" 
        AND   $tbl_admin.passAdmin = \"$password\" 
        AND   $tbl_admin.idNiveauAcces_fk = $tbl_niveauAcces.idNiveauAcces 
        AND   $tbl_admin.idAdminHT = $tbl_clubs.idUserHT";

$req  = mysql_query($sql) or die(mysql_error()."\n".$sql);
$_SESSION['sesUser']=$sesUser;

$sesUser = mysql_fetch_array($req);

$sql = " SELECT
		truncate((UNIX_TIMESTAMP(sysdate())-UNIX_TIMESTAMP('1997-05-31'))/86400/112,0) as saison,
		UNIX_TIMESTAMP('1997-05-31') as date0
		FROM dual";
$req  = mysql_query($sql) or die(mysql_error()."\n".$sql);
$res = mysql_fetch_array($req);

$sesUser["saison"] = $res["saison"];
$sesUser["dateSemaine0"] = $res["date0"]+112*86400*$sesUser["saison"];


if(count($sesUser) > 0){

$sql = "UPDATE $tbl_admin SET 
          dateAvantDerniereConnexion = dateDerniereConnexion, 
          heureAvantDerniereConnexion = heureDerniereConnexion
        WHERE idAdmin = ".$sesUser["idAdmin"]."";
$req  = mysql_query($sql) or die(mysql_error()."\n".$sql);

$sql = "UPDATE $tbl_admin SET 
          dateDerniereConnexion = '".date("Y-m-d")."',
          heureDerniereConnexion = '".date("H:i")."'
        WHERE idAdmin = ".$sesUser["idAdmin"]."";
$req  = mysql_query($sql) or die(mysql_error()."\n".$sql);

$_SESSION["sesUser"] = $sesUser;
$_SESSION['acces']="INTERFACE"; // sert à avoir un affichage personnalisé pour les composants utilisés dans le portail et l'interface

header("location: index2.php");
}
else
header("location: index.php?ErrorMsg=Erreur lors de l'identification");

break;
*/


case "supprAdmin";

// Suppression des asssignations des joueurs qu'il suit




        $sql2 = mysql_query("UPDATE ht_joueurs SET dtnSuiviJoueur_fk = '0' WHERE dtnSuiviJoueur_fk = '".$idAdmin."'") or die("Erreur Req 2 : $sql2");
        $sql2 = mysql_query("UPDATE ht_admin SET affAdmin = '0' WHERE idAdmin = '".$idAdmin."'") or die("Erreur Req 2 : $sql2");


header("location: equipe/$from.php?msg=Administrateur bien supprime");

break;

case "reactivAdmin":

$sql = mysql_query("UPDATE ht_admin SET affAdmin=1 WHERE idAdmin=".$id."");
header("location: equipe/$from.php?msg=Administrateur bien reactive");
break;

case "modifAdmin";


$sql = mysql_query("
UPDATE ht_admin SET   loginAdmin = '".$loginAdmin."', passAdmin  ='".$passAdmin."',idAdminHT= '".$idAdminHT."' ,
                                         emailAdmin  = '".$emailAdmin."' ,idNiveauAcces_fk = '".$idNiveauAcces_fk."'  ,  idPosition_fk  = '".$idPosition_fk."' WHERE idAdmin = '".$idAdmin."'
");


?>
<script language="JavaScript">
window.opener.location = "equipe/<?=$from?>.php?msg=L administrateur a bien ete modifie";
window.close();
</script>
<?php
break;

case "ajoutAdmin":
//Verification des redondances

$sql = "select * from $tbl_admin where loginAdmin = \"$loginAdmin\" or emailAdmin = \"$emailAdmin\" ";
$req = mysql_query($sql);


if(mysql_num_rows($req) > 0){
header("location: equipe/$from.php?msg=!! Erreur. Cette administrateur existe deja !!");

}
else{

$sql = "INSERT INTO ht_admin (loginAdmin, passAdmin, idAdminHT, emailAdmin, idNiveauAcces_fk , idPosition_fk, affAdmin)";
$sql .= "VALUES                          ('$loginAdmin', '$passAdmin', '$idAdminHT', '$emailAdmin', $idNiveauAcces_fk, '$idPosition_fk', 1)";
$req = mysql_query($sql);

header("location: equipe/$from.php?msg=Administrateur bien ajoute a la liste");
}
break;

case "ajoutClub":

$sql = "select * from $tbl_clubs where idClubHT = \"$idClubHT\"";
$req = mysql_query($sql);


if(mysql_num_rows($req) > 0){

header("location: clubs/index.php?msg=!! Erreur. Ce club existe deja !!");

}
else{

$_POST["nomClub"] = strtolower($_POST["nomClub"]);

//$sql = insertDB($tbl_clubs);

$sql = "INSERT INTO ht_clubs (  idClubHT,  nomClub , idPays_fk ) VALUES ('".$idClubHT."','".strtolower($nomClub)."','".$idPays_fk."')";
$req  = mysql_query($sql);
header("location: clubs/index.php?msg=Club bien ajoute a la liste");
}
break;

case "supprClub";

$sql = "DELETE FROM  ht_clubs WHERE idClubHT = '".$id."'";
$req = mysql_query($sql);

$sql = "DELETE FROM  ht_clubs_histo WHERE idClubHT_fk = '".$id."'";
$req = mysql_query($sql);

header("location: clubs/index.php?msg=Club bien supprime");
break;


case "modifClub";
$_POST["nomClub"] = strtolower($_POST["nomClub"]);

majDB($tbl_clubs,$id);
?>
<script language="JavaScript">
window.opener.location = "clubs/index.php?msg=Modification du club OK";
window.close();
</script>
<?php
break;



/********************************************/
/*AJOUT JOUEUR                              */
/*Appelé dans addPlayer.php                 */
/********************************************/
case "ajoutJoueur":

$player = unserialize(urldecode(stripslashes($_POST['playerToAddManual']))) ;

// On complète le tableau playerToAddManual avec les valeurs sélectionnées
$player["idEndurance"]=$_POST["idEndurance"];
$player["idGardien"]=$_POST["idGardien"];
$player["idConstruction"]=$_POST["idConstruction"];
$player["idButeur"]=$_POST["idButeur"];
$player["idPasse"]=$_POST["idPasse"];
$player["idAilier"]=$_POST["idAilier"];
$player["idDefense"]=$_POST["idDefense"];
$player["idPA"]=$_POST["idPA"];


$joueurDTN=getJoueurHt($player['idHattrickJoueur']);

// Test pour vérifier l'existence du joueur (Normalement déjà fait dans le script addplayer.php donc ne devrait pas arriver)
if(isset($joueurDTN ['idJoueur'])){
  $_SESSION['msgAddPlayer']="Erreur. Ce joueur existe deja !!";
  header("location: joueurs/addPlayer.php?msg=!! Erreur. Ce joueur existe deja !!");
} else {

  // On vérifie si le joueur respecte les minimas
  // Le but est de récupérer le secteur d'affectation
  // Si le joueur ne respecte pas les minimas, on force l'insertion dans la base mais le joueur n'est attribué à aucun secteur
  $todaySeason=getSeasonWeekOfMatch(mktime(0,0,0,date('m'), date('d'),date('Y')));
  $poste=validateMinimaPlayer($player,$todaySeason);
  if ($poste<0) $poste=0;
   
  // Insertion du club s'il n'existe pas
  $idJoueur=ajoutJoueur($sesUser["loginAdmin"],"D",$player,$joueurDTN,$poste);
  
  $_SESSION['listID']=$_POST["listID"];
  $_SESSION['msgAddPlayer']="$idHattrickJoueur => joueur ajoute";
  
  header("location: joueurs/addPlayer.php");
}
break;


// ********************************************************************
// ** Suppression d un joueur par un DTN # **
// ** Joueur -> ht_obsolete_players
// ********************************************************************

case "supprJoueur";

if($sesUser["idNiveauAcces"] == 1 ){
	
$infJoueur = getJoueur($id);

if ($infJoueur["idHattrickJoueur"]!= "" ){

/*$sql = "DELETE FROM  ht_entrainement WHERE idJoueur_fk = '".$infJoueur["idJoueur"]."' ";
$req = mysql_query($sql);

$sql = "DELETE FROM  ht_histomodif WHERE idJoueur_fk = '".$infJoueur["idJoueur"]."'";
$req = mysql_query($sql);

$sql = "DELETE FROM  ht_perfs_individuelle WHERE id_joueur = '".$infJoueur["idHatrickJoueur"]."'";
$req = mysql_query($sql);

$_POST["dateSuppression"] = date('Y-m-d H:i');


$_POST["loginAdmin"] = $sesUser["loginAdmin"];
$_POST["idHattrickJoueur"] = $infJoueur["idHattrickJoueur"];
$_POST["idEndurance"] = $infJoueur["idEndurance"];
$_POST["idGardien"] = $infJoueur["idGardien"];
$_POST["idConstruction"] = $infJoueur["idConstruction"];
$_POST["idPasse"] = $infJoueur["idPasse"];
$_POST["idAilier"] = $infJoueur["idAilier"];
$_POST["idDefense"] = $infJoueur["idDefense"];
$_POST["idButeur"] = $infJoueur["idButeur"];
$_POST["idPA"] = $infJoueur["idPA"];
$_POST["optionJoueur"] = $infJoueur["optionJoueur"];
$_POST["ageJoueur"] = $infJoueur["ageJoueur"];
$_POST["dateDerniereModifJoueur"] = $infJoueur["dateDerniereModifJoueur"];



$sql = insertDB("ht_obsolete_players");

$sql = "DELETE FROM  ht_joueurs WHERE idJoueur = '".$infJoueur["idJoueur"]."'";
$req = mysql_query($sql);
*/
  delJoueur($infJoueur);

	header("location: joueurs/verifPlayer.php?msg=Joueur%20bien%20supprime");
	}else{
		header("location: joueurs/verifPlayer.php?msg=erreur_le_joueur_existe_toujours");
	}
}else{
	header("location: joueurs/fiche.php?id=".$infJoueur['idJoueur']."&msg=operation%20interdite");
}
break;


// ********************************************************************
// ** Suppression d un joueur depuis la page de purge des joueurs **
// ** Le Joueur supp -> ht_obsolete_players
// ********************************************************************
case "supprJoueurDepuisPagePurge";


if(($sesUser["idNiveauAcces"] == 1) ||($sesUser["idNiveauAcces"] == 2) ){
	

$infJoueur = unserialize(urldecode(stripslashes($_POST['infJoueur']))) ;

if ($infJoueur["idHattrickJoueur"]!= "" ){

  delJoueur($infJoueur);
  
	header("location: joueurs/purgeJoueurs.php?msg=Joueur%20bien%20supprime");
	}else{
		header("location: joueurs/purgeJoueurs.php?msg=erreur_le%20joueur%20existe%20toujours");
	}
}else{
	header("location: joueurs/purgeJoueurs.php?msg=Seuls%20les%20DTN%20Plus%20et%20DTN%20diese%20sont%20autorises%20a%20supprimer%20un%20joueur%20botifie");
}
break;





case "modifJoueur";

$_POST["nomJoueur"] = strtolower($_POST["nomJoueur"]);
$_POST["prenomJoueur"] = strtolower($_POST["prenomJoueur"]);



$_POST["dateDerniereModifJoueur"] = date("Y-m-d");


                $_POST["scoreGardien"] = (($_POST["idGardien"] * 9) + ($_POST["idEndurance"] * 1) + $_POST["idExperience_fk"]) / 11;
                $_POST["scoreDefense"] = (($_POST["idDefense"] * 9) + ($_POST["idEndurance"] * 1) + $_POST["idExperience_fk"]) / 11;
                $_POST["scoreAilierOff"] = (($_POST["idAilier"] * 5 + $_POST["idConstruction"] * 1.5 + $_POST["idPasse"] * 1.5) +($_POST["idEndurance"]*2) +$_POST["idExperience_fk"])/11;
                $_POST["scoreMilieu"] = (($_POST["idConstruction"] * 4 + $_POST["idPasse"] * 2 + $_POST["idDefense"] * 1) + ($_POST["idEndurance"] * 3) + $_POST["idExperience_fk"]) / 11;
                $_POST["scoreAttaquant"] = (($_POST["idButeur"] * 7 + $_POST["idPasse"] * 2) + ($_POST["idEndurance"] * 1) + $_POST["idExperience_fk"]) / 11;






$sql = "select * from ht_joueurs where idJoueur = $id";
$req = mysql_query($sql);
$infJ = mysql_fetch_array($req);

$_POST["dateSaisieJoueur"] = $infJ["dateSaisieJoueur"];

$_POST["saisonApparitionJoueur"] = $infJ["saisonApparitionJoueur"];
$_POST["AdminSaisieJoueur_fk"]  = $infJ["AdminSaisieJoueur_fk"];
$_POST["dtnSuiviJoueur_fk"]  = $infJ["dtnSuiviJoueur_fk"];
$_POST["ht_posteAssigne"]= $infJ["ht_posteAssigne"];
$_POST["entrainement_id"]= $infJ["entrainement_id"];


$_POST["scoreGardien"] = round($_POST["scoreGardien"],2);
$_POST["scoreDefense"] = round($_POST["scoreDefense"],2);
$_POST["scoreAilierOff"] = round($_POST["scoreAilierOff"],2);
$_POST["scoreMilieu"] = round($_POST["scoreMilieu"],2);
$_POST["scoreAttaquant"] = round($_POST["scoreAttaquant"],2);

$_POST["joueurActif"] = 1;
$_POST["affJoueur"]= 1;
$_POST["saisonApparitionJoueur"]= 0;
$sql = majDB($tbl_joueurs,$id);

$_POST["idJoueur_fk"] = $id;
$_POST["idAdmin_fk"] = $sesUser["idAdmin"];
$_POST["dateHisto"] = date("Y-m-d");


$_POST["heureHisto"] = date("H:i");
$_POST["intituleHisto"]  = "Modification du joueur";

$sql = insertDB($tbl_histomodif);

?>
<script language="JavaScript">
window.opener.location = "joueurs/checkPlayer.php?msg=Le joueur a bien ete modifie";
window.close();
</script>
<?php
break;
case "assigneJoueur";

for($i=0;$i<count($assigne);$i++){

$sql2 = "select * from $tbl_position where idPosition = $idPosition";
$req2 = mysql_query($sql2);
$res2 = mysql_fetch_array($req2);



$_POST["idJoueur_fk"] = $assigne[$i];
$_POST["idAdmin_fk"] = $sesUser["idAdmin"];
$_POST["dateHisto"] = date("Y-m-d");
$_POST["heureHisto"] = date("H:i");
$_POST["intituleHisto"]  = "Assignation du joueur au poste ".$res2["intitulePosition"];

//$sql3 = insertDB($tbl_histomodif);



$sql = "update $tbl_joueurs set ht_posteAssigne = $idPosition where idJoueur = $assigne[$i]";
$req = mysql_query($sql);


}
header("location: joueurs/liste.php");



break;


case "assigneJoueurSelection";

for($i=0;$i<count($assigne);$i++){
$sql = "INSERT INTO ht_selection ( id_joueur,   selection  )";
$sql .= " VALUES ('".$assigne[$i]."','".$selectionFrance."')";
$req = mysql_query($sql);



$sql2 = "INSERT INTO ht_histomodif (idJoueur_fk , idAdmin_fk , dateHisto  ,heureHisto  ,intituleHisto )";
$sql2 .= " VALUES ('".$assigne[$i]."','". $sesUser["idAdmin"]."','".date("Y-m-d")."','".date("H:i")."','Appele en selection ".$selectionFrance." ! ')";
$req2 = mysql_query($sql2);

 }
header("location: joueurs/listeInternational.php?affPosition=$affPosition&masque=$masque&ordre=$ordre&sens=$sens");
break;

case "supprAssigneSelection";
$sql = "DELETE FROM ht_selection WHERE id_joueur = ".$idJoueur."";
$req = mysql_query($sql);



$sql2 = "INSERT INTO ht_histomodif (idJoueur_fk , idAdmin_fk , dateHisto  ,heureHisto  ,intituleHisto )";
$sql2 .= " VALUES ('".$idJoueur."','". $sesUser["idAdmin"]."','".date("Y-m-d")."','".date("H:i")."','Ce joueur quitte la selection ".$selectionFrance." ! ')";
$req2 = mysql_query($sql2);


header("location: joueurs/listeInternational.php?affPosition=$affPosition&masque=$masque&ordre=$ordre&sens=$sens");
break;





case "annuleAssignation";

$sql = "update $tbl_joueurs set ht_posteAssigne = NULL where idJoueur = $idJoueur";
$req = mysql_query($sql);


$sql2 = "select * from $tbl_position where idPosition = $affPosition";
$req2 = mysql_query($sql2);
$res2 = mysql_fetch_array($req2);


$_POST["idJoueur_fk"] = $idJoueur;
$_POST["idAdmin_fk"] = $sesUser["idAdmin"];
$_POST["dateHisto"] = date("Y-m-d");
$_POST["heureHisto"] = date("H:i");
$_POST["intituleHisto"]  = "Desassignation du joueur du poste ".$res2["intitulePosition"];

$sql3 = insertDB($tbl_histomodif);

header("location: joueurs/liste.php?affPosition=$affPosition&masque=$masque&ordre=$ordre&sens=$sens");
break;

case "annuleAssignationDTN";

$sql = "update $tbl_joueurs set dtnSuiviJoueur_fk = 0 where idJoueur = $idJoueur";
$req = mysql_query($sql);



$sql2 = "select * from $tbl_admin where idAdmin = $ancienDTN";
$req = mysql_query($sql2);
$res = mysql_fetch_array($req);



$_POST["idJoueur_fk"] = $idJoueur;
$_POST["idAdmin_fk"] = $sesUser["idAdmin"];
$_POST["dateHisto"] = date("Y-m-d");
$_POST["heureHisto"] = date("H:i");
$_POST["intituleHisto"]  = "Deassignation du joueur du dtn ".$res["loginAdmin"];

$sql3 = insertDB($tbl_histomodif);

header("location: joueurs/attribution.php?masque=$masque&ordre=$ordre&sens=$sens&affPosition=$affPosition");
break;


case "assigneJoueurDTN";

for($i=0;$i<count($assigne);$i++){

$infDTN = getDTN($idDtn);


$_POST["idJoueur_fk"] = $assigne[$i];
$_POST["idAdmin_fk"] = $sesUser["idAdmin"];
$_POST["dateHisto"] = date("Y-m-d");
$_POST["heureHisto"] = date("H:i");
$_POST["intituleHisto"]  = "Assignation du joueur au dtn ".$infDTN["loginAdmin"];

$sql3 = insertDB($tbl_histomodif);




$sql = "update $tbl_joueurs set dtnSuiviJoueur_fk = $idDtn where idJoueur = $assigne[$i]";
$req = mysql_query($sql);


}
header("location: joueurs/attribution.php?masque=$masque&ordre=$ordre&sens=$sens&affPosition=$affPosition");

break;


case "assigne1JoueurDTN";

$infDTN = getDTN($idDtn);

$_POST["idJoueur_fk"] = $idJoueur;
$_POST["idAdmin_fk"] = $sesUser["idAdmin"];
$_POST["dateHisto"] = date("Y-m-d");
$_POST["heureHisto"] = date("H:i");
$_POST["intituleHisto"]  = "Assignation du joueur au dtn ".$infDTN["loginAdmin"];

$sql3 = insertDB($tbl_histomodif);

$sql = "update $tbl_joueurs set dtnSuiviJoueur_fk = $idDtn where idJoueur = $idJoueur";
$req = mysql_query($sql);

header("location: joueurs/fiche.php?htid=$htid");

break;



case "ajoutPays";



$sql = insertDB($tbl_pays);
header("location: pays/index.php?msg=Pays bien ajoute a la liste");
break;

case "supprPays";
supprDB($tbl_pays,$id);
header("location: pays/index.php?msg=Pays bien supprime");
break;


case "modifPays";
majDB($tbl_pays,$id);
?>
<script language="JavaScript">
window.opener.location = "pays/index.php?msg=Le pays a bien ete modifie";
window.close();
</script>
<?php
break;


case "valideJoueur";

for($i=0;$i<count($assigne);$i++){




$_POST["idJoueur_fk"] = $assigne[$i];
$_POST["idAdmin_fk"] = $sesUser["idAdmin"];
$_POST["dateHisto"] = date("Y-m-d");
$_POST["heureHisto"] = date("H:i");
$_POST["intituleHisto"]  = "Validation du joueur";

$sql3 = insertDB($tbl_histomodif);



$sql = "update $tbl_joueurs set joueurActif = 1 where idJoueur = $assigne[$i]";
$req = mysql_query($sql);


}
header("location: joueurs/validejoueur.php?msg=Le joueur a bien ete valide");



break;



case "rapportDetaille":

$_POST["idJoueur_fk"] = $id;
$_POST["idAdmin_fk"] = $sesUser["idAdmin"];
if($blessure == "BLS") $_POST["postePerf"] = $blessure;
$sql = insertDB($tbl_perf);

$sql = "select * from $tbl_perf order by idPerf desc";

$req = mysql_query($sql);
$res = mysql_fetch_array($req);



$_POST["dateHisto"] = date("Y-m-d");
$_POST["heureHisto"] = date("H:i");
$_POST["intituleHisto"]  = "Ajout d'un match";
$_POST["idPerf_fk"]  = $res["idPerf"];


$sql3 = insertDB($tbl_histomodif);

$sql = "UPDATE ht_joueurs SET dateDerniereModifJoueur = \"".date("Y-m-d")."\" WHERE idJoueur = '".$id."'";
$req = mysql_query($sql);

header("location: joueurs/rapportDetaille.php?id=$id");
break;


case "rapportDetailleAjout":

$_POST["idAdmin_fk"] = $sesUser["idAdmin"];
$_POST["date_match"] = $date_match." 00:00:00";

//calcul ht-time to insert ht week/season information			
$dateArray = explode("-",$date_match);
$unixTime=  mktime(0,0,0,$dateArray[1],$dateArray[2],$dateArray[0]);
$seasonw=(getSeasonWeekOfMatch($unixTime));


$_POST["week"]=$seasonw["week"];
$_POST["season"]=$seasonw["season"];

$sql = insertDB($tbl_perf);

$_POST["dateHisto"] = date("Y-m-d");
$_POST["heureHisto"] = date("H:i");
$_POST["intituleHisto"]  = "Ajout d'un match ".$id_match." par ".$sesUser["loginAdmin"];
$_POST["idPerf_fk"]  = NULL;
$_POST["idProgression_fk"]  = NULL;
$_POST["idJoueur_fk"]  = $id;

$sql3 = insertDB($tbl_histomodif);

//$sql = "UPDATE ht_joueurs SET dateDerniereModifJoueur = \"".date("Y-m-d")."\" WHERE idJoueur = '".$id."'";
//$req = mysql_query($sql);

header("location: joueurs/rapportDetaille.php?id=$id");
break;


case "rapportDetailleSupprime":

$_POST["idAdmin_fk"] = $sesUser["idAdmin"];

if (($id_match != "")&&($ht_id!="")){ 
	$sql = "delete from $tbl_perf where id_joueur='".$ht_id."' and id_match='".$id_match."'  LIMIT 1";
	$req = mysql_query($sql);
	
}else{
	header("location: joueurs/rapportDetaille.php?id=$id&msg=la+suppression+a+echoue");
	break;
}
$_POST["dateHisto"] = date("Y-m-d");
$_POST["heureHisto"] = date("H:i");
$_POST["intituleHisto"]  = "Suppression du match ".$id_match." par ".$sesUser["loginAdmin"];
$_POST["idPerf_fk"]  = NULL;
$_POST["idProgression_fk"]  = NULL;
$_POST["idJoueur_fk"]  = $id;

$sql3 = insertDB($tbl_histomodif);
header("location: joueurs/rapportDetaille.php?id=$id&msg=suppression+ok");
break;




case "rapportDetailleModif":
$_POST["idAdmin_fk"] = $sesUser["idAdmin"];

if (($id_match != "")&&($id_joueur!="")){ 
	$sql = "update $tbl_perf set tsi='".$tsi."',forme='".$forme."',etoile='".$etoile."',id_role='".$id_role."',id_behaviour='".$id_behaviour."',id_position='".$id_position."' where id_joueur='".$id_joueur."' and id_match='".$id_match."'  LIMIT 1";
	$req = mysql_query($sql);
	
}else{
	header("location: joueurs/rapportDetaille.php?id=$id&msg=la+modification+a+echoue");
	break;
}

$_POST["idJoueur_fk"] = $id;
$_POST["dateHisto"] = date("Y-m-d");
$_POST["heureHisto"] = date("H:i");
$_POST["idPerf_fk"]  = NULL;
$_POST["idProgression_fk"]  = NULL;
$_POST["intituleHisto"]  = "Modification du  match  " .$id_match." par ".$sesUser["loginAdmin"] ;
$sql3 = insertDB($tbl_histomodif);

header("location: joueurs/rapportDetaille.php?id=$id&msg=Rapport+detaille+modifie+avec+succes");
break;








case "clotureRapport":
$sql = mysql_query("update $tbl_config set valeurConfig  = \"0\" where nomConfig = \"rapport\"");
header("location: index2.php");

break;
case "ouvrirRapport":
$sql = mysql_query("update $tbl_config set valeurConfig  = \"1\" where nomConfig = \"rapport\"");
header("location: index2.php");

break;



case "supprMatch":
$sql = mysql_query("update $tbl_perf set affPerf = 0 where idPerf = $idPerf");


$_POST["idJoueur_fk"] = $idJoueur;
$_POST["idAdmin_fk"] = $sesUser["idAdmin"];
$_POST["dateHisto"] = date("Y-m-d");
$_POST["heureHisto"] = date("H:i");
$_POST["idPerf_fk"] = $idPerf;

$_POST["intituleHisto"]  = "Supression d'un match par ".$sesUser["loginAdmin"];

$sql3 = insertDB($tbl_histomodif);


header("location: joueurs/rapportDetaille.php?id=$idJoueur");

break;

case "archiveJoueur":

$sql = "INSERT INTO ht_histomodif ( idJoueur_fk,  dateHisto  ,heureHisto, intituleHisto  )";
$sql .= " VALUES ('".$id."','".date("Y-m-d")."','".date("H:i")."','[".$sesUser["loginAdmin"]."] Archivage du joueur')";
$req = mysql_query($sql);

$sql = mysql_query(" UPDATE ht_joueurs SET archiveJoueur = 1, dtnSuiviJoueur_fk  =0, affJoueur = 0 WHERE idJoueur = $id") or die ("Erreur :$sql");
header("location: joueurs/fiche.php?id=$id&msg=archive");
break;

case "joueurSupprimeDTN":

$sql = "INSERT INTO ht_histomodif ( idJoueur_fk,  dateHisto  ,heureHisto, intituleHisto  )";
$sql .= " VALUES ('".$idJoueur."','".date("Y-m-d")."','".date("H:i")."','[".$sesUser["loginAdmin"]."] changement de DTN (avant : ".$dtnname.")')";
$req = mysql_query($sql);

$sql = mysql_query(" UPDATE ht_joueurs SET  dtnSuiviJoueur_fk  =0 WHERE idJoueur = '$idJoueur'") or die ("Erreur :$sql");
header("location: joueurs/fiche.php?id=$idJoueur&msg=plus_suivi_par_".$dtnname);
break;

case "joueurSupprimeSecteur":

$sql = "INSERT INTO ht_histomodif ( idJoueur_fk,  dateHisto  ,heureHisto, intituleHisto  )";
$sql .= " VALUES ('".$idJoueur."','".date("Y-m-d")."','".date("H:i")."','[".$sesUser["loginAdmin"]."] changement de secteur (avant : ".$secteur.")')";
$req = mysql_query($sql);

$sql = mysql_query(" UPDATE ht_joueurs SET  dtnSuiviJoueur_fk  =0, ht_posteAssigne=0 WHERE idJoueur = '$idJoueur'") or die ("Erreur :$sql");
header("location: joueurs/fiche.php?id=$idJoueur&msg=plus_de_secteur_defini");
break;

case "desarchiveJoueur":

$sql = "INSERT INTO ht_histomodif ( idJoueur_fk,   dateHisto  ,heureHisto, intituleHisto  )";
$sql .= " VALUES ('".$id."','".date("Y-m-d")."','".date("H:i")."','[".$sesUser["loginAdmin"]."] Desarchivage du joueur')";
$req = mysql_query($sql);

$sql = mysql_query(" UPDATE ht_joueurs SET archiveJoueur = 0, dtnSuiviJoueur_fk  =0, affJoueur = 1 WHERE idJoueur = $id") or die ("Erreur :$sql");
header("location: joueurs/fiche.php?id=$id&msg=desarchive");
break;

case "sortJoueur":

$sql = "DELETE FROM ht_selection WHERE id_joueur = $idJoueur";
$req = mysql_query($sql);

header("location: joueurs/selection.php?sup=ok");

break;


case "coeffSelectionneur":

$infSettings =  getCoeffSelectionneur($id_p);

if(!$infSettings){

$sql = " INSERT INTO ht_settings ( idAdmin,coeffConstruction, coeffPasse,  coeffAilier,  coeffDefense , coeffButeur,  coeffXp , coeffGardien, coeffEndurance,   useit , id_poste )";
$sql .=" VALUES ('".$sesUser["idAdmin"]."','".$coeffConstruction."','".$coeffPasse."','".$coeffAilier."','".$coeffDefense."','".$coeffButeur."','".$coeffXp."','".$coeffGardien."','".$coeffEndurance."','1','".$id_p."')";
$req = mysql_query($sql);
}
else {
$sql = " UPDATE ht_settings SET
 coeffConstruction = '".$coeffConstruction."',
 coeffPasse = '".$coeffPasse."',
 coeffAilier  = '".$coeffAilier."',
 coeffDefense   = '".$coeffDefense."',
 coeffButeur  = '".$coeffButeur."',
 coeffXp  = '".$coeffXp."',
 coeffGardien  = '".$coeffGardien."',
 coeffEndurance  = '".$coeffEndurance."',
 useit   = '".$use."'
 WHERE idAdmin = '".$sesUser["idAdmin"]."' AND id_poste = '".$id_p."'
  ";
$req = mysql_query($sql);
}

header("location: settings.php?id_postes=$id_p&mod=ok&affCoeff=$affCoeff");

break;

case "chgInfoPerso":

$sql = "UPDATE ht_admin SET loginAdmin = '".$login."', passAdmin = '".$mdp."', emailAdmin = '".$email."'


 WHERE idAdmin = '".$sesUser["idAdmin"]."'";


$req = mysql_query($sql);

header("location: settings.php?modperso=ok&affinfoPerso=1");
break;


// **************************************************************
// **  Modification Fiche DTN 		utilisation +1/-1 		   **
// **************************************************************

case "addNiveau":

// check niveau 0 ou divin+1 refusés
 if (  ($niveau==0 &&( $champ=='idEndurance' || $champ=='idGardien' ||$champ=='idConstruction' ||$champ=='idPasse' ||$champ=='idAilier' ||$champ=='idDefense' ||$champ=='idButeur' ||$champ=='idPA'  ||$champ=='idExperience_fk'   ))
	 || ($niveau==21 &&( $champ=='idEndurance' || $champ=='idGardien' ||$champ=='idConstruction' ||$champ=='idPasse' ||$champ=='idAilier' ||$champ=='idDefense' ||$champ=='idButeur' ||$champ=='idPA'  ||$champ=='idExperience_fk'  )) 
 	){
 	$_POST["intituleHisto"]  = "Niveau choisi impossible! ($niveau)";
}else{

  // Modification du niveau
  $sql = mysql_query("update $tbl_joueurs set $champ = $niveau where idjoueur = $idJoueur");
  
  $_POST["idJoueur_fk"] = $idJoueur;
  $_POST["idAdmin_fk"] = $sesUser["idAdmin"];
  $_POST["dateHisto"] = date("Y-m-d");
  $_POST["heureHisto"] = date("H:i:s");
  
  
  $_POST["idTypeCarac_fk"] = $idTypeCarac;
  
  $updateDateDerniereModiff="on";
  
  $updatedate_modif_effectif="off";
  
  
  // Remise a 0 des semaines d'entrainement du niveau concerne
  switch($idTypeCarac){
  case "1";
  	$nomNiveau=" Construction ";
  	$sql = "update $tbl_entrainement set nbSemaineConstruction  = 0 where idJoueur_fk = $idJoueur";
  	$req=mysql_query($sql);
  	$updatedate_modif_effectif="on";
  break;
  
  case "2";
  	$nomNiveau=" Ailier ";
  	$sql = "update $tbl_entrainement set nbSemaineAilier  = 0 where idJoueur_fk = $idJoueur";
  	$req=mysql_query($sql);
  	$updatedate_modif_effectif="on";
  break;
  
  case "3";
  	$nomNiveau=" Buteur ";
  	$sql = "update $tbl_entrainement set nbSemaineButeur  = 0 where idJoueur_fk = $idJoueur";
  	$req=mysql_query($sql);
  	$updatedate_modif_effectif="on";
  break;
  case "4";
  	$nomNiveau=" Gardien ";
  	$sql = "update $tbl_entrainement set nbSemaineGardien  = 0 where idJoueur_fk = $idJoueur";
  	$req=mysql_query($sql);
  	$updatedate_modif_effectif="on";
  break;
  case "5";
  	$nomNiveau=" Passe ";
  	$sql = "update $tbl_entrainement set nbSemainePasses  = 0 where idJoueur_fk = $idJoueur";
  	$req=mysql_query($sql);
  	$updatedate_modif_effectif="on";
  break;
  case "6";
  	$nomNiveau=" Defense ";
  	$sql = "update $tbl_entrainement set nbSemaineDefense  = 0 where idJoueur_fk = $idJoueur";
  	$req=mysql_query($sql);
  	$updatedate_modif_effectif="on";
  break;
  case "7":
  		$nomNiveau=" Coup de pied ";
  		//MAJ CF par jojoje86 le 18/01/10-->
  		$updatedate_modif_effectif="on";
  		break;   
  case "8":
  		$nomNiveau=" XP " ;
  		break;   
  case "9":
  		$nomNiveau=" Endurance " ;
  break;
  case "10": //age
  		$nomNiveau=" Age " ;
  		$updateDateDerniereModiff="off";
  break;
  
  }
  
  if($baisse != 1){
  	$_POST["intituleHisto"]  = " + Hausse de niveau ".$nomNiveau." = ".$niveau;
  }
  else {
  	$_POST["intituleHisto"]  = " - Baisse de niveau ".$nomNiveau." = ".$niveau;
  }
  
  $_POST["idAdmin_fk"] = $sesUser["idAdmin"];
  
  $sql = insertDB($tbl_histomodif);
  
   if (  $updateDateDerniereModiff=='on' ){ 
  	$sql = "UPDATE ht_joueurs set dateDerniereModifJoueur = '".date("Y-m-d")."' WHERE idJoueur = $idJoueur";
  	$req = mysql_query($sql);
   }
   if (  $updatedate_modif_effectif=='on' ){ 
  	$sql = "UPDATE ht_joueurs set date_modif_effectif = '".date("Y-m-d")."' WHERE idJoueur = $idJoueur";
  	$req = mysql_query($sql);
   }
  
  // Calcul des nouvelles valeurs du joueurs :
  $infJoueur = getJoueur($idJoueur);
  
  $infJoueur["score"] = calculNote($infJoueur);
  $infJoueur["scorePotentiel"] = calculNotePotentiel($infJoueur);
  $maj = majCaracJoueur($infJoueur);

}
header("location: joueurs/ficheDTN.php?id=$idJoueur&msg=".$_POST['intituleHisto']);

break;


// ********************************************************************
// **  Modification Fiche DTN utilisation "mettre à jour le rapport" **
// ********************************************************************

case "updateTraining":

$infJoueur = getJoueur($_POST["idJoueur_fk"]);
$updateDateDerniereModiff="off";
$updatedate_modif_effectif="off";

$msg ="";
if($infJoueur["nbSemaineConstruction"]!= $_POST["nbSemaineConstruction"]){
	$msg  = "Modif semaines construction (".$infJoueur["nbSemaineConstruction"]." ->+".$_POST["nbSemaineConstruction"].")  ";
	$updateDateDerniereModiff="on";	
	$updatedate_modif_effectif="on";
}
if($infJoueur["nbSemaineAilier"]!= $_POST["nbSemaineAilier"]){
	$msg  = $msg." Modif semaines ailier (".$infJoueur["nbSemaineAilier"]." ->+".$_POST["nbSemaineAilier"].")  ";
	$updateDateDerniereModiff="on";	
	$updatedate_modif_effectif="on";
}
if($infJoueur["nbSemaineButeur"]!= $_POST["nbSemaineButeur"]){
	$msg  = $msg." Modif semaines buteur (".$infJoueur["nbSemaineButeur"]." ->+".$_POST["nbSemaineButeur"].")  ";
	$updateDateDerniereModiff="on";	
	$updatedate_modif_effectif="on";
}
if($infJoueur["nbSemaineGardien"]!= $_POST["nbSemaineGardien"]){
	$msg  = $msg." Modif semaines gardien (".$infJoueur["nbSemaineGardien"]." ->+".$_POST["nbSemaineGardien"].")  ";
	$updateDateDerniereModiff="on";	
	$updatedate_modif_effectif="on";
}

if($infJoueur["nbSemainePasses"]!= $_POST["nbSemainePasses"]){
	$msg  = $msg." Modif semaines passe (".$infJoueur["nbSemainePasses"]." ->+".$_POST["nbSemainePasses"].")  ";
	$updateDateDerniereModiff="on";	
	$updatedate_modif_effectif="on";
}
if($infJoueur["nbSemaineDefense"]!= $_POST["nbSemaineDefense"]){
	$msg  = $msg." Modif semaines defense (".$infJoueur["nbSemaineDefense"]." ->+".$_POST["nbSemaineDefense"].")  ";
	$updateDateDerniereModiff="on";	
	$updatedate_modif_effectif="on";
}


	$sql =("UPDATE ht_entrainement SET
         nbSemaineConstruction = \"".$_POST["nbSemaineConstruction"]."\",
         nbSemaineAilier= \"".$_POST["nbSemaineAilier"]."\",
         nbSemaineButeur = \"".$_POST["nbSemaineButeur"]."\",
         nbSemaineGardien = \"".$_POST["nbSemaineGardien"]."\",
         nbSemainePasses = \"".$_POST["nbSemainePasses"]."\",
         nbSemaineDefense = \"".$_POST["nbSemaineDefense"]."\"
         WHERE idJoueur_fk = ".$_POST["idJoueur_fk"]."");
     $req = mysql_query($sql) or die($sql);


$infJoueur = getJoueur($_POST["idJoueur_fk"]);


$_POST["dateDerniereModifJoueur"] = date("Y-m-d");

$infJoueur["score"] = calculNote($infJoueur);
$infJoueur["scorePotentiel"] = calculNotePotentiel($infJoueur);
$maj = majCaracJoueur($infJoueur);



$_POST["idJoueur_fk"] = $idJoueur_fk;
$_POST["idAdmin_fk"] = $sesUser["idAdmin"];
$_POST["dateHisto"] = date("Y-m-d");
$_POST["heureHisto"] = date("H:i:s");

$_POST["intituleHisto"]  = $msg;
$sql = insertDB($tbl_histomodif);

if (  $updateDateDerniereModiff=='on' ){ 
	$sql = "UPDATE ht_joueurs set dateDerniereModifJoueur = '".date("Y-m-d")."' WHERE idJoueur ='".$idJoueur_fk."'";
	$req = mysql_query($sql);
}
if (  $updatedate_modif_effectif=='on' ){ 
	$sql = "UPDATE ht_joueurs set date_modif_effectif = '".date("Y-m-d")."' WHERE idJoueur ='".$idJoueur_fk."'";
	$req = mysql_query($sql);
}



header("location: joueurs/ficheDTN.php?id=$idJoueur_fk&msg=$msg");
break;



// ********************************************************************
// **  Modification Commentaire sur un joueur **
// ********************************************************************

case "updateComment":

/*$_POST["idAdmin_fk"] = 0;
$_POST["dateHisto"] = date("Y-m-d");
$_POST["heureHisto"] = date("H:i");
$_POST["intituleHisto"]  = "[".$sesUser["loginAdmin"]."]Modification commentaire joueur";

$sql = insertDB($tbl_histomodif);
*/

$joueurComment = addslashes($joueurComment);

	$sql2 = "UPDATE ht_joueurs
			        SET commentaire = '".$joueurComment."'
			      WHERE idJoueur    = '".$idJoueur_fk."'";
	$result = mysql_query($sql2);


header("location: joueurs/commentaires.php?id=$idJoueur_fk");
break;

########################################################
#
# Section dediee à la connexion a Hattrick
#
########################################################

//following code allow the DTN to open its connexion with the hattrick server.
//ht_client used is stored in "ht_session" variable along the session
/*case "openHtSession":
if (!isset($zeurl)){
	$zeurl="maliste/miseajour.php";
}

	if (!session_is_registered("ht_session")){
		if ( isset($htlogin) && isset($htseccode) && $htlogin!="" && $htseccode!=""){
		
			$ht_session = &new HT_Client();
			if (!$ht_session->Login($htlogin, $htseccode))
			{
				//		echo "  Couldn't connect to Hattrick.";
				header("location: ".$zeurl."?msg=probleme_lors_de_l_ouverture_de_Session_hattrick");
			}else{
				session_register("ht_session");
				$_SESSION['ht_session']=$ht_session;
				header("location: ".$zeurl."?msg=Session_hattrick_ouverte!");
			}
		}else{
			header("location: ".$zeurl."?msg=ouverture_de_Session_hattrick_impossible_manque_de_parametres");
		}
	}else{
		header("location: ".$zeurl."?msg=Session_hattrick_deja_active");
	}
break;
*/

//check if htSession is valid
/*case "checkHtSession":
if (!session_is_registered("ht_session")){
		print "pas de connexion actuellement";
}else{
			if ($ht_session->isConnected()){
				print "connexion toujours active";
			}else{
				print "connexion interrompue. (inactivit&eacute; trop longue)";
			}
}
break;
*/

// following code allow the DTN to close its open connexion with the hattrick server.
/*case "resetHtSession":
if (!isset($zeurl)){
	$zeurl="maliste/miseajour.php";
}
	if (session_is_registered("ht_session")){
		if ($ht_session->Logout()){
			$msglogout="_logoutOK";
		}else{
				$msglogout="_logoutKO";
		}
		if (session_unregister("ht_session")){
			header("location: ".$zeurl."?msg=Session_desactivee".$msglogout);
		}else{
			header("location: ".$zeurl."?msg=probleme_lors_de_la_fermeture_de_Session".$msglogout);
		}
	}else{
				header("location: ".$zeurl."?msg=probleme_lors_de_la_fermeture_de_Session_deja_fermee");
	}
break;
*/

// ********************************************************************
// **  Ajout Fiche DTN d'un historique club **
// ********************************************************************
case "addHistoClub":

  // Insertion de l'histo club
  $_POST["cree_par"]=$sesUser["loginAdmin"];
  $idHistoClub=insertHistoClub($_POST);
  if ($idHistoClub!=false) {
    $msg="Insertion historique club reussie";
  }

  // Si changement entrainement alors insertion dans histo modif pour le joueur concerné
  if ($_POST["OldEntrainementId"]!= $_POST["idEntrainement"]) {
  	$lTraining=listEntrainement();
  	$codeEntrainement=getEntrainementCode($_POST["idEntrainement"],$lTraining);
  	
    if ($codeEntrainement!='??') {
    	$msgHistoModif .= "Modif entrainement (".getEntrainementName($_POST["OldEntrainementId"],$lTraining)." ->".getEntrainementName($_POST["idEntrainement"],$lTraining).")  ";
    }
    
    // Insertion d'un enregistrement dans l'histo des modifs
  	$_POST["idJoueur_fk"] = $_POST['idJoueur'];
  	$_POST["idAdmin_fk"] = $sesUser["idAdmin"];
  	$_POST["intituleHisto"]  = $msgHistoModif;
  	$_POST["id_Clubs_Histo"] = $idHistoClub;
  	$_POST["dateHisto"] = date("Y-m-d");
    $_POST["heureHisto"] = date("H:i:s");
  	$sql = insertDB($tbl_histomodif);
  	
  }


  // Maj de l'entrainement au niveau du joueur
	$sql2 = "UPDATE ht_joueurs
			     SET entrainement_id = '".$_POST["idEntrainement"]."'
			     WHERE teamid=".$_POST['idClubHT'];
	$result = mysql_query($sql2) or die(mysql_error()."\n".$sql2);
 
  header("location: joueurs/ficheDTN.php?id=".$_POST['idJoueur']."&msg=$msg");

break;


// ********************************************************************
// **  Ajout Fiche DTN d'un commentaire joueur **
// ********************************************************************
case "addHistoJoueur":

	$_POST["idJoueur_fk"] = $_POST['idJoueur'];
	$_POST["idAdmin_fk"] = $sesUser["idAdmin"];
	$_POST["intituleHisto"]  = $_POST['intituleHisto'];
	$_POST["dateHisto"] = date("Y-m-d");
  $_POST["heureHisto"] = date("H:i:s");
	$sql = insertDB($tbl_histomodif);

  $msg="Insertion commentaire joueur reussie";
  
  header("location: joueurs/ficheDTN.php?id=".$_POST['idJoueur']."&msg=$msg");

break;





case "":
	echo "error no action code given";
	return;
}




//
//
// Sous ce commentaires se trouvent les fonctionnalites normalement
// programmee de facon plus propre.
//
//
require_once "_config/CstGlobals.php"; // fonctions d'admin
require_once "fonctions/AccesBase.php"; // fonction de connexion a la base
require_once "fonctions/coach_utils.php";// fonction pour les operations specifiques au selectionneur

$maBase = initBD();

switch($mode){
case "joueurSelectionOn";

if (selectionNationalTeamOn($idJoueur,$selectionFrance,$maBase)){
	header("location: joueurs/fiche.php?id=".$idJoueur);
}else{
	header("location: joueurs/fiche.php?id=".$idJoueur."&msg=probleme_sql");
}
break;

case "joueurSelectionOff";
if (selectionNationalTeamOff($idJoueur,$selectionFrance,$maBase)){
	header("location: joueurs/fiche.php?id=".$idJoueur);
}else{
	header("location: joueurs/fiche.php?id=".$idJoueur."&msg=probleme_sql");
}
break;

}

?>
