<?php

require("../includes/head.inc.php");
require("../includes/serviceEntrainement.php");
require("../includes/serviceJoueur.php");
require("../includes/serviceEquipes.php");
require("../includes/langue.inc.php");
require("../includes/serviceListesDiverses.php");
require("../includes/serviceMatchs.php");
require("../CHPP/config.php");
require_once("../fonctions/AccesBase.php"); // fonction de connexion a la base
require_once("../_config/CstGlobals.php"); 
$maBase = initBD();

if(!$_SESSION['sesUser']["idAdmin"])
{
	header("location: ../index.php?ErrorMsg=Session Expiree");
}

global $msg;


if(!isset($lang)) $lang = "FR";
if(!isset($url)) $url = "rapportDetaille.php";

if (isset($htid))
{
	$infJ = getJoueurHt($htid);
	$id = $infJ["idJoueur"];
}
else $infJ = getJoueur($id);
	
$actualSeason=getSeasonWeekOfMatch(mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')));
if (!isset($saison)){$saison=$actualSeason['season'];}

$lstBehaviour=list_behaviour();
$lstRole=list_role();

// Requête d'extraction des matchs de la saison sélectionnée
$SqlAgeJoueur=getCalculAgeAnneeSQL('IFNULL(M.date_match_age,HJ.date_histo)');
$SqlJourJoueur=getCalculAgeJourSQL('IFNULL(M.date_match_age,HJ.date_histo)');

$sqlreel = "SELECT  
              J.idJoueur,
              J.idHattrickJoueur,
              J.prenomJoueur,
              ".$SqlAgeJoueur." as ageJoueur,
              ".$SqlJourJoueur." as jourJoueur,
              J.nomJoueur,
              J.optionJoueur,
              J.ht_posteAssigne,
              J.teamid,
              C.nomClub,
              CAL.season,
              CAL.week,
              HJ.*,
              M.*";

$sql= " FROM 
          ((($tbl_joueurs as J CROSS JOIN $tbl_calendrier as CAL 
          LEFT JOIN 
            ( SELECT  
                HISTO.id_joueur_fk,
                HISTO.date_histo,
                HISTO.forme,
                HISTO.tsi,
                HISTO.xp,
                HISTO.blessure,
                HISTO.salaire,
                HISTO.transferListed,
                HISTO.week as weekHJ
              FROM $tbl_joueurs_histo as HISTO
              WHERE HISTO.season=$saison
            ) as HJ ON (J.idHattrickJoueur = HJ.id_joueur_fk AND HJ.weekHJ=CAL.week) ) 
          LEFT JOIN
            ( SELECT
                PERF.id_joueur,
                PERF.id_match,
                DATE_FORMAT(PERF.date_match,'%d/%m/%Y') as date_match,
                PERF.date_match as date_match_age,
                PERF.week  as weekM,
                PERF.id_club,
                PERF.id_role,
                PERF.id_position,
                PERF.id_behaviour,
                concat(PERF.etoile,'/',PERF.etoileFin) etoiles,
                PERF.idTypeMatch_fk
              FROM $tbl_perf as PERF
              WHERE PERF.season=$saison
            ) as M ON (J.idHattrickJoueur = M.id_joueur AND M.weekM=CAL.week) )
          LEFT JOIN
          $tbl_clubs as C ON M.id_club = C.idClubHT)
        WHERE J.affJoueur = '1'
        AND CAL.season=$saison
        AND CAL.date_deb < DATE_ADD(CURDATE(), INTERVAL 1 DAY)
        AND J.idJoueur=$id";

$ordreDeTri=" ORDER BY CAL.week DESC,M.date_match DESC";

$sql=$sqlreel.$sql.$ordreDeTri;

$lstJ = $maBase->select($sql);

/*$nbPage = round(count($lstMT) / 10);
if(!isset($limMin)) $limMin = 0;
if(!isset($limMax)) $limMax = 10;

$limitMatch .= " limit $limMin, 10";
$lstM = listMatchJoueur($infJ["idHattrickJoueur"]);
*/

?><html>
<head>
<title>Fiche joueur</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<?php
// Gestion de l'affichage des menus
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
$idHT=$infJ['idHattrickJoueur'];
$idClubHT=$infJ["teamid"];

require("../menu/menuJoueur.php");

?>
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<table width="95%"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr> 
    <td height="15" bgcolor="#000000">
    <div align="center"><font color="#FFFFFF"><strong>Matchs</strong></font></div></td>
  </tr>
  <tr>
    <td valign="top">
    
      <table width="85%" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr> 
          <td width="50%" align="left">&nbsp; <font color="#000099"><b><?=$infJ["idHattrickJoueur"]?>&nbsp;-&nbsp;<?=$infJ["nomJoueur"]?> <?=$infJ["prenomJoueur"]?>&nbsp;-&nbsp;<?=$infJ["ageJoueur"]?>&nbsp;ans&nbsp;-&nbsp;<?=$infJ["intitulePosition"]?> 
                <a href="http://alltid.org/player/<?=$infJ["idHattrickJoueur"]?>" target="_blank"><img src="../images/ahstats.png" width="47" height="16" border="0" align="absmiddle"></a></b></font></td>
          <td width="20%" align="left"colspan="2"><b>Club Actuel : </b><a href="<?=$url?>/clubs/fiche_club.php?idClubHT=<?=$infJ["teamid"]?>"><?=$infJ["nomClub"]?></a>
          </td>
        </tr>
      </table>
      
      <br><br>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="6"><div align="center">Historique des matchs de ce joueur</div></td>
        </tr>
        <tr>
          <td colspan="6">&nbsp;</td>
        </tr>
	     <tr>
	        
	        <font face="Courrier New">
          <center> <link href="/dtn/interface/css/ht2.css" rel="stylesheet" type="text/css">
          	<table class="cadre" width="100%">
            <tr class="activ">
              <td>Saison</td>
              <td>Semaine</td>
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
            <?php
			$j=0;
            while ($j<count($lstJ)){?>
            <tr>
              <td><?=$lstJ[$j]["season"]?></td>
              <td><?=$lstJ[$j]["week"]?></td>
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
                  
                <?php if (empty($_SESSION['numServeurHT'])){?>
          		    <td><?=$lstJ[$j]["id_match"]?></td>
          		  <?php } else {?>
                  <td><a href="http://www<?=$_SESSION['numServeurHT']?>.hattrick.org/Club/Matches/Match.aspx?matchID=<?=$lstJ[$j]["id_match"]?>" target="_BLANK"><?=$lstJ[$j]["id_match"]?></a></td>
                <?php }?>
                <?php if ($lstJ[$j]["date_match"]!=null){?>
                  <td><?=$lstJ[$j]["date_match"]?></td>
                  <td><?=$abbrTypeMatch[$lstJ[$j]["idTypeMatch_fk"]]?></td>
                  <td align=center bgcolor=#fded84><b><?=$lstJ[$j]["etoiles"]?></b></td>
                  <td><?php $role=get_role_byID($lstJ[$j]["id_role"],$lstRole);
                            $behaviour=get_behaviour_byID($lstJ[$j]["id_behaviour"],$lstBehaviour);
                            echo($role["nom_role_abbrege"].' '.$behaviour["nom_behaviour"]);?>
                  </td>
                <?php } else {?>
                  <td colspan=3><font color=orange><i>Pas de match trouv&eacute;</i></font></td>
                <?php }?>
            </tr>
            <?php $j++;
            }?>
          </table>
	        
	     </tr>
   
       <tr>
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <br>
          <td width="30%">
      		  <?php if($saison!=$actualSeason['season']){?>
      		    <a href = ?id=<?=$id?>&saison=<?=$saison+1?>>&lt;&lt; Saison <?=$saison+1?>
      		  <?php }?>
		      </td>
          <td width="50%"></td>
          <td width="30%">
            <div align="right">
            <?php
            // Recherche de la saison la plus ancienne pour laquelle un match est répertorié
            $sql = "SELECT min(season) as season
                    FROM $tbl_perf
                    WHERE id_joueur=$idHT";
            
            $minSaison = $maBase->select($sql);

            if($saison>$minSaison[0]['season']){?>
      		    <a href = ?id=<?=$id?>&saison=<?=$saison-1?>>&gt;&gt; Saison <?=$saison-1?>
      		  <?php }?>
        		</div>
          </td>
        </tr>
      </table>
      </tr>
    </font></table>
    <br>
    <?php
	if($msg) echo "<center><font color = red>".$msg."</font></center>";
	?><br>
    </td>
  </tr>
</table>            
     <p align="center"><a href="javascript:history.go(-1);">Retour</a> </p>
</body>
</html><?php  deconnect(); ?>

