<?php
require_once("../includes/head.inc.php");
require("../includes/serviceEntrainement.php");
require("../includes/serviceJoueur.php");
require("../includes/serviceEquipes.php");
require("../includes/langue.inc.php");
require("../includes/serviceListesDiverses.php");

if(!$sesUser["idAdmin"])
  {
  header("location: index.php?ErrorMsg=Session Expiree");
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
    
 
 

switch($infJ["idPosition"]){

    case "1":
      //gK
      $k = 1;
      $defense = 1;
      break;
    
    case "2":
      // cD
      $d = 1;
      $ailier = 1;
      $defense = 1;
      $passe = 1;
      $construction = 1;
      break;
    
    case "3":
      // Wg
      $construction = 1;
      $ailier = 1;
      $defense = 1;
      $passe = 1;
      $wing = 1;
      $wingoff = 1;
      $wingwtm = 1;
      break;

    case "4":
      //IM
      $m = 1;
      $moff = 1;
      $construction = 1;
      $defense = 1;
      $passe = 1;
	  //ajout de buteur et ailier par jojoje86 le 21/07/09
	  $ailier = 1;
	  $buteur = 1;
      break;
    
    case "5":
      // Fw
      $ailier = 1;
      $passe = 1;
      $buteur = 1;
      $construction = 1;
      break;
  
    default:
      break;
}
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

<p>
  
  <SPAN ID=textespan>
    
    <b>[b]
    <?=strtolower($infJ["nomJoueur"])?> 
    <?=strtolower($infJ["prenomJoueur"])?> 
    (
    <?=strtolower($infJ["idHattrickJoueur"])?>
    ) 
    <?php 
      $tabage = ageetjour($infJ["datenaiss"], 2);
      echo $tabage[ageJoueur];?>&nbsp;ans&nbsp;-&nbsp;<?=$tabage[jourJoueur]
    ?> 
    jours[/b]</b><br>
    Proprietaire :  
    <?=$infJ["nomClub"]?> 
    (
    <?=$nomPays[0]?>
    )<br/>
    [color=darkred]> Derniere edition:
    <?=dateToHTML($infJ["dateDerniereModifJoueur"])?>
    [/color]<br/>
    [color=red]> Derniere mise a jour par le proprietaire: (
    <?=dateToHTML($infJ["dateSaisieJoueur"])?> 
    )[/color]<br/>
    TSI: 
    <?=$infJ["valeurEnCours"]?>
    <br>
  </span><SPAN>Salaire : 
  <?=round(($infJ["salary"]/10),2)?>
  &euro;/semaine (
  <?=round(($infJ["salaireDeBase"]/10),2)?>
  &euro; en France)<br/>
  <?php  if( $infJ["optionJoueur"] != 0){?>
  <b>[b]
  <?=$option[$infJ["optionJoueur"]]["FR"]?>
  [/b]</b>
  <?php } ?>
  Xp : 
  <?=$lstCaractJ[$infJ["idExperience_fk"]]["intituleCaracFR"]?>
  <br/>
  
  
  
  
  
  Endurance : 
  <?=$lstCaractJ[$infJ["idEndurance"]]["intituleCaracFR"]?> 
  ( 
  <?=$infJ["idEndurance"]?> 
  )<br/>
  <?php if($construction == 1) {?>
  Construction : 
  <?=$lstCaractJ[$infJ["idConstruction"]]["intituleCaracFR"]?>
  </i> ( 
  <?=$infJ["idConstruction"]?> 
  ) + 
  <?=$infJ["nbSemaineConstruction"]?>
  <br/>
  <?php } ?>
  <?php if($ailier == 1) {?>
  Ailier : 
  <?=$lstCaractJ[$infJ["idAilier"]]["intituleCaracFR"]?>
  </i> ( 
  <?=$infJ["idAilier"]?> 
  ) + 
  <?=$infJ["nbSemaineAilier"]?>
  <br/>
  <?php } ?>
  <?php if($buteur == 1) {?>
  Buteur : 
  <?=$lstCaractJ[$infJ["idButeur"]]["intituleCaracFR"]?>
  </i> ( 
  <?=$infJ["idButeur"]?> 
  ) + 
  <?=$infJ["nbSemaineButeur"]?>
  <br/>
  <?php } ?>
  <?php if($k == 1) {?>
  Gardien : 
  <?=$lstCaractJ[$infJ["idGardien"]]["intituleCaracFR"]?>
  </i> ( 
  <?=$infJ["idGardien"]?> 
  ) + 
  <?=$infJ["nbSemaineGardien"]?>
  <br/>
  <?php } ?>
  <?php if($passe == 1) {?>
  Passe : 
  <?=$lstCaractJ[$infJ["idPasse"]]["intituleCaracFR"]?>
  </i> ( 
  <?=$infJ["idPasse"]?> 
  ) + 
  <?=$infJ["nbSemainePasses"]?>
  <br/>
  <?php } ?>
  <?php if($defense == 1) {?>
  Defense : 
  <?=$lstCaractJ[$infJ["idDefense"]]["intituleCaracFR"]?>
  </i> ( 
  <?=$infJ["idDefense"]?> 
  ) + 
  <?=$infJ["nbSemaineDefense"]?>
  <br/>
  <?php } ?>
  Coup de pied : 
  <?=$lstCaractJ[$infJ["idPA"]]["intituleCaracFR"]?>
  </i> ( 
  <?=$infJ["idPA"]?> 
  ) <br/>
  
  <br/>
  [u]Entrainement et commentaires[/u]: 
  <?php if(!isset($infJ["finFormation"]) || $infJ["finFormation"] == "") $infJ["finFormation"] = "Inconnu";?>
  <?=$infJ["finFormation"]?>
  <br/>
  </span></p>
<p>
<p>
<p>


<A HREF=# style=\"text-decoration:none\" onClick=\"copy2Clipboard(document.getElementById('textespan'));return(false)\">Copier cette fiche dans le presse-papier en un click(sous Internet Explorer)</A>
<!--// ajout du retour vers la fiche DTN du joueur par jojoje86 le 21/07/09-->
<br><br>
<A HREF=# style=\"text-decoration:none\" onClick="javascript:history.go(-1);">Retour</A>
</body>
</html>

