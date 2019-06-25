<?php
require_once("../includes/head.inc.php");
require("../includes/serviceEntrainement.php");
require("../includes/serviceJoueur.php");
require("../includes/serviceEquipes.php");
require("../includes/langue.inc.php");
require("../includes/serviceListesDiverses.php");

if(!$sesUser["idAdmin"])
{
	header("location: ../index.php?ErrorMsg=Session Expiree");
}

if (isset($htid))
{
  $infJ = getJoueurHt($htid);
  $id = $infJ["idJoueur"];
}
else
  $infJ = getJoueur($id);
  
global $mode;
$idHT = $infJ["idHattrickJoueur"];
$idClubHT=$infJ["teamid"];

switch($sesUser["idNiveauAcces"]){
    
  /*  case "2":
      if ($sesUser["idPosition_fk"]!= $infJ["ht_posteAssigne"]
        && $infJ["ht_posteAssigne"]!=0 
      ){
        print("<html><body><center>Ce joueur est associe a un autre secteur de jeu.<br> Operation interdite pour  <font color=red>".$sesUser["loginAdmin"]."</font>!</center></body></html>");
        return;
      }
    break; */


    case "3":
      if ($sesUser["idPosition_fk"]!= $infJ["ht_posteAssigne"]
        && $infJ["ht_posteAssigne"]!=0 
        && $sesUser["idPosition_fk"]!=0)
      {
        print("<html><body><center>Ce joueur est associe a un autre DTN.<br> Operation interdite pour  <font color=red>".$sesUser["loginAdmin"]."</font>!</center></body></html>");
        return;
      }
    break;
    
    default;
    break;

}

  
  
if(!isset($lang)) $lang = "FR";

if($mode == "transfere") $lstClub = listClubs();  


$lstEntrainementPossible = listEntrainement();
$lstCaractJ = listCarac('ASC',23);

  $sql = "SELECT idPays_fk from ht_clubs,ht_joueurs   where   idJoueur = '".$infJ["idJoueur"]."' and  ht_joueurs.teamid = ht_clubs.idClubHT ";
  $result= $conn->query($sql);
  $idPaysFK = $result->fetch();
  $sql = "SELECT nomPays from ht_pays   where idPays= '".$idPaysFK[0]."' ";
  $result= $conn->query($sql);
  $nomPays = $result->fetch();
  
  

?>
<html>
<head>
<title> Fiche DTN <?=$infJ["nomJoueur"]?> <?=$infJ["prenomJoueur"]?> 
      </title>

<script language="JavaScript" type="text/JavaScript">
function copy2Clipboard(obj)
{
	var textRange = document.body.createTextRange();
	textRange.moveToElementText(obj);
	textRange.execCommand("Copy");
}
</script>
</head>
<?php
switch($_SESSION['sesUser']["idNiveauAcces"]){
    case "1":
    require("../menu/menuAdmin.php");
    break;
    
    case "2":
    require("../menu/menuSuperviseur.php");
    break;


    case "3":
    require("../menu/menuDTN.php");
    break;
    
    case "4":
    require("../menu/menuCoach.php");
    break;
    
    default;
    break;
}


require("../menu/menuJoueur.php");


?>

<p style="border: 1px solid black;padding: 10px 10px 10px 10px">
  <span id="textespan" >
    [b]<?=ucwords($infJ["prenomJoueur"])?> <?php if (isset($infJ["surnomJoueur"])) echo " (".$infJ["surnomJoueur"].")"; ?> <?=ucwords($infJ["nomJoueur"])?>
    ([url=https://www.hattrick.org/goto.ashx?path=/Club/Players/Player.aspx?playerId=<?=strtolower($infJ["idHattrickJoueur"])?>]<?=strtolower($infJ["idHattrickJoueur"])?>[/url]) 
    <br/>
    <?php 
      $tabage = ageetjour($infJ["datenaiss"], 2);
      echo $tabage['ageJoueur'];?>&nbsp;ans&nbsp;-&nbsp;<?=$tabage['jourJoueur']
    ?> 
    jours[/b]<br/>
  Un gars <?=$infJ["intituleCaractereFR"]?> qui est <?=$infJ["intituleAggresFR"]?> et <?=$infJ["intituleHonneteteFR"]?>.<br/>
  Poss&egrave;de un niveau d'exp&eacute;rience <?=$lstCaractJ[$infJ["idExperience_fk"]]["intituleCaracFR"]?> (<?=$infJ["idExperience_fk"]?>) et un temp&eacute;rament de chef <?=$infJ["intituleLeaderFR"]?> (<?=$infJ["idLeader_fk"]?>)<br/><br/>
    Propri&eacute;taire :  
    <?=$infJ["nomClub"]?> 
    (<?=utf8_decode($nomPays[0])?>)<br/><br/>
    TSI: 
    <?=$infJ["valeurEnCours"]?>
    <br/>
  Salaire : 
  <?=round(($infJ["salary"]/10),2)?>
  &euro;/semaine (<?=round(($infJ["salaireDeBase"]/10),2)?>&euro; en France)<br/>
  <?php  if( $infJ["optionJoueur"] != 0){?>
  [b]<?=$option[$infJ["optionJoueur"]]["FR"]?>[/b]
  <?php } ?><br/>
  Endurance : <?=$lstCaractJ[$infJ["idEndurance"]]["intituleCaracFR"]?> (<?=$infJ["idEndurance"]?>)<br/>
<?php    // HTMS du joueur    
        $ageetjours = ageetjour($infJ["datenaiss"]);
        $tabage = explode(" - ",$ageetjours);
        $htms = htmspoint($tabage[0], $tabage[1], $infJ["idGardien"], $infJ["idDefense"], $infJ["idConstruction"], $infJ["idAilier"], $infJ["idPasse"], $infJ["idButeur"], $infJ["idPA"]); ?>
    [b]HTMS : <?php echo $htms["value"]." (".$htms["potential"].")[/b]"; ?><br/><br/>

  Gardien : <?=$lstCaractJ[$infJ["idGardien"]]["intituleCaracFR"]?> (<?=$infJ["idGardien"]?>) + <?=$infJ["nbSemaineGardien"]?> <br/>

  D&eacute;fense : <?=$lstCaractJ[$infJ["idDefense"]]["intituleCaracFR"]?> (<?=$infJ["idDefense"]?>) + <?=$infJ["nbSemaineDefense"]?> <br/>

  Construction : <?=$lstCaractJ[$infJ["idConstruction"]]["intituleCaracFR"]?> (<?=$infJ["idConstruction"]?>) + <?=$infJ["nbSemaineConstruction"]?> <br/>

  Ailier : <?=$lstCaractJ[$infJ["idAilier"]]["intituleCaracFR"]?> (<?=$infJ["idAilier"]?>) + <?=$infJ["nbSemaineAilier"]?> <br/>

  Passe : <?=$lstCaractJ[$infJ["idPasse"]]["intituleCaracFR"]?> (<?=$infJ["idPasse"]?>) + <?=$infJ["nbSemainePasses"]?> <br/>

  Buteur : <?=$lstCaractJ[$infJ["idButeur"]]["intituleCaracFR"]?> (<?=$infJ["idButeur"]?>) + <?=$infJ["nbSemaineButeur"]?> <br/>

  Coup Franc : <?=$lstCaractJ[$infJ["idPA"]]["intituleCaracFR"]?> (<?=$infJ["idPA"]?>) <br/>
  <br/>
  [u]Entrainement et commentaires[/u]: 
  <br/>
  </span>
  </p>
<p>
<p>
<p>


<A HREF=# style="text-decoration:none" onClick="copy2Clipboard(document.getElementById('textespan'));return(false)">Copier cette fiche dans le presse-papier en un clic</A>
<!--// ajout du retour vers la fiche DTN du joueur par jojoje86 le 21/07/09-->
<br><br>
<A HREF=# style="text-decoration:none" onClick="javascript:history.go(-1);">Retour</A>
</body>
</html>

