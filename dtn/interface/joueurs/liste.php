<?php 
require_once("../includes/head.inc.php");
require("../includes/serviceJoueur.php");
require("../includes/serviceListesDiverses.php");
require("../includes/serviceDTN.php");
if(!$sesUser["idAdmin"])
{
	header("location: index.php?ErrorMsg=Session Expire");
}
if(!isset($ordre)) $ordre = "nomJoueur";
if(!isset($sens)) $sens = "ASC";
if(!isset($lang)) $lang = "FR";
if(!isset($masque)) $masque = 0;
if(!isset($affPosition)) $affPosition = 0;
if(!isset($affArchive))  $affArchive = 0;
if(!isset($useFormule)) $useFormule = 0;
if (!is_numeric($affPosition))
	$affPosition = substr($affPosition, 0, 1);

require("../includes/langue.inc.php");

//
$huit = 60 * 60 * 24 * 8; //time_0
$quinze = 60 * 60 * 24 * 15; //time_1
$trente = 60 * 60 * 24 * 30; //time_2
$twomonths = 60 * 60 * 24 * 60; //time_3
$fourmonths = 60 * 60 * 24 * 120; //time_4

// Date du jour
$mkday = mktime(0,0,0,date('m'), date('d'),date('Y'));

$lstPosition = listPosition();
$lstJoueurs = listJoueur($affArchive, $affPosition);
$d = 0;
$wing=0;
$wingoff = 0;
$wingwtm = 0;
$m=0;
$moff=0;
$att=0;
$k=0;
$font = "<font color = black>";
$ffont = "</font>";
switch($affPosition){

	case "1":
		//gK
		$k = 1;
		$keeperColor = "#9999CC";
		break;
		
	case "2":
		// cD
		$d = 1;
		$defense = 1;
		$defenseColor = "#9999CC";
		break;
		
	case "3":
		// Wg
		$construction = 1;
		$constructionColor = "#CCCCCC";
		$ailier = 1;
		$ailierColor = "#9999CC";
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
		$constructionColor = "#9999CC";
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
		$buteurColor = "#9999CC";
		break;
			
}

?>
<link href="../css/ht2.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<?php
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





?><title>Superviseur</title>
<script language="JavaScript" src="menu_joueur.js"></script>

<style type="text/css">
<!--
.Style1 {color: #FFFFFF}
.Style3 {color: #FF9933}
.Style4 {color: #FF0000}
-->
</style>
<body onLoad = "init();">
<br>
<p>
<form name="form1" method="post" action="../form.php">
<br>

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

case "htms.value":
$intitule = "valeur htms";
break;

case "htms.potential":
$intitule = "potentiel htms";
break;


case "optionJoueur":
$intitule = "specialite";
break;


case "idEndurance":
$intitule = "endurance";
break;


case "idGardien":
$intitule = "gardien";
break;

case "idDefense":
$intitule = "defense";
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


case "scoreGardien":
$intitule = "score Gardien";
break;

case "scoreDefense":
$intitule = "score defense";
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
?>

<b>Nouveau :</b> <a href="listeExportCsv.php?ordre=<?=$ordre?>&sens=<?=$sens?>&lang=<?=$lang?>&masque=<?=$masque?>&affPosition=<?=$affPosition?>">Sauvez cette page en CSV pour la consulter sous Excel!</a> 

<center><h3><?=$tri?> par <?=$intitule?></h3></center>

<br>  <table width="980" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr> 
      <td height="20" ><div align="center"> 
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td width="28%" height="21"> <div align="center">Poste : 
                  <select name="menu1" onChange="MM_jumpMenu('parent',this,0)">
					<option value = liste.php?affPosition=0>Liste des non assignes</option>
			 
			<?php
			  for($i=0;$i<count($lstPosition);$i++){
				if($affPosition == $lstPosition[$i]["idPosition"]) $etat = "selected"; else $etat = "";
				echo "<option value = liste.php?useFormule=".$useFormule."&affPosition=".$lstPosition[$i]["idPosition"]." $etat >".$lstPosition[$i]["intitulePosition"]."</option>";
			  
			  }
			  
			  
			  ?>
			  
                  </select>
                </div></td>
              <td width="50%"><div align="center"><font color="#000000">Liste 
                  des joueurs</font></div></td>
              <td width="22%">
			  <?php if($useFormule == 1) $etat = "checked"; else $etat = "";?>
			  <input name="useFormule" type="checkbox" id="useFormule" value="1" onClick="chgFormule(<?=$affPosition?>)" <?=$etat?>>
			  
			  
              Utiliser mes formules </td>
            </tr>
            <tr> 
              <td height="1" colspan="3" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
            </tr>
            <tr> 
              <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr bgcolor="#000000">
                  <td width="200" onClick="chgTri('nomJoueur','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"><font color="#FFFFFF">Identit&eacute;</font></td>
                  <td width="40" rowspan="5"><div align="center"><span class="Style1">TSI</span></div></td>
                  <!-- largeur de la collone age pour les + de 99 jours par jojoje86 le 21/07/09-->
				  <td width="35" onClick="chgTri('ageJoueur','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">Age</font></div></td>
                  <td width="20" onClick="chgTri('idExperience_fk','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">Xp</font></div></td>
                  <td width="25" onClick="chgTri('idLeader_fk','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">TDC</font></div></td>
                  <td width="40" <!-- onClick="chgTri('TODO htms value')"> -->
                    <div align="center"><font color="#FFFFFF">Valeur HTMS</font></div></td>
                  <td width="40" <!-- onClick="chgTri('TODO htms potentiel')"> -->
                    <div align="center"><font color="#FFFFFF">Potentiel HTMS</font></div></td>
                  <td width="30"  onClick="chgTri('optionJoueur','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">Sp&eacute;</font></div></td>
                  <td width="30"onClick="chgTri('idEndurance','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">E</font></div></td>
                  <td width="30"onClick="chgTri('idGardien','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">G</font></div></td>
                  <td width="30" witdth = "20" onClick="chgTri('idDefense','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">D</font></div></td>
                  <td width="30" height="17"onClick="chgTri('idConstruction','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">C</font></div></td>
                  <td width="30"onClick="chgTri('idAilier','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">A</font></div></td>
                  <td width="30" witdth = "20" onClick="chgTri('idPasse','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">P</font></div></td>
                  <td width="30"onClick="chgTri('idButeur','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">B</font></div></td>
                  <td width="30" witdth = "20" onClick="chgTri('idPA','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">CF</font></div></td>
                    
                  <td width="40" witdth = "20" onClick="chgTri('scoreGardien','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')">	  
                    <div align="center"><font color="#FFFFFF">K</font></div></td>
                  <td width="40" height="17"onClick="chgTri('scoreDefense','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">D</font></div></td>
                  <td width="40"onClick="chgTri('scoreAilierOff','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">W</font></div></td>
                  <td width="40"onClick="chgTri('scoreMilieu','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">M</font></div></td>
                  <td width="40" onClick="chgTri('scoreAttaquant','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">F</font></div></td>
                  <td width="40">                   
                    <div align="center"><font color="#FFFFFF">Pos</font></div></td>
                  </tr>
              </table>
                
				  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr> 
                    <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                  </tr>
                </table>

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
			  
	?>

				               
<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor = "<?=$bgcolor?>">
				  <td align="left" width="200" nowrap>&nbsp;<img src="../images/time_<?=$img_nb?>.gif" onMouseOver="return escape('<?=$zealt?>')" >&nbsp;
                    <a href ="<?=$url?>/joueurs/fiche.php?id=<?=$l["idJoueur"]?>" class="bred1"> 
                      <b>
                      <?=strtolower($l["prenomJoueur"])?> <?=strtolower($l["nomJoueur"])?>
                      </b>
                      </a>
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
                    <td width="2" rowspan="6" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td width="40" witdth = "20"> <div align="center"> 
    <?php
		if($k == 1)
		{
			echo "<font color = #000099><b>";
		}
		else
			echo "<font color = gray>";


				   
				   
$carac["endurance"] = $l["idEndurance"];
$carac["construction"] = $l["idConstruction"];
$carac["ailier"] = $l["idAilier"];
$carac["buteur"] = $l["idButeur"];
$carac["gardien"] = $l["idGardien"];
$carac["passe"] = $l["idPasse"];
$carac["defense"] = $l["idDefense"];
$carac["xp"] = $l["idExperience_fk"];


$semaine["construction"] = $infTraining["nbSemaineConstruction"];
$semaine["ailier"] = $infTraining["nbSemaineAilier"];
$semaine["buteur"] = $infTraining["nbSemaineButeur"];
$semaine["gardien"] = $infTraining["nbSemaineGardien"];
$semaine["passe"] = $infTraining["nbSemainePasses"];
$semaine["defense"] = $infTraining["nbSemaineDefense"];

			$coeff = getCoeffSelectionneur(1);
			if($useFormule == 1 && $coeff["useit"] == 1){
					
				$score = calculNoteDynamique($carac,$semaine,$coeff);
				echo $score;
			} else{
				   
				echo $l["scoreGardien"];
				echo $ffont;
			}


	?>
					  
				</div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div>
						<img src="../images/spacer.gif" width="1" height="1"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="40" height="17" witdth = "20"> <div align="center"> 
                    <?php
						if($d == 1)
							echo "<font color = #000099><b>";
						else
							echo "<font color = gray>";
					
					
$carac["endurance"] = $l["idEndurance"];
$carac["construction"] = $l["idConstruction"];
$carac["ailier"] = $l["idAilier"];
$carac["buteur"] = $l["idButeur"];
$carac["gardien"] = $l["idGardien"];
$carac["passe"] = $l["idPasse"];
$carac["defense"] = $l["idDefense"];
$carac["xp"] = $l["idExperience_fk"];


$semaine["construction"] = $infTraining["nbSemaineConstruction"];
$semaine["ailier"] = $infTraining["nbSemaineAilier"];
$semaine["buteur"] = $infTraining["nbSemaineButeur"];
$semaine["gardien"] = $infTraining["nbSemaineGardien"];
$semaine["passe"] = $infTraining["nbSemainePasses"];
$semaine["defense"] = $infTraining["nbSemaineDefense"];

			  $coeff = getCoeffSelectionneur(2);
				if($useFormule == 1 && $coeff["useit"] == 1){
					
					$score = calculNoteDynamique($carac,$semaine,$coeff);
				echo $score;
				   } else{
				   
					echo $l["scoreDefense"];
					echo $ffont;
				}

					  ?>
                      </div></td>

                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"> 
                      </div>
                      <div align="center"></div></td>
                    <td width="40" witdth = "20"> <div align="center"> 
						  
												                      <?php
					  
				if($wingoff == 1)
					echo "<font color = #000099><b>";
				else
					echo "<font color = gray>";
					 
$carac["endurance"] = $l["idEndurance"];
$carac["construction"] = $l["idConstruction"];
$carac["ailier"] = $l["idAilier"];
$carac["buteur"] = $l["idButeur"];
$carac["gardien"] = $l["idGardien"];
$carac["passe"] = $l["idPasse"];
$carac["defense"] = $l["idDefense"];
$carac["xp"] = $l["idExperience_fk"];


$semaine["construction"] = $infTraining["nbSemaineConstruction"];
$semaine["ailier"] = $infTraining["nbSemaineAilier"];
$semaine["buteur"] = $infTraining["nbSemaineButeur"];
$semaine["gardien"] = $infTraining["nbSemaineGardien"];
$semaine["passe"] = $infTraining["nbSemainePasses"];
$semaine["defense"] = $infTraining["nbSemaineDefense"];

			  $coeff = getCoeffSelectionneur(3);
				if($useFormule == 1 && $coeff["useit"] == 1){
					
					$score = calculNoteDynamique($carac,$semaine,$coeff);
				echo $score;
				   } else{
				   
					echo $l["scoreAilierOff"];
					echo $ffont;
				}

					  ?>
                      </div>
					  
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div>
                      <img src="../images/spacer.gif" width="1" height="1"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="40" witdth = "20"> <div align="center"> 
											
																				                      <?php
					  if($m == 1)
					 {
					echo "<font color = #000099><b>";
					 }
					  else
					echo "<font color = gray>";
				   
$carac["endurance"] = $l["idEndurance"];
$carac["construction"] = $l["idConstruction"];
$carac["ailier"] = $l["idAilier"];
$carac["buteur"] = $l["idButeur"];
$carac["gardien"] = $l["idGardien"];
$carac["passe"] = $l["idPasse"];
$carac["defense"] = $l["idDefense"];
$carac["xp"] = $l["idExperience_fk"];


$semaine["construction"] = $infTraining["nbSemaineConstruction"];
$semaine["ailier"] = $infTraining["nbSemaineAilier"];
$semaine["buteur"] = $infTraining["nbSemaineButeur"];
$semaine["gardien"] = $infTraining["nbSemaineGardien"];
$semaine["passe"] = $infTraining["nbSemainePasses"];
$semaine["defense"] = $infTraining["nbSemaineDefense"];

			  $coeff = getCoeffSelectionneur(4);
				if($useFormule == 1 && $coeff["useit"] == 1){
					
					$score = calculNoteDynamique($carac,$semaine,$coeff);
				echo $score;
				   } else{
				   
					echo $l["scoreMilieu"];
					echo $ffont;
				}

					  ?>     
                      </div></td>

                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div>
                      <img src="../images/spacer.gif" width="1" height="1"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="40" witdth = "20"> <div align="center"> 
						
																					
																				                      <?php
					  
				   
					  if($att == 1)
					 {
					echo "<font color = #000099><b>";
					 }
					  else
					echo "<font color = gray>";
					 
$carac["endurance"] = $l["idEndurance"];
$carac["construction"] = $l["idConstruction"];
$carac["ailier"] = $l["idAilier"];
$carac["buteur"] = $l["idButeur"];
$carac["gardien"] = $l["idGardien"];
$carac["passe"] = $l["idPasse"];
$carac["defense"] = $l["idDefense"];
$carac["xp"] = $l["idExperience_fk"];


$semaine["construction"] = $infTraining["nbSemaineConstruction"];
$semaine["ailier"] = $infTraining["nbSemaineAilier"];
$semaine["buteur"] = $infTraining["nbSemaineButeur"];
$semaine["gardien"] = $infTraining["nbSemaineGardien"];
$semaine["passe"] = $infTraining["nbSemainePasses"];
$semaine["defense"] = $infTraining["nbSemaineDefense"];

			  $coeff = getCoeffSelectionneur(5);
				if($useFormule == 1 && $coeff["useit"] == 1){
					
					$score = calculNoteDynamique($carac,$semaine,$coeff);
				echo $score;
				   } else{
				   
					echo $l["scoreAttaquant"];
					echo $ffont;
				}

					  ?>     

                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div>
                      <img src="../images/spacer.gif" width="1" height="1"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="40" witdth = "20"> <div align="center"> 
                        <?php
					

			if($l["ht_posteAssigne"] != "0" ) 
			{
						
				$pos = getPosition($l["ht_posteAssigne"]);

					switch($sesUser["idNiveauAcces_fk"]) 
					{
					case "1":
						echo '<a href = ../form.php?affPosition='.$affPosition.'&masque='.$masque.'&ordre='.$ordre.'&sens='.$sens.'&mode=annuleAssignation&idJoueur='.$l["idJoueur"].' alt = "Supprimer cette assignation">';
						echo $pos["intitulePosition"]."</a>";
						break;
					
					case "2":
						if($affPosition == $sesUser["idPosition_fk"]){
							echo '<a href = ../form.php?affPosition='.$affPosition.'&masque='.$masque.'&ordre='.$ordre.'&sens='.$sens.'&mode=annuleAssignation&idJoueur='.$l["idJoueur"].' alt = "Supprimer cette assignation">';
							echo $pos["intitulePosition"]."</a>";
						}
						else
						{
							echo $pos["intitulePosition"];
						}
						break;

					}
			}
			else
			{
	?>
                        <input name="assigne[]" type="checkbox" id="assigne[]"  value="<?=$l["idJoueur"]?>"> 
    <?php
			}
	?>
                      </div></td>
                  </tr>
				 
				 
				 
				 
				
				 
				 
                </table>
    <?php
				}
				?>
 
              </td>
            </tr>           
          </table>
        </div></td>
    </tr>
  </table>
  <br>
  <?php
  if($affPosition == "" || $affPosition == 0){
  ?>
  <table width="980" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="819"><div align="right">
          <input name="mode" type="hidden" id="mode" value="assigneJoueur">
		  
		  <input name="ordre" type="hidden" id="mode" value="<?=$ordre?>">
          <input name="sens" type="hidden" id="mode" value="<?=$sens?>">
          <input name="masque" type="hidden" id="mode" value="<?=$masque?>">
          Assigner les joueurs coch&eacute;s au poste : </div></td>
      <td width="161"> <div align="right">
          <select name="idPosition" id="idPosition">
            <?php
			  for($i=0;$i<count($lstPosition);$i++){
			  if($affPosition == $lstPosition[$i]["idPosition"]) $etat = "selected"; else $etat = "";
			  echo "<option value = ".$lstPosition[$i]["idPosition"]." $etat >".$lstPosition[$i]["intitulePosition"]."</option>";
			  
			  }
			  
			  
			  ?>
          </select>
          <input type="submit" name="Submit" value="Assigner">
        </div></td>
    </tr>
  </table><?php }?>
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
