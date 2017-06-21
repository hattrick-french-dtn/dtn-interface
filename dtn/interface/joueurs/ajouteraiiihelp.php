<?php
require_once("../includes/head.inc.php");




if(!$sesUser["idAdmin"])
	{
	header("location: index.php?ErrorMsg=Session Expiree");
	exit();
	}


if(!isset($lang)) $lang = "FR";




if($lang == "fr") $lang = "FR";
if($lang == "en") $lang = "EN";


require("../includes/serviceEntrainement.php");
require("../includes/serviceListesDiverses.php");
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
				header("location:fiche.php?id=$id");;
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


// Liste des caractéristiques pour liste déroulante
$liste_carac = listTypeCaracEntrainable();




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
<title>Fiche <?=$infJ["nomJoueur"]?> <?=$infJ["prenomJoueur"]?></title>
<script src="../../../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../../../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
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
<link href="../../../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="../../../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">
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
         	?>&nbsp;<img src="../images/fr.gif" alt="<?=$verifInternational?>"><?php 
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
            
        <!-- debut histo -->
        <tr>
        <td colspan="4">
            <p align="center"><br>
            </p>
<?php
if($sesUser["idNiveauAcces"] == 2 ||  $sesUser["idNiveauAcces"] == 1)
{
// verif si joueur déjà rentrer :
                 $sql = "select * from ht_iiihelp_joueur where etat = 0 and id_HT = ".$infJ["idHattrickJoueur"];
                 $req =  $conn->query($sql);
				 if ($res = $req->fetch())
				 {?>
					Le joueur est d&eacute;j&agrave; dans la table des ventes et les mails ne sont pas envoy&eacute;s !
				<?php }
				else
				{

 ?>            <form name="form1" method="post" action="validajoutiiihelp.php">
              <table width="543" border="0" align="center">
                <tr>
                  <td>Entrainement souhait&eacute; : </td>
                  <td><span id="spryselect1">
                    <select name="ent_voulu" id="ent_voulu">
                      <option value="-1" selected>Choisissez</option>
                      <?php
            		      for ($i=0;$i<count($liste_carac);$i++) {?>
                        <option 
                            value="<?php echo($liste_carac[$i]['idTypeCarac']);?>"  
                            <?php if ($_POST['entrainement_voulu1']==$liste_carac[$i]['idTypeCarac']) {?>selected="selected"<?php }?> >
                            <?php echo($liste_carac[$i]['nomTypeCarac']);?>
                        </option>
                      <?php }?>
                    </select>
                    <span class="selectInvalidMsg">Sélectionnez un élément valide.</span>                    <span class="selectRequiredMsg">Sélectionnez un élément.</span></span></td>
                </tr>
				<tr>
					<td>Cat&eacute;gorie d'age &agrave; entrainer : </td>
					<td><input type="radio" name="catage" id="catage" value="17-20 ans" <?php if (intval($tabage[0])<20) {?> checked="true" <?php }?>> 17-20 ans
						<input type="radio" name="catage" id="catage" value="+21 ans" <?php if (intval($tabage[0])>19) {?> checked="true" <?php }?>> 21 ans et +
					</td>

                <tr>
                  <td>MAP pr&eacute;vu</td>
                  <td><span id="sprytextfield1">
                  <input type="text" name="map" id="map">
&euro;                  <span class="textfieldRequiredMsg">Une valeur est requise.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span></span></td>
                </tr>
                <tr>
                  <td>Commentaire</td>
                  <td><textarea name="comment" cols="40" rows="5" id="comment"></textarea></td>
                </tr>
                <tr>
                  <td colspan="2" align="center"><input name="id" type="hidden" id="id" value="<?=$id?>">
                    <input type="submit" name="button" id="button" value="Envoyer"></td>
                  </tr>
              </table>
            </form><?php
			}
		 } ?>
            <p align="center">&nbsp;             
  </p></td>
        </tr>
      </table></td>
  </tr>
                
	    
</table>
<div align="center"><a href="javascript:history.go(-1);">Retour</a></div>
    </body>
    <script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "integer", {validateOn:["change"]});
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1", {invalidValue:"-1", validateOn:["change"]});
//-->
</script>
</html>
<?php  deconnect(); ?>
