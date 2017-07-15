<?php
  require_once("includes/head.inc.php");
  require("includes/nomTables.inc.php");
  require_once "fonctions/AccesBase.php";
  require_once "fonctions/HT_Client.php";
  require_once "_config/CstGlobals.php"; 

  // Connexion à Base DTN
  $maBase=initBD();

  // Recherche des dernières mise à jour auto de la base
  $sql="select 
              date_maj,
              nom_traitement,
              nbre_maj,
              nom_script,
              case when '".$_SESSION['sesUser']['dateDerniereConnexion']."'<=date_maj then 1 else 0 end topNew
        from $tbl_maj_auto
        order by date_maj desc, id_maj_auto desc";

  $majs = $maBase->select($sql);
  
  
  if ( ($_SESSION['sesUser']['idNiveauAcces']=="1") || ($_SESSION['sesUser']['idNiveauAcces']=="2") ) {
    // Vérification de la liste des joueurs non assignés si un DTN+ ou DTN# est connecté
    $sql="SELECT count(1) as NbJoueurNonAssigne
          FROM $tbl_joueurs
          WHERE ht_posteassigne =0
          AND archiveJoueur =0";
  
    $alertes1 = $maBase->select($sql);
    
    $sql = "select * from $tbl_iiihelp_repreneur where etat=1";
    $alertes2 = $maBase->select($sql);

  }

  
?>




<table width="100%">
  <td width="50%" valign="top">
  <table class="Tableau">
    <thead class="breadvar">
    <tr>
      <td colspan="4">
        Derni&egrave;res mises &agrave; jour automatiques
      </td>
    </tr>
    </thead>
  
    <thead class="entete">
    <tr>
      <td width="10%">Date MAJ</td>
      <td width="50%">Traitement</td>
      <td width="15%">Nbre MAJ</td>
      <td width="25%">R&eacute;sultat</td>
    </tr>
    </thead>
  
    <?php
    // Initialisation des variable de la boucle
    $i=0;
    $nbMajs=count($majs);
    for ($i;(($i<5)&&($i<$nbMajs));$i++){
      if ($majs[$i]["nom_script"]=="majEquipesAuto.php") {$lien="./joueurs/purgeJoueurs.php";$msgLien="Voir les bots";}
      if ($majs[$i]["nom_script"]=="majJoueursArchives.php") {$lien="./joueurs/purgeJoueurs.php";$msgLien="Voir les joueurs port&eacute;s disparus";}
      ?>
      <?php if ($majs[$i]["topNew"]==1) {?><tr class="new"><?php } else {?><tr class="notnew"><?php }?>
      <td width="10%" nowrap><div align="left"><?=$majs[$i]["date_maj"]?></div></td>
      <td width="50%"><div align="left"><?=$majs[$i]["nom_traitement"]?></div></td>
      <td width="15%"><div align="right"><?=$majs[$i]["nbre_maj"]?></div></td>
      <td width="25%" nowrap><div align="left"><?php if ($majs[$i]["topNew"]==1) {?><font size="-1" color="red"><b>[New] </b></font><?php }?><u><a href="<?=$lien?>"><?=$msgLien?></a></u></div></td>
      </tr>
    <?php }?>

  </table>
  </td>
  <td width="50%" valign="top">
  <table class="Tableau">
    <thead class="breadvar">
    <tr>
      <td colspan="2">
        Mes Alertes
      </td>
    </tr>
    </thead>
  
    <thead class="entete">
    <tr>
      <td width="75%">Alerte</td>
      <td width="25%">Lien</td>
    </tr>
    </thead>

  
    <?php
    // Initialisation des variable de la boucle
    $i=0;
    $j=0;
    $nbAlertes=0;
    if ( (isset($alertes1)) && ($alertes1[0]['NbJoueurNonAssigne']>0) ) {
          $nbAlertes++;
          $msgAlerte[$i]=$alertes1[0]['NbJoueurNonAssigne']." joueurs ne sont assign&eacute; &agrave; aucun secteur";
          $lienAlerte[$i]="joueurs/liste.php";
          $msgLienAlerte[$i]="Voir les joueurs non assign&eacute;s";
          $i++;
    }

    // Alerte si repreneur iiihelp en attente de validation
    // Fireproofed le 06/11/2010
    if  ((isset($alertes2)) and (count($alertes2)>0))
      {
      $nbAlertes++;
      $msgAlerte[$i]=count($alertes2)." repreneur";
      if (count($alertes2)>1) {$msgAlerte[$i].="s sont";}
        else {$msgAlerte[$i].=" est";}      
      $msgAlerte[$i].=" en attente de validation";
      $lienAlerte[$i]="./admin/liste_repreneur_iiihelp.php";
      $msgLienAlerte[$i]="Voir la liste des repreneurs iiihelp!";
      $i++;     
      }

    if ($nbAlertes==0) {
      $msgAlerte[$i]="Rien &agrave; signaler";
      $lienAlerte[$i]="";
      $msgLienAlerte[$i]="";
      $nbAlertes++;
    }

    for ($j;(($j<5)&&($j<$nbAlertes));$j++){
      ?>
      <tr class=<?php if ($j%2==0) {?>"paire"<?php } else {?>"impaire"<?php }?>>
      <td width="60%"><div align="left"><?=$msgAlerte[$j]?></div></td>
      <td width="40%" nowrap><div align="left"><u><a href="<?=$lienAlerte[$j]?>"><?=$msgLienAlerte[$j]?></a></u></div></td>
      </tr>
    <?php }?>



  </td>
  </table>
</table>
<hr />
