<?php
  ignore_user_abort(true);
  ini_set('max_execution_time',3600); // 3600 secondes = 60 minutes -> à la main on se met un timeout de 60 minutes
  ini_set("memory_limit",'256M');

  include($_SERVER['DOCUMENT_ROOT'].'/gestion_session_HT.php'); // Pour récupérer les constantes CONSUMER
  
  require("../includes/head.inc.php");
  require("../includes/nomTables.inc.php");
  require_once "../fonctions/AccesBase.php";
  require_once "../fonctions/HT_Client.php";
  require_once "../_config/CstGlobals.php"; 
  require("../includes/serviceJoueur.php");
  require("../includes/serviceEquipes.php");
  require("../includes/serviceMatchs.php");

  echo "Initial Memory Usage: " . memory_get_usage() . "<br>\n";
  
  // Création ou ouverture d'un fichier de log
  $filename = $_SERVER['DOCUMENT_ROOT'].'/dtn/interface/maj/log/majJoueursArchives.txt';
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
  
  
  // Extraction de 100 joueurs archivés présents dans la base DTN
  if (isset($joueurDTN)) unset($joueurDTN);
  $joueurDTN=getListeJoueursArchives($limitDeb,$nbClubExtract);
  $nbJoueurs=count($joueurDTN);


  // Initialisation des variable de la boucle
  $i=0;
  
  while ($nbJoueurs > 0) {
  //while (($nbJoueurs > 0) && ($limitDeb<200)) { // Pour debug

    // Boucle sur les clubs
    $i=0;
    for ($i=0;$i < $nbJoueurs;$i++)
    {
      //echo("<br />i=".$i."-id=".$joueurDTN[$i]["idHattrickJoueur"]);
      // récupération des informations joueur et club sur HT
      $joueurHT=getDataUnJoueurFromHT_usingPHT($joueurDTN[$i]["idHattrickJoueur"]);

      if ($joueurHT!=false) {
        $clubHT=getDataClubFromHT_usingPHT($joueurHT['teamid']);

        // Maj du joueur
        $maj=majJoueur("MAJ auto","D",$joueurHT,$joueurDTN[$i]);
        
        if ($maj['joueur_est_maj']||$maj['club_est_maj']) {
          fwrite($myfile,"-".$i."-".$joueurDTN[$i]["idHattrickJoueur"]);
          $nbUpdate++;
          fwrite($myfile, "=>MAJ OK\n");
        }
        unset($clubHT);
        unset($joueurHT);
      }
    }
    fwrite($myfile, "___________________\n");
    fflush($myfile);
    unset($i);
    unset($joueurDTN);
    unset($nbJoueurs);
    
    // Extraction des clubs présents dans la base DTN
    $limitDeb=$limitDeb+$nbClubExtract;
    fwrite($myfile,"limitDeb=".$limitDeb."|NbUpdate=".$nbUpdate."\n");
    $joueurDTN=getListeJoueursArchives($limitDeb,$nbClubExtract);
    $nbJoueurs=count($joueurDTN);
    
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
  $sql="INSERT INTO $tbl_maj_auto (date_maj,nom_traitement,nbre_maj,nom_script) VALUES (curdate(),'[JOUEUR] MAJ des joueurs archivés',$nbUpdate,'majJoueursArchives.php')";
  $bool = $maBase->insert($sql);

?>