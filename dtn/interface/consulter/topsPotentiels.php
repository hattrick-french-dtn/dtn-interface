<?php
require_once("../includes/head.inc.php");
require("../includes/serviceListesDiverses.php");
require("../includes/serviceJoueur.php");
require("../includes/serviceDTN.php");
require("../includes/langue.inc.php");
require("../includes/serviceEntrainement.php");


if(!isset($_SESSION['sesUser']["idAdmin"]))
{
	header("location: http://".$_SERVER['SERVER_NAME']."/dtn/interface/index.php?ErrorMsg=Session Expire");
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

if(!isset($ordre)) $ordre = "nomJoueur";
if(!isset($sens)) $sens = "ASC";
$lang = "FR";
if(!isset($masque)) $masque = 0;
if(!isset($secteur)) $secteur = 0;
if(!isset($affPosition)) $affPosition = 0;


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
	$keeperColor = "#BBEEBB";
	$defenseColor = "#DDEEDD";
	$param1="1";
	$param3="noteGardien";
	$secteurLabel="gK";
	break;
case 1:
	$defenseColor = "#BBEEBB";
	$passeColor = "#DDEEDD";
	$constructionColor = "#DDEEDD";
	$param1="DEF";
	$param3="noteDefense";
	$secteurLabel="cD `normal";
	break;
case 2:
	$defenseColor = "#BBEEBB";
	$ailierColor = "#DDEEDD";
	$param1="DEF";
	$param3="noteLateral";
	$secteurLabel="wB";
	break;
case 3:
	$param1="3";
	$param3="noteMilieu";
	$constructionColor = "#BBEEBB";
	$defenseColor = "#DDEEDD";
	$passeColor = "#DDEEDD";
	$secteurLabel="iM";
	break;
case 4:
	$constructionColor = "#DDEEDD";
	$ailierColor = "#BBEEBB";
	$passeColor = "#DDEEDD";
	$param1="4";
	$param3="noteAilier";
	$secteurLabel="Wg";
	break;
case 5:
	$passeColor = "#DDEEDD";
	$buteurColor = "#BBEEBB";
	$ailierColor = "#DDEEDD";
	$param1="5";
	$param3="noteAttaquant";
	$secteurLabel="Fw";
	break;
}
?>


<br>
<form name="form_htwww" method="post" action="<?=$_SERVER['PHP_SELF']?>?action=setnbplayers&secteur=<?=$secteur?>" >
  <b><font color=red>[<a href="formulesPotentiels.php">Formules!</a>]</font></b><?php


if (isset($nb_players)){
		$_SESSION["sess_nb_players"]=$nb_players;
}else{
		if (isset($_SESSION["sess_nb_players"])){
			$nb_players=$_SESSION["sess_nb_players"];
		}else{
				$nb_players=7;
		}
	
}
?>&nbsp;&nbsp;&nbsp;&nbsp;Nb players :<select name='nb_players' onchange='this.form.submit();'><?php
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
    <b><span class="breadvar">Liste des Tops Potentiels en : <?=$secteurLabel?></span></b>
    <br>[<b>&nbsp;
    <a class="btn" href="topsPotentiels.php?secteur=0">gK</a>&nbsp;|&nbsp;
    <a class="btn" href="topsPotentiels.php?secteur=1">cD</a>&nbsp;|&nbsp;
    <a class="btn" href="topsPotentiels.php?secteur=2">wB</a>&nbsp;|&nbsp;
    <a class="btn" href="topsPotentiels.php?secteur=3">iM</a>&nbsp;|&nbsp;
    <a class="btn" href="topsPotentiels.php?secteur=4">Wg</a>&nbsp;|&nbsp;
    <a class="btn" href="topsPotentiels.php?secteur=5">Fw</a>&nbsp;</b>]

  </center>


<p>


<!-- debut Table Top -->  
  <table width="100%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr> 
      <td><div align="center"> 
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
       

            <tr bgcolor="#000000"> 
                  <td width="127"><font color="#FFFFFF">&nbsp;Identit&eacute;</font></td>
                  <td width="30"  align="center"><font color="#FFFFFF" >Training</font></td>
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
		for ($lstnb=17;$lstnb<26;$lstnb++ ){
?>
<tr bgcolor="#000000"> 
                  <td colspan="16" align="center" bgcolor="#00CC00"><font color="#FFFFFF">
				  <?php
				  		$lstnb2=$lstnb+1;
						echo "Joueurs de ".$lstnb." ans et x jours -> ".$lstnb2." ans";
				   ?></font></td>
              </tr><?php
			
			$lstPlayer = toplist_potentiels($param1, $param2, $param3, $lstnb);

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
			
			$noteMax=0;

			foreach($lstPlayer as $l){

        $ageJourJoueur=ageetjour($l["datenaiss"]);			
				$note=0;
				switch($secteur){
					case 0:
					$note=$l["noteGardien"];
					break;
					case 1:
					$note=$l["noteDefense"];
					break;				
					case 2:
					$note=$l["noteLateral"];
					break;
					case 3:
					$note=$l["noteMilieu"];
					break;
					case 4:
					$note=$l["noteAilier"];
					break;
					case 5:
					$note=$l["noteAttaquant"];
					break;
				}
				
				if ($note <= 0) {
					break;
				}
				
				if ($noteMax == 0) {
					$noteMax = $note;
				} else {
					if ($note < ($noteMax*7/10)) {
						break;
					}
				}
			
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
?>
				  
                 <tr bgcolor = "<?=$bgcolor?>">  
                    <td class="bred1" width="127" nowrap>&nbsp;<img src="../images/time_<?=$img_nb?>.gif" onmouseover="return escape('<?=$zealt?>')" >&nbsp;

<?php 
         		if 	( ($sesUser["idNiveauAcces"]==1 || $sesUser["idNiveauAcces"]==4) 
					|| (($sesUser["idNiveauAcces"]==3 || $sesUser["idNiveauAcces"]==2)
					&& ($l["ht_posteAssigne"]==$sesUser["idPosition_fk"]))){
?>
				<a href ="<?=$url?>/joueurs/fiche.php?id=<?=$l["idJoueur"]?>" class="bred1">
<?php	
				}
				else if ($sesUser["idNiveauAcces"]==2) {
?>
				<a href ="<?=$url?>/joueurs/fiche_visu.php?id=<?=$l["idJoueur"]?>" class="bred1">
<?php	
				}
?>
				<?=strtolower($l["prenomJoueur"])?>&nbsp;<?=strtolower($l["nomJoueur"])?>
				<?php if (isset($l["surnomJoueur"])) echo " (".$l["surnomJoueur"].")"; ?>
<?php
				if 	( ($sesUser["idNiveauAcces"]==1 || $sesUser["idNiveauAcces"]==2 || $sesUser["idNiveauAcces"]==4) 
					|| (($sesUser["idNiveauAcces"]==3)
					&& ($l["ht_posteAssigne"]==$sesUser["idPosition_fk"]))){
?>
				</a>
<?php
				} 
				if($verifInternational != ""){?>&nbsp;<img src="../images/fr.gif" alt="<?=$verifInternational?>"><?php
				} 
?>
				</td>
                <td width="30"><div align="center"><font size="-2"><?=getEntrainementName($l["entrainement_id"],$lstTrain)?></font></div></td>
                <td width="30"><div align="center"><font size="-2"><?=$scout["loginAdmin"]?></font></div></td>
                <td width="40"><div align="right"><?=$l["valeurEnCours"]?><img src="../images/spacer.gif" width="10" height="1"></div></td>
                <td width="50" nowrap="nowrap"><div align="left"><?=$ageJourJoueur?></div></td>
                <td width="20"> <div align="center"><?=$l["idExperience_fk"]?></div></td>
                <td width="20"><div align="center"><?=$specabbrevs[$l["optionJoueur"]]?></div></td>

<?php 

				if 	( ($sesUser["idNiveauAcces"]==1 || $sesUser["idNiveauAcces"]==4 || $sesUser["idNiveauAcces"]==2) 
					|| (($sesUser["idNiveauAcces"]==3)
					&& ($l["ht_posteAssigne"]==$sesUser["idPosition_fk"]))){
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
                    <td > <div align="center"> 
                    <?=$note?> 
                      </div></td>


<?php }else{
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
<?php  deconnect(); ?>
