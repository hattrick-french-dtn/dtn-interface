<?php
// Variable paramétrage de la page
$nomFicPhpCourant = explode("?",$_SERVER['REQUEST_URI']);
$urlsource = $nomFicPhpCourant[0]; // Utilisé pour setlang.php
$callbackUrl="http://".$_SERVER['HTTP_HOST'].$nomFicPhpCourant[0]."?mode=retour"; // Url de retour après authentification sur HT
//$file="members"; // Nom du fichier pour include

require("dtn/interface/includes/head.inc.php");

include("init.php");
require("dtn/interface/includes/serviceJoueur.php");
require("dtn/interface/includes/serviceMatchs.php");
require("dtn/interface/includes/serviceiihelp.php");
require("dtn/interface/includes/serviceListesDiverses.php");
require_once "dtn/interface/_config/CstGlobals.php"; 
require_once "dtn/interface/fonctions/AccesBase.php"; // fonction de connexion a la base




/******************************************************************************/
/******************************************************************************/
/*      GESTION DONNEES FORMULAIRE                                            */
/******************************************************************************/
/******************************************************************************/
// Initialisation variable php
$messageaff='<b><Font color="red" size="2">';
$errForm='0';
$validPlayers=array();

// S'il n'y a pas de session HT ouverte on ne fait rien
if (isset($_SESSION['HT'])) {

  // Informations club
  $row_club = getDataClubFromHT_usingPHT(); // Récupération des informations du club connecté
  
  // Liste des caractéristiques pour liste déroulante
  $liste_carac = listTypeCaracEntrainable();

  // Si le formulaire a été soumis
  if (isset($_POST['action']) && ($_POST['action']=="submit_iiihelp")) {
    //extract($_POST,EXTR_OVERWRITE);
    // Controle de cohérence du formulaire
    if (!isset($_POST['email']) || ($_POST['email']=="")) {$messageaff.="Vous devez saisir votre adresse mail afin de recevoir les annonces de joueurs en vente.<br />"; $errForm='1';}
    if ($_POST['entrainement_voulu1']=="99") {$messageaff.="Vous devez s&eacute;lectionner au moins un entrainement pour votre souhait n&deg;1.<br />"; $errForm='1';}
    if (($_POST['entrainement_voulu1']!="99") && ($_POST['entrainement_voulu1']==$_POST['entrainement_voulu2'])) {$messageaff.="Vous devez s&eacute;lectionner 2 entrainements diff&eacute;rents.<br />"; $errForm='1';}
    if (($_POST['entrainement_voulu1']!="99") && (!isset($_POST['age_Entrainement1']))) {$messageaff.="Vous devez s&eacute;lectionner au moins une cat&eacute;gorie d'&acirc;ge pour votre souhait n&deg;1.<br />"; $errForm='1';}
    if (($_POST['entrainement_voulu2']!="99") && (!isset($_POST['age_Entrainement2']))) {$messageaff.="Vous devez s&eacute;lectionner au moins une cat&eacute;gorie d'&acirc;ge pour votre souhait n&deg;2.<br />"; $errForm='1';}
    if ($_POST['entrainement_voulu2']=="99") {$_POST['age_Entrainement2']=array();}
    $_POST['$commentaire']=ltrim($_POST['$commentaire']);
  
  } else { // Si le formulaire n'a pas été soumis
  
    
    // Recherche dans la base DTN des informations repreneurs
    $sql = get_iiihelp_repreneurSQL($row_club['idClubHT']);
    $req = $conn->query($sql);
  
    // Si le formulaire n'a pas encore été soumis, on initialise les variables du formulaire
    $_POST['action']            = "";
    if(!$req){
        echo("ERROR REQUEST MYSQL. Please contact Staff Members!");
        exit;
    } elseif ($req->rowCount() == 0) { /* le repreneur n'existe pas dans la base => on initialise le formulaire */
  
      $_POST['email']               ="";
      $_POST['commentaire']         ="";
      $_POST['entrainement_voulu1'] ="99";
      $_POST['entrainement_voulu2'] ="99";
      $_POST['age_Entrainement1']   =array();
      $_POST['age_Entrainement2']   =array();
    } elseif($req->rowCount() == 1){ /* le repreneur existe dans la base => on alimente le formulaire avec ses données */
      $dtn_iiihelp_repreneurSQL   = mysql_fetch_array ($req);
      //echo ("<br />");print_r($dtn_iiihelp_repreneurSQL);echo ("<br />");
      $_POST['email']               = $dtn_iiihelp_repreneurSQL['email'];
      $_POST['commentaire']         = $dtn_iiihelp_repreneurSQL['commentaire'];
      $_POST['entrainement_voulu1'] = $dtn_iiihelp_repreneurSQL['entrainement_voulu1'];
      $_POST['entrainement_voulu2'] = $dtn_iiihelp_repreneurSQL['entrainement_voulu2'];
      $_POST['age_Entrainement1']   = libelleAgeFormulaire($dtn_iiihelp_repreneurSQL['age_voulu1']);
      $_POST['age_Entrainement2']   = libelleAgeFormulaire($dtn_iiihelp_repreneurSQL['age_voulu2']);
      //echo ("<br />");print_r($_POST);echo ("<br />");
    } else { // Plus d'une ligne extraite. Impossible en théorie car il y a une contrainte d'unicité en base
        echo("ERROR UNICITY REPRENEUR. Please contact Staff Members!");
        exit;
    }
  }


  // Si le formulaire a été soumis et ne contient pas d'erreur, on essaie d'insérer le proprio dans la base de données repreneur
  if (($action=="submit_iiihelp") && ($errForm=='0')){
  
    // Initialisation des variables
    $row_iiihelp_repreneur=array();
    $row_clubs_histo=array();
    
    // Traitement des variables de formulaires
  	$Lib_Age1=FormulaireLibelleAge($_POST['age_Entrainement1']);
  
  	if ($_POST['entrainement_voulu2']!="99") {
      $Lib_Age2=FormulaireLibelleAge($_POST['age_Entrainement2']);
    } else {
      $_POST['entrainement_voulu2']=null;
      $Lib_Age2=null;
    }
  
  	if ($row_club['niv_Entraineur'] < 7)
  	{
  		$etat = -1; // alert car entraineur non honorable
  	}
  	else if ($row_club['idPays_fk'] == "5")
  		$etat = 1;
  	else
  		$etat = 0;
  
    
    // Récupération des informations du club
    $row_clubs_histo = getDataClubsHistoFromHT_usingPHT();
    $row_clubs_histo['cree_par']=$row_club['nomUser'];
    $row_clubs_histo['role_createur']='P';
    $row_clubs_histo['Commentaire']="Inscription &agrave; &iexcl;&iexcl;&iexcl;help!";
  
    //insertion ou mise à jour du club
    $idClub=insertionClub($row_club);
    		
    // Insertion HistoClub
    $id_clubs_histo=insertHistoClub($row_clubs_histo);
    
    // Insertion Repreneur iiiHelp
    $row_iiihelp_repreneur['idClubHT']            = $row_club['idClubHT'];
    $row_iiihelp_repreneur['leagueLevel']         = $row_club['leagueLevel'];
    $row_iiihelp_repreneur['email']               = $_POST['email'];
    $row_iiihelp_repreneur['commentaire']         = $_POST['commentaire'];
    $row_iiihelp_repreneur['etat']                = $etat;
    $row_iiihelp_repreneur['entrainement_voulu1'] = $_POST['entrainement_voulu1'];
    $row_iiihelp_repreneur['age_voulu1']          = $Lib_Age1;
    $row_iiihelp_repreneur['entrainement_voulu2'] = $_POST['entrainement_voulu2'];
    $row_iiihelp_repreneur['age_voulu2']          = $Lib_Age2;

    // Insertion HistoClub
    $id_iiihelp_repreneur=insertionRepreneuriiiHelp($row_iiihelp_repreneur);
    
  
    /******************************************************************************/
    /******************************************************************************/
    /*      AFFICHAGE RESULTAT APRES TRAITEMENT FORMULAIRE D'INSCRIPTION          */
    /******************************************************************************/
    /******************************************************************************/
    ?>
    <p class="contenuJustifie">
    <?php switch ($etat)
    {
    	case "1" :
    	case "0" :
    		$messageaff .= "<b><Font color='green' size='2'>".FIN_OK_IIIHELP."</font></b>";
    	break;
    	
    	case "-1" :
    		$messageaff .= "<b><Font color='red' size='2'>".FIN_KO_ENTRAINEUR_IIIHELP."</font></b>";
    	break;
    }
  
    $messageaff.='</font></b>';
    echo ($messageaff);?>
    </p>
  <?php } elseif ($errForm=='1') {
    $messageaff.='</font></b>';
    echo ($messageaff);
  }
}

/******************************************************************************/
/******************************************************************************/
/*      AFFICHAGE DU FORMULAIRE D'INSCRIPTION OU DE CONNEXION                 */
/******************************************************************************/
/******************************************************************************/
if ( isset($_SESSION['HT']) ) {
  include( "h_submitting.php" );
} else {?>
  <div id="contenu">
  <table width="760" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td rowspan="4" width="20">&nbsp;</td>
			<td class="style40" align="left"><br />
        <?php echo OBJECTIF_AUTORISATION_ACCES_IIIHELP;?>
        <br />
        <?php echo EXPLICATION_AUTORISATION_ACCES;?>
        <br />
  		</td>
    </tr>
  	<tr>
    	<td class="style40">
    		<form name="formConnexionHT" method="get" action="">
    		  <input name="mode" type="hidden" value="redirectionHT">
    		  <input type="submit" value="<?php echo TEXTE_BOUTON_AUTORISATION;?>" class="bouton" /> <br /><br />
          <input type="checkbox" name="connexion_permanente" value="1" checked />&nbsp;<?php echo SESS_ACTIVE;?><br />
        </form>
    	</td>
    </tr>
    <tr>
      <td class="detailInfo">
      <img src="images/info.PNG" title="Information" alt="Information" height="20" width="20" />&nbsp;<?php echo EXPLICATION_SESS_ACTIVE;?>
      </td>
    </tr>
  </table>
  </div>
<?php }


include("menu_bas.php");
?>
