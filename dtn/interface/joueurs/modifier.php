<?php 
require_once("../includes/head.inc.php");
require("../includes/serviceListesDiverses.php");
require("../includes/serviceJoueur.php");
require("../includes/serviceEquipes.php");




if(!$sesUser["idAdmin"])
	{
	header("location: index.php?ErrorMsg=Session Expir�");
	}
if(!isset($ordre)) $ordre = "nomJoueur";
if(!isset($sens)) $sens = "ASC";
if(!isset($lang)) $lang = "FR";


require("../includes/langue.inc.php");






$lstAgg = listAggres();
$lstCarac = listCaractere();
$lstHon = listHonnetete();
$lstLeader = listLeadership();
$lstCaractJ = listCarac('ASC',20);
$lstClubs = listClubs();
$infJoueur = getJoueur($id);
?>
<link href="../css/ht.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>


<title>Modifier un joueur</title>
<script language="JavaScript" type="text/JavaScript">
<!--
<!--


//-->
resizeTo(750,500);
 windowWidth=750; // largeur du popup
 windowHeight=500; // hauteur du popup
 window.moveTo((screen.width/2)-(windowWidth/2+10),(screen.height/2)-(windowHeight/2+20));//-->
</script>
<body>
<form name="form1" method="post" action="../form.php">  
<table width="700" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td height="20" ><div align="center">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td height="20" bgcolor="#000000"> <div align="center"><font color="#FFFFFF">Gestion 
                  des joueurs</font></div></td>
            </tr>
            <tr> 
              <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
            </tr>
            <tr> 
              <td><br> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr> 
                    <td width="19%"><div align="center">Nom</div></td>
                    <td width="30%"><div align="center"> 
                        <input name="nomJoueur" type="text" id="nomJoueur" value="<?=$infJoueur["nomJoueur"]?>">
                      </div></td>
                    <td width="11%"><div align="center">Pr&eacute;nom</div></td>
                    <td width="40%"><div align="center"> 
                        <input name="prenomJoueur" type="text" id="prenomJoueur" value="<?=$infJoueur["prenomJoueur"]?>">
                      </div></td>
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
                        <option>Non sp�cifi�&eacute;</option>
                        <?php
					for($i=0;$i<count($lstClubs);$i++){
					
			if($lstClubs[$i]["idClubHT"] == $infJoueur["teamid"]) $etat = "selected"; else $etat = "";


				echo "<option value=".$lstClubs[$i]["idClubHT"]." $etat>".$lstClubs[$i]["nomClub"]." (".$lstClubs[$i]["idClubHT"].")</option>";    
					
					}
					?>
                      </select> [<a href="http://localhost/hattrick/clubs/index.php" target="_blank">Ajouter 
                        un club</a>] </td>
                  </tr>
                  <tr> 
                    <td height="25"><div align="left">&nbsp;Apparition &agrave; 
                        la saison :</div></td>
                    <td height="25"><input name="saisonApparitionJoueur" type="text" id="saisonApparitionJoueur2" value="<?=$infJoueur["saisonApparitionJoueur"]?>" size="3"> 
                    </td>
                  </tr>
                  <tr> 
                    <td height="25"><div align="left">&nbsp;Age</div></td>
                    <td height="25"><input name="ageJoueur" type="text" id="ageJoueur2" size="3" value = "<?=$infJoueur["ageJoueur"]?>"> 
                    </td>
                  </tr>
                  <tr> 
                    <td height="25">&nbsp;ID Hattrick</td>
                    <td height="25"><input name="idHattrickJoueur" type="text" id="idHattrickJoueur"  value = "<?=$infJoueur["idHattrickJoueur"]?>"> 
                    </td>
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
                <br> <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr> 
                    <td width="20%" valign="top"> <div align="center"> 
                        <?=$caractere["$lang"]?>
                      </div></td>
                    <td width="25%"><p> 
                        <select name="idCaractere_fk" size="<?=count($lstCarac)?>" id="select3">
                          <?php
				for($i=0;$i<count($lstCarac);$i++)
				{
				
						if($lstCarac[$i]["idCaractere"] == $infJoueur["idCaractere_fk"]) $etat = "selected"; else $etat = "";




				echo "<option value=".$lstCarac[$i]["idCaractere"]." $etat>".$lstCarac[$i]["numCaractere"]." - ".$lstCarac[$i]["intituleCaractereFR"]."|".$lstCarac[$i]["intituleCaractereUK"]."</option>";    
				}
				?>
                        </select>
                        <br>
                      </p></td>
                    <td width="23%" valign="top"> <div align="center"> 
                        <?=$exp["$lang"]?>
                      </div></td>
                    <td width="32%" valign="top"> <select name="idExperience_fk" id="select7">
                        <?php
				for($i=0;$i<count($lstCaractJ);$i++)
				{
				
		if($lstCaractJ[$i]["idCarac"] == $infJoueur["idExperience_fk"]) $etat = "selected"; else $etat = "";


				echo "<option value=".$lstCaractJ[$i]["idCarac"]. " $etat>".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";    
				}
				?>
                      </select> </td>
                  </tr>
                  <tr> 
                    <td valign="top"><div align="center"></div></td>
                    <td>&nbsp;</td>
                    <td valign="top"><div align="center"></div></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr> 
                    <td width="20%" valign="top"> <div align="center"> 
                        <?=$agressivite["$lang"]?>
                      </div></td>
                    <td> <select name="idAggre_fk" size="<?=count($lstAgg)?>" id="idAggre_fk">
                        <?php
				for($i=0;$i<count($lstAgg);$i++){
				
		if($lstAgg[$i]["idAggres"] == $infJoueur["idAggre_fk"]) $etat = "selected"; else $etat = "";


				echo "<option value=".$lstAgg[$i]["idAggres"]." $etat>".$lstAgg[$i]["numAggres"]." - ".$lstAgg[$i]["intituleAggresFR"]."|".$lstAgg[$i]["intituleAggresUK"]."</option>";    
				}?>
                      </select></td>
                    <td valign="top"> <div align="center"> 
                        <?=$chef["$lang"]?>
                      </div></td>
                    <td> <select name="idLeader_fk" size="<?=count($lstLeader)?>" id="select6">
                        <?php
				for($i=0;$i<count($lstLeader);$i++)
				{
				
						if($lstLeader[$i]["idLeader"] == $infJoueur["idLeader_fk"]) $etat = "selected"; else $etat = "";


				echo "<option value=".$lstLeader[$i]["idLeader"]." $etat>".$lstLeader[$i]["numLeader"]." - ".$lstLeader[$i]["intituleLeaderFR"]."|".$lstLeader[$i]["intituleLeaderUK"]."</option>";    
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
                    <td valign="top"><center>
                        <?=$honnetete["$lang"]?>
                      </center></td>
                    <td> <select name="idHonnetete_fk" size="<?=count($lstHon)?>" id="select4">
                        <?php
				for($i=0;$i<count($lstHon);$i++)
				{

				
		if($lstHon[$i]["idHonnetete"] == $infJoueur["idHonnetete_fk"]) $etat = "selected"; else $etat = "";


				echo "<option value=".$lstHon[$i]["idHonnetete"]." $etat>".$lstHon[$i]["numHonnetete"]." - ".$lstHon[$i]["intituleHonneteteFR"]."|".$lstHon[$i]["intituleHonneteteUK"]."</option>";    
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
				
		if($lstCaractJ[$i]["numCarac"] == $infJoueur["idEndurance"]) $etat = "selected"; else $etat = "";


				echo "<option value=".$lstCaractJ[$i]["idCarac"]." $etat>".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";    
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
				
		if($lstCaractJ[$i]["numCarac"] == $infJoueur["idGardien"]) $etat = "selected"; else $etat = "";
			
				echo "<option value=".$lstCaractJ[$i]["idCarac"]." $etat>".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";    
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
				
		if($lstCaractJ[$i]["numCarac"] == $infJoueur["idConstruction"]) $etat = "selected"; else $etat = "";
			
				echo "<option value=".$lstCaractJ[$i]["idCarac"]." $etat>".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";    
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
				
		if($lstCaractJ[$i]["numCarac"] == $infJoueur["idPasse"]) $etat = "selected"; else $etat = "";


				echo "<option value=".$lstCaractJ[$i]["idCarac"]." $etat>".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";    
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
				
		if($lstCaractJ[$i]["numCarac"] == $infJoueur["idAilier"]) $etat = "selected"; else $etat = "";


				echo "<option value=".$lstCaractJ[$i]["idCarac"]." $etat>".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";    
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
				
		if($lstCaractJ[$i]["numCarac"] == $infJoueur["idDefense"]) $etat = "selected"; else $etat = "";


				echo "<option value=".$lstCaractJ[$i]["idCarac"]." $etat>".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";    
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
				
	if($lstCaractJ[$i]["numCarac"] == $infJoueur["idButeur"]) $etat = "selected"; else $etat = "";


				echo "<option value=".$lstCaractJ[$i]["idCarac"]." $etat>".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";    
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
				
	if($lstCaractJ[$i]["numCarac"] == $infJoueur["idPA"]) $etat = "selected"; else $etat = "";
	
				echo "<option value=".$lstCaractJ[$i]["idCarac"]." $etat>".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";    
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
                       
				  
				  				  <?php
				  for($i=0;$i<count($option);$i++){
				  
						  	if($option[$i]["FR"] == $infJoueur["optionJoueur"]) $etat = "selected"; else $etat = "";
		  
				echo "<option value=".$i." ".$etat.">".$option[$i]["FR"]."|".$option[$i]["UK"]."</option>";    
				  }
				  
				  ?>


                        </select>
	</td>
                    </tr>
                  </table>
                  <p> 
                    <input type="submit" name="Submit" value="modifier ce joueur">
                    <input name="mode" type="hidden" id="mode" value="modifJoueur">
                    <input name="id" type="hidden" id="id" value="<?=$id?>">
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


</body>
