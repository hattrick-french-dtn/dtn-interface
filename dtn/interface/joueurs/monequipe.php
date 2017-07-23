<?php 
require_once("../includes/head.inc.php");
require("../includes/serviceJoueur.php");

if(!$sesUser["idAdmin"])
	{
	header("location: index.php?ErrorMsg=Session Expirée");
	}
if(!isset($ordre)) $ordre = "nomJoueur";
if(!isset($sens)) $sens = "ASC";
if(!isset($lang)) $lang = "FR";
if(!isset($masque)) $masque = 0;
if(!isset($affPosition)) $affPosition = 0;

require("../includes/langue.inc.php");



//

$sql = "select * from $tbl_position";


switch($sesUser["idNiveauAcces_fk"]){
		case "1":
		break;
		case "2":
		if($sesUser["idPosition_fk"] != 0){
		$sql .= " where idPosition = ".$sesUser["idPosition_fk"];
		}
		break;
		
		
}

$lstPosition = construitListe($sql,$tbl_position);

$sql = "select * from $tbl_position";

$lstPosition2 = construitListe($sql,$tbl_position);




$sql = "select * from $tbl_joueurs ";

if(isset($affPosition) && $affPosition != 0) $sql .= " left join $tbl_position on ht_posteAssigne = idPosition where ht_posteAssigne = $affPosition ";
else
{
$sql .= " where ht_posteAssigne = 0";
}


$sql .= " and affJoueur = 1   order by $ordre $sens";

$reqJoueurs = $conn->query($sql);


switch($affPosition){

		case "1":
		//gK
		$k = 1;
		$keeperColor = "#999999";
		break;
		
		case "2":
		// cD
		$d = 1;
		$defense = 1;
		$defenseColor = "#999999";
		break;
		
		case "3":
		// Wg
		$construction = 1;
		$constructionColor = "#CCCCCC";
		$ailier = 1;
		$ailierColor = "#999999";
		$defense = 1;
		$defenseColor = "#CCCCCC";
		$passe = 1;
		$passeColor = "#CCCCCC";
		
		$wing = 1;
		$wingoff = 1;
		$wingwtm = 1;

		break;
		case "4":
		//IM
		$m = 1;
		$moff = 1;
		$construction = 1;
		$constructionColor = "#999999";
		$defense = 1;
		$defenseColor = "#CCCCCC";
		$passe = 1;
		$passeColor = "#CCCCCC";
		break;
		
		case "5":
		// Fw
				
		$att = 1;
		$passe = 1;
		$passeColor = "#999999";
		$buteur = 1;
		$buteurColor = "#999999";
		break;
	
		default:
		$font = "<font color = black>";
		$ffont = "</font>";
		break;
		
}

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
		
		case "4":
		require("../menu/menuCoach.php");
		break;
		
		default;
		break;

}





?><title>Superviseur</title>
<script language="JavaScript" src="menu_joueur.js"></script>
<style type="text/css">
<!--
.Style1 {color: #FFFFFF}
.Style3 {color: #FF9933}
.Style4 {color: #FF0000}
-->
</style>
<body onload = "init();">
<br>
<form name="form1" method="post" action="../form.php">
<br>

<?php
switch($sens){

case "ASC":
$tri = "Tri croissant";
break;

case "DESC":
$tri = "Tri décroissant";
break;
}

switch($ordre){

case "nomJoueur":
$intitule = "identité";
break;

case "ageJoueur":
$intitule = "Age";
break;

case "idExperience_fk":
$intitule = "expérience";
break;

case "idLeader_fk":
$intitule = "leadership";
break;

case "idCaractere_fk":
$intitule = "popularité";
break;


case "idAggre_fk":
$intitule = "agréssivité";
break;

case "idHonnetete_fk":
$intitule = "honnêteté";
break;

case "optionJoueur":
$intitule = "spécialité";
break;


case "idEndurance":
$intitule = "endurance";
break;


case "idConstruction":
$intitule = "construction";
break;

case "idAilier":
$intitule = "ailier";
break;
case "idButeur":
$intitule = "buteur";
break;

case "idGardien":
$intitule = "gardien";
break;

case "idPasse":
$intitule = "passe";
break;

case "idDefense":
$intitule = "défense";
break;

case "idPA":
$intitule = "coup de pieds arreté";
break;

case "scoreGardien":
$intitule = "score Gardien";
break;

case "scoreDefense":
$intitule = "score Gardien";
break;
case "scoreAilierOff":
$intitule = "score ailier offensif";
break;

case "scoreMilieu":
$intitule = "score milieu";
break;
case "scoreAttaquant":
$intitule = "score attaquant";
break;





}
?>


<center><h3><?=$tri?> par <?=$intitule?></h3></center>

<br>  <table width="980" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr> 
      <td height="20" ><div align="center"> 
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td width="28%" height="21"> <div align="center">Poste : 
                  <select name="menu1" onChange="MM_jumpMenu('parent',this,0)">
			<option value = listeInternational.php?affPosition=0>Liste des non assignés</option>
			 
			  <?php
			  for($i=0;$i<count($lstPosition);$i++){
			  if($affPosition == $lstPosition[$i]["idPosition"]) $etat = "selected"; else $etat = "";
			  echo "<option value = listeInternational.php?affPosition=".$lstPosition[$i]["idPosition"]." $etat >".$lstPosition[$i]["intitulePosition"]."</option>";
			  
			  }
			  
			  
			  ?>
			  
                  </select>
                </div></td>
              <td width="51%"><div align="center"><font color="#000000">Liste 
                  des joueurs</font></div></td>
              <td width="21%"> </td>
            </tr>
            <tr> 
              <td height="1" colspan="3" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
            </tr>
            <tr> 
              <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr bgcolor="#000000">
                  <td width="213" onClick="chgTri('nomJoueur','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')"><font color="#FFFFFF">Identit&eacute;</font></td>
                  <td width="82" rowspan="5"><div align="center"><span class="Style1">TSI</span></div></td>
                  <td width="25" onClick="chgTri('ageJoueur','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">Age</font></div></td>
                  <td width="22" onClick="chgTri('idExperience_fk','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">Xp</font></div></td>
                  <td width="24" onClick="chgTri('idLeader_fk','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">Ld</font></div></td>
                  <td width="30" onClick="chgTri('idCaractere_fk','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">Pop</font></div></td>
                  <td width="29" onClick="chgTri('idAggre_fk','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">Agg</font></div></td>
                  <td width="28" onClick="chgTri('idHonnetete_fk','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">Hon</font></div></td>
                  <td width="32"  onClick="chgTri('optionJoueur','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">Sp&eacute;</font></div></td>
                  <td width="31"onClick="chgTri('idEndurance','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">Sta</font></div></td>
                  <td width="30" height="17"onClick="chgTri('idConstruction','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">Pla</font></div></td>
                  <td width="37"onClick="chgTri('idAilier','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">Wn</font></div></td>
                  <td width="33"onClick="chgTri('idButeur','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">Sco</font></div></td>
                  <td width="30"onClick="chgTri('idGardien','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">Kee</font></div></td>
                  <td width="30" witdth = "20"onClick="chgTri('idPasse','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">Pas</font></div></td>
                  <td width="29" rowspan="5">
                    <div align="center"><font color="#FFFFFF">Def</font></div></td>
                  <td width="29" rowspan="5">
                    <div align="center"><font color="#FFFFFF">Set</font></div></td>
                  <td width="44" rowspan="5">
				  
                    <div align="center"><font color="#FFFFFF">K</font></div>
					</td>
                  <td width="43" height="17"onClick="chgTri('scoreDefense','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">D</font></div></td>
                  <td width="39"onClick="chgTri('scoreAilierOff','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')" witdth = "20"><div align="center"><font color="#FFFFFF">W</font></div></td>
                  <td width="37"onClick="chgTri('scoreMilieu','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">M</font></div></td>
                  <td width="36" onClick="chgTri('scoreAttaquant','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">F</font></div></td>
                  <td width="43" rowspan="5">
                   
                    <div align="center"><font color="#FFFFFF">Sel.</font></div>                    </td>
                  </tr>
              </table>
                
				  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr> 
                    <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                  </tr>
                </table>
                 
<?php
	$lst = 1;
			 
	foreach ($reqJoueurs as $lstJoueurs){
			
		$infTraining = getEntrainement($lstJoueurs["idJoueur"]);
			  
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
			


$val = array($lstJoueurs["scoreGardien"],$lstJoueurs["scoreDefense"],$lstJoueurs["scoreAilier"],$lstJoueurs["scoreAilierOff"],$lstJoueurs["scoreAilierVersMilieu"],$lstJoueurs["scoreMilieu"],$lstJoueurs["scoreMilieuOff"],$lstJoueurs["scoreAttaquant"]);
sort($val);
$valMax =  $val[7];
$val2 = $val[6];
			  
			  $class = "#";
			  $quinze = 60 * 60 * 24 * 15;
			  $trente = 60 * 60 * 24 * 30;
			 
			 
			 $date = explode("-",$lstJoueurs["dateDerniereModifJoueur"]);
			 
			 // Date de la dernier modif de ce joueur
			  $mkJoueur =  mktime(0,0,0,$date[1],$date[2],$date[0]); 
			  
			  // Date du jour
			 $mkDay = mktime(0,0,0,date('m'), date('d'),date('Y'));
			 $d1 =  $mkDay - $quinze;
			 $d2 =  $mkDay - $trente;
			 
			if($mkJoueur >  $d1) $class= "#"; 
			else if( $mkJoueur > $d2 && $mkJoueur < $d1 ) $class = "style3";	
			else if($mkJoueur < $d2) $class = "style4";
			 
			  ?>

				               
<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor = "<?=$bgcolor?>">  
                    <td width="200" > 
                      &nbsp;<a href ="javascript:fiche('<?=$lstJoueurs["idJoueur"]?>')" class='<?=$class?>'> 
                      <b>   <?=strtolower($lstJoueurs["nomJoueur"])?></b>
                   <?=strtolower($lstJoueurs["prenomJoueur"])?>
				   <?php if (isset($lstJoueurs["surnomJoueur"])) echo " (".$lstJoueurs["surnomJoueur"].")"; ?>
                      </a>
                      <div align="center"> </div></td>
                    <td width="1" bgcolor="#000000" ><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td ><img src="../images/spacer.gif" width="1" height="1">
                    <?=$infTraining["valeurEnCours"]?></td>
                    <td width="1" rowspan="6" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td width="25"><div align="center"> 
                        <?=$lstJoueurs["ageJoueur"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td width="20"> <div align="center"> 
                        <?=$lstJoueurs["idExperience_fk"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="26"> <div align="center"> 
                        <?=$lstJoueurs["idLeader_fk"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="24"> <div align="center"> 
                        <?=$lstJoueurs["idAggre_fk"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div>
                   </td>
                    <td width="25"> <div align="center"> 
                        <?=$lstJoueurs["idCaractere_fk"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30"> <div align="center"> 
                        <?=$lstJoueurs["idHonnetete_fk"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30"> <div align="center"> 
                        <?=substr($lstJoueurs["optionJoueur"],0,1)?>
                      </div></td>
                    <td width="2" rowspan="6" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td width="30" bgcolor="#CCCCCC" witdth = "20"> 
                      <div align="center"> 
                        <?=$lstJoueurs["idEndurance"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"> 
                        <img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30" height="17" witdth = "20" <?php if ($construction==1) echo "bgcolor = $constructionColor";?>> <div align="center"> 
                        <?=$lstJoueurs["idConstruction"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"> 
                        <img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="40" witdth = "20"<?php if ($ailier==1) echo "bgcolor = $ailierColor";?>> <div align="center"> 
                        <?=$lstJoueurs["idAilier"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1">
                        <img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30" witdth = "20"<?php if ($buteur==1) echo "bgcolor = $buteurColor";?>> <div align="center"> 
                        <?=$lstJoueurs["idButeur"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"> 
                        <img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30" witdth = "20"<?php if ($k==1) echo "bgcolor = $keeperColor";?>> <div align="center"> 
                        <?=$lstJoueurs["idGardien"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1">
                        <img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30" witdth = "20" <?php if ($passe==1) echo "bgcolor = $passeColor";?>> <div align="center"> 
                        <?=$lstJoueurs["idPasse"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"> 
                        <img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30" witdth = "20" <?php if ($defense==1) echo "bgcolor = $defenseColor";?>> <div align="center"> 
                        <?=$lstJoueurs["idDefense"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1">
                        <img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30" witdth = "20"> <div align="center"> 
                        <?=$lstJoueurs["idPA"]?>
                      </div></td>
                    <td width="2" rowspan="6" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td width="40" witdth = "20"> <div align="center"> 
                      <?php
					  if($k == 1)
					  {
					echo "<font color = #000099><b>";
					  }
					  else
					echo "<font color = gray>";


				   
				   
					echo $lstJoueurs["scoreGardien"];
					echo $ffont;

					  ?>
					  
					  </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div>
                      <img src="../images/spacer.gif" width="1" height="1"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="40" height="17" witdth = "20"> <div align="center"> 
                      <?php
					  if($d == 1)
					 {
					echo "<font color = #000099><b>";
					 }
					  else
					echo "<font color = gray>";
					
					
					echo $lstJoueurs["scoreDefense"];
					echo $ffont;

					  ?>
                      </div></td>

                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"> 
                      </div>
                      <div align="center"></div></td>
                    <td width="40" witdth = "20"> <div align="center"> 
						  
												                      <?php
					  
					  if($wingoff == 1)
					 {
					echo "<font color = #000099><b>";
					 }
					  else
					echo "<font color = gray>";
					 
					echo $lstJoueurs["scoreAilierOff"];
					echo $ffont;

					  ?>
                      </div>
					  
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div>
                      <img src="../images/spacer.gif" width="1" height="1"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="40" witdth = "20"> <div align="center"> 
											
																				                      <?php
					  if($m == 1)
					 {
					echo "<font color = #000099><b>";
					 }
					  else
					echo "<font color = gray>";
				   
					echo $lstJoueurs["scoreMilieu"];
					echo $ffont;

					  ?>     
                      </div></td>

                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div>
                      <img src="../images/spacer.gif" width="1" height="1"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="40" witdth = "20"> <div align="center"> 
						
																					
																				                      <?php
					  
				   
					  if($att == 1)
					 {
					echo "<font color = #000099><b>";
					 }
					  else
					echo "<font color = gray>";
					 
					echo $lstJoueurs["scoreAttaquant"];
					echo $ffont;

					  ?>     

                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div>
                      <img src="../images/spacer.gif" width="1" height="1"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30" witdth = "20"> <div align="center"> 
                       
                       <?php
					   $verifSelection = verifSelection($lstJoueurs["idJoueur"]);
					  if($verifSelection != ""){
					  ?>
					  
					  <?php
					  if($verifSelection == $sesUser["selection"]){
					  ?>
					  <a href = "../form.php?affPosition=<?=$affPosition?>&masque=<?=$masque?>&ordre=<?=$ordre?>&sens=<?=$sens?>&mode=supprAssigneSelection&idJoueur=<?=$lstJoueurs["idJoueur"]?>"><img src="../images/fr.gif" width="19" height="12" alt="<?=$verifSelection?>" border="0"></a>
					  <?php 
					  } else{
					  ?>
					    <img src="../images/fr.gif" width="19" height="12" alt="<?=$verifSelection?>">
					  <?php
					  }
					  }else{
					   ?> 
                        <input name="assigne[]" type="checkbox" id="assigne[]"  value="<?=$lstJoueurs["idJoueur"]?>"> 
						<?php
						}?>
                      </div></td>
                  </tr>
				 
				 
				 
				 
				
				 
				 
                </table>
    <?php
				}
				?>
 
              </td>
            </tr>           
          </table>
        </div></td>
    </tr>
  </table>
  <br>

  <table width="980" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="914"><div align="right">
          <input name="mode" type="hidden" id="mode" value="assigneJoueurSelection">
		  
		  <input name="ordre" type="hidden" id="mode" value="<?=$ordre?>">
          <input name="sens" type="hidden" id="mode" value="<?=$sens?>">
          <input name="masque" type="hidden" id="mode" value="<?=$masque?>">
          <input name="selectionFrance" type="hidden" id="mode" value="<?=$sesUser["selection"]?>">
          <input name="affPosition" type="hidden" id="masque" value="<?=$affPosition?>">
Assigner les joueurs coch&eacute;s &agrave; la selection
<?=$sesUser["selection"]?>: </div></td>
      <td width="66"> <div align="right">
          <input type="submit" name="Submit" value="Assigner">
        </div></td>
    </tr>
  </table>
  <br>
  <table width="400"  border="0" align="center">
    <tr>
      <td width="111">L&eacute;gende : </td>
      <td width="279">&nbsp;</td>
    </tr>
    <tr>
      <td><a href="#">racine jean-ren&eacute; </a></td>
      <td>Joueur mis &agrave; jour r&eacute;cemment </td>
    </tr>
    <tr>
      <td><span class="Style3">racine jean ren&eacute; </span></td>
      <td>Joueur mis &agrave; jour il y a + de 15 jours </td>
    </tr>
    <tr>
      <td><span class="Style4">racine jean-ren&eacute; </span></td>
      <td>Joueur mis &agrave; jour il y a + de 30 jours </td>
    </tr>
  </table>
</form>
</body>
<?php  deconnect(); ?>
