<?php
require_once("../includes/head.inc.php");
require("../includes/serviceEntrainement.php");
require("../includes/serviceJoueur.php");
require("../includes/serviceEquipes.php");
require("../includes/serviceMatchs.php");
require("../includes/langue.inc.php");
require("../includes/serviceListesDiverses.php");
require("../includes/serviceJoueurTeam.php");
require("../includes/nomTables.inc.php");

global $mode;
global $sesUser;

if(!$sesUser["idAdmin"])
{
	header("location: ../index.php?ErrorMsg=Session Expiree");
}

if (isset($htid))
{
	$joueurDTN = getJoueurHt($htid);
	$id = $joueurDTN["idJoueur"];
}
else
	$joueurDTN = getJoueur($id);

switch($sesUser["idNiveauAcces"]){
	case "2":
		if ($sesUser["idPosition_fk"]!= $joueurDTN["ht_posteAssigne"] && $joueurDTN["ht_posteAssigne"]!=0 && $sesUser["idPosition_fk"]!=0){
			print("<html><body><center>Ce joueur est associ&eacute; &agrave; un autre secteur de jeu.</center></body></html>");
			return;
		}
	break;

	case "3":
		if ($sesUser["idPosition_fk"]!= $joueurDTN["ht_posteAssigne"] && $joueurDTN["ht_posteAssigne"]!=0 ){
			print("<html><body><center>Ce joueur est associ&eacute; &agrave; un autre secteur de jeu.</center></body></html>");
			return;
		}
	break;
	
	default;
	break;
}
	
// Variable pour menuJoueur.php
$idHT = $joueurDTN["idHattrickJoueur"];
$idClubHT=$joueurDTN["teamid"];
$infJ = getJoueurHt($idHT);

// Variable d'age
$ageetjours = ageetjour($joueurDTN["datenaiss"]);
$tabage = explode(" - ",$ageetjours);

// Récupération de l'entrainement subit par le joueur
$nom_entrainement=getEntrainementName($joueurDTN["entrainement_id"],listEntrainement());

$lang = "FR";

$lstCaracJoueur = array($endurance["$lang"]=>$joueurDTN["idEndurance"],
                        $gardien["$lang"]=>$joueurDTN["idGardien"],
                        $defense["$lang"]=>$joueurDTN["idDefense"],
                        $construction["$lang"]=>$joueurDTN["idConstruction"],
                        $ailier["$lang"]=>$joueurDTN["idAilier"],
                        $passe["$lang"]=>$joueurDTN["idPasse"],
                        $buteur["$lang"]=>$joueurDTN["idButeur"],
                        $pa["$lang"]=>$joueurDTN["idPA"]
            );	


if($mode == "transfere") $lstClub = listClubs();
//print_r($lstCaracJoueur);
$lstCaractJ = listCarac('ASC',23);
		
$htms = htmspoint($tabage[0], $tabage[1], $joueurDTN["idGardien"], $joueurDTN["idDefense"], $joueurDTN["idConstruction"], $joueurDTN["idAilier"], $joueurDTN["idPasse"], $joueurDTN["idButeur"], $joueurDTN["idPA"]);
//print_r($htms);

?><html><head><title> Fiche DTN <?=$joueurDTN["prenomJoueur"]?> <?=$joueurDTN["nomJoueur"]?></title>

<script>
function AlertNumServeurHT()
{ 
  alert("Vous devez d\351finir le num\351ro de serveur Hattrick auquel vous \352tes actuellement connect\351 !\n Pour connaitre votre num\351ro de serveur :\n- Aller sur votre fen\352tre Hattrick\n- Il s'agit des 2 chiffres qui suivent 'http://www' et qui pr\351c\350dent '.hattrick.org'\n- Saisir ce num\351ro en bas de la page a l'endroit pr\351vu et valider"); 
  return; 
} 

</script>
<script src="../../../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script language="JavaScript" src="../includes/javascript/navigation.js" charset="ISO-8859-1"></script>
<script language="JavaScript" src="../includes/javascript/popup.js" charset="ISO-8859-1"></script>
<link href="../../../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="../css/popup.css" rel="stylesheet" />
<link type="text/css" href="../css/ht2.css" rel="stylesheet" />
<noscript><h1>Cette page nécessite Javascript. Merci d'activer Javascript dans votre explorateur et de rafraichir la page.</h1></noscript>

</head>
<?php

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



if(isset($msg)) {?>
<center><span class="premium"><?=$msg?></span></center><?php } ?>
<table width="85%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
  <td>
    <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
      <tr> 
      <td height="15" bgcolor="#000000"><div align="center"><font color="#FFFFFF"><strong>Fiche DTN </strong></font></div></td>
      </tr>
      <tr>
      <td valign="top">
        <table width="90%" border="0" cellpadding="0" cellspacing="0">
          <tr> 
          <td colspan="3">&nbsp;</td>
          </tr>
          <tr> 
          <td align="left" width="50%">&nbsp;<font color="#000099"> Info: <?=strtolower($joueurDTN["idHattrickJoueur"])?>&nbsp;-&nbsp;<?=strtolower($joueurDTN["prenomJoueur"])?>&nbsp;<?=strtolower($joueurDTN["nomJoueur"])?><?php if (isset($joueurDTN["surnomJoueur"])) echo " (".$joueurDTN["surnomJoueur"].")"; ?>&nbsp;-&nbsp;
          <?=$tabage[0];?>&nbsp;ans&nbsp;-&nbsp;<?=$tabage[1]?>&nbsp;jours<br>&nbsp; Salaire: <?=number_format(round(($joueurDTN["salary"]/10),2),"0"," "," ")?>&nbsp;&euro;/semaine&nbsp;-&nbsp;<?=$joueurDTN["intitulePosition"]?> 
           </font>
     			
	       </td>
	       <td width="20%" align="left" colspan="2">
         <b>Club Actuel : </b><a href="<?=$url?>/clubs/fiche_club.php?idClubHT=<?=$joueurDTN["teamid"]?>"><?php if ($joueurDTN["isBot"]!=0) {?><b><font color="red">[BOT]</b></font><?php }?><?=$joueurDTN["nomClub"]?></a> <img src="../images/time_<?=$img_nb?>.gif" title="Derni&egrave;re connexion du propri&eacute;taire sur HT, il y a <?=($mkday-$datemaj)/(60*60*24)?> jour(s)">
          <?php if (existAutorisationClub($idClubHT,null)==false) {?>
            <img height="16" src="../images/non_autorise.JPG" title="Ce club n'a pas autoris&eacute; la DTN &agrave; acc&eacute;der &agrave; ses donn&eacute;es">
          <?php } else { ?>
            <img height="16" src="../images/Autorise.PNG" title="Ce club a autoris&eacute; la DTN &agrave; acc&eacute;der &agrave; ses donn&eacute;es">
          <?php }
          ?><a href="https://hattrickportal.pro/Tracker/Player.aspx?playerID=<?=$joueurDTN["idHattrickJoueur"]?>" target="_blank"><img src="../images/htportal.png" width="16" title="Voir le joueur sur HT Portal"></a>
      		<?php if (!empty($_SESSION['numServeurHT'])){?>
      			&nbsp;<a href="http://www<?=$_SESSION['numServeurHT']?>.hattrick.org/Club/?TeamID=<?=$idClubHT?>&SendMessage=true" target="_NEW"
      		<?php } else { ?>
      			&nbsp;<a href="#" onClick='AlertNumServeurHT();'
      		<?php } ?>
      		    alt="ht">HT-mail</a>
                <form method="post" action="../maliste/miseajourunique.php">
                <input type="hidden" name="joueur" value= <?=$joueurDTN["idHattrickJoueur"]?> />
                <input type="submit" value="Mettre &agrave; jour sur Hattrick" />
                </form>
        </td>
		</tr>
		<tr>
		<td align="left" width="50%">&nbsp;<font color="#000099"> HTMS: <?=$htms["value"]?> (<?=$htms["potential"]?>)</font>
		</td>
		<td align="left" width="50%">&nbsp;</td>
		</tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr> 
        <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        </tr>
        <tr> 
        <td>
          <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr> 
            <td width="25%" height="15"><font color="#000099">Popularit&eacute;</font></td>
            <td width="25%" height="15"> <div align="left"><?=$joueurDTN["numCaractere"]?> (<?=$joueurDTN["intituleCaractereFR"]?>)</div></td>
            <td width="25%" height="15"> <div align="left"><font color="#000099">Temp&eacute;rament de chef</font></div></td>
            <td width="12%" height="15"> <div align="left"><?=$joueurDTN["numLeader"]?> (<?=$joueurDTN["intituleLeaderFR"]?>)</div></td>
            <td width="13%" height="15">&nbsp;</td>
            </tr>
            <tr> 
            <td width="25%" height="15"><font color="#000099">Agressivit&eacute;</font></td>
            <td width="25%" height="15"> <div align="left"><?=$joueurDTN["numAggres"]?> (<?=$joueurDTN["intituleAggresFR"]?>)</div></td>
            <td width="25%" height="15"> <div align="left"><font color="#000099"></font></div></td>
            <td width="25%" height="15" colspan="2"> <div align="center"></div></td>
            </tr>
            <tr> 
            <td width="25%" height="15"><font color="#000099">Honn&ecirc;tet&eacute;</font></td>
            <td width="25%" height="15"> <div align="left"><?=$joueurDTN["numHonnetete"]?> (<?=$joueurDTN["intituleHonneteteFR"]?>)</div></td>
            <td width="25%" height="15"> <div align="left"><font color="#000099">Exp&eacute;rience</font></div></td>
            <td width="12%" height="15"> <div align="left"><?=$joueurDTN["idExperience_fk"]?> (<?=$joueurDTN["nomXP_fr"];?>)</div></td>
            </tr>
            <!--Ajout TSI par Musta le 24/09/2008-->
            <tr>
            <td width="25%" height="15"><font color="#000099">TSI</font></td>
            <td width="25%" height="15"> <div align="left"> <?=number_format($joueurDTN["valeurEnCours"], "0"," ", " ")?> </div></td>
            </tr>
          </table>
        </td>
        </tr>  
      </table>
      <br>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
      <td height="25" valign="bottom">Caract&eacute;ristiques physiques <?php if( $joueurDTN["optionJoueur"] != 0){?>[<font color="#CC22DD"><?=$option[$joueurDTN["optionJoueur"]]["FR"]?></b></font>]<?php } ?></td>
      </tr>
      <tr> 
      <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
      </tr>
      </table>
      <br>
      <center>
      <tr> 
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <td width="50%"> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <form name="formnbsem" method="post" action="../form.php" id="formnbsem">
              <tr bgcolor="#85A275"> 
              <td><div align="left" style="font-size: 9pt;color: white"><b>Carac.</b></div></td>
              <td colspan=3><div align="center" style="font-size: 9pt;color: white"><b>Niveau</b></div></td>
              <td><div align="left" style="font-size: 9pt;color: white"><b>Nbre sem. entrainement</b></div></td>
              </tr>
              <?php $i=1;
              foreach($lstCaracJoueur as $int=>$val){
                unset($nomColCarac);
                unset($nomColNbSem);

                switch($int){
                  case "construction":
                  $nbSemaineE = '(+'.$joueurDTN["nbSemaineConstruction"].')';
                  $nomColCarac = 'idConstruction';
                  $nomColNbSem = 'nbSemaineConstruction';
                  break;
                  case "defense":
                  $nbSemaineE = '(+'.$joueurDTN["nbSemaineDefense"].')';
                  $nomColCarac = 'idDefense';
                  $nomColNbSem = 'nbSemaineDefense';
                  break;
                  
                  case "buteur":
                  $nbSemaineE = '(+'.$joueurDTN["nbSemaineButeur"].')';
                  $nomColCarac = 'idButeur';
                  $nomColNbSem = 'nbSemaineButeur';
                  break;
            
                  case "ailier":
                  $nbSemaineE = '(+'.$joueurDTN["nbSemaineAilier"].')';
                  $nomColCarac = 'idAilier';
                  $nomColNbSem = 'nbSemaineAilier';
                  break;
                  
                  case "gardien":
                  $nbSemaineE = '(+'.$joueurDTN["nbSemaineGardien"].')';
                  $nomColCarac = 'idGardien';
                  $nomColNbSem = 'nbSemaineGardien';
                  break;
                
                  case "passe":
                  $nbSemaineE = '(+'.$joueurDTN["nbSemainePasses"].')';
                  $nomColCarac = 'idPasse';
                  $nomColNbSem = 'nbSemainePasses';
                  break;

                  case "coup franc":
                  $nbSemaineE = '(+'.$joueurDTN["nbSemaineCoupFranc"].')';
                  $nomColCarac = 'idPA';
				  $nomColNbSem = 'nbSemaineCoupFranc';
                  break;

                  default:
                  $nbSemaineE ="";
                  break;
                }
                
                $sql =  "select * from $tbl_caracteristiques where numCarac = ".$val;
                $req = $conn->query($sql);
                $res = $req->fetch();
              
                
                ?>
                <tr <?php if ($i % 2 == 0) {?>bgcolor="#EEEEEE"<?php } ?> >
                <td><b><?=$int?> :</b></td>
                <td><?php for ($j=1; $j<=$res["numCarac"]; $j++) {?><img src="../images/carre.JPG">&nbsp;<?php }?></td>
                <td>&nbsp;<?='['.$res["numCarac"].'] '.$res["intituleCaracFR"]?> <?=$nbSemaineE?></td>
                <td>
                  <?php if (isset($nomColCarac)) {?>
                    <?php
                    if($sesUser["idNiveauAcces"] == 1 || ($sesUser["idNiveauAcces"] == 2 && $sesUser["idPosition_fk"] == $joueurDTN["ht_posteAssigne"]) || ($sesUser["idNiveauAcces"] == 3 && $sesUser["idPosition_fk"] == $joueurDTN["ht_posteAssigne"] && (existAutorisationClub($idClubHT,null)==false))){?>
                      &nbsp;|&nbsp;
                      <a href="javascript:majNiveau('<?=$nomColCarac?>','<?=$joueurDTN[$nomColCarac]?>','<?=$joueurDTN[$nomColCarac]+1?>','<?=$id?>')" class="Vert">+ 1</a>
                      <a href="javascript:majNiveau2('<?=$nomColCarac?>','<?=$joueurDTN[$nomColCarac]?>','<?=$joueurDTN[$nomColCarac]-1?>','<?=$id?>')" class="Rouge">- 1</a>
  		              <?php }
                  }?>
                </td>
                <td width="20%">&nbsp;
                  <?php if (isset($nomColNbSem)) {?>
                    <span id="sprytextfield<?=$i?>">
                    <input name="<?=$nomColNbSem?>" type="text" style="height: 1.4em; padding:0em; font-size: 1em;" size="2" value="<?=$joueurDTN[$nomColNbSem]?>" onChange='degriserBouton("bt_maj");'>
                    <span class="textfieldInvalidFormatMsg">Entrez uniquement des chiffres et des nombres non n&eacute;gatifs</span>
                    <span class="textfieldMinValueMsg">Doit etre sup&eacute;rieure &agrave; 0</span>
                    <span class="textfieldMaxValueMsg">Doit etre inf&eacute;rieure &agrave; 100</span>
                    <span class="textfieldRequiredMsg">Obligatoire</span>
                    </span>
                  <?php }?>
                </td>
                
                </tr><?php  
                $i++;
              }?>
              <tr>
                <td colspan=4>
                <div align="left">
                <br>
                <em>Type d'entrainement : <?=$nom_entrainement?></em><br />
                <em>Derni&egrave;re maj DTN : <?=dateToHTML($joueurDTN["dateDerniereModifJoueur"])?></em><br />
                <em>Derni&egrave;re maj propri&eacute;taire : <?=dateToHTML($joueurDTN["dateSaisieJoueur"])?><br /><br /></em>
                </div>
                </td>
                <td><input id="bt_maj" type="submit" value="MAJ Nb. Sem." disabled></td>
              </tr>
              <input name="mode" type="hidden" id="mode" value="updateTraining">
              <input name="idJoueur_fk" type="hidden" id="idJoueur_fk" value="<?=$id?>">
      				<input name="url" type="hidden" id="id4" value="<?=$url?>">
      				
              </form>
            </tr>
          </table>
          </td>
           


        </table>
      </tr>
      <!-- fin carac physiques -->

      <!-- debut histo -->
      <tr> 
        <td colspan="4">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr><td>Historique des modifications / remarques</td></tr> 
        </td>
      </tr>
      <tr bgcolor="#000000"> 
        <td colspan="4"><img src="../images/spacer.gif" width="1" height="1"></td>
      </tr>
      <tr>

      <td colspan="4">
      <br>
          
        <table width="98%" style="border:1px solid #C5C7C7" align="center" cellpadding="0" cellspacing="0" bgcolor="#000000" rules=COLS>
          <tr bgcolor="#85A275"> 
            <td width=20%><div align="center" style="font-size: 9pt;color: white"><b>Date</b></div></td>
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
  
$actualSeason=getSeasonWeekOfMatch(mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')));
if (!isset($saison)){$saison=$actualSeason['season'];}  
          $sqlreel = "SELECT  
              CAL.season,
              CAL.week,
              HJ.*,
              M.*";
          $sql2= " FROM 
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

$sqlHJ=$sqlreel.$sql2.$ordreDeTri.$limitSql;
$reqHJ = $conn->query($sqlHJ);

          foreach($conn->query($sql) as $l) { ?>
          <tr <?php if ($i % 2 == 0) {?>bgcolor="#EEEEEE"<?php } else {?>bgcolor="#FFFFFF"<?php }?>>
            <td><div align="left"><?=dateToHTML($l["dateHisto"]).' '.$l["heureHisto"]?></div></td>
            <td><div align="left">&nbsp;<?=$l["intituleHisto"]?></div></td>
            <td><div align="left"><?=$l["loginAdmin"]?></div></td>
          </tr>
          <?php $i++;
        }?>
        <tr bgcolor="#FFFFFF" style="border:1px solid #C5C7C7">
        <td colspan=11>
        <div align="left" valign="middle" style="padding:0.5em;">
            <img src="../images/triangle1.JPG"  border="0" align="absmiddle">
            <a href="<?=$url?>/joueurs/histoJoueur.php?htid=<?=$idHT?>" class="Mauve">Afficher tout</a>
            <img src="../images/triangle2.JPG"  border="0" align="absmiddle">
            <img src="../images/triangle1.JPG"  border="0" align="absmiddle">
            <a href="#" onclick="openPopup('formAddHistoJoueur.php?idJoueur=<?=$joueurDTN["idJoueur"]?>&test=test');" class="Mauve">Ajouter</a>
            <img src="../images/triangle2.JPG"  border="0" align="absmiddle">
        </div>
        </td>
        </tr>
        </table>
        <br>
        <br>

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
         <br>
         <br>

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
            <!-- ajout niveau entraineur (ligne 599) -->
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
          foreach($conn->query($sqlClubsHisto) as $lHisto) {
            if ($lHisto["role_createur"]=="D") {$lHisto["createur"]='[DTN]';}
            else if ($lHisto["role_createur"]=="P") {$lHisto["createur"]='[Proprio]';}
            $lHisto["createur"].=$lHisto["cree_par"];?>
        
            <tr <?php if ($i % 2 == 0) {?>bgcolor="#EEEEEE"<?php } else {?>bgcolor="#FFFFFF"<?php }?>>
              <td > <div align="left"><?=$lHisto["date_histo"]?></div></td>
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
          <td colspan=9>

            <div align="left" valign="middle" style="padding:0.5em;">
            <img src="../images/triangle1.JPG"  border="0" align="absmiddle">
            <a href="<?=$url?>/clubs/fiche_club.php?idClubHT=<?=$joueurDTN["teamid"]?>" class="Mauve">Afficher tout</a>
            <img src="../images/triangle2.JPG"  border="0" align="absmiddle">
            <img src="../images/triangle1.JPG"  border="0" align="absmiddle">
            <a href="#" onclick="openPopup('formAddHistoClub.php?idClubHT=<?=$joueurDTN["teamid"]?>&idJoueur=<?=$joueurDTN["idJoueur"]?>&entrainement_id=<?=$joueurDTN["entrainement_id"]?>&role_createur=D');" class="Mauve">Ajouter</a>
            <img src="../images/triangle2.JPG"  border="0" align="absmiddle">
            </div>
            <div align="center" valign="middle">
            <div id="page_action" class="panel" style="position:absolute; top:20%; left:25%; width:650px; height:350px; visibility:hidden;">
            <table style="border:0px;height:20px;width:100%;" cellspacing="0px" cellpadding="0px">
              <tr>
              <td class="name" style="width:95%;padding-left:10px;">
              <!-- Ici se trouve le titre du panneau -->
              Ajout information : Joueur [<?=$joueurDTN["prenomJoueur"]?> <?=$joueurDTN["nomJoueur"]?>] | club [<?=$joueurDTN["nomClub"]?>]
              </td>
              <td class="title" style="text-align:center;"><img src="../images/croix.bmp" onclick="hidePopup();"></td> 
              </tr>
            </table>
          
             <!-- Cet iframe affiche la page demandée ( dans cette exemple test.html ). durant le chargement, elle affiche la page empty.html -->
             <iframe id="page_action_iframe" name="page_action_iframe" src="empty.html" style="width:50%;height:60%;"></iframe>
           </div>

          </div></td>
          </tr>
        </table>
        
        <p align="center"><br></p>
        </td>
      </tr>
      </td>
    </tr>

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
  <script type="text/javascript">
  <!--
   var sprytextfield1= new Spry.Widget.ValidationTextField("sprytextfield1", "integer", {validateOn:["change"],allowNegative:false,minValue:0,maxValue:100,isRequired:true});
   var sprytextfield2= new Spry.Widget.ValidationTextField("sprytextfield2", "integer", {validateOn:["change"],allowNegative:false,minValue:0,maxValue:100,isRequired:true});
   var sprytextfield3= new Spry.Widget.ValidationTextField("sprytextfield3", "integer", {validateOn:["change"],allowNegative:false,minValue:0,maxValue:100,isRequired:true});
   var sprytextfield4= new Spry.Widget.ValidationTextField("sprytextfield4", "integer", {validateOn:["change"],allowNegative:false,minValue:0,maxValue:100,isRequired:true});
   var sprytextfield5= new Spry.Widget.ValidationTextField("sprytextfield5", "integer", {validateOn:["change"],allowNegative:false,minValue:0,maxValue:100,isRequired:true});
   var sprytextfield6= new Spry.Widget.ValidationTextField("sprytextfield6", "integer", {validateOn:["change"],allowNegative:false,minValue:0,maxValue:100,isRequired:true});
   var sprytextfield7= new Spry.Widget.ValidationTextField("sprytextfield7", "integer", {validateOn:["change"],allowNegative:false,minValue:0,maxValue:100,isRequired:true});
   var sprytextfield8= new Spry.Widget.ValidationTextField("sprytextfield8", "integer", {validateOn:["change"],allowNegative:false,minValue:0,maxValue:100,isRequired:true});
  //-->
  </script>
</table>
</body>
</html>
