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
  $filename = $_SERVER['DOCUMENT_ROOT'].'/dtn/interface/maj/log/repriseClubsDTN.txt';
  $myfile=fopen($filename,'w');
  
  $_SESSION['HT']=creerConnexionHT();  

  fwrite($myfile, "Debut traitement : ".date("d/m/Y H:i:s")."\n");
  fwrite($myfile, "======================================\n");


  // Extraction de la liste des DTN
  $sql = "SELECT idAdminHT FROM $tbl_admin WHERE idAdminHT IS NOT NULL AND idAdminHT != 0";
  $result = mysql_query($sql) or die(mysql_error()."\n".$sql);
  while($row =  mysql_fetch_array($result)){
    $tabDTN[] = $row;
    unset($row);
  }
  mysql_free_result($result);   
  unset($sql);
  unset($result);



  // Initialisation variable boucle
  $nbClubs=count($tabDTN);
  $nbUpdate=0;

  $i=0;
  for ($i=0;$i < $nbClubs;$i++)
  {
    $resuClub=majClub(null,$tabDTN[$i]['idAdminHT'],null);
    if ($resuClub["maj"] == true) {$nbUpdate = $nbUpdate + 1;}
    fwrite($myfile, $resuClub["logModif"]);
    fflush($myfile);

    unset($resuClub);
  }
  // Libération de mémoire
  unset($i);
  unset($tabDTN);
  unset($nbClubs);
    
  if (round(memory_get_usage()/1024000) > 200) {
    echo("M&eacute;moire insuffisante - Arret du traitement<br />");
    echo("Relance : $url_courante?limiteDeb=$limitDeb&nbUpdate=$nbUpdate<br />");
    fwrite($myfile, "M&eacute;moire insuffisante - Arret du traitement\n");
    fwrite($myfile, "Relance : $url_courante?limiteDeb=$limitDeb&nbUpdate=$nbUpdate");
    fclose($myfile);
    exit;
  }
 
  echo ('<br />fin<br />');
  echo "<b>Max Peak Memory Usage: " . memory_get_peak_usage() . "</b><br>\n";

  fwrite($myfile, "=>Nbre Update=".$nbUpdate."\n");
  fwrite($myfile, "Fin traitement : ".date("d/m/Y H:i:s")."\n");
  fclose($myfile);

?>
