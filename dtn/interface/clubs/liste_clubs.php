<?php
require_once("../includes/head.inc.php");
require_once("../includes/nomTables.inc.php");

if(!$sesUser["idAdmin"])
	{
	header("location: ../index.php?ErrorMsg=Session Expiree");
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
		require("../menu/menuCoachConsulter.php"); 
		break;
		
		default;
		break;
}

	
/******************************************************************************/
/******************************************************************************/
/*      DEFINITION VARIABLES                                                  */
/******************************************************************************/
/******************************************************************************/
$i=0;

$urlGet = explode("?",$_SERVER['REQUEST_URI']);
$urlSansLeGet = $urlGet[0];

$sql="select 
          distinct(C.idClub), 
          C.idClubHT, 
          C.nomClub, 
          C.idPays_fk, 
          concat(CA.intituleCaracFR,' (',C.niv_Entraineur,')') AS niv_Entraineur, 
          C.isBot, 
          C.date_last_connexion ";
$sqlFrom1="
        from 
            $tbl_clubs C,
            $tbl_caracteristiques CA";
$sqlFrom2="
        from 
            $tbl_clubs C,
            $tbl_caracteristiques CA,
            $tbl_joueurs J ";
$sqlWhere1=" 
        where
            C.niv_Entraineur = CA.idCarac
        and C.date_last_connexion!='1970-01-01' 
        and C.date_last_connexion!='0001-01-01' 
        and C.isBot!=1 ";
$sqlWhere2=$sqlWhere1."
        and J.teamid=C.idClubHT 
        and J.archiveJoueur!=1 
        and J.ht_posteAssigne=".$sesUser['idPosition_fk'];
$sqlWhere3=$sqlWhere1."
        and J.teamid=C.idClubHT 
        and J.archiveJoueur!=1 
        and J.dtnSuiviJoueur_fk=".$sesUser['idAdmin'];

if (!isset ($ordre))
	$ordre = "C.date_last_connexion ";
if (!isset ($sens))
	$sens = "ASC";
if (!isset ($_POST['mesJoueurs'])) {
	$mesJoueurs = "0";
} else {
	$mesJoueurs = $_POST['mesJoueurs'];
}

if($mesJoueurs=="1"){
  $sql.=$sqlFrom2.$sqlWhere3;
	$sqlC="select count(*) as nb ".$sqlFrom2.$sqlWhere3;
	$nb=$conn->query($sqlC);	
	$nombre=current($nb->fetch());
}
elseif($sesUser["idNiveauAcces"]=="1"){
	$sql.=$sqlFrom1.$sqlWhere1;
	$sqlC="select count(*) as nb ".$sqlFrom1.$sqlWhere1;
	$nb=$conn->query($sqlC);
	$nombre=current($nb->fetch());
}
elseif(($sesUser["idNiveauAcces"]=="2") || ($sesUser["idNiveauAcces"]=="3")){
  $sql.=$sqlFrom2.$sqlWhere2;
	$sqlC="select count(*) as nb ".$sqlFrom2.$sqlWhere2;
	$nb=$conn->query($sqlC);	
	$nombre=current($nb->fetch());
}


if (!isset ($suivant))
	$suivant =0;
$sql.=" order by $ordre $sens LIMIT 30 OFFSET $suivant";
$result= $conn->query($sql);


/******************************************************************************/
/******************************************************************************/
/*      AFFICHAGE DU CONTENU DE LA PAGE                                       */
/******************************************************************************/
/******************************************************************************/
?>
<html>
<link href="../css/ht2.css" rel="stylesheet" type="text/css">
<title>[ht-fff]Connexion des clubs</title>
<body>
<center>
<br>
    <b><span class="breadvar">Liste des Clubs</span></b><br>
</center>
	Par d&eacute;faut, la liste est tri&eacute;e par date de connexion des proprios (de la plus ancienne &agrave; la plus r&eacute;cente). Rappel : Les dates de connexion des proprios sont mises &agrave; jour avec le menu Administration (DTN# et DTN+) ou Ma Liste/Mise a jour sur hattrick (DTN). Vous avez la possibilit&eacute; de modifier ce tri en cliquant sur les entetes de colonnes du tableau.

<br><hr>
<form method="post" action="<?=$urlSansLeGet?>">
    <?php if ($mesJoueurs=="0") {?>
      <input type="checkbox" name="mesJoueurs" value="1" onclick="this.form.submit()" />
    <?php }elseif ($mesJoueurs=="1"){?>
    <input type="checkbox" name="mesJoueurs" value="0" checked onclick="this.form.submit()" />
  <?php }?>
  &nbsp;Voir uniquement les joueurs de Ma Liste
</form>

<center>  
	<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
	<tr><td>
	<center><b><span class="breadvar">
  <?php if($suivant>0){
      ?>
  		<a href="<?=$urlSansLeGet?>?suivant=0&ordre=<?=$ordre?>&sens=<?=$sens?>"> D&eacute;but</a>
  		  <a href="<?=$urlSansLeGet?>?suivant=<?=$suivant-30?>&ordre=<?=$ordre?>&sens=<?=$sens?>"> <<</a> | <?php
  	}  
  	if(($suivant+30)<$nombre["nb"]){?>
  		<a href="<?=$urlSansLeGet?>?suivant=<?=$suivant+30?>&ordre=<?=$ordre?>&sens=<?=$sens?>"> >></a>
  		  <a href="<?=$urlSansLeGet?>?suivant=<?=$nombre["nb"]-30?>&ordre=<?=$ordre?>&sens=<?=$sens?>"> Fin</a><?php 
  	}?>
  </span></b></center>
  
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td bgcolor="#000000"><a href="<?=$urlSansLeGet?>?ordre=nomClub"><font color="#FFFFFF">Nom club et id HT</font></a></td>
		<td bgcolor="#000000"><a href="<?=$urlSansLeGet?>?ordre=idPays_fk"><div align="center"><font color="#FFFFFF">Pays</font></a></div></td>
		<td bgcolor="#000000"><a href="<?=$urlSansLeGet?>?ordre=date_last_connexion"><div align="center"><font color="#FFFFFF">date connexion</font></a></div></td>
		<td bgcolor="#000000"><a href="<?=$urlSansLeGet?>?ordre=C.niv_Entraineur"><div align="center"><font color="#FFFFFF">niv entraineur</font></a></div></td>
		<td bgcolor="#000000"><div align="center"><font color="#FFFFFF">entrainement</font></a></div></td>
		<td bgcolor="#000000"><div align="center"><font color="#FFFFFF">Joueurs</font></a></div></td>
	</tr>
<?php

while ($res=$result->fetch(PDO::FETCH_OBJ))){
$i++;
?>
	<tr <?php if ($i % 2 == 0) echo "bgcolor=#CCCCCC";  else echo "bgcolor=#FFFFFF";?> ><?php
	
	$huit = 60 * 60 * 24 * 8; /*time_0*/
	$quinze = 60 * 60 * 24 * 15; /*time_1*/
	$trente = 60 * 60 * 24 * 30; /*time_2*/
	$twomonths = 60 * 60 * 24 * 40; /*time_3*/
	$fourmonths = 60 * 60 * 24 * 50; /*time_4*/
			  
	/* Date du jour*/
	$mkday = mktime(0,0,0,date('m'), date('d'),date('Y'));

	/* Date de la dernière connexion du club */ 
	$date = explode("-",$res->date_last_connexion);
	$datemaj =  mktime(0,0,0,$date[1],$date[2],$date[0]);

	$img_nb=0;
	if ($datemaj >$mkday -$huit){
		$img_nb=0;
	}else if ($datemaj >$mkday -$quinze){
		$img_nb=1;
	}else if ($datemaj >$mkday -$trente){
		$img_nb=2;	
	}else if ($datemaj >$mkday -$twomonths){
		$img_nb=3;
	}else if ($datemaj >$mkday -$fourmonths){
		$img_nb=4;
	}else{
		$img_nb=5;
	}	

?>
		<td><img src="<?=$url?>/images/time_<?=$img_nb?>.gif" title="Derni&egrave;re connexion du propri&eacute;taire sur HT, il y a <?=($mkday-$datemaj)/(60*60*24)?> jour(s)">  <a href="<?=$url?>/clubs/fiche_club.php?idClubHT=<?=$res->idClubHT?>"><?=$res->nomClub?>  (<?=$res->idClubHT?>)</a></td>
		<td align="center"><img border="1" src="../../../images/flags/<?php echo $res->idPays_fk; ?>flag.gif" /></td>
		<td align="center"><?=$res->date_last_connexion?></td>
		<td align="left"><?=$res->niv_Entraineur?></td>
<?php
$sql3="select 
          J.entrainement_id 
      from 
          $tbl_clubs C,
          $tbl_joueurs J 
      where 
          C.idClubHT=J.teamid 
      and C.idClubHT=$res->idClubHT 
      and J.archiveJoueur!=1 
      group by 
          J.entrainement_id";
$result3= $conn->query($sql3);
$resentrainement=$result3->rowCount();
	if ($resentrainement==0){
		?><td align="center">?<?php
	}
	elseif ($resentrainement==1){
		$sql4=" select 
              J.entrainement_id, 
              E.libelle_type_entrainement 
            from 
              $tbl_type_entrainement2 E, 
              $tbl_joueurs J,
              $tbl_clubs C 
            where 
                J.entrainement_id = E.id_type_entrainement 
            and C.idClubHT = J.teamid 
            and C.idClubHT = $res->idClubHT
            group by 
                E.libelle_type_entrainement";
		$result4= $conn->query($sql4);
		$res4=$result4->fetch(PDO::FETCH_OBJ);
		if($res4->entrainement_id==0){
			?><td align="center">?<?php
		}
		else{
		?><td align="center"><?=$res4->entrainement_nom?><?php
		}
	}
	else{
		?><td align="center">/!\ Erreur<font color="red">*</font> /!\<?php
	}
?>
		</td>
		<td align="center">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">	
<?php
$sql2=" select 
            J.idJoueur, 
            J.nomJoueur, 
            J.idHattrickJoueur 
        from 
            $tbl_joueurs J
        where 
            J.archiveJoueur!=1 
        and J.teamid=$res->idClubHT";

		while ($conn->query($sql2) as $res2){
?>
		<tr><td align="left"><a href="<?=$url?>/joueurs/fiche.php?id=<?=$res2['idJoueur']?>"><?=$res2['nomJoueur']?> (<?=$res2['idHattrickJoueur']?>)</a></td></tr>

<?php
}
?>
			</table>
		</td>
	</tr>
<?php
}
$result=NULL;
?>
	</table>
	<br><center>
	<b><span class="breadvar">
	<?php if($suivant>0){
	    ?>
			<a href="<?=$urlSansLeGet?>?suivant=0&ordre=<?=$ordre?>&sens=<?=$sens?>"> D&eacute;but</a>
			  <a href="<?=$urlSansLeGet?>?suivant=<?=$suivant-30?>&ordre=<?=$ordre?>&sens=<?=$sens?>"> <<</a> | <?php
		}  
		if(($suivant+30)<$nombre["nb"]){?>
			<a href="<?=$urlSansLeGet?>?suivant=<?=$suivant+30?>&ordre=<?=$ordre?>&sens=<?=$sens?>"> >></a>
			  <a href="<?=$urlSansLeGet?>?suivant=<?=$nombre["nb"]-30?>&ordre=<?=$ordre?>&sens=<?=$sens?>"> Fin</a><?php 
		}?>
	</span></b></center>
	</td></tr></table>
	<br><br>
	<font color="red">*</font> : Il y a des entrainements différents dans la base pour les joueurs du m&ecirc;me club !
</center>

<br>
 <table width="450"  border="0" align="center" cellspacing=0 >
    <tr>
      <td colspan=5 width="450" ><center>L&eacute;gende : </center></td>
      
    </tr>
    <tr>
      <td><img src="../images/time_0.gif"></td>
      <td>Derni&egrave;re connexion récente </td>
      <td width="1" bgcolor="#000000"></td>
      <td>&nbsp;<img src="../images/time_3.gif"></td>
      <td>Derni&egrave;re connexion il y a + de 30 jours </td>
    </tr>
    <tr>
      <td><img src="../images/time_1.gif"></td>
      <td>Derni&egrave;re connexion il y a + de 8 jours </td>
      <td width="1" bgcolor="#000000"></td>
      <td>&nbsp;<img src="../images/time_4.gif"></td>
      <td>Derni&egrave;re connexion il y a + de 40 jours </td>
    </tr>
    <tr>
      <td><img src="../images/time_2.gif"></td>
      <td>Derni&egrave;re connexion il y a + de 15 jours </td>
      <td width="1" bgcolor="#000000"></td>
      <td>&nbsp;<img src="../images/time_5.gif"></td>
      <td>Derni&egrave;re connexion il y a + de 50 jours </td>
    </tr>
  </table> 

</body>
</html>