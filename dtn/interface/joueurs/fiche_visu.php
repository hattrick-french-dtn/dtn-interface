<?php
require_once("../includes/head.inc.php");




if(!$sesUser["idAdmin"])
{
	header("location: ../index.php?ErrorMsg=Session Expiree");
}


if(!isset($lang)) $lang = "FR";




if($lang == "fr") $lang = "FR";
if($lang == "en") $lang = "EN";


require("../includes/serviceEntrainement.php");
require("../includes/serviceJoueur.php");
require("../includes/langue.inc.php");

if (isset($htid))
{
	$infJ = getJoueurHt($htid);
	$id = $infJ["idJoueur"];
}
else
	$infJ = getJoueur($id);
	


switch($sesUser["idNiveauAcces"]){
		
		case "3":
			if ($sesUser["idPosition_fk"]!= $infJ["ht_posteAssigne"]
				&& $infJ["ht_posteAssigne"]!=0 
			){
				print("<html><body><center>Ce joueur est associ&eacute; &agrave; un autre secteur de jeu.<br> Pas de consultation.</center></body></html>");
				return;
			}
		break;
		default;
		break;
}


	




$lstCaracJoueur = array($endurance["$lang"]=>$infJ["idEndurance"],
						$gardien["$lang"]=>$infJ["idGardien"],
						$construction["$lang"]=>$infJ["idConstruction"],
						$passe["$lang"]=>$infJ["idPasse"],
						$ailier["$lang"]=>$infJ["idAilier"],
						$defense["$lang"]=>$infJ["idDefense"],
						$buteur["$lang"]=>$infJ["idButeur"],
						$pa["$lang"]=>$infJ["idPA"]
						);
$lienModif="off";					
if ($infJ["loginAdminSuiveur"] == $sesUser["loginAdmin"]){
$lienModif="on";					

}						
$val = array($infJ["scoreGardien"],$infJ["scoreDefense"],$infJ["scoreAilier"],$infJ["scoreAilierOff"],$infJ["scoreAilierVersMilieu"],$infJ["scoreMilieu"],$infJ["scoreMilieuOff"],$lstJoueur["scoreAttaquant"]);
sort($val);
$valMax =  round($val[7],2);
$val2 = round($val[6],2);


$verifInternational = verifSelection($id);


?><html>
<head>
<title>Fiche <?=$infJ["prenomJoueur"]?> <?=$infJ["nomJoueur"]?></title>
<script language="JavaScript" type="text/JavaScript">
<!--






function checkSuppression()
{
	if( <?=$infJ["idJoueur"]?>== "" ||isNaN(<?=$infJ["idJoueur"]?>)){
		alert('erreur lors de la suppression... Avertir l\'&eacute;quipe technique merci.');
	}


	if (confirm('Voulez vous VRAIMENT supprimer ce joueur "<?=$infJ["idHattrickJoueur"]?>"?')){
		document.location="../form.php?mode=supprJoueur&id=<?=$infJ["idJoueur"]?>";
	}
}


function submitSupprimeDTN()
{
	if (confirm('Voulez vous VRAIMENT retirer ce joueur de son DTN?')){
		document.formSupprimeDTN.submit();
	}
}
function submitSupprimeSecteur()
{
	if (confirm('Voulez vous VRAIMENT retirer ce joueur de son Secteur de jeu?')){
		document.formSupprimeSecteur.submit();
	}
}


function submitSel()
{
document.formSelection.submit();




}//-->
</script>


<style type="text/css">



<!--
.Style1 {color: #FF0000}
-->
</style>
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
$idClubHT=$infJ['teamid'];
$idHT=$infJ['idHattrickJoueur'];


//require("../menu/menuJoueur.php");


?>


<table width="85%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td colspan="3" bgcolor="#000000"><b><div align="center"><font color="#FFFFFF">Fiche consultation 
         <?php     	if($verifInternational != ""){
         	?>&nbsp;<img src="../images/fr.gif" alt="<?=$verifInternational?>"><?php 
  		} ?>
              </font></div></b>
          </td>
        </tr>
        <tr> 
          <td height="2" colspan="3" bgcolor="#999999"><img src="../images/spacer.gif" width="1" height="1"></td>
        </tr>
  		<tr>
    		<td valign="top">
			<table width="90%" border="0" cellpadding="0" cellspacing="0">
        	<tr> 
          		<td colspan="3">&nbsp;</td>
        	</tr>
        	<tr> 
          	<td width="40%" align="left">&nbsp; <font color="#000099"><b><?=$infJ["idHattrickJoueur"]?>&nbsp;-&nbsp;<?=<?=$infJ["prenomJoueur"]?> $infJ["nomJoueur"]?>&nbsp;-&nbsp;<?php 
			$ageetjours = ageetjour($infJ["datenaiss"]);
			$tabage = explode(" - ",$ageetjours);
			echo $tabage[0];?>&nbsp;ans&nbsp;-&nbsp;<?=$tabage[1]?>&nbsp;jours<br>&nbsp; <?=number_format(round(($infJ["salary"]/10),2),"0"," "," ")?>&nbsp;�/semaine&nbsp;
			</b></font></td>
          	<td width="20%" align="left"><b>Club : </b><a href="<?=$url?>/clubs/fiche_club.php?idClubHT=<?=$infJ["teamid"]?>"><?=$infJ["nomClub"]?></a></td>
    	  	<td nowrap align="right" width="40%">
          		<?php
			
		  if($infJ["dtnSuiviJoueur_fk"] != 0){
		?>  
	    <font color="#000000"><strong>Ce joueur est suivi par  
		<?php
		if($sesUser["idNiveauAcces"] !=3){
			?> <a href ="liste_suivi.php?dtn=<?=$infJ["dtnSuiviJoueur_fk"]?>"><?=$infJ["loginAdminSuiveur"]?></A><?
			if($sesUser["idNiveauAcces"] == 2 ||  $sesUser["idNiveauAcces"] == 1){
		  if($infJ["dtnSuiviJoueur_fk"] != 0){
	 
?>                     
    &nbsp;
<?php
		  }
}
			
			 
        }else{ 
       		?><?=$infJ["loginAdminSuiveur"]?><?php }?>&nbsp;</strong></font><?php 		
		}else if($infJ["archiveJoueur"] == 1){
			 ?><font color="#FF0000"><strong>Ce joueur est archiv&eacute;&nbsp;</strong></font><?php
		}else {
		  ?><font color="#FF0000"><strong>Ce joueur n'est pas suivi !&nbsp;</strong></font><?php
		 }
		?></td>
		<td>
        <b><a href="https://hattrickportal.pro/Tracker/Player.aspx?playerID=<?=$joueurDTN["idHattrickJoueur"]?>" target="_blank"><img src="../images/htportal.png" width="100" height="20" border="0" align="absmiddle"></a></b>
        </td>
          
          
        </tr>
        </table>
        </td>
        </tr>
        <tr> 
          <td colspan="3" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        </tr>
        <tr> 
          <td colspan="3"><table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr> 
                <td><font color="#000099"><b>&nbsp;<?=$infJ["intitulePosition"]?><?php
          			  if($infJ["ht_posteAssigne"] != 0){
          	
          	if($sesUser["idNiveauAcces"] == 2 ||  $sesUser["idNiveauAcces"] == 1){?>
    &nbsp;      		
          		<?php }
          			  }?></b></font>                </td>
                <td>&nbsp;</td>
                <td colspan="2" ><div align="right">Joueur soumis par <strong><?=$infJ["loginAdminSaisie"]?> </strong>&nbsp; </div>
                </td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td colspan="2">&nbsp;</td>
            </tr>
              <tr> 
                <td colspan="2">Un type 
                  <?=$infJ["intituleCaractereFR"]?>
                  qui est 
                  <?=$infJ["intituleAggresFR"]?>
                  et 
                  <?=$infJ["intituleHonneteteFR"]?>
                  .<br>
                  Il a une 
                  <?=$infJ["nomXP_fr"];?>
                  exp&eacute;rience et un 
                  <?=$infJ["intituleLeaderFR"]?>
                  temp&eacute;rament de chef</td>
                                  <td colspan="2"><div align="center"><span class="Style1">
                    <?php if($msg == "archive") echo "Joueur correctement archiv&eacute;";?>
                    <?php if($msg == "desarchive") echo "Joueur correctement desarchiv&eacute;";?>
</span></div></td>
                  
              </tr>
              <tr> 
                <td colspan="4"> 
                  <?php if($infJ["optionJoueur"]) echo "<font color=\"#CC22DD\"><i>Specialit&eacute; : ".$option[$infJ["optionJoueur"]]["FR"]."</i></font>"?>
                </td>
              </tr>
              <tr> 
                <td colspan="4">&nbsp;</td>
              </tr>
              <tr> 
                <td colspan="4">Caract&eacute;ristiques physiques</td>
              </tr>
              <tr bgcolor="#000000"> 
                <td colspan="4"><img src="../images/spacer.gif" width="1" height="1"></td>
              </tr>
              <tr> 
                <td colspan="4">&nbsp;</td>
              </tr>
              <tr> 
                <td colspan="4"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <?php
		$i=1;	
				foreach($lstCaracJoueur as $int=>$val){


			switch($int){
			
			case "construction":
			$nbSemaineE = '(+'.$infJ["nbSemaineConstruction"].')';
			break;
			case "defense":
			$nbSemaineE = '(+'.$infJ["nbSemaineDefense"].')';
			break;
			
			case "buteur":
			$nbSemaineE = '(+'.$infJ["nbSemaineButeur"].')';
			break;


			case "ailier":
			$nbSemaineE = '(+'.$infJ["nbSemaineAilier"].')';
			break;
			case "gardien":
			$nbSemaineE = '(+'.$infJ["nbSemaineGardien"].')';
			break;
		
			case "passe":
			$nbSemaineE = '(+'.$infJ["nbSemainePasses"].')';
			break;
			
			case "coup franc":
			$nbSemaineE = '(+'.$infJ["nbSemaineCoupFranc"].')';
			break;
	
			default:
			$nbSemaineE ="";
			break;
			
			}
$sql =  "select * from $tbl_caracteristiques where numCarac = ".$val;
$req = $conn->query($sql);
$res = $req->fetch();
		
			
			?><td width = 25%><b><?=$int?> :</B></td><td width = 25%>&nbsp;<?=$res["intituleCaracFR"]?> <?=$nbSemaineE?></td><?php	




		  if($i % 2 == 0)  print("</tr><tr>");
$i++;
}


?>
                    </tr>
                  </table></td>
              </tr>
              
              <tr><td colspan="4">
              <table width="100%"  border="0">
              <tr>
                <td width="53%">&nbsp;</td>
                <td width="47%"><div align="right"><em>Derni&egrave;re maj DTN : <?=dateToHTML($infJ["dateDerniereModifJoueur"])?></em></div></td>
              </tr>
              <tr><td >&nbsp;</td>
                <td ><div align="right"><em>Derni&egrave;re maj propri&eacute;taire : <?=dateToHTML($infJ["dateSaisieJoueur"])?></em></div></td>
              </tr>
            </table>
            </td>
            </tr> <!-- fin carac physiques -->
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
          <td colspan="8" align="center">gK : <b><?=$infJ["scoreGardien"]?></b></td>
          </tr><tr align="center" bgcolor="white">
          <td>wB `off : <b><?=$infJ["scoreDefLatOff"]?></b></td>
          <td colspan="3" >cD `normal : <b><?=$infJ["scoreDefense"]?></b></td>
          <td colspan="3" >cD `off : <b><?=$infJ["scoreDefCentralOff"]?></b></td>
          <td>wB `normal : <b><?=$infJ["scoreDefLat"]?></b></td>
          </tr><tr align="center" bgcolor="white" valign="center">
          <td>Wg `normal : <b><?=$infJ["scoreAilier"]?></b><br>
          Wg `off : <b><?=$infJ["scoreAilierOff"]?></b></td>
          <td colspan="2">iM `def : <b><?=$infJ["scoreMilieuDef"]?></b></td>
          <td colspan="2">iM `normal : <b><?=$infJ["scoreMilieu"]?></b></td>
          <td colspan="2">iM `off : <b><?=$infJ["scoreMilieuOff"]?></b></td>
          <td>Wg `towards : <b><?=$infJ["scoreAilierVersMilieu"]?></b></td>
          </tr><tr align="center" bgcolor="white">
          <td colspan="2">Fw `towards : <b><?=$infJ["scoreAttaquantVersAile"]?></b></td>
          <td colspan="2">Fw `normal : <b><?=$infJ["scoreAttaquant"]?></b></td>
          <td colspan="4">Fw `def : <b><?=$infJ["scoreAttaquantDef"]?></b></td>
          </tr>
          </table>
          <br>
          </td>
        </tr>
        <!-- fin valeur par poste-->
            
        <!-- debut histo -->
            <tr> 
              <td colspan="4">Historique des modifications / remarques </td>
              </tr>
              <tr bgcolor="#000000"> 
                <td colspan="4"><img src="../images/spacer.gif" width="1" height="1"></td>
            </tr>
        <tr>
        <td colspan="4">
            <br>
            
           <table width="98%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#000000">
              <tr bgcolor="#EEEEEE"> 
                <td width=10%> <div align="center"><b>Date</b></div></td>
                <td width=10%><div align="center"><b>Heure</b></div></td>
                <td width=65%><div align="center"><b>Info joueur</b></div></td>
                <td width=15%><div align="center"><b>Auteur</b></div></td>
              </tr>
               <?php
			$sql = "select * from $tbl_histomodif LEFT JOIN ht_admin ON idAdmin = idAdmin_fk where idJoueur_fk = $id order by dateHisto desc, heureHisto desc ";
			$sql .= " limit 0,5";
			
			foreach($conn->query($sql) as $l){
		   
		   
		   ?>
		      <tr bgcolor="white"> 
                <td  > 
                  <div align="center"><?=dateToHTML($l["dateHisto"])?></div></td>
                <td  > 
                  <div align="center"><?=$l["heureHisto"]?></div></td>
                <td  > <div align="left">&nbsp;<?=$l["intituleHisto"]?></div></td>
                <td  > 
                  <div align="center"><?=$l["loginAdmin"]?></div></td>
              </tr>
 <?php } ?>
            </table>

			
<br>


            <table width="98%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#000000">
              <tr bgcolor="#EEEEEE"> 
                <td width=10%> <div align="center"><b>Date</b></div></td>
                <td width=10%><div align="center"><b>Heure</b></div></td>
                <td width=80%><div align="center"><b>Info club [<?=$infJ["nomClub"]?>]</b></div></td>
              </tr>
               <?php
			$sqlClubsHisto = "select * from ht_clubs_histo LEFT JOIN ht_clubs ON idClubHT=idClubHT_fk  where idClubHT=".$infJ["teamid"]." order by dateHisto desc, timeHisto desc LIMIT 0,5";
			$req = ;
			foreach($conn->query($sqlClubsHisto) as $lHisto){
		   
		   
		   ?>
		      <tr bgcolor="#FFFFFF"> 
                <td > 
                  <div align="center"><?=dateToHTML($lHisto["dateHisto"])?></div></td>
                <td > 
                  <div align="center"><?=$lHisto["timeHisto"]?></div></td>
                <td > <div align="left">&nbsp;<?=$lHisto["clubInfo"]?></div></td>
              </tr>
 <?php } ?>
            </table>
		
            
            
            <p align="center"><br>
            </p></td>
        </tr>
      </table></td>
  </tr>
                
	    
</table>
<div align="center"><a href="javascript:history.go(-1);">Retour</a></div>


 <form name="formSupprimeDTN" method="post" action="../form.php">
	  <input name="idJoueur" type="hidden"value="<?=$id?>">
      <input name="dtnname" type="hidden" value="<?=$infJ["loginAdminSuiveur"]?>">
      <input name="mode" type="hidden"  value="joueurSupprimeDTN">
</form>	


 <form name="formSupprimeSecteur" method="post" action="../form.php">
	  <input name="idJoueur" type="hidden"value="<?=$id?>">
      <input name="secteur" type="hidden" value="<?=$infJ["intitulePosition"]?>">
      <input name="mode" type="hidden"  value="joueurSupprimeSecteur">
</form>	


</body>
</html>
<?php  deconnect(); ?>
