<?php 
require_once("../includes/head.inc.php");
require("../includes/serviceJoueur.php");
require("../includes/serviceDTN.php");
if(!$sesUser["idAdmin"])
{
	header("location: index.php?ErrorMsg=Session Expire");
}
if(!isset($ordre)) $ordre = "nomJoueur";
if(!isset($sens)) $sens = "ASC";
if(!isset($lang)) $lang = "FR";
if(!isset($masque)) $masque = "";
if(!isset($affPosition)) $affPosition = 0;
$defautFiltre="";
if ($masque=="on")   $defautFiltre = "CHECKED";
	
require("../includes/langue.inc.php");

//
$sql = "select * from $tbl_position";


switch($sesUser["idNiveauAcces_fk"]){
	case "1":
		break;
	case "2":
		if($sesUser["idPosition_fk"] != 0){
			$sql .= " where idPosition = ".$sesUser["idPosition_fk"];
		}
		break;
}

$lstPosition = construitListe($sql,$tbl_position);
$sql = "select * from $tbl_position";

$lstPosition2 = construitListe($sql,$tbl_position);
$sql = "select * from $tbl_joueurs ";

if(isset($affPosition) && $affPosition != 0) $sql .= " left join $tbl_position on ht_posteAssigne = idPosition where ht_posteAssigne = $affPosition ";
else
{
$sql .= " where ht_posteAssigne = 0";
}
if ($masque!="on"){

if ($sesUser["selection"]== "U20"){
	$sql .= " and ageJoueur<='20' "; 
}

if ($sesUser["selection"]== "A"){
	$sql .= " and ageJoueur>='20' "; 
}
}
$sql .= " and affJoueur = 1  order by $ordre $sens";

$reqJoueurs = $conn->query($sql);


switch($affPosition){

	case "1":
		//gK
		$k = 1;
		$keeperColor = "#999999";
		break;
		
	case "2":
		// cD
		$d = 1;
		$defense = 1;
		$defenseColor = "#999999";
		break;
		
	case "3":
		// Wg
		$construction = 1;
		$constructionColor = "#CCCCCC";
		$ailier = 1;
		$ailierColor = "#999999";
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
		$constructionColor = "#999999";
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
		$buteurColor = "#999999";
		break;
	
	default:
		$font = "<font color = black>";
		$ffont = "</font>";
		break;
		
}

switch($sesUser["idNiveauAcces"]){
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

?><title>selection des internationaux</title>
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<script language="JavaScript">
  <!--
  function reloadList()
  {
	  document.changeListe.submit();
  }
 // -->
 </script>
<style type="text/css">
<!--
.Style1 {color: #FFFFFF}
.Style3 {color: #FF9933}
.Style4 {color: #FF0000}
-->
</style>
<?php
switch($sens){

case "ASC":
$tri = "Tri croissant";
break;

case "DESC":
$tri = "Tri decroissant";
break;
}

switch($ordre){

case "nomJoueur":
$intitule = "identite";
break;

case "ageJoueur":
$intitule = "age";
break;

case "idExperience_fk":
$intitule = "experience";
break;

case "idLeader_fk":
$intitule = "leadership";
break;

case "idCaractere_fk":
$intitule = "popularite";
break;


case "idAggre_fk":
$intitule = "agressivite";
break;

case "idHonnetete_fk":
$intitule = "honnetete";
break;

case "optionJoueur":
$intitule = "specialite";
break;


case "idEndurance":
$intitule = "endurance";
break;


case "idConstruction":
$intitule = "construction";
break;

case "idAilier":
$intitule = "ailier";
break;
case "idButeur":
$intitule = "buteur";
break;

case "idGardien":
$intitule = "gardien";
break;

case "idPasse":
$intitule = "passe";
break;

case "idDefense":
$intitule = "defense";
break;

case "idPA":
$intitule = "coup de pieds arrete";
break;

case "scoreGardien":
$intitule = "score Gardien";
break;

case "scoreDefense":
$intitule = "score Gardien";
break;
case "scoreAilierOff":
$intitule = "score ailier offensif";
break;

case "scoreMilieu":
$intitule = "score milieu";
break;
case "scoreAttaquant":
$intitule = "score attaquant";
break;
}
?><a name="toplink"></a>
<ul type="square"><?php
if ($masque=="on"){ 
?>
<li>Tous les joueurs figurent dans la liste (pas de limitation d'&acirc;ge)
<?php }else{
	if ($sesUser["selection"]== "U20"){
	?><li> Par d&eacute;faut seuls les joueurs &lt;= 20 ans sont affich&eacute;s pour le Coach U20<?php
	}
	if ($sesUser["selection"]== "A"){
	?><li> Par d&eacute;faut seuls les joueurs &gt;= 20 ans sont affich&eacute;s pour le Coach A<?php
	}
}
?>
<li><?=$tri?> par <?=$intitule?>
<li>
<?php
	switch($affPosition){
		case "1"://gK
			    $coeff = getCoeffSelectionneur(1);
				if($coeff["useit"] == 1){
					?>
					<i>Note Gk de vos pr&eacute;f&eacute;rences</i><br><?php
				}else{?>
					<i>Note Gk de Lloko</i><br><?php
				}
		break;
		case "2":// cD
				$coeff = getCoeffSelectionneur(2);
				if($coeff["useit"] == 1){
					?>
					<i>Note cD normal de vos pr&eacute;f&eacute;rences + Lloko</i><br><?php
				}else{?>
					<i>Notes D&eacute;fense de Lloko</i><br><?php
				}
		break;
		case "3":		// Wg
						$coeff = getCoeffSelectionneur(3);
				if($coeff["useit"] == 1){
					?>
					<i>Note Wg Off de vos pr&eacute;f&eacute;rences + Lloko </i><br><?php
				}else{?>
					<i>Notes Ailier de Lloko</i><br><?php
				}
		break;
		case "4":		//IM
				$coeff = getCoeffSelectionneur(4);
				if($coeff["useit"] == 1){
					?>
					<i>Note iM normal de vos pr&eacute;f&eacute;rences + Lloko</i><br><?php
				}else{?>
					<i>Notes Milieu de Lloko</i><br><?php
				}
		break;
		case "5":		// Fw
				$coeff = getCoeffSelectionneur(5);
				if($coeff["useit"] == 1){
					?>
					<i>Note Fw de vos pr&eacute;f&eacute;rences + Lloko</i><br><?php
				}else{?>
					<i>Notes Attaque de Lloko</i><br><?php
				}
		break;
		default: 
			    $coeff = getCoeffSelectionneur(1);
				if($coeff["useit"] == 1){
					?>
					<i>Note gK de vos pr&eacute;f&eacute;rences</i><br><?php
				}else{?>
					<i>Note gK de Lloko</i><br><?php
				}
				$coeff = getCoeffSelectionneur(2);
				if($coeff["useit"] == 1){
					?>
					<li><i>Note cD normal de vos pr&eacute;f&eacute;rences</i><br><?php
				}else{?>
					<li><i>Note cD normal de Lloko</i><br><?php
				}
				$coeff = getCoeffSelectionneur(3);
				if($coeff["useit"] == 1){
					?>
					<li><i>Note Wg off de vos pr&eacute;f&eacute;rences</i><br><?php
				}else{?>
					<li><i>Note Wg off de Lloko</i><br><?php
				}
				$coeff = getCoeffSelectionneur(4);
				if($coeff["useit"] == 1){
					?>
					<li><i>Note iM normal de vos pr&eacute;f&eacute;rences</i><br><?php
				}else{?>
					<li><i>Note iM normal de Lloko</i><br><?php
				}
				$coeff = getCoeffSelectionneur(5);
				if($coeff["useit"] == 1){
					?>
					<li><i>Note Fw de vos pr&eacute;f&eacute;rences</i><br><?php
				}else{?>
					<li><i>Note Fw de Lloko</i><br><?php
				}
		break;
		}
	?>
</ul>
<br>  
<table width="90%" align="center" cellpadding="0" cellspacing="0"  bordercolor="#000000">
    <tr> 
      <td>
<form name="changeListe" method="post" action="listeInternational.php#toplink">
      
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td width="50%" > <div align="center">Poste : 
                  <select name="affPosition" onChange="reloadList()">
			<option value=0>Liste des non assignes</option>
			  <?php
			  for($i=0;$i<count($lstPosition);$i++){
			  if($affPosition == $lstPosition[$i]["idPosition"]) $etat = "selected"; else $etat = "";
			  echo "<option value =".$lstPosition[$i]["idPosition"]." $etat >".$lstPosition[$i]["intitulePosition"]."</option>";
			  
			  }
			  ?>
              </select>
                </div></td>
<td>
             Montrer tous les joueurs :  <input name="masque" type="checkbox"  onChange="reloadList()" <?=$defautFiltre?>> 
</td>
            </tr>
       </table>
</form>       
       
       </td>
       </tr>
       <tr>
<form name="form1" method="post" action="../form.php">
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
           <tr bgcolor="#000000">
                  <td width="213" onClick="chgTri('nomJoueur','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"><font color="#FFFFFF">Identit&eacute;</font></td>
                  <td width="82"><div align="right"><span class="Style1">TSI</span></div></td>
                  <td width="25" onClick="chgTri('ageJoueur','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"><div align="center"><font color="#FFFFFF">Age</font></div></td>
                  <td width="22" onClick="chgTri('idExperience_fk','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"><div align="center"><font color="#FFFFFF">Xp</font></div></td>
                  <td width="24" onClick="chgTri('idLeader_fk','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"><div align="center"><font color="#FFFFFF">Ld</font></div></td>
                  <td width="32" onClick="chgTri('optionJoueur','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"><div align="center"><font color="#FFFFFF">Sp&eacute;</font></div></td>
                  <td width="31" onClick="chgTri('idEndurance','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"><div align="center"><font color="#FFFFFF">Sta</font></div></td>
                  <td width="30" onClick="chgTri('idConstruction','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"><div align="center"><font color="#FFFFFF">Pla</font></div></td>
                  <td width="37" onClick="chgTri('idAilier','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"><div align="center"><font color="#FFFFFF">Wn</font></div></td>
                  <td width="33" onClick="chgTri('idButeur','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"><div align="center"><font color="#FFFFFF">Sco</font></div></td>
                  <td width="30" onClick="chgTri('idGardien','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"><div align="center"><font color="#FFFFFF">Kee</font></div></td>
                  <td width="30" onClick="chgTri('idPasse','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"><div align="center"><font color="#FFFFFF">Pas</font></div></td>
                  <td width="29" onClick="chgTri('idDefense','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"><div align="center"><font color="#FFFFFF">Def</font></div></td>
                  <td width="29" onClick="chgTri('idPA','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"><div align="center"><font color="#FFFFFF">Set</font></div></td>
                  <td width="3" bgcolor="#FFFFDD"> 
                      &nbsp;</td>
<?php
	switch($affPosition){
		case "1"://gK
		?>
               <td width="50" onClick="chgTri('scoreGardien','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="right"><font color="#FFFFFF">gK</font></div></td>
           <?php
		break;
		case "2":// cD
		?>
		            <td width="50" onClick="chgTri('scoreDefense','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="right"><font color="#FFFFFF">cD</font></div></td>
                    <td width="50" onClick="chgTri('scoreDefCentralOff','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="right"><font color="#FFFFFF">cD off</font></div></td>
                    <td width="50" onClick="chgTri('scoreDefLat','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="right"><font color="#FFFFFF">wB</font></div></td>
                    <td width="50" onClick="chgTri('scoreDefLatOff','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="right"><font color="#FFFFFF">wB off</font></div></td>
		<?php
		break;
		case "3":		// Wg
		?>
		            <td width="50" onClick="chgTri('scoreAilier','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="right"><font color="#FFFFFF">Wg</font></div></td>
                    <td width="50" nowrap onClick="chgTri('scoreAilierVersMilieu','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="right"><font color="#FFFFFF">Wg towards</font></div></td>
                    <td width="50" onClick="chgTri('scoreAilierOff','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="right"><font color="#FFFFFF">Wg off</font></div></td>
		<?php
		break;
		case "4":		//IM 
		?>
                    <td width="50" onClick="chgTri('scoreMilieuDef','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="right"><font color="#FFFFFF">iM def</font></div></td>
                    <td width="50"  onClick="chgTri('scoreMilieu','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="right"><font color="#FFFFFF">iM</font></div></td>
                    <td width="50" onClick="chgTri('scoreMilieuOff','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="right"><font color="#FFFFFF">iM off</font></div></td>
		<?php
		break;
		case "5":		// Fw
		?>
                    <td width="50" onClick="chgTri('scoreAttaquantDef','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="right"><font color="#FFFFFF">Fw def</font></div></td>
                    <td width="50" onClick="chgTri('scoreAttaquant','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="right"><font color="#FFFFFF">Fw</font></div></td>
		<?php				
		break;
		default: ?>
	               <td width="50" onClick="chgTri('scoreGardien','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="right"><font color="#FFFFFF">gK</font></div></td>
                    <td width="50" onClick="chgTri('scoreDefense','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="right"><font color="#FFFFFF">cD</font></div></td>
            		<td width="50" onClick="chgTri('scoreAilierOff','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="right"><font color="#FFFFFF">Wg off</font></div></td>                      
                    <td width="50" onClick="chgTri('scoreMilieu','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="right"><font color="#FFFFFF">iM</font></div></td>
                    <td width="50" onClick="chgTri('scoreAttaquant','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="right"><font color="#FFFFFF">Fw</font></div></td>
		<?php
		break;
		}
	?>
                  <td width="43" ><div align="center"><font color="#FFFFFF">Sel.</font></div></td>
            </tr>
                
	<?php
	$lst = 1;
			 
	foreach($reqJoueurs as $lstJoueurs){
			
			  
		$infTraining = getEntrainement($lstJoueurs["idJoueur"]);
			  
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
			
		




		$val = array($lstJoueurs["scoreGardien"],$lstJoueurs["scoreDefense"],$lstJoueurs["scoreAilier"],$lstJoueurs["scoreAilierOff"],$lstJoueurs["scoreAilierVersMilieu"],$lstJoueurs["scoreMilieu"],$lstJoueurs["scoreMilieuOff"],$lstJoueurs["scoreAttaquant"]);
		sort($val);
		$valMax =  $val[7];
		$val2 = $val[6];
			  
		$class = "#";
		$quinze = 60 * 60 * 24 * 15;
		$trente = 60 * 60 * 24 * 30;


		$date = explode("-",$lstJoueurs["dateDerniereModifJoueur"]);
			 
		// Date de la dernier modif de ce joueur
		$mkJoueur =  mktime(0,0,0,$date[1],$date[2],$date[0]); 

		// Date du jour
		$mkDay = mktime(0,0,0,date('m'), date('d'),date('Y'));
		$d1 =  $mkDay - $quinze;
		$d2 =  $mkDay - $trente;

		if($mkJoueur >  $d1) $class= "#"; 
		else if($mkJoueur > $d2 && $mkJoueur < $d1 ) $class = "style3";	
		else if($mkJoueur < $d2) $class = "style4";
			 
			 
//		if($settings["useit"] == 1){
//			$lstJoueurs["scoreGardien"] = 5;
//		}

	?>
	  <tr bgcolor = "<?=$bgcolor?>" align="right">  
                    <td align="left" >&nbsp;<a href ="<?=$url?>/joueurs/fiche.php?id=<?=$lstJoueurs["idJoueur"]?>" class=<?=$class?>>
                    <span class=<?=$class?>><b><?=strtolower($lstJoueurs["prenomJoueur"])?> <?=strtolower($lstJoueurs["nomJoueur"])?></b>
                    </span></a></td>
                    
                    <td >
                    <?=$infTraining["valeurEnCours"]?></td>
                    
                    <td width="25"><div align="center"> 
                        <?=$lstJoueurs["ageJoueur"]?>
                      </div></td>
                    
                    <td width="20"> <div align="center"> 
                        <?=$lstJoueurs["idExperience_fk"]?>
                      </div></td>
                    
                    <td width="26"> <div align="center"> 
                        <?=$lstJoueurs["idLeader_fk"]?>
                      </div></td>
                    
                    <td width="30"> <div align="center"> 
                        <?=$specabbrevs[$lstJoueurs["optionJoueur"]]?>
                      </div></td>
                    
                    <td width="30" bgcolor="#CCCCCC" > 
                      <div align="center"> 
                        <?=$lstJoueurs["idEndurance"]?>
                      </div></td>
                     
                    
                    <td width="30"  <?php if ($construction==1) echo "bgcolor = $constructionColor";?>> <div align="center"> 
                        <?=$lstJoueurs["idConstruction"]?>
                      </div></td>
                    
                    <td width="40" <?php if ($ailier==1) echo "bgcolor = $ailierColor";?>> <div align="center"> 
                        <?=$lstJoueurs["idAilier"]?>
                      </div></td>
                    
                    <td width="30" <?php if ($buteur==1) echo "bgcolor = $buteurColor";?>> <div align="center"> 
                        <?=$lstJoueurs["idButeur"]?>
                      </div></td>
                    
                    <td width="30" <?php if ($k==1) echo "bgcolor = $keeperColor";?>> <div align="center"> 
                        <?=$lstJoueurs["idGardien"]?>
                      </div></td>
                    
                    <td width="30"  <?php if ($passe==1) echo "bgcolor = $passeColor";?>> <div align="center"> 
                        <?=$lstJoueurs["idPasse"]?>
                      </div></td>
                    
                    <td width="30"  <?php if ($defense==1) echo "bgcolor = $defenseColor";?>> <div align="center"> 
                        <?=$lstJoueurs["idDefense"]?>
                      </div></td>
                    
                    <td width="30" > <div align="center"> 
                        <?=$lstJoueurs["idPA"]?>
                      </div></td>
                    <td width="3" bgcolor="#FFFFDD"> 
                      &nbsp;</td>
                    
<?php

$carac["endurance"] = $lstJoueurs["idEndurance"];
$carac["construction"] = $lstJoueurs["idConstruction"];
$carac["ailier"] = $lstJoueurs["idAilier"];
$carac["buteur"] = $lstJoueurs["idButeur"];
$carac["gardien"] = $lstJoueurs["idGardien"];
$carac["passe"] = $lstJoueurs["idPasse"];
$carac["defense"] = $lstJoueurs["idDefense"];
$carac["xp"] = $lstJoueurs["idExperience_fk"];


$semaine["construction"] = $infTraining["nbSemaineConstruction"];
$semaine["ailier"] = $infTraining["nbSemaineAilier"];
$semaine["buteur"] = $infTraining["nbSemaineButeur"];
$semaine["gardien"] = $infTraining["nbSemaineGardien"];
$semaine["passe"] = $infTraining["nbSemainePasses"];
$semaine["defense"] = $infTraining["nbSemaineDefense"];
$semaine["coupfranc"] = $infTraining["nbSemaineCoupFranc"];


	switch($affPosition){
		case "1"://gK
		      ?><td><?php
			  $coeff = getCoeffSelectionneur(1);
				if($coeff["useit"] == 1){
					
					$score = calculNoteDynamique($carac,$semaine,$coeff);
					echo $score;
				   } else{
						echo $lstJoueurs["scoreGardien"];
				   }
				?></td><?php
		break;
		case "2":// cD

		      ?><td><?php
			  $coeff = getCoeffSelectionneur(2);
				if($coeff["useit"] == 1){
					
					$score = calculNoteDynamique($carac,$semaine,$coeff);
					echo $score;
				   } else{
						echo $lstJoueurs["scoreDefense"];
				   }
				?></td><td>
                <?=$lstJoueurs["scoreDefCentralOff"];?>
               </td><td>
                <?=$lstJoueurs["scoreDefLat"];?>
               </td><td>
                <?=$lstJoueurs["scoreDefLatOff"];?>
               </td>
		<?php
		break;
		case "3":		// Wg
		?>
               <td >
                <?=$lstJoueurs["scoreAilier"];?>
               </td><td >
                <?=$lstJoueurs["scoreAilierVersMilieu"];?>
               </td><td><?php
			  $coeff = getCoeffSelectionneur(3);
				if($coeff["useit"] == 1){
					
					$score = calculNoteDynamique($carac,$semaine,$coeff);
					echo $score;
				   } else{
						echo $lstJoueurs["scoreAilierOff"];
				   }
				?></td>
		<?php
		break;
		case "4":		//IM 
		?>
               <td >
                <?=$lstJoueurs["scoreMilieuDef"];?>
               </td><td>
               <?php
			  $coeff = getCoeffSelectionneur(4);
				if($coeff["useit"] == 1){
					
					$score = calculNoteDynamique($carac,$semaine,$coeff);
					echo $score;
				   } else{
						echo $lstJoueurs["scoreMilieu"];
				   }
				?></td><td>
                <?=$lstJoueurs["scoreMilieuOff"];?>
               </td>
		<?php
		break;
		case "5":		// Fw
		?>
               <td >
                <?=$lstJoueurs["scoreAttaquantDef"];?>
               </td>
               <td >
               <?php
			  $coeff = getCoeffSelectionneur(5);
				if($coeff["useit"] == 1){
					
					$score = calculNoteDynamique($carac,$semaine,$coeff);
					echo $score;
				   } else{
						echo $lstJoueurs["scoreAttaquant"];
				   }
				?></td><?php				
		break;
		default: ?>
		
                    <td width="40" > 
                      <?php
			  $coeff = getCoeffSelectionneur(1);
				if($coeff["useit"] == 1){
					
					$score = calculNoteDynamique($carac,$semaine,$coeff);
				echo $score;
				   } else{
				   
					echo $lstJoueurs["scoreGardien"];
				}
					  ?></td>
                    <td width="40" > 
                      <?php
       		  $coeff = getCoeffSelectionneur(2);
				if($coeff["useit"] == 1){
					
					$score = calculNoteDynamique($carac,$semaine,$coeff);
				echo $score;
				   } else{
				   
					echo $lstJoueurs["scoreDefense"];
				}

					  ?>
                      </td>
                <td width="40" >  
                      <?php

			  $coeff = getCoeffSelectionneur(3);
				if($coeff["useit"] == 1){
					
					$score = calculNoteDynamique($carac,$semaine,$coeff);
				echo $score;
				   } else{
				   
					echo $lstJoueurs["scoreAilierOff"];
				}
					  ?>
                      </td>		
                    <td width="40" > 
<?php

			  $coeff = getCoeffSelectionneur(4);
				if($coeff["useit"] == 1){
					
					$score = calculNoteDynamique($carac,$semaine,$coeff);
				echo $score;
				   } else{
				   
					echo $lstJoueurs["scoreMilieu"];
				}
					  ?>     
                      </td>
		<td width="40" >  
<?php

			  $coeff = getCoeffSelectionneur(5);
				if($coeff["useit"] == 1){
					
					$score = calculNoteDynamique($carac,$semaine,$coeff);
					echo $score;
				   } else{
				   
					echo $lstJoueurs["scoreAttaquant"];
				}
					  ?>     

                      </td>
		
		<?php
		break;
		}
	?>
                    
                            <td width="30" > <div align="center"> 
                       
                       <?php
					   $verifSelection = verifSelection($lstJoueurs["idJoueur"]);
					  if($verifSelection != ""){
					  ?>
					  
					  <?php
					  if($verifSelection == $sesUser["selection"]){
					  ?>
					  <a href = "../form.php?affPosition=<?=$affPosition?>&masque=<?=$masque?>&ordre=<?=$ordre?>&sens=<?=$sens?>&mode=supprAssigneSelection&idJoueur=<?=$lstJoueurs["idJoueur"]?>"><img src="../images/fr.gif" width="19" height="12" alt="<?=$verifSelection?>" border="0"></a>
					  <?php 
					  } else{
					  ?>
					    <img src="../images/fr.gif" width="19" height="12" alt="<?=$verifSelection?>">
					  <?php
					  }
					  }else{
					   ?> 
                        <input name="assigne[]" type="checkbox" id="assigne[]"  value="<?=$lstJoueurs["idJoueur"]?>"> 
						<?php
						}?>
                      </div></td>
                  </tr>
				 
				 
				 
				 
				
				 
				 
        
    <?php
				}
				?>
 
          </table>
        </div></td>
    </tr>
  </table>
  <br>

  <table width="980" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="914"><div align="right">
          <input name="mode" type="hidden" id="mode" value="assigneJoueurSelection">
		  
		  <input name="ordre" type="hidden" id="mode" value="<?=$ordre?>">
          <input name="sens" type="hidden" id="mode" value="<?=$sens?>">
          <input name="masque" type="hidden" value="<?=$masque?>">
          <input name="selectionFrance" type="hidden"  value="<?=$sesUser["selection"]?>">
          <input name="affPosition" type="hidden"  value="<?=$affPosition?>">
Assigner les joueurs coch&eacute;s &agrave; la selection
<?=$sesUser["selection"]?>: </div></td>
      <td width="66"> <div align="right">
          <input type="submit" name="Submit" value="Assigner">
        </div></td>
    </tr>
  </table>
  <br>
  <table width="400"  border="0" align="center">
    <tr>
      <td width="111">L&eacute;gende : </td>
      <td width="279">&nbsp;</td>
    </tr>
    <tr>
      <td><a href="#">racine jean-ren&eacute; </a></td>
      <td>Joueur mis &agrave; jour r&eacute;cemment </td>
    </tr>
    <tr>
      <td><span class="Style3">racine jean ren&eacute; </span></td>
      <td>Joueur mis &agrave; jour il y a + de 15 jours </td>
    </tr>
    <tr>
      <td><span class="Style4">racine jean-ren&eacute; </span></td>
      <td>Joueur mis &agrave; jour il y a + de 30 jours </td>
    </tr>
  </table>
</form>
</body>
<?php  deconnect(); ?>
