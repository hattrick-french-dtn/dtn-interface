<?php
require_once ("../includes/head.inc.php");
require ("../includes/serviceListesDiverses.php");
require ("../includes/serviceJoueur.php");

if (!$sesUser["idAdmin"]) {
	header("location: ../index.php?ErrorMsg=Session Expiree");
}
if (!isset ($ordre))
	$ordre = "idHattrickJoueur";
if (!isset ($sens))
	$sens = "ASC";
if (!isset ($lang))
	$lang = "FR";
if (!isset ($masque))
	$masque = 0;
if (!isset ($affPosition))
	$affPosition = 0;
if (!isset ($typeExport))
  $typeExport = "maliste";

require ("../includes/langue.inc.php");

$infPos = getPosition($sesUser["idPosition_fk"]);
$huit = 60 * 60 * 24 * 8; //time_0
$quinze = 60 * 60 * 24 * 15; //time_1
$trente = 60 * 60 * 24 * 30; //time_2
$twomonths = 60 * 60 * 24 * 60; //time_3
$fourmonths = 60 * 60 * 24 * 120; //time_4

// Date du jour
$mkday = mktime(0,0,0,date('m'), date('d'),date('Y'));
  
switch ($sesUser["idPosition_fk"]) {

	case "1" :
		//gK
		$k = 1;
		$keeperColor = "#9999CC";
		break;

	case "2" :
		// cD
		$d = 1;
		$defense = 1;
		$defenseColor = "#9999CC";
		break;

	case "3" :
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
	case "4" :
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

	case "5" :
		// Fw

		$att = 1;
		$passe = 1;
		$passeColor = "#9999CC";
		$buteur = 1;
		$buteurColor = "#9999CC";
		break;

	default :
		$font = "<font color = black>";
		$$font = "</font>";
		break;

}
switch ($sesUser["idNiveauAcces"]) {
	case "1" :
		require ("../menu/menuAdmin.php");
		break;

	case "2" :
		require ("../menu/menuSuperviseur.php");
		break;

	case "3" :
		require ("../menu/menuDTN.php");
		break;

	case "4" :
		require ("../menu/menuCoach.php");
		break;

	default;
		break;
}
require ("../menu/menuMaListe.php");


switch ($sens) {

	case "ASC" :
		$tri = "Tri croissant";
		break;

	case "DESC" :
		$tri = "Tri decroissant";
		break;
}

global $selection;
?>
<script language="JavaScript" type="text/JavaScript">
<!--
function ficheDTN(id,url){
document.location='<?=$url?>/joueurs/ficheDTN.php?url='+url+'&id='+id
}

function init(){
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
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<form name="form1" method="post" action="../form.php">
<input type=hidden name=selection value=<?=$selection?>>
<br>

<table border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td valign="middle">Export Excel :&nbsp;</td>
      <td valign="middle"><a href="../outils/ExportCsv.php?ordre=<?=$ordre?>&sens=<?=$sens?>&lang=<?=$lang?>&masque=<?=$masque?>&affPosition=<?=$affPosition?>&typeExport=<?=$typeExport?>"><img border=1 src="../images/icone-excel.jpg" title="Exporter Ma Liste sur Excel"></a></td>
    
      <!-- Rajout export vers fiches résumé -->
      <!-- Fireproofed le 05/11/2010 -->
      
      <td valign="middle">
             &nbsp &nbsp &nbsp Export Fiches R&eacute;sum&eacute; | Fiches Slack | Fiches Hattrick :&nbsp;</td>              
      <td valign="middle">
        <a href="../joueurs/ficherecupchoix.php?origine=<?php echo "maliste"?>">
          <img border=1 src="../images/jst.bmp" title="Exporter la liste des joueurs sous forme d'une fiche r&eacute;sum&eacute; globale"></a></td>
                 
    </tr>
    <h3 align="center">ATTENTION : La mise à jour des semaines d'entrainement devient AUTOMATIQUE &agrave; compter du 02/08/19. <br>A partir de cette date, merci de faire attention aux doublons ! (contactez votre DTN+ pour plus d'informations)</h3>
    
</table> 
<center>
<h3><?=$tri?> par [ <?=$ordre?> ]</h3>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#000000">
    <tr> 
      <td> 
          <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#EFEFEF">
            <tr > 
              <td width="28%" height="21"> <div align="center">Poste : <?php echo (isset($infPos["intitulePosition"])?$infPos["intitulePosition"]:""); ?></div></td>
              <td> <div align="center"><font color="#000000">Liste des joueurs suivis par <?=$sesUser["loginAdmin"]?></font></div></td>
            </tr>
            <tr> 
              <td colspan="2">
              <table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#000000">
                  <tr bgcolor="#000000" > 
                    <td style="cursor:default;" onClick="chgTri('nomJoueur','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"><font color="#FFFFFF">&nbsp;Identit&eacute; <i class="fa fa-sort-amount-asc"></i></font></td>
                    <td width="150" style="cursor:default;" onClick="chgTri('teamid','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')" ><font color="#FFFFFF">&nbsp;Club <i class="fa fa-sort-amount-asc"></i></font></td>
                    <td width="80" ><font color="#FFFFFF">&nbsp;Maj DTN</font></td>
                    <td width="70" ><font color="#FFFFFF">&nbsp;Valeur HTMS</font></td>
                    <td width="70" ><font color="#FFFFFF">&nbsp;Potentiel HTMS</font></td>
<!--                <td width="70" style="cursor:default;" onClick="chgTri('TODO (valeur htms)','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"><font color="#FFFFFF">&nbsp;Valeur HTMS <i class="fa fa-sort-amount-asc"></i></font></td>
                    <td width="70" style="cursor:default;" onClick="chgTri('TODO (potentiel htms)','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"><font color="#FFFFFF">&nbsp;Potentiel HTMS <i class="fa fa-sort-amount-asc"></i></font></td> -->
                    <td width="60" ><div align="center"><font color="#FFFFFF">&nbsp;TSI</font></div></td>
<!--                <td width="60" style="cursor:default;" onClick="chgTri('TODO (TSI)','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"><font color="#FFFFFF">&nbsp;Potentiel HTMS <i class="fa fa-sort-amount-asc"></i></font></td> -->
					<td width="60" style="cursor:default;" onClick="chgTri('datenaiss','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">Age<i class="fa fa-sort-amount-asc"></i></font></div></td>
                    
                    <td width="100"> 
                      <div align="center"><font color="#FFFFFF">Entrainement</font></div></td>
                    
                    <td width="30"> 
                      <div align="center"><font color="#FFFFFF">Int.</font></div></td>
                    
                    <td width="26"> 
                      <div align="center"><font color="#FFFFFF">%En.</font></div></td>
                    
                    <td width="26"> 
                      <div align="center"><font color="#FFFFFF">Entr.</font></div></td>
                    
                    <td width="26"> 
                      <div align="center"><font color="#FFFFFF">Adj</font></div></td>
                    
                    <td width="26"> 
                      <div align="center"><font color="#FFFFFF">Doc</font></div></td>
                    
                    <td width="26"> 
                      <div align="center"><font color="#FFFFFF">PP</font></div></td>
                    
                    <td width="30" style="cursor:default;" onClick="chgTri('idExperience_fk','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">Xp<i class="fa fa-sort-amount-asc"></i></font></div></td>
                    
                    
                    <td width="26" style="cursor:default;" onClick="chgTri('idLeader_fk','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">TDC<i class="fa fa-sort-amount-asc"></i></font></div></td>
                    
                    <td width="30" style="cursor:default;" onClick="chgTri('optionJoueur','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">Sp&eacute;<i class="fa fa-sort-amount-asc"></i></font></div></td>
                    
                    <td width="30" style="cursor:default;" onClick="chgTri('idEndurance','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">E<i class="fa fa-sort-amount-asc"></i></font></div></td>
                    
                    <td width="60" style="cursor:default;" onClick="chgTri('idGardien','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">G<i class="fa fa-sort-amount-asc"></i></font></div></td>

                    <td width="60" style="cursor:default;" onClick="chgTri('idDefense','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">D<i class="fa fa-sort-amount-asc"></i></font></div></td>

                    <td width="60" style="cursor:default;" onClick="chgTri('idConstruction','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">C<i class="fa fa-sort-amount-asc"></i></font></div></td>
                    
                    <td width="60" style="cursor:default;" onClick="chgTri('idAilier','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">A<i class="fa fa-sort-amount-asc"></i></font></div></td>
                    
                    <td width="60" style="cursor:default;" onClick="chgTri('idPasse','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">P<i class="fa fa-sort-amount-asc"></i></font></div></td>

                    <td width="60" style="cursor:default;" onClick="chgTri('idButeur','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">B<i class="fa fa-sort-amount-asc"></i></font></div></td>
                    
                    <td width="40" style="cursor:default;" onClick="chgTri('idPA','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">CF<i class="fa fa-sort-amount-asc"></i></font></div></td>
					<td width="3" bgcolor="#FFFFDD"> 
                      &nbsp;</td>

<?php

switch ($sesUser["idPosition_fk"]) {
	case "1" : //gK
?>
					<td width="50" style="cursor:default;" onClick="chgTri('scoreGardien','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">gK<i class="fa fa-sort-amount-asc"></i></font></font></div></td>
<?php
		break;
	case "2" : // cD
?>
		            <td width="50" style="cursor:default;" onClick="chgTri('scoreDefense','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">cD<i class="fa fa-sort-amount-asc"></i></font></font></div></td>
                    <td width="50" style="cursor:default;" onClick="chgTri('scoreDefCentralOff','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">cD off<i class="fa fa-sort-amount-asc"></i></font></font></div></td>
                    <td width="50" style="cursor:default;" onClick="chgTri('scoreDefLat','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">wB<i class="fa fa-sort-amount-asc"></i></font></font></div></td>
                    <td width="50" style="cursor:default;" onClick="chgTri('scoreDefLatOff','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">wB off<i class="fa fa-sort-amount-asc"></i></font></font></div></td>
<?php
		break;
	case "3" : // Wg
?>
		            <td width="50" style="cursor:default;" onClick="chgTri('scoreAilier','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">Wg<i class="fa fa-sort-amount-asc"></i></font></font></div></td>
                    <td width="50" style="cursor:default;" nowrap onClick="chgTri('scoreAilierVersMilieu','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">Wg towards<i class="fa fa-sort-amount-asc"></i></font></font></div></td>
                    <td width="50" style="cursor:default;" onClick="chgTri('scoreAilierOff','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">Wg off<i class="fa fa-sort-amount-asc"></i></font></font></div></td>
<?php
		break;
	case "4" : //IM 
?>
                    <td width="50" style="cursor:default;" onClick="chgTri('scoreMilieuDef','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">iM def<i class="fa fa-sort-amount-asc"></i></font></font></div></td>
                    <td width="50" style="cursor:default;" onClick="chgTri('scoreMilieu','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">iM<i class="fa fa-sort-amount-asc"></i></font></font></div></td>
                    <td width="50" style="cursor:default;" onClick="chgTri('scoreMilieuOff','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">iM off<i class="fa fa-sort-amount-asc"></i></font></font></div></td>
<?php
		break;
	case "5" : // Fw
?>
                    <td width="50" style="cursor:default;" onClick="chgTri('scoreAttaquantDef','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">Fw def<i class="fa fa-sort-amount-asc"></i></font></font></div></td>
                    <td width="50" style="cursor:default;" onClick="chgTri('scoreAttaquant','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">Fw<i class="fa fa-sort-amount-asc"></i></font></font></div></td>
<?php
		break;
	default :
?>
					<td width="50" style="cursor:default;" onClick="chgTri('scoreGardien','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">gK<i class="fa fa-sort-amount-asc"></i></font></font></div></td>
                    <td width="50" style="cursor:default;" onClick="chgTri('scoreDefense','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">cD<i class="fa fa-sort-amount-asc"></i></font></font></div></td>
                    <td width="50" style="cursor:default;" onClick="chgTri('scoreMilieu','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">iM<i class="fa fa-sort-amount-asc"></i></font></font></div></td>
            		<td width="50" style="cursor:default;" onClick="chgTri('scoreAilierOff','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">Wg off<i class="fa fa-sort-amount-asc"></i></font></font></div></td>                      
                    <td width="50" style="cursor:default;" onClick="chgTri('scoreAttaquant','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"> 
                      <div align="center"><font color="#FFFFFF">Fw<i class="fa fa-sort-amount-asc"></i></font></font></div></td>
<?php
		break;
}
?>
                </tr>
<?php

$AgeAnneeSQL=getCalculAgeAnneeSQL();
$AgeJourSQL=getCalculAgeJourSQL();

$sql = "select $tbl_joueurs.*,".$AgeAnneeSQL." as AgeAn,".$AgeJourSQL." as AgeJour 
        from $tbl_joueurs 
        where dtnSuiviJoueur_fk  = ".$sesUser["idAdmin"];
      
                   
$sql .= " and affJoueur = 1  order by $ordre $sens";
$listID="";

foreach ($conn->query($sql) as $l) {

    $intensite="-";
    $endurance="-";
    $adjoints="-";
    $medecin="-";
    $physio="-";
    $libelle_type_entrainement="-";
  
	$sql2 = "select * from $tbl_clubs_histo A left join $tbl_type_entrainement2 on idEntrainement = id_type_entrainement where idClubHT = ".$l["teamid"]." order by date_histo desc";
	//error_log($sql2);
	$req2 = $conn->query($sql2);
	$ligne = $req2->fetch(PDO::FETCH_ASSOC);
	if (is_array($ligne))
		extract($ligne);

	$sql3 = "select * from $tbl_clubs where idClubHT = ".$l["teamid"];
	$req3 = $conn->query($sql3);
	$ligne3 = $req3->fetch(PDO::FETCH_ASSOC);
	extract($ligne3);

// Extraction statut du joueur à la dernière MàJ (en vente ou non)
            $sql4= "SELECT transferListed, tsi FROM $tbl_joueurs_histo
                   WHERE id_joueur_fk=".$l["idHattrickJoueur"]." 
                   ORDER BY date_histo DESC LIMIT 1";
            $req4 = $conn->query($sql4);
            $ligne4 = $req4->fetch(PDO::FETCH_ASSOC);
            if (is_array($ligne4))
            extract($ligne4);

	$infJ = getJoueur($l["idJoueur"]);
	
	$listID.=$infJ["idHattrickJoueur"].";";

	$date = explode("-",$infJ["dateDerniereModifJoueur"]);
//echo($l["nomJoueur"]);print_r($date);echo('<br>');
	$mkJoueur =  mktime(0,0,0,$date[1],$date[2],$date[0]); 
	$datesaisie = explode("-",$infJ["dateSaisieJoueur"]);
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
	$zealt=" Date dtn : ".$infJ["dateDerniereModifJoueur"].
					"<br> Date proprio : ".$infJ["dateSaisieJoueur"].
					"<br> [ Mis &agrave; jour il y a  ".round(($mkday - $datemaj)/(60*60*24) )." jours ]";
    
    // HTMS du joueur    
    $ageetjours = ageetjour($infJ["datenaiss"]);
	$tabage = explode(" - ",$ageetjours);
	$htms = htmspoint($tabage[0], $tabage[1], $infJ["idGardien"], $infJ["idDefense"], $infJ["idConstruction"], $infJ["idAilier"], $infJ["idPasse"], $infJ["idButeur"], $infJ["idPA"]);
	
	global $class;
?>
                  <tr bgcolor="#FFFFFF" align="right"> 
                    <td align="left" nowrap>&nbsp;<img src="../images/time_<?=$img_nb?>.gif" onmouseover="return escape('<?=$zealt?>')" >&nbsp;
                    <?php if (existAutorisationClub($idClubHT,null)==false) {?>
                      <img height="12" src="../images/non_autorise.JPG" title="Ce club n'a pas autoris&eacute; la DTN &agrave; acc&eacute;der &agrave; ses donn&eacute;es">
                    <?php } else {?>
                      <img height="12" src="../images/Autorise.PNG" title="Ce club a autoris&eacute; la DTN &agrave; acc&eacute;der &agrave; ses donn&eacute;es">
                    <?php }?>
                    <?php if ($transferListed==1) {?><img height="12" src="../images/enVente.JPG" title="Plac&eacute; sur la liste des transferts"><?php }?>
                    <a href ="<?=$url?>/joueurs/ficheDTN.php?id=<?=$l["idJoueur"]?>" class=<?=$class?>> 
                      <b> 
                      <?=$l["prenomJoueur"]?> <?php if (isset($l["surnomJoueur"])) echo '"'.$l["surnomJoueur"].'" '; ?><?=$l["nomJoueur"]?>
                      </b> 
                      </a> 
                      
                    </td>
                    
                    <td width="120" nowrap align="center"><?=$ligne3['nomClub']?></td>
                    <td width="55" nowrap align="center"><?=$date[2]?>/<?=$date[1]?>/<?=$date[0]?></td>
                    <td width="55" nowrap align="center"><?=$htms["value"]?></td>
                    <td width="55" nowrap align="center"><?=$htms["potential"]?></td>
					<td width="55" nowrap align="center"><?=number_format($tsi, "0"," ", " ")?></td>
                    <td nowrap><div align="center"><?=$l["AgeAn"]."-".$l["AgeJour"]?></div></td>
                    <td width="20"> <div align="center"><?=$libelle_type_entrainement?></div></td>
                    <td width="20"> <div align="center"><?=$intensite?></div></td>
                    <td width="20"> <div align="center"><?=$endurance?></div></td>
                    <td width="20"> <div align="center"><?=$niv_Entraineur?></div></td>
                    <td width="20"> <div align="center"><?=$adjoints?></div></td>
                    <td width="20"> <div align="center"><?=$medecin?></div></td>
                    <td width="20"> <div align="center"><?=$physio?></div></td>
                    <td width="30"> <div align="center"><?=$l["idExperience_fk"]?></div></td>
                    <td width="26"> <div align="center"><?=$l["idLeader_fk"]?></div></td>
                    <td width="30"> <div align="center"><?=$specabbrevs[$l["optionJoueur"]]?></div></td>
                    <td width="30" bgcolor="#CCCCCC" witdth = "20"> <div align="center"><?=$l["idEndurance"]?></div></td>
                    <td width="30" <?php if ($k==1) echo "bgcolor = $keeperColor";?>><div align="center"><?=$l["idGardien"]?> <?php afficheLesPlus($infJ,"nbSemaineGardien"); ?></div></td>
                    <td width="30" <?php if ($defense==1) echo "bgcolor = $defenseColor";?>><div align="center"><?=$l["idDefense"]?> <?php afficheLesPlus($infJ,"nbSemaineDefense"); ?></div></td>
                    <td width="30" <?php if ($construction==1) echo "bgcolor = $constructionColor";?>><div align="center"><?=$l["idConstruction"]?> <?php afficheLesPlus($infJ,"nbSemaineConstruction"); ?></div></td>
                    <td width="30" <?php if ($ailier==1) echo "bgcolor = $ailierColor";?>><div align="center"><?=$l["idAilier"]?> <?php afficheLesPlus($infJ,"nbSemaineAilier"); ?></div></td>
                    <td width="30" <?php if ($passe==1) echo "bgcolor = $passeColor";?>><div align="center"><?=$l["idPasse"]?> <?php afficheLesPlus($infJ,"nbSemainePasses"); ?></div></td>
                    <td width="30" <?php if ($buteur==1) echo "bgcolor = $buteurColor";?>><div align="center"><?=$l["idButeur"]?> <?php afficheLesPlus($infJ,"nbSemaineButeur"); ?></div></td>
                    <td width="30" > <div align="center"><?=$l["idPA"]?><?php afficheLesPlus($infJ,"nbSemaineCoupFranc"); ?></div></td>
                  <td width="3" bgcolor="#FFFFDD"> 
                      &nbsp;</td>

<?php

	switch ($sesUser["idPosition_fk"]) {
		case "1" : //gK
?>
               <td >
                <?=$l["scoreGardien"];?>
               </td>
           <?php

			break;
		case "2" : // cD
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
		case "3" : // Wg
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
		case "4" : //IM 
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
		case "5" : // Fw
?>
               <td >
                <?=$l["scoreAttaquantDef"];?>
               </td>
               <td >
                <?=$l["scoreAttaquant"];?>
               </td>
		<?php

			break;
		default :
?>
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



                  </tr>
                <?php

}

$listID=substr($listID,0,-1); // enlève le dernier ;
$_SESSION['ListeFicheResume']=$listID;

?>
              </td>
            </tr>
          </table>
        </div></td>
    </tr>
  </table></td>
    </tr>
  </table>
  <?php

deconnect();
?>
</form>
</center>


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
    <br>

</center>

<script language="JavaScript" type="text/javascript" src="../includes/javascript/tooltips.js"></script>
</body>
</html>
