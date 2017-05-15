<?php
// Affiche toutes les erreurs
error_reporting(E_ALL);

require_once "../_config/CstGlobals.php"; // fonctions d'admin
require_once "../fonctions/AdminDtn.php"; // fonctions d'admin
require_once "../fonctions/AccesBase.php"; // fonction de connexion � la base
require_once("../includes/head.inc.php");
require_once("../includes/serviceListesDiverses.php");
require_once("../includes/serviceEntrainement.php");
require_once("../includes/serviceiihelp.php");
require_once("../includes/serviceEquipes.php");



if(!$sesUser["idAdmin"])
{
  header("location: ../index.php?ErrorMsg=Session_Expire");
}


/******************************************************************************/
/*      TRAITEMENT DES MODIFICATION DE DONNEES                                */
/******************************************************************************/
$repreneur=array();

if (isset($_REQUEST['propriosupr']))
{
  if (del_iiihelp_repreneur($_REQUEST['propriosupr'])) {$msgSucces="Club Supprim&eacute;";}
  else {$msgErreur="ERREUR LORS DE LA SUPPRESSION ! Prenez contact avec un d&eacute;veloppeur.";}
}

if (isset($_REQUEST['valid_proprio']))
{
  $repreneur['id_iiihelp_repreneur']=$_REQUEST['valid_proprio'];
  $repreneur['etat']=0;
  if (updateRepreneuriiiHelp($repreneur) != false) {$msgSucces="Repreneur Valid&eacute;";}
  else {$msgErreur="ERREUR LORS DE LA VALIDATION DU REPRENEUR ! Prenez contact avec un d&eacute;veloppeur.";}
}

if (isset($_REQUEST['refuse_proprio']))
{
  $repreneur['id_iiihelp_repreneur']=$_REQUEST['refuse_proprio'];
  $repreneur['etat']=-2;
  if (updateRepreneuriiiHelp($repreneur) != false) {$msgSucces="Repreneur Refus&eacute;";}
  else {$msgErreur="ERREUR LORS DU REFUS DU REPRENEUR ! Prenez contact avec un d&eacute;veloppeur.";}
}

/******************************************************************************/
/*      RECUPERATION DONNEES                                                  */
/******************************************************************************/
if (!isset ($_REQUEST['ordre']))
	$_REQUEST['ordre'] = "nomClub";
if (!isset ($_REQUEST['sens']))
	$_REQUEST['sens'] = "DESC";


// Requête SQL pour récupérer données
$sql = get_iiihelp_repreneur_clubs_SQL();
if (isset($ent) && $ent!="Tous") {	$sql .="AND (entrainement_voulu1=$ent or entrainement_voulu2=$ent)";}
$sql .= " ORDER BY etat ".$_REQUEST['sens'].",".$_REQUEST['ordre'];
$req  = mysql_query($sql) or die(mysql_error()."\n".$sql);

// Liste des entrainements
$liste_entrainement=listEntrainement();


?>
<link href="../css/ht2.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../includes/javascript/navigation.js" type="text/javascript" ></script>

<?php

/******************************************************************************/
/*      AFFICHAGE MENU                                                        */
/******************************************************************************/
switch($sesUser["idNiveauAcces"]){
                case "1":
                require("../menu/menuAdmin.php");
                require("../menu/menuAdminGestion.php");
                break;

                case "2":
                require("../menu/menuSuperviseur.php");
                require("../menu/menuSuperviseurGestion.php");
                break;

                case "3":
                require("../menu/menuDTN.php");
                require("../menu/menuDTNGestion.php");
                break;

                case "4":
                require("../menu/menuCoach.php");
                require("../menu/menuCoachGestion.php");
                break;

                default;
                break;
}


/******************************************************************************/
/*      AFFICHAGE PAGE                                                        */
/******************************************************************************/
?>
<title>Repreneurs</title>
<body>

<?php 
if (isset($msgSucces)) {?>
  <br />
  <span class="MsgSucces"><?php echo($msgSucces);?></span>
<?php }
if (isset($msgErreur)) {?>
  <br />
  <span class="MsgErreur"><?php echo($msgErreur);?></span>
<?php }?>

<center>
<br />
<b><span class="breadvar">Liste des Repreneurs iiiHelp!</span></b>
<br />
[<b>&nbsp;
<a class="btn" href="?ent=Tous">Tous</a>&nbsp;|&nbsp;
<a class="btn" href="?ent=10">Gardien</a>&nbsp;|&nbsp;
<a class="btn" href="?ent=4">D&eacute;fense</a>&nbsp;|&nbsp;
<a class="btn" href="?ent=6">Ailier</a>&nbsp;|&nbsp;
<a class="btn" href="?ent=9">Construction</a>&nbsp;|&nbsp;
<a class="btn" href="?ent=8">Passe</a>&nbsp;|&nbsp;
<a class="btn" href="?ent=5">Buteur</a>&nbsp;|&nbsp;
<a class="btn" href="?ent=3">Coup Franc</a>&nbsp;	
</b>]

</center>
<p>
<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
  <td>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#000000"><a href="?ordre=idClubHT"><font color="#FFFFFF">idHT - login - equipe</font></a></td>
    <td bgcolor="#000000"><a href="?ordre=idPays_fk"><font color="#FFFFFF">Pays</font></a></td>
    <td bgcolor="#000000"><a href="?ordre=leagueLevel"><div align="center"><font color="#FFFFFF">Div</font></a></div></td>
    <td bgcolor="#000000"><a href="?ordre=niv_Entraineur"><div align="center"><font color="#FFFFFF">Entraineur</font></a></div></td>
    <td bgcolor="#000000"><a href="?ordre=intensite"><div align="center"><font color="#FFFFFF">Intensite</font></a></div></td>
    <td bgcolor="#000000"><a href="?ordre=endurance"><div align="center"><font color="#FFFFFF">Endu</font></a></div></td>
    <td bgcolor="#000000"><a href="?ordre=idEntrainement"><div align="left"><font color="#FFFFFF">Entrainement</font></a></div></td>
    <td bgcolor="#000000"><a href="?ordre=adjoints"><div align="center"><font color="#FFFFFF">Adjoint</font></a></div></td>
    <td bgcolor="#000000"><a href="?ordre=entrainement_voulu1"><div align="left"><font color="#FFFFFF">Souhait 1</font></a></div></td>
	  <td bgcolor="#000000"><a href="?ordre=entrainement_voulu2"><div align="left"><font color="#FFFFFF">Souhait 2</font></a></div></td>
    <td bgcolor="#000000"><a href="?ordre=commentaire"><div align="center"><font color="#FFFFFF">mail - commentaire</font></a></div></td>
    <td bgcolor="#000000"><font color="#FFFFFF">&Eacute;tat</font></td>
    <td bgcolor="#000000"><font color="#FFFFFF">Action</font></td>
  </tr>

 

<?php // Boucle sur les repreneurs
$i=0;

while ($res = mysql_fetch_object($req))
{
  $i++;
  $lastHistoClub=getLastHistoClub($res->idClubHT); // Dernière historique du club
  if ($i==1) {$etat=$res->etat;}
  if ( ($i==1) || ($etat != $res->etat) ) {?>
    <tr>
      <td ALIGN="center" bgcolor="#7878F0" colspan="14">
      <font color="#FFFFFF">
      <?php 
      switch ($res->etat) {
        case 1 :
          echo ("Nouveau repreneurs :");
          break;
        case 0 :
          echo ("Repreneurs valid&eacute;s :");
          break;
        case -1 :
          echo ("Repreneurs refus&eacute;s :");
          break;
      } ?>
      </font></td>
    </tr>
    <?php
    $etat = $res->etat; 
  }?>

  <tr <?php if ($i % 2 == 0) echo "bgcolor=#CCCCCC";  else echo "bgcolor=#FFFFFF";?> >
    <td>
    <a href="<?=$url?>/clubs/fiche_club.php?idClubHT=<?=$res->idClubHT?>"><?=$res->idClubHT?>	- <?=$res->nomUser?>	- <?=$res->nomClub?></a>
  	</td>
    <td>
    <!-- ajout du drapeau plutôt que le nom du pays le 26/07/09 par jojoje86 -->
    <img border="1" src="../../../images/flags/<?php echo $res->idPays_fk; ?>flag.gif" />
    </td>
    <td align="center"><div align="center">
    <?=$res->leagueLevel?>
    </div></td>
    <td align="center"><div align="center">
      <?=$res->niv_Entraineur?>
    </div></td>
    <td align="center"><div align="center">
      <?=$lastHistoClub['intensite']?>
      %</div></td>
    <td align="center"><div align="center">
      <?=$lastHistoClub['endurance']?>
      %</div></td>
    <td><div align="left">
      <?php echo getEntrainementName($lastHistoClub['idEntrainement'],$liste_entrainement);?>
    </div></td>
    <td><div align="center">
      <?=$lastHistoClub['adjoints']?>
    </div></td>
    <td><div align="left">
      <?php $carac=get_Carac_byID($res->entrainement_voulu1);
            echo( $carac['nomTypeCarac'].
                  "-".
                  str_replace("+21 ans","21 ans et +",$res->age_voulu1));?>
    </div></td>
    <td><div align="left">
      <?php $carac=get_Carac_byID($res->entrainement_voulu2);
            echo( $carac['nomTypeCarac'].
                  "-".
                  str_replace("+21 ans","21 ans et +",$res->age_voulu2));?>
    </div></td>
    <td>
    <div align="center"><div ID=Id_info style=position:absolute></div>
  	<a href="#" onClick="javascript:f_message('<?=$res->nomUser?>', '<?=strtr(str_replace(CHR(13).CHR(10),"<br/>",$res->commentaire),"'"," ");?>', '<?=$res->email?>'); return false;">
  	<?php 	if ($res->commentaire != "")
  		{?>Lire<?php }
  			else
  			{?> @ <?php }?>
    </a></div></td>
    <td><?php
  		switch ($res->etat)
      {
        case  -1 :
          echo("refus&eacute; par crit&egrave;re");
          break;
        case  -2 :
          echo("refus&eacute; par DTN");
          break;
        case  0 :
          echo("Valid&eacute;");
          break;
        case  1 :
          echo("Nouveau");
          break;
      }
    ?></td>
    <td>
      <?php
      switch($res->etat)
      {
      	case "-1":
      	case "-2":
      		if ($res->niv_Entraineur > 6)
      		{ 
      		  ?><a href="?valid_proprio=<?=$res->id_iiihelp_repreneur?>">Valider</a><br /><?php
      		}
          break;
      	case "1": ?>
          <a href="?valid_proprio=<?=$res->id_iiihelp_repreneur?>">Valider</a><br />
          <a href="?refuse_proprio=<?=$res->id_iiihelp_repreneur?>">Rejeter</a><br /><?php
      	   break;
      	case "0":
      	case "2":
          ?><a href="?refuse_proprio=<?=$res->id_iiihelp_repreneur?>">Rejeter</a><br /><?php
          break;
    	}?>
      <a href="?propriosupr=<?=$res->idClubHT?>">Supprimer</a>
    </td>
  </tr>
<?php } ?>

  
</table>
</td></tr>
</table>
<div align="center"><a href="javascript:history.go(-1);">Retour</a></div>
</body>
</html>
