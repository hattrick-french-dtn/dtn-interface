<?php 
require_once("../_config/CstGlobals.php"); // fonctions d'admin
require_once("../fonctions/AccesBase.php"); // fonction de connexion a la base
require_once("../fonctions/AdminDtn.php"); // fonctions d'admin
require_once("../includes/head.inc.php");
$maBase = initBD();

require("../includes/serviceJoueur.php");
require("../includes/langue.inc.php");
require("../includes/serviceListesDiverses.php");
		
if(!$sesUser["idAdmin"])
{
	header("location: ../index.php?ErrorMsg=Session Expiree");
}
		
?>
<link href="../css/ht2.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<script language="JavaScript" src="../includes/javascript/ht_tools.js"></script>
<?php
$lstCarac = listCarac("ASC",23);
$lstPos = listAllPosition();

switch($sesUser["idNiveauAcces"]){
	case "1":
		require("../menu/menuAdmin.php");
		require("../menu/menuSuperviseurConsulter.php");
		break;
		
	case "2":
		require("../menu/menuSuperviseur.php");
		require("../menu/menuSuperviseurConsulter.php");
		break;


	case "3":
		require("../menu/menuDTN.php");
		require("../menu/menuDTNConsulter.php");
		break;

	case "4":
		require("../menu/menuCoach.php");
		require("../menu/menuCoachConsulter.php");
		break;

}
?>

<style type="text/css">
<!--
.Style1 {color: #FFFFFF}
.Style3 {color: #FF9933}
.Style4 {color: #FF0000}
-->
</style>
<script language="JavaScript">
  <!--

  function verifID()
  {
  	var newid=cleanIdHattrick(document.form1.id.value);
  	var oldid=document.form1.id.value;
    document.form1.id.value=newid;
    if(document.form1.id.value== "" ) 
    {
     alert("Veuillez entrer un id Numerique pour ce joueur  \n(les parentheses sont supprimees automatiquement) \nou une partie de son nom");
     document.form1.id.focus();
     return false;
    }
    if (isNaN(document.form1.id.value)){
    document.form1.id.value=cleanSpace(oldid);
    if (document.form1.id.value.length<3){
    	alert("au moins 3 caracteres svp");
    	return false;
     }
     document.form1.stylereq.value="nom";
    }
    return true;
    
  }
  
  function verifform()
  {
  
  	if (verifID() ){
  		return true;
  	}else{
  		return false;
  	}
  }
 // -->
 </script>
<title>Tester un joueur</title>
<body>
<center><?php 
if(isset($msg)) {?>
	<font color="red" size="+1"> <?=$msg?></font>
<?php } ?>
<form name="form1" method="post"  onSubmit="return verifform()" action="verifPlayer.php">  

  <table width="85%"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
    <td width=60%>
		<div align="center">ID hattrick du joueur &agrave; tester
		<br>ou une partie de son nom ou pr&eacute;nom
		<br>&nbsp;
      	<p>
        &nbsp;<input name="id" type="text" id="id">
        <input type="submit" name="Submit" value="Tester">
        <input type="hidden" name="action" value="verif">
        <input type="hidden" name="stylereq" value="id">
	</td>
	<td width=40%><br>
		&nbsp;<b> Exemples : </b><br>
		<ul>
			<li> (27839634)
			<li> 27839634
		</ul>
		&nbsp;ou 
		<ul>
			<li> sylvain
			<li> ylvain 
			<li> wahl
		</ul>
		<br>
			
				</td>
    </tr>
  </table>
</center>
<p>
<?php
if(isset($action) && $action == "verif"){

	if($stylereq == "id"){
		$sql = "SELECT idJoueur FROM ht_joueurs WHERE idHattrickJoueur = '".$id."' order by archiveJoueur ASC,nomJoueur";
		$lstJ = $maBase->select($sql);
		$nbjoueur=count($lstJ);

		if($nbjoueur == 0){
	?>	
			<font color="red" size="+1"> Joueur introuvable, recommencez. </font> 
	<?php
		} 
	}else {
		$sql = "SELECT idJoueur FROM ht_joueurs WHERE (nomJoueur like '%".$id."%') or (prenomJoueur like '%".$id."%') order by archiveJoueur ASC,nomJoueur ";
		//$sql.=" or (to_lower[nomJoueur] like '%".$id."%') or (to_lower[prenomJoueur] like '%".$id."%') ";
		$lstJ = $maBase->select($sql);
		$nbjoueur=count($lstJ);
		if($nbjoueur == 0){
	?>	
			<font color="red" size="+1"> Joueur introuvable, recommencez. </font> 
	<?php
		} 	
	}
	if($nbjoueur != 0){
			$huit = 60 * 60 * 24 * 8; //time_0
			  $quinze = 60 * 60 * 24 * 15; //time_1
			  $trente = 60 * 60 * 24 * 30; //time_2
			  $twomonths = 60 * 60 * 24 * 60; //time_3
			  $fourmonths = 60 * 60 * 24 * 120; //time_4
			  
			  // Date du jour
			 $mkday = mktime(0,0,0,date('m'), date('d'),date('Y'));
		
		?>
		
<TABLE BORDER="0" CELLPADDING="2" CELLSPACING="0" WIDTH="85%">
		<?php
			$j=0;
			while ($j<$nbjoueur){
				$infJ=getJoueur($lstJ[$j]["idJoueur"]);
	
				
				$sqlscout="select loginAdmin FROM ht_joueurs, ht_admin  WHERE idHattrickJoueur = '".$infJ["idHattrickJoueur"]."' AND dtnSuiviJoueur_fk=idAdmin ";
				$scout=$maBase->select($sqlscout);
				$dtnDuJoueur="<i>[personne &agrave; d&eacute;finir]</i>";
				if (count($scout)>0){
					$dtnDuJoueur=$scout[0]["loginAdmin"];
				}

	 	$date = explode("-",$infJ["dateDerniereModifJoueur"]);
			 $mkJoueur =  mktime(0,0,0,$date[1],$date[2],$date[0]); 
			 $datesaisie = explode("-",$infJ["dateSaisieJoueur"]);
			 $mkSaisieJoueur= mktime(0,0,0,$datesaisie[1],$datesaisie[2],$datesaisie[0]);
			 if ($mkSaisieJoueur>$mkJoueur){
			 	$datemaj=$mkSaisieJoueur;
			 }else{
			 	$datemaj=$mkJoueur;
			 }
			
			 $img_nb=0;
			 if ($datemaj >$mkday -$huit){
			 	$img_nb=0;
			 	$strtiming="moins de 8 jours";	
			 }else if ($datemaj >$mkday -$quinze){
			 	$img_nb=1;
			 	$strtiming="moins de 15 jours";
			 }else if ($datemaj >$mkday -$trente){
			 	$img_nb=2;
			 	$strtiming="moins de 30 jours";
			 	
			 }else if ($datemaj >$mkday -$twomonths){
			 	$img_nb=3;
			 	$strtiming="moins de 2 mois";
			 	
			 }else if ($datemaj >$mkday -$fourmonths){
			 	$img_nb=4;
			 	$strtiming="moins de 4 mois";
			 
			 }else{
			 		$img_nb=5;
			 	$strtiming="plus que 4 mois";
			 }
			 
			 // Date de la dernier modif de ce joueur
			 $zealt=" Date dtn : ".$infJ["dateDerniereModifJoueur"].
					"<br> Date proprio : ".$infJ["dateSaisieJoueur"].
					"<br> [ Mis &agrave; jour il y a  ".round(($mkday - $datemaj)/(60*60*24) )." jours ]";


?>						<TR>
				<TD ><BR>
					<font color="#CC2233"><?=$j+1?>.</font> <A HREF="../joueurs/fiche.php?id=<?=$infJ["idJoueur"]?>"><?=$infJ["prenomJoueur"]?> <?=$infJ["nomJoueur"]?> </A>&nbsp; 
					  <?php if($infJ["optionJoueur"]) 
					  		echo "<font color=\"#CC22DD\">[<i>".$option[$infJ["optionJoueur"]]["FR"]."</i>]</font>"; 
					  		
					 ?>&nbsp;|&nbsp;Secteur : 
					 <?php if ($infJ["ht_posteAssigne"]!=0){?>
					  <?=$lstPos[$infJ["ht_posteAssigne"]-1]["descriptifPosition"]?>
					  <?php }else{ ?>
					  	aucun
					  	<?php } ?>
			<?php if($infJ["archiveJoueur"] == 1){?>&nbsp;|&nbsp;  
					[<Font color="#DD3322" size=2><strong>Ce joueur est archiv&eacute;&nbsp;</strong></font></span>]
			<?php } ?>
				</TD>
			</TR>
			
			<TR>
				<TD VALIGN="top">

					<B>Suivi par </B> <?=$dtnDuJoueur?> &nbsp;|&nbsp;<B>Age:&nbsp; </B><?=$infJ["ageJoueur"]?> ans &nbsp;|&nbsp; <b>XP</b> : <?=$lstCarac[$infJ["idExperience_fk"]]["intituleCaracFR"]?><BR>
					<B><b>id</b> : ( <?=$infJ["idHattrickJoueur"]?> )&nbsp;|&nbsp;<B>
					<img src="../images/time_<?=$img_nb?>.gif" onmouseover="return escape('<?=$zealt?>')" >
					<BR>
				</TD>
			</TR>
					
<?php
				$j=$j+1;
			
				
				
			}		
		
	}
}else{
	?>
	        <input type="button" name="Submit2" value="Back" onClick="javascript:history.go(-1)"></div>
	
	<?php
}
?>
				</TD>

			</TR>
</table>

</p>
</form>
 <table width="450"  border="0" align="center" cellspacing=0 >
    <tr>
      <td colspan=5 width="450" ><center>L&eacute;gende : </center></td>
      
    </tr>
    <tr>
      <td><img src="../images/time_0.gif"></td>
      <td>Joueur mis &agrave; jour r&eacute;cemment </td>
      <td width="1" bgcolor="#000000"></td>
      <td>&nbsp;<img src="../images/time_3.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 30 jours </td>
    </tr>
    <tr>
      <td><img src="../images/time_1.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 8 jours </td>
      <td width="1" bgcolor="#000000"></td>
      <td>&nbsp;<img src="../images/time_4.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 60 jours </td>
    </tr>
    <tr>
      <td><img src="../images/time_2.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 15 jours </td>
      <td width="1" bgcolor="#000000"></td>
      <td>&nbsp;<img src="../images/time_5.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 120 jours </td>
    </tr>
  </table>

<script language="JavaScript" type="text/javascript" src="../includes/javascript/tooltips.js"></script>
</body>
</html>
