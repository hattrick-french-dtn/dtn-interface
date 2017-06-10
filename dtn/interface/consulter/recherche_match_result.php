<?php 
error_reporting(E_ALL);

require_once "../_config/CstGlobals.php"; // fonctions d'admin
//require_once "../fonctions/HT_Client.php"; // Hattrick Client New (with Advanced HTTP Client)
require_once "../fonctions/phpxml.php"; // XML to Tree converter
require_once "../fonctions/AccesBase.php"; // fonction de connexion a la base
require_once "../fonctions/AdminDtn.php"; // fonctions d'admin
require("../includes/head.inc.php");
require("../includes/serviceMatchs.php");
require_once("../CHPP/config.php");
$maBase = initBD();
require("../includes/serviceListesDiverses.php");
require("../includes/serviceEntrainement.php");
require("../includes/serviceJoueur.php");
require("../includes/langue.inc.php");

if(!$sesUser["idAdmin"])
	{
	header("location: index.php?ErrorMsg=Session Expiree");
	}



if(!isset($ht_posteAssigne))	$ht_posteAssigne=0;
if(!isset($minAge)) $minAge ="";
if(!isset($maxAge)) $maxAge ="";
if(!isset($posteTerrain) ) $posteTerrain =-1;
if(!isset($formeMin)) $formeMin =-1;
if(!isset($formeMax)) $formeMax =-1;
if(!isset($etoilesMin)) $etoilesMin =-1;
if(!isset($etoilesMax)) $etoilesMax =-1;
if(!isset($specialty)) $specialty =-1;

$todaySeason=getSeasonWeekOfMatch(mktime(0,0,0,date('m'), date('d'),date('Y')));
if(!isset($depuisDate)) {
	$depuisDate =$todaySeason["season"]."-".$todaySeason["week"];
}


switch($sesUser["idNiveauAcces"]){
		case "1":
		require("../menu/menuAdmin.php");
		require("../menu/menuSuperviseurConsulter.php");
		break;
		
		case "2":
		require("../menu/menuSuperviseur.php");
		require("../menu/menuSuperviseurConsulter.php");
		break;


		case "3":
		require("../menu/menuDTN.php");
		require("../menu/menuDTNConsulter.php");
		break;
		
		case "4":
		require("../menu/menuCoach.php");
		require("../menu/menuCoachConsulter.php"); 
		break;
		
		default;
		break;


}

$keeperColor = "#000000";
$defenseColor = "#000000";
$constructionColor = "#000000";
$ailierColor = "#000000";
$passeColor = "#000000";
$buteurColor = "#000000";
$keeperColor = "#FFFFFF";
$defenseColor = "#FFFFFF";
$constructionColor = "#FFFFFF";
$ailierColor = "#FFFFFF";
$passeColor = "#FFFFFF";
$buteurColor = "#FFFFFF";
$ordreDeTri=" ";

$ordreDeTriTxt="";

switch($ht_posteAssigne){

		case "1":
		//gK
		$keeperColor = "#BBBBEE";
		$ordreDeTri=" ORDER BY scoreGardien DESC ";
		$ordreDeTriTxt=" note GK";
		break;
		
		case "2":
		// cD
		$defenseColor = "#BBBBEE";
		$ordreDeTri=" ORDER BY scoreDefense DESC ";
		$ordreDeTriTxt=" note cD `normal";
		break;
		
		case "3":
		// Wg
		$constructionColor = "#DDDDDD";
		$ailierColor = "#BBBBEE";
		$defenseColor = "#DDDDDD";
		$passeColor = "#DDDDDD";
		$ordreDeTri=" ORDER BY scoreAilierOff DESC ";
		$ordreDeTriTxt=" note Wg `off";
		break;
		case "4":
		//IM
		$constructionColor = "#BBBBEE";
		$defenseColor = "#DDDDDD";
		$passeColor = "#DDDDDD";
		$ordreDeTri=" ORDER BY scoreMilieu DESC ";
		$ordreDeTriTxt=" note iM `normal";
		break;
		
		case "5":
		// Fw
		$ordreDeTri=" ORDER BY scoreAttaquant DESC ";
		$passeColor = "#BBBBEE";
		$buteurColor = "#BBBBEE";
		$ordreDeTriTxt=" note Fw `normal";
		break;
	
		default:
		$ordreDeTri=" ORDER BY idHattrickJoueur DESC ";
		$ordreDeTriTxt=" id Hattrick du joueur";
		break;
}

$ordreDeTriNb=1;
switch($ordreDeTriNb){
	case "1": 
		$ordreDeTri=" ORDER BY  etoile DESC ";
		$ordreDeTriTxt=" &eacute;toiles";
		break;
	default:
		break;
}



$lstPos = listAllPosition();
$lstTypeCarac = listTypeCarac();
$lstCarac = listCarac("ASC",23);
$lstTrain=listEntrainement();

$strPoste="";
$sqlPoste="";

if ($posteTerrain==0){
	$strPoste="gardien";
	$sqlPoste=" AND id_position=1 ";
}else if ($posteTerrain==1){
	$strPoste="d&eacute;fenseur";
	$sqlPoste=" AND ( id_position in (2,3,4,5) ) ";
}else if ($posteTerrain==2){
	$strPoste="ailier";
	$sqlPoste=" AND  ( id_position in (6,9) ) ";
}else if ($posteTerrain==3){
	$strPoste="milieu";
	$sqlPoste="  AND  ( id_position in (7,8) ) ";
}else if ($posteTerrain==4){
	$strPoste="attaquant";
	$sqlPoste="  AND  ( id_position in (10,11) ) ";
}else if ($posteTerrain==5){
	$strPoste="cD normal";
	$sqlPoste="  and (id_behaviour=7 OR ( id_role in (4,3)  and  id_behaviour=0)  )";
}else if ($posteTerrain==6){
	$strPoste="cD off";
	$sqlPoste=" and ( id_role in (4,3)  and  id_behaviour=1 )";
}else if ($posteTerrain==7){
	$strPoste="cD towards wing";
	$sqlPoste=" and ( id_role in (4,3)  and  id_behaviour=4 )";
}else if ($posteTerrain==8){
	$strPoste="wB off";
	$sqlPoste=" and ( id_role in (2,5)  and  id_behaviour=1 ) ";
}else if ($posteTerrain==9){
	$strPoste="wB normal";
	$sqlPoste=" and ( id_role in (2,5)  and  id_behaviour=0 )";
}else if ($posteTerrain==10){
	$strPoste="wB def";
	$sqlPoste=" and ( id_role in (2,5)  and  id_behaviour=2 )";
}else if ($posteTerrain==11){
	$strPoste="wB towards middle";
	$sqlPoste=" and ( id_role in (2,5)  and  id_behaviour=3 )";
}else if ($posteTerrain==12){
	$strPoste="iM normal";
	$sqlPoste=" and (id_behaviour=6 OR  ( id_role in (7,8)  and  id_behaviour=0 ) ) ";
}else if ($posteTerrain==13){
	$strPoste="iM off";
	$sqlPoste=" and ( id_role in (7,8)  and  id_behaviour=1 ) ";
}else if ($posteTerrain==14){
	$strPoste="iM def";
	$sqlPoste=" and ( id_role in (7,8)  and  id_behaviour=2 )  ";
}else if ($posteTerrain==15){
	$strPoste="iM towards wings";
	$sqlPoste=" and ( id_role in (7,8)  and  id_behaviour=4 ) ";
}else if ($posteTerrain==16){
	$strPoste="Wg normal";
	$sqlPoste=" and ( id_role in (6,9)  and  id_behaviour=0 )  ";
}else if ($posteTerrain==17){
	$strPoste="Wg off";
	$sqlPoste=" and ( id_role in (6,9)  and  id_behaviour=1 ) ";
}else if ($posteTerrain==18){
	$strPoste="Wg def";
	$sqlPoste=" and ( id_role in (6,9)  and  id_behaviour=2 ) ";
}else if ($posteTerrain==19){
	$strPoste="Wg towards middle";
	$sqlPoste=" and ( id_role in (6,9)  and  id_behaviour=3 ) ";
}else if ($posteTerrain==20){
	$strPoste="Fw normal";
	$sqlPoste=" and (  id_behaviour=5 OR ( id_role in (10,11)  and  id_behaviour=0 ))";
}else if ($posteTerrain==21){
	$strPoste="Fw towards wings";
	$sqlPoste=" and ( id_role in (10,11)  and  id_behaviour=4 ) ";
}else if ($posteTerrain==22){
	$strPoste="Fw def";
	$sqlPoste=" and ( id_role in (10,11)  and  id_behaviour=2 )";
}
			



$sql="SELECT
          J.idJoueur,
          J.nomJoueur,
          J.prenomJoueur,
          ".getCalculAgeAnneeSQL()." AS ageJoueur,
          J.optionJoueur,
          P.id_match,
          P.season,
          P.week,
          P.id_role,
          P.id_behaviour,
          HJ.forme,
          P.etoile
      FROM 
        $tbl_perf P
        LEFT JOIN $tbl_joueurs J ON P.id_Joueur = J.idHattrickJoueur 
        LEFT JOIN $tbl_joueurs_histo HJ ON (P.id_Joueur = HJ.id_joueur_fk AND P.season = HJ.season AND P.week = HJ.week)
     WHERE 1 ";
$sql=$sql . $sqlPoste;
?>
<link href="../css/ht2.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<title>R&eacute;sultat de la recherche</title>

<style type="text/css">
<!--
.Style1 {color: #FFFFFF}
.Style3 {color: #FF9933}
.Style4 {color: #FF0000}
-->
</style>
<body>
<br>
Vous avez recherch&eacute; des matchs :
<ul>
<?php if ($posteTerrain !=-1 ){ ?> 
<li>Au poste de :
 <b><font color="#CC2233"><?=$strPoste?></b></font> 
<?php	
 }
if ($minAge!="" ||$maxAge!="" ){ 
?><li><?php
if ($minAge!="" ){ 
	$sql=$sql." AND ".getCalculAgeAnneeSQL().">=".$minAge;
	?>D'au moins <?=$minAge?> ans
<?php }
if ($maxAge!="" ){ 
	$sql=$sql." AND ".getCalculAgeAnneeSQL()."<=".$maxAge;
	?> D'au plus <?=$maxAge?> ans
<?php }
}
if ($formeMin!=-1 || $formeMax!=-1 ){
	?><li>Avec un niveau de <font color="#CC2233"> <b>forme</b> </font><?php
	if ($formeMin!=-1){
		$sql=$sql." AND HJ.forme>=".$formeMin;
		?> au moins &eacute;gal &agrave; : <font color="#CC2233"><?=$lstCarac[$formeMin]["intituleCaracFR"]?></font><?php
	}
	if ($formeMax!=-1){
		$sql=$sql." AND HJ.forme<=".$formeMax;
		?> au maximum &eacute;gal &agrave; : <font color="#CC2233"><?=$lstCarac[$formeMax]["intituleCaracFR"]?></font><?php
	}
} 
if ($etoilesMin!=-1 || $etoilesMax!=-1 ){
	?><li>Avec un nombre <font color="#C9A709"><b> d'&eacute;toiles  </b></font><?php
	if ($etoilesMin!=-1){
		$sql=$sql." AND P.etoile>=".$etoilesMin;
		?> au moins &eacute;gal &agrave; : <font color="#CC2233"><?=$etoilesMin?></font><?php
	}
	if ($etoilesMax!=-1){
		$sql=$sql." AND P.etoile<=".$etoilesMax;
		?> au maximum &eacute;gal &agrave; : <font color="#CC2233"><?=$etoilesMax?></font><?php
	}
} 
if ($specialty!=-1){
	if ($specialty==0){
		$sql=$sql." AND J.optionJoueur=0 ";
	?><li> Pour des joueurs qui n'ont rien de sp&eacute;cial... les pauvres. 
	<?php
	}else if ($specialty==1){
		$sql=$sql." AND J.optionJoueur=1 ";
	?><li> Pour des joueurs amateurs des doubles contacts (techniques) 
	<?php
	}else if ($specialty==2){
			$sql=$sql." AND J.optionJoueur=2 ";
	?><li> Pour des joueurs qui arrive m&ecirc;me  &agrave; rattraper Ciss&eacute; une fois lanc&eacute;!(rapides) 
	<?php
	}else if ($specialty==3){
			$sql=$sql." AND J.optionJoueur=3 ";
	?><li> Pour des joueurs qui ont longtemps h&eacute;sit&eacute; &agrave; faire rugby (costauds) 
	<?php
	}else if ($specialty==4){
			$sql=$sql." AND J.optionJoueur=4 ";
	?><li> Pour des joueurs comme Rib&eacute;ry... (impr&eacute;visibles) 
	<?php
	}else if ($specialty==5){
			$sql=$sql." AND J.optionJoueur=5 ";
	?><li> Pour des joueurs comme Zidane lors d'un certain France Br&eacute;sil... (Joueur de t&ecirc;te) 
	<?php
	}
	
	
}
	
	
  $zedate = explode("-",$depuisDate);
  ?>
<li> depuis la saison <?=$zedate[0]?> semaine <?=$zedate[1]?>
<?php	

if ($zedate[0]<$todaySeason["season"]){
	$sql=$sql." AND (P.season=".$todaySeason["season"];
	$sql=$sql ." OR (P.season=".$zedate[0]." AND P.week >=".$zedate[1]."  )) ";
}else {
	$sql=$sql." AND (P.season=".$zedate[0]." AND P.week >=".$zedate[1]."  ) ";
}
	
?>
<li> Matchs tri&eacute;s par <?=$ordreDeTriTxt?>
</ul>
<p>
<?php 
$retour = $maBase->select($sql);
$nbjoueur=count($retour);
$sql=$sql.$ordreDeTri."	LIMIT 0,50;";

$lstJ = $maBase->select($sql);


?>
<TABLE BORDER="0" CELLPADDING="2" CELLSPACING="0" WIDTH="85%">
		<TR>

				<TD COLSPAN="2" BGCOLOR="#DDDDDD" ALIGN="center">
				&nbsp; 

<?php
		if(count($lstJ)==0) {
?>
	Pas de matchs correspondent.

<?php			
		}else{

?>
				<?=$nbjoueur?>&nbsp; matchs ont &eacute;t&eacute; trouv&eacute;s.&nbsp; 
<?php			if($nbjoueur>50) {?> (seuls les 50 premiers sont affich&eacute;s)<?php
	}
}?>


				</TD>
			</TR>
			</table><br>
			<table width=100% bgcolor=#EEEEFF><tr align=right>
		  <td align=left><b>Joueur</b></td>
          <td align=center><b>age</b></td>
          <td align=center><b>sp&eacute;</b></td>
          <td ><b>Matchid</b></td>
          <td align=center><b>forme</b></td>
          <td ><b>ht-date</b></td>
          <td ><b>role</b></td>
          <td align="center" ><b>ordre</b></td>
          <td align="center"><b>etoiles</b></td>
          </tr>
<?php
		if(count($lstJ)>0) {
			$j=0;
			while ($j<count($lstJ)){
				 $star = explode(".",$lstJ[$j]["etoile"]);
			    
			    ?>
			    <tr align=right>
			    <td width=15% align=left>
			     	&nbsp; <a href ="../joueurs/ficheDTN.php?id=<?=$lstJ[$j]["idJoueur"]?>" > 
                      <b> <?=strtolower($lstJ[$j]["nomJoueur"])?> <?=strtolower($lstJ[$j]["prenomJoueur"])?></b> 
                      </a></td>
			    <td width=5%  align=center><?=$lstJ[$j]["ageJoueur"]?></td>
			    <td width=5%  align=center><?php 
			    if ($lstJ[$j]["optionJoueur"]!=0){?><?=$option[$lstJ[$j]["optionJoueur"]]["FR"][0]?><?php }?></td>
			    <td width=5% ><?=$lstJ[$j]["id_match"]?></td>
			    <td width=5% align=center><?=$lstJ[$j]["forme"]?></td>
			    <td width=5%>S<?=$lstJ[$j]["season"]?>W<?=$lstJ[$j]["week"]?></td>
          <td width=20%><?php
          if ($lstJ[$j]["id_role"]!=null){
          ?>
          <?=$minifrenchRole[$lstJ[$j]["id_role"]]?>
          <?php } ?> </td>
          <td width=20% align=center>
          <?php
          if ($lstJ[$j]["id_behaviour"]!=null){
          ?>
          <?=$frenchBehaviour[$lstJ[$j]["id_behaviour"]]?><?php } ?> </td>
			    <td width=30% align=left ><?php
		  
		  for($Z=0;$Z<$star[0];$Z++){
		  ?>
            <img src="../images/star.gif" width="14" height="14">
            <?php
		  }
		 	if($star[1] > 0) echo "<img src=\"../images/half_star.gif\">";
		 		echo " (".$lstJ[$j]["etoile"].")";
		  ?></td>
			    </tr>
<?php
				$j=$j+1;
			}// fin while j<count(lstJ)
		}// fin if count(lstJ)>0
?>	

  </table>
  <br>
</form>
</body>
