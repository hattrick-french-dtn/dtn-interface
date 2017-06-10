<?php 
require_once("../includes/head.inc.php");
require("../includes/langue.inc.php");
require("../includes/serviceListesDiverses.php");

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

$lstPosition = listPosition();


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

$lstJoueurs = array();
while($conn->query($sql) as $lst){
	array_push($lstJoueurs, $lst);
}


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
		require("../menu/menuDTNGestion.php");
		break;
		
	case "4":
		require("../menu/menuCoach.php");
		break;
		
	default;
		break;


}

?>
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->


function init()
{
var scrollPos = "<?=$scrollPos?>";
document.body.scrollTop = scrollPos;

}//-->
</script>
<style type="text/css">
<!--
.Style1 {color: #FFFFFF}
.Style3 {color: #FF9933}
.Style4 {color: #FF0000}

-->
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
              <td><div align="center">
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
            <tr bgcolor="#000000"> 
              <td colspan="3">
              <table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#000000">
                <tr bgcolor="#000000">
                  <td  onClick="chgTri('nomJoueur','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')"><font color="#FFFFFF">Identit&eacute;</font></td>
                  
                  <td width="24" onClick="chgTri('ageJoueur','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">Age</font></div></td>
                  <td width="23" onClick="chgTri('idExperience_fk','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">Xp</font></div></td>
                  <td width="26" onClick="chgTri('idLeader_fk','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">Ld</font></div></td>
                  <td width="26"  onClick="chgTri('optionJoueur','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">Sp&eacute;</font></div></td>
                  <td width="31" onClick="chgTri('idEndurance','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')" >
                    <div align="center"><font color="#FFFFFF">Sta</font></div></td>
                  <td width="29" onClick="chgTri('idConstruction','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')" >
                    <div align="center"><font color="#FFFFFF">Pla</font></div></td>
                  <td width="32" onClick="chgTri('idAilier','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')" >
                    <div align="center"><font color="#FFFFFF">Wn</font></div></td>
                  <td width="33" onClick="chgTri('idButeur','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')" >
                    <div align="center"><font color="#FFFFFF">Sco</font></div></td>
                  <td width="30" onClick="chgTri('idGardien','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')" >
                    <div align="center"><font color="#FFFFFF">Kee</font></div></td>
                  <td width="30" onClick="chgTri('idPasse','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">Pas</font></div></td>
                  <td width="29" onClick="chgTri('idDefense','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')">
                  	<div align="center"><font color="#FFFFFF">Def</font></div></td>
                  <td width="29" onClick="chgTri('idPA','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')">
                  	<div align="center"><font color="#FFFFFF">Set</font></div></td>
                  <td width="3" bgcolor="#FFFFDD">&nbsp;</td>
<?php
	switch($affPosition){
		case "1"://gK
		?>
               <td width="50" onClick="chgTri('scoreGardien','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">gK</font></div></td>
           <?php
		break;
		case "2":// cD
		?>
		            <td width="50" onClick="chgTri('scoreDefense','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">cD</font></div></td>
                    <td width="50" onClick="chgTri('scoreDefCentralOff','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">cD off</font></div></td>
                    <td width="50" onClick="chgTri('scoreDefLat','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">wB</font></div></td>
                    <td width="50" onClick="chgTri('scoreDefLatOff','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">wB off</font></div></td>
		<?php
		break;
		case "3":		// Wg
		?>
		            <td width="50" onClick="chgTri('scoreAilier','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">Wg</font></div></td>
                    <td width="50" nowrap onClick="chgTri('scoreAilierVersMilieu','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">Wg towards</font></div></td>
                    <td width="50" onClick="chgTri('scoreAilierOff','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">Wg off</font></div></td>
		<?php
		break;
		case "4":		//IM 
		?>
                    <td width="50" onClick="chgTri('scoreMilieuDef','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">iM def</font></div></td>
                    <td width="50"  onClick="chgTri('scoreMilieu','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">iM</font></div></td>
                    <td width="50" onClick="chgTri('scoreMilieuOff','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">iM off</font></div></td>
		<?php
		break;
		case "5":		// Fw
		?>
                    <td width="50" onClick="chgTri('scoreAttaquantDef','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">Fw def</font></div></td>
                    <td width="50" onClick="chgTri('scoreAttaquant','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">Fw</font></div></td>
		<?php				
		break;
		default: ?>
	               <td width="50" onClick="chgTri('scoreGardien','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">gK</font></div></td>
                    <td width="50" onClick="chgTri('scoreDefense','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">cD</font></div></td>
                    <td width="50" onClick="chgTri('scoreMilieu','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">iM</font></div></td>
            		<td width="50" onClick="chgTri('scoreAilierOff','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">Wg off</font></div></td>                      
                    <td width="50" onClick="chgTri('scoreAttaquant','<?=$sens?>','<?=$oldMasque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">Fw</font></div></td>
		<?php
		break;
		}
	?>
                  
                  
                  <td width="34"><div align="center"><font color="#FFFFFF">Pos</font></div></td>
                </tr>
                <?php
			  foreach($lstJoueurs as $l){

 $val = array($l["scoreGardien"],$l["scoreDefense"],$l["scoreAilierDef"],$l["scoreAilierOff"],$l["scoreWtm"],$l["scoreMilieu"],$l["scoreMilieuOff"],$l["scoreAttaquant"]);
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
			 
			 			  	
			  
			  
			  ?>
                <tr bgcolor="#FFFFFF" align="right"> 
                    <td align="left" nowrap>&nbsp;<img src="../images/time_<?=$img_nb?>.gif" onMouseOver="return escape('<?=$zealt?>')" >&nbsp;<a href ="<?=$url?>/joueurs/fiche.php?id=<?=$l["idJoueur"]?>" class=bred1>
                    <b><?=strtolower($l["nomJoueur"])?></b>&nbsp;<?=strtolower($l["prenomJoueur"])?></a>
                    
                    </td>
                    
                    <td width="25"><div align="center"> 
                      <?=$l["ageJoueur"]?>
</div></td>
                    
                    <td width="20"> <div align="center"> 
                        <?=$l["idExperience_fk"]?>
                      </div></td>
                    
                    <td width="26"> <div align="center"> 
                         <?=$l["idLeader_fk"]?>
                      </div></td>
                    
                    
                    <td width="24"> <div align="center"> 
                        <?=$specabbrevs[$l["optionJoueur"]]?>
                      </div></td>
                    
                    <td width="30" bgcolor="#CCCCCC" witdth = "20"> <div align="center"> 
                        <?=$l["idEndurance"]?>
                      </div></td>
                    
                    <td width="30" height="17" witdth = "20" <?php if ($construction=1) echo "bgcolor = $constructionColor";?>> 
                      <div align="center"> 
                        <?=$l["idConstruction"]?>
                      </div></td>
                    
                    <td width="30" witdth = "20"<?php if ($ailier=1) echo "bgcolor = $ailierColor";?>> 
                      <div align="center"> 
                        <?=$l["idAilier"]?>
                      </div></td>
                    <td width="30" witdth = "20"<?php if ($buteur=1) echo "bgcolor = $buteurColor";?>> 
                      <div align="center"> 
                        <?=$l["idButeur"]?>
                      </div></td>
                    <td width="30" witdth = "20"<?php if ($keeper=1) echo "bgcolor = $keeperColor";?>> 
                      <div align="center"> 
                        <?=$l["idGardien"]?>
                      </div></td>
                    <td width="30" witdth = "20" <?php if ($passe=1) echo "bgcolor = $passeColor";?>> 
                      <div align="center"> 
                        <?=$l["idPasse"]?>
                      </div></td>
                    <td width="30" witdth = "20" <?php if ($defense=1) echo "bgcolor = $defenseColor";?>> 
                      <div align="center"> 
                        <?=$l["idDefense"]?>
                      </div></td>
                    <td width="30" witdth = "20"> <div align="center"> 
                        <?=$l["idPA"]?>
                      </div></td>
                                      <td width="3" bgcolor="#FFFFDD">&nbsp; 
                      </td>


<?php
	switch($affPosition){
		case "1"://gK
		?>
               <td >
                <?=$l["scoreGardien"];?>
               </td>
           <?php
		break;
		case "2":// cD
		?>
               <td >
                <?=$l["scoreDefense"];?>
               </td>
               <td >
                <?=$l["scoreDefCentralOff"];?>
               </td>
               <td >
                <?=$l["scoreDefLat"];?>
               </td>
               <td >
                <?=$l["scoreDefLatOff"];?>
               </td>
		<?php
		break;
		case "3":		// Wg
		?>
               <td >
                <?=$l["scoreAilier"];?>
               </td>
               <td >
                <?=$l["scoreAilierVersMilieu"];?>
               </td>
               <td >
                <?=$l["scoreAilierOff"];?>
               </td>
		<?php
		break;
		case "4":		//IM 
		?>
               <td >
                <?=$l["scoreMilieuDef"];?>
               </td>
               <td >
                <?=$l["scoreMilieu"];?>
               </td>
               <td >
                <?=$l["scoreMilieuOff"];?>
               </td>
		<?php
		break;
		case "5":		// Fw
		?>
               <td >
                <?=$l["scoreAttaquantDef"];?>
               </td>
               <td >
                <?=$l["scoreAttaquant"];?>
               </td>
		<?php				
		break;
		default: ?>
               <td >
                <?=$l["scoreGardien"];?>
               </td>
               <td >
                <?=$l["scoreDefense"];?>
               </td>
               <td >
                <?=$l["scoreMilieu"];?>
               </td>
               <td >
                <?=$l["scoreAilierOff"];?>
               </td>
               <td >
                <?=$l["scoreAttaquant"];?>
               </td>
		<?php
		break;
		}
	?>

                    
                    <td width="30" witdth = "20"><div align="center"> 
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
			  
			  
			  
			  if($total[$lstDtn["idAdmin"]] != 0){
			  
			  echo " (".$total[$lstDtn["idAdmin"]].")";
			  
			  }
			  
			  
			  "</option>";
			  }
			  
			  
			  ?>
          <option value="309">Paros59</option>
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
</html>