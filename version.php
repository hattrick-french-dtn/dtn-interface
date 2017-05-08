<?php
echo PHP_VERSION;
phpinfo();
 /* ignore_user_abort(true);
  ini_set('max_execution_time',3600); // 3600 secondes = 60 minutes -> à la main on se met un timeout de 60 minutes
  ini_set("memory_limit",'128M');

  include($_SERVER['DOCUMENT_ROOT'].'/gestion_session_HT.php'); // Pour récupérer les constantes CONSUMER

  require("../includes/head.inc.php");
  include_once "../includes/serviceEquipes.php";
  include_once "../includes/nomTables.inc.php";
  

  // Création ou ouverture d'un fichier de log
  $filename = $_SERVER['DOCUMENT_ROOT'].'dtn/interface/maj/log/majEquipesAuto.txt';
  $myfile=fopen($filename,'w');
  
  $_SESSION['HT']=creerConnexionHT();  

  fwrite($myfile, "Debut traitement : ".date("d/m/Y H:i:s")."\n");
  fwrite($myfile, "======================================\n");
    
  // Initialisation des variable de la boucle
  if (isset($_GET['nbUpdate'])) {$nbUpdate=$_GET['nbUpdate'];} else {$nbUpdate=0;}
  // On va traiter les clubs par paquets de 100 afin d'éviter les dépassements de mémoire alloué
  // Initialisation des limites d'extraction
  if (isset($_GET['limitDeb'])) {$limitDeb=$_GET['limitDeb'];} else {$limitDeb=1;}
  $limitFin=$limitDeb+99;
  
  if ($limitDeb=1) {
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
    $reqValid= mysql_query($sql) or die(mysql_error()."\n".$sql);
    fwrite($myfile, "Purge $tbl_clubs_histo : ".mysql_affected_rows()." lignes\n");

    $sql = "DELETE FROM $tbl_clubs 
            WHERE
                  NOT EXISTS (SELECT 1 FROM $tbl_joueurs WHERE teamid = $tbl_clubs.idClubHT )
              AND NOT EXISTS (SELECT 1 FROM $tbl_admin WHERE $tbl_admin.idAdminHT = $tbl_clubs.idUserHT AND $tbl_admin.idAdminHT != 0)
              AND NOT EXISTS (SELECT 1 FROM $tbl_iiihelp_repreneur WHERE $tbl_iiihelp_repreneur.idClubHT = $tbl_clubs.idClubHT)";
    $reqValid= mysql_query($sql) or die(mysql_error()."\n".$sql);
    fwrite($myfile, "Purge $tbl_clubs : ".mysql_affected_rows()." lignes\n");
      
    $sql = "DELETE FROM $tbl_clubs_histo_joueurs 
            WHERE
               id_clubs_histo NOT IN (SELECT id_Clubs_Histo FROM $tbl_clubs_histo)
            OR id_joueur NOT IN (SELECT idJoueur FROM $tbl_joueurs)";
    $reqValid= mysql_query($sql) or die(mysql_error()."\n".$sql);
    fwrite($myfile, "Purge $tbl_clubs_histo_joueurs : ".mysql_affected_rows()." lignes\n");
    
    
    fwrite($myfile, "======================================\n");
  }
  
  
  // Extraction de 100 clubs
  $clubsDTN=listClubs($limitDeb,$limitFin);
var_dump($clubsDTN);
  $nbClubs=count($clubsDTN);
echo("nbclub=");
echo($nbClubs);
  while ($nbClubs > 0)
  //while ($nbClubs < 101)
  {
    // Boucle sur les clubs
    for ($i=0;$i<$nbClubs;$i++)
    {
echo ($i."<br />")
      $resuClub=majClub($clubsDTN[$i]['idClubHT'],$clubsDTN[$i]['idUserHT'],$clubsDTN[$i]);
      if ($resuClub["maj"] == true) {$nbUpdate = $nbUpdate + 1;}
      fwrite($myfile, $resuClub["logModif"]);
      fflush($myfile);
      
      $resuClubHisto=majClubHisto($clubsDTN[$i]['idClubHT'],'Maj Auto','D');
      //if ($resuClubHisto != 0) {echo($resuClubHisto["HTML"]);} //else {echo("Pas autorisation.");}
    }
      
    // Extraction des clubs présents dans la base DTN
    unset($clubsDTN);
    $limitDeb=$limitDeb+100;
    $limitFin=$limitFin+100;
    fwrite($myfile,"limitDeb=".$limitDeb."|NbUpdate=".$nbUpdate."\n");
    $clubsDTN=listClubs($limitDeb,$limitFin);
    $nbClubs=count($clubsDTN);
  
    if (round(memory_get_usage()/1024000) > 120) {
      echo("M&eacute;moire insuffisante - Arret du traitement<br />");
      echo("Relance : $url_courante?limiteDeb=$limitDeb&nbUpdate=$nbUpdate<br />");
      fwrite($myfile, "M&eacute;moire insuffisante - Arret du traitement\n");
      fwrite($myfile, "Relance : $url_courante?limiteDeb=$limitDeb&nbUpdate=$nbUpdate");
      fclose($myfile);
      exit;
    }
  }


  fwrite($myfile, "=>Nbre Update=".$nbUpdate."\n");
  fwrite($myfile, "Fin traitement : ".date("d/m/Y H:i:s")."\n");
  fclose($myfile);
  
  // Insertion dans la table ht_maj_auto
  $sql="INSERT INTO $tbl_maj_auto (date_maj,nom_traitement,nbre_maj,nom_script) VALUES (curdate(),'[CLUB] Recherche et MAJ des clubs botifiés',$nbUpdate,'majEquipesAuto.php')";
  $reqValid= mysql_query($sql) or die(mysql_error()."\n".$sql);
  
  exit();*/
?>
