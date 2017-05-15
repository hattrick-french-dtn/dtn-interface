<?php 
require("../includes/head.inc.php");
require("../includes/serviceEquipes.php");
require("../includes/serviceListesDiverses.php");


if(!$sesUser["idAdmin"])
	{
	header("location: index.php?ErrorMsg=Session Expiree");
	}
if(!isset($ordre)) $ordre = "nomJoueur";
if(!isset($sens)) $sens = "ASC";
if(!isset($lang)) $lang = "FR";


require("../includes/langue.inc.php");










$lstAgg = listAggres();
$lstCarac = listCaractere();
$lstHon = listHonnetete();
$lstLeader = listLeadership();
//


switch($sesUser["idNiveauAcces_fk"]){


case 1:
case 4:
// Full Acces
$sqlJ = "SELECT * FROM ht_joueurs  WHERE  affJoueur = 1 ";
$sqlJ .= "  order by $ordre $sens";


break;


case "2":
// Acc�s superviseur
// Acces limit� au joueur qu'il a soumis et au joueurs de son poste 


if($sesUser["idPosition_fk"] != ""){
$sqlJ = "SELECT * FROM ht_joueurs  WHERE  affJoueur = 1 AND (ht_posteAssigne = ".$sesUser["idPosition_fk"]." OR AdminSaisieJoueur_fk = ".$sesUser["idAdmin"].") ";
$sqlJ .= "  ORDER BY $ordre $sens";
}
else {
$sqlJ = "SELECT * FROM ht_joueurs  WHERE  affJoueur = 1  ";
$sqlJ .= "  ORDER BY $ordre $sens";
}






break;




case "3":
// Acces qu'au joueur qu'ils ont cr�e et qu'ils n'ont pas �t� valid�
$sqlJ = "SELECT * FROM ht_joueurs  WHERE  affJoueur = 1 AND joueurActif = 0 AND AdminSaisieJoueur_fk = ".$sesUser["idAdmin"]." ";
$sqlJ .= "  order by $ordre $sens";
break;




}


//$sql = "select * from $tbl_caracteristiques order by numCarac";
$lstCaractJ = listCarac('ASC',23);


$lstClubs = listClubs();


?>
<link href="../css/ht.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
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


}










?><title>Superviseur</title>
<script language="JavaScript" type="text/JavaScript">
<!--
<!--


//-->


function init()
{
var scrollPos = "<?=$scrollPos?>";
document.body.scrollTop = scrollPos;


}//-->


function testChamp(){
	
	
if(
document.form1.nomJoueur.value == "" ||
document.form1.prenomJoueur.value == "" ||
document.form1.saisonApparitionJoueur.value == "" ||
document.form1.ageJoueur.value == "" ||
document.form1.idHattrickJoueur.value == "" ||
document.form1.nomJoueur.value == "") {
alert("Les champs marqu�s d'une ast�risque (*) sont obligatoire !"); 
return false;
}
else return true;






}
</script>
<body onLoad = "init();">
<form name="form1" method="post" action="../form.php" onSubmit="return testChamp()">  
<table width="700" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td height ="20" ><div align="center">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td width="76%" height="20" bgcolor="#000000"> <div align="center"><font color="#FFFFFF">Gestion 
                  des joueurs</font></div></td>
              <td width="11%" bgcolor="#000000">&nbsp;</td>
              <td width="13%" bgcolor="#000000">&nbsp;</td>
            </tr>
            <tr> 
              <td height="1" colspan="3" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
            </tr>
            <tr> 
              <td colspan="3"><br> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr> 
                    <td width="19%"><div align="center">Nom *</div></td>
                    <td width="30%"><div align="center"> 
                        <input name="nomJoueur" type="text" id="nomJoueur">
                      *</div></td>
                    <td width="11%"><div align="center">Pr&eacute;nom </div></td>
                    <td width="40%"><div align="center"> 
                        <input name="prenomJoueur" type="text" id="prenomJoueur">
                      *</div></td>
                  </tr>
                </table>
                <br> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr> 
                    <td><strong>&nbsp; Informations :</strong></td>
                  </tr>
                  <tr> 
                    <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                  </tr>
                </table>
                <br> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr> 
                    <td height="25"><div align="left">&nbsp;Club actuel :</div></td>
                    <td height="25"><select name="teamid" id="select2">
                       <option>Non specifie</option>
                          <?php
					foreach($lstClubs as $l){
				echo "<option value=".$l["idClubHT"].">".$l["nomClub"]." (".$l["idClubHT"].")</option>";    
					
					}
					?>
                      </select> 
                      * [<a href="<?=$url?>/clubs/index.php" target="_blank">Ajouter 
                        un club</a>]                   </td>
                  </tr>
                  <tr> 
                    <td height="25"><div align="left">&nbsp;Apparition &agrave; 
                        la saison :</div></td>
                    <td height="25"><input name="saisonApparitionJoueur" type="text" id="saisonApparitionJoueur2" size="3"> 
                    *  
                    </td>
                  </tr>
                  <tr> 
                    <td height="25"><div align="left">&nbsp;Age</div></td>
                    <td height="25"><input name="ageJoueur" type="text" id="ageJoueur2" size="3"> 
                    *  
                    </td>
                  </tr>
                  <tr> 
                    <td height="25">&nbsp;ID Hattrick</td>
                    <td height="25"><input name="idHattrickJoueur" type="text" id="idHattrickJoueur"> 
                      * [ <a href="javascript:verifPlayer();"> Verifier si un joueur existe d&eacute;ja</a> ] </td>
                  </tr>
                </table>
                <br> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr> 
                    <td>&nbsp;<strong> 
                      <?=$caracMental["$lang"]?>
                      : </strong> </td>
                  </tr>
                  <tr> 
                    <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                  </tr>
                </table>
                <br> 
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr> 
                    <td height="20" colspan="2" align="lef"> &nbsp; 
                      <?=$caractere["$lang"]?>
                    </td>
                    <td height="20" colspan="2" align="lef"> 
                      <?=$exp["$lang"]?>
                    </td>
                  </tr>
                  <tr> 
                    <td width="11%" valign="top">&nbsp;</td>
                    <td width="34%"><select name="idCaractere_fk" size="<?=count($lstCarac)?>" id="select3">
                        <?php
				foreach($lstCarac as $l)
				{
				echo "<option value=".$l["idCaractere"].">".$l["numCaractere"]." - ".$l["intituleCaractereFR"]."|".$l["intituleCaractereUK"]."</option>";    
				}
				?>
                      </select></td>
                    <td width="14%" valign="top">&nbsp;</td>
                    <td width="41%" valign="top"><select name="idExperience_fk" id="select4">
                        <?php
				for($i=0;$i<count($lstCaractJ);$i++)
				{
				echo "<option value=".$lstCaractJ[$i]["idCarac"].">".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";    
				}
				?>
                      </select></td>
                  </tr>
                  <tr> 
                    <td valign="top"><div align="center"></div></td>
                    <td>&nbsp;</td>
                  <td valign="top"><div align="center"></div></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr> 
                    <td height="20" colspan="2" valign="left"> &nbsp; 
                      <?=$agressivite["$lang"]?>
                    </td>
                    <td height="20" colspan="2" valign="top"> 
                      <?=$chef["$lang"]?>
                      <div align="left"></div></td>
                  </tr>
                  <tr> 
                    <td width="11%" valign="top">&nbsp;</td>
                    <td><select name="idAggre_fk" size="<?=count($lstAgg)?>" id="select9">
                        <?php
				foreach($lstAgg as $l){
				echo "<option value=".$l["idAggres"].">".$l["numAggres"]." - ".$l["intituleAggresFR"]."|".$l["intituleAggresUK"]."</option>";    
				}?>
                      </select></td>
                    <td valign="top">&nbsp;</td>
                    <td><select name="idLeader_fk" size="<?=count($lstLeader)?>" id="select8">
                        <?php
				foreach($lstLeader as $l)
				{
				echo "<option value=".$l["idLeader"].">".$l["numLeader"]." - ".$l["intituleLeaderFR"]."|".$l["intituleLeaderUK"]."</option>";    
				}
				?>
                      </select></td>
                  </tr>
                  <tr> 
                    <td valign="top">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr> 
                    <td height="20" colspan="2" valign="top"> &nbsp; 
                      <?=$honnetete["$lang"]?>
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr> 
                    <td valign="top">&nbsp;</td>
                    <td><select name="idHonnetete_fk" size="<?=count($lstHon)?>" id="select10">
                        <?php
				for($i=0;$i<count($lstHon);$i++)
				{
				echo "<option value=".$lstHon[$i]["idHonnetete"].">".$lstHon[$i]["numHonnetete"]." - ".$lstHon[$i]["intituleHonneteteFR"]."|".$lstHon[$i]["intituleHonneteteUK"]."</option>";    
				}
				?>
                      </select></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
                <br> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr> 
                    <td><strong>&nbsp; 
                      <?=$caracPhysique["$lang"]?>
                      : </strong> </td>
                  </tr>
                  <tr> 
                    <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                  </tr>
                </table>
                <br> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr> 
                    <td width="25%" height="25">&nbsp;
                      <?=$endurance["$lang"]?>
                    </td>
                    <td width="25%" height="25"><select name="idEndurance" id="idEndurance">
                        <?php
				for($i=0;$i<count($lstCaractJ);$i++)
				{
				echo "<option value=".$lstCaractJ[$i]["idCarac"].">".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";    
				}
				?>
                      </select> </td>
                    <td width="25%" height="25">&nbsp;
                      <?=$gardien["$lang"]?>
                    </td>
                    <td width="25%" height="25"><select name="idGardien" id="idGardien">
                        <?php
				for($i=0;$i<count($lstCaractJ);$i++)
				{
				echo "<option value=".$lstCaractJ[$i]["idCarac"].">".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";   
				}
				?>
                      </select></td>
                  </tr>
                  <tr> 
                    <td height="25">&nbsp;
                      <?=$construction["$lang"]?>
                    </td>
                    <td height="25"><select name="idConstruction" id="idConstruction">
                        <?php
				for($i=0;$i<count($lstCaractJ);$i++)
				{
				echo "<option value=".$lstCaractJ[$i]["idCarac"].">".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";    
				}
				?>
                      </select></td>
                    <td height="25">&nbsp;
                      <?=$passe["$lang"]?>
                    </td>
                    <td height="25"><select name="idPasse" id="idPasse">
                        <?php
				for($i=0;$i<count($lstCaractJ);$i++)
				{
				echo "<option value=".$lstCaractJ[$i]["idCarac"].">".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";    
				}
				?>
                      </select></td>
                  </tr>
                  <tr> 
                    <td height="25">&nbsp;
                      <?=$ailier["$lang"]?>
                    </td>
                    <td height="25"><select name="idAilier" id="idAilier">
                        <?php
				for($i=0;$i<count($lstCaractJ);$i++)

				{
				echo "<option value=".$lstCaractJ[$i]["idCarac"].">".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";    
				}
				?>
                      </select></td>
                    <td height="25">&nbsp;
                      <?=$defense["$lang"]?>
                    </td>
                    <td height="25"><select name="idDefense" id="idDefense">
                        <?php
				for($i=0;$i<count($lstCaractJ);$i++)
				{
				echo "<option value=".$lstCaractJ[$i]["idCarac"].">".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";    
				}
				?>
                      </select></td>
                  </tr>
                  <tr> 
                    <td height="25">&nbsp;
                      <?=$buteur["$lang"]?>
                    </td>
                    <td height="25"><select name="idButeur" id="idButeur">
                        <?php
				for($i=0;$i<count($lstCaractJ);$i++)
				{
				echo "<option value=".$lstCaractJ[$i]["idCarac"].">".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";    
				}
				?>
                      </select></td>
                    <td height="25">&nbsp;
                      <?=$pa["$lang"]?>
                    </td>
                    <td height="25"><select name="idPA" id="idPA">
                        <?php
				for($i=0;$i<count($lstCaractJ);$i++)
				{
				echo "<option value=".$lstCaractJ[$i]["idCarac"].">".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";    
				}
				?>
                      </select></td>
                  </tr>
                </table>
                <div align="center"> <br>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td><strong>&nbsp; 
                        <?=$caracOption["$lang"]?>
                        : </strong> </td>
                    </tr>
                    <tr> 
                      <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                  <br>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td width="25%">&nbsp;Intitul&eacute; :</td>
                      <td width="75%"><select name="optionJoueur" id="optionJoueur">
				  				  <option value = "0">Aucune</option>


				  <?php
				  for($i=0;$i<count($option);$i++){
				echo "<option value=".$option[$i]["FR"].">".$option[$i]["FR"]."|".$option[$i]["UK"]."</option>";    
				  }
				  
				  ?>
                        </select></td>
                    </tr>
                  </table>
                  <p> 
                    <?php
			  if($msg) echo "<h3><center><font color = red>".stripslashes($msg)."</font></center></h2>";
			  ?>
                    <input type="submit" name="Submit" value="Ajouter ce joueur">
                    <input name="mode" type="hidden" id="mode" value="ajoutJoueur">
                  </p>
                  <p>&nbsp;</p>
                </div></td>
            </tr>
          </table>
      </div>
    </td>
  </tr>
</table>
</form>
<p><br>
</p>
<table width="700" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td height="20" ><div align="center">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="20" bgcolor="#000000"><div align="center"><font color="#FFFFFF">Liste
                  des joueurs</font></div>
            </td>
          </tr>
          <tr>
            <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td width="289" height="20" onClick="chgTri('nomJoueur','<?=$sens?>')"><strong>&nbsp;Identit&eacute;</strong></td>
                  <td width="1" bgcolor="#000000" onClick="chgTri('nomJoueur','<?=$sens?>')"><img src="../images/spacer.gif" width="1" height="1"></td>
                  <td width="119" onClick="chgTri('nomJoueur','<?=$sens?>')"><div align="center"><strong>Fiche 
                      joueur </strong> :</div></td>
                  <td width="1" bgcolor="#000000"><strong><img src="../images/spacer.gif" width="1" height="1"> 
                    </strong></td>
                  <td width="127"><div align="center"><strong>Modifier</strong></div></td>
                  <td width="1" bgcolor="#000000"><div align="center"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></div></td>
                  <td width="132"><div align="center"><strong>Archiver</strong></div></td>
                </tr>
		<?php
		
		$lst = 1;

		foreach($conn->query($sqlJ) as $lstJoueurs){

			switch($lst){
			case 1:
			$bgcolor = "#D8D8D8";
			$lst = 0;
			break;
			
			case 0:
			$bgcolor = "white";
			$lst = 1;
			break;
			}
			
			?>
                <tr bgcolor="#000000"> 
                  <td colspan="3"><img src="../images/spacer.gif" width="1" height="1"></td>
                  <td width="1"><img src="../images/spacer.gif" width="1" height="1"></td>
                  <td><img src="../images/spacer.gif" width="1" height="1"></td>
                  <td width="1"><img src="../images/spacer.gif" width="1" height="1"></td>
                  <td><img src="../images/spacer.gif" width="1" height="1"></td>
                </tr>
                <tr bgcolor="<?=$bgcolor?>"> 
                  <td>&nbsp; 
                    <?=$lstJoueurs["nomJoueur"]?>
    <?=$lstJoueurs["prenomJoueur"]?>
                  </td>
                  <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                  <td onClick="javascript:fiche('<?=$lstJoueurs["idJoueur"]?>')"><div align="center"><a href="javascript:fiche('<?=$lstJoueurs["idJoueur"]?>');"><img src="../images/carre-rouge.gif" width="10" height="10" border="0"></a></div></td>
                  <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                  
				  <?php
				  $aff = false;
				  if($sesUser["idNiveauAcces_fk"] == 1) {
				  $aff = true;
				  }
				  else if($sesUser["idNiveauAcces_fk"] == 2) {
				  		if($sesUser["idPosition_fk"] == $lstJoueurs["ht_posteAssigne"] || $sesUser["idPosition_fk"] == "") $aff = true;
				  }
				  else if($sesUser["idNiveauAcces_fk"] == 3) {
				  		if($lstJoueurs["joueurActif"] == 0 && $sesUser["idAdmin"] == $lstJoueur["AdminSaisieJoueur_fk"]) $aff = true;
				  }
				  
			if($aff == true){				  ?>
				  <td onClick="javascript:modifier('<?=$lstJoueurs["idJoueur"]?>','modifJoueur')"><div align="center"><a href = "javascript:modifier('<?=$lstJoueurs["idJoueur"]?>','modifJoueur')"><img src="../images/carre-rouge.gif" border = "0" width="10" height="10"></a></div></td>
				  <?php
				  }
				  else echo "<td>&nbsp;</td>";
				  ?>
				  
                  <td width="1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                
			
				  <td bgcolor="<?=$bgcolor?>"><? if($aff == true){				  ?><div align="center"><a href = "javascript:supprimer('<?=$lstJoueurs["idJoueur"]?>','archiveJoueur')"><img src="../images/carre-rouge.gif" border = "0" width="10" height="10"></a></div><?php }			  ?></td>
				
			  
				  
                </tr>
                <?php
			  }
			  ?>
              </table></td>
          </tr>
        </table>
      </div>
    </td>
  </tr>
</table>
</body>
