<?php 
require_once("../includes/head.inc.php");
require("../includes/serviceDTN.php");
require("../includes/serviceJoueur.php");

if(!$sesUser["idAdmin"])
{
	header("location: ../index.php?ErrorMsg=Session Expiree");
}
?>

<link href="../css/ht.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<?php
switch($sesUser["idNiveauAcces"]){
		case "1":
		require("../menu/menuAdmin.php");
		require("../menu/menuAdminGestion.php");
		break;
		
		case "2":
		require("../menu/menuSuperviseur.php");
		require("../menu/menuSuperviseurGestion.php");
		break;

		
		case "4":
		require("../menu/menuCoach.php");
		exit;
		break;
		
		default;
		exit;
		break;

}

$huit = 60 * 60 * 24 * 8; //time_0
$quinze = 60 * 60 * 24 * 15; //time_1
$trente = 60 * 60 * 24 * 30; //time_2
$twomonths = 60 * 60 * 24 * 60; //time_3
$fourmonths = 60 * 60 * 24 * 120; //time_4
		  
// Date du jour
$mkday = mktime(0,0,0,date('m'), date('d'),date('Y'));

$infAdmin = getDTN($_GET['dtn']);
$lstJoueur = getJoueurByDTN($_GET['dtn']);
$_SESSION['ListeFicheResume']=$lstJoueur;
?>
<title>DTN</title>
<br />
<br />
&nbsp;&nbsp;&nbsp;Export Fiche R&eacute;sum&eacute; des joueurs de la page :&nbsp;&nbsp;             
<a href="../joueurs/ficherecupchoix.php?origine=<?php echo "selection"?>">
<img border=1 src="../images/jst.bmp" title="Exporter le r&eacute;sultat affich&eacute; dans la page sous forme d'une fiche r&eacute;sum&eacute; globale"></a>
<br />
<br />
<table width="1200" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
  <td height="55" ><div align="center">
    <table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#000000">
      <tr>
      <td height="20" bgcolor="#000000"><div align="center"><font color="#FFFFFF">Liste des joueurs suivi par <?=$infAdmin["loginAdmin"]?></font></div></td>
      </tr>
	  <tr><td height="20" bgcolor="#000000"><div align="center"><font color="#FFFFFF">
		  <?php if ($sesUser["idNiveauAcces"] ==1 || ($sesUser["idNiveauAcces"] ==2 && $sesUser["idPosition_fk"]==$infAdmin["idPosition_fk"] )) { ?>
	  <form method="post" action="../maliste/miseajour.php">
      <input type="hidden" name="dtn" value= <?=$_GET['dtn']?> />
      <input type="submit" value="Mettre &agrave; jour la liste sur Hattrick" />
                </form>
		  <?php
          }
          ?>
      <form method="post" action="../maliste/dernieresmodifs.php">
      <input type="hidden" name="dtn" value= <?=$_GET['dtn']?> />
      <input type="submit" value="Derni&egrave;res modifications" />
                </form></div></td>
	  </tr>
      <tr>
      <td><div align="center">
        <table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor=lightgrey>
        <tr>
          <td width="300"><div align="left"><strong> &nbsp;(id) Club</strong></div></td>
          <td width="100"><div align="left"><strong>Valeur HTMS</strong></div></td>
          <td width="100"><div align="left"><strong>Potentiel HTMS</strong></div></td>
          <td width="100"><div align="left"><strong>Entrainement</strong></div></td>
          <td width="26"><div align="left"><strong>Int</strong></div></td>
          <td width="26"><div align="left"><strong>End</strong></div></td>
          <td width="26"><div align="left"><strong>Entr.</strong></div></td>
          <td width="26"><div align="left"><strong>Adj</strong></div></td>
          <td width="26"><div align="left"><strong>Doc</strong></div></td>
          <td width="26"><div align="left"><strong>PP</strong></div></td>
          <td width="300"><div align="left"><strong> &nbsp;(id) Joueur</strong></div></td>
          <td width="60" ><div align="left"><strong> &nbsp;Age</strong></div></td>
          <td width="150"><div align="center"><strong>&nbsp;Derni&egrave;re mise &agrave; jour DTN</strong></div></td>
          <td><div align="center"><strong><CENTER>Voir la fiche</CENTER> </strong></div></td>
        </tr>
			      
			  <?php
			  $j=0;
        while($j<count($lstJoueur)) {
          if($j%2 == 1) $bgcolor = "#e8e8e8"; else $bgcolor="#ffffff";
			 	 	$date = explode("-",$lstJoueur[$j]["dateDerniereModifJoueur"]);
          $mkJoueur =  mktime(0,0,0,$date[1],$date[2],$date[0]); 
          $datesaisie = explode("-",$lstJoueur[$j]["dateSaisieJoueur"]);
          $mkSaisieJoueur= mktime(0,0,0,$datesaisie[1],$datesaisie[2],$datesaisie[0]);
          if ($mkSaisieJoueur>$mkJoueur){
            $datemaj=$mkSaisieJoueur;
          }else{
            $datemaj=$mkJoueur;
          }
			
          $img_nb=0;
          if ($datemaj >$mkday -$huit){
            $img_nb=0;
            $strtiming="moins de 8 jours";	
          }else if ($datemaj >$mkday -$quinze){
            $img_nb=1;
            $strtiming="moins de 15 jours";
          }else if ($datemaj >$mkday -$trente){
            $img_nb=2;
            $strtiming="moins de 30 jours";
          }else if ($datemaj >$mkday -$twomonths){
            $img_nb=3;
            $strtiming="moins de 2 mois";
          }else if ($datemaj >$mkday -$fourmonths){
            $img_nb=4;
            $strtiming="moins de 4 mois";
          }else{
            $img_nb=5;
            $strtiming="plus que 4 mois";
          }
			 
          // Date de la dernier modif de ce joueur
          $zealt=" Date dtn : ".$lstJoueur[$j]["dateDerniereModifJoueur"].
					"<br /> Date proprio : ".$lstJoueur[$j]["dateSaisieJoueur"].
					"<br /> [ Mis &agrave; jour il y a  ".round(($mkday - $datemaj)/(60*60*24) )." jours ]";?>

    <?php
		$intensite="-";
		$endurance="-";
		$adjoints="-";
		$medecin="-";
		$physio="-";
		$libelle_type_entrainement="-";
		$niv_Entraineur="-";
		
		if (isset($lstJoueur[$j]["idClubHT"])) {
			$sql2 = "select * from $tbl_clubs_histo A left join $tbl_type_entrainement2 on idEntrainement = id_type_entrainement where idClubHT = ".$lstJoueur[$j]["idClubHT"]." order by date_histo desc";
			$req2 = $conn->query($sql2);
			$ligne2 = $req2->fetch(PDO::FETCH_ASSOC);
			if ($ligne2) extract($ligne2);
        
			$sql3 = "select * from $tbl_clubs where idClubHT = ".$lstJoueur[$j]["idClubHT"];
			$req3 = $conn->query($sql3);
			$ligne3 = $req3->fetch(PDO::FETCH_ASSOC);
			if ($ligne3) extract($ligne3);
            
            // Extraction statut du joueur à la dernière MàJ (en vente ou non)
            $sql= "SELECT transferListed FROM $tbl_joueurs_histo
                   WHERE id_joueur_fk=".$lstJoueur[$j]["idHattrickJoueur"]." 
                   ORDER BY date_histo DESC LIMIT 1";
            $req = $conn->query($sql);
            $ligne = $req->fetch(PDO::FETCH_ASSOC);
            if (is_array($ligne))
            extract($ligne);
            
            // HTMS du joueur    
            $ageetjours = ageetjour($lstJoueur[$j]["datenaiss"]);
            $tabage = explode(" - ",$ageetjours);
            $htms = htmspoint($tabage[0], $tabage[1], $lstJoueur[$j]["idGardien"], $lstJoueur[$j]["idDefense"], $lstJoueur[$j]["idConstruction"], $lstJoueur[$j]["idAilier"], $lstJoueur[$j]["idPasse"], $lstJoueur[$j]["idButeur"], $lstJoueur[$j]["idPA"]);
		}
    ?>

          <tr bgcolor="<?=$bgcolor?>">
            <td>
            <?php if (existAutorisationClub($lstJoueur[$j]["idClubHT"],null)==false) {?>
              <img height="12" src="../images/non_autorise.JPG" title="Ce club n'a pas autoris&eacute; la DTN &agrave; acc&eacute;der &agrave; ses donn&eacute;es">
            <?php } else {?>
              <img height="12" src="../images/Autorise.PNG" title="Ce club a autoris&eacute; la DTN &agrave; acc&eacute;der &agrave; ses donn&eacute;es">
            <?php if ($transferListed==1) {?><img height="12" src="../images/enVente.JPG" title="Plac&eacute; sur la liste des transferts"><?php }?>
            <?php }
				if (isset($lstJoueur[$j]["idClubHT"])) {
			?>
            &nbsp;<a href ="../clubs/fiche_club.php?idClubHT=<?=$lstJoueur[$j]["idClubHT"]?>">(<?php echo($lstJoueur[$j]["idClubHT"]);?>)&nbsp;<?php echo($lstJoueur[$j]["nomClub"]);?></a>
			<?php
				} else { 
					echo($lstJoueur[$j]["nomClub"]);
				}
			?>
            </td>
            <td width="20"> <div align="center"><?=$htms["value"]?></div></td>
            <td width="20"> <div align="center"><?=$htms["potential"]?></div></td>
            <td width="20"> <div align="center"><?=$libelle_type_entrainement?></div></td>
            <td width="20"> <div align="center"><?=$intensite?></div></td>
            <td width="20"> <div align="center"><?=$endurance?></div></td>
            <td width="20"> <div align="center"><?=$niv_Entraineur?></div></td>
            <td width="20"> <div align="center"><?=$adjoints?></div></td>
            <td width="20"> <div align="center"><?=$medecin?></div></td>
            <td width="20"> <div align="center"><?=$physio?></div></td>
            <td>&nbsp;(<?php echo($lstJoueur[$j]["idHattrickJoueur"]);?>)&nbsp;<?php echo ($lstJoueur[$j]["prenomJoueur"]." ".$lstJoueur[$j]["nomJoueur"]);?><?php if (isset($l["surnomJoueur"])) echo " (".$l["surnomJoueur"].")"; ?>
                &nbsp;<img src="../images/time_<?=$img_nb?>.gif" onmouseover="return escape('<?=$zealt?>')" ></td>
            <td><?=$lstJoueur[$j]["ageJoueur"]." - ".$lstJoueur[$j]["jourJoueur"]?></td>
            <td><CENTER> <?=($lstJoueur[$j]["dateDerniereModifJoueur"])?></CENTER></td>
            <td><CENTER>
                <!-- Correction href par musta56 le 30/07/2009 pour bug http://www.ht-fff.org/bug/view.php?id=95-->
                <a href ="../joueurs/fiche.php?id=<?=$lstJoueur[$j]["idJoueur"]?>">Voir </a>
                </CENTER>
            </td>
          </tr>
			      
          <?php 
          $j++;
        }?>
			      
			  </table>
      </div>
      </td>
      </tr>
    </table>
  </div>
  </td>
  </tr>
</table>
  <br />
  <br />

<table width="450"  border="0" align="center" cellspacing=0 >
    <tr>
      <td colspan=5 width="450" ><center>L&eacute;gende : </center></td>
      
    </tr>
    <tr>
      <td><img src="../images/time_0.gif"></td>
      <td>Joueur mis &agrave; jour r&eacute;cemment </td>
      <td width="1" bgcolor="#000000"></td>
      <td>&nbsp;<img src="../images/time_3.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 30 jours </td>
    </tr>
    <tr>
      <td><img src="../images/time_1.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 8 jours </td>
      <td width="1" bgcolor="#000000"></td>
      <td>&nbsp;<img src="../images/time_4.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 60 jours </td>
    </tr>
    <tr>
      <td><img src="../images/time_2.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 15 jours </td>
      <td width="1" bgcolor="#000000"></td>
      <td>&nbsp;<img src="../images/time_5.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 120 jours </td>
    </tr>
  </table>

<script language="JavaScript" type="text/javascript" src="../includes/javascript/tooltips.js"></script>

</body>
</html>