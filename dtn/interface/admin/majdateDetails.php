<?php
ini_set('max_execution_time',600);
/* Mise à jour des semaines d'entraînement */
require_once("../includes/serviceJoueur.php");
require_once("../includes/serviceEquipes.php");
require_once("../includes/serviceListesDiverses.php");
require_once("../includes/serviceMatchs.php");
require_once("../CHPP/config.php");
error_reporting(E_ALL);

// Service réservé aux DTN#
if ($_SESSION['sesUser']["idNiveauAcces_fk"] == 1){

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

?>
<br \>
<table width="50%" style="border:1px solid #C5C7C7" align="center" cellpadding="2" cellspacing="1" rules=COLS>
  <tr <?php
$sqlmajsecteur = "SELECT m.type_maj typedemaj, m.date_majsecteur datedemajsecteur, m.auteur_majsecteur auteurdemajsecteur, p.intitulePosition position
                  FROM ht_maj_secteur m
                  INNER JOIN ht_position p
                  ON p.idPosition = m.secteur
                  ORDER BY id
                  ASC";
$i=1;
if ($i % 2 == 0) {?>bgcolor="#EEEEEE"<?php } else {?>bgcolor="#FFFFFF"<?php }?>>
  <thead>
    <tr>
    <th>Secteur</th>
    <th>Génération</th>
    <th>Dernière mise à jour</th>
    <th>Auteur</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach($conn->query($sqlmajsecteur) as $lSecteur) {?>
      <td > <div align="center"><?=$lSecteur["position"]?></div></td>
      <td > <div align="center"><?=$lSecteur["typedemaj"]?></div></td>
      <td > <div align="center"><?=date("d/m/Y H:i", strtotime($lSecteur["datedemajsecteur"]))?></div></td>
      <td > <div align="center"><?=$lSecteur["auteurdemajsecteur"]?></div></td>
  </tr>
    <?php $i++;
    }?>
  </tbody>
</table>
