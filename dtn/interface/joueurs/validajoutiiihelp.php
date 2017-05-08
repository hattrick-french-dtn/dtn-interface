<?php
require("../includes/head.inc.php");




if(!$sesUser["idAdmin"])
	{
	header("location: index.php?ErrorMsg=Session Expiree");
	exit();
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
	
if($sesUser["idNiveauAcces"]=="2"){
			if ($sesUser["idPosition_fk"]!= $infJ["ht_posteAssigne"] && $infJ["ht_posteAssigne"]!=0 ){
				header("location:fiche_visu.php?id=$id");;
				exit();
			}
}

switch($sesUser["idNiveauAcces"]){
		
		case "2":
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
$val = array($infJ["scoreGardien"],$infJ["scoreDefense"],$infJ["scoreAilierDef"],$infJ["scoreAilierOff"],$infJ["scoreWtm"],$infJ["scoreMilieu"],$infJ["scoreMilieuOff"],$lstJoueur["scoreAttaquant"]);
sort($val);
$valMax =  round($val[7],2);
$val2 = round($val[6],2);


$verifInternational = verifSelection($id);


// ajout à iiihelp!
                 $sql = "insert into ht_iiihelp_joueur values (0, ".$infJ["idHattrickJoueur"].", $id, NOW(), $ent_voulu, $map, '$comment', '0000-00-00', 0, '$catage')";
                 $req=  mysql_query($sql);



?><html>
<head>
<title>Fiche <?=$infJ["nomJoueur"]?> <?=$infJ["prenomJoueur"]?></title>

<script language="JavaScript" type="text/JavaScript">
<!--






function checkSuppression()
{
	if( <?=$infJ["idJoueur"]?>== "" ||isNaN(<?=$infJ["idJoueur"]?>)){
		alert('erreur lors de la suppression... Avertir l\'equipe technique merci.');
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


require("../menu/menuJoueur.php");


?>


<table width="85%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td colspan="3" bgcolor="#000000"><div align="center"><b><font color="#FFFFFF">Ajouter &agrave; iiihelp! :  
         <?php     	if($verifInternational != ""){
         	?>&nbsp;<img src="../images/fr.gif" alt="<?=$verifInternational?>"><? 
  		} ?>
              </font></b></div>
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
          	<td width="40%" align="left">&nbsp; <font color="#000099"><b><?=$infJ["idHattrickJoueur"]?>&nbsp;-&nbsp;<?=$infJ["nomJoueur"]?> <?=$infJ["prenomJoueur"]?>&nbsp;-&nbsp;<?php 
			$ageetjours = ageetjour($infJ["datenaiss"]);
			$tabage = explode(" - ",$ageetjours);
			echo $tabage[0];?>&nbsp;ans&nbsp;-&nbsp;<?=$tabage[1]?>&nbsp;jours<br>&nbsp; <?=round(($infJ["salary"]/10),2)?>&nbsp;€/semaine&nbsp;<a href="http://alltid.org/player/<?=$infJ["idHattrickJoueur"]?>" target="_blank"><img src="../images/ahstats.png" width="47" height="16" border="0" align="absmiddle"></a></b></font>          	</td>

          	<td width="20%" align="left"><b>Club : </b><a href="<?=$url?>/clubs/fiche_club.php?idClubHT=<?=$infJ["teamid"]?>"><?=$infJ["nomClub"]?></a></td>
    	  	<td nowrap align="right" width="40%">&nbsp;</td>
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
                    <?php if($msg == "archive") echo "Joueur correctement archive";?>
                    <?php if($msg == "desarchive") echo "Joueur correctement desarchive";?>
</span></div></td>
              </tr>
              <tr> 
                <td colspan="4"> 
                  <?php if($infJ["optionJoueur"]) echo "<font color=\"#CC22DD\"><i>Specialite : ".$option[$infJ["optionJoueur"]]["FR"]."</i></font>"?>                </td>
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
	
			default:
			$nbSemaineE ="";
			break;
			
			}
$sql =  "select * from $tbl_caracteristiques where numCarac = ".$val;
$req = mysql_query($sql);
$res = mysql_fetch_array($req);
		
			
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
            
        <!-- debut histo -->
        <tr>
        <td colspan="4">
            <p align="center"><br>
            </p>
            Le joueur a &eacute;t&eacute; rajout&eacute; &agrave; la liste des ventes pour une reprise, merci.
			<a href="../admin/liste_joueur_iiihelp.php">Se rendre à la liste</a>
            <p align="center">&nbsp;             
  </p></td>
        </tr>
      </table></td>
  </tr>
                
	    
</table>
<div align="center"><a href="javascript:history.go(-1);">Retour</a></div>
    </body>
</html>
<?php  deconnect(); ?>
