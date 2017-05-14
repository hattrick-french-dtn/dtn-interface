<?php
require("../includes/head.inc.php");
require("../includes/serviceJoueur.php");
?><title>Mise &agrave; jour des date</title>
<?php

// 1. Liste des joueurs avec un entrainement de selectionner. 
$sql =  "
		SELECT * from ht_perfmatch WHERE  semainePerf  > 0";
		foreach ($conn->query($sql) as $result) {
			$res = $result['semainePerf'] -1;
				
			$sql2 = "UPDATE ht_perfmatch SET semainePerf = '".$res."' WHERE idPerf  = '".$result['idPerf']."' ";
			$req2 = $conn->exec($sql2);
			//echo $sql2."<br>";
		}
// 2. +1 semaine pour l'entrainement en question

?>
