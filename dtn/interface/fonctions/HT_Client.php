<?php

require_once( 'HTTP_Advanced/http.inc' );
require_once "phpxml.php";

class HT_Client
{
	var	$HTClient;
	
	function HT_Client()
	{
		$this->HTClient = &new http( HTTP_V11, false);
	}


	function GetActivePage()
	{
		$this->HTClient->host = "www.hattrick.org";
		$code = $this->HTClient->get("/Common/chppxml.axd?file=servers");
		if ($code != HTTP_STATUS_OK)
		return false;
		$response = $this->HTClient->get_response_body();
		$parser = xml_parser_create();
		xml_parse_into_struct($parser,$response,$vals,$index);
		xml_parser_free($parser);
		$RecommendedURL = str_replace( "http://", "", $vals[$index['RECOMMENDEDURL'][0]]['value'] );
				
		$this->HTClient->host = $RecommendedURL;


		return $RecommendedURL;
	}
	function isConnected()
	{
	  
		$code = $this->HTClient->get("/Common/chpp/chppxml.axd?file=team");
		if ($code != HTTP_STATUS_OK)
		return false;
		$res = $this->HTClient->get_response_body();
		//print ("  [".str_replace("a","z",$res)."]");
		if (!(stristr($res,"chpperror.xml")===FALSE )){
			
			return false;
		}
		if (!(stristr($res,"you are not logged in")===FALSE )){
			return false;
		}
		return true;
	}
	
	function Login($username, $password)
	{
		$RecommendedURL = $this->GetActivePage();
						
		$code = $this->HTClient->get( '/Common/chppxml.axd?file=login&actionType=login&loginname='.$username.'&readonlypassword='.$password.'&chppID=896&chppKey=FA8F0D1A-7129-4289-8717-EAE41321612E' );
				
	    if ($code != HTTP_STATUS_OK) return false;
	    
	    $response = $this->HTClient->get_response_body();
	    
	    $parser = xml_parser_create();
		xml_parse_into_struct($parser,$response,$vals,$index);
		xml_parser_free($parser);
				
		if ($vals[$index['LOGINRESULT'][0]]['value'] != 0) return false;

		//if ($GLOBALS["debug"]) echo $RecommendedURL;
					    	       
	    return true;
	}
				
	function Logout()
	{
		$code = $this->HTClient->get( '/Common/chppxml.axd?file=login&actionType=logout' );

	    if ($code != HTTP_STATUS_OK) return false;
	    
	    $response = $this->HTClient->get_response_body();
	    
	    $parser = xml_parser_create();
		xml_parse_into_struct($parser,$response,$vals,$index);
		xml_parser_free($parser);
		
		if ($vals[$index['ACTIONSUCCESSFUL'][0]]['value'] != 0) return false;
			    	       
	    return true;
		
	}
	
	function GetLeagueTable($leagueid)
	{

		$code = $this->HTClient->get( '/Common/chpp/chppxml.axd?file=leaguedetails&leagueLevelUnitID='.$leagueid );

		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML de la Division
	}
	
	function GetFixture($leagueid,$season)
	{		
		$code = $this->HTClient->get( '/Common/chpp/chppxml.axd?file=leaguefixtures&leagueLevelUnitID='.$leagueid.'&season='.$season );
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML du calendrier de la Division
	}	
	
	function GetMatch($matchid)
	{
		$code = $this->HTClient->get( '/Common/chpp/chppxml.axd?file=matchdetails&matchID='.$matchid );
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML du match en parametre
	}
	
	function GetPlayers($teamid)
	{
		$code = $this->HTClient->get( '/Common/chpp/chppxml.axd?file=players&version=1.6&teamID='.$teamid );
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML des  joueurs de l'equipe teamid
	}

	function GetMyOwnPlayers()
	{
		$code = $this->HTClient->get( '/Common/chpp/chppxml.axd?file=players&version=1.6');
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML des  joueurs de l'equipe du user connecte
	}
	
	function GetSinglePlayer($playerid)
	{
		$code = $this->HTClient->get( '/Common/chpp/chppxml.axd?file=playerdetails&playerID='.$playerid.'&version=1.4');
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML du joueur parametre
	}
	
	function GetLastMatch($teamid)
	{
		$code = $this->HTClient->get( '/Common/chpp/chppxml.axd?file=matches&teamID='.$teamid.'&isYouth=false');
	
		//$form = array('outputType' => 'XML', 'actionType' => 'view', 'teamID' => $teamid);
		//$code = $this->HTClient->post( '/Common/matches.asp', $form, 'http://' . $this->HTClient->host . '/Common/matches.asp' );
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML des derniers matchs en parametre
	}

	// Ajouté par Musta le 07/10/2009
  function GetMatchesBeforeToday($teamid)
	{
		$code = $this->HTClient->get( '/Common/chpp/chppxml.axd?file=matches&teamID='.$teamid.'&isYouth=false&LastMatchDate='.date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")+1, date("y"))));
	
		//$form = array('outputType' => 'XML', 'actionType' => 'view', 'teamID' => $teamid);
		//$code = $this->HTClient->post( '/Common/matches.asp', $form, 'http://' . $this->HTClient->host . '/Common/matches.asp' );
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML des derniers matchs en parametre
	}
	
	function GetFullMatch($teamid, $dateFrom)
	{
		$code = $this->HTClient->get( '/Common/chpp/chppxml.axd?file=matchesArchive&teamID='.$teamid.'&FirstMatchDate='.$dateFrom.'&isYouth=false');
		//$form = array('outputType' => 'XML', 'actionType' => 'view', 'teamID' => $teamid , 'FirstMatchDate' => $dateFrom ); 
		//$code = $this->HTClient->post( '/Common/matchesArchive.asp', $form, 'http://' . $this->HTClient->host . '/Common/matchesArchive.asp' );
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML du match en param�tre
	}
	
	
	function GetMatchDetails($matchid) /// duplication par rapport � GetMatch mais fait par David :p
	{
		$code = $this->HTClient->get( '/Common/chpp/chppxml.axd?file=matchdetails&matchID='.$matchId.'&isYouth=false');
		//$form = array('outputType' => 'XML', 'actionType' => 'view', 'matchID' => $matchid);
		//$code = $this->HTClient->post( '/Common/matchDetails.asp', $form, 'http://' . $this->HTClient->host . '/Common/matchDetails.asp' );
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML du match en param�tre
	}
	
	function GetMatchLineup($matchid="LAST",$teamid)
	{
	  if ($matchid=="LAST")  {$code = $this->HTClient->get( '/Common/chpp/chppxml.axd?file=matchLineup&teamID='.$teamid.'&isYouth=false&version=1.3');}
	  else {$code = $this->HTClient->get( '/Common/chpp/chppxml.axd?file=matchLineup&matchID='.$matchid.'&teamID='.$teamid.'&isYouth=false&version=1.3');}
		//$form = array('outputType' => 'XML', 'actionType' => 'view', 'matchID' => $matchid, 'teamID' => $teamid);
		//$code = $this->HTClient->post( '/Common/matchLineup.asp', $form, 'http://' . $this->HTClient->host . '/Common/matchLineup.asp' );
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML du match en param�tre
	}
	
	function AddLiveMatch($matchid)
	{
		$code = $this->HTClient->get( '/Common/chpp/chppxml.axd?file=live&actionType=addMatch&matchID='.$matchid);
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML du live du match en param�tre
	}
	
	function DeleteLiveMatch($matchid)
	{
		$code = $this->HTClient->get( '/Common/chpp/chppxml.axd?file=live&actionType=deleteMatch&matchID='.$matchid);
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML du live du match en param�tre
	}


	function GetTeamDetails($teamid)
	{
		$code = $this->HTClient->get( '/Common/chpp/chppxml.axd?file=teamdetails&teamID='.$teamid);
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // 
	}

	function GetMyOwnTeamDetails()
	{
		$code = $this->HTClient->get( '/Common/chpp/chppxml.axd?file=team');
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // 
	}


	function GetRegionDetails($regionid)
	{
		$code = $this->HTClient->get( '/Common/chpp/chppxml.axd?file=regiondetails&regionID='.$regionid);
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML de la region
	}
	
	function GetWorldDetails()
	{
		$code = $this->HTClient->get( '/Common/chpp/chppxml.axd?file=worlddetails');
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML avec les details de toutes les leagues
	}
	
	function TransferHistory($playerid)
	{
		$code = $this->HTClient->get( '/Common/chpp/chppxml.axd?file=transfersPlayer&PlayerID='.$playerid);
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML des transferts du joueur
	}

	function getMatchDetails2($matchId)
	{
		//$form = array('file' => 'matchdetails', 'matchID' => 237985, 'isYouth' => true);
		$code = $this->HTClient->get( '/Common/chpp/chppxml.axd?file=matchdetails&matchID='.$matchId.'&isYouth=true');

		//$code = $this->HTClient->post( '/Common/chpp/chppxml.axd', $form, '/Common/chpp/chppxml.axd' );
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML du match en param�tre
	}
	
	function NTlistJoueurEquipe($teamID)
	{
		$code = $this->HTClient->get( '/Common/chpp/chppxml.axd?file=nationalplayers&teamID='.$teamID);
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML du match en param�tre
	}
	
	function GetMonEntrainement()
	{
		$code = $this->HTClient->get( '/Common/chpp/chppxml.axd?file=training');
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML du match en param�tre
	}
	
	function GetMonClub()
	{
		$code = $this->HTClient->get( '/Common/chpp/chppxml.axd?file=club');
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML du match en param�tre
	}
	

	
	
	
}

?>
