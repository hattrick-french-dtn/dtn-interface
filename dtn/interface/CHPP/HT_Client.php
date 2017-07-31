<?PHP 

//require_once $DOCUMENT_ROOT . '/preprod/include/config.php';
require_once( 'HTTP_Advanced/http.inc' );

require_once "phpxml.php";


class HT_Client
{
	var	$HTClient;
	
	function HT_Client()
	{
		$this->HTClient = new http( HTTP_V11, false);
	}

	function GetActivePage()
	{
		$this->HTClient->host = "www.hattrick.org";
		$code = $this->HTClient->get("/Common/menu.asp?outputType=XML");
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
	
	function Login_HTML($username, $password)
	{
		$RecommendedURL = $this->GetActivePage();
		$form = array('loginname' => $username, 'password' => $password, 'actionType' => 'login', 'user-agent' => 'ht-fff.org, v0.1');
		$code = $this->HTClient->post( '/Common/default.asp', $form, 'http://' . $this->HTClient->host . '/Common/default.asp' );
		
	    if ($code != HTTP_STATUS_OK) return false;
	    
	    $response = $this->HTClient->get_response_body();
	    if (strpos($response, "&loginError=Yes") !== false) return false;
	    
	    return true;
	}
		
	function Logout_HTML()
	{
		$form = array('actionType' => 'logout');
		$code = $this->HTClient->post('/Common/default.asp', $form, 'http://' . $this->HTClient->host .  '/Common/default.asp' );

		if ($code == HTTP_STATUS_OK)
				return True;
	}
	
	function Login($username, $password)
	{
		$RecommendedURL = $this->GetActivePage();
		
		$form = array('outputType' => 'XML', 'actionType' => 'login', 'loginType' => 'CHPP', 'Loginname' => $username, 'readonlypassword' => $password,  'user-agent' => 'ht-fff.org, v0.1');
		$code = $this->HTClient->post( '/Common/default.asp', $form, 'http://' . $this->HTClient->host . '/Common/default.asp' );
		
	    if ($code != HTTP_STATUS_OK) return false;
	    
	    $response = $this->HTClient->get_response_body();
	    
	    $parser = xml_parser_create();
		xml_parse_into_struct($parser,$response,$vals,$index);
		xml_parser_free($parser);
		
		if ($vals[$index['ACTIONSUCCESSFUL'][0]]['value'] != "True") return false;

		if ($GLOBALS["debug"]) echo $RecommendedURL;
					    	       
	    return true;
	}
		
	function Logout()
	{
		$form = array('outputType' => 'XML', 'actionType' => 'logout');
		$code = $this->HTClient->post('/Common/default.asp', $form, 'http://' . $this->HTClient->host .  '/Common/default.asp' );

	    if ($code != HTTP_STATUS_OK) return false;
	    
	    $response = $this->HTClient->get_response_body();
	    
	    $parser = xml_parser_create();
		xml_parse_into_struct($parser,$response,$vals,$index);
		xml_parser_free($parser);
		
		if ($vals[$index['ACTIONSUCCESSFUL'][0]]['value'] != "True") return false;
			    	       
	    return true;
		
	}
	
	function GetLeagueTable($leagueid)
	{
		$form = array('outputType' => 'XML', 'actionType' => 'view', 'leagueLevelUnitID' => $leagueid);
		$code = $this->HTClient->post( '/Common/leagueDetails.asp', $form, 'http://' . $this->HTClient->host . '/Common/leaguedetails.asp' );
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML de la Division
	}
	
	function GetFixture($leagueid,$season)
	{
		$form = array('outputType' => 'XML', 'actionType' => 'view', 'leagueLevelUnitID' => $leagueid, 'season' => $season);
		$code = $this->HTClient->post( '/Common/leagueFixtures.asp', $form, 'http://' . $this->HTClient->host . '/Common/leagueFixtures.asp' );
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML du calendrier de la Division
	}	
	
	function GetMatch($matchid)
	{
		$form = array('outputType' => 'XML', 'actionType' => 'view', 'matchID' => $matchid);
		$code = $this->HTClient->post( '/Common/matchDetails.asp', $form, 'http://' . $this->HTClient->host . '/Common/matchDetails.asp' );
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML du match en param?tre
	}

	function GetLastMatch($teamid)
	{
		$form = array('outputType' => 'XML', 'actionType' => 'view', 'teamID' => $teamid);
		$code = $this->HTClient->post( '/Common/matches.asp', $form, 'http://' . $this->HTClient->host . '/Common/matches.asp' );
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML du match en param?tre
	}
	
	function GetFullMatch($teamid, $dateFrom)
	{
		$form = array('outputType' => 'XML', 'actionType' => 'view', 'teamID' => $teamid , 'FirstMatchDate' => $dateFrom ); 
		$code = $this->HTClient->post( '/Common/matchesArchive.asp', $form, 'http://' . $this->HTClient->host . '/Common/matchesArchive.asp' );
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML du match en param?tre
	}
	
	
	function getMatchDetails($matchId)
	{
		$form = array('outputType' => 'XML', 'actionType' => 'view', 'matchID' => $matchId);
		$code = $this->HTClient->post( '/Common/matchDetails.asp', $form, 'http://' . $this->HTClient->host . '/Common/matchDetails.asp' );
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML du match en param?tre
	}
	
	function getMatchLineup($matchId,$teamID)
	{
		$form = array('outputType' => 'XML', 'actionType' => 'view', 'matchID' => $matchId, 'teamID' => $teamID);
		$code = $this->HTClient->post( '/Common/matchLineup.asp', $form, 'http://' . $this->HTClient->host . '/Common/matchLineup.asp' );
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML du match en param?tre
	}
	
	function listJoueurEquipe()
	{
		$form = array('outputType' => 'XML', 'actionType' => 'view');
		$code = $this->HTClient->post( '/Common/players.asp', $form, 'http://' . $this->HTClient->host . '/Common/players.asp' );
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML du match en param?tre
	}
	
	function getTeamInfo()
	{
		$form = array('outputType' => 'XML', 'actionType' => 'view');
		$code = $this->HTClient->post( '/Common/teamDetails.asp', $form, 'http://' . $this->HTClient->host . '/Common/teamDetails.asp' );
		if ($code != HTTP_STATUS_OK) { return $code; }
		return $this->HTClient->get_response_body(); // renvoie le fichier XML du match en param?tre
	}

	
	
}