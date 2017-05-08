<?php

$filtreDate="AND 1=1 ";
$filtrePoste="AND 1=1 ";
$filtreDTN="AND 1=1 ";
$affFiltre=" ";

if (!isset($_POST["TypeFiltre"])) $_POST["TypeFiltre"]=2;
if (!isset($_POST["ht_posteAssigne"])) $_POST["ht_posteAssigne"]=0;


if ($FromMenu=="Ma Liste") {
  $filtreDTN="AND ht_joueurs.dtnSuiviJoueur_fk = ".$sesUser["idAdmin"]." ";
}

if ($FromMenu=="Mon Secteur") {  
  if (isset($_POST["ht_posteAssigne"])) {$filtrePoste="AND ht_joueurs.ht_posteAssigne  = '".$_POST["ht_posteAssigne"]."' ";}  
}


if ($_POST["TypeFiltre"]==2 ) {
  $sql = 
      "SELECT 
          DATE_FORMAT(dateAvantDerniereConnexion,'%d/%m/%Y') as dateAvantDerniereConnexion,
          heureAvantDerniereConnexion
      FROM
		      ht_admin
		  WHERE
		      idAdmin = ".$sesUser["idAdmin"];

	$req = mysql_query($sql);
	$res = mysql_fetch_array($req);
  $filtreDate="AND ht_clubs_histo.date_histo >='".$res["dateAvantDerniereConnexion"]." ".$res["heureAvantDerniereConnexion"]."' ";
  $affFiltre="depuis le : ".$res["dateAvantDerniereConnexion"]." &agrave; ".$res["heureAvantDerniereConnexion"];
}

if ($_POST["TypeFiltre"]==3 ) {
  $filtreDate="AND ht_clubs_histo.date_histo >='".$_POST["DateFiltre"]."' ";
  $affFiltre="depuis le : ".$_POST["DateFiltre"];
}


		$sql = 
      "SELECT 
          ht_joueurs.idJoueur,
          ht_joueurs.idHattrickJoueur,
          ht_joueurs.nomJoueur,
          ht_joueurs.prenomJoueur, 
          ht_clubs.idClubHT,
          ht_clubs.nomClub,
          DATE_FORMAT(ht_clubs_histo.date_histo,'%d/%m/%Y %H:%i:%s') as date_histo,
          ht_clubs_histo.Commentaire
      FROM
		      ht_joueurs,
          ht_clubs,
		      ht_clubs_histo
		  WHERE
		      ht_clubs.idClubHT = ht_clubs_histo.idClubHT
		  AND ht_joueurs.teamid = ht_clubs.idClubHT 
		  AND ht_joueurs.joueurActif = 1
      AND ht_joueurs.affJoueur = 1
    	AND ht_joueurs.archiveJoueur = 0 ".
		  $filtrePoste.$filtreDTN.$filtreDate.
		  "AND ht_clubs_histo.role_createur = 'P'
		  AND ht_clubs_histo.Commentaire != '' 
      AND ht_clubs_histo.Commentaire IS NOT NULL
		  ORDER BY 
          ht_clubs_histo.date_histo DESC
      LIMIT 0,100";

		  $req = mysql_query($sql) or die ($sql);?>

  <table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <br><br>
  <tr>
    <td>
      <div align="center">
      <span class="breadvar">Messages des propri&eacute;taires de joueurs de <?=$FromMenu." ".$affFiltre?>
      </span>
      </div>
      <br>
    </td>
  </tr>
  <tr>
    <td><table width="100%"  border="0" cellpadding="0" cellspacing="0">
      <tr bgcolor="#000000">
        <td colspan="1"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div>          </td>
        </tr>
      <tr>
        <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td width="3%" bgcolor="#000000"><div align="center" class="Style2">Num</div></td>
        <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td width="12%" bgcolor="#000000"><div align="center" class="Style2">Date Message</div></td>
        <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td width="20%" bgcolor="#000000"><div align="center" class="Style2">Identit&eacute; Joueur</div></td>
        <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td width="15%" bgcolor="#000000"><div align="center" class="Style2">Club</div></td>
        <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td width="50%" bgcolor="#000000"><div align="center" class="Style2">Message</div></td>
        <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
      </tr>
	    <tr bgcolor="#000000">
      <td colspan="1"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div>          </td>
      </tr>
		 
		  <?php $i=0;
        while($lst = mysql_fetch_array($req)){ 
        $i++;?>
        <tr bgcolor="<?php if ($i%2==0) {?>lightblue<?php } else {?>white<?php }?>">
          <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
          <td  nowrap>
            <?=$i?>
          </td>
          <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
          <td  nowrap>
            <?=$lst["date_histo"]?>
          </td>
          <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
          <td  nowrap>
            <a href ="<?=$url?>/joueurs/fiche.php?id=<?=$lst["idJoueur"]?>" class="bred1">
            <?=$lst["idHattrickJoueur"]."-".strtolower($lst["nomJoueur"])?><?=strtolower($lst["prenomJoueur"])?>
            </a>    
          </td>
          <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
          <td>
            <a href ="<?=$url?>/clubs/fiche_club.php?idClubHT=<?=$lst["idClubHT"]?>" class="bred1">
            &nbsp;<?=strtolower($lst["nomClub"])?>
            </a>
          </td>
  		    <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
          <td><?=$lst["Commentaire"]?></td>
          <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        </tr>	 
  	     <tr bgcolor="#000000">
          <td colspan="12"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div>          </td>
          </tr><?php 
      } ?>






    </table></td>
  </tr>
</table>
<br>
<?php if ($i>=100) {?><i>Maximum atteint : 100 messages affich&eacute;s</i> <?php }?>
