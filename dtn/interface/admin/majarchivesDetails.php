<?php
ini_set('max_execution_time',600);
/* Mise a jour des joueurs /matchs xp/transferts  TSI depuis playerdetails quand il semble 
* que ça n'ait pas ete fait par le DTN.    
*/
require_once("../includes/serviceJoueur.php");
require_once("../includes/serviceEquipes.php");
require_once("../includes/serviceListesDiverses.php");
require_once("../includes/serviceMatchs.php");
require_once("../CHPP/config.php");

error_reporting(E_ALL);
    
//Service reserve aux DTN+ et Admin
if (($_SESSION['sesUser']["idNiveauAcces"] == 1)||($_SESSION['sesUser']["idNiveauAcces"] == 2)){
   	
  if (!isset($_REQUEST['checkMatch'])) {$_REQUEST['checkMatch']="off";}
  if (!isset($_REQUEST['choixmaj'])) {$_REQUEST['choixmaj']="";}
       	
  $lstPos = listAllPosition();
  if (!isset($affPosition)) {$affPosition="";}   	
    
  if($affPosition == "") {
  	if ($_SESSION['sesUser']["idPosition_fk"] == ""){
      $affPosition = "0";
  	}else{
      $affPosition = $_SESSION['sesUser']['idPosition_fk'];
  	}
  }
} else {
	echo("Fonction impossible.");
	return;
}

$lstBehaviour=list_behaviour();
$lstRole=list_role();

/******************************************************************************/
/*      DECLARATION FONCTION                                                  */
/******************************************************************************/
function checkNumberMatchPoste($season,$week,$maBase,$poste){
  $sql = "SELECT	count(1) 
          FROM 
            ht_perfs_individuelle 
  		      LEFT JOIN ht_joueurs ON id_Joueur = idHattrickJoueur 
  		    WHERE 
          week='$week' 
          AND season='$season' 
          AND ht_posteAssigne ='$poste'";

  $resMatch = $maBase->select($sql);
  $countMatch  = $resMatch[0][0];
  return $countMatch; 
}  
// This function check number of matchs  in database for a specific week and a specific season  
function checkNumberMatch($season,$week,$maBase){
  $sql =  "select	count(1) from ht_perfs_individuelle where week='$week' and season='$season' LIMIT 1";

  $resMatch = $maBase->select($sql);
  $countMatch  = $resMatch[0][0];
  return $countMatch; 
}


function checkNumberPoste($maBase,$poste){

  $sql =  "select	count(1) from ht_joueurs where (ht_posteAssigne ='$poste' or ht_posteAssigne ='0') and archiveJoueur='1' and joueurActif='0' ";

 	$resPlayers= $maBase->select($sql);
	$countPlayers  = $resPlayers[0][0];
	return $countPlayers; 
}



/******************************************************************************/
/*      AFFICHAGE CONTENU PAGE                                                */
/******************************************************************************/
?>
<br />
<center>
<a href="index.php?redirect=matchsOverview.php"><u>Acc&eacute;der aux options de gestion/administration des matchs</u></a>
</center>
<?php
if (!isset($_SESSION['HT'])) {
	$todaySeason=getSeasonWeekOfMatch(mktime(0,0,0,date('m'), date('d'),date('Y')));
	$mseason=$todaySeason["season"];
	$mweek=$todaySeason["week"];
	$compteur=0;
?>
  <br />
	 <b>Semaine : <?=$mweek?></b> d&eacute;j&agrave; : <?=checkNumberMatch($mseason,$mweek,$maBase)?> matchs en base.<br />
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
	 Gardiens <?=$nbg?> sur <?=$nbtg?>  -> <?php  printf ("%.2f", $pctg); ?> % <br />
	 Defenseurs <?=$nbd?> sur <?=$nbtd?> -> <?php printf ("%.2f", $pctd); ?> % <br />
	 Ailiers <?=$nba?> sur <?=$nbta?> -> <?php    printf ("%.2f", $pcta); ?> % <br />
	 Milieux <?=$nbm?> sur <?=$nbtm?> -> <?php    printf ("%.2f", $pctm); ?> % <br />
	 Attaquants <?=$nbf?> sur <?=$nbtf?>  -> <?php printf ("%.2f", $pctf); ?> % <br />
<hr />
<br />

<!-- FORMULAIRE AUTORISATION -->
<div class="ContenuCentrer">
  <br />
  Vous devez etre connect&eacute; &agrave; Hattrick.&nbsp;&nbsp;
  <br />
<?php
if ( isset($_SESSION['HT']) ) { ?>

    Votre compte DTN est li&eacute; &agrave; votre compte Hattrick : <?php echo($_SESSION['nomUser']." [".$_SESSION['idUserHT']."]"); ?>

<?php } else {?>
    
    <form name="formConnexionHT" method="get" action="">
      <input name="mode" type="hidden" value="redirectionHT">
      <input type="submit" value="AUTORISER ACC&Egrave;S CHPP" class="bouton" /> <br /><br />
    </form>
<?php }?>
<br /> 
</div>
<?php
} else {
  ?><p>
  <center>  
  <table border=1 width=90% >  
  <tr>
  <td align="left">
    <img src="../images/greenball.jpg"> Votre session hattrick est active ! 
    <br />
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
      <td height="20" >
        <form name="majPlayerDetails" method="post" action="<?=$_SERVER['REQUEST_URI']?>">
        <div align="center"><font color="blue">Mise &agrave; jour des joueurs/TSI/match/xp/club</font></div>
        <br />
        <b>Archiv&eacute;s du secteur de jeu : </b>
					
        <?php if (($affPosition!=0) && ($_SESSION['sesUser']["idNiveauAcces"] == 2)  ) { 
          // Si c'est un DTN+ qui est connecté et qu'il a un secteur ?>
					<INPUT TYPE="hidden" NAME="ht_posteAssigne" VALUE="<?=$affPosition?>" >
					  <b><font color="#CC2233"><?=$lstPos[$affPosition-1]["descriptifPosition"]?></font></b><br />
				<?php } elseif (isset($_REQUEST['ht_posteAssigne'])) {
				  // $_REQUEST['ht_posteAssigne'] est défini seulement lorsqu'on a chargé déjà une page de joueur?>
				  <INPUT TYPE="hidden" NAME="ht_posteAssigne" VALUE="<?=$_REQUEST['ht_posteAssigne']?>" >
          <?php if ($_REQUEST['ht_posteAssigne']==0) {?>
            <b><font color="#CC2233">Non Assign&eacute;</font></b>
          <?php } else {?>
						<b><font color="#CC2233"><?=$lstPos[($_REQUEST['ht_posteAssigne']-1)]["descriptifPosition"]?></font></b>
          <?php }
        } else { ?>
					<SELECT NAME="ht_posteAssigne" SIZE=1>
						<OPTION VALUE="0" SELECTED>Non assign&eacute;</OPTION>
						<OPTION VALUE="1">Gardien</OPTION>
						<OPTION VALUE="2">D&eacute;fenseur</OPTION>
						<OPTION VALUE="4">Milieu de terrain</OPTION>
						<OPTION VALUE="3">Ailier</OPTION>
						<OPTION VALUE="5">Attaquant</OPTION>
					</SELECT>
				<?php }
         
				if (!isset($_REQUEST['ht_posteAssigne'])) {?>
					<br />
    <p>Mettre &agrave; jour les donn&eacute;es des archivés... (aucune s&eacute;lection = tous les &acirc;ges)<br />
    <ul>
        <li><input type="radio" name="choixmaj" value="u20" >
    <label for="u20">U20 (17-20 ans)</label><br />
        <li><input type="radio" name="choixmaj" value="a" >
    <label for="a">A (21 ans et plus)</label><br />
        <li><input type="radio" name="choixmaj" value="selecu20" > 
    <label for="selecu20">S&eacute;lectionnables u20 uniquement (19-20 ans)</label><br />
        <li><input type="radio" name="choixmaj" value="seleca" >
    <label for="seleca">S&eacute;lectionnables A uniquement (27 ans et plus)</label>
    </ul>
    Charger aussi les matchs ? <input type="checkbox" name="checkMatch" checked >
    </p><br />
		  <?php }?>
      </td></tr>
      <tr><td>
        <div align="center">
        <?php if(!isset($_REQUEST['ht_posteAssigne'])) { // Si on a pas encore soumis le formulaire donc pas encore chargé de maj ?>
          <br />
          Nombre de joueurs maximum par page : <input type="text" name="nbrePlayersMax" value="20" />
          <br />
          <br />
          <input type="submit" name="Submit" value="Charger les données" />
        <?php } ?>
        </div>
      </form>
      </td>
      </tr>
      <!-- AFFICHAGE DES JOUEURS -->
      <tr>
      <td align="center">
        <?php //Si un update a ete demande
        //echo ("ht_posteassigne=".$_REQUEST['ht_posteAssigne']);
        //echo ("<br />nbrePlayersMax=".$_REQUEST['nbrePlayersMax']);
        if(isset($_REQUEST['ht_posteAssigne'])) {
        
          // Initialisation des variables
         	if(!isset($_REQUEST['nbrePlayersMax'])) { // Transmis par le formulaire
         		$_REQUEST['nbrePlayersMax']=50;
         	}
         	if(!isset($_GET['startingPlayer'])) { // Transmis dans l'URL sauf au premier chargement de la page
         		$_GET['startingPlayer']=0; 
         	}
      		// Check Match
      		if ($_REQUEST['checkMatch']=="on") {
				print " <p> CHECK MATCH : " .$_REQUEST['checkMatch']. " </p>";
      		} else {
				print " <p> CHECK MATCH : off </p>";
      		}
            // Choix MàJ
			print " <p> CHOIX MAJ : " .$_REQUEST['choixmaj']. " </p>";
            
		    $todaySeason=getSeasonWeekOfMatch(mktime(0,0,0,date('m'), date('d'),date('Y')));

			// Extraction des joueurs
            $SqlAgeJoueur=getCalculAgeAnneeSQL(); // Récupère l'âge (année) dans la BDD

        if ($_REQUEST['choixmaj']=="u20") {
      		$sql = "SELECT $tbl_joueurs.idHattrickJoueur
                  FROM $tbl_joueurs 
      		        WHERE 
                      ($tbl_joueurs.ht_posteAssigne = '".$_REQUEST['ht_posteAssigne']."'
                    OR
                        $tbl_joueurs.ht_posteAssigne = '0')
      		        AND $tbl_joueurs.archiveJoueur = '1' 
                    AND $SqlAgeJoueur < '21'
                    ORDER BY idHattrickJoueur DESC,prenomJoueur,nomJoueur 
                  LIMIT ".$_GET['startingPlayer'].",".$_REQUEST['nbrePlayersMax'];
        } else if ($_REQUEST['choixmaj']=="a") {          
            $sql = "SELECT $tbl_joueurs.idHattrickJoueur
                  FROM $tbl_joueurs 
      		        WHERE 
                      ($tbl_joueurs.ht_posteAssigne = '".$_REQUEST['ht_posteAssigne']."'
                    OR
                        $tbl_joueurs.ht_posteAssigne = '0')
      		        AND $tbl_joueurs.archiveJoueur = '1' 
                    AND $SqlAgeJoueur >= '21'
                    ORDER BY idHattrickJoueur DESC,prenomJoueur,nomJoueur 
                  LIMIT ".$_GET['startingPlayer'].",".$_REQUEST['nbrePlayersMax'];
        } else if ($_REQUEST['choixmaj']=="selecu20") {          
            $sql = "SELECT $tbl_joueurs.idHattrickJoueur
                  FROM $tbl_joueurs 
      		        WHERE 
                      ($tbl_joueurs.ht_posteAssigne = '".$_REQUEST['ht_posteAssigne']."'
                    OR
                        $tbl_joueurs.ht_posteAssigne = '0')
      		        AND $tbl_joueurs.archiveJoueur = '1' 
                    AND $SqlAgeJoueur BETWEEN '19' AND '20'
                    ORDER BY idHattrickJoueur DESC,prenomJoueur,nomJoueur 
                  LIMIT ".$_GET['startingPlayer'].",".$_REQUEST['nbrePlayersMax'];
        } else if ($_REQUEST['choixmaj']=="seleca") {          
            $sql = "SELECT $tbl_joueurs.idHattrickJoueur
                  FROM $tbl_joueurs 
      		        WHERE 
                      ($tbl_joueurs.ht_posteAssigne = '".$_REQUEST['ht_posteAssigne']."'
                    OR
                        $tbl_joueurs.ht_posteAssigne = '0')
      		        AND $tbl_joueurs.archiveJoueur = '1' 
                    AND $SqlAgeJoueur >= '27'
                    ORDER BY idHattrickJoueur DESC,prenomJoueur,nomJoueur 
                  LIMIT ".$_GET['startingPlayer'].",".$_REQUEST['nbrePlayersMax'];
        } else {
            $sql = "SELECT $tbl_joueurs.idHattrickJoueur
                  FROM $tbl_joueurs 
      		        WHERE 
                      ($tbl_joueurs.ht_posteAssigne = '".$_REQUEST['ht_posteAssigne']."'
                    OR
                        $tbl_joueurs.ht_posteAssigne = '0')
      		        AND $tbl_joueurs.archiveJoueur = '1' 
                    ORDER BY idHattrickJoueur DESC,prenomJoueur,nomJoueur 
                  LIMIT ".$_GET['startingPlayer'].",".$_REQUEST['nbrePlayersMax'];
        }

			$req= $conn->query($sql);
          
			if(!$req){
				echo("Erreur lors de l'extraction des joueurs. Contactez un d&eacute;veloppeurs ou les administrateurs de la DTN.");
				exit;
			} elseif ($req->rowCount() == 0) {
				echo("Pas de joueur trouv&eacute;");
			} else {
				$i=0;
				while($i<$req->rowCount()) {
					$listeJoueursDTN[$i] = $req->fetch(PDO::FETCH_ASSOC);
					$i++;
				}
				$req=NULL;
            
            // Création liste id joueur à scanner
            foreach($listeJoueursDTN as $joueur) {
				$listeID[]=$joueur["idHattrickJoueur"];
            }

            $scanMatch=false;
            if (isset($_REQUEST['checkMatch']) && $_REQUEST['checkMatch']=="on") { 
				$scanMatch=true; 
            }

            // MAJ des joueurs avec chargement des matchs si case cochée
            unset($resUpdateJoueur);
            $resUpdateJoueur=scanListeJoueurs($listeID,$_SESSION['sesUser']["loginAdmin"],"D",true,$scanMatch);

            ?>

            <p><b>Chargement des donn&eacute;es depuis hattrick. Attendez la fin de l'op&eacute;ration.</b></p>
            <table class="cadre" width="97%">
            <tr class="activ">
              <td>JOUEUR</td>
              <td>AGE</td>
              <td>FORME</td>
              <td>TSI</td>
              <td>SALAIRE</td>
              <td>XP</td>
              <td>VENTE/BLESSURE/ENTR.</td>
              <td>MATCH(ID)</td>
              <td>ETOILES</td>
              <td>POSTE</td>
            </tr>
            <?php

            for ($j=0;$j<count($resUpdateJoueur);$j++) {

				echo($resUpdateJoueur[$j]['HTML']);

            	if ($j%20==0 && $j>0){?>
            		</table><br />.
            		<table class="cadre" width="97%">
            		<tr class="activ">
                        <td>JOUEUR</td>
                        <td>AGE</td>
                        <td>FORME</td>
                        <td>TSI</td>
                        <td>SALAIRE</td>
                        <td>XP</td>
                        <td>VENTE/BLESSURE/ENTR.</td>
                        <td>MATCH(ID)</td>
                        <td>ETOILES</td>
                        <td>POSTE</td>
                </tr>
            	<?php }

              flush();

              if ($j >= $_REQUEST['nbrePlayersMax']){
                break;
              }
            } // Fin Boucle
            ?>

          </table>
          </font>
          <br />
          <font color=#229922> Op&eacute;ration termin&eacute;e.</font><br />
          <?php if ($j==$_REQUEST['nbrePlayersMax']) {
            $newLimit=$_GET['startingPlayer']+$_REQUEST['nbrePlayersMax'];
            $lien="index2.php?ht_posteAssigne=".$_REQUEST['ht_posteAssigne']."&nbrePlayersMax=".$_REQUEST['nbrePlayersMax']."&startingPlayer=$newLimit&checkMatch=".$_REQUEST['checkMatch']."&choixmaj=".$_REQUEST['choixmaj'];?>
            <center>
        		<font color=#red> Attention ! Il reste probablement encore des joueurs &agrave; mettre &agrave; jour ! (nombre d'update max : <?php echo($_REQUEST['nbrePlayersMax']);?>)</font><p>
        		<font color=#222299>
            &gt;&gt; <a href="<?php echo($lien);?>">Continuer les mises &agrave; jour </a>&lt;&lt;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            &gt;&gt; <a href="<?php echo($lien);?>&modemajauto=1">Mode automatique de mise &agrave; jour </a>&lt;&lt;
            </font>
            </center>
            <br />
            <?php if (isset($_GET['modemajauto']) && $_GET['modemajauto']==1) {?>
              <script language="JavaScript" type="text/javascript">
          		function recharge()
          		{
          			document.location.href = "<?=$lien?>&modemajauto=1";
          		}

          		setTimeout("recharge()",3000);
              </script>
            <?php }
          }
        }
      }?>
    </td>
    </tr>
    </table>
  </td>
  </tr>
</table>
</center>
<?php }?>