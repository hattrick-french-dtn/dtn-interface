<?php
require_once "../includes/head.inc.php";

if ( (!isset($_SESSION['sesUser'])) || (empty($_SESSION['sesUser'])) )
{
  header("location: ../index.php?ErrorMsg=Session Expiree");
}

require_once "../_config/CstGlobals.php"; // fonctions d'admin
require_once "../fonctions/AccesBase.php"; // fonction de connexion a la base
require_once "../fonctions/AdminDtn.php"; // fonctions d'admin
require_once "../CHPP/config.php"; // Libellé CHPP

$maBase = initBD();
        
require("../includes/serviceListesDiverses.php");
require("../includes/serviceEntrainement.php");
require("../includes/serviceJoueur.php");
require("../includes/langue.inc.php");
require_once "../includes/nomTables.inc.php";
	
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

// Liste des saison et semaine de la table ht_perfs_individuelle
$lstSeasonWeek=listSeasonWeek();
// Liste des secteurs DTN
$lstPos = listAllPosition();


// Initialisation ou prise en compte des variables de formulaires
if (isset($_POST['action']) and $_POST['action'] == 'submitted') {
  if(!isset($indexDeb))	$indexDeb=0;
  if(!isset($PageSelect)) $PageSelect=1;
  //if (!isset($_POST['ht_posteAssigne'])) {$_POST['ht_posteAssigne']=$sesUser['idPosition_fk'];}
  //if (!isset($_POST["seasonWeek"])) {$_POST["seasonWeek"]=$lstSeasonWeek[0]["seasonWeek"];}
}

if (isset($_POST['action']) and $_POST['action'] == 'ChangePageResu') {
  if (isset($_POST['tbIndexDeb'])) {
    $tbIndexDeb = unserialize($_POST['tbIndexDeb']);
    $PageSelect = $_POST['PagesResu'];
    $indexDeb = $tbIndexDeb[$PageSelect]-1;
  } else {
    $indexDeb=0;
    $PageSelect=1;
  }
  if (!isset($_POST['ht_posteAssigne'])) {$_POST['ht_posteAssigne']=$sesUser['idPosition_fk'];}
  if (!isset($_POST["seasonWeek"])) {$_POST["seasonWeek"]=$lstSeasonWeek[0]["seasonWeek"];}
}

if (!isset($_POST['action'])) {
  if (isset($_SESSION['RappMatchPageSelect']) && isset($_SESSION['RappMatchIndexDeb'])) {
    $PageSelect = $_SESSION['RappMatchPageSelect'];
    $indexDeb = $_SESSION['RappMatchIndexDeb'];
  } else {
    if(!isset($indexDeb))	$indexDeb=0;
    if(!isset($PageSelect)) $PageSelect=1;
  }
  if (!isset($_POST['ht_posteAssigne'])) {$_POST['ht_posteAssigne']=$sesUser['idPosition_fk'];}
  if (!isset($_POST["seasonWeek"])) {$_POST["seasonWeek"]=$lstSeasonWeek[0]["seasonWeek"];}
}
$_SESSION['RappMatchPageSelect'] = $PageSelect;
$_SESSION['RappMatchIndexDeb'] = $indexDeb;


/*Calcul dynamique de l'age*/
$SqlAgeJoueur="floor((datediff(CURRENT_DATE,'1970-01-01')-(574729200/86400)-J.datenaiss)/112)";
$SqlJourJoueur="round(mod(datediff(CURRENT_DATE,'1970-01-01')-(574729200/86400)-J.datenaiss,112))";
$ordreDeTri=" ORDER BY J.idHattrickJoueur DESC";

$sqlreel = "SELECT  
              J.idJoueur,
              J.idHattrickJoueur,
              J.prenomJoueur,
              J.nomJoueur,
              ".$SqlAgeJoueur." as ageJoueur,
              ".$SqlJourJoueur." as jourJoueur,
              J.optionJoueur,
              J.ht_posteAssigne,
              J.teamid,
              C.nomClub,
              HJ.*,
              M.*";

$sql= " FROM 
          $tbl_joueurs as J LEFT JOIN 
          ( SELECT  
              HISTO.id_joueur_fk,
              HISTO.forme,
              HISTO.tsi,
              HISTO.xp,
              HISTO.blessure,
              HISTO.salaire,
              HISTO.transferListed
            FROM $tbl_joueurs_histo as HISTO
            WHERE HISTO.season=".substr($_POST["seasonWeek"],1,2)."
            AND HISTO.week=".substr($_POST["seasonWeek"],5,2)."
          ) as HJ ON J.idHattrickJoueur = HJ.id_joueur_fk LEFT JOIN
          ( SELECT
              PERF.id_joueur,
              PERF.id_match,
              DATE_FORMAT(PERF.date_match,'%d/%m/%Y') as date_match,
              PERF.id_club,
              PERF.id_role,
              PERF.id_position,
              PERF.id_behaviour,
              concat(PERF.etoile,'/',PERF.etoileFin) etoiles,
              PERF.idTypeMatch_fk
            FROM $tbl_perf as PERF
            WHERE PERF.season=".substr($_POST["seasonWeek"],1,2)."
            AND PERF.week=".substr($_POST["seasonWeek"],5,2)."
          ) as M ON J.idHattrickJoueur = M.id_joueur LEFT JOIN
          $tbl_clubs as C ON M.id_club = C.idClubHT
        WHERE J.affJoueur = '1'";

        
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
<li>Activer les liens vers HT en saisissant le num&eacute;ro de serveur HT auquel vous etes connect&eacute; (Dans votre barre de navigation, il s'agit des 2 chiffres se trouvant apr&egrave;s www http://www<b><u>XX</u></b>.hattrick.org):<br>
<?php require("../outils/define_numserveurHT.php");?>
<li>Sélectionner la saison + la semaine du rapport de match que vous souhaitez visualiser :<br><br>
<FORM ACTION="rapportMatchs.php" METHOD="POST">
    <center>
    <u>S</u>aison et semaine (<u>W</u>eek):
    <SELECT name="seasonWeek">
      <?php
      $k=0;
      $nbSeasonWeek=count($lstSeasonWeek);
      while($k<=$nbSeasonWeek) 
      {
        ?>
  		  <OPTION VALUE="<?=$lstSeasonWeek[$k]["seasonWeek"]?>" <?php if ($lstSeasonWeek[$k]["seasonWeek"]==$_POST["seasonWeek"]) {?>SELECTED<?php }?> > <?=$lstSeasonWeek[$k]["seasonWeek"]?></OPTION>
        <?php
        $k++;
      } // Fin While?>
      </SELECT>
      &nbsp;&nbsp;&nbsp;&nbsp;
      <B>Secteur de jeu : </B>
				
				<!-- Si DTN alors accès uniquement aux matchs de son secteur --> 
				<?php if ($sesUser["idNiveauAcces"] == 3) {?>
					<INPUT TYPE="hidden" NAME="ht_posteAssigne" VALUE="<?=$sesUser["idPosition_fk"]?>" >
					  <b><font color="#CC2233"><?=$lstPos[$sesUser["idPosition_fk"]-1]["descriptifPosition"]?></font></b><br>
					  
				<?php }else if ($sesUser["idNiveauAcces"] == 2){?>
				<!-- Si DTN+ alors accès uniquement aux matchs de son secteur et aux joueurs non assignés -->
				<SELECT NAME="ht_posteAssigne" SIZE=1>
						<OPTION VALUE="0" <?php if ($_POST['ht_posteAssigne']==0) {?>SELECTED<?php }?>>Non assign&eacute;</OPTION>
						<OPTION VALUE="<?=$sesUser["idPosition_fk"]?>"  <?php if ($_POST['ht_posteAssigne']==$sesUser["idPosition_fk"]) {?>SELECTED<?php }?>><?=$lstPos[$sesUser["idPosition_fk"]-1]["descriptifPosition"]?></OPTION>
				</SELECT>
				
				<?php } else {?>
				<SELECT NAME="ht_posteAssigne" SIZE=1>
						<OPTION VALUE="0" <?php if ($_POST['ht_posteAssigne']==0) {?>SELECTED<?php }?>>Non assign&eacute;</OPTION>
						<OPTION VALUE="1" <?php if ($_POST['ht_posteAssigne']==1) {?>SELECTED<?php }?>>Gardien</OPTION>
						<OPTION VALUE="2" <?php if ($_POST['ht_posteAssigne']==2) {?>SELECTED<?php }?>>D&eacute;fenseur</OPTION>
						<OPTION VALUE="4" <?php if ($_POST['ht_posteAssigne']==4) {?>SELECTED<?php }?>>Milieu de terrain</OPTION>
						<OPTION VALUE="3" <?php if ($_POST['ht_posteAssigne']==3) {?>SELECTED<?php }?>>Ailier</OPTION>
						<OPTION VALUE="5" <?php if ($_POST['ht_posteAssigne']==5) {?>SELECTED<?php }?>>Attaquant</OPTION>
				</SELECT>
        <?php }?>
      <br>
			<br>
      
      <INPUT TYPE="hidden" NAME="action" VALUE="submitted" >
	    <INPUT TYPE="hidden" NAME="PagesResu" VALUE="<?=$_POST['PagesResu']?>" >
	    <INPUT TYPE="submit" VALUE="OK" style="{width: 100}">

	    </center>
</FORM>
</ul>
</p>

<p>
<u>Ci-dessous la liste des joueurs et leurs matchs de la semaine :</u>
<ul>
<?php
$sql=$sql." AND ( J.ht_posteAssigne=".$_POST["ht_posteAssigne"].")" ;
?>
<li>Dans la cat&eacute;gorie 
<?php if ($_POST["ht_posteAssigne"]==0) {?><b><font color="#CC2233">Non Assign&eacute;</font></b> 
<?php } else {?><b><font color="#CC2233"><?=$lstPos[$_POST["ht_posteAssigne"]-1]["descriptifPosition"]?></font></b><?php }?>


<li> Joueurs tri&eacute;s par identifiant Hattrick
</ul>
</p>


<?php $retour = $maBase->select("SELECT  count(*) as nb ".$sql);

$nbmatch=$retour[0]["nb"];
if ($indexDeb>=$nbmatch) $indexDeb=0;
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
	Pas de r&eacute;sultat.

<?php			
}else{

    ?>
				<?=$nbmatch?>&nbsp;enregistrements ont &eacute;t&eacute; trouv&eacute;s.&nbsp;<br> 
    <?php			

    if ($nbmatch%50==0) {$nbPages=floor(($nbmatch / 50));} 
    else {$nbPages=floor(($nbmatch / 50)+1);}
    
    if ($nbPages>1) {?>

				<FORM ACTION="rapportMatchs.php" METHOD="POST">
				 Afficher r&eacute;sultats : <SELECT name="PagesResu">  

          <?php
          $k=1;
          while($k<=$nbPages) 
          {
            $indexDeb=($k*50)-49;
            $indexFin=($k*50);
            
            if ($k==$nbPages) {
              $indexFin=$nbmatch;
            }
      
            $tbIndexDeb[$k]=$indexDeb;
            
            if ($PageSelect>$nbPages) $PageSelect=1;
            
            ?>
      		  <OPTION VALUE="<?=$k?>" <?php if ($k==$PageSelect) {?>SELECTED<?php }?> > <?=$indexDeb?> &agrave; <?=$indexFin?></OPTION>
            <?php
            $k++;
          }?> // Fin While
          
         ?> 
         </SELECT>
	       <INPUT TYPE="hidden" NAME="action" VALUE="ChangePageResu" >
	       <INPUT TYPE="hidden" NAME="seasonWeek" VALUE="<?=$_POST['seasonWeek']?>" >
	       <INPUT TYPE="hidden" NAME="ht_posteAssigne" VALUE="<?=$_POST['ht_posteAssigne']?>" >
	       <INPUT TYPE="hidden" NAME="tbIndexDeb" VALUE="<?=serialize($tbIndexDeb)?>" >
	       <INPUT TYPE="submit" VALUE="OK">
	    </form><?php
	       
    } // Fin Si il y a plus de 1 page
} // Fin else (= au moins 1 joueur trouvé)
?>
				</TD>
			</TR>

        <font face="Courrier New">
        <center> <link href="/dtn/interface/css/ht2.css" rel="stylesheet" type="text/css">
        
          <?php $j=0;
          while ($j<count($lstJ)){
          	if ($j % 10==0){
              	 if ($j!=0){?>
              	   </table><br>
              	 <?php }?>
            	<table class="cadre" width="100%">
              <tr class="activ">
                <td colspan=2>Joueur</td>
                <td>Age</td>
                <td>Forme</td>
                <td>TSI</td>
                <td>Salaire</td>
                <td>XP</td>
                <td>Blessure</td>
                <td colspan=2>Club</td>
                <td>Mis en vente</td>
                <td colspan=3>Match</td>
                <td>Etoiles</td>
                <td>Poste</td>
              </tr>
          	<?php }?>
            <tr>
              <?php if ($lstJ[$j]["idJoueur"]==$lstJ[$j-1]["idJoueur"]){?>
                <i>
                <td colspan=2>...........</td>
                <td colspan=9>&nbsp;</td>
                </i>
              <?php } else {?>
                <td><a href="<?="../joueurs/ficheDTN.php?id=".$lstJ[$j]["idJoueur"]?>"><?=$lstJ[$j]["idHattrickJoueur"]?></a></td><td><b><i> <?=$lstJ[$j]["prenomJoueur"]. " ".$lstJ[$j]["nomJoueur"]?></b></i></td>
                <td><?=$lstJ[$j]["ageJoueur"]." - ".$lstJ[$j]["jourJoueur"]?></td>
                <td><?=$lstJ[$j]["forme"]?></td>
                <td><?=$lstJ[$j]["tsi"]?></td>
                <td><?=$lstJ[$j]["salaire"]?></td>
                <td><?=$lstJ[$j]["xp"]?></td>
                <td>
                <?php if ($lstJ[$j]["blessure"]==null) {?>&nbsp;<?php }
                  else {if ($lstJ[$j]["blessure"]==0) {?><img src="../images/pansement.JPG" title="Pansement"><?php }
                        else {if ($lstJ[$j]["blessure"]>0) {?><img src="../images/blessure.JPG" title="<?=$lstJ[$j]["blessure"]?> semaine(s)"><?=$lstJ[$j]["blessure"]?>
                  <?php }}}?>
                </td>
                <?php if ($lstJ[$j]["nomClub"]!=null) {?>
                  <td><a href="<?="../clubs/fiche_club.php?idClubHT=".$lstJ[$j]["id_club"]?>"><?=$lstJ[$j]["id_club"]?></a></td><td><b><i><?=$lstJ[$j]["nomClub"]?></b></i></td>
                <?php } else {?>
                  <td><?=$lstJ[$j]["id_club"]?></td><td><font color=orange><i>N/A</b></i></font></td>
                <?php }?>
                <td>
                <?php if ($lstJ[$j]["transferListed"]==1) {?><img src="../images/enVente.JPG" title="Plac&eacute; sur la liste des transferts"><?php }?>
                </td>
              <?php }
                
              if (empty($_SESSION['numServeurHT'])){?>
        		    <td><?=$lstJ[$j]["id_match"]?></td>
        		  <?php } else {?>
                <td><a href="http://www<?=$_SESSION['numServeurHT']?>.hattrick.org/Club/Matches/Match.aspx?matchID=<?=$lstJ[$j]["id_match"]?>" target="_BLANK"><?=$lstJ[$j]["id_match"]?></a></td>
              <?php }?>
              <?php if ($lstJ[$j]["date_match"]!=null){?>
                <td><?=$lstJ[$j]["date_match"]?></td>
                <td><?=$abbrTypeMatch[$lstJ[$j]["idTypeMatch_fk"]]?></td>
                <td align=center bgcolor=#fded84><b><?=$lstJ[$j]["etoiles"]?></b></td>
                <td><?=$minifrenchRole[$lstJ[$j]["id_role"]]." ".$frenchBehaviour[$lstJ[$j]["id_behaviour"]]?></td>
              <?php } else {?>
                <td colspan=3><font color=orange><i>Pas de match trouv&eacute;</i></font></td>
              <?php }?>
            </tr>    
        <?php $j++;
        }?>
        </table>
        </font>
      
  		<TR>
  			<TD COLSPAN="2" BGCOLOR="#DDDDDD" ALIGN="center">
  			&nbsp; 
        <?php if(count($lstJ)==0) {?>
          	Pas de match trouv&eacute;.
        <?php }else{?>
      				<?=$nbmatch?>&nbsp;enregistrements ont &eacute;t&eacute; trouv&eacute;s.&nbsp;<br> 
        <?php } // Fin else (= au moins 1 joueur trouvé)?>
  			</TD>
  		</TR>

  </table>
  <br>

</body>