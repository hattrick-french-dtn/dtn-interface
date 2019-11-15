<?php
require_once("../includes/head.inc.php");
require("../includes/serviceEquipes.php");
require("../includes/serviceListesDiverses.php");
require("../includes/serviceJoueur.php");
require("../includes/serviceMatchs.php");
require_once "../_config/CstGlobals.php"; 
require("../includes/langue.inc.php");

if(!$sesUser["idAdmin"])
{
	header("location: index.php?ErrorMsg=Session Expire");
}


switch($sesUser["idNiveauAcces"]){
case "1":
	require("../menu/menuAdmin.php");
	require("../menu/menuSuperviseurConsulter.php");
	break;
	
case "2":
	require("../menu/menuSuperviseur.php");
	require("../menu/menuSuperviseurConsulter.php");
	break;

case "3":
	require("../menu/menuDTN.php");
	require("../menu/menuDTNConsulter.php");
	break;
	
case "4":
	require("../menu/menuCoach.php");
	require("../menu/menuCoachSubmit.php");
	break;
	
default;
	break;
}


// Initialisation des variables
if(!isset($ordre)) $ordre = "nomJoueur";
if(!isset($sens)) $sens = "ASC";
if(!isset($lang)) $lang = "FR";

if (isset($_SESSION['listID']) && !isset($_REQUEST['listID']) )  {
  $_REQUEST['listID']=$_SESSION['listID'];
}




?>
<link href="../css/ht.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<title>Superviseur</title>
<script language="JavaScript" src="menu_joueur.js"></script>

<body onLoad = "init();">

<br />

<!--<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">-->
<table width="700" class="ContenuCentrer">
  <tr>
  <td>
    &nbsp;
  </td>
  </tr>
  <tr>
  <!-- Entête tableau pour l'ajout des joueurs sur le marché des transferts -->
  <td height="20" class="EnteteContenu">
    Ajouter un ou plusieurs joueurs
  </td>
  </tr>

  <?php
  if (!isset($_SESSION['HT'])) {?>

    <tr> 
    <td>
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
    </td>
    </tr>
              
              
  <?php } else {?>

    <form name="form1" method="post" action="" onSubmit="return testListId()">  
    <tr>
    <td height ="20" >
      <img src="../images/greenball.jpg"> 1 - Votre session hattrick est active ! 
    </td>
    </tr>
              
  <?php }?>

  <tr>
  <td> 
    <br /> 
    2 - Entrez la liste des IDs de joueurs actuellement sur le march&eacute; des transferts que vous souhaitez ajouter dans la base :<br>
    <br />
  </td>
  </tr>
  <tr>
  <td class="ContenuCentrer">
    <textarea name="listID" id="listID" style="font-size:7pt;font-family:Arial" cols=150 rows=6 <?php if (!isset($_SESSION['HT'])) {?> DISABLED <?php }?> ><?php if (isset($_SESSION['listID'])) echo ($_SESSION['listID']);?></textarea>
  </td>
  </tr>
  <tr>
  <td>
    <i>Remarque : Chaque ID de joueur doit &ecirc;tre s&eacute;par&eacute; par un ";"</i>
  </td>
  </tr> 
  <tr> 
  <td class="ContenuCentrer">
    <br />
    <input type="submit" name="button" value="AJOUTER" class="boutonGris" <?php if (!isset($_SESSION["HT"])) {?>DISABLED <?php }?> />
    <br />
    <br />
  </td>
  </tr>
  </form>
</table>


<br />
<?php
if(isset($_SESSION['msgAddPlayer'])) echo "<h3><center><font color = red>".$_SESSION['msgAddPlayer']."</font></center></h2>";
$_SESSION['msgAddPlayer']="";
?>
<br />


<?php 
if (isset($_SESSION["HT"]) && isset($_REQUEST['listID']) ) {
    ?>
    <script language="JavaScript">
    document.form1.listID.value  = "<?=$_REQUEST['listID']?>";
    </script>
    <?php             
    $xml=null;
    $arrayID=null;
    $player=null;
    $listID=str_replace(CHR(32),"",$_REQUEST['listID']);
    $arrayID = explode(";",$listID);
    for($i=0 ; $i<count($arrayID);$i++)
    {
      $joueurHT[$i]=getDataUnJoueurFromHT_usingPHT($arrayID[$i]);
    }
    ?>
    

    <table width="700" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr> 
      <td><div align="center"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr class="activ">
        <td colspan="4" height="20" bgcolor="#000000"> <div align="center"><font color="#FFFFFF">Liste des joueurs</font></div></td>
      </tr>
      <tr class="activ" bgcolor="#0000CC"><div align="left">
        <td width="10%" bgcolor="#0000CC"> <font color="#FFFFFF">Id Joueur</font></td>
        <td width="30%" bgcolor="#0000CC"> <font color="#FFFFFF">Nom Joueur</font></td>
        <td width="50%" bgcolor="#0000CC"> <font color="#FFFFFF">Commentaire</font></td></div>
        <td width="10%" bgcolor="#0000CC"> <font color="#FFFFFF">Fiche</font></td></div>
      </tr>
      <?php
        $playerToAddManual=NULL;
        for($i=0;$i<count($joueurHT);$i++)
        {
          $rejet=1;
          //print_r($player[$i]);
          if (!$joueurHT[$i]) {
              /*Si le joueur n'existe pas sur HT*/
              $commentaireJ="n'existe pas sur HT";
              $lien="n/a";
          }
          else {
              /*Si le joueur existe sur HT*/
              if ($joueurHT[$i]["NATIVELEAGUENAME"]!="France") {
                /* Si le joueur est étranger*/
                $commentaireJ="est un joueur &eacute;tranger";
                $lien="n/a";
              }
              else {
                  /*Si le joueur existe sur HT et est en vente*/
                  $joueurDTN=getJoueurHt($joueurHT[$i]['idHattrickJoueur']);
                  if ($joueurDTN['idHattrickJoueur']==$joueurHT[$i]['idHattrickJoueur']) {
                    /*si le joueur existe déja dans la base*/
                    $commentaireJ="existe d&eacute;j&agrave; dans la base";
                    $lien="<u><a href='fiche.php?htid=$arrayID[$i]' color='#0000FF' target='_NEW'>Voir</a></u>";
                  }
                  else {if ($joueurHT[$i]['transferListed']==0) { 
                        /*Si le joueur existe sur HT mais n'est pas en vente*/
                        $commentaireJ="n'est pas &agrave; vendre";
                        $lien="<u><a href='addPlayer.php#".$arrayID[$i]."' color='#0000FF'>Saisir</a></u>";
                        $Nb=count($playerToAddManual);
                        $playerToAddManual[$Nb]=$joueurHT[$i];
                      }
                      else {
                        $todaySeason=getSeasonWeekOfMatch(mktime(0,0,0,date('m'), date('d'),date('Y')));
                        $poste[$i]=validateMinimaPlayer($joueurHT[$i],$todaySeason);
                        if ($poste[$i]==-2) {$poste[$i]=0;}
                        if ($poste[$i]==-1) {
                          /*si le joueur est en dessous des minimas*/
                          $commentaireJ="est en-dessous des minimas mais intégré dans la base DTN";
                          $lien="<u><a href='fiche.php?htid=$arrayID[$i]' color='#0000FF' target='_NEW'>Voir</a></u>";
                          $rejet=0;
		          $poste[$i]=0;
                        }
                        else{
                          $commentaireJ="Ins&eacute;r&eacute; dans la base DTN !!";
                          $lien="<u><a href='fiche.php?htid=$arrayID[$i]' color='#0000FF' target='_NEW'>Voir</a></u>";
                          $rejet=0;
                        }
                      }
                  }
                  
              }
          }
          if ($rejet==1) {
            // Si joueur rejeté
            $FontColor="#FF0000";
          } else {
            // Si joueur accepté
            $FontColor="#006600";
            $idJoueur=ajoutJoueur($_SESSION["sesUser"]["loginAdmin"],"D",$joueurHT[$i],$joueurDTN,$poste[$i]);
			if ($idJoueur === FALSE) {
				$commentaireJ="Erreur lors de l'insertion";
				$lien = "n/a";
			}
          }
          unset($joueurDTN);
          ?>
            <div align="center">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">                
            <tr bgcolor=<?php if ($i % 2==0){?>"lightblue"<?php } else {?>"#FFFFFF"<?php }?>>
              <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
              <td width="10%"> <?=$arrayID[$i]?></td>
              <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
              <td width="30%"><?=strtr($joueurHT[$i]['prenomJoueur'],"'"," ")?> <?=strtr($joueurHT[$i]['nomJoueur'],"'"," ")?></td>
              <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
              <td width="50%"><font color="<?=$FontColor?>"><?=$commentaireJ?></font></td>
              <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
              <td width="10%" align="center"><?=$lien?></font></td>
              <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
            </tr>
            <tr> 
                    <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
            </tr>
            </table>
        <?php } /*Fin boucle for */ ?>  
    </table>
  </div></td>
  </tr>
  </table>
<?php

 
 
 
/*********************************************************/
/* AJOUT MANUEL                                          */
/*********************************************************/

if (isset($playerToAddManual)) {
  // Récupération des listes de valeurs de chaque carac (Emplacement des fonctions : includes/ServiceListesDiverses)
  $lstAgg = listAggres();
  $lstCarac = listCaractere();
  $lstHon = listHonnetete();
  $lstLeader = listLeadership();
  $lstCaractJ = listCarac('ASC',21);
  
  for($j=0;$j<count($playerToAddManual);$j++)
  {
  // Récupération des données club sur HT
  $clubHT=getDataClubFromHT_usingPHT($playerToAddManual[$j]['teamid']);

  // Agréabilité
  foreach($lstCarac as $l)
  {
   if ($playerToAddManual[$j]["idCaractere_fk"]==$l["numCaractere"]) $Player_agree=$l["numCaractere"]." - ".$l["intituleCaractereFR"]."|".$l["intituleCaractereUK"];
  }

  // Expérience, Endurance
  for($i=0;$i<count($lstCaractJ);$i++)
  {
   if ($playerToAddManual[$j]["idExperience_fk"]==$lstCaractJ[$i]["numCarac"]) $Player_xp=$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"];
   if ($playerToAddManual[$j]["idEndurance"]==$lstCaractJ[$i]["numCarac"]) $Player_endurance=$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"];
  }
  
  // Agressivité
  foreach($lstAgg as $l){
   if ($playerToAddManual[$j]["idAggre_fk"]==$l["numAggres"]) $Player_aggress=$l["numAggres"]." - ".$l["intituleAggresFR"]."|".$l["intituleAggresUK"];
  }
  
  // Tempérament de chef, Forme
  foreach($lstLeader as $l)
  {
   if ($playerToAddManual[$j]["idLeader_fk"]==$l["numLeader"]) $Player_tdc=$l["numLeader"]." - ".$l["intituleLeaderFR"]."|".$l["intituleLeaderUK"];
   if ($playerToAddManual[$j]["forme"]==$l["numLeader"]) $Player_forme=$l["numLeader"]." - ".$l["intituleLeaderFR"]."|".$l["intituleLeaderUK"];
  }
  
  //Honnetete
  for($i=0;$i<count($lstHon);$i++)
  {
   if ($playerToAddManual[$j]["idHonnetete_fk"]==$lstHon[$i]["numHonnetete"]) $Player_hon=$lstHon[$i]["numHonnetete"]." - ".$lstHon[$i]["intituleHonneteteFR"]."|".$lstHon[$i]["intituleHonneteteUK"];
  }
  
  //Spécialité
  for($i=0;$i<count($option);$i++){
   if ($playerToAddManual[$j]["optionJoueur"]==$i) $Player_speciality=$i." - ".$option[$i]["FR"]."|".$option[$i]["UK"];
  }
  ?>
  
  
  
  <br />
  <br />
  <br />
  
  <form name="form2" method="post" action="../form.php">  
  <table width="700" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <td height ="20" ><div align="center">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="20" bgcolor="#00CC00"> <div align="center"><font color="#FFFFFF"><a name="<?=$playerToAddManual[$j]["idHattrickJoueur"]?>"><?=$playerToAddManual[$j]["idHattrickJoueur"]." - ".$playerToAddManual[$j]["prenomJoueur"]." ".$playerToAddManual[$j]["nomJoueur"]?></a></font></div></td>
              </tr>
              <tr> 
                <td height="1" colspan="3" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
              </tr>
              <tr> 
                <td colspan="3"><br /> 
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td><strong>&nbsp; Informations :</strong></td>
                    </tr>
                    <tr> 
                      <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                  <br /> 
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td height="20"><div align="left">&nbsp;ID Hattrick :</div></td>                      
                      <td height="20"><?=$playerToAddManual[$j]["idHattrickJoueur"] ?></td>
                    </tr>
                    <tr> 
                      <td height="20"><div align="left">&nbsp;Nom :</div></td>                      
                      <td height="20"><?=$playerToAddManual[$j]["prenomJoueur"]?> <?=$playerToAddManual[$j]["nomJoueur"]?></td>
                    </tr>
                    <tr> 
                      <td height="20"><div align="left">&nbsp;Age</div></td>
                      <td height="20"><?=$playerToAddManual[$j]["AGE"]." ans - ".$playerToAddManual[$j]["AGEDAYS"]." jours"?> </td>
                    </tr>
                    <tr> 
                      <td height="20"><div align="left">&nbsp;Club actuel :</div></td>                      
                      <td height="20"><?=$clubHT["nomClub"]." (".$playerToAddManual[$j]["teamid"].")" ?></td>
                    </tr>
          				  <!--rajout entraineur -->
          				  <tr> 
                      <td height="20"><div align="left">&nbsp;Entraineur :</div></td>                      
                      <td height="20"><?=$clubHT["niv_Entraineur"]?></td>
                    </tr>
                    <tr> 
                      <td height="20"><div align="left">&nbsp;TSI :</div></td>                      
                      <td height="20"><?=$playerToAddManual[$j]["tsi"]?></td>
                    </tr>
                    <tr> 
                      <td height="20"><div align="left">&nbsp;Salaire :</div></td>                      
                      <td height="20"><?=round(($playerToAddManual[$j]["salary"]/10),2)." &euro;/semaine" ?></td>
                    </tr>
                  </table>
                  
                  <br /> 
                  
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td>&nbsp;<strong> caracteristiques mentales : </strong> </td>
                    </tr>
                    <tr> 
                      <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                  <br /> 
                  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr> 
                      <td width="25%" height="20" align="left"> &nbsp; popularite :</td>
                      <td width="25%" height="20"><?=$Player_agree?> </td>
                      <td width="50%" height="20">&nbsp; </td>
                    </tr>
                    <tr>
                      <td width="25%" height="20" align="left"> &nbsp; aggressivite :</td> 
                      <td width="25%" height="20"><?=$Player_aggress?> </td>
                    </tr>
                    <tr>
                      <td width="25%" height="20" align="left"> &nbsp; honnetete :</td> 
  
                      <td width="25%" height="20"><?=$Player_hon?> </td>
                    </tr>
                    <tr>
                      <td width="25%" height="20" align="left"> &nbsp; experience :</td> 
                      <td width="25%" height="20"><?=$Player_xp?> </td>
                    </tr>
                    <tr>
                      <td width="25%" height="20" align="left"> &nbsp; temperament de chef :</td> 
                      <td width="25%" height="20"><?=$Player_tdc?> </td>
                    </tr>
                  </table>
                  
                  <br />
                  
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td><strong>&nbsp; caracteristiques physiques : </strong> </td>
                    </tr>
                    <tr> 
                      <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                  <br /> 
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="25%" height="20">&nbsp;Forme </td>
                      <td width="25%" height="20"><?=$Player_forme?></td>
                      <td width="50%" height="20">&nbsp; </td>
                    </tr>
                    <tr> 
                      <td width="25%" height="20">&nbsp;Endurance </td>
                      <td width="25%" height="20"><?=$Player_endurance?></td>
                      <td width="50%" height="20">&nbsp; </td>
                    </tr>
                    <tr>
                      <td width="25%" height="25">&nbsp;Gardien </td>
                      <td width="25%" height="25">
                        <select name="idGardien" id="idGardien">
                          <?php
                  				for($i=0;$i<count($lstCaractJ);$i++)
                  				{
                  				echo "<option value=".$lstCaractJ[$i]["idCarac"].">".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";   
                  				}
                  				?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td width="25%" height="25">&nbsp;Defense </td>
                      <td width="25%" height="25">
                        <select name="idDefense" id="idDefense">
                          <?php
                  				for($i=0;$i<count($lstCaractJ);$i++)
                  				{
                  				echo "<option value=".$lstCaractJ[$i]["idCarac"].">".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";    
                  				}
                  				?>
                        </select>
                     </td>
                    </tr>
                    <tr> 
                      <td width="25%" height="25">&nbsp;Construction </td>
                      <td width="25%" height="25">
                        <select name="idConstruction" id="idConstruction">
                          <?php
                  				for($i=0;$i<count($lstCaractJ);$i++)
                  				{
                  				echo "<option value=".$lstCaractJ[$i]["idCarac"].">".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";    
                  				}
                  				?>
                        </select>
                      </td>
                    </tr>
                    <tr> 
                      <td width="25%" height="25">&nbsp;Ailier </td>
                      <td width="25%" height="25">
                        <select name="idAilier" id="idAilier">
                          <?php
                  				for($i=0;$i<count($lstCaractJ);$i++)
                  				{
                  				echo "<option value=".$lstCaractJ[$i]["idCarac"].">".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";    
                  				}
                  				?>
                        </select>
                      </td>                    
                    </tr>
                    <tr>
                      <td width="25%" height="25">&nbsp;Passe </td>
                      <td width="25%" height="25">
                        <select name="idPasse" id="idPasse">
                          <?php
                  				for($i=0;$i<count($lstCaractJ);$i++)
                  				{
                  				echo "<option value=".$lstCaractJ[$i]["idCarac"].">".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";    
                  				}
                  				?>
                        </select>
                      </td>
                    </tr>
                    <tr> 
                      <td width="25%" height="25">&nbsp;Buteur </td>
                      <td width="25%" height="25">
                        <select name="idButeur" id="idButeur">
                          <?php
                  				for($i=0;$i<count($lstCaractJ);$i++)
                  				{
                  				echo "<option value=".$lstCaractJ[$i]["idCarac"].">".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";    
                  				}
                  				?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td width="25%" height="25">&nbsp;Coup Franc </td>
                      <td width="25%" height="25">
                        <select name="idPA" id="idPA">
                          <?php
                  				for($i=0;$i<count($lstCaractJ);$i++)
                  				{
                  				echo "<option value=".$lstCaractJ[$i]["idCarac"].">".$lstCaractJ[$i]["numCarac"]." - ".$lstCaractJ[$i]["intituleCaracFR"]."|".$lstCaractJ[$i]["intituleCaracUK"]."</option>";    
                  				}
                  				?>
                        </select>
                      </td>
                    </tr>
                  </table>
                  
                  <div align="center"> <br />
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
                        <td><strong>&nbsp;caracteristique speciale : </strong> </td>
                      </tr>
                      <tr> 
                        <td bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                      </tr>
                    </table>
                    <br />
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
                        <td width="25%" height="20">&nbsp;Intitul&eacute; :</td>
                        <td width="25%" height="20"><?=$Player_speciality?></td>
                        <td width="50%" height="20">&nbsp; </td>
                      </tr>
                    </table>
                    <p> 
            			  
                      <input name="mode" type="hidden" id="mode" value="ajoutJoueur">
                      <input type="submit" name="Submit" value="Ajouter ce joueur">
                      <input type="hidden" name="listID" value="<?=$_REQUEST['listID']?>">
                      <input type="hidden" name="playerToAddManual" id="playerToAddManual" value="<?=urlencode(serialize($playerToAddManual[$j]))?>">
                    </p>
                    <p>&nbsp;</p>
                  </div></td>
              </tr>
            </table>
        </div>
      </td>
    </tr>
  </table>
  </form>
  
    <?php
    unset($clubHT);
  } // Fin boucle for PlayerToAddManual
} // Fin boucle Si PlayerToAddManual existe

} /* Fin si : connexion ht OK*/ ?>

</body>
