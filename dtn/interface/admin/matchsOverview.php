<?php
  /* Mise a jour des joueurs /matchs xp/transferts  TSI depuis playerdetails quand il semble 
   * que ça n'ait pas ete fait par le DTN.    
   */
       require_once("../includes/serviceJoueur.php");
       require_once("../includes/serviceListesDiverses.php");
       require_once("../includes/serviceMatchs.php");
       require_once("../CHPP/config.php");
       require_once("../includes/head.inc.php");
       
       //   error_reporting(E_WARNING);
       
   //Service reserve aux DTN+ et Admin
if (($sesUser["idNiveauAcces"] == 1)||($sesUser["idNiveauAcces"] == 2)){
   	
   	
$lstPos = listAllPosition();
	if (!isset($affPosition)){
		$affPosition="";
    }   	

	if($affPosition == "") {
		if ($sesUser["idPosition_fk"] == ""){
		$affPosition ="0";	
		}else{
		$affPosition = $sesUser["idPosition_fk"] ;
		}
	}
}else{
	?>
	Fonction impossible.
	
	<?php
	return;
  }
  
// This function check number of matchs  in database for a specific week and a specific season  
function checkNumberMatch($season,$week,$maBase){
$sql =  "select	count(1) from ht_perfs_individuelle where ".
	" week='$week' and season='$season' LIMIT 1";

   	$resMatch = $maBase->select($sql);
  	$countMatch  = $resMatch[0][0];
  	return $countMatch; 
}


function checkNumberMatchPoste($season,$week,$maBase,$poste){
$sql =  "select	count(1) from ht_perfs_individuelle 	" .
		"LEFT JOIN ht_joueurs ON id_Joueur = idHattrickJoueur " .
		" where ".
	" week='$week' and season='$season' " .
	"and ht_posteAssigne ='$poste'";

   	$resMatch = $maBase->select($sql);
  	$countMatch  = $resMatch[0][0];
  	return $countMatch; 
}  
function checkNumberPoste($maBase,$poste){

$sql =  "select	count(1) from ht_joueurs 	" .
		" where ".
	"ht_posteAssigne ='$poste' and archiveJoueur='0' and joueurActif='1' ";

   	$resPlayers= $maBase->select($sql);
  	$countPlayers  = $resPlayers[0][0];
  	return $countPlayers; 
}  
  
if (isset ($action) && "purge"==$action){
if (isset($week)&& (isset($season))){
	        require_once "../_config/CstGlobals.php"; // fonctions d'admin
	       require_once "../fonctions/AccesBase.php"; // fonction de connexion a la base
	     $maBase = initBD();
	
	$nbmatchs=checkNumberMatch($season,$week,$maBase);
	
?>

Purge des <?=$nbmatchs?> matchs  en saison <?=$season?> semaine:<?=week?>
<?php	
 $sql="delete from  ht_perfs_individuelle where week='$week' and season='$season' ";
 $resPurge = $maBase->delete($sql);
if ($resPurge){?>
		<br/> 
		Purge effectu&eacute;e.	<br/><a href="index.php?redirect=matchsOverview.php"> retour </a>
	<?php
}	
	return ;
}
}  
  ?>
  <br>
<center>  
<table border=1 width=90% >  
  <tr>
  <td align="left">
  <?php
  	$todaySeason=getSeasonWeekOfMatch(mktime(0,0,0,date('m'), date('d'),date('Y')));
$mseason=$todaySeason["season"];
$mweek=$todaySeason["week"];
$compteur=0;?>
<ul>
<li> <font color=#2222CC><b>Saison <?=$mseason?> </b></font><br>
	 <b>Semaine : <?=$mweek?></b> d&eacute;j&agrave; : <?=checkNumberMatch($mseason,$mweek,$maBase)?> matchs en base.<br>
	<?php
	$nbg=checkNumberMatchPoste($mseason,$mweek,$maBase,1);
	$nbd=checkNumberMatchPoste($mseason,$mweek,$maBase,2);
	$nba=checkNumberMatchPoste($mseason,$mweek,$maBase,3);
	$nbm=checkNumberMatchPoste($mseason,$mweek,$maBase,4);
	$nbf=checkNumberMatchPoste($mseason,$mweek,$maBase,5);
	$nbtg=checkNumberPoste($maBase,1);
	$nbtd=checkNumberPoste($maBase,2);
	$nbta=checkNumberPoste($maBase,3);
	$nbtm=checkNumberPoste($maBase,4);
	$nbtf=checkNumberPoste($maBase,5);
	$pctg=0;
	if ($nbtg!=0){
		$pctg=($nbg*100)/$nbtg;
	}
	if ($nbta!=0){
		$pcta=($nba*100)/$nbta;
	}
	if ($nbtd!=0){
		$pctd=($nbd*100)/$nbtd;
	}
	if ($nbtm!=0){
		$pctm=($nbm*100)/$nbtm;
	}
	if ($nbtf!=0){
		$pctf=($nbf*100)/$nbtf;
	}
	
	?>

	 Gardiens <?=$nbg?> sur <?=$nbtg?>  -> <?php
	  printf ("%.2f", $pctg); 
	 ?> % <br>
	 Defenseurs <?=$nbd?> sur <?=$nbtd?> -> <?php
	  printf ("%.2f", $pctd); 
	 ?> % <br>
	 Ailiers <?=$nba?> sur <?=$nbta?> -> <?php
	  printf ("%.2f", $pcta); 
	 ?> % <br>
	 Milieux <?=$nbm?> sur <?=$nbtm?> -> <?php
	  printf ("%.2f", $pctm); 
	 ?> % <br>
	 Attaquants <?=$nbf?> sur <?=$nbtf?>  -> <?php
	  printf ("%.2f", $pctf); 
	 ?> % <br><br>

	
	
	
<?php
$toto=0;

while ($compteur <20){
	$toto=$toto+1;
	
	$compteur=$compteur+1;
	if ($mweek==0){
		
		$mseason=$mseason-1;?>
		------------------------------------<br>
	<li> <font color=#2222CC><b>Saison <?=$mseason?> </b></font><br>
	<?php
		$mweek=16;
	}
	$mweek=$mweek-1;
	?>
	
	<b>Semaine : <?=$mweek?></b> il y avait : <?=checkNumberMatch($mseason,$mweek,$maBase)?> matchs en base.<br>
						
	<?php
	if ($toto<3){?>
	dont - 
	( Gardiens : <?=checkNumberMatchPoste($mseason,$mweek,$maBase,1)?> ) -
	( Defenseurs : <?=checkNumberMatchPoste($mseason,$mweek,$maBase,2)?> ) -
	( Ailiers : <?=checkNumberMatchPoste($mseason,$mweek,$maBase,3)?> ) -
	( Milieux : <?=checkNumberMatchPoste($mseason,$mweek,$maBase,4)?> ) -
	( Attaquants : <?=checkNumberMatchPoste($mseason,$mweek,$maBase,5)?> ) <br><br>
		
	<?php }
	
	
}?>
------------------------------------<br>
<?php
 while ($compteur <32 && $mseason>20){
	if ($mweek==0){
		$mseason=$mseason-1;
		$mweek=16;?>
	<li> <font color=#2222CC><b>Saison <?=$mseason?> </b></font><br>
	<?php }
	$mweek=$mweek-1;
	
	$nbmatchs=checkNumberMatch($mseason,$mweek,$maBase);
	if ($nbmatchs>0){
	?>
		Semaine :<?=$mweek?> il y avait : <?=$nbmatchs?> matchs en base. <a href="matchsOverview.php?week=<?=$mweek?>&amp;season=<?=$mseason?>&amp;action=purge"> Purger </a><br>
	<?php 
	$compteur=$compteur+1;
	}
 } 	



?>						  
  </td>
  </tr>
  </table> 
</body>
</html>
