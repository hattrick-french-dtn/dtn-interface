<?php 
ini_set('memory_limit','64M');
error_reporting(E_ALL);

require_once "../_config/CstGlobals.php"; // fonctions d'admin
//require_once "../fonctions/HT_Client.php"; // Hattrick Client New (with Advanced HTTP Client)
require_once "../fonctions/phpxml.php"; // XML to Tree converter
require_once "../fonctions/AccesBase.php"; // fonction de connexion a la base
require_once "../fonctions/AdminDtn.php"; // fonctions d'admin
require("../includes/head.inc.php");

$maBase = initBD();
        
        
require("../includes/serviceListesDiverses.php");
require("../includes/serviceEntrainement.php");
require("../includes/serviceJoueur.php");
require("../includes/langue.inc.php");

if(!$sesUser["idAdmin"])
{
	header("location: ../index.php?ErrorMsg=Session Expiree");
}

if(isset($_POST['action']) and $_POST['action'] == 'submitted') {
  $ht_posteAssigne=$_POST['ht_posteAssigne'];
  $minAge         =$_POST['minAge'];
  $maxAge         =$_POST['maxAge'];
  $minjour        =$_POST['minjour'];
  $maxjour        =$_POST['maxjour'];
  $minValue       =$_POST['minValue'];
  $maxValue       =$_POST['maxValue'];
  $specialty      =$_POST['specialty'];
  $SkillType1     =$_POST['SkillType1'];
  $SkillMin1      =$_POST['SkillMin1'];
  $SkillMax1      =$_POST['SkillMax1'];
  $SkillType2     =$_POST['SkillType2'];
  $SkillMin2      =$_POST['SkillMin2'];
  $SkillMax2      =$_POST['SkillMax2'];
  $SkillType3     =$_POST['SkillType3'];
  $SkillMin3      =$_POST['SkillMin3'];
  $SkillMax3      =$_POST['SkillMax3'];
  $SkillType4     =$_POST['SkillType4'];
  $SkillMin4      =$_POST['SkillMin4'];
  $SkillMax4      =$_POST['SkillMax4'];
  $ordreDeTriNb   =$_POST['ordreDeTriNb'];
  $joueurArchive  =$_POST['joueurArchive'];
  $NivEntraineur  =$_POST['NivEntraineur'];
  $minSalaire     =$_POST['minSalaire'];
  $maxSalaire     =$_POST['maxSalaire'];
  $tbIndexDeb     =unserialize($_POST['tbIndexDeb']);
  $PageSelect     =$_POST['PagesResu'];
  $indexDeb       =$tbIndexDeb[$PageSelect]-1;
} else {  
  if(!isset($ht_posteAssigne))	$ht_posteAssigne=0;
  if(!isset($minAge)) $minAge ="";
  if(!isset($maxAge)) $maxAge ="";
  if(!isset($minjour)) $minjour ="0";
  if(!isset($maxjour)) $maxjour ="112";
  if(!isset($minValue)) $minValue ="";
  if(!isset($maxValue)) $maxValue ="";
  if(!isset($specialty)) $specialty =-1;
  if(!isset($SkillType1)) $SkillType1 ="";
  if(!isset($SkillMin1)) $SkillMin1 ="";
  if(!isset($SkillMax1)) $SkillMax1 ="";
  
  if(!isset($SkillType2)) $SkillType2 ="";
  if(!isset($SkillMin2)) $SkillMin2 ="";
  if(!isset($SkillMax2)) $SkillMax2 ="";
  
  if(!isset($SkillType3)) $SkillType3 ="";
  if(!isset($SkillMin3)) $SkillMin3 ="";
  if(!isset($SkillMax3)) $SkillMax3 ="";
  
  if(!isset($SkillType4)) $SkillType4 ="";
  if(!isset($SkillMin4)) $SkillMin4 ="";
  if(!isset($SkillMax4)) $SkillMax4 ="";
  if(!isset($ordreDeTriNb)) $ordreDeTriNb ="";
  
  if(!isset($joueurArchive)) $joueurArchive =0;
  

  if(!isset($NivEntraineur)) $NivEntraineur =0;
  
  if(!isset($indexDeb))	$indexDeb=0;
  
  if(!isset($PageSelect)) $PageSelect=1;
 
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
$typeExport="recherche"; // Utilisé pour export csv

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

/*Calcul dynamique de l'age*/
$SqlAgeJoueur=getCalculAgeAnneeSQL();
$SqlJourJoueur=getCalculAgeJourSQL();

switch($ordreDeTriNb){
	
	case "1": 
		$ordreDeTri=" ORDER BY  dateDerniereModifJoueur DESC ";
		$ordreDeTriTxt=" mise &agrave; jour DTN";
		break;
	case "2": 
		$ordreDeTri=" ORDER BY  dateDerniereModifJoueur ASC ";
		$ordreDeTriTxt=" mise &agrave; jour DTN invers&eacute;e";
		break;
	case "3": 
		$ordreDeTri=" ORDER BY  valeurEnCours DESC ";
		$ordreDeTriTxt=" TSI";
		break;
	case "4": 
		$ordreDeTri=" ORDER BY  idHattrickJoueur DESC ";
		$ordreDeTriTxt=" id Hattrick du joueur";
		break;
	case "5": 
		$ordreDeTri=" ORDER BY  scoreGardien DESC ";
		$ordreDeTriTxt=" note GK";
		break;
	case "6": 
		$ordreDeTri=" ORDER BY  scoreDefense DESC ";
		$ordreDeTriTxt=" note cD `normal";
		break;
	case "7": 
		$ordreDeTri=" ORDER BY  scoreDefCentralOff  DESC ";
		$ordreDeTriTxt=" note cD `off";
		break;
	case "8": 
		$ordreDeTri=" ORDER BY  scoreDefLatOff  DESC ";

		$ordreDeTriTxt=" note wB `off";
		break;
	case "9": 
		$ordreDeTri=" ORDER BY  scoreDefLat   DESC ";
		$ordreDeTriTxt=" note wB `normal";
		break;
	case "10": 
		$ordreDeTri=" ORDER BY  scoreMilieu DESC ";
		$ordreDeTriTxt=" note iM `normal";
		
		break;
	case "11": 
		$ordreDeTri=" ORDER BY  scoreMilieuOff DESC ";
		$ordreDeTriTxt=" note iM `off";
		
		break;
	case "12": 
		$ordreDeTri=" ORDER BY  scoreMilieuDef DESC ";
		$ordreDeTriTxt=" note iM `def";
		break;
	case "13": 
		$ordreDeTri=" ORDER BY  scoreAilier DESC ";
		$ordreDeTriTxt=" note Wg `normal";
		break;
	case "14": 
		$ordreDeTri=" ORDER BY  scoreAilierOff DESC ";
		$ordreDeTriTxt=" note Wg `off";
		break;
	case "15": 
		$ordreDeTri=" ORDER BY  scoreAilierVersMilieu DESC ";
		$ordreDeTriTxt=" note Wg `towards middle";
		break;
	case "16": 
		$ordreDeTri=" ORDER BY  scoreAttaquant DESC ";
		$ordreDeTriTxt=" note Fw `normal";
		break;
	case "17": 
		$ordreDeTri=" ORDER BY  scoreAttaquantDef DESC ";
		$ordreDeTriTxt=" note Fw `def";
		break;
		
	case "18":
		$ordreDeTri=" ORDER BY  dateSaisieJoueur DESC ";
		$ordreDeTriTxt=" mise &agrave; jour propri&eacute;taire";
		break;
	case "19":
		$ordreDeTri=" ORDER BY  dateSaisieJoueur ASC ";
		$ordreDeTriTxt=" mise &agrave; jour propri&eacute;taire invers&eacute;e";
		break;
	case "20":
		$ordreDeTri=" ORDER BY salary DESC ";
		$ordreDeTriTxt=" salaire réel";
		break;				
	case "21":
		$ordreDeTri=" ORDER BY salaireDeBase DESC ";
		$ordreDeTriTxt=" salaire de base";
		break;
		
	case "22":
		$ordreDeTri=" ORDER BY scoreAttaquantVersAile DESC ";
		$ordreDeTriTxt=" note Fw `towards wings";
		break;
		
	case "23":
		$ordreDeTri=" ORDER BY salaireDeBase ASC ";
		$ordreDeTriTxt=" salaire de base invers&eacute;";
	break;

  case "24":
		$ordreDeTri=" ORDER BY ".$SqlAgeJoueur." ASC ,".$SqlJourJoueur." ASC ";
		$ordreDeTriTxt=" âge";
	break;
	
	case "25":
		$ordreDeTri=" ORDER BY ".$SqlAgeJoueur." DESC ,".$SqlJourJoueur." DESC ";
		$ordreDeTriTxt=" âge invers&eacute;";
	break;
	
	case "26":
		$ordreDeTri=" ORDER BY IdLeader_fk DESC ";
		$ordreDeTriTxt=" TDC";
	break;

	default:
		break;
}



$lstPos = listAllPosition();
$lstTypeCarac = listTypeCarac();
$lstCarac = listCarac("ASC",30);
$lstTrain=listEntrainement();


/*Calcul dynamique de l'age
$SqlAgeJoueur=getCalculAgeAnneeSQL();
$SqlJourJoueur=getCalculAgeJourSQL();*/

$sqlreel = "SELECT  *, 
              ROUND(salary/coefSalary) AS salaireDeBase,
              ".$SqlAgeJoueur." as ageJoueur,
              ".$SqlJourJoueur." as jourJoueur,
              ht_caracteristiques.intituleCaracFR as Lib_Niv_entrainement";
$sql= " FROM ht_joueurs
        	INNER JOIN ht_clubs ON teamid = idClubHT
        	INNER JOIN ht_pays ON idPays_fk = idPays
        	LEFT JOIN ht_entrainement ON idJoueur_fk = idJoueur
        	LEFT JOIN ht_caracteristiques ON ht_clubs.niv_Entraineur =ht_caracteristiques.idCarac
    	WHERE 1";
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

Vous avez recherch&eacute; des joueurs :
<ul>
<?php
if ($ht_posteAssigne!=0){
	if ($sesUser["idNiveauAcces"]=="2" || $sesUser["idNiveauAcces"]=="1" || $sesUser["idNiveauAcces"]=="4"){
		$sql=$sql." AND ( ht_posteAssigne=".$ht_posteAssigne ." OR ht_posteAssigne=0 )" ;
?>
<li>Dans la cat&eacute;gorie <b><font color="#CC2233"><?=$lstPos[$ht_posteAssigne-1]["descriptifPosition"]?></font></b> ou joueurs <font color="#CC2233"><b>non assign&eacute;s</b></font> 
<?php	}else{
		$sql=$sql." AND ht_posteAssigne=".$ht_posteAssigne;
?>
<li>Dans la cat&eacute;gorie <b><font color="#CC2233"><?=$lstPos[$ht_posteAssigne-1]["descriptifPosition"]?></font></b> 
<?php	}
 }

/**** Filtre sur l'age *****/
if ($minAge=="" && $maxAge=="" ){
?><li><?php
$sql=$sql." AND ".$SqlJourJoueur.">=".$minjour." AND ".$SqlJourJoueur."<=".$maxjour;
?>Entre <?=$minjour?> jours et <?=$maxjour?> jours
<?php 
}
if ($minAge!="" || $maxAge!="" ){ 
?><li><?php
if ($minAge!="" ){ 
	if ($minjour=="") $minjour=0;
	$sql=$sql." AND (".$SqlAgeJoueur.">".$minAge." OR (".$SqlAgeJoueur."=".$minAge." AND ".$SqlJourJoueur.">=".$minjour."))";
	?>D'au moins <?=$minAge?> ans et <?=$minjour?> jours
<?php }
if ($maxAge!="" ){ 
	if ($maxjour=="") $maxjour=112;
	$sql=$sql." AND (".$SqlAgeJoueur."<".$maxAge." OR (".$SqlAgeJoueur."=".$maxAge." AND ".$SqlJourJoueur."<=".$maxjour."))";
	?> D'au plus <?=$maxAge?> ans et <?=$maxjour?> jours
<?php 
//echo $sql;
}
}


/***** Filtre sur la spécialité ****/
if ($specialty==99){
	$sql=$sql." AND (optionJoueur='1' || optionJoueur='2'|| optionJoueur='3' || optionJoueur='4' || optionJoueur='5' || optionJoueur='6' || optionJoueur='8')";
	?><li> Avec n'importe quelle sp&eacute;cialit&eacute; <?php
} else if ($specialty!=-1 ){
	$sql=$sql." AND optionJoueur=".$specialty;
	?><li> Avec comme sp&eacute;cialit&eacute; :<font color="#CC22DD"><?=$option[$specialty]["FR"]?></font><?php
} 
if ($minValue!="" ||$maxValue!=""){ 
?><li><?php
if ($minValue!="" ){ 
	$sql=$sql." AND valeurEnCours>=".$minValue;
	?>Avec un TSI d'au moins <?=$minValue?>
<?php }
if ($maxValue!=""){ 
	$sql=$sql." AND valeurEnCours<=".$maxValue;
	?> Avec un TSI d'au plus <?=$maxValue?> 
<?php }
}
if ($minSalaire!="" ||$maxSalaire!=""){ 
?><li><?php
if ($minSalaire!="" ){ 
	
	$sql=$sql." AND salary>=".$minSalaire*10;
	?>Avec un Salaire d'au moins <?=$minSalaire?>
<?php }
if ($maxSalaire!=""){ 
	$sql=$sql." AND salary<=".$maxSalaire*10;
	?> Avec un Salaire d'au plus <?=$maxSalaire?> 
<?php }
}
if ($SkillType1!="" && ($SkillMin1!="" || $SkillMax1!="" )){

	?><li>Avec un niveau de <font color="#CC2233"><?=$lstTypeCarac[$SkillType1-1]["nomTypeCarac"]?></font><?php
	if ($SkillMin1!=""){
		$sql=$sql." AND ".$lstTypeCarac[$SkillType1-1]["nomColonneHt_Joueurs"].">=".$SkillMin1;
		?> au moins &eacute;gal &agrave; : <font color="#CC2233"><?=$lstCarac[$SkillMin1]["intituleCaracFR"]?></font><?php
	}
	if ($SkillMax1!=""){
		$sql=$sql." AND ".$lstTypeCarac[$SkillType1-1]["nomColonneHt_Joueurs"]."<=".$SkillMax1;
		?> au maximum &eacute;gal &agrave; : <font color="#CC2233"><?=$lstCarac[$SkillMax1]["intituleCaracFR"]?></font><?php
	}
} 
if ($SkillType2!="" && ($SkillMin2!="" || $SkillMax2!="" )){
	?><li>Avec un niveau de <font color="#CC2233"><?=$lstTypeCarac[$SkillType2-1]["nomTypeCarac"]?></font><?php
	if ($SkillMin2!=""){
		$sql=$sql." AND ".$lstTypeCarac[$SkillType2-1]["nomColonneHt_Joueurs"].">=".$SkillMin2;
		?> au moins &eacute;gal &agrave; : <font color="#CC2233"><?=$lstCarac[$SkillMin2]["intituleCaracFR"]?></font><?php
	}
	if ($SkillMax2!=""){
		$sql=$sql." AND ".$lstTypeCarac[$SkillType2-1]["nomColonneHt_Joueurs"]."<=".$SkillMax2;
		?> au maximum &eacute;gal &agrave; : <font color="#CC2233"><?=$lstCarac[$SkillMax2]["intituleCaracFR"]?></font><?php
	}
} 
if ($SkillType3!="" && ($SkillMin3!="" || $SkillMax3!="" )){
	?><li>Avec un niveau de <font color="#CC2233"><?=$lstTypeCarac[$SkillType3-1]["nomTypeCarac"]?></font><?php
	if ($SkillMin3!=""){
		$sql=$sql." AND ".$lstTypeCarac[$SkillType3-1]["nomColonneHt_Joueurs"].">=".$SkillMin3;
		?> au moins &eacute;gal &agrave; : <font color="#CC2233"><?=$lstCarac[$SkillMin3]["intituleCaracFR"]?></font><?php
	}
	if ($SkillMax3!=""){
		$sql=$sql." AND ".$lstTypeCarac[$SkillType3-1]["nomColonneHt_Joueurs"]."<=".$SkillMax3;
		?> au maximum &eacute;gal &agrave; : <font color="#CC2233"><?=$lstCarac[$SkillMax3]["intituleCaracFR"]?></font><?php
	}
} 
if ($SkillType4!="" && ($SkillMin4!="" || $SkillMax4!="" )){
	?><li>Avec un niveau de <font color="#CC2233"><?=$lstTypeCarac[$SkillType4-1]["nomTypeCarac"]?></font><?php
	if ($SkillMin4!=""){
		$sql=$sql." AND ".$lstTypeCarac[$SkillType4-1]["nomColonneHt_Joueurs"].">=".$SkillMin4;
		?> au moins &eacute;gal &agrave; : <font color="#CC2233"><?=$lstCarac[$SkillMin4]["intituleCaracFR"]?></font><?php
	}
	if ($SkillMax4!=""){
		$sql=$sql." AND ".$lstTypeCarac[$SkillType4-1]["nomColonneHt_Joueurs"]."<=".$SkillMax4;
		?> au maximum &eacute;gal &agrave; : <font color="#CC2233"><?=$lstCarac[$SkillMax4]["intituleCaracFR"]?></font><?php
	}
}
	if ($joueurArchive==1){
		$sql=$sql." AND archiveJoueur=1 ";		
		?>
		<li> En montrant les joueurs archiv&eacute;s
		<?php
	}else {
				$sql=$sql." AND archiveJoueur=0 ";		
		
	}

	if ($NivEntraineur>=7){
		$sql=$sql." AND niv_Entraineur=".$NivEntraineur;		
		?>
		<li> Avec un entraineur de niveau <?=$lstCarac[$NivEntraineur]["intituleCaracFR"]?>
		<?php
	}else {if ($NivEntraineur==6){
      	  $sql=$sql." AND niv_Entraineur<=".$NivEntraineur;
      		?>
      		<li> Avec un entraineur de niveau <?=$lstCarac[$NivEntraineur]["intituleCaracFR"]?> ou moins
      		<?php
        }
	}
	
?>
<li> Joueurs tri&eacute;s par <?=$ordreDeTriTxt?>
</ul>
<p>
<?php if ($sesUser["idNiveauAcces"]==3 and $sesUser["idPosition_fk"]!=$ht_posteAssigne){?>
  NB : Pour exporter le r&eacute;sultat de votre recherche au format Fiche R&eacute;sum&eacute;, s&eacute;lectionnez votre secteur de jeu dans la page pr&eacute;c&eacute;dente!
  <p>
<?php }


$retour = $maBase->select("SELECT  count(*) as nb ".$sql);

//ajout dans une variable des clauses FROM et WHERE avant que tout ne soit regroupé (Utilisé pour export csv) par jojoje86 le 22/07/09-->
$laSelection=urlencode($sql);

$nbjoueur=$retour[0]["nb"];
$sql=$sqlreel.$sql.$ordreDeTri;

// Extraction des 50 joueurs de la page (ou moins si page incomplète) pour transfert
// à ficherecupchoix.php pour affichage de fiches résumé par lots
// Dans ce cas $sql1=$sql et $ListeTotale=$lstJ
// mais on conserve la routine pour modification rapide du nombre de fiches résumé à extraire 
// Fireproofed le 05/11/2010
// $sql1=$sql."	LIMIT ".$indexDeb.",50;";
// $ListeTotale=$maBase->select($sql1);
// $_SESSION['ListeFicheResume']=$ListeTotale;
//

$sql.="	LIMIT ".$indexDeb.",50;";

$lstJ = $maBase->select($sql);

$_SESSION['ListeFicheResume']=$lstJ //variable _SESSION pour transfert vers fiches résumé en lot - Fireproofed


//echo $sql;

?>

<TABLE BORDER="0" CELLPADDING="2" CELLSPACING="0" WIDTH="85%">
		<TR>

				<TD COLSPAN="2" BGCOLOR="#DDDDDD" ALIGN="center">
				&nbsp; 

<?php
if(count($lstJ)==0) {
?>
	Pas de joueurs correspondent.

<?php			
}else{

    ?>
				<?=$nbjoueur?>&nbsp;joueurs ont &eacute;t&eacute; trouv&eacute;s.&nbsp;<br> 
    <?php
	$nbPages=floor(($nbjoueur / 50)+1);
    
    if ($nbPages>1) {?>

				<FORM ACTION="recherche_result.php" METHOD="POST">
				 Afficher r&eacute;sultats : <SELECT name="PagesResu">  

      <?php
      $k=1;
      while($k<=$nbPages) 
      {
      $indexDeb=($k*50)-49;
      $indexFin=($k*50);
      
      if ($k==$nbPages) {
        $indexFin=$nbjoueur;
      }

      $tbIndexDeb[$k]=$indexDeb;   

      ?>
		  <OPTION VALUE="<?=$k?>" <?php if ($k==$PageSelect) {?>SELECTED<?php }?> > <?=$indexDeb?> &agrave; <?=$indexFin?></OPTION>
      <?php
      $k++;
      } // Fin While
      
         ?> 
         </SELECT>
	       
         <INPUT TYPE="hidden" NAME="ht_posteAssigne" VALUE="<?=$ht_posteAssigne?>" >
	       <INPUT TYPE="hidden" NAME="minAge" VALUE="<?=$minAge?>" >
	       <INPUT TYPE="hidden" NAME="maxAge" VALUE="<?=$maxAge?>" >
	       <INPUT TYPE="hidden" NAME="minjour" VALUE="<?=$minjour?>" >
	       <INPUT TYPE="hidden" NAME="maxjour" VALUE="<?=$maxjour?>" >
	       <INPUT TYPE="hidden" NAME="minValue" VALUE="<?=$minValue?>" >
         <INPUT TYPE="hidden" NAME="maxValue" VALUE="<?=$maxValue?>" >
	       <INPUT TYPE="hidden" NAME="specialty" VALUE="<?=$specialty?>" >
	       <INPUT TYPE="hidden" NAME="SkillType1" VALUE="<?=$SkillType1?>" >
	       <INPUT TYPE="hidden" NAME="SkillMin1" VALUE="<?=$SkillMin1?>" >
	       <INPUT TYPE="hidden" NAME="SkillMax1" VALUE="<?=$SkillMax1?>" >
	       <INPUT TYPE="hidden" NAME="SkillType2" VALUE="<?=$SkillType2?>" >
         <INPUT TYPE="hidden" NAME="SkillMin2" VALUE="<?=$SkillMin2?>" >
	       <INPUT TYPE="hidden" NAME="SkillMax2" VALUE="<?=$SkillMax2?>" >
	       <INPUT TYPE="hidden" NAME="SkillType3" VALUE="<?=$SkillType3?>" >
	       <INPUT TYPE="hidden" NAME="SkillMin3" VALUE="<?=$SkillMin3?>" >
	       <INPUT TYPE="hidden" NAME="SkillMax3" VALUE="<?=$SkillMax3?>" >
	       <INPUT TYPE="hidden" NAME="SkillType4" VALUE="<?=$SkillType4?>" >
	       <INPUT TYPE="hidden" NAME="SkillMin4" VALUE="<?=$SkillMin4?>" >
	       <INPUT TYPE="hidden" NAME="SkillMax4" VALUE="<?=$SkillMax4?>" >
         <INPUT TYPE="hidden" NAME="ordreDeTriNb" VALUE="<?=$ordreDeTriNb?>" >
	       <INPUT TYPE="hidden" NAME="joueurArchive" VALUE="<?=$joueurArchive?>" >
	       <INPUT TYPE="hidden" NAME="NivEntraineur" VALUE="<?=$NivEntraineur?>" >
	       <INPUT TYPE="hidden" NAME="maxSalaire" VALUE="<?=$maxSalaire?>" >
	       <INPUT TYPE="hidden" NAME="minSalaire" VALUE="<?=$minSalaire?>" >
	       <INPUT TYPE="hidden" NAME="action" VALUE="submitted" >
	       <INPUT TYPE="hidden" NAME="tbIndexDeb" VALUE="<?=serialize($tbIndexDeb)?>" >
	       <INPUT TYPE="submit" VALUE="OK"><?php
	       
	       
    } // Fin Si il y a plus de 1 page
} // Fin else (= au moins 1 joueur trouvé)
?>
				<br>
				<!--// Ajout du liens permetant l'export CSV par jojoje86 le 22/07/09-->
				<!--// Ce liens n'est visible que si le niveau d'accès est suffisant par jojoje86 le 25/07/09-->
<?php
				if ($sesUser["idNiveauAcces"]==1 || $sesUser["idNiveauAcces"]==2 || $sesUser["idNiveauAcces"]==4)
        //si admin, DTN+ ou sélectionneur alors autoriser export CSV et fiche résumé globale
				{
?>

        <table border="0" cellpadding="0" cellspacing="0">
            <tr> 
              <td valign="middle">Export Excel :&nbsp;</td>
              <td valign="middle"><a href="../outils/ExportCsv.php?typeExport=<?=$typeExport?>&laSelection=<?=$laSelection?>&ordre=<?=$ordreDeTri?>"><img border=1 src="../images/icone-excel.jpg" title="Exporter le r&eacute;sultat de ma recherche sur Excel"></a></td>
              
              <!-- Rajout export vers fiches résumé -->
              <!-- Fireproofed le 05/11/2010 -->
              
              <td valign="middle">
                     &nbsp &nbsp &nbsp Export Fiche R&eacute;sum&eacute; des joueurs de la page :&nbsp;</td>              
              <td valign="middle">
                <a href="../joueurs/ficherecupchoix.php?origine=<?php echo "selection"?>">
                  <img border=1 src="../images/jst.bmp" title="Exporter le r&eacute;sultat affich&eacute; dans la page sous forme d'une fiche r&eacute;sum&eacute; globale"></a></td>
              
            </tr>
        </table> 				
				</TD>
			</TR>
<?php
				} else { 
        if ($sesUser["idNiveauAcces"]==3 and ($sesUser["idPosition_fk"]==$ht_posteAssigne or $sesUser["idPosition_fk"]==6 or $sesUser["idPosition_fk"]==0))
        //Si DTN et secteur DTN=secteur recherché ou si DTN tous secteurs alors autoriser fiche résumé globale
        //Fireproofed le 23/11/2010
        {
?>        
        <table border="0" cellpadding="0" cellspacing="0">
            <tr> 
              <td valign="middle">
                     &nbsp &nbsp &nbsp Export Fiche R&eacute;sum&eacute; des joueurs de la page :&nbsp;</td>              
              <td valign="middle">
                <a href="../joueurs/ficherecupchoix.php?origine=<?php echo "selection"?>"
                  <img border=1 src="../images/jst.bmp" title="Exporter le r&eacute;sultat affich&eacute; dans la page sous forme d'une fiche r&eacute;sum&eacute; globale"></a></td>              
            </tr>
        </table> 				
 <?php       
        }
    }
        
	if(count($lstJ)>0) {
		$j=0;
		$huit = 60 * 60 * 24 * 8; //time_0
		$quinze = 60 * 60 * 24 * 15; //time_1
		$trente = 60 * 60 * 24 * 30; //time_2
		$twomonths = 60 * 60 * 24 * 60; //time_3
		$fourmonths = 60 * 60 * 24 * 120; //time_4
			  
		// Date du jour                                                                                            
		$mkday = mktime(0,0,0,date('m'), date('d'),date('Y'));
			
			
		while ($j<count($lstJ)){
			$sqlscout="select loginAdmin FROM ht_joueurs, ht_admin  WHERE idHattrickJoueur = '".$lstJ[$j]["idHattrickJoueur"]."' AND dtnSuiviJoueur_fk=idAdmin ";
			$scout=$maBase->select($sqlscout);
			$dtnDuJoueur="<i>[personne &agrave; d&eacute;finir]</i>";
			if (count($scout)>0){
				$dtnDuJoueur=$scout[0]["loginAdmin"];
			}
				
			$date = explode("-",$lstJ[$j]["dateDerniereModifJoueur"]);
			$mkJoueur =  mktime(0,0,0,$date[1],$date[2],$date[0]); 
			$datesaisie = explode("-",$lstJ[$j]["dateSaisieJoueur"]);
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
			$zealt=" Date dtn : ".$lstJ[$j]["dateDerniereModifJoueur"].
					"<br> Date proprio : ".$lstJ[$j]["dateSaisieJoueur"].
					"<br> [ Mis &agrave; jour il y a  ".round(($mkday - $datemaj)/(60*60*24) )." jours ]";
			
?>
		
			<TR>
				<TD><BR>
				  <?php if ($nbPages==1) {$numResu=$j+1;} else {$numResu=$j+$tbIndexDeb[$PageSelect];}?>
					<font color="#CC2233"><?=$numResu?>.</font> <A HREF="../joueurs/fiche.php?id=<?=$lstJ[$j]["idJoueur"]?>"><?=$lstJ[$j]["prenomJoueur"]?> <?=$lstJ[$j]["nomJoueur"]?><?php if (isset($lstJ[$j]["surnomJoueur"])) echo " (".$lstJ[$j]["surnomJoueur"].")"; ?> </A>&nbsp; 
					  <?php if($lstJ[$j]["optionJoueur"]) echo "<font color=\"#CC22DD\">[<i>".$option[$lstJ[$j]["optionJoueur"]]["FR"]."</i>]</font>"?>
					  &nbsp;|&nbsp;Secteur : 
					 <?php if ($lstJ[$j]["ht_posteAssigne"]!=0){?>
					  <?=$lstPos[$lstJ[$j]["ht_posteAssigne"]-1]["descriptifPosition"]?>
					  <?php }else{ ?>
					  	<b><font color="#3C9478">aucun<font></b>
					  	<?php } ?>
				</TD>
				<TD align="right"><BR>
				Entra&icirc;nement Actuel : [ <?=getEntrainementName($lstJ[$j]["entrainement_id"],$lstTrain); ?>  ]
				&nbsp;|&nbsp;
				Entraineur : <?=$lstJ[$j]["Lib_Niv_entrainement"]?>
				</TD>
			</TR>
			
			<TR>
				<TD VALIGN="top">

					<B>Suivi par </B> <?=$dtnDuJoueur?><BR>
					<B>Age:&nbsp; </B><?=$lstJ[$j]["ageJoueur"]?> ans <?=$lstJ[$j]["jourJoueur"]?> jours / <b>XP</b> : <?=$lstCarac[$lstJ[$j]["idExperience_fk"]]["intituleCaracFR"]?> / <b>TDC</b> : <?=$lstCarac[$lstJ[$j]["idLeader_fk"]]["intituleCaracFR"]?><BR>
					<B>TSI :&nbsp; </B> <?=$lstJ[$j]["valeurEnCours"]?> / <b>id</b> : ( <?=$lstJ[$j]["idHattrickJoueur"]?> )<BR>					
					<B>Salaire :&nbsp; </B> <?=round(($lstJ[$j]["salary"]/10),2)?> ( <?=$lstJ[$j]["salaireDeBase"]/10?> ) <BR>					
				</TD>
				<TD VALIGN="top">
					<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0">
					<TR>
						<TD width=25% ><B>Endurance :&nbsp; </B></TD><TD width=25% ><?=$lstCarac[$lstJ[$j]["idEndurance"]]["intituleCaracFR"]?></TD>
						<TD width=25% ><B>&nbsp; &nbsp; Gardien :&nbsp;</B></TD> <TD bgcolor="<?=$keeperColor?>" width=25% ><?=$lstCarac[$lstJ[$j]["idGardien"]]["intituleCaracFR"]?><?php afficheLesPlus($lstJ[$j],"nbSemaineGardien"); ?></TD>

					</TR>
					<TR>
						<TD><B>Construction :&nbsp; </B></TD><TD bgcolor="<?=$constructionColor?>"><?=$lstCarac[$lstJ[$j]["idConstruction"]]["intituleCaracFR"]?><?php afficheLesPlus($lstJ[$j],"nbSemaineConstruction"); ?></TD>
						<TD><B>&nbsp; &nbsp; Passe :&nbsp; </B></TD><TD bgcolor="<?=$passeColor?>"><?=$lstCarac[$lstJ[$j]["idPasse"]]["intituleCaracFR"]?><?php afficheLesPlus($lstJ[$j],"nbSemainePasses"); ?></TD>
					</TR>

					<TR>
						<TD><B>Ailier : &nbsp;</B></TD> <TD bgcolor="<?=$ailierColor?>"><?=$lstCarac[$lstJ[$j]["idAilier"]]["intituleCaracFR"]?><?php afficheLesPlus($lstJ[$j],"nbSemaineAilier"); ?></TD>
						<TD><B>&nbsp; &nbsp; D&eacute;fense :&nbsp; </B></TD><TD bgColor="<?=$defenseColor?>"><?=$lstCarac[$lstJ[$j]["idDefense"]]["intituleCaracFR"]?><?php afficheLesPlus($lstJ[$j],"nbSemaineDefense"); ?></TD>
					</TR>
					<TR>

						<TD><B>Buteur :&nbsp; </B></TD><TD bgcolor="<?=$buteurColor?>"><?=$lstCarac[$lstJ[$j]["idButeur"]]["intituleCaracFR"]?><?php afficheLesPlus($lstJ[$j],"nbSemaineButeur"); ?></TD>
						<TD><B>&nbsp; &nbsp; Coup franc :&nbsp; </B></TD><TD><?=$lstCarac[$lstJ[$j]["idPA"]]["intituleCaracFR"]?><?php afficheLesPlus($lstJ[$j],"nbSemaineCoupFranc"); ?></TD>
					</TR>
					</TABLE>
				</TD>
			</TR>
			<TR>
				<TD VALIGN="top">
					<B>Mis &agrave; jour : &nbsp; </B>&nbsp;<img src="../images/time_<?=$img_nb?>.gif" onMouseOver="return escape('<?=$zealt?>')" > 
				</TD>
				<TD ALIGN="right">
<?php
				$htms = htmspoint($lstJ[$j]["ageJoueur"], $lstJ[$j]["jourJoueur"], $lstJ[$j]["idGardien"], $lstJ[$j]["idDefense"], $lstJ[$j]["idConstruction"], $lstJ[$j]["idAilier"], $lstJ[$j]["idPasse"], $lstJ[$j]["idButeur"], $lstJ[$j]["idPA"]);

?>
					HTMS <?=$htms["value"]?> (<?=$htms["potential"]?>)
				</TD>
			</TR>
<?php
				$j=$j+1;
			}// fin while j<count(lstJ)
		}// fin if count(lstJ)>0
?>
		<TR>

				<TD COLSPAN="2" BGCOLOR="#DDDDDD" ALIGN="center">
				&nbsp; 

<?php
		if(count($lstJ)==0) {
?>
	Aucun joueur correspondant.

<?php			
		}else{


    ?>
				<?=$nbjoueur?>&nbsp;joueurs ont &eacute;t&eacute; trouv&eacute;s.&nbsp;<br> 
    <?php			

} // Fin else (= au moins 1 joueur trouvé)
?>
				</TD>
			</TR>

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
