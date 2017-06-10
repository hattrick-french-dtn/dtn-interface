<?php
require_once("../includes/head.inc.php");






if(!isset($lang)) $lang = "FR";


require("../includes/langue.inc.php");




$sql = "select * from $tbl_joueurs left join $tbl_admin on dtnSuiviJoueur_fk = idAdmin where idJoueur = $id ";
$lstJoueur = construitListe($sql, $tbl_joueurs, $tbl_admin);
// Info Club

$sql = "select * from ht_clubs where idClubHT = ".$lstJoueur[0]["teamid"];
$lstClubActuel = construitListe($sql,$tbl_clubs);


// Info personnalité


$sql = "select * from $tbl_caractere where  idCaractere=".$lstJoueur[0]["idCaractere_fk"] ;
$lstCaractere = construitListe($sql,$tbl_caractere);


$sql = "select * from $tbl_hon where  idHonnetete=".$lstJoueur[0]["idHonnetete_fk"] ;
$lstHonnetete = construitListe($sql,$tbl_hon);


$sql = "select * from $tbl_aggres where idAggres=".$lstJoueur[0]["idAggre_fk"] ;
$lstAggres = construitListe($sql,$tbl_aggres);


$sql = "select * from $tbl_caracteristiques where idCarac=".$lstJoueur[0]["idExperience_fk"] ;
$lstExp = construitListe($sql,$tbl_caracteristiques);


$sql = "select * from $tbl_leader where  idLeader=".$lstJoueur[0]["idLeader_fk"] ;
$lstLeader = construitListe($sql,$tbl_leader);




$lstCaracJoueur = array($endurance["$lang"]=>$lstJoueur[0]["idEndurance"],
						$gardien["$lang"]=>$lstJoueur[0]["idGardien"],
						$construction["$lang"]=>$lstJoueur[0]["idConstruction"],
						$passe["$lang"]=>$lstJoueur[0]["idPasse"],
						$ailier["$lang"]=>$lstJoueur[0]["idAilier"],
						$defense["$lang"]=>$lstJoueur[0]["idDefense"],
						$buteur["$lang"]=>$lstJoueur[0]["idButeur"],
						$pa["$lang"]=>$lstJoueur[0]["idPA"]
						);
						
$val = array($lstJoueur[0]["scoreGardien"],$lstJoueur[0]["scoreDefense"],$lstJoueur[0]["scoreAilierDef"],$lstJoueur[0]["scoreAilierOff"],$lstJoueur[0]["scoreWtm"],$lstJoueur[0]["scoreMilieu"],$lstJoueur[0]["scoreMilieuOff"],$lstJoueur[0]["scoreAttaquant"]);
sort($val);
$valMax =  round($val[7],2);
$val2 = round($val[6],2);
?><html>
<head>
<title>Fiche joueur</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../css/ht.css" rel="stylesheet" type="text/css">
</head>
<script language="JavaScript" type="text/JavaScript">
<!--
<!--


//-->
	
window.resizeTo(550,400);


 windowWidth=550; // largeur du popup
 windowHeight=500; // hauteur du popup
 window.moveTo((screen.width/2)-(windowWidth/2+10),(screen.height/2)-(windowHeight/2+20));//-->
</script>


<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="window.focus()";>
<table width="530" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">

  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td bgcolor="#000000"> <div align="center"><font color="#FFFFFF"> 
              <?=$lstJoueur[0]["nomJoueur"]?>
              <?=$lstJoueur[0]["prenomJoueur"]?>
              - 
              <?=$lstJoueur[0]["ageJoueur"]?>
              ans</font></div>
            <div align="right"> &nbsp;</div></td>
        </tr>
        <tr> 
          <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        </tr>
        <tr> 
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td>&nbsp;Information :</td>
        </tr>
        <tr> 
          <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        </tr>
        <tr> 
          <td> 
        </td>
        </tr>
        <tr> 
          <td><table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr> 
                <td><strong>Club Actuel :</strong></td>
                <td colspan="3"> 
                  <?=$lstClubActuel[0]["nomClub"]?>
                </td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
                <td colspan="3">&nbsp;</td>
              </tr>
              <tr> 
                <td colspan="4">Un 
                  <?=$lstCaractere[0]["intituleCaractereFR"]?>
                  qui est 
                  <?=$lstAggres[0]["intituleAggresFR"]?>
                  et 

                  <?=$lstHonnetete[0]["intituleHonneteteFR"]?>
                  .<br>
                  Il a une 
                  <?=$lstExp[0]["IntituleCaracFR"];?>
                  exp&eacute;rience et un 
                  <?=$lstLeader[0]["intituleLeaderFR"]?>
                  temp&eacute;rament de chef</td>
              </tr>
              <tr> 
                <td colspan="4"> 
                  <?php if($lstJoueur[0]["optionJoueur"]) echo "<i>Spécialité : ".$lstJoueur[0]["optionJoueur"]."</i>"?>
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


			
$sql =  "select * from $tbl_caracteristiques where numCarac = ".$val;
$intCarac = construitListe($sql,$tbl_caracteristiques);
		
			
			echo "<td width = 25%><b>".$int."</B></td><td width = 25%>&nbsp;".$intCarac[0]["intituleCaracFR"]."</td>";	




		  if($i % 2 == 0)  print("</tr><tr>");

$i++;
}


?>
                    </tr>
                  </table></td>
              </tr>
              <tr> 
                <td colspan="4">&nbsp;</td>
              </tr>
              <tr> 
                <td colspan="4">Valeur par poste</td>
              </tr>
              <tr bgcolor="#000000"> 
                <td colspan="4"><img src="../images/spacer.gif" width="1" height="1"></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td><br> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
              </tr>
              <tr> 
                <td width = "65" bgcolor="#000000"> <div align="center"><font color="#FFFFFF"><strong>K</strong></font></div></td>
                <td width = "1" bgcolor="#000000"><div align="center"><font color="#FFFFFF"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></font></div></td>
                <td width = "65" bgcolor="#000000"> <div align="center"><font color="#FFFFFF"><strong>D</strong></font></div></td>
                <td width = "1" bgcolor="#000000"><div align="center"><font color="#FFFFFF"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></font></div></td>
                <td width = "65" bgcolor="#000000"> <div align="center"><font color="#FFFFFF"><strong>W</strong></font></div></td>
                <td width = "1" bgcolor="#000000"><div align="center"><font color="#FFFFFF"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></font></div></td>
                <td width = "65" bgcolor="#000000"> <div align="center"><font color="#FFFFFF"><strong>Woff</strong></font></div></td>
                <td width = "1" bgcolor="#000000"><div align="center"><font color="#FFFFFF"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></font></div></td>
                <td width = "65" bgcolor="#000000"> <div align="center"><font color="#FFFFFF"><strong>Wtm</strong></font></div></td>
                <td width = "1" bgcolor="#000000"><div align="center"><font color="#FFFFFF"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></font></div></td>
                <td width = "65" bgcolor="#000000"> <div align="center"><font color="#FFFFFF"><strong>M</strong></font></div></td>
                <td width = "1" bgcolor="#000000"><div align="center"><font color="#FFFFFF"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></font></div></td>
                <td width = "65" bgcolor="#000000"> <div align="center"><font color="#FFFFFF"><strong>Moff</strong></font></div></td>
                <td width = "1" bgcolor="#000000"><div align="center"><font color="#FFFFFF"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></font></div></td>
                <td width = "65" bgcolor="#000000"> <div align="center"><font color="#FFFFFF"><strong>F</strong></font></div></td>
              </tr>
              <tr> 
                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
              </tr>
              <tr> 
                <td width = "65"><div align="center"> 
                    <?php
				   if($valMax == $lstJoueur[0]["scoreGardien"]){; $font = "<b><font color = red>"; $ffont = "</font></b>"; } 
				   else if($val2 == $lstJoueur[0]["scoreGardien"]){; $font = "<b><font color = green>"; $ffont = "</font></b>"; } else {$font = ""; $ffont = ""; }
				   
				   
					echo $font;
					echo $lstJoueur[0]["scoreGardien"];
					echo $ffont;
					?>
                  </div></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td width = "65"><div align="center"> 
                    <?php
				   if($valMax == $lstJoueur[0]["scoreDefense"]){; $font = "<b><font color = red>"; $ffont = "</font></b>"; }
				  else if($val2 == $lstJoueur[0]["scoreDefense"]){; $font = "<b><font color = green>"; $ffont = "</font></b>"; } else {$font = ""; $ffont = ""; }
					
					echo $font;
					echo $lstJoueur[0]["scoreDefense"];
					echo $ffont;


					?>
                  </div></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td width = "65"><div align="center"> 
                    <?php
				   if($valMax == $lstJoueur[0]["scoreAilierDef"]){; $font = "<b><font color = red>"; $ffont = "</font></b>"; } 
				   else if($val2 ==  $lstJoueur[0]["scoreAilierDef"]){; $font = "<b><font color = green>"; $ffont = "</font></b>"; } else {$font = ""; $ffont = ""; }
					
					echo $font;
					echo  $lstJoueur[0]["scoreAilierDef"];
					echo $ffont;
					?>
                  </div></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td width = "65"><div align="center"> 
                    <?php
				   if($valMax == $lstJoueur[0]["scoreAilierOff"]){; $font = "<b><font color = red>"; $ffont = "</font></b>"; } 
				   else if($val2 == $lstJoueur[0]["scoreAilierOff"]){; $font = "<b><font color = green>"; $ffont = "</font></b>"; } else {$font = ""; $ffont = ""; }
					
					echo $font;
					echo $lstJoueur[0]["scoreAilierOff"];
					echo $ffont;
					?>
                  </div></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td width = "65"><div align="center"> 
                    <?php
				   if($valMax == $lstJoueur[0]["scoreWtm"]){; $font = "<b><font color = red>"; $ffont = "</font></b>"; } 
				   else if($val2 == $lstJoueur[0]["scoreWtm"]){; $font = "<b><font color = green>"; $ffont = "</font></b>"; } else {$font = ""; $ffont = ""; }
					
					echo $font;
					echo $lstJoueur[0]["scoreWtm"];
					echo $ffont;
					?>
                  </div></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td width = "65"><div align="center"> 
                    <?php
				   if($valMax == $lstJoueur[0]["scoreMilieu"]){; $font = "<b><font color = red>"; $ffont = "</font></b>"; } 
				   else if($val2 == $lstJoueur[0]["scoreMilieu"]){; $font = "<b><font color = green>"; $ffont = "</font></b>"; } else {$font = ""; $ffont = ""; }
					
					echo $font;
					echo $lstJoueur[0]["scoreMilieu"];
					echo $ffont;
					?>
                  </div></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td width = "65"><div align="center"> 
                    <?php
				   if($valMax == $lstJoueur[0]["scoreMilieuOff"]){; $font = "<b><font color = red>"; $ffont = "</font></b>"; } 
				   else if($val2 == $lstJoueur[0]["scoreMilieuOff"]){; $font = "<b><font color = green>"; $ffont = "</font></b>"; } else {$font = ""; $ffont = ""; }
					
					echo $font;
					echo $lstJoueur[0]["scoreMilieuOff"];
					echo $ffont;
					?>
                  </div></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td width = "65"><div align="center"> 
                    <?php
				   if($valMax == $lstJoueur[0]["scoreAttaquant"]){; $font = "<b><font color = red>"; $ffont = "</font></b>"; } 
				   else if($val2 == $lstJoueur[0]["scoreAttaquant"]){; $font = "<b><font color = green>"; $ffont = "</font></b>"; } else {$font = ""; $ffont = ""; }
					
					echo $font;
					echo $lstJoueur[0]["scoreAttaquant"];
					echo $ffont;
					?>
                  </div></td>
              </tr>
              <tr> 
                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>

                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width = "1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
              </tr>
            </table>
            <p align="center"><a href="javascript:window.close();">Fermer</a><br>
              <br>
            </p></td>
        </tr>
      </table></td>
  </tr>
</table>


</body>
</html>
