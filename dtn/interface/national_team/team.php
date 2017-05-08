<?php 
require("../includes/head.inc.php");


require("../includes/serviceJoueur.php");
require("../includes/serviceDTN.php");
require("../includes/serviceListesDiverses.php");
if(!$sesUser["idAdmin"])
	{
	header("location: index.php?ErrorMsg=Session Expir�");
	}
if(!isset($ordre)) $ordre = "ht_posteAssigne";
if(!isset($sens)) $sens = "ASC";
if(!isset($lang)) $lang = "FR";
if(!isset($masque)) $masque = 0;
if(!isset($affPosition)) $affPosition = 0;


require("../includes/langue.inc.php");






switch($sesUser["idNiveauAcces"]){
		case "1":
		require("../menu/menuAdmin.php");
		require("../menu/menuNational.php");
		break;
		
		case "2":
		require("../menu/menuSuperviseur.php");
		require("../menu/menuNational.php");
		break;




		case "3":
		require("../menu/menuDTN.php");
		require("../menu/menuNational.php");
		break;
		
		case "4":
		require("../menu/menuCoach.php");
		require("../menu/menuNational.php");
		break;
		
		default;
		break;




}








#$selection="A";
#$lstJoueurs = listJoueurSelection($infAdmin["selection"]);
$lstJoueurs = joueursSelection($selection);
$lstPos = listAllPosition();
#$lstPos[0]["intitulePosition"]="non défini";
#$lstPos[0]["descriptifPosition"]="non défini";


?><title>Equipe <?=$selection?></title>
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<script language="JavaScript" type="text/JavaScript">
<!--


//-->


function fiche(id,url){
	
document.location='<?=$url?>/joueurs/fiche.php?url='+url+'&id='+id
}


function init()
{
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
<body onLoad = "init();">
<br>


<form name="form1" method="post" action="../form.php">
<p>
<?php


?>
  <center>
    <b><span class="titre">Liste des joueurs  en &eacute;quipe de France
    <?=$selection?>
    <br></b>
  </center>
    </span></b>    
  <table width="100%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr> 
      <td height="20" ><div align="center"> 
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td  height="21"> <div align="center"></div></td>
              <td colspan="13"><div align="center"><font color="#000000">Liste 
                  des joueurs</font></div></td>
              <td > </td>
            </tr>
            <tr> 
              <td height="1" colspan="15" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
            </tr>
            <tr bgcolor="#000000"> 
                  <td width="10%" onClick="chgTriSelection('nomJoueur','<?=$sens?>','<?=$masque?>','<?=$affPosition?>','<?=$selection?>')"><font color="#FFFFFF">&nbsp;Identit&eacute;</font></td>
                  <td width="10%" ><div align="left"><font color="#FFFFFF">TSI</font></div></td>
                  <td width="5%" ><div align="left"><font color="#FFFFFF">Pos</font></div></td>
                  <td onClick="chgTriSelection('ageJoueur','<?=$sens?>','<?=$masque?>','<?=$affPosition?>','<?=$selection?>')">
                    <div align="center"><font color="#FFFFFF">Age</font></div></td>
                  <td width="6%" onClick="chgTriSelection('idExperience_fk','<?=$sens?>','<?=$masque?>','<?=$affPosition?>','<?=$selection?>')">
                    <div align="center"><font color="#FFFFFF">Xp</font></div></td>
				  <td width="5%" onClick="chgTriSelection('idEndurance','<?=$sens?>','<?=$masque?>','<?=$affPosition?>','<?=$selection?>')" >
                    <div align="center"><font color="#FFFFFF">Sta</font></div></td>
                  <td width="6%" onClick="chgTriSelection('idConstruction','<?=$sens?>','<?=$masque?>','<?=$affPosition?>','<?=$selection?>')" >
                    <div align="center"><font color="#FFFFFF">Pla</font></div></td>
                  <td width="6%" onClick="chgTriSelection('idAilier','<?=$sens?>','<?=$masque?>','<?=$affPosition?>','<?=$selection?>')" >
                    <div align="center"><font color="#FFFFFF">Wn</font></div></td>
                  <td width="6%" onClick="chgTriSelection('idButeur','<?=$sens?>','<?=$masque?>','<?=$affPosition?>','<?=$selection?>')" >
                    <div align="center"><font color="#FFFFFF">Sco</font></div></td>
                  <td width="6%" onClick="chgTriSelection('idGardien','<?=$sens?>','<?=$masque?>','<?=$affPosition?>','<?=$selection?>')" >
                    <div align="center"><font color="#FFFFFF">Kee</font></div></td>
                  <td width="7%" onClick="chgTriSelection('idPasse','<?=$sens?>','<?=$masque?>','<?=$affPosition?>','<?=$selection?>')">
                    <div align="center"><font color="#FFFFFF">Pas</font></div></td>
                  <td width="7%" onClick="chgTriSelection('idDefense','<?=$sens?>','<?=$masque?>','<?=$affPosition?>','<?=$selection?>')">
                    <div align="center"><font color="#FFFFFF">Def</font></div></td>
                  <td width="7%" onClick="chgTriSelection('idPA','<?=$sens?>','<?=$masque?>','<?=$affPosition?>','<?=$selection?>')">
                    <div align="center"><font color="#FFFFFF">Set</font></div></td>


                  <td width="5%" onClick="chgTriSelection('optionJoueur','<?=$sens?>','<?=$masque?>','<?=$affPosition?>','<?=$selection?>')">
                    <div align="center"><font color="#FFFFFF">Sp&eacute;</font></div></td>
                  
                  </tr>
                  <tr> 
                    <td colspan="15" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                  </tr>
                 
				             <?php
				$lst = 1;
			$huit = 60 * 60 * 24 * 8; //time_0
			  $quinze = 60 * 60 * 24 * 15; //time_1
			  $trente = 60 * 60 * 24 * 30; //time_2
			  $twomonths = 60 * 60 * 24 * 60; //time_3
			  $fourmonths = 60 * 60 * 24 * 120; //time_4
			  
			  // Date du jour
			 $mkday = mktime(0,0,0,date('m'), date('d'),date('Y'));
			 
			foreach($lstJoueurs as $l){
			
			  
			  $infTraining = getEntrainement($l["idJoueur"]);
			  
		switch($lst){
			case 1:
			$bgcolor = "#EEEEEE";
			$lst = 0;
			break;
			
			case 0:
			$bgcolor = "white";
			$lst = 1;
			break;
			}
			
		




 $val = array($l["scoreGardien"],$l["scoreDefense"],$l["scoreAilierDef"],$l["scoreAilierOff"],$l["scoreWtm"],$l["scoreMilieu"],$l["scoreMilieuOff"],$l["scoreAttaquant"]);
sort($val);
$valMax =  $val[7];
$val2 = $val[6];
			  
			  $class = "#";
			  
			 
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
                    <td nowrap >  &nbsp;
              
<?php
	if 	( ($sesUser["idNiveauAcces"]==1 || $sesUser["idNiveauAcces"]==4) 
		|| (($sesUser["idNiveauAcces"]==2 || $sesUser["idNiveauAcces"]==3)
		&& ($l["ht_posteAssigne"]==$sesUser["idPosition_fk"]))){
		?><a href ="<?=$url?>/joueurs/fiche.php?id=<?=$l["idJoueur"]?>" class="bred1">
<?php }?>
<?=strtolower($l["nomJoueur"])?><?=strtolower($l["prenomJoueur"])?>
<?php
	if 	( ($sesUser["idNiveauAcces"]==1 || $sesUser["idNiveauAcces"]==4) 
		|| (($sesUser["idNiveauAcces"]==2 || $sesUser["idNiveauAcces"]==3)
		&& ($l["ht_posteAssigne"]==$sesUser["idPosition_fk"]))){
?>
</a>
<?php		
}?>&nbsp;<img src="../images/time_<?=$img_nb?>.gif" onMouseOver="return escape('<?=$zealt?>')" >                      
                    </td>
                    <td ><?=$infTraining["valeurEnCours"]?></td>
                    <td ><?=$lstPos[$l["ht_posteAssigne"]-1]["intitulePosition"]?></td>
                    <td nowrap="nowrap"><div align="center"><?=$l["ageJoueur"]?> 
                      - <?=$l["jourJoueur"]?>
                    </div></td>
                    <td width="20"> <div align="center"><?=$l["idExperience_fk"]?></div></td>
                    
<?php 
	if 	( ($sesUser["idNiveauAcces"]==1 || $sesUser["idNiveauAcces"]==4) 
		|| (($sesUser["idNiveauAcces"]==2 || $sesUser["idNiveauAcces"]==3)
		&& ($l["ht_posteAssigne"]==$sesUser["idPosition_fk"]))){
	 ?>
                     
                    <td > <div align="center">
                    <?=$l["idEndurance"]?></div></td>
                    <td > <div align="center"> 
                    <?=$l["idConstruction"]?><?php afficheLesPlus($l,"nbSemaineConstruction"); ?> 
                      </div></td>
                    <td > <div align="center"> 
                    <?=$l["idAilier"]?><?php afficheLesPlus($l,"nbSemaineAilier"); ?> 
                      </div></td>
                    <td > <div align="center"> 
                    <?=$l["idButeur"]?><?php afficheLesPlus($l,"nbSemaineButeur"); ?> 
                      </div></td>
                    <td > <div align="center"> 
                    <?=$l["idGardien"]?><?php afficheLesPlus($l,"nbSemaineGardien"); ?> 
                      </div></td>
                    <td > <div align="center"> 
                    <?=$l["idPasse"]?><?php afficheLesPlus($l,"nbSemainePasse"); ?> 
                      </div></td>
                    <td > <div align="center"> 
                    <?=$l["idDefense"]?><?php afficheLesPlus($l,"nbSemaineDefense"); ?> 
                      </div></td>
                    <td > <div align="center"> 
                    <?=$l["idPA"]?> 
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
    
	
	<?php
		}
?>






                    <td width="30"> <div align="center"> 
                        <?=$specabbrevs[$l["optionJoueur"]]["FR"]?>
                      </div></td>
    <?php
				}
				?>
 
            </tr>           
          </table>
        </div></td>
    </tr>
  </table>
  <br>
  <?php
  if($affPosition == "" || $affPosition == 0){
  ?>
  <?php }?>
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


