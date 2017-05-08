<?php

//require_once $DOCUMENT_ROOT . '/preprod/include/config.php';
require_once "HT_Client.php"; // Hattrick Client New (with Advanced HTTP Client)
require_once "config.php"; // Config
require_once "phpxml.php"; // Hattrick Client New (with Advanced HTTP Client)
// paramètres

$ht_user="david1980";
$ht_password="8789a";


// Tableau du joueur

$tabJs = array( "14908666"  =>"47240",  "11914076"=> "47240" );


// Tableau de l'équipe

//$tabJs = array( "8931", "8923" , "8937" , "8913" , "8980" , "9033" , "9053", "8922" );
		
		foreach($tabJs as $idJoueur=>$idEquipe){
		//foreach($tabJs as $idEquipe){
		
unset($tabJoueur);
							for ($i = 0 ; $i <= 0; $i++) {
							
								//echo $i . " ";
								$HTCli = &new HT_Client();
								if (!$HTCli->Login($ht_user, $ht_password))
									{
										echo "  Couldn't connect to Hattrick.";
										}
								//else echo "<a href ='match.php'>Suivants</a>";
								
							// Retrieve sur les archives
							set_time_limit(0);
							$infMatch = $HTCli->GetFullMatch($idEquipe,"2004-10-01");
							
							// Retrieve sur 2 matchs
							//$infMatch = $HTCli->GetLastMatch($idEquipe);
							
							$tree = GetXMLTree($infMatch);
							
							
							// Nb Match a lister
							 $nbMatch = count($tree["HATTRICKDATA"][0]["TEAM"][0]["MATCHLIST"][0]["MATCH"]);
							
								$newTab = array();
							
							
							// Récupération de la liste des matchs seuls
							for($i=0;$i<$nbMatch;$i++){
							
								
								
								$newTab[] = $tree["HATTRICKDATA"][0]["TEAM"][0]["MATCHLIST"][0]["MATCH"][$i];
								
							}
							
							
							/*
							
							// Retrieve sur 2 matchs :
							
							$n = count($newTab);
							
							$max = $n-2;
							// Conservation uniquement des 2 derniers matchs
							for($i=0;$i<$n;$i++){
							
								if($i>=$max){
								
								$tab[] = $newTab[$i];
								}
							
							}
							*/
							
							// Récupération de la lineup de ces matchs
							$i=0;
							foreach($newTab as $l){
							
							$lineUp = $HTCli->getMatchLineup($l["MATCHID"][0]["VALUE"],$idEquipe);
							$treeMatch[$i] = GetXMLTree($lineUp);
							$i++;
							}
							
							// Mise dans un tableau des informations concernant le joueur tracké
							foreach($treeMatch as $t){
									
									
										foreach($t["HATTRICKDATA"][0]["TEAM"][0]["LINEUP"][0]["PLAYER"] as $n){
										
										
											if($n["PLAYERID"][0]["VALUE"] == $idJoueur && $n["ROLEID"][0]["VALUE"] != 18){
											// Association des infos match
											
											 $n["MATCHID"] = $t["HATTRICKDATA"][0]["MATCHID"][0]["VALUE"];
											 $n["MATCHDATE"] = $t["HATTRICKDATA"][0]["MATCHDATE"][0]["VALUE"];
											 $n["HOME"] = $t["HATTRICKDATA"][0]["HOMETEAM"][0]["HOMETEAMNAME"][0]["VALUE"];
											 $n["AWAY"]= $t["HATTRICKDATA"][0]["AWAYTEAM"][0]["AWAYTEAMNAME"][0]["VALUE"];
											 $tabJoueur[] = $n;
											}
										
										}
								
								$i++;
								}
								
								
								foreach($tabJoueur as $t){
								
								echo $t["MATCHID"]." ";
								echo $t["MATCHDATE"]." ";
								echo $t["HOME"]." vs ";
								echo $t["AWAY"]." | ";
								echo $t["PLAYERID"][0]["VALUE"]." ";
								echo $t["PLAYERNAME"][0]["VALUE"]." ";
								echo $role[$t["ROLEID"][0]["VALUE"]].", ";
								echo $position[$t["POSITIONCODE"][0]["VALUE"]].", ";
								echo $behaviour[$t["BEHAVIOUR"][0]["VALUE"]].", ";
								echo $t["RATINGSTARS"][0]["VALUE"]."* ";
								echo "<br>";
								}
												
												
				echo "<hr>";

				}
				
				
	if (!$HTCli->Logout())
		{
			echo "  Pb de deconnexion";
			}
//	else echo " DeConnexion OK";	
//	echo "<BR />";
}

?>
