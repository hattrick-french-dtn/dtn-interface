<?php 
require_once("../includes/head.inc.php");
require_once("../includes/serviceListesDiverses.php");
require_once("../includes/serviceJoueur.php");
//require_once("../includes/serviceEquipes.php");
require_once("../includes/serviceMatchs.php");
require_once("../fonctions/AccesBase.php"); // fonction de connexion a la base
require_once("../CHPP/config.php");
require_once("../_config/CstGlobals.php"); 
require("../includes/langue.inc.php");


if(!$_SESSION['sesUser']["idAdmin"])
{
	header("location: http://".$_SERVER['SERVER_NAME']."/dtn/interface/index.php?ErrorMsg=Session Expire");
}

if(!isset($ordre)) $ordre = "nomJoueur";
if(!isset($sens)) $sens = "ASC";
if(!isset($lang)) $lang = "FR";
if(!isset($masque)) $masque = 0;
if(!isset($affPosition)) $affPosition = 0;

$infPos = getPosition($_SESSION['sesUser']["idPosition_fk"]);

switch($_SESSION['sesUser']["idPosition_fk"]){

		case "1":
		//gK
		$k = 1;
		$keeperColor = "#9999CC";
		break;
		
		case "2":
		// cD
		$d = 1;
		$defense = 1;
		$defenseColor = "#9999CC";
		break;
		
		case "3":
		// Wg
		$construction = 1;
		$constructionColor = "#CCCCCC";
		$ailier = 1;
		$ailierColor = "#9999CC";
		$defense = 1;
		$defenseColor = "#CCCCCC";
		$passe = 1;
		$passeColor = "#CCCCCC";
		
		$wing = 1;
		$wingoff = 1;
		$wingwtm = 1;

		break;
		case "4":
		//IM
		$m = 1;
		$moff = 1;
		$construction = 1;
		$constructionColor = "#9999CC";
		$defense = 1;
		$defenseColor = "#CCCCCC";
		$passe = 1;
		$passeColor = "#CCCCCC";
		break;
		
		case "5":
		// Fw
				
		$att = 1;
		$passe = 1;
		$passeColor = "#9999CC";
		$buteur = 1;
		$buteurColor = "#9999CC";
		break;
	
		default:
		$font = "<font color = black>";
		$$font = "</font>";
		break;
		
}
switch($_SESSION['sesUser']["idNiveauAcces"]){
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
 require ("../menu/menuMaListe.php");

switch($sens){
  case "ASC":
    $tri = "Tri croissant";
    break;
  case "DESC":
    $tri = "Tri decroissant";
    break;
}

?>
<script language="JavaScript" type="text/JavaScript">
<!--
function ficheDTN(id,url){
document.location='<?=$url?>/joueurs/ficheDTN.php?url='+url+'&id='+id
}

function init(){
var scrollPos = "<?=$scrollPos?>";
document.body.scrollTop = scrollPos;
}//-->
</script>
<style type="text/css">
<!--
.Style1 {color: #FFFFFF}
.Style3 {color: #FF9933}
.Style4 {color: #FF0000}
-->
</style>
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<br />

<?php
if (isset($_SESSION['HT'])) {

  $listeJoueursDTN=getJoueurByDTN($_SESSION['sesUser']["idAdmin"]);

  if(!$listeJoueursDTN){
      echo("Erreur lors de l'extraction des joueurs. Contactez un d&eacute;veloppeurs ou les administrateurs de la DTN.");
      exit;
  } elseif (count($listeJoueursDTN) == 0) {
    echo("Pas de joueurs suivis");
  } else {

  foreach($listeJoueursDTN as $joueur) {
    $listeID[]=$joueur["idHattrickJoueur"];
  }

  // MAJ des joueurs avec chargement des matchs
  unset($resUpdateJoueur);
  $resUpdateJoueur=scanListeJoueurs($listeID,$_SESSION['sesUser']["loginAdmin"],"D",true,true);
  ?>
  


    <p><b>Chargement des donn&eacute;es depuis hattrick. Attendez la fin de l'op&eacute;ration.</b></p>
    <table class="cadre" width="97%">
    <tr class="activ">
      <td> joueur </td>
      <td>age</td>
      <td>forme</td>
      <td>tsi?</td>
      <td>salaire?</td>
      <td>xp?</td>
      <td>vente/blessure/entraineur</td>
      <td>matchid</td>
      <td>etoiles</td>
      <td>poste</td>
    </tr>
    <?php
  
    
    
    for ($j=0;$j<count($resUpdateJoueur);$j++) {


    	echo($resUpdateJoueur[$j]['HTML']);
    	
    	if ($j%11==0 && $j>0){?>
    		</table><br>.
    		<table class="cadre" width="97%">
    		<tr class="activ">
          <td> joueur </td>
          <td>age</td>
          <td>forme</td>
          <td>tsi?</td>
          <td>salaire?</td>
          <td>xp?</td>
          <td>vente/blessure/entraineur</td>
          <td>matchid</td>
          <td>etoiles</td>
          <td>poste</td>
        </tr>
    		<?php
        //flush();
    	}
    
    	flush();
    }?>
    </table></font>
    <p><font color=#229922> Op&eacute;ration termin&eacute;e.</font></p>

  <?php } 
} else {?>
  
  <br />
  <!-- FORMULAIRE AUTORISATION -->
  <div class="ContenuCentrer">
    <br />
    Vous devez etre connect&eacute; &agrave; Hattrick.&nbsp;&nbsp;
    <br />
  
    <?php if ( isset($_SESSION['HT']) ) {?>
      
      Votre compte DTN est li&eacute; &agrave; votre compte Hattrick : <?php echo($_SESSION['nomUser']." [".$_SESSION['idUserHT']."]"); ?>
      
    <?php } else {?>
      
      <form name="formConnexionHT" method="get" action="">
        <input name="mode" type="hidden" value="redirectionHT">
        <input type="submit" value="AUTORISER ACC&Egrave;S CHPP" class="bouton" /> <br /><br />
      </form>
    
    <?php }?>
    <br /> 
  </div>
<?php }?>

</body>
</html>
