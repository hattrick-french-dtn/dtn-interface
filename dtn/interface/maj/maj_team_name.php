<?php

// require_once $DOCUMENT_ROOT . '/preprod/include/config.php';
require_once "HT_Client.php"; // Hattrick Client New (with Advanced HTTP Client)
require_once "phpxml.php"; // XML to Tree converter

// paramètres GET
$ht_user=$_GET['ht_user'];
$ht_password=$_GET['ht_password'];
$teamid=$_GET['teamid'];

// connexion HT
$HTCli = &new HT_Client();
if (!$HTCli->Login($ht_user, $ht_password))
	{
		echo "  Couldn't connect to Hattrick.";
		}
// else echo " Connexion OK<BR />";



// recupere info equipe

	unset($xml);
	$xml = $HTCli->GetTeamDetails($teamid);
	$tree = GetXMLTree($xml);
	
//	$teamname = mysql_real_escape_string($tree['HATTRICKDATA'][0]['TEAM'][$i]['TEAMNAME'][0]['VALUE']);
	$teamname = $tree['HATTRICKDATA'][0]['TEAM'][0]['TEAMNAME'][0]['VALUE'];
	echo "Teamid : ".$teamid;
	echo "<BR />";
	echo "Nom d'équipe : ".$teamname;
	echo "<BR />";
	
//	printa($tree);
	echo "<BR />";
	
	echo "Rien n'est écrit en base pour l'instant, juste de l'affichage";
//}



// deconnexion HT	
if (!$HTCli->Logout())
	{
		echo "  Pb de deconnexion";
		}
// else echo " DeConnexion OK";	
echo "<BR />";

?>
