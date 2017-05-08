<?php
require("../includes/head.inc.php");
if(!$sesUser["idAdmin"])
	{
	header("location: index.php?ErrorMsg=Session Expiree");
	}

if(!isset($lang)) $lang = "FR";
if(!isset($graph)) $graph = "perfMatch";

require("../includes/langue.inc.php");

$sql = "select * from $tbl_joueurs, $tbl_position where idJoueur = $id and ht_posteAssigne  = idPosition";
$lstJoueur = construitListe($sql, $tbl_joueurs, $tbl_position);


switch($lstJoueur[0]["ht_posteAssigne"]){
case "1":
$selectGardien = "1";
break;

case "2":
$selectDefense = "1";
break;

case "3":
$selectAilier = "1";
break;

case "4":
$selectMilieu = "1";
break;

case "5":
$selectAttaquant = "1";
break;
}




$sql = "select * from $tbl_caracteristiques where  numCarac < 9 order by numCarac DESC";
$lstCarac = construitListe($sql,$tbl_caracteristiques);
// Info Club

$sql = "select * from $tbl_clubs where idClubHT = ".$lstJoueur[0]["teamid"];
$lstClubActuel = construitListe($sql,$tbl_clubs);


// Info match




//$sql =  'select * from '.$tbl_perf.', '.$tbl_caracteristiques.', '.$tbl_joueurs.' where idJoueur_fk = idJoueur and formePerf = idCarac and idJoueur_fk = '.$id.' and affPerf = 1 and postePerf != "BLS" ';
$sql =  'select * from '.$tbl_perf.', '.$tbl_caracteristiques.', '.$tbl_joueurs.' where id_Joueur = idHattrickJoueur and forme = numCarac and id_joueur = '.$lstJoueur[0]["idHattrickJoueur"].'   ';
$lstMatchsTotal = construitListe($sql,$tbl_caracteristiques,$tbl_joueurs,$tbl_perf);


$nbPage = round(count($lstMatchsTotal) / 10);
if(!isset($limMin)) $limMin = 0;
if(!isset($limMax)) $limMax = 10;
$sql .= " order by id_match DESC";
$sql .= " limit $limMin, $limMax";


$lstMatchs = construitListe($sql,$tbl_caracteristiques,$tbl_joueurs,$tbl_perf);

?><html>
<head>
<title>Fiche joueur</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
</head>
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

$idClubHT=$lstJoueur[0]['teamid'];
$idHT=$lstJoueur[0]['idHattrickJoueur'];
$htid = $idHT;
require("../menu/menuJoueur.php");

?>
<center>
<table width="85%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr> 
    <td height="15" bgcolor="#000000">
<div align="center"><font color="#FFFFFF"><strong>Graphiques</strong></font></div></td>
  </tr>
  <tr>
    <td valign="top">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
             <tr> 
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr> 
          <td width="50%" align="left">&nbsp; <font color="#000099"><b><?=$lstJoueur[0]["idHattrickJoueur"]?>&nbsp;-&nbsp;<?=$lstJoueur[0]["nomJoueur"]?> <?=$lstJoueur[0]["prenomJoueur"]?>&nbsp;-&nbsp;<?=$lstJoueur[0]["ageJoueur"]?>&nbsp;ans&nbsp;-&nbsp;<?=$lstJoueur[0]["intitulePosition"]?></b></font></td>
          <td width="20%" align="left"><b>Club Actuel : </b><?=$lstClubActuel[0]["nomClub"]?></td>
                  <td width="30%" align="left">&nbsp;</td>
        
          
        </tr>
      </table>
      <br>   
          
      <center>
        <img src="generate/<?=$graph?>.php?maj=<?=mktime()?>&id=<?=$id?>">
      </center>
      <br>   
    
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td > <form name="form1" method="post" action="">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>
                <div align="right">
                    Graphiques disponibles :
                      <select name="menu1" onChange="MM_jumpMenu('parent',this,0)">
                      <option value="?id=<?=$id?>&graph=perfMatch" <?php if($graph == "perfMatch") echo "selected";?>>Rapport Etoile / Forme</option>
                      <option value="?id=<?=$id?>&graph=ordreMatch" <?php if($graph == "ordreMatch") echo "selected";?>>Positions occup&eacute;es</option>
                    </select>
                </div></td>
            </tr>
          </table>
          </form>            
        </td>
        </tr>
      </table>      <br>
    <p align="center"><br>
    </p></td>

  </tr>
</table>
<div align="center"><a href="javascript:history.go(-1);">Retour</a></div>
<p>

    <table width="85%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><div align="left">Rappel Niveaux de forme : <br>
        <ul>
        <li>8 - tr&egrave;s bon
        <li>7 - honorable
        <li>6 - passable
        <li>5 - inad&eacute;quat
        <li>4 - faible
        <li>3 - m&eacute;diocre
        <li>2 - tr&egrave;s mauvais
        <li>1 - catastrophique
        </ul>
        </div></td>
        </tr>
    </table>


<p>
</center>            
</body>
</html>
