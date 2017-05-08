<?php 
require("../includes/head.inc.php");
require("../includes/serviceJoueur.php");
require("../includes/serviceDTN.php");


if(!$sesUser["idAdmin"])
	{
	header("location: index.php?ErrorMsg=Session Expir�");
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
//OPB
//$infAdmin = getDTN($sesUser["idAdmin"]);


		$lstJoueurs = listJoueurSelection($infAdmin["selection"]);




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
		require("../menu/menuCoachSubmit.php");
		break;
		
		default;
		break;


}










?><title>Superviseur</title>
<script language="JavaScript" type="text/JavaScript">
<!--


//-->




function init()
{
var scrollPos = "<?=$scrollPos?>";
document.body.scrollTop = scrollPos;


}//-->
</script>
<link href="../css/ht2.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
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
  <center>
    <br>
    <b><span class="titre">Liste des joueurs de l'&eacute;quipe de France
    <?=$sesUser["selection"]?>
  


  


    <br>
    </span></b>    
  </center>
  <table width="980" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr> 
      <td height="20" ><div align="center"> 
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td width="28%" height="21"> <div align="center"></div></td>
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
                  <td width="29" witdth = "20" onClick="chgTri('idDefense','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">Def</font></div></td>
                  <td width="29" witdth = "20" onClick="chgTri('idPA','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')">
                    <div align="center"><font color="#FFFFFF">Set</font></div></td>
                  <td width="44" witdth = "20" onClick="chgTri('scoreGardien','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')">
				  
                    <div align="center"><font color="#FFFFFF">K</font></div>
					</td>
                  <td width="43" height="17"onClick="chgTri('scoreDefense','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">D</font></div></td>
                  <td width="39"onClick="chgTri('scoreAilierOff','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')" witdth = "20"><div align="center"><font color="#FFFFFF">W</font></div></td>
                  <td width="37"onClick="chgTri('scoreMilieu','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">M</font></div></td>
                  <td width="36" onClick="chgTri('scoreAttaquant','<?=$sens?>','<?=$masque?>','<?=$affPosition?>')" witdth = "20">
                    <div align="center"><font color="#FFFFFF">F</font></div></td>
                  <td width="43">
                   
                    <div align="center"><font color="#FFFFFF">Exit</font></div>                    </td>
                  </tr>
              </table>
                
				  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr> 
                    <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                  </tr>
                </table>
                 
				             <?php
				$lst = 1;


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
			  $quinze = 60 * 60 * 24 * 15;
			  $trente = 60 * 60 * 24 * 30;
			 
			 
			 $date = explode("-",$l["dateDerniereModifJoueur"]);
			 
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
                      &nbsp;<a href ="<?=$url?>/joueurs/fiche.php?id=<?=$l["idJoueur"]?>" class=<?=$class?>><span class=<?=$class?>>  <b><?=strtolower($l["nomJoueur"])?></b>
                   <?=strtolower($l["prenomJoueur"])?></span>
                      </a>
                      <div align="center"> </div></td>
                    <td width="1" bgcolor="#000000" ><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td ><img src="../images/spacer.gif" width="1" height="1">
                    <?=$infTraining["valeurEnCours"]?></td>
                    <td width="1" rowspan="6" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td width="25"><div align="center"> 
                        <?=$l["ageJoueur"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td width="20"> <div align="center"> 
                        <?=$l["idExperience_fk"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="26"> <div align="center"> 
                        <?=$l["idLeader_fk"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="24"> <div align="center"> 
                        <?=$l["idAggre_fk"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div>
                   </td>
                    <td width="25"> <div align="center"> 
                        <?=$l["idCaractere_fk"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30"> <div align="center"> 
                        <?=$l["idHonnetete_fk"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30"> <div align="center"> 
                        <?=$specabbrevs[$l["optionJoueur"]]?>
                      </div></td>
                    <td width="2" rowspan="6" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td width="30" bgcolor="#CCCCCC" witdth = "20"> 
                      <div align="center"> 
                        <?=$l["idEndurance"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"> 
                        <img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30" height="17" witdth = "20" <?php if ($construction==1) echo "bgcolor = $constructionColor";?>> <div align="center"> 
                        <?=$l["idConstruction"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"> 
                        <img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="40" witdth = "20"<?php if ($ailier==1) echo "bgcolor = $ailierColor";?>> <div align="center"> 
                        <?=$l["idAilier"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1">
                        <img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30" witdth = "20"<?php if ($buteur==1) echo "bgcolor = $buteurColor";?>> <div align="center"> 
                        <?=$l["idButeur"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"> 
                        <img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30" witdth = "20"<?php if ($k==1) echo "bgcolor = $keeperColor";?>> <div align="center"> 
                        <?=$l["idGardien"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1">
                        <img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30" witdth = "20" <?php if ($passe==1) echo "bgcolor = $passeColor";?>> <div align="center"> 
                        <?=$l["idPasse"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"> 
                        <img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30" witdth = "20" <?php if ($defense==1) echo "bgcolor = $defenseColor";?>> <div align="center"> 
                        <?=$l["idDefense"]?>
                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1">
                        <img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30" witdth = "20"> <div align="center"> 
                        <?=$l["idPA"]?>
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




				   
				   
					echo $l["scoreGardien"];
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
					
					
					echo $l["scoreDefense"];
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
					 
					echo $l["scoreAilierOff"];
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
				   
					echo $l["scoreMilieu"];
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
					 
					echo $l["scoreAttaquant"];
					echo $ffont;


					  ?>     


                      </div></td>
                    <td width="1" rowspan="6" bgcolor="#000000"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div>
                      <img src="../images/spacer.gif" width="1" height="1"> <div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                    <td width="30" witdth = "20"> <div align="center"> 
                       <a href = "javascript:sortirJoueur('<?=$l["idJoueur"]?>','<?=$l["nomJoueur"]?>')"> X </a>
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
  <?php
  if($affPosition == "" || $affPosition == 0){
  ?>
  <?php }?>
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
