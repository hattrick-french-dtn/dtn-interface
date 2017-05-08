<?php
require("../includes/head.inc.php");
require("../CHPP/config.php");
require("../includes/serviceJoueur.php");




if(!$sesUser["idAdmin"])
	{
	header("location: index.php?ErrorMsg=Session Expir�");
	}


if(!isset($lang)) $lang = "FR";
if(!isset($fonction)) $fonction = "insert";
require("../includes/langue.inc.php");



$sql = "select * from $tbl_joueurs, $tbl_position where idJoueur = $id and ht_posteAssigne  = idPosition";
$lstJoueur = construitListe($sql, $tbl_joueurs, $tbl_position);

$infJ=getJoueur($id);

switch($sesUser["idNiveauAcces"]){
		
		case "2":
			if ($sesUser["idPosition_fk"]!= $infJ["ht_posteAssigne"]
				&& $infJ["ht_posteAssigne"]!=0 
			){
				print("<html><body><center>Ce joueur est associ&eacute; &agrave; un autre secteur de jeu.</center></body></html>");
				return;
			}
		break;


		case "3":
			if ($sesUser["idAdmin"]!= $infJ["dtnSuiviJoueur_fk"]){
				print("<html><body><center>Ce joueur est associ&eacute; &agrave; un autre DTN.</center></body></html>");
				return;
			 }
		break;
		
		default;
		break;

}



$sql = "select * from $tbl_caracteristiques where  numCarac < 9 order by numCarac DESC";
$lstCarac = construitListe($sql,$tbl_caracteristiques);


// Info Club
$sql = "select * from ht_clubs where idClubHT = ".$lstJoueur[0]["teamid"];
$lstClubActuel = construitListe($sql,$tbl_clubs);




// Info match
if (!$id_match) {
	$zeMatch=null;
	//pas de match
}else{
	$sql =  "select * from $tbl_perf where id_joueur=".$lstJoueur[0]["idHattrickJoueur"]." and id_match=$id_match ";
	$zeMatch=construitListe($sql,$tbl_perf);
}


?><html>
<head>
<title>Match du joueur</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<script language="JavaScript" src="../includes/javascript/ht_tools.js"></script>
<script language="JavaScript">
  <!--
  function verifdate()
  {
   if(isDate(document.form1.date_match.value))
    {
     return true;
    }else{
     alert("Veuillez utiliser le format Annee-mois-jour pour la date! \n exemple : 2005-11-23");
     document.form1.date_match.focus();
     return false;
    }


  }
  
  function verifid()
  {
  	var newidmatch=cleanSpace(document.form1.id_match.value);
  	document.form1.id_match.value=newidmatch;
    if(document.form1.id_match.value== "" ||isNaN(document.form1.id_match.value))
    {
     alert("Veuillez entrer un id de match hattrick \n Celui-ci est disponible sur la feuille de match\n["+document.form1.id_match.value+"] n'est pas un nombre");
     document.form1.id_match.focus();
     return false;
    }else{
     return true;
    }
  }
  function verifstar()
  {
  	var newplayerstar=cleanSpace(document.form1.etoile.value);
  	newplayerstar=cleanStarForm(newplayerstar);
  	document.form1.etoile.value=newplayerstar;
    if(document.form1.etoile.value== ""||isNaN(document.form1.etoile.value) )
    {
     alert("Veuillez entrer le nombre d'etoiles pour ce match  \n(au moins 0)\n["+newplayerstar+"] n'est pas un nombre ");
     document.form1.etoile.focus();
     return false;
    }else{
     return true;
    }
  }
  function verifTSI()
  {
  	var newtsi=cleanSpace(document.form1.tsi.value);
  	document.form1.tsi.value=newtsi;
    if(document.form1.tsi.value== "" ||isNaN(document.form1.tsi.value)) 
    {
     alert("Veuillez entrer un TSI Numerique pour ce joueur pour ce match  \n(au moins 0)");
     document.form1.tsi.focus();
     return false;
    }else{
     return true;
    }
  }
  
  function verifform()
  {
  
  	if (verifstar() &&verifTSI() && verifdate() && verifid()){
  		return true;
  	}else{
  		return false;
  	}
  }
 // -->
 </script>
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
$idHT=$infJ['idHattrickJoueur'];
$idClubHT=$lstJoueur[0]['teamid'];
require("../menu/menuJoueur.php");

$zeRole=0;
$zePosition=1;
$zeEtoile=0;
$zeForme=6;
$zeTSI=0;


if ($fonction == "modification"){
	if ($zeMatch!=null && $zeMatch[0]!=null){
		$zeMatch_id=$zeMatch[0]["id_match"];
		$zeDate_Match=substr($zeMatch[0]["date_match"],0,10);
		$zeRole=$zeMatch[0]["id_role"];
		$zeBehaviour=$zeMatch[0]["id_behaviour"];
		$zeEtoile=$zeMatch[0]["etoile"];
		$zeForme=$zeMatch[0]["forme"];
		$zeTSI=$zeMatch[0]["tsi"];
		
	}else{
	?>
	 <?=$id?> / <?=$id_match?> / <?=$lstJoueur[0]["idHattrickJoueur"]?>
		Match non trouv&eacute;.<br>
		Update impossible.
		</body>
		</html>
	<?php
	return;
	}
}

?>
<table width="85%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr> 
    <td height="15" bgcolor="#000000">
<div align="center"><font color="#FFFFFF"><strong>Ins&eacute;rer / Modifier un match</strong></font></div></td>
  </tr>
  <tr>
    <td valign="top">
<table width="100%" border="0" cellpadding="0" cellspacing="0">


        <tr> 
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr> 
          <td width="50%" align="left">&nbsp; <font color="#000099"><b><?=$infJ["idHattrickJoueur"]?>&nbsp;-&nbsp;<?=$infJ["nomJoueur"]?> <?=$infJ["prenomJoueur"]?>&nbsp;-&nbsp;<?=$infJ["ageJoueur"]?>&nbsp;ans&nbsp;-&nbsp;<?=$infJ["intitulePosition"]?></b></font></td>
          <td width="20%" align="left"><b>Club Actuel : </b><?=$infJ["nomClub"]?>
          </td>
          <td width="30%">&nbsp;</td>
        </tr>

      </table>
      <br>      
        
        <form name="form1" method="post" action="../form.php" onSubmit="return verifform()">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="12" colspan="4">&nbsp;<?php
          if ($fonction== "insert"){
          	?>Ajout d'<?php }else{
          	?>Modification d'<?php 
          }?>un  match</td>
        </tr>
        <tr bgcolor="#000000">
          <td height="1" colspan="4"><img src="../images/spacer.gif" width="1" height="1"></td>
        </tr>
        <tr>
          <td width="20%" height="11">&nbsp;</td>
          <td colspan="3">&nbsp;            </td>
        </tr>
        </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td>hattrick Match ID
			</td>
            <td>
			<?php if ($fonction == "modification"){ ?>
				<input name="id_match" type="hidden" id="id_match" value = "<?=$zeMatch_id?>" >
				<?=$zeMatch_id?>
			<?php }else{ ?>
            	<input name="id_match" type="text" id="id_match" value = "" size="12">
            <?php } ?> 
			</td>
		</tr>
		<tr>
            <td> &nbsp;date (aaaa-mm-jj)</td>
            <td>
			<?php if ($fonction == "modification"){ ?>

				<input name="date_match" type="hidden" id="date_match" value="<?=$zeDate_Match?>" >
				<?=$zeDate_Match?>
			<?php }else{ ?>
	            <input name="date_match" type="text" id="date_match" size="10" value = "">
            <?php } ?> 
            </td>
        </tr>
        <tr>
          <td height="26">&nbsp;Etat de forme</td>
          <td width="28%"><select name="forme" id="forme">
		  <?php
		  for($i=0;$i<count($lstCarac);$i++){
			if ($fonction == "modification"){
				if($lstCarac[$i]["idCarac"] == $zeForme) $etat = "selected"; else $etat = "";
			}else{ 
		  		if($lstCarac[$i]["idCarac"] == $lstPerf[0]["formePerf"]) $etat = "selected"; else $etat = "";
			}


		  echo "<option value = ".$lstCarac[$i]["idCarac"]." $etat>".$lstCarac[$i]["numCarac"]." - ".$lstCarac[$i]["intituleCaracFR"]."</option>";
		  
		  }
		  
		  ?>
          </select></td>
		</tr>
		<tr>
          <td>&nbsp;TSI</td>
          <td>
          
          <input name="tsi" type="text" id="tsi" value = "<?=$zeTSI?>" size="12">
          
          </td>




        </tr>
        <tr>
            <td width="24%">&nbsp;Place(terrain)</td>
            <td colspan=3>
            <select name="id_role" id="select">
            <?php
		  for($i=1;$i<22;$i++){
		  	if ($i==17 || $i==18 ||$i==20 || $i==21){
		  		echo "\n";
		  	}else{
			if ($fonction == "modification"){
				   if($i == $zeRole ) $etat = "selected"; else $etat = "";
			}else{
		  	       if($i == 1 ) $etat = "selected"; else $etat = "";

			}
			  	echo "<option value = ".$i." $etat>".$frenchRole[$i]."</option>";
		  	}
		  }
		  
		  ?>
              </select>
              
		</td>
        </tr>
        <tr>
            <td>&nbsp;Ordre individuel</td>
            <td width="28%">
            <select name="id_behaviour" id="select">
            <?php
		  for($i=0;$i<count($behaviour);$i++){
			if ($fonction == "modification"){
				if($i == $zeBehaviour ) $etat = "selected"; else $etat = "";
			}else{
		  		if($i == 0 ) $etat = "selected"; else $etat = "";
			}
			  echo "<option value = ".$i." $etat>".$frenchBehaviour[$i]."</option>";
		  }
		  
		  ?>
              </select>
              </td>
		</tr>
		<tr>
          <td>&nbsp;&Eacute;toiles <font size="-1">(ex: 3.5)</font></td>
          <td><input name="etoile" type="text" id="etoile" size="4" value = "<?=$zeEtoile?>">
            <img src="../images/star.gif" width="14" height="14"></td>
        </tr>
        <tr>
          <td colspan="2"><div align="center">
          &nbsp;
        	</td>
		</tr>
		<tr>
          <td colspan="2"><div align="center">
          <?php
          if ($fonction== "insert"){
          	?><input type="submit" name="Submit" value="Ajouter ce match">
          	<input name="mode" type="hidden" id="mode" value="rapportDetailleAjout">
          	<?php }else{
          	?><input type="submit" name="Submit" value="Modifier ce match">
          	<input name="mode" type="hidden" id="mode" value="rapportDetailleModif">
          	<?php 
          }?>
            
            
            <input name="id" type="hidden" id="id" value="<?=$id?>">
            <input name="id_joueur" type="hidden" id="id_joueur" value="<?=$lstJoueur[0]["idHattrickJoueur"]?>">
    
          
             
         </div></td>
        </tr>
      </table>
      </form>
      <br>
    
</td>
  </tr>
</table>
    <p align="center"><a href="javascript:history.go(-1);">Retour sans valider le match</a> <br>
    </p>            
</body>
</html>
<?php  deconnect(); ?>


