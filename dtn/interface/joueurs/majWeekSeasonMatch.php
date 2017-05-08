<?php
require("../includes/head.inc.php");
require("../includes/serviceMatchs.php");


if(!$sesUser["idAdmin"])
	{
	header("location: ../index.php?ErrorMsg=Session Expiree");
	}
set_time_limit(0);
$lot=100;
if (isset($nb)){
	$nb=$nb+1;
	$lot=$lot*$nb;
}else{
	$nb=1;
	$lot=100;
}
$listeMatch = getMatchByPacket($lot);
$max=count($listeMatch);
?>
Script made at the moment of creation of week/season in table ht_perfs_individuelles (match table)
(V1.0 30 Oct 2005)
<br>
it updates table by group of 100 matchs. click at the end of the page to 
update the next 100 matchs.<br/> 

<?php
for($id=$lot-100; $id<$lot;$id++){
	
	if ($id-$lot+100>=$max)
	break;

$zeMatch=$listeMatch[$id-$lot+100];

$zeDateMatch= $zeMatch["date_match"];
$date = explode("-",substr($zeDateMatch,0,10));
$unixTime=  mktime(0,0,0,$date[1],$date[2],$date[0]); 
			
		?>match : <?=$date[1]?>-<?=$date[2]?>-<?=$date[0]?>  id : <?=$zeMatch["id_match"]?>  <?php
		$seasonw=(getSeasonWeekOfMatch($unixTime));
		?>-> S<?=$seasonw["season"]?>.W<?=$seasonw["week"]?><?php
		$result=updateSeasonWeekMatch($zeMatch["id_match"],$zeMatch["id_joueur"],$seasonw["season"],$seasonw["week"])

?>[ <?=$result?>]rows updated<br><?php
}
?>
<p>
<a href="majWeekSeasonMatch.php?nb=<?=($nb)?>">suite>

