<?php
require_once("../includes/head.inc.php");
require("../includes/serviceListesDiverses.php");
require("../includes/serviceJoueur.php");
require("../includes/serviceDTN.php");
require("../includes/langue.inc.php");
require("../includes/serviceEntrainement.php");


if(!isset($_SESSION['sesUser']["idAdmin"]))
{
	header("location: https://".$_SERVER['SERVER_NAME']."/dtn/interface/index.php?ErrorMsg=Session Expire");
}
	

switch($_SESSION['sesUser']["idNiveauAcces"]){
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

	
if(!isset($ordre)) $ordre = "nomJoueur";
if(!isset($sens)) $sens = "ASC";
$lang = "FR";
if(!isset($masque)) $masque = 0;
if(!isset($secteur)) $secteur = 0;
if(!isset($affPosition)) $affPosition = 0;


//Liste des types d'entrainement
$lstTrain=listEntrainement();


?><title>[ht-fff]TopList DTN</title>
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<script language="JavaScript" type="text/JavaScript">
<!--


function fiche(id,url){
	
document.location='<?=$url?>/joueurs/fiche.php?url='+url+'&id='+id
}


//-->
</script>
<style type="text/css">
<!--
.Style1 {color: #FFFFFF}
.Style3 {color: #FF9933}
.Style4 {color: #FF0000}
-->
</style>
<?php
$huit = 60 * 60 * 24 * 8; //time_0
$quinze = 60 * 60 * 24 * 15; //time_1
$trente = 60 * 60 * 24 * 30; //time_2
$twomonths = 60 * 60 * 24 * 60; //time_3
$fourmonths = 60 * 60 * 24 * 120; //time_4

// Date du jour
$mkday = mktime(0,0,0,date('m'), date('d'),date('Y'));
  

$keeperColor = "";
$defenseColor = "";
$constructionColor = "";
$ailierColor = "";
$passeColor = "";
$buteurColor = "";  

switch($secteur){
case 0:
	$keeperColor = "#BBBBEE";
	$defenseColor = "#DDDDEE";
	$param1="1";
	$param3="scoreGardien";
	$secteurLabel="gK";
	break;
case 1:
	$defenseColor = "#BBBBEE";
	$constructionColor = "#DDDDEE";
	$param1="DEF";
	$param3="scoreDefense";
	$secteurLabel="cD `normal";
	break;
case 5:
	$defenseColor = "#BBBBEE";
	$constructionColor = "#DDDDEE";
	$param1="DEF";
	$param3="scoreDefCentralOff";
	$secteurLabel="cd `off";
	break;
case 11:
	$defenseColor = "#BBBBEE";
	$ailierColor = "#BBBBEE";
	$param1="DEF";
	$param3="scoreDefLatOff";
	$secteurLabel="wB `off";
	break;
case 12:
	$defenseColor = "#BBBBEE";
	$ailierColor = "#DDDDEE";
	$param1="DEF";
	$param3="scoreDefLat";
	$secteurLabel="wB `normal ";
	break;

case 2:
	$param1="4";
	$param3="scoreMilieu";
	$constructionColor = "#BBBBEE";
	$defenseColor = "#DDDDEE";
	$passeColor = "#DDDDEE";
	$secteurLabel="iM `normal";
	break;

case 6:
	$param1="4";
	$param3="scoreMilieuOff";
	$constructionColor = "#BBBBEE";
	$passeColor = "#DDDDEE";
	$secteurLabel="iM `off";
				
	break;

case 7:
	$param1="4";
	$param3="scoreMilieuDef";
	$constructionColor = "#BBBBEE";
	$defenseColor = "#DDDDEE";
	$secteurLabel="iM `def";
	break;

case 3:
	$constructionColor = "#DDDDEE";
	$ailierColor = "#BBBBEE";
	$passeColor = "#DDDDEE";
	$param1="3";
	$param3="scoreAilierOff";
	$secteurLabel="Wg `off";
	break;

case 8:
	$constructionColor = "#BBBBEE";
	$ailierColor = "#DDDDEE";
	$passeColor = "#DDDDEE";
	$param1="3";
	$param3="scoreAilierVersMilieu";
	$secteurLabel="Wg `towards middle";
	break;

case 9:
	$constructionColor = "#DDDDEE";
	$ailierColor = "#BBBBEE";
	$passeColor = "#DDDDEE";
	$param1="3";
	$param3="scoreAilier";
	$secteurLabel="Wg `normal";
	break;

case 4:
	$passeColor = "#DDDDEE";
	$buteurColor = "#BBBBEE";
	$param1="5";
	$param3="scoreAttaquant";
	$secteurLabel="Fw `normal";
	break;

case 10:
	$passeColor = "#DDDDEE";
	$buteurColor = "#BBBBEE";
	$constructionColor = "#BBBBEE";
	$param1="5";
	$param3="scoreAttaquantDef";
	$secteurLabel="Fw `def";
	break;

case 13:
	$passeColor = "#DDDDEE";
	$buteurColor = "#BBBBEE";
	$ailierColor = "#BBBBEE";
	$param1="5";
	$param2="10";
	$param3="scoreAttaquantVersAile";
	$secteurLabel="Fw `towards";
	break;

}
?>


<br>
<form name="form_htwww" method="post" action="<?=$_SERVER['PHP_SELF']?>?action=setnbplayers&secteur=<?=$secteur?>" >
  <b><font color=red>[<a href="formules.php">Formules!</a>]</font></b><?php


if (isset($nb_players)){
		$_SESSION["sess_nb_players"]=$nb_players;
} else {
	if (isset($_SESSION["sess_nb_players"])){
		$nb_players=$_SESSION["sess_nb_players"];
	}else{
			$nb_players=7;
	}
}
?>&nbsp;&nbsp;&nbsp;&nbsp;Nb joueurs :<select name='nb_players' onchange='this.form.submit();'><?php
for ($i=5;$i<30;$i++){
	$etat="";
	if ($nb_players==$i){
		$etat=" SELECTED";
	}
?><option value='<?=$i?>'<?=$etat?>><?=$i?></option><?php 
}

$param2="$nb_players";

 ?> </select>
</form>
  
  
<form name="form1" method="post" action="../form.php">
  <center>
    <b><span class="breadvar">Liste des Tops en : <?=$secteurLabel?></span></b>
    <br>[<b>&nbsp;
    <a class="btn" href="topsPublics.php?secteur=0">gK</a>&nbsp;|&nbsp;
    <a class="btn" href="topsPublics.php?secteur=1">cD `normal</a>&nbsp;|&nbsp;
    <a class="btn" href="topsPublics.php?secteur=5">cD `off</a>&nbsp;|&nbsp;
    <a class="btn" href="topsPublics.php?secteur=12">wB `normal</a>&nbsp;|&nbsp;
    <a class="btn" href="topsPublics.php?secteur=11">wB `off</a>&nbsp;|&nbsp;
    <a class="btn" href="topsPublics.php?secteur=7">iM `def</a>&nbsp;|&nbsp;
    <a class="btn" href="topsPublics.php?secteur=2">iM `normal</a>&nbsp;|&nbsp;
    <a class="btn" href="topsPublics.php?secteur=6">iM `off</a>&nbsp;|&nbsp;
    <a class="btn" href="topsPublics.php?secteur=8">Wg `towards</a>&nbsp;|&nbsp;
    <a class="btn" href="topsPublics.php?secteur=9">Wg `normal</a>&nbsp;|&nbsp;
    <a class="btn" href="topsPublics.php?secteur=3">Wg `off</a>&nbsp;|&nbsp;
    <a class="btn" href="topsPublics.php?secteur=10">Fw `def</a>&nbsp;|&nbsp;
    <a class="btn" href="topsPublics.php?secteur=13">Fw `towards</a>&nbsp;|&nbsp;
    <a class="btn" href="topsPublics.php?secteur=4">Fw `normal</a>&nbsp;</b>]
  </center>

<p>

<!-- debut Table Top -->  
  <table width="100%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr> 
      <td><div align="center"> 
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr bgcolor="#000000"> 
                  <td width="127"><font color="#FFFFFF">&nbsp;Identit&eacute;</font></td>
                  <td width="30"  align="center"><font color="#FFFFFF" >Entrainement</font></td>
                  <td width="30"><div align="center"><font color="#FFFFFF">DTN</font></div></td>
                  <td width="40"><div align="center"><font color="#FFFFFF">TSI</font></div></td>
                  <td width="50"><div align="center"><font color="#FFFFFF">Age</font></div></td>
                  <td width="20"><div align="center"><font color="#FFFFFF">Xp</font></div></td>
                  <td width="20"><div align="center"><font color="#FFFFFF">Sp&eacute;</font></div></td>


				  <td width="4%" >
                    <div align="center"><font color="#FFFFFF">Sta</font></div></td>
				  <td width="4%"  >
                    <div align="center"><font color="#FFFFFF">Pla</font></div></td>
                  <td width="4%"  >
                    <div align="center"><font color="#FFFFFF">Wn</font></div></td>
                  <td width="4%" >
                    <div align="center"><font color="#FFFFFF">Sco</font></div></td>
                  <td width="4%" >
                    <div align="center"><font color="#FFFFFF">Kee</font></div></td>
                  <td width="4%" >
                    <div align="center"><font color="#FFFFFF">Pas</font></div></td>
                  <td width="4%" >
                    <div align="center"><font color="#FFFFFF">Def</font></div></td>
                  <td width="4%" >
                    <div align="center"><font color="#FFFFFF">Set</font></div></td>
		         <td width="4%" >
                    <div align="center"><font color="#FFFFFF">Note</font></div></td>


              </tr>
              <tr> 
                    <td colspan="16" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
              </tr>

<?php
$coloridx = 1;
for ($lstnb=16;$lstnb<27;$lstnb++ ){
	if ($lstnb<21){
		$param4 = $lstnb - 15;
	}
	else if ($lstnb<26){
		$param4 = $lstnb;
	}else{
		$param4 = "SUP";
	}
?>
	<tr bgcolor="#000000"> 
        <td colspan="16" align="center" bgcolor="#0000CC"><font color="#FFFFFF">
<?php
	if ($param4 == "SUP") {
		echo "Toutes catégories";
	} else if ($param4 == "1") {
		if ($_SESSION['sesUser']["saison"] % 2 == 0)
			echo "Qualification saison ".($_SESSION['sesUser']["saison"]+3);
		else
			echo "CDM saison ".($_SESSION['sesUser']["saison"]+3);
	} else if ($param4 == "2") {
			if ($_SESSION['sesUser']["saison"] % 2 == 1)
				echo "Qualification saison ".($_SESSION['sesUser']["saison"]+2);
			else
				echo "CDM saison ".($_SESSION['sesUser']["saison"]+2);
	} else if ($param4 == "3") {
			if ($_SESSION['sesUser']["saison"] % 2 == 0)
				echo "Qualification saison ".($_SESSION['sesUser']["saison"]+1);
			else
				echo "CDM saison ".($_SESSION['sesUser']["saison"]+1);
	} else if ($param4 == "4") {
			if ($_SESSION['sesUser']["saison"] % 2 == 1)
				echo "Qualification saison ".($_SESSION['sesUser']["saison"]);
			else
				echo "CDM saison ".($_SESSION['sesUser']["saison"]);
	} else if ($param4 == "5") {
		echo "Autres joueurs de 20 ans";
	} else
		echo "Joueurs de ".$param4." ans";
	?>
		</font></td>
	</tr>
<?php
	$lstPlayer = toplist_new($param1, $param2,$param3, $param4, $_SESSION['sesUser']["dateSemaine0"], $_SESSION['sesUser']["saison"]);
			
	switch($coloridx){
	case 1:
		$bgcolor = "#EEEEEE";
		$coloridx = 0;
		break;
	case 0:
		$bgcolor = "white";
		$coloridx = 1;
		break;
	}
	if ($lstnb==26){
		$bgcolor = "lightblue";
	}
	
    $nbplayers=count($lstPlayer);
    if ($nbplayers>0) {
		foreach($lstPlayer as $l){
			$scout=getScout($l["idHattrickJoueur"]);
			$verifInternational = verifSelection($l["idJoueur"]);

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
					
			// calcul age réel !
			
			/* $datenaissjoueur = $l["datenaiss"];
			 $jouractuel = round((mktime(0,0,0,date("m"),date("d"),date("Y"))-574729200)/3600/24,0);
			$nbjourjoueur = $jouractuel-$datenaissjoueur;
			$jourjoueur = $nbjourjoueur % 112;
			$agejoueur = ($nbjourjoueur-$jourjoueur)/112;*/
			
			$ageetjour = ageetjour($l["datenaiss"]);
			 			  	
?>
				  <tr bgcolor = "<?=$bgcolor?>">  
                    <td class="bred1" width="127" nowrap>&nbsp;<img src="../images/time_<?=$img_nb?>.gif" onmouseover="return escape('<?=$zealt?>')" >&nbsp;

<?php 
                    
			if 	( ($_SESSION['sesUser']["idNiveauAcces"]==1 || $_SESSION['sesUser']["idNiveauAcces"]==4) 
				|| (($_SESSION['sesUser']["idNiveauAcces"]==2 || $_SESSION['sesUser']["idNiveauAcces"]==3)
				&& ($l["ht_posteAssigne"]==$_SESSION['sesUser']["idPosition_fk"]))){
    
                ?><a href ="<?=$url?>/joueurs/fiche.php?id=<?=$l["idJoueur"]?>" class="bred1"><?php
			}
			else if ($_SESSION['sesUser']["idNiveauAcces"]==2) {
				?><a href ="<?=$url?>/joueurs/fiche.php?id=<?=$l["idJoueur"]?>" class="bred1"><?php
			}
?>
			<?=strtolower($l["prenomJoueur"])?>&nbsp;<?=strtolower($l["nomJoueur"])?>
			<?php if (isset($l["surnomJoueur"])) echo " (".$l["surnomJoueur"].")"; ?>
<?php
			if 	( ($_SESSION['sesUser']["idNiveauAcces"]==1 || $_SESSION['sesUser']["idNiveauAcces"]==2 || $_SESSION['sesUser']["idNiveauAcces"]==4) 
				|| (($_SESSION['sesUser']["idNiveauAcces"]==3)
				&& ($l["ht_posteAssigne"]==$_SESSION['sesUser']["idPosition_fk"]))){
        
				?></a><?php
			} 
			if($verifInternational != ""){?>&nbsp;<img src="../images/fr.gif" alt="<?=$verifInternational?>"><?php
			} ?></td>
                    <td width="30"><div align="center"><font size="-2"><?=getEntrainementName($l["entrainement_id"],$lstTrain)?></font></div></td>
                    <td width="30"><div align="center"><font size="-2"><?=$scout["loginAdmin"]?></font></div></td>
                    <td width="40"><div align="right"><?=$l["valeurEnCours"]?><img src="../images/spacer.gif" width="10" height="1"></div></td>
                    <td width="50" nowrap="nowrap"><div align="left"><?=$ageetjour?></div></td>
                    <td width="20"> <div align="center"><?=$l["idExperience_fk"]?></div></td>
                    <td width="20"><div align="center"><?=$specabbrevs[$l["optionJoueur"]]?></div></td>


<?php 

			if 	( ($_SESSION['sesUser']["idNiveauAcces"]==1 || $_SESSION['sesUser']["idNiveauAcces"]==4 || $_SESSION['sesUser']["idNiveauAcces"]==2) 
				|| (($_SESSION['sesUser']["idNiveauAcces"]==3)
				&& ($l["ht_posteAssigne"]==$_SESSION['sesUser']["idPosition_fk"]))){
	 ?>
                     
                    <td > <div align="center">
                    <?=$l["idEndurance"]?></div></td>
                    <td bgcolor="<?=$constructionColor?>"> <div align="center"> 
                    <?=$l["idConstruction"]?><?php afficheLesPlus($l,"nbSemaineConstruction"); ?> 
                      </div></td>
                    <td bgcolor="<?=$ailierColor?>"> <div align="center"> 
                    <?=$l["idAilier"]?><?php afficheLesPlus($l,"nbSemaineAilier"); ?> 
                      </div></td>
                    <td bgcolor="<?=$buteurColor?>"> <div align="center"> 
                    <?=$l["idButeur"]?><?php afficheLesPlus($l,"nbSemaineButeur"); ?> 
                      </div></td>
                    <td bgcolor="<?=$keeperColor?>"> <div align="center"> 
                    <?=$l["idGardien"]?><?php afficheLesPlus($l,"nbSemaineGardien"); ?> 
                      </div></td>
					  <!--//correction bug semaines passe : jojoje86 20/07/09 -->
                    <td bgcolor="<?=$passeColor?>"> <div align="center"> 
                    <?=$l["idPasse"]?><?php afficheLesPlus($l,"nbSemainePasses"); ?> 
                      </div></td>
                    <td bgcolor="<?=$defenseColor?>"> <div align="center"> 
                    <?=$l["idDefense"]?><?php afficheLesPlus($l,"nbSemaineDefense"); ?> 
                      </div></td>
                    <td > <div align="center"> 
                    <?=$l["idPA"]?> 
                      </div></td>


<?php
				$note=0;
				switch($secteur){
					case 0:
					$note=$l["scoreGardien"];
					break;
					case 1:
					$note=$l["scoreDefense"];
					break;
					case 5:
					$note=$l["scoreDefCentralOff"];
					break;
					case 11:
					$note=$l["scoreDefLatOff"];
					break;
					case 12:
					$note=$l["scoreDefLat"];
					break;
					case 2:
					$note=$l["scoreMilieu"];
					break;
					case 6:
					$note=$l["scoreMilieuOff"];
					break;
					case 7:
					$note=$l["scoreMilieuDef"];
					break;
					case 3:
					$note=$l["scoreAilierOff"];
					break;
					case 8:
					$note=$l["scoreAilierVersMilieu"];
					break;
					case 9:
					$note=$l["scoreAilier"];
					break;
					case 4:
					$note=$l["scoreAttaquant"];
					break;
					case 10:
					$note=$l["scoreAttaquantDef"];
					break;
					case 13:
					$note=$l["scoreAttaquantVersAile"];
					break;
				}
?>
                    <td > <div align="center"> 
                    <?=$note?> 
                      </div></td>


<?php 			} else {
	?>
	<td > <div align="center">&nbsp;</div></td>
    <td > <div align="center">&nbsp;</div></td>
    <td > <div align="center">&nbsp;</div></td>
    <td > <div align="center">&nbsp;</div></td>
    <td > <div align="center">&nbsp;</div></td>
    <td > <div align="center">&nbsp;</div></td>
    <td > <div align="center">&nbsp;</div></td>
    <td > <div align="center">&nbsp;</div></td>
    <td > <div align="center">&nbsp;</div></td>
    
	
<?php
				}
			}
	}
}
?>
    </tr>           
<!-- fin table top -->  
          </table>




        </div></td>
    </tr>
  </table>

  <br>
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
