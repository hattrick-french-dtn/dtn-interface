<?php
require_once("../includes/head.inc.php");
require("../includes/serviceEntrainement.php");
require("../includes/serviceJoueur.php");
require("../includes/serviceMatchs.php");
require("../includes/langue.inc.php");
require("../CHPP/config.php");
require("../includes/serviceListesDiverses.php");

if(!$_SESSION['sesUser']["idAdmin"])
{
	header("location: ../index.php?ErrorMsg=Session Expiree");
	exit();
}

$lang = "FR";

global $msg;

if (isset($htid))
{
  $joueurDTN = getJoueurHt($htid);
  $id = $joueurDTN["idJoueur"];
  if (empty($joueurDTN)) {
    header("location: ../joueurs/verifPlayer.php");
    exit;
  }
} else {
  $joueurDTN = getJoueur($id);
}

// Variable pour menuJoueur.php
$idHT = $joueurDTN["idHattrickJoueur"];
$idClubHT=$joueurDTN["teamid"];

$lstCaracJoueur = array($endurance["$lang"]=>$joueurDTN["idEndurance"],
                        $gardien["$lang"]=>$joueurDTN["idGardien"],
                        $defense["$lang"]=>$joueurDTN["idDefense"],
                        $construction["$lang"]=>$joueurDTN["idConstruction"],
                        $ailier["$lang"]=>$joueurDTN["idAilier"],
                        $passe["$lang"]=>$joueurDTN["idPasse"],
                        $buteur["$lang"]=>$joueurDTN["idButeur"],
                        $pa["$lang"]=>$joueurDTN["idPA"]
            );

$verifInternational = verifSelection($id);
$lstBehaviour=list_behaviour();
$lstRole=list_role();

// Récupération de l'entrainement subit par le joueur
$nom_entrainement=getEntrainementName($joueurDTN["entrainement_id"],listEntrainement());

// Variable d'age
$ageetjours = ageetjour($joueurDTN["datenaiss"]);
$tabage = explode(" - ",$ageetjours);
$htms = htmspoint($tabage[0], $tabage[1], $joueurDTN["idGardien"], $joueurDTN["idDefense"], $joueurDTN["idConstruction"], $joueurDTN["idAilier"], $joueurDTN["idPasse"], $joueurDTN["idButeur"], $joueurDTN["idPA"]);


// Extraction des données Historique Joueur
$actualSeason=getSeasonWeekOfMatch(mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')));
if (!isset($saison)){$saison=$actualSeason['season'];}

$sqlreel = "SELECT  
              CAL.season,
              CAL.week,
              HJ.*,
              M.*";

$sql= " FROM 
          (($tbl_joueurs as J CROSS JOIN $tbl_calendrier as CAL 
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
        WHERE CAL.season=$saison
        AND CAL.date_deb < DATE_ADD(CURDATE(), INTERVAL 1 DAY)
        AND J.idJoueur=$id";

$ordreDeTri=" ORDER BY CAL.week DESC,M.date_match DESC";
$limitSql=" LIMIT 0,10";

$sqlHJ=$sqlreel.$sql.$ordreDeTri.$limitSql;
$reqHJ = $conn->query($sqlHJ);

?>

<html>
<head>
<title>Fiche <?=$joueurDTN["prenomJoueur"]?> <?=$joueurDTN["nomJoueur"]?></title>

<script language="JavaScript" type="text/JavaScript">
function AlertNumServeurHT()
{ 
  alert("Vous devez d\351finir le num\351ro de serveur Hattrick auquel vous \352tes actuellement connect\351 !\n Pour connaitre votre num\351ro de serveur :\n- Aller sur votre fen\352tre Hattrick\n- Il s'agit des 2 chiffres qui suivent 'http://www' et qui pr\351c\350dent '.hattrick.org'\n- Saisir ce num\351ro en bas de la page a l'endroit pr\351vu et valider"); 
  return; 
} 

<!--

function checkSuppression()
{
  if( <?=$joueurDTN["idJoueur"]?>== "" ||isNaN(<?=$joueurDTN["idJoueur"]?>)){
    alert('erreur lors de la suppression... Avertir l\'equipe technique merci.');
  }


  if (confirm('Voulez vous VRAIMENT supprimer ce joueur "<?=$joueurDTN["idHattrickJoueur"]?>" ?')){
    document.location="../form.php?mode=supprJoueur&id=<?=$joueurDTN["idJoueur"]?>";
  }
}

function checkArchivageSansSecteur()
{
  if( <?=$joueurDTN["ht_posteAssigne"]?>== 0){
    alert('Vous ne pouvez pas archiver ce joueur car il est sans secteur défini.');
  }
  else {
    document.location= "../form.php?mode=archiveJoueur&id=<?=$joueurDTN["idJoueur"]?>";
  }
  
}

function submitSupprimeDTN()
{
  if (confirm('Voulez vous VRAIMENT retirer ce joueur de son DTN ?')){
    document.formSupprimeDTN.submit();
  }
}
function submitSupprimeSecteur()
{
  if( <?=$joueurDTN["archiveJoueur"]?>== 1){
    alert('Vous ne pouvez pas retirer ce joueur de son secteur car il est archivé.');
  }
  else {
	  if (confirm('Voulez vous VRAIMENT retirer ce joueur de son Secteur de jeu ?')){
      document.formSupprimeSecteur.submit();
  }
  }
}


function submitSel()
{
document.formSelection.submit();
}//-->
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

$huit = 60 * 60 * 24 * 8; //time_0
$quinze = 60 * 60 * 24 * 15; //time_1
$trente = 60 * 60 * 24 * 30; //time_2
$twomonths = 60 * 60 * 24 * 40; //time_3
$fourmonths = 60 * 60 * 24 * 50; //time_4
      
// Date du jour
$mkday = mktime(0,0,0,date('m'), date('d'),date('Y'));

// Date de la dernière connexion du club 
$date = explode("-",$joueurDTN['date_last_connexion']);
$datemaj =  mktime(0,0,0,$date[1],$date[2],$date[0]);

$img_nb=0;
if ($datemaj >$mkday -$huit){
  $img_nb=0;
}else if ($datemaj >$mkday -$quinze){
  $img_nb=1;
}else if ($datemaj >$mkday -$trente){
  $img_nb=2;  
}else if ($datemaj >$mkday -$twomonths){
  $img_nb=3;
}else if ($datemaj >$mkday -$fourmonths){
  $img_nb=4;
}else{
  $img_nb=5;
}


?>


<table width="95%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td colspan="3" bgcolor="#000000"><b><div align="center"><font color="#FFFFFF">Fiche consultation 
        <?php if($verifInternational != ""){?>
           &nbsp;<img src="../images/fr.gif" alt="<?=$verifInternational?>"><?php 
        } ?>
        </font></div></b>
        </td>
      </tr>
      <tr>
        <td valign="top">
          <table width="90%" border="0" cellpadding="0" cellspacing="0">
            <tr> 
              <td colspan="3">&nbsp;</td>
            </tr>
            <tr> 
              <td width="40%" align="left">&nbsp; <font color="#000099"><b>Info: <?=$joueurDTN["idHattrickJoueur"]?>&nbsp;-&nbsp;<?=$joueurDTN["prenomJoueur"]?>&nbsp;<?php if (isset($joueurDTN["surnomJoueur"])) echo '"'.$joueurDTN["surnomJoueur"].'" '; ?><?=$joueurDTN["nomJoueur"]?>&nbsp;-&nbsp;<?php 
              echo $tabage[0];?>&nbsp;ans&nbsp;-&nbsp;<?=$tabage[1]?>&nbsp;jours<br>&nbsp; Salaire: <?=number_format(round(($joueurDTN["salary"]/10),2),"0"," "," ")?>&nbsp;&euro;/semaine&nbsp;
			  <br/>&nbsp; HTMS: <?=$htms["value"]?> (<?=$htms["potential"]?>)
              </b></font></td>
              <td width="25%" align="center">
              <b>Club : </b><a href="<?=$url?>/clubs/fiche_club.php?idClubHT=<?=$joueurDTN["teamid"]?>"><?php if ($joueurDTN["isBot"]!=0) {echo '<b><font color="red">[BOT]</b></font>';}?><?=$joueurDTN["nomClub"]?></a> <img src="../images/time_<?=$img_nb?>.gif" title="Derni&egrave;re connexion du propri&eacute;taire sur HT, il y a <?=($mkday-$datemaj)/(60*60*24)?> jour(s)">
              <?php if (existAutorisationClub($idClubHT,null)==false) {?>
                <img height="16" src="../images/non_autorise.JPG" title="Ce club n'a pas autoris&eacute; la DTN &agrave; acc&eacute;der &agrave; ses donn&eacute;es">
              <?php } else {?>
                <img height="16" src="../images/Autorise.PNG" title="Ce club a autoris&eacute; la DTN &agrave; acc&eacute;der &agrave; ses donn&eacute;es">
              <?php }?><a href="https://hattrickportal.pro/Tracker/Player.aspx?playerID=<?=$joueurDTN["idHattrickJoueur"]?>" target="_blank"><img src="../images/htportal.png" width="16" title="Voir le joueur sur HT Portal"></a>

              <BR>
                <?php if (!empty($_SESSION['numServeurHT'])){?>
                  &nbsp;<a href="http://www<?=$_SESSION['numServeurHT']?>.hattrick.org/Club/?TeamID=<?=$joueurDTN['teamid']?>&SendMessage=true" target="_NEW"
                <?php }else{?>
                  &nbsp;<a href="#" onClick='AlertNumServeurHT();'
                <?php }?>
                alt="ht">HT-mail</a>
	<?php 	if ($sesUser["idNiveauAcces"] == "4") { // Mise à jour sur Hattrick pour le sélectionneur
    ?>          <form method="post" action="../maliste/miseajourunique.php">
                <input type="hidden" name="joueur" value= <?=$joueurDTN["idHattrickJoueur"]?> />
                <input type="submit" value="Mettre &agrave; jour sur Hattrick" />
                </form>
	<?php	}?>
              </td>

              <td  width="35%" nowrap align="right">
              
              <?php
              // ####################### Si Joueur Suivi #######################       
              if($joueurDTN["dtnSuiviJoueur_fk"] != 0){
              ?>  
                <font color="#000000"><strong>Ce joueur est suivi par  
              <?php
              if($_SESSION['sesUser']["idNiveauAcces"] !=3){
                ?> <a href ="liste_suivi.php?dtn=<?=$joueurDTN["dtnSuiviJoueur_fk"]?>"><?=$joueurDTN["loginAdminSuiveur"]?></a><?php
                if ( ($_SESSION['sesUser']["idNiveauAcces"] == 1) || ($_SESSION['sesUser']["idNiveauAcces"]==2 && ( ($_SESSION['sesUser']["idPosition_fk"]==$joueurDTN["ht_posteAssigne"]) || ($_SESSION['sesUser']["idPosition_fk"]==0) ) ) ){
                            
                  if($joueurDTN["dtnSuiviJoueur_fk"] != 0){
                  ?>                     
                  &nbsp;<a href="javascript:submitSupprimeDTN()"><u>[<font color="red"><i>D&eacute;saffecter</i></font>]</u></a>
                  <?php
                  }
                }
                }else{
                  ?><?=$joueurDTN["loginAdminSuiveur"]?><?php 
                }?> 
              &nbsp;</strong></font><?php     
              }
              
              // ####################### Si Joueur Non suivi et archivé ####################### 
              else if($joueurDTN["archiveJoueur"] == 1){
                 ?><font color="#FF0000"><strong>Ce joueur est archiv&eacute;&nbsp;</strong></font><?php
                 
              // ####################### Si Joueur Non suivi et non archivé #######################
              }else {
                ?><font color="#FF0000"><strong>Ce joueur n'est pas suivi ! &nbsp; </strong></font><?php
                if(($_SESSION['sesUser']["idNiveauAcces"]==2 && ($_SESSION['sesUser']["idPosition_fk"]==$joueurDTN["ht_posteAssigne"]  ||  $_SESSION['sesUser']["idPosition_fk"] == 0) ) ||  $_SESSION['sesUser']["idNiveauAcces"] == 1)
                {
                    ?><form name="form1" method="post" action="../form.php"><div align="right">
                    <input name="mode" type="hidden" id="mode2" value="assigne1JoueurDTN">
                    <input name="htid" type="hidden" id="mode2" value="<?=$joueurDTN["idHattrickJoueur"]?>">
                    <input name="idJoueur" type="hidden" id="mode2" value="<?=$joueurDTN["idJoueur"]?>">
                    <select name="idDtn" id="select">
                    <?php
                    if ($_SESSION['sesUser']["idPosition_fk"] == 0) {
                      $sql = "select * from $tbl_admin where idPosition_fk = 0 AND idNiveauAcces_fk IN (2,3) AND affAdmin = 1 ";
                    } else {
                      $sql = "select * from $tbl_admin where idPosition_fk = ".$joueurDTN["ht_posteAssigne"]." AND affAdmin = 1 ";
                    }
                    $req = $conn->query($sql);
                    foreach($req as $lst){
						echo "<option value = ".$lst["idAdmin"]." $etat >".$lst["loginAdmin"]."";
						if($total[$lst["idAdmin"]] != 0){
							echo " (".$total[$lst["idAdmin"]].")";
						}
						echo "</option>";
                    }
                    ?>
                    </select>
                    <input type="submit" name="Submit" value="Assigner">
                    </div></form><?php
                }
              }      
              ?>
              
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr> 
        <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
      </tr>
      <tr> 
        <td>
        <table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td width="50%"><font color="#000099"><b>&nbsp;<?=$joueurDTN["intitulePosition"]?><?php
          if($joueurDTN["ht_posteAssigne"] != 0){
      
            if(($_SESSION['sesUser']["idNiveauAcces"]==2 && $_SESSION['sesUser']["idPosition_fk"]==$joueurDTN["ht_posteAssigne"]) ||  $_SESSION['sesUser']["idNiveauAcces"] == 1){?>
            &nbsp;<a href="javascript:submitSupprimeSecteur()"><u>[<font color="red"><i>D&eacute;saffecter</i></font>]</u></a>         
            <?php }
          }?></b></font>
          </td>
          <td width="25%">&nbsp;</td>
          <td width="25%"><div align="right">Joueur soumis par <strong><?=$joueurDTN["loginAdminSaisie"]?> </strong>&nbsp; </div></td>
        </tr>
        <tr> 
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>
          <div align="right"><?php 
          if ( ($joueurDTN["archiveJoueur"] != 1) && (($_SESSION['sesUser']["idNiveauAcces_fk"] ==2 && $_SESSION['sesUser']["idPosition_fk"]==$joueurDTN["ht_posteAssigne"]) || ($_SESSION['sesUser']["idNiveauAcces_fk"] ==1)) )  {?>
            [ <a href="javascript:checkArchivageSansSecteur()">Archiver ce joueur </a>]
          <?php } else  if ( ($joueurDTN["archiveJoueur"] == 1) && (($_SESSION['sesUser']["idNiveauAcces_fk"] ==2 && $_SESSION['sesUser']["idPosition_fk"]==$joueurDTN["ht_posteAssigne"]) || ($_SESSION['sesUser']["idNiveauAcces_fk"] ==2 && $joueurDTN["ht_posteAssigne"]==0) || ($_SESSION['sesUser']["idNiveauAcces_fk"] ==1)) )  {?>
            [ <a href="../form.php?mode=desarchiveJoueur&id=<?=$joueurDTN["idJoueur"]?>">D&eacute;sarchiver ce joueur </a>]        
          <?php }
          if($_SESSION['sesUser']["idNiveauAcces"] == 1 ){?>
            [ <a href="javascript:checkSuppression()">Supprimer ce joueur </a>]               
          <?php }
        
          if($_SESSION['sesUser']["idNiveauAcces"] == 4 ){?>                
            <form name="formSelection" method="post" action="../form.php">                     
            <input name="idJoueur" type="hidden" id="idJoueur" value="<?=$id?>">
            <?php if($verifInternational != ""){?>
              <input name="mode" type="hidden" id="mode" value="joueurSelectionOff">
            <?php }else{ ?>
              <input name="mode" type="hidden" id="mode" value="joueurSelectionOn">
            <?php }?>
            <input name="selectionFrance" type="hidden" id="mode" value="<?=$_SESSION['sesUser']["selection"]?>">
            <a href="javascript:submitSel()"><u>S&eacute;lectionnable?</u></font></a>
            </form>  
          <?php }?>
          </div>
          </td>
        </tr>
        <tr> 
          <td colspan="2">          
		  <?php if( $joueurDTN["numLeader"]>=6){?>
		    Un type 
            <?=$joueurDTN["intituleCaractereFR"]?>
            qui est 
            <?=$joueurDTN["intituleAggresFR"]?>
            et 
            <?=$joueurDTN["intituleHonneteteFR"]?>
            .<br>
            Il a une 
            <?=$joueurDTN["nomXP_fr"];?>
            exp&eacute;rience et un 
            <font color="#CC22DD"><?=$joueurDTN["intituleLeaderFR"]?></font>
            temp&eacute;rament de chef
			<?php } else { ?>
			Un type 
            <?=$joueurDTN["intituleCaractereFR"]?>
            qui est 
            <?=$joueurDTN["intituleAggresFR"]?>
            et 
            <?=$joueurDTN["intituleHonneteteFR"]?>
            .<br>
            Il a une 
            <?=$joueurDTN["nomXP_fr"];?>
            exp&eacute;rience et un 
            <?=$joueurDTN["intituleLeaderFR"]?>
            temp&eacute;rament de chef
			<?php } ?>
          </td>
          <td><div align="center">
            <span class="Style1">
            <?php if($msg == "archive") echo "Joueur correctement archiv&eacute;";?>
            <?php if($msg == "desarchive") echo "Joueur correctement desarchiv&eacute;";?>
            </span></div>
          </td>     
        </tr>
      
        <tr> 
          <td colspan="3"> 
            <?php if($joueurDTN["optionJoueur"]) echo "<font color=\"#CC22DD\"><i>Specialit&eacute; : ".$option[$joueurDTN["optionJoueur"]]["FR"]."</i></font><br />"?>
            <br />
          </td>
        </tr>
        
      
        <!-- Debut carac physiques -->
        <tr> 
          <td colspan="3">Caract&eacute;ristiques physiques</td>
        </tr>
        <tr bgcolor="#000000"> 
          <td colspan="3"><img src="../images/spacer.gif" width="1" height="1"></td>
        </tr>
        <tr>
        <td colspan="3">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <?php 
            $i=1;
            foreach($lstCaracJoueur as $int=>$val){
      
              switch($int){
                case "construction":
                $nbSemaineE = '(+'.$joueurDTN["nbSemaineConstruction"].')';
                break;
                case "defense":
                $nbSemaineE = '(+'.$joueurDTN["nbSemaineDefense"].')';
                break;
                
                case "buteur":
                $nbSemaineE = '(+'.$joueurDTN["nbSemaineButeur"].')';
                break;
          
                case "ailier":
                $nbSemaineE = '(+'.$joueurDTN["nbSemaineAilier"].')';
                break;
                case "gardien":
                $nbSemaineE = '(+'.$joueurDTN["nbSemaineGardien"].')';
                break;
              
                case "passe":
                $nbSemaineE = '(+'.$joueurDTN["nbSemainePasses"].')';
                break;
              
                case "coup franc":
                $nbSemaineE = '(+'.$joueurDTN["nbSemaineCoupFranc"].')';
                break;
            
                default:
                $nbSemaineE ="";
                break;
              }
              
              $sql =  "select * from $tbl_caracteristiques where numCarac = ".$val;
              $req = $conn->query($sql);
              $res = $req->fetch(); ?>
                
                  
              <tr <?php if ($i % 2 == 0) {?>bgcolor="#EEEEEE"<?php }?>>
              <td  width="20%"><div align="left"><b><?=$int?> :</b></div></td>
              <td width="30%"><div align="left"><?php for ($j=1; $j<=$res["numCarac"]; $j++) {?><img src="../images/carre.JPG">&nbsp;<?php }?></div></td>
              <td width="50%"><div align="left">&nbsp;<?='['.$res["numCarac"].'] '.$res["intituleCaracFR"]?> <?=$nbSemaineE?></div></td>
              </tr><?php  
              $i++;
            }?>
            <tr>
              <td colspan=3>
                <div align="left"><em>
                <br />
                Type d'entrainement : <?=$nom_entrainement?><br />
                Derni&egrave;re maj DTN : <?=dateToHTML($joueurDTN["dateDerniereModifJoueur"])?><br />
                Derni&egrave;re maj propri&eacute;taire : <?=dateToHTML($joueurDTN["dateSaisieJoueur"])?><br /><br />
                </em></div>
              </td>
            </tr>

          </table>
        </td>
        </tr>
        <!-- fin carac physiques -->

        <!-- debut histo Modifs-->
        <tr> 
          <td colspan="3">Historique des modifications / remarques </td>
        </tr>
        <tr bgcolor="#000000"> 
          <td colspan="3"><img src="../images/spacer.gif" width="1" height="1"></td>
        </tr>
        <tr>
          <td colspan="3">
          <br />
            
          <table width="98%" style="border:1px solid #C5C7C7" align="center" cellpadding="0" cellspacing="0" bgcolor="#000000" rules=COLS>
            <tr bgcolor="#85A275"> 
              <td width=10%><div align="center" style="font-size: 9pt;color: white"><b>Date</b></div></td>
              <td width=10%><div align="center" style="font-size: 9pt;color: white"><b>Heure</b></div></td>
              <td width=65%><div align="center" style="font-size: 9pt;color: white"><b>Info joueur [<?=strtolower($joueurDTN["prenomJoueur"])?> <?=strtolower($joueurDTN["nomJoueur"])?>]</b></div></td>
              <td width=15%><div align="center" style="font-size: 9pt;color: white"><b>Auteur</b></div></td>
            </tr>
          
            <?php
			$sql = "SELECT DISTINCT all_histo.*
                      FROM
                        (
                          (SELECT 
                            HM1.idHisto,
                            HM1.dateHisto AS dateHisto,
                            HM1.heureHisto AS heureHisto,
                            HM1.intituleHisto AS intituleHisto,
                            AD1.loginAdmin AS loginAdmin
                          FROM 
                            $tbl_histomodif HM1
                            LEFT JOIN $tbl_admin AD1 ON AD1.idAdmin = HM1.idAdmin_fk 
                          WHERE HM1.idJoueur_fk = $id )
                          UNION
                          (SELECT 
                            HM2.idHisto,
                            HM2.dateHisto AS dateHisto,
                            HM2.heureHisto AS heureHisto,
                            HM2.intituleHisto AS intituleHisto,
                            AD2.loginAdmin AS loginAdmin
                          FROM 
                            ($tbl_histomodif HM2
                            LEFT JOIN $tbl_admin AD2 ON AD2.idAdmin = HM2.idAdmin_fk),
                            $tbl_clubs_histo_joueurs HCJ
                          WHERE HCJ.id_joueur = $id 
                          AND   HCJ.id_clubs_histo=HM2.id_clubs_histo) 
                        ) all_histo
                      ORDER BY all_histo.dateHisto desc, all_histo.heureHisto desc 
                      LIMIT 0,5";
        
              $i=1;
              foreach($conn->query($sql) as $l) { ?>
                <tr <?php if ($i % 2 == 0) {?>bgcolor="#EEEEEE"<?php } else {?>bgcolor="#FFFFFF"<?php }?>>
                  <td><div align="center"><?=dateToHTML($l["dateHisto"])?></div></td>
                  <td><div align="center"><?=$l["heureHisto"]?></div></td>
                  <td><div align="left">&nbsp;<?=$l["intituleHisto"]?></div></td>
                  <td><div align="center"><?=$l["loginAdmin"]?></div></td>
                </tr>
                <?php $i++;
              }?>
          <tr bgcolor="#FFFFFF" style="border:1px solid #C5C7C7">
          <td colspan=11>
          <div align="left" valign="middle" style="padding:0.5em;">
          <img src="../images/triangle1.JPG"  border="0" align="absmiddle">
          <a href="<?=$url?>/joueurs/histoJoueur.php?htid=<?=$idHT?>" class="Mauve">Afficher tout</a>
          <img src="../images/triangle2.JPG"  border="0" align="absmiddle">
          </div>
          </td>
          </tr>
          </table>
          <!-- fin Histo Modifs-->
          
          <br>
          <br>
          
          <!-- fin Histo Joueur-->          
          <table width="98%" style="border:1px solid #C5C7C7" align="center" cellpadding="0" cellspacing="0" bgcolor="white" rules=COLS>
            <tr bgcolor="#DCC061" align="center">
              <td>Semaine</td>
              <td>Forme</td>
              <td>TSI</td>
              <td>Salaire</td>
              <td>XP</td>
              <td>Blessure</td>
              <td>En vente?</td>
              <td colspan=2>Match</td>
              <td>Etoiles</td>
              <td>Poste</td>
            </tr>          
            <?php $j=0;
            
            foreach($reqHJ as $lstHJ){?>
            <tr <?php if ($j % 2 == 0) {?>bgcolor="#EEEEEE"<?php } else {?>bgcolor="white"<?php }?>>
              <td align="left"><?=$lstHJ["season"].'.'.$lstHJ["week"]?></td>
              <td align="center"><?=$lstHJ["forme"]?></td>
              <td><?=$lstHJ["tsi"]?></td>
              <td><?=$lstHJ["salaire"]?></td>
              <td align="center"><?=$lstHJ["xp"]?></td>
              <td>
              <?php if ($lstHJ["blessure"]==null) {?>&nbsp;<?php }
                else {if ($lstHJ["blessure"]==0) {?><img src="../images/pansement.JPG" title="Pansement"><?php }
                      else {if ($lstHJ["blessure"]>0) {?><img src="../images/blessure.JPG" title="<?=$lstHJ["blessure"]?> semaine(s)"><?=$lstHJ["blessure"]?>
                <?php }}}?>
              </td>
              <td>
              <?php if ($lstHJ["transferListed"]==1) {?><img src="../images/enVente.JPG" title="Plac&eacute; sur la liste des transferts"><?php }?>
              </td>
              <?php if ($lstHJ["date_match"]!=null){?>
                <td><?=$lstHJ["date_match"]?></td>
                
                <td><?=$abbrTypeMatch[$lstHJ["idTypeMatch_fk"]]?></td>
                <td align=center bgcolor=#fded84><b><?=$lstHJ["etoiles"]?></b></td>
                <td><?php $role=get_role_byID($lstHJ["id_role"],$lstRole);
                          $behaviour=get_behaviour_byID($lstHJ["id_behaviour"],$lstBehaviour);
                          echo($role["nom_role_abbrege"].' '.$behaviour["nom_behaviour"]);?>
                </td>
                
              <?php } else {?>
                <td colspan=4><font color=orange><i>Pas de match trouv&eacute;</i></font></td>
              <?php }?>
            </tr>
            <?php $j++;
            }?>
            <tr bgcolor="#FFFFFF" style="border:1px solid #C5C7C7">
            <td colspan=11>
            <div align="left" valign="middle" style="padding:0.5em;">
            <img src="../images/triangle1.JPG"  border="0" align="absmiddle">
            <a href="<?=$url?>/joueurs/rapportDetaille.php?htid=<?=$idHT?>" class="Mauve">Afficher tout</a>
            <img src="../images/triangle2.JPG"  border="0" align="absmiddle">
            </div>
            </td>
            </tr>
          </table>
          <!-- fin Histo Joueur-->
          
          <br>
          <br>
          
          <!-- debut histo Club-->
          <table width="98%" style="border:1px solid #C5C7C7" align="center" cellpadding="0" cellspacing="1" rules=COLS>
            <tr bgcolor="#85A275">
              <td width=12%><div align="center" style="font-size: 9pt;color: white"><b>Date</b></div></td>
              <td width=10%><div align="center" style="font-size: 9pt;color: white"><b>Cr&eacute;&eacute; par</b></div></td>
              <td width=8%><div align="center" style="font-size: 9pt;color: white"><b>Entrainement</b></div></td>
              <!-- ajout niveau entraineur -->
              <td width=5%><div align="center" style="font-size: 9pt;color: white"><b>Entraineur</b></div></td>
              <td width=7%><div align="center" style="font-size: 9pt;color: white"><b>Intensit&eacute;</b></div></td>
              <td width=5%><div align="center" style="font-size: 9pt;color: white"><b>Endu</b></div></td>
              <td width=5%><div align="center" style="font-size: 9pt;color: white"><b>Adj.</b></div></td>
              <td width=5%><div align="center" style="font-size: 9pt;color: white"><b>Medecin</b></div></td>
              <td width=5%><div align="center" style="font-size: 9pt;color: white"><b>Prepa. phys.</b></div></td>
              <td width=43%><div align="center" style="font-size: 9pt;color: white"><b>Info club [<?=$joueurDTN["nomClub"]?>]</b></div></td>
            </tr>
            <?php
			$sqlClubsHisto = " SELECT DATE_FORMAT(ht_clubs_histo.date_histo,'%d/%m/%Y %H:%i:%s') AS date_histo,
                                        ht_clubs_histo.role_createur,
                                        ht_clubs_histo.cree_par,
                                        ht_type_entrainement.libelle_type_entrainement AS CaracEntraine,
                                        ht_clubs.niv_Entraineur,
                                        ht_clubs_histo.intensite,
                                        ht_clubs_histo.endurance,
                                        ht_clubs_histo.adjoints,
                                        ht_clubs_histo.medecin,
                                        ht_clubs_histo.physio,
                                        ht_clubs_histo.Commentaire,
                                        ht_clubs_histo.intensite
                                FROM    ($tbl_clubs_histo 
                                      INNER JOIN 
                                        $tbl_clubs
                                        ON ht_clubs.idClubHT=ht_clubs_histo.idClubHT)
                                      LEFT JOIN    
                                        $tbl_type_entrainement2
                                        ON ht_clubs_histo.idEntrainement=ht_type_entrainement.id_type_entrainement
                                WHERE ht_clubs_histo.idClubHT=".$joueurDTN["teamid"]." 
                                ORDER BY ht_clubs_histo.date_histo desc 
                                LIMIT 0,10";
      
            $i=1;
            foreach($conn->query($sqlClubsHisto) as $lHisto){
              if ($lHisto["role_createur"]=="D") {$lHisto["createur"]='[DTN]';}
              else if ($lHisto["role_createur"]=="P") {$lHisto["createur"]='[Proprio]';}
              $lHisto["createur"].=$lHisto["cree_par"];?>
          
              <tr <?php if ($i % 2 == 0) {?>bgcolor="#EEEEEE"<?php } else {?>bgcolor="#FFFFFF"<?php }?>>
                <td > <div align="center"><?=$lHisto["date_histo"]?></div></td>
                <td > <div align="left"><?=$lHisto["createur"]?></div></td>
                <td > <div align="left"><?=$lHisto["CaracEntraine"]?></div></td>
                <!-- ajout niveau entraineur -->
                <td > <div align="center"><?=$lHisto["niv_Entraineur"]?></div></td>
                <td > <div align="center"><?=$lHisto["intensite"]?></div></td>
                <td > <div align="center"><?=$lHisto["endurance"]?></div></td>
                <td > <div align="center"><?=$lHisto["adjoints"]?></div></td>
                <td > <div align="center"><?=$lHisto["medecin"]?></div></td>
                <td > <div align="center"><?=$lHisto["physio"]?></div></td>
                <td > <div align="left"><?=$lHisto["Commentaire"]?></div></td>
              </tr>
              <?php $i++;
            }?>
            <tr bgcolor="#FFFFFF" style="border:1px solid #C5C7C7">
            <td colspan=7>
            <div align="left" valign="middle" style="padding:0.5em;">
            <img src="../images/triangle1.JPG"  border="0" align="absmiddle">
            <a href="<?=$url?>/clubs/fiche_club.php?idClubHT=<?=$joueurDTN["teamid"]?>" class="Mauve">Afficher tout</a>
            <img src="../images/triangle2.JPG"  border="0" align="absmiddle">
            </td>
            </tr>
          </table>
          
          <p align="center"><br></p>
          </td>
        </tr>
        </td>
      </tr>
      <!-- fin Histo club-->
      

      
      <!-- debut valeur par poste-->
      <tr> 
        <td colspan="4">Valeur par poste</td>
      </tr>
      <tr bgcolor="#000000"> 
        <td colspan="4"><img src="../images/spacer.gif" width="1" height="1"></td>
      </tr>
      <tr> 
        <td colspan="4" align="center">
        <br>
        <table width="90%" border="0" cellspacing="1" cellpadding="0" bgcolor="#8CE4C8" >
        <tr bgcolor=white>
        <td colspan="8" align="center">gK : <b><?=$joueurDTN["scoreGardien"]?></b></td>
        </tr><tr align="center" bgcolor="white">
        <td>wB `off : <b><?=$joueurDTN["scoreDefLatOff"]?></b></td>
        <td colspan="3" >cD `normal : <b><?=$joueurDTN["scoreDefense"]?></b></td>
        <td colspan="3" >cD `off : <b><?=$joueurDTN["scoreDefCentralOff"]?></b></td>
        <td>wB `normal : <b><?=$joueurDTN["scoreDefLat"]?></b></td>
        </tr><tr align="center" bgcolor="white" valign="center">
        <td>Wg `normal : <b><?=$joueurDTN["scoreAilier"]?></b><br>
        Wg `off : <b><?=$joueurDTN["scoreAilierOff"]?></b></td>
        <td colspan="2">iM `def : <b><?=$joueurDTN["scoreMilieuDef"]?></b></td>
        <td colspan="2">iM `normal : <b><?=$joueurDTN["scoreMilieu"]?></b></td>
        <td colspan="2">iM `off : <b><?=$joueurDTN["scoreMilieuOff"]?></b></td>
        <td>Wg `towards : <b><?=$joueurDTN["scoreAilierVersMilieu"]?></b></td>
        </tr><tr align="center" bgcolor="white">
        <td colspan="2">Fw `towards : <b><?=$joueurDTN["scoreAttaquantVersAile"]?></b></td>
        <td colspan="2">Fw `normal : <b><?=$joueurDTN["scoreAttaquant"]?></b></td>
        <td colspan="4">Fw `def : <b><?=$joueurDTN["scoreAttaquantDef"]?></b></td>
        </tr>
        </table>
        <br>
        </td>
      </tr>
      <!-- fin valeur par poste-->
      
    </table>

    <div align="center"><a href="javascript:history.go(-1);">Retour</a></div>
    <br>
    <hr>
    Pour contacter par HT Mail le propri&eacute;taire, saisissez le num&eacute;ro de serveur HT auquel vous etes connect&eacute;. Dans votre barre de navigation, il s'agit des 2 chiffres se trouvant apr&egrave;s www http://www<b><u>XX</u></b>.hattrick.org.
    <?php require("../outils/define_numserveurHT.php");?>
    
    
    
    <form name="formSupprimeDTN" method="post" action="../form.php">
      <input name="idJoueur" type="hidden"value="<?=$id?>">
      <input name="dtnname" type="hidden" value="<?=$joueurDTN["loginAdminSuiveur"]?>">
      <input name="mode" type="hidden"  value="joueurSupprimeDTN">
    </form>  
    
    
    <form name="formSupprimeSecteur" method="post" action="../form.php">
      <input name="idJoueur" type="hidden"value="<?=$id?>">
      <input name="secteur" type="hidden" value="<?=$joueurDTN["intitulePosition"]?>">
      <input name="mode" type="hidden"  value="joueurSupprimeSecteur">
    </form>
  </table>
  
  <table width="450"  border="0" align="center" cellspacing=0 >
    <tr>
      <td colspan=5 width="450" ><center>L&eacute;gende : </center></td>
      
    </tr>
    <tr>
      <td><img src="../images/time_0.gif"></td>
      <td>Derni&egrave;re connexion récente </td>
      <td width="1" bgcolor="#000000"></td>
      <td>&nbsp;<img src="../images/time_3.gif"></td>
      <td>Derni&egrave;re connexion il y a + de 30 jours </td>
    </tr>
    <tr>
      <td><img src="../images/time_1.gif"></td>
      <td>Derni&egrave;re connexion il y a + de 8 jours </td>
      <td width="1" bgcolor="#000000"></td>
      <td>&nbsp;<img src="../images/time_4.gif"></td>
      <td>Derni&egrave;re connexion il y a + de 40 jours </td>
    </tr>
    <tr>
      <td><img src="../images/time_2.gif"></td>
      <td>Derni&egrave;re connexion il y a + de 15 jours </td>
      <td width="1" bgcolor="#000000"></td>
      <td>&nbsp;<img src="../images/time_5.gif"></td>
      <td>Derni&egrave;re connexion il y a + de 50 jours </td>
    </tr>
  </table>    
  </td>
  </tr>
</table>

</body>
</html>
