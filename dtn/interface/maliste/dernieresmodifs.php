<?php 
error_reporting(E_ALL);
require_once "../_config/CstGlobals.php"; // fonctions d'admin
require_once "../fonctions/AccesBase.php"; // fonction de connexion � la base
require_once "../fonctions/AdminDtn.php"; // fonctions d'admin
require_once("../includes/head.inc.php");
$maBase = initBD();
if(!$sesUser["idAdmin"])
{
	header("location: ../index.php?ErrorMsg=Session Expiree");
}


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
 require ("../menu/menuMaListe.php"); 


$sql = "select count(1) from ( $tbl_histomodif )" .
		" LEFT JOIN ht_admin hta ON hta.idAdmin = idAdmin_fk " .
		" LEFT JOIN ht_joueurs htj ON idjoueur_fk = htj.idJoueur " .
		"where  dtnSuiviJoueur_fk ='".$sesUser["idAdmin"]."'  ";

$resp = $maBase->select($sql);
$numMaxHisto=$resp[0][0];

?>
<script language="JavaScript" type="text/JavaScript">
<!--
function ficheDTN(id,url){
document.location='<?=$url?>/joueurs/ficheDTN.php?url='+url+'&id='+id
}

//-->
</script>
<style type="text/css">
<!--
.Style1 {color: #FFFFFF}
.Style3 {color: #FF9933}
.Style4 {color: #FF0000}
-->
</style>
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<br>
<center>
<?php if (isset($msg) && $msg!=""){
?>
<table  bordercolor="#00FF00" class="bgtool">
<tr><td class="special"><b><?=$msg?></b></td></tr>
</table>
<?php
}?>
<?php
      
	$sqlClubs = "select teamid from ht_joueurs where dtnSuiviJoueur_fk ='".$sesUser["idAdmin"]."' ";
	$listeClubs = $maBase->select($sqlClubs);
	$j=0;
	$listeTeamid ="";
		 
	while ($j<count($listeClubs)){
			 
		if ($j+1>=count($listeClubs) ){
			$listeTeamid = $listeTeamid ."'".$listeClubs[$j][0]."'";
		}else{
		 	$listeTeamid = $listeTeamid ."'".$listeClubs[$j][0]."',";
		}
			$j=$j+1;
		}
		   
		if ($j!=0){
	?>

<p>
Derni&egrave;res infos club :
    <table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#000000">
              <tr bgcolor="#EEEEEE"> 
                <td width=20%> <div align="left"><b>&nbsp;NomClub</b></div></td>
                <td width=10%> <div align="center"><b>Date</b></div></td>
                <td width=60%><div align="center"><b>Info club </b></div></td>
              </tr>
               <?php
               $j=0;
		   $sqlClubsHisto =  "select 
                              DATE_FORMAT(ht_clubs_histo.date_histo,'%d/%m/%Y %H:%i:%s') AS date_histo,
                              Commentaire,
                              nomClub,
                              ht_clubs_histo.idClubHT 
                          from ht_clubs_histo,ht_clubs  
                          where ht_clubs_histo.idClubHT in (".$listeTeamid.") 
                          and ht_clubs_histo.idClubHT=ht_clubs.idClubHT  
                          order by ht_clubs_histo.date_histo desc 
                          LIMIT 0,15";

		   $listeHistoMsg = $maBase->select($sqlClubsHisto);
		   while ($j<count($listeHistoMsg)){

			$lHisto = $listeHistoMsg[$j];
			$j=$j+1;
		   
		   
		   ?>
		      <tr bgcolor="#FFFFFF"> 
                <td >
                  <div align="left">&nbsp;<a href ="<?=$url?>/clubs/fiche_club.php?idClubHT=<?=$lHisto["idClubHT"]?>"><?=$lHisto["nomClub"]?></a></div></td>
                <td > 
                  <div align="center"><?=$lHisto["date_histo"]?></div></td>
                <td > <div align="left">&nbsp;<?=$lHisto["Commentaire"]?></div></td>
              </tr>
 <?php } ?>
            </table>
<?php } ?>		
<p>		
Derni&egrave;res infos Joueurs :		
<table bgcolor="#000000" cellpadding="0" cellspacing="1" width=95%> 	
<tr><td>
     <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
        <tr bgcolor=#DDD7FF> 
                <td width="20%"><div align="left"><b>&nbsp;Joueur</b></div></td>
                <td width="10%"> <div align="center"><b>Date</b></div></td>
                <td width="50%"><div align="center"><b>Libell&eacute;</b></div></td>
                <td width="10%"><div align="center"><b>Auteur</b></div></td>
        </tr>

<?php

if(!isset($nbParPage)) $nbParPage = 25;
if(!isset($numEnr)) $numEnr = 0;


$next = $numEnr+$nbParPage;
$prev = $numEnr-$nbParPage;
   $sql = "select dateHisto,heureHisto,intituleHisto,loginAdmin,nomJoueur,prenomJoueur,idJoueur from ($tbl_histomodif, $tbl_joueurs) " .
   " LEFT JOIN ht_admin hta ON hta.idAdmin = idAdmin_fk " .
   " where  idJoueur_fk = idJoueur ".
   " and dtnSuiviJoueur_fk ='".$sesUser["idAdmin"]."'  order by dateHisto desc, heureHisto desc ";
   $sql .= " limit $numEnr, $nbParPage";
   $listeHistoMsg = $maBase->select($sql);
	$j=0;
	
	$bgcol="#F4F4FF";
		while ($j<count($listeHistoMsg)){
			   $l=$listeHistoMsg[$j];
$j=$j+1;
if ($bgcol=="#F4F4FF"){
	$bgcol="#FEFEFF";
}else{
	$bgcol="#F4F4FF";
}
?>
                <tr bgcolor="<?=$bgcol?>">
                <td> &nbsp;<a href ="<?=$url?>/joueurs/ficheDTN.php?id=<?=$l["idJoueur"]?>" ><b><?=strtolower($l["nomJoueur"])?>
                      <?=strtolower($l["prenomJoueur"])?></b>
                      </a></td>
                <td> <div align="center"><?=dateToHTML($l["dateHisto"])?>&nbsp;<?=$l["heureHisto"]?></div></td>
                <td > <div align="left">&nbsp;<?=$l["intituleHisto"]?></div></td>
                <td ><div align="center"><?=$l["loginAdmin"]?></div></td>
              </tr>
<?php
	}
?>
</table>

</td></tr></table>           	
  <br>
  
      <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="26%" height="18">
          
		  <?php
		  
if($numEnr >= $nbParPage){
echo "<a href = '?url=$url&numEnr=$prev'>Pr&eacute;c&eacute;dent</A>";
}
		  ?>

		  
		  </td>
          <td width="36%">
</td>
          <td width="38%"><div align="right"><?php
          
if($numEnr +$nbParPage < $numMaxHisto){
echo "<a href = '?url=$url&numEnr=$next'>Suivant</A>";
}
?> </div></td>
        </tr>
      </table>


</center></body>
</html>
