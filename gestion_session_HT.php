<?php
// Variable de connexion à HT
define("CONSUMERKEY",'GVTI3L4KQDr97DCibIHwWq');
define("CONSUMERSECRET",'FImiceJW7lscXkHtGp85XfYCiS9eHBcXoKBZPh14miW');
require_once($_SERVER['DOCUMENT_ROOT']."/dtn/interface/includes/serviceEquipes.php");



/******************************************************************************/
/******************************************************************************/
/*      TEST CONNECTIVITE HT                                                  */
/******************************************************************************/
/******************************************************************************/

if (isset($_SESSION['HT']) && PHT\Network\Request::pingChppServer(5000)==false) { // Si le serveur CHPP est inaccessible durant 10 secondes
   $_SESSION['CHPP_KO'] = true;
   unset($_SESSION['HT']);
} else {
   $_SESSION['CHPP_KO'] = false;
}



/******************************************************************************/
/******************************************************************************/
/*      GESTION DONNEES FORMULAIRE                                            */
/******************************************************************************/
/******************************************************************************/

if (!isset($_SESSION['horsConnexion'])) {

  // Si l'utilisateur a cliqué sur ouvrir une session HT
  if (isset($_REQUEST['mode'])) {
  
    /******************************************************************************/
    /******************************************************************************/
    /*      CONNEXION HT                                                          */
    /******************************************************************************/
    /******************************************************************************/
    if ($_REQUEST['mode']=='redirectionHT') {
  
		if (isset($_REQUEST['connexion_permanente']) && $_REQUEST['connexion_permanente']==1) {
			$callbackUrl .= "&connexion_permanente=1";
		} else {
			$callbackUrl .= "&connexion_permanente=0";
		}
	  
		/* move to v3 */
		/*
		You must supply your chpp crendentials and a callback url.
		User will be redirected to this url after login
		You can add your own parameters to this url if you need,
		they will be kept on user redirection
		*/
		/*
		try
		{
			$HT = new CHPPConnection(CONSUMERKEY,CONSUMERSECRET,$callbackUrl);
			$url = $HT->getAuthorizeUrl();
		}
		catch(HTError $e)
		{
			echo $e->getMessage();
		}
		*/
		/*
		Be sure to store $HT in session before redirect user
		to Hattrick chpp login page
		*/
		//$_SESSION['HT'] = $HT;
		
		
		/*
		You must supply your chpp crendentials and a callback url.
		User will be redirected to this url after login
		You can add your own parameters to this url if you need,
		they will be kept on user redirection
		*/
		$config = array(
			'CONSUMER_KEY' => CONSUMERKEY,
			'CONSUMER_SECRET' => CONSUMERSECRET,
			'CACHE' => 'memcached',
		);
		$HT = new \PHT\Connection($config);
		if (isset($_REQUEST['connexion_permanente']) && $_REQUEST['connexion_permanente']==1) {
			$auth = $HT->getPermanentAuthorization($callbackUrl);
		} else {
			$auth = $HT->getTemporaryAuthorization($callbackUrl);
		}
		if ($auth === false) {
			echo "Impossible to initiate chpp connection";
			exit();
		}
		$url = $auth->url;
		/*
		Be sure to store the CHPP token in session before redirect user
		to Hattrick chpp login page
		Needed to validate the connection
		*/
		$_SESSION['HTToken'] = $auth->temporaryToken;
				
		/*
		Redirect user to Hattrick for login
		or put a link with this url on your site
		*/
		header('Location: '.$url); 
			exit();
    }
    
    
    /******************************************************************************/
    /******************************************************************************/
    /*      GESTION RETOUR APRES CONNEXION HT                                     */
    /******************************************************************************/
    /******************************************************************************/
    
    if ($_REQUEST['mode']=='retour' && isset($_SESSION['HTToken'])) {
      // On récupère les données d'authentification passé dans l'url
      try
      {
		$config = array(
			'CONSUMER_KEY' => CONSUMERKEY,
			'CONSUMER_SECRET' => CONSUMERSECRET,
			'CACHE' => 'memcached',
		);
		$HTCon = new \PHT\Connection($config);
        $access = $HTCon->getChppAccess($_SESSION['HTToken'], $_REQUEST['oauth_token'], $_REQUEST['oauth_verifier']);
		
		if ($access === false) {
			//print($_SESSION['HTToken']);exit();
			print("<br/>access failed");
			exit();
		}
		$config['OAUTH_TOKEN'] = $access->oauthToken;
		$config['OAUTH_TOKEN_SECRET'] = $access->oauthTokenSecret;
		$HT = new \PHT\PHT($config);
		$_SESSION['HT'] = $HT;

        /*
        Now access is granted for your application
        You can save user token and token secret and/or request xml files
        */
		$userId = $_SESSION['HT']->getUser()->getId();
		$teamcfg = new \PHT\Config\Team();
		$teamcfg->userId = $userId;
		try {
			$teamcfg->international = true;
			$team = $HT->getSeniorTeam($teamcfg);
			if ($team != NULL) {
				$clubHT = getDataClubFromHT_usingPHTv3($team);
				$clubHT['userToken'] = $access->oauthToken;
				$clubHT['userTokenSecret'] = $access->oauthTokenSecret;

				$majClub=insertionClub($clubHT); // Insertion ou Maj des tokens dans la bdd DTN
			}
		}
		catch (\PHT\Exception\InvalidArguementException $e) {
			echo $e->getMessage();
		}
		try {
			$teamcfg->secondary  = true;
			$team = $HT->getSeniorTeam($teamcfg);
			if ($team != NULL) {
				$clubHT = getDataClubFromHT_usingPHTv3($team);
				$clubHT['userToken'] = $access->oauthToken;
				$clubHT['userTokenSecret'] = $access->oauthTokenSecret;

				$majClub=insertionClub($clubHT); // Insertion ou Maj des tokens dans la bdd DTN
			}
		}
		catch (\PHT\Exception\InvalidArguementException $e) {
			echo $e->getMessage();
		}
		try {
			$teamcfg->primary  = true;
			$team = $HT->getSeniorTeam($teamcfg);
			if ($team != NULL) {
				$clubHT = getDataClubFromHT_usingPHTv3($team);
				$clubHT['userToken'] = $access->oauthToken;
				$clubHT['userTokenSecret'] = $access->oauthTokenSecret;

				$majClub=insertionClub($clubHT); // Insertion ou Maj des tokens dans la bdd DTN
			}
		}
		catch (\PHT\Exception\InvalidArguementException $e) {
			echo $e->getMessage();
		}
		        
        $_SESSION['nomUser']=$clubHT['nomUser'];
        $_SESSION['idUserHT']=$clubHT['idUserHT'];
        $_SESSION['newVisit']=1;
        
        if (isset($_REQUEST['connexion_permanente']) && $_REQUEST['connexion_permanente']==1) {
			// Si la case "garder ma session active" est cochée alors on ajoute un cookie valable 5 ans
			setcookie('idClubHT',$clubHT['idClubHT'], time() + 5*365*24*3600, null, null, false, true);        
        } else if (isset($_REQUEST['connexion_permanente']) && $_REQUEST['connexion_permanente']==0) {
			// Si la case "garder ma session active" est décochée alors on supprime les cookies
			setcookie('idClubHT');
        }
      }
      catch(\PHT\Exception\ChppException $e)
      {
			echo $e->getErrorCode().': '.$e->getError();
			// you can also get whole xml response like any other chpp request:
			echo $e->getXml(false);
			//echo $e->getMessage();
      }
      catch(\PHT\Exception\NetworkException $e)
      {
		echo $e->getError();
        echo $e->getMessage();
      }
      
    }
    
    if ($_REQUEST['mode']=='logout') {
      setcookie('idClubHT');
      unset($_SESSION['HT']);
      unset($_SESSION['nomUser']);
      unset($_SESSION['idUserHT']);
      unset($_SESSION['newVisit']);
    }
     
  } else { // $_REQUEST['mode'] n'est pas défini
  
    if (!isset($_SESSION['HT']) || empty($_SESSION['HT']) ) {
  
      /******************************************************************************/
      /******************************************************************************/
      /*      RECUPERATION SESSION DES COOKIES - UTILISE SUR PORTAIL                */
      /******************************************************************************/
      /******************************************************************************/
      if (isset($_COOKIE['idClubHT']) && !empty($_COOKIE['idClubHT']) && $_SESSION['acces']=='PORTAIL') {
  
        //echo("<br />===PORTAIL===<br />");
		$config = array(
			'CONSUMER_KEY' => CONSUMERKEY,
			'CONSUMER_SECRET' => CONSUMERSECRET,
			'CACHE' => 'memcached',
		);
		$HT = new \PHT\PHT($config);
        $clubDTN = getClubID($_COOKIE['idClubHT']);
  
        //$HT->setOauthToken($clubDTN['userToken']);
        //$HT->setOauthTokenSecret($clubDTN['userTokenSecret']);
      
        $_SESSION['HT']=$HT;
        unset($HT);
        $_SESSION['nomUser']=$clubDTN['nomUser'];
        $_SESSION['idUserHT']=$clubDTN['idUserHT'];
      }
      
      /******************************************************************************/
      /******************************************************************************/
      /*      RECUPERATION SESSION DANS BDD - UTILISE SUR INTERFACE                 */
      /******************************************************************************/
      /******************************************************************************/
      if ($_SESSION['acces']=='INTERFACE') {
  
        $_SESSION['HT']=existAutorisationClubv3(null,$_SESSION['sesUser']['idAdminHT']);
  
        if (!$_SESSION['HT']) {
			unset($_SESSION['HT']);
        } else {
			$clubDTN = getClubID(null,$_SESSION['sesUser']['idAdminHT']); // Extraction du club dans la bdd DTN
			$_SESSION['nomUser']=$clubDTN['nomUser'];
			$_SESSION['idUserHT']=$clubDTN['idUserHT'];
        }
        /*echo("<br />===INTERFACE===<br />");
        echo("idAdminHT=");var_dump($_SESSION['sesUser']['idAdminHT']);
        echo("<br />session=");var_dump($_SESSION['HT']);*/
        
      }
  
    }
    
    if (isset($_SESSION['HT'])) {
      /******************************************************************************/
      /******************************************************************************/
      /*      VERIFICATION VALIDITE SESSION                                         */
      /******************************************************************************/
      /******************************************************************************/
  
      // Vérifier que la session est valide
      $check = PHT\Network\Auth::checkToken();
      //var_dump($check);echo("<br><br>".$check->isValid());exit;
  
      if ($check->isValid()===false) {
        // Si session non Valide alors détruire la session
        unset($_SESSION['HT']);
        unset($_SESSION['nomUser']);
        unset($_SESSION['idUserHT']);
        unset($_SESSION['newVisit']);
        setcookie('idClubHT');
      }
    }
  
  }
  
  // Initialistion de la variable de session newVisit afin d'empêcher les proprios de faire une maj en base à chaque clique sur proposer.
  // Une seule maj par session 
  if (!isset($_SESSION['newVisit']) && $_SESSION['acces']=='PORTAIL') {$_SESSION['newVisit']=1;}
  
  //print_r($_SESSION['HT']);
}


if (isset($_SESSION['HT']) && PHT\Network\Request::pingChppServer(5000)==false) { // Si le serveur CHPP est inaccessible durant 10 secondes
   $_SESSION['CHPP_KO'] = true;
   unset($_SESSION['HT']);
} else {
   $_SESSION['CHPP_KO'] = false;
}


?>
