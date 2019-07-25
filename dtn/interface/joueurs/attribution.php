<?php 
require_once("../includes/head.inc.php");
require("../includes/langue.inc.php");
require("../includes/serviceJoueur.php");
require("../includes/serviceMatchs.php");
require("../includes/serviceListesDiverses.php");
require("../includes/serviceDTN.php");

$keeperColor = "#FFFFFF";
$defenseColor = "#FFFFFF";
$constructionColor = "#FFFFFF";
$ailierColor = "#FFFFFF";
$passeColor = "#FFFFFF";
$buteurColor = "#FFFFFF";


if(!$sesUser["idAdmin"])
{
	header("location: index.php?ErrorMsg=Session Expire");
}
if(!isset($ordre)) $ordre = "nomJoueur";
if(!isset($sens)) $sens = "ASC";
if(!isset($lang)) $lang = "FR";
if(!isset($masque)) $masque = 1;
if(!isset($affPosition)) $affPosition = 1;

//ecart sur variable affArchive appelé
if(!isset($affArchive))  $affArchive = 0;
if (!is_numeric($affPosition))
	$affPosition = substr($affPosition, 0, 1);

$lstPosition = listPosition();

$font = "<font color = black>";
$ffont = "</font>";


$huit = 60 * 60 * 24 * 8; //time_0
$quinze = 60 * 60 * 24 * 15; //time_1
$trente = 60 * 60 * 24 * 30; //time_2
$twomonths = 60 * 60 * 24 * 60; //time_3
$fourmonths = 60 * 60 * 24 * 120; //time_4

// Date du jour
$mkday = mktime(0,0,0,date('m'), date('d'),date('Y'));


$sql = "SELECT count( * ) as sum , dtnSuiviJoueur_fk
	FROM ht_joueurs
	where dtnSuiviJoueur_fk != 0
	GROUP BY dtnSuiviJoueur_fk";
foreach($conn->query($sql) as $count)
{
	$total[$count["dtnSuiviJoueur_fk"]] = $count["sum"];
}

$sql = "select * from $tbl_position";


switch($sesUser["idNiveauAcces_fk"]){
		
		case "2":
		if($sesUser["idPosition_fk"] != 0){
		$sql .= " where idPosition = ".$sesUser["idPosition_fk"];
		$affPosition=$sesUser["idPosition_fk"];
		
		}
		break;
}

$sql = "select * from $tbl_admin ";

switch($sesUser["idNiveauAcces_fk"]){
		case "1":
		$sql .= " where idPosition_fk = ".$affPosition;
		break;
		case "2":
		if($sesUser["idPosition_fk"] != 0){
		$sql .= " where idPosition_fk = ".$sesUser["idPosition_fk"];
		}
		else $sql .= " where idPosition_fk = ".$affPosition;
	
		break;
		default :
		exit;
		break;
}

$sql .= " AND affAdmin = 1 ";

$lstDtn = array();
foreach($conn->query($sql) as $lst){
	array_push($lstDtn, $lst);
}

$sql = "select * from $tbl_joueurs ,$tbl_position where affJoueur = 1 AND archiveJoueur = 0 AND joueurActif = 1 and ht_posteAssigne = idPosition ";

switch($sesUser["idNiveauAcces_fk"]){
		case "1":
		$sql .= " AND ht_posteAssigne = ".$affPosition;
		break;
		case "2":
		if($sesUser["idPosition_fk"] != 0){
		$sql .= " AND ht_posteAssigne = ".$sesUser["idPosition_fk"];
		}
		else $sql .= " AND ht_posteAssigne = ".$affPosition;
	
		break;
}

if($masque == 1) $sql.= " and dtnSuiviJoueur_fk = 0";

$sql .= " order by $ordre $sens";

//ecart sur variable listejoueurs appelé
$lstJoueurs = listJoueur($affArchive, $affPosition);
//$lstJoueursv2 = array();
foreach($conn->query($sql) as $lst){
array_push($lstJoueurs, $lst);
}
//

switch($affPosition){

	case "1":
		//gK
		$k = 1;
		$keeperColor = "#9999FF";
		break;
		
	case "2":
		// cD
		$d = 1;
		$defense = 1;
		$defenseColor = "#9999FF";
		break;
		
	case "3":
		// Wg
		$construction = 1;
		$constructionColor = "#CCCCCC";
		$ailier = 1;
		$ailierColor = "#9999FF";
		$defense = 1;
		$defenseColor = "#CCCCCC";
		$passe = 1;
		$passeColor = "#CCCCCC";
		
		$wing = 1;
		$wingoff = 1;
		$wingwtm = 1;

		break;
	
	case "4":
		//IM
		$m = 1;
		$moff = 1;
		$construction = 1;
		$constructionColor = "#9999FF";
		$defense = 1;
		$defenseColor = "#CCCCCC";
		$passe = 1;
		$passeColor = "#CCCCCC";
		break;
		
	case "5":
		// Fw
			
		$att = 1;
		$passe = 1;
		$passeColor = "#999999";
		$buteur = 1;
		$buteurColor = "#9999FF";
		break;
	
	default:
		$font = "<font color = black>";
		$$font = "</font>";
		break;
		
}

switch($sens){

case "ASC":
$tri = "Tri croissant";
break;

case "DESC":
$tri = "Tri decroissant";
break;
}
switch($sesUser["idNiveauAcces"]){
		case "1":
		require("../menu/menuAdmin.php");
		require("../menu/menuAdminGestion.php");
		break;
		
		case "2":
		require("../menu/menuSuperviseur.php");
		require("../menu/menuSuperviseurGestion.php");
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

?>
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<script language="JavaScript" src="menu_joueur.js"></script>
<style type="text/css">
<!--
.Style1 {color: #FFFFFF}
.Style3 {color: #FF9933}
.Style4 {color: #FF0000}

-->

<?php
switch($ordre){

case "nomJoueur":
$intitule = "identit&eacute;";
break;

case "ageJoueur":
$intitule = "age";
break;

case "idExperience_fk":
$intitule = "exp&eacute;rience";
break;

case "idLeader_fk":
$intitule = "temp&eacute;rament de chef";
break;

case "htms.value":
$intitule = "valeur htms";
break;

case "htms.potential":
$intitule = "potentiel htms";
break;


case "optionJoueur":
$intitule = "specialit&eacute;";
break;


case "idEndurance":
$intitule = "endurance";
break;


case "idGardien":
$intitule = "gardien";
break;

case "idDefense":
$intitule = "d&eacute;fense";
break;

case "idConstruction":
$intitule = "construction";
break;

case "idAilier":
$intitule = "ailier";
break;

case "idPasse":
$intitule = "passe";
break;

case "idButeur":
$intitule = "buteur";
break;

case "idPA":
$intitule = "coup franc";
break;

break;
}
?>

</style>
<body onLoad = "init();">
<br><br>
<center><h3><?=$tri?> par <?=$ordre?></h3></center>
<br>
<form name="form1" method="post" action="../form.php"> 
  <table width="100%" border="1" align="center" cellpadding="0" cellspacing="0" bgcolor="#000000">
    <tr> 
      <td > 
          <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#EFEFEF">
            <tr> 
              <td> <div align="center">Poste : 
                  <select name="menu1" onChange="MM_jumpMenu('parent',this,0)">
                    <?php
			  foreach($lstPosition as $l){			  
			  if($affPosition == $l["idPosition"]) $etat = "selected"; else $etat = "";
			  echo "<option value = attribution.php?masque=".$masque."&affPosition=".$l["idPosition"]." ".$etat." >".$l["intitulePosition"]."</option>";
			  
			  }
			  
			  
			  ?>
                  </select>
                </div></td>
              <td> <div align="center"><font color="#000000">Liste des joueurs</font></div></td>
              <td><div>
			  <?php
			  if($masque == 1){
			   $masque = 0;
			   $oldMasque = 1; 
			   $texte = "Afficher tous les joueurs";
			   }
			   else {
			   $masque = 1;
			   $oldMasque = 0; 
			   $texte = "Masquer les joueurs d&eacute;ja attribu&eacute;s";
			   }
			  ?>
			  <a href="?ordre=<?=$ordre?>&sens=<?=$sens?>&masque=<?=$masque?>&affPosition=<?=$affPosition?>"><?=$texte?></a>
			  </div></td>
			  </tr>
            <tr> 
              <td height="1" colspan="3" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
            </tr>
            <tr> 
              <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr bgcolor="#000000">
                  <td width="200" onClick="chgTri('nomJoueur','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')"><font color="#FFFFFF">Identit&eacute;</font></td>
                  <td width="40" rowspan="5"><div align="center"><span class="Style1">TSI</span></div></td>
                  <!-- largeur de la collone age pour les + de 99 jours par jojoje86 le 21/07/09-->
				  <td width="35" onClick="chgTri('ageJoueur','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">Age</font></div></td>
                  <td width="20" onClick="chgTri('idExperience_fk','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">Xp</font></div></td>
                  <td width="25" onClick="chgTri('idLeader_fk','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">TDC</font></div></td>
                  <td width="40" <!-- onClick="chgTri('TODO htms value')"> -->
                    <div align="center"><font color="#FFFFFF">Valeur HTMS</font></div></td>
                  <td width="40" <!-- onClick="chgTri('TODO htms potentiel')"> -->
                    <div align="center"><font color="#FFFFFF">Potentiel HTMS</font></div></td>
                  <td width="30"  onClick="chgTri('optionJoueur','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">Sp&eacute;</font></div></td>
                  <td width="30"onClick="chgTri('idEndurance','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">E</font></div></td>
                  <td width="30"onClick="chgTri('idGardien','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">G</font></div></td>
                  <td width="30" witdth = "20" onClick="chgTri('idDefense','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">D</font></div></td>
                  <td width="30" height="17"onClick="chgTri('idConstruction','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">C</font></div></td>
                  <td width="30"onClick="chgTri('idAilier','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">A</font></div></td>
                  <td width="30" witdth = "20" onClick="chgTri('idPasse','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">P</font></div></td>
                  <td width="30"onClick="chgTri('idButeur','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">B</font></div></td>
                  <td width="30" witdth = "20" onClick="chgTri('idPA','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">CF</font></div></td>
                    
                  
                  <td width="70">                   
                    <div align="center"><font color="#FFFFFF">Entra&icirc;nement</font></div></td>
                  <td width="90">                   
                    <div align="center"><font color="#FFFFFF">Dernier Match</font></div></td>
                  <td width="40">                   
                    <div align="center"><font color="#FFFFFF">Pos</font></div></td>
                  </tr>


<?php
	$lst = 1;

	if(is_array($lstJoueurs))
		foreach($lstJoueurs as $l){

			$infTraining = getEntrainement($l["idJoueur"]);
			  
			switch($lst){
			case 1:
				$bgcolor = "#EEEEEE";
				$lst = 0;
				break;
			case 0:
				$bgcolor = "white";
				$lst = 1;
				break;
			}

			$val = array($l["scoreGardien"],$l["scoreDefense"],$l["scoreAilier"],$l["scoreAilierOff"],$l["scoreAilierVersMilieu"],$l["scoreMilieu"],$l["scoreMilieuOff"],$l["scoreAttaquant"]);
			sort($val);
			$valMax =  $val[7];
			$val2 = $val[6];
			  
			$date = explode("-",$l["dateDerniereModifJoueur"]);
			$mkJoueur =  mktime(0,0,0,$date[1],$date[2],$date[0]); 
			$datesaisie = explode("-",$l["dateSaisieJoueur"]);
			$mkSaisieJoueur= mktime(0,0,0,$datesaisie[1],$datesaisie[2],$datesaisie[0]);
			if ($mkSaisieJoueur>$mkJoueur){
			 	$datemaj=$mkSaisieJoueur;
			}else{
			 	$datemaj=$mkJoueur;
			}
			
			$img_nb=0;
			if ($datemaj >$mkday -$huit){
			 	$img_nb=0;
			 	$strtiming="moins de 8 jours";	
			}else if ($datemaj >$mkday -$quinze){
			 	$img_nb=1;
			 	$strtiming="moins de 15 jours";
			}else if ($datemaj >$mkday -$trente){
			 	$img_nb=2;
			 	$strtiming="moins de 30 jours";
			 	
			}else if ($datemaj >$mkday -$twomonths){
			 	$img_nb=3;
			 	$strtiming="moins de 2 mois";
			 	
			}else if ($datemaj >$mkday -$fourmonths){
			 	$img_nb=4;
			 	$strtiming="moins de 4 mois";
			 
			}else{
			 		$img_nb=5;
			 	$strtiming="plus que 4 mois";
			}
			 
			// Date de la dernier modif de ce joueur
			$zealt=" Date dtn : ".$l["dateDerniereModifJoueur"].
					"<br> Date proprio : ".$l["dateSaisieJoueur"].
					"<br> [ Mis &agrave; jour il y a  ".round(($mkday - $datemaj)/(60*60*24) )." jours ]";
			 
			// HTMS du joueur    
            $ageetjours = ageetjour($l["datenaiss"]);
            $tabage = explode(" - ",$ageetjours);
            $htms = htmspoint($tabage[0], $tabage[1], $l["idGardien"], $l["idDefense"], $l["idConstruction"], $l["idAilier"], $l["idPasse"], $l["idButeur"], $l["idPA"]); 			  	
			
            // Entraînement du joueur
            $libelle_type_entrainement="-";

            
            // ID club HT
          	$sql3 = "select * from $tbl_clubs where idClubHT = ".$l["teamid"];
            $req3 = $conn->query($sql3);
            $ligne3 = $req3->fetch(PDO::FETCH_ASSOC);
            if (is_array($ligne3))
            extract($ligne3);
            
            // Extraction statut du joueur à la dernière MàJ (en vente ou non)
            $sql= "SELECT transferListed FROM $tbl_joueurs_histo
                   WHERE id_joueur_fk=".$l["idHattrickJoueur"]." 
                   ORDER BY date_histo DESC LIMIT 1";
            $req = $conn->query($sql);
            $ligne = $req->fetch(PDO::FETCH_ASSOC);
            if (is_array($ligne))
            extract($ligne);
            
            // Extraction rôle joueur au dernier match
            $id_role="0";
            $date_match="";
            $sql4= "SELECT * FROM $tbl_perf
                   WHERE id_joueur=".$l["idHattrickJoueur"]." 
                   ORDER BY date_match DESC LIMIT 1";
            $req4 = $conn->query($sql4);
            $ligne4 = $req4->fetch(PDO::FETCH_ASSOC);
            if (is_array($ligne4))
            extract($ligne4);
            $role=get_role_byID($id_role,null);
            $datedumatch=substr($date_match, 0, 10);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor = "<?=$bgcolor?>">

                      <td align="left" width="200" nowrap>&nbsp;<img src="../images/time_<?=$img_nb?>.gif" onmouseover="return escape('<?=$zealt?>')" >&nbsp;
                    <?php if (existAutorisationClub($idClubHT,null)==false) {?>
                      <img height="12" src="../images/non_autorise.JPG" title="Ce club n'a pas autoris&eacute; la DTN &agrave; acc&eacute;der &agrave; ses donn&eacute;es">
                    <?php } else {?>
                      <img height="12" src="../images/Autorise.PNG" title="Ce club a autoris&eacute; la DTN &agrave; acc&eacute;der &agrave; ses donn&eacute;es">
                    <?php }?>
                    <?php if ($transferListed==1) {?><img height="12" src="../images/enVente.JPG" title="Plac&eacute; sur la liste des transferts"><?php }?>
                    <a href ="<?=$url?>/joueurs/ficheDTN.php?id=<?=$l["idJoueur"]?>" class="bred1"> 
                      <b> 
                      <?=$l["prenomJoueur"]?> <?=$l["nomJoueur"]?>
					  <?php if (isset($l["surnomJoueur"])) echo " (".$l["surnomJoueur"].")"; ?>
                      </b> </a> 
                      
                    </td>                        
                    
                    <td width="1" bgcolor="#000000" ><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td width="40" <div align="right"><img src="../images/spacer.gif" width="1" height="1">
                    <?=$infTraining["valeurEnCours"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td width="35"><div align="center"> 
                        <?=$l["AgeAn"]."-".$l["AgeJour"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td width="20"> <div align="center"> 
                        <?=$l["idExperience_fk"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="25"> <div align="center"> 
                        <?=$l["idLeader_fk"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="40"> <div align="center"> 
                        <?=$htms["value"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div>
                   </td>
                    <td width="40"> <div align="center"> 
                        <?=$htms["potential"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30"> <div align="center"> 
                        <?=$specabbrevs[$l["optionJoueur"]]?>
                      </div></td>
                    <td width="2" rowspan="6" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td width="30" bgcolor="#CCCCCC" witdth = "20"> 
                      <div align="center"> 
                        <?=$l["idEndurance"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"> 
                        <img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30" witdth = "20"<?php if ($k==1) echo "bgcolor = $keeperColor";?>> <div align="center"> 
                        <?=$l["idGardien"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"> 
                        <img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30" witdth = "20" <?php if ($defense==1) echo "bgcolor = $defenseColor";?>> <div align="center"> 
                        <?=$l["idDefense"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"> 
                        <img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30" height="17" witdth = "20" <?php if ($construction==1) echo "bgcolor = $constructionColor";?>> <div align="center"> 
                        <?=$l["idConstruction"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"> 
                        <img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30" witdth = "20"<?php if ($ailier==1) echo "bgcolor = $ailierColor";?>> <div align="center"> 
                        <?=$l["idAilier"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1">
                        <img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30" witdth = "20" <?php if ($passe==1) echo "bgcolor = $passeColor";?>> <div align="center"> 
                        <?=$l["idPasse"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1">
                        <img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30" witdth = "20"<?php if ($buteur==1) echo "bgcolor = $buteurColor";?>> <div align="center"> 
                        <?=$l["idButeur"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1">
                        <img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30" witdth = "20"> <div align="center"> 
                        <?=$l["idPA"]?>
                      </div></td>
                    
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div>
                    <img src="../images/spacer.gif" width="1" height="1"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="70" ><div align="center"><?php echo $libelle_type_entrainement;?></div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div>
                    <img src="../images/spacer.gif" width="1" height="1"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="90" ><div align="center"><?php echo $datedumatch." (".$role["nom_role_abbrege"].")";?></div></td>
                    <td width="2" rowspan="6" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td width="40" witdth = "20"> <div align="center"> 
                        <?php
					

					 if($l["dtnSuiviJoueur_fk"] != 0){
						echo '<a href = ../form.php?ancienDTN='.$l["dtnSuiviJoueur_fk"].'&affPosition='.$affPosition.'&masque='.$masque.'&ordre='.$ordre.'&sens='.$sens.'&mode=annuleAssignationDTN&idJoueur='.$l["idJoueur"].' alt = "Supprimer cette assignation">X</a>';
					 }else					 {
					 
					 ?>
                        <input name="assigne[]" type="checkbox" id="assigne[]"  value="<?=$l["idJoueur"]?>">
                        <?php }?>
                      </div></td>
                  </tr>
                <?php
				}
				?>
          </table>
        </td>
    </tr>
  </table>
        </td>
    </tr>
  </table>
  <br>
  <table width="980" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="724"><div align="right"> 
          <input name="mode" type="hidden" id="mode2" value="assigneJoueurDTN">
          <input name="affPosition" type="hidden" id="mode2" value="<?=$affPosition?>">
          <input name="ordre" type="hidden" id="mode2" value="<?=$ordre?>">
          <input name="sens" type="hidden" id="mode2" value="<?=$sens?>">
          <input name="masque" type="hidden" id="mode2" value="<?=$oldMasque?>">
          Assigner les joueurs coch&eacute;s au DTN : </div></td>
      <td width="256"> <div align="right"> 
          <select name="idDtn" id="select">
            <?php
			foreach($lstDtn as $lstDtn){
				echo "<option value = ".$lstDtn["idAdmin"]." $etat >".$lstDtn["loginAdmin"]."";
				if (array_key_exists($lstDtn["idAdmin"], $total)) {
					if($total[$lstDtn["idAdmin"]] != 0){
			  			echo " (".$total[$lstDtn["idAdmin"]].")";
			  		}
				}
			  
				"</option>";
			}
			  
			?>
<!--          <option value="309">Paros59</option>-->
          </select>
          <input type="submit" name="Submit" value="Assigner">
        </div></td>
    </tr>
  </table>
  <br>
</form>

<table width="450"  border="0" align="center" cellspacing=0 >
    <tr>
      <td colspan=5 width="450" ><center>L&eacute;gende : </center></td>
      
    </tr>
    <tr>
      <td><img src="../images/time_0.gif"></td>
      <td>Joueur mis &agrave; jour r&eacute;cemment </td>
      <td width="1" bgcolor="#000000"></td>
      <td>&nbsp;<img src="../images/time_3.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 30 jours </td>
    </tr>
    <tr>
      <td><img src="../images/time_1.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 8 jours </td>
      <td width="1" bgcolor="#000000"></td>
      <td>&nbsp;<img src="../images/time_4.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 60 jours </td>
    </tr>
    <tr>
      <td><img src="../images/time_2.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 15 jours </td>
      <td width="1" bgcolor="#000000"></td>
      <td>&nbsp;<img src="../images/time_5.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 120 jours </td>
    </tr>
  </table>

<script language="JavaScript" type="text/javascript" src="../includes/javascript/tooltips.js"></script>

</body>
<?php  deconnect(); ?>
</html>
