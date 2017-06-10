<?php
  ignore_user_abort(true);
  ini_set('max_execution_time',3600); // 3600 secondes = 60 minutes -> à la main on se met un timeout de 60 minutes
  ini_set("memory_limit",'256M');

  include($_SERVER['DOCUMENT_ROOT'].'/gestion_session_HT.php'); // Pour récupérer les constantes CONSUMER

  require("../includes/head.inc.php");
  include_once "../includes/serviceEquipes.php";
  include_once "../includes/nomTables.inc.php";
  
  echo "Initial Memory Usage: " . memory_get_usage() . "<br>\n";
  
  // Création ou ouverture d'un fichier de log
  $filename = $_SERVER['DOCUMENT_ROOT'].'/dtn/interface/maj/log/majEquipesAuto.txt';
  $myfile=fopen($filename,'w');
  
  $_SESSION['HT']=creerConnexionHT();  

  fwrite($myfile, "Debut traitement : ".date("d/m/Y H:i:s")."\n");
  fwrite($myfile, "======================================\n");

  // Initialisation des variable de la boucle
  if (isset($_GET['nbUpdate'])) {$nbUpdate=$_GET['nbUpdate'];} else {$nbUpdate=0;} // _GET nb update au cas ou j'implémenterai la relance du traitement
  
  // Initialisation des limites d'extraction
  if (isset($_GET['limitDeb'])) {$limitDeb=$_GET['limitDeb'];} else {$limitDeb=1;}
  
  // On va traiter les clubs par paquets afin d'éviter les dépassements de mémoire alloué
  $nbClubExtract=100;

  if ($limitDeb == 1) {
    $sql = "DELETE FROM $tbl_clubs_histo 
            WHERE
              idClubHT IN ( 
                            SELECT  distinct
                                idClubHT 
                            FROM $tbl_clubs C
                            WHERE
                                NOT EXISTS (SELECT 1 FROM $tbl_joueurs WHERE teamid = C.idClubHT )
                            AND NOT EXISTS (SELECT 1 FROM $tbl_admin WHERE idAdminHT = C.idUserHT AND idAdminHT != 0)
                            AND NOT EXISTS (SELECT 1 FROM $tbl_iiihelp_repreneur WHERE idClubHT = C.idClubHT)
                          )";
    $reqValid = $conn->exec($sql);
    fwrite($myfile, "Purge $tbl_clubs_histo : ".$reqValid." lignes\n");
    unset($sql);

    $sql = "DELETE FROM $tbl_clubs 
            WHERE
                  NOT EXISTS (SELECT 1 FROM $tbl_joueurs WHERE teamid = $tbl_clubs.idClubHT )
              AND NOT EXISTS (SELECT 1 FROM $tbl_admin WHERE $tbl_admin.idAdminHT = $tbl_clubs.idUserHT AND $tbl_admin.idAdminHT != 0)
              AND NOT EXISTS (SELECT 1 FROM $tbl_iiihelp_repreneur WHERE $tbl_iiihelp_repreneur.idClubHT = $tbl_clubs.idClubHT)";
    $reqValid = $conn->exec($sql);
    fwrite($myfile, "Purge $tbl_clubs : ".$reqValid." lignes\n");
    unset($sql);
      
    $sql = "DELETE FROM $tbl_clubs_histo_joueurs 
            WHERE
               id_clubs_histo NOT IN (SELECT id_Clubs_Histo FROM $tbl_clubs_histo)
            OR id_joueur NOT IN (SELECT idJoueur FROM $tbl_joueurs)";
    $reqValid = $conn->exec($sql);
    fwrite($myfile, "Purge $tbl_clubs_histo_joueurs : ".$reqValid." lignes\n");
    unset($sql);
    
    fwrite($myfile, "======================================\n");
  }

  // Extraction de 100 clubs
  if (isset($clubsDTN)) unset($clubsDTN);
  $clubsDTN=listClubs($limitDeb,$nbClubExtract);
  $nbClubs=count($clubsDTN);

  while ($nbClubs > 0) {
  //while (($nbClubs > 0) && ($limitDeb<1000)) { // Pour debug

    // Boucle sur les clubs
    $i=0;
    for ($i=0;$i < $nbClubs;$i++)
    {
      $resuClub=majClub($clubsDTN[$i]['idClubHT'],$clubsDTN[$i]['idUserHT'],$clubsDTN[$i]);

      if ($resuClub["maj"] == true) {$nbUpdate = $nbUpdate + 1;}
      fwrite($myfile, $resuClub["logModif"]);
      fflush($myfile);
      
      $resuClubHisto=majClubHisto($clubsDTN[$i]['idClubHT'],'Maj Auto','D');
      //if (isset($resuClubHisto["HTML"])) {echo($resuClubHisto["HTML"]);} 
      
      unset($resuClub);
      unset($resuClubHisto);
    }
    // Libération de mémoire
    unset($i);
    unset($clubsDTN);
    unset($nbClubs);
    
    // Extraction des clubs présents dans la base DTN
    $limitDeb=$limitDeb+$nbClubExtract;
    fwrite($myfile,"limitDeb=".$limitDeb."|NbUpdate=".$nbUpdate."\n");
    $clubsDTN=listClubs($limitDeb,$nbClubExtract);
    $nbClubs=count($clubsDTN);
    
    //echo("<br />mem=".$limitDeb."-".round(memory_get_usage()/1024));

    if (round(memory_get_usage()/1024000) > 200) {
      echo("M&eacute;moire insuffisante - Arret du traitement<br />");
      echo("Relance : $url_courante?limiteDeb=$limitDeb&nbUpdate=$nbUpdate<br />");
      fwrite($myfile, "M&eacute;moire insuffisante - Arret du traitement\n");
      fwrite($myfile, "Relance : $url_courante?limiteDeb=$limitDeb&nbUpdate=$nbUpdate");
      fclose($myfile);
      exit;
    }
  }
  echo ('<br />fin<br />');
  echo "<b>Max Peak Memory Usage: " . memory_get_peak_usage() . "</b><br>\n";

  fwrite($myfile, "=>Nbre Update=".$nbUpdate."\n");
  fwrite($myfile, "Fin traitement : ".date("d/m/Y H:i:s")."\n");
  fclose($myfile);
  
  // Insertion dans la table ht_maj_auto
  $sql="INSERT INTO $tbl_maj_auto (date_maj,nom_traitement,nbre_maj,nom_script) VALUES (curdate(),'[CLUB] Recherche et MAJ des clubs botifiés',$nbUpdate,'majEquipesAuto.php')";
  $reqValid= $conn->exec($sql);

?>
