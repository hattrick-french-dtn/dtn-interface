<?php
require_once("../includes/head.inc.php");

if ( (!isset($_SESSION['sesUser'])) || (empty($_SESSION['sesUser'])) )
{
	header("location: ../index.php?ErrorMsg=Session Expiree");
}

require_once "../_config/CstGlobals.php"; // fonctions d'admin
require_once "../fonctions/AccesBase.php"; // fonction de connexion a la base
require_once "../fonctions/AdminDtn.php"; // fonctions d'admin

$maBase = initBD();
        
require("../includes/serviceListesDiverses.php");
require("../includes/serviceEntrainement.php");
require("../includes/serviceJoueur.php");
require("../includes/langue.inc.php");
	
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


if(isset($_POST['action']) and $_POST['action'] == 'submitted') {
  $tbIndexDeb = unserialize($_POST['tbIndexDeb']);
  $PageSelect = $_POST['PagesResu'];
  $indexDeb = $tbIndexDeb[$PageSelect]-1;
  $_SESSION['PurgeJoueurPageSelect'] = $PageSelect;
  $_SESSION['PurgeJoueurIndexDeb'] = $indexDeb;
} else {
  if (isset($_SESSION['PurgeJoueurPageSelect']) && isset($_SESSION['PurgeJoueurIndexDeb'])) {
    $PageSelect = $_SESSION['PurgeJoueurPageSelect'];
    $indexDeb = $_SESSION['PurgeJoueurIndexDeb'];
  } else {
      if(!isset($indexDeb))	$indexDeb=0;
      if(!isset($PageSelect)) $PageSelect=1;
    }
}


switch($sesUser["idPosition_fk"])
{
	case "1":
		//gK
		$keeperColor = "#BBBBEE";
		break;
		
	case "2":
		// cD
		$defenseColor = "#BBBBEE";
		break;
		
	case "3":
		// Wg
		$constructionColor = "#DDDDDD";
		$ailierColor = "#BBBBEE";
		$defenseColor = "#DDDDDD";
		$passeColor = "#DDDDDD";
		break;
		
    case "4":
		//IM
		$constructionColor = "#BBBBEE";
		$defenseColor = "#DDDDDD";
		$passeColor = "#DDDDDD";
		break;
		
	case "5":
		// Fw
		$passeColor = "#BBBBEE";
		$buteurColor = "#BBBBEE";
		break;	
}


$lstPos = listAllPosition();
$lstTypeCarac = listTypeCarac();
$lstCarac = listCarac("ASC",30);
$lstTrain=listEntrainement();

function afficheTraining($lstTrain,$idTraining){
	if($idTraining == "0"){
		echo "?";
		return ;
	}
	foreach($lstTrain as $l){
		if($idTraining == $l["id_type_entrainement"]){ 
			echo $l["libelle_type_entrainement"];
			return;
		}
	}
}

/*Calcul dynamique de l'age*/
$SqlAgeJoueur="floor((datediff(CURRENT_DATE,'1970-01-01')-(574729200/86400)-ht_joueurs.datenaiss)/112)";
$SqlJourJoueur="round(mod(datediff(CURRENT_DATE,'1970-01-01')-(574729200/86400)-ht_joueurs.datenaiss,112))";
$ordreDeTri=" ORDER BY ht_joueurs.idHattrickJoueur ASC";

$sqlreel = "SELECT  
              ht_entrainement.valeurEnCours,
              ht_entrainement.nbSemaineGardien,
              ht_entrainement.nbSemaineConstruction,
              ht_entrainement.nbSemainePasses,
              ht_entrainement.nbSemaineDefense,
              ht_entrainement.nbSemaineAilier,
              ht_entrainement.nbSemaineButeur,
              ht_joueurs.idJoueur,
              ht_joueurs.idHattrickJoueur,
              ht_joueurs.prenomJoueur,
              ht_joueurs.nomJoueur,
              ht_joueurs.entrainement_id,
              ht_joueurs.optionJoueur,
              ht_joueurs.idExperience_fk,
              ht_joueurs.idEndurance,
              ht_joueurs.idGardien,              
              ht_joueurs.idLeader_fk,
              ht_joueurs.idConstruction,
              ht_joueurs.idPasse,
              ht_joueurs.idDefense,
              ht_joueurs.idButeur,
              ht_joueurs.idAilier,
              ht_joueurs.idPA,
              ht_joueurs.ht_posteAssigne,
              ht_joueurs.dateDerniereModifJoueur,
              ht_joueurs.dateSaisieJoueur,
              ht_joueurs.archiveJoueur,
              ht_joueurs.teamid,
              ROUND(ht_joueurs.salary/ht_pays.coefSalary) AS salaireDeBase,
              ht_joueurs.salary,
              ".$SqlAgeJoueur." as ageJoueur,
              ".$SqlJourJoueur." as jourJoueur,
              ht_caracteristiques.intituleCaracFR as Lib_Niv_entrainement,
              ht_clubs.isBot,
              ht_joueurs.joueurActif,
              ht_clubs.idUserHT";
$sql= " FROM ht_joueurs
        	INNER JOIN ht_clubs ON teamid = idClubHT
        	INNER JOIN ht_pays ON idPays_fk = idPays
        	LEFT JOIN ht_entrainement ON idJoueur_fk = idJoueur
        	LEFT JOIN ht_caracteristiques ON ht_clubs.niv_Entraineur =ht_caracteristiques.idCarac
    	  WHERE ( (ht_clubs.isBot=1) or (ht_clubs.isBot=2) or (ht_joueurs.joueurActif=0) or (ht_clubs.idUserHT=0) )";
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

<p>
<u>Mode d'emploi :</u><br>
<ul>
<li>1 - Activer les liens vers HT en saisissant le num&eacute;ro de serveur HT auquel vous etes connect&eacute; (Dans votre barre de navigation, il s'agit des 2 chiffres se trouvant apr&egrave;s www http://www<b><u>XX</u></b>.hattrick.org):<br>
<?php require("../outils/define_numserveurHT.php");?>
<li>2 - Cliquer sur le motif de purge du joueur que vous souhaitez supprimer afin de v&eacute;rifier que la purge est justifi&eacute;e. Une nouvelle page Hattrick s'ouvre et pointe directement sur le joueur ou l'&eacute;quipe en question. <b>Attention</b> : Ces 2 premi&egrave;res &eacute;tape sont falcutatives mais il est recommand&eacute; de v&eacute;rifier sur Hattrick que la purge du joueur est justifi&eacute;e. <br>
<li>3 - Si la v&eacute;rification est OK alors supprimer le joueur en cliquant sur : <br><img border=0 src="../images/Poubelle.png"><br>
<i>Le joueur ne sera pas archiv&eacute; mais supprim&eacute; de la base ! En cas d'erreur de manipulation, contacter un d&eacute;veloppeur. Seuls les DTN# et les DTN+ sont habilit&eacute;s &agrave; supprimer des joueurs !</i>
</ul>
</p>

<p>
<u>Ci-dessous la liste des joueurs propos&eacute;s &agrave; la purge :</u>
<ul>
<?php
// Si Niveau accès différent d'Admin (1) ou sélectionneur (4), on filtre sur les joueurs du secteur du DTN)
if ( ($sesUser["idNiveauAcces"]!="1") && ($sesUser["idNiveauAcces"]!="4")){
		$sql=$sql." AND ( ht_posteAssigne=".$sesUser["idPosition_fk"] ." OR ht_posteAssigne=0 )" ;
?>
<li>Dans la cat&eacute;gorie <b><font color="#CC2233"><?=$lstPos[$sesUser["idPosition_fk"]-1]["descriptifPosition"]?></font></b> ou joueurs <font color="#CC2233"><b>non assign&eacute;s</b></font> 
<?php }


?>
<li> Joueurs tri&eacute;s par identifiant Hattrick
</ul>
</p>


 
<?php if (isset($msg)) {?><center><font color=red><?=$msg?></font></center><?php } 

$retour = $maBase->select("SELECT  count(*) as nb ".$sql);

$nbjoueur=$retour[0]["nb"];
if ($indexDeb>=$nbjoueur) $indexDeb=0;
$sql=$sqlreel.$sql.$ordreDeTri."	LIMIT ".$indexDeb.",50;";

$lstJ = $maBase->select($sql);


?>

<TABLE BORDER="0" CELLPADDING="2" CELLSPACING="0" WIDTH="100%">
		<TR>

				<TD COLSPAN="3" BGCOLOR="#DDDDDD" ALIGN="center">
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

    if ($nbjoueur%50==0) {$nbPages=floor(($nbjoueur / 50));} 
    else {$nbPages=floor(($nbjoueur / 50)+1);}
    
    if ($nbPages>1) {?>

				<FORM ACTION="purgeJoueurs.php" METHOD="POST">
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
        
        if ($PageSelect>$nbPages) $PageSelect=1;
        
        ?>
  		  <OPTION VALUE="<?=$k?>" <?php if ($k==$PageSelect) {?>SELECTED<?php }?> > <?=$indexDeb?> &agrave; <?=$indexFin?></OPTION>
        <?php
        $k++;
      } // Fin While
      
         ?> 
         </SELECT>
	       <INPUT TYPE="hidden" NAME="action" VALUE="submitted" >
	       <INPUT TYPE="hidden" NAME="tbIndexDeb" VALUE="<?=serialize($tbIndexDeb)?>" >
	       <INPUT TYPE="submit" VALUE="OK">
	    </form><?php
	       
    } // Fin Si il y a plus de 1 page
} // Fin else (= au moins 1 joueur trouvé)
?>
				</TD>
			</TR>
<?php
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
				<TD>
				  <?php if ($nbPages==1) {$numResu=$j+1;} else {$numResu=$j+$tbIndexDeb[$PageSelect];}?>
					<font color="#CC2233"><?=$numResu?>.</font> <A HREF="../joueurs/fiche.php?id=<?=$lstJ[$j]["idJoueur"]?>"><?=$lstJ[$j]["prenomJoueur"]?> <?=$lstJ[$j]["nomJoueur"]?> </A>&nbsp; 
					  <?php if($lstJ[$j]["optionJoueur"]) echo "<font color=\"#CC22DD\">[<i>".$option[$lstJ[$j]["optionJoueur"]]["FR"]."</i>]</font>"?>
					  &nbsp;|&nbsp;Secteur : 
					 <?php if ($lstJ[$j]["ht_posteAssigne"]!=0){?>
					  <?=$lstPos[$lstJ[$j]["ht_posteAssigne"]-1]["descriptifPosition"]?>
					  <?php }else{ ?>
					  	<b><font color="#3C9478">aucun<font></b>
					  	<?php } 
					  	if ($lstJ[$j]["archiveJoueur"]!=0){?>
					       &nbsp;|&nbsp;<b><font color="red">[Joueur Archiv&eacute;]<font></b>
					    <?php }?>
				</TD>
				<TD align=right>
				Entra&icirc;nement Actuel : [ <?php afficheTraining($lstTrain,$lstJ[$j]["entrainement_id"]); ?>  ]
				&nbsp;|&nbsp;
				Entraineur : <?=$lstJ[$j]["Lib_Niv_entrainement"]?>
				</TD>
				<TD VALIGN="top">
				&nbsp;
				</TD>
			</TR>
			
			<TR>
				<TD VALIGN="top">

					<B>Suivi par </B> <?=$dtnDuJoueur?><BR>
					<B>Age:&nbsp; </B><?=$lstJ[$j]["ageJoueur"]?> ans <?=$lstJ[$j]["jourJoueur"]?> jours / <b>XP</b> : <?=$lstCarac[$lstJ[$j]["idExperience_fk"]]["intituleCaracFR"]?> / <b>TDC</b> : <?=$lstCarac[$lstJ[$j]["idLeader_fk"]]["intituleCaracFR"]?><BR>
					<B>TSI :&nbsp; </B> <?=$lstJ[$j]["valeurEnCours"]?> / <b>id</b> : ( <?=$lstJ[$j]["idHattrickJoueur"]?> )<BR>					
					<B>Salaire :&nbsp; </B> <?=round(($lstJ[$j]["salary"]/10),2)?> ( <?=$lstJ[$j]["salaireDeBase"]/10?> ) <BR>					
					<B>Mis &agrave; jour : &nbsp; </B>&nbsp;<img src="../images/time_<?=$img_nb?>.gif" onMouseOver="return escape('<?=$zealt?>')" > 
				</TD>
				<TD VALIGN="top">								
					<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0">
					<TR>
						<TD width=25% ><B>Endurance:&nbsp; </B></TD><TD width=25% ><?=$lstCarac[$lstJ[$j]["idEndurance"]]["intituleCaracFR"]?></TD>
						<TD width=25% ><B>&nbsp; &nbsp; Gardien:&nbsp;</B></TD> <TD bgcolor="<?=$keeperColor?>" width=25% ><?=$lstCarac[$lstJ[$j]["idGardien"]]["intituleCaracFR"]?><?php afficheLesPlus($lstJ[$j],"nbSemaineGardien"); ?></TD>

					</TR>
					<TR>
						<TD><B>Construction:&nbsp; </B></TD><TD bgcolor="<?=$constructionColor?>"><?=$lstCarac[$lstJ[$j]["idConstruction"]]["intituleCaracFR"]?><?php afficheLesPlus($lstJ[$j],"nbSemaineConstruction"); ?></TD>
						<TD><B>&nbsp; &nbsp; Passe:&nbsp; </B></TD><TD bgcolor="<?=$passeColor?>"><?=$lstCarac[$lstJ[$j]["idPasse"]]["intituleCaracFR"]?><?php afficheLesPlus($lstJ[$j],"nbSemainePasses"); ?></TD>
					</TR>

					<TR>
						<TD><B>Ailier: &nbsp;</B></TD> <TD bgcolor="<?=$ailierColor?>"><?=$lstCarac[$lstJ[$j]["idAilier"]]["intituleCaracFR"]?><?php afficheLesPlus($lstJ[$j],"nbSemaineAilier"); ?></TD>
						<TD><B>&nbsp; &nbsp; D&eacute;fense:&nbsp; </B></TD><TD bgColor="<?=$defenseColor?>"><?=$lstCarac[$lstJ[$j]["idDefense"]]["intituleCaracFR"]?><?php afficheLesPlus($lstJ[$j],"nbSemaineDefense"); ?></TD>
					</TR>
					<TR>
						<TD><B>Buteur:&nbsp; </B></TD><TD bgcolor="<?=$buteurColor?>"><?=$lstCarac[$lstJ[$j]["idButeur"]]["intituleCaracFR"]?><?php afficheLesPlus($lstJ[$j],"nbSemaineButeur"); ?></TD>
						<TD><B>&nbsp; &nbsp; Coups francs:&nbsp; </B></TD><TD><?=$lstCarac[$lstJ[$j]["idPA"]]["intituleCaracFR"]?></TD>
					</TR>
					<TR>
						<TD colspan=4><B>Motif Purge:&nbsp;
						<?php if (empty($_SESSION['numServeurHT'])){?>
                  <?php if ($lstJ[$j]["isBot"]==1)      {?><font color="red">[BOT]</font><?php }?>&nbsp;
                  <?php if ($lstJ[$j]["isBot"]==2 && $lstJ[$j]["idUserHT"]==0) {?><font color="red">[Pas de manager humain]</font><?php }?>&nbsp;
                  <?php if ($lstJ[$j]["joueurActif"]==0){?><font color="red">[Disparu sur HT]</font><?php }?>
                <font color="green"><i>D&eacute;finir num serveur HT pour activer les liens</i></font>
						  <?php } else {
                  if ($lstJ[$j]["isBot"]==1)      {?><a href="http://www<?=$_SESSION['numServeurHT']?>.hattrick.org/Club/?TeamID=<?=$lstJ[$j]["teamid"]?>" target="_BLANK"><font color="red">[BOT]</font></a><?php }?>&nbsp;
                <?php if ($lstJ[$j]["isBot"]==2 && $lstJ[$j]["ht_clubs.idUserHT"]==0) {?><a href="http://www<?=$_SESSION['numServeurHT']?>.hattrick.org/Club/?TeamID=<?=$lstJ[$j]["teamid"]?>" target="_BLANK"><font color="red">[Pas de manager humain]</font></a><?php }?>&nbsp;
                <?php if ($lstJ[$j]["joueurActif"]==0){?><a href="http://www<?=$_SESSION['numServeurHT']?>.hattrick.org/Club/Players/Player.aspx?PlayerID=<?=$lstJ[$j]["idHattrickJoueur"]?>" target="_BLANK"><font color="red">[Disparu sur HT]</font></a><?php }
              }?>
            </TD>
					</TR>

					</TABLE>
				</TD>
				<TD VALIGN="middle">
				  <!--FORMULAIRE SUPPRESSION JOUEUR-->
  				<FORM ACTION="../form.php" METHOD="POST">
  				<INPUT border=0 src="../images/Poubelle.png" type="image" Value="submit" align="middle">
  				<INPUT type="hidden" name="infJoueur" Value=<?=urlencode(serialize($lstJ[$j]))?>>
  				<INPUT type="hidden" name="mode" Value="supprJoueurDepuisPagePurge">
  				<INPUT TYPE="hidden" NAME="action" VALUE="submitted" >
	        <INPUT TYPE="hidden" NAME="tbIndexDeb" VALUE="<?=serialize($tbIndexDeb)?>" >
	        <INPUT TYPE="hidden" NAME="PagesResu" VALUE="<?=$PageSelect?>" >
          </FORM>
        </TD>	
      
			</TR>
			<TR>
			   <TD colspan="3">
			   <HR>
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
	Pas de joueurs correspondent.

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