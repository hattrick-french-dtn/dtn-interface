<?php 
error_reporting(E_ALL);

require_once "../_config/CstGlobals.php"; // fonctions d'admin
require_once "../fonctions/phpxml.php"; // XML to Tree converter
require_once "../fonctions/AccesBase.php"; // fonction de connexion a la base
require_once "../fonctions/AdminDtn.php"; // fonctions d'admin
require_once("../includes/head.inc.php");
require("../includes/serviceListesDiverses.php");
require("../includes/serviceEntrainement.php");
require("../includes/serviceJoueur.php");
require("../includes/langue.inc.php");

$maBase = initBD();


if(!$sesUser["idAdmin"])
	{
	header("location: index.php?ErrorMsg=Session Expiree");
	}



if(!isset($idClubHT)){
	header("location: ../index.php?Msg=id_club_introuvable");
}

$sqlpays="select nomPays FROM ht_pays, ht_clubs  WHERE idPays_fk = idPays and idClubHT=$idClubHT and idPays!=0 ";
$pays=$maBase->select($sqlpays);
if (count($pays)>0){
	$nomPays=$pays[0]["nomPays"];
}else{
	$nomPays="inconnu";
}

$sqlclubHT="select idClubHT,nomClub,niv_Entraineur,intituleCaracFR,isBot,date_last_connexion
            FROM  ht_clubs,ht_caracteristiques
            WHERE  idClubHT=$idClubHT 
            and ht_clubs.niv_Entraineur =ht_caracteristiques.idCarac";
$zeclub=$maBase->select($sqlclubHT);
if (count($zeclub)>0){
	$idClubHT=$zeclub[0]["idClubHT"];
	$nomClub=$zeclub[0]["nomClub"];
	$niv_Entraineur=$zeclub[0]["niv_Entraineur"];
	$Lib_niv_Entraineur=$zeclub[0]["intituleCaracFR"];
	$isBot=$zeclub[0]["isBot"];
	$date_connexion=$zeclub[0]["date_last_connexion"];
}else{
	$idClubHT="?";
	$nomClub="?";
	$niv_Entraineur="?";
	$Lib_niv_Entraineur="?";
	$isBot="";
}




?><html><title>[ht-fff] <?=$nomClub?> ( <?=$idClubHT?> )</title><?php


switch($sesUser["idNiveauAcces"]){
		case "1":
		require("../menu/menuAdmin.php");
		break;
		
		case "2":
		require("../menu/menuSuperviseur.php");
		break;


		case "3":
		require("../menu/menuDTN.php");
		break;
		
		case "4":
		require("../menu/menuCoach.php");
		break;
		
		default;
		break;


}


$lstPos = listAllPosition();
$lstTypeCarac = listTypeCarac();
$lstCarac = listCarac("ASC",23);
$lstTrain=listEntrainement();


$AgeAnneeSQL=getCalculAgeAnneeSQL();
$AgeJourSQL=getCalculAgeJourSQL();

$sql="select $tbl_joueurs.*,".$AgeAnneeSQL." as AgeAn,".$AgeJourSQL." as AgeJour 
      from $tbl_joueurs " .
     " LEFT JOIN $tbl_clubs ON (idClub = teamid ) WHERE teamid=$idClubHT  group by idHattrickJoueur ";
?>
<table border=0 width=100%>
<tr><td align="left" nospan>&nbsp;<A class="smliensorange" href="javascript:history.go(-1);" class="btn" alt="retour">Retour</a>&nbsp;
</td></tr></table>

<script language="JavaScript" src="../includes/javascript/navigation.js"></script>

<style type="text/css">
<!--
.Style1 {color: #FFFFFF}
.Style3 {color: #FF9933}
.Style4 {color: #FF0000}
-->
</style>
<body>
<br>


<p>
<?php

 
$retour = $maBase->select($sql);
$nbjoueur=count($retour);
$sql=$sql."	LIMIT 0,50;";



$lstJ = $maBase->select($sql);

	$huit = 60 * 60 * 24 * 8; //time_0
	$quinze = 60 * 60 * 24 * 15; //time_1
	$trente = 60 * 60 * 24 * 30; //time_2
	$twomonths = 60 * 60 * 24 * 40; //time_3
	$fourmonths = 60 * 60 * 24 * 50; //time_4
			  
	// Date du jour
	$mkday = mktime(0,0,0,date('m'), date('d'),date('Y'));

	// Date de la dernière connexion du club 
	$date = explode("-",$date_connexion);
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
	$nbjour=($mkday-$datemaj)/(60*60*24);

?>
<TABLE BORDER="0" CELLPADDING="2" CELLSPACING="0" WIDTH="85%">
		<TR bgcolor="#ffffdd">

				<TD align="center">
        <b>Club : </b><span class="breadvar"><B><?=$nomClub?> ( <?=$idClubHT?> ) <img src="../images/time_<?=$img_nb?>.gif"></B></span> (Dernière connexion il y a <?=$nbjour?> jours)
        <?php if (existAutorisationClub($idClubHT,null)==false) {?>
			<img src="../images/non_autorise.JPG" title="Ce club n'a pas autoris&eacute; la DTN &agrave; acc&eacute;der &agrave; ses donn&eacute;es">
        <?php } else {?>
			<img src="../images/Autorise.PNG" title="Ce club a autoris&eacute; la DTN &agrave; acc&eacute;der &agrave; ses donn&eacute;es">
		<?php }?> 
        </TD>
				<TD align="center">&nbsp; &nbsp; Pays du club:&nbsp; <B><?=$nomPays?></B></TD>
		</TR>
		<TR>
		  <TD><BR><?php if ($isBot!=0) {echo '<BR><BR><b><font color="red">ATTENTION ! Ce club est devenu un bot !!</b></font><BR><BR>';}?><BR></TD>
		</TR>
		<TR <?php if ($niv_Entraineur<7) {?> bgcolor="#FF3300"	<?php } else { ?> bgcolor="#00CC00" <?php } ?> >
 		    <TD colspan="2" align="center"><B>Entraineur:</B> <?=$Lib_niv_Entraineur?> ( <?=$niv_Entraineur?> ) </TD>
		</TR>

			
<?php
		if(count($lstJ)>0) {
			$j=0;
			while ($j<count($lstJ)){
				$sqlscout="select loginAdmin FROM ht_joueurs, ht_admin  WHERE idHattrickJoueur = '".$lstJ[$j]["idHattrickJoueur"]."' AND dtnSuiviJoueur_fk=idAdmin ";
				$scout=$maBase->select($sqlscout);
				$dtnDuJoueur="<i>[personne &agrave; d&eacute;finir]</i>";
				if (count($scout)>0){
					$dtnDuJoueur=$scout[0]["loginAdmin"];
				}

				$sqlClubActuelTid="select nomClub FROM ht_joueurs, ht_clubs  WHERE idHattrickJoueur = '".$lstJ[$j]["idHattrickJoueur"]."' AND idClubHT=teamid  ";
				$clubtid=$maBase->select($sqlClubActuelTid);
				$nomClubActuelTid="?";
				if (count($clubtid)>0){
					$nomClubActuelTid=$clubtid[0]["nomClub"];
				}
				
	  	        $class = "#";
			    $quinze = 60 * 60 * 24 * 15;
			    $trente = 60 * 60 * 24 * 30;
			    $date = explode("-",$lstJ[$j]["dateDerniereModifJoueur"]);
			 // Date de la dernier modif de ce joueur
			  $mkJoueur =  mktime(0,0,0,$date[1],$date[2],$date[0]); 
			  // Date du jour
			 $mkDay = mktime(0,0,0,date('m'), date('d'),date('Y'));
			 $d1 =  $mkDay - $quinze;
			 $d2 =  $mkDay - $trente;
			if($mkJoueur >  $d1) $class= "#"; 
			else if( $mkJoueur > $d2 && $mkJoueur < $d1 ) $class = "style3";	
			else if($mkJoueur < $d2) $class = "style4";
				
				
				
?>
		
			<TR>
				<TD ><BR>
					<font color="#CC2233"><?=$j+1?>.</font> <A HREF="../joueurs/fiche.php?id=<?=$lstJ[$j]["idJoueur"]?>"><?=$lstJ[$j]["prenomJoueur"]?> <?=$lstJ[$j]["nomJoueur"]?> </A>&nbsp; 
					  <?php if($lstJ[$j]["optionJoueur"]) echo "<font color=\"#CC22DD\">[<i>".$option[$lstJ[$j]["optionJoueur"]]["FR"]."</i>]</font>"?>
				</TD>
				<TD >
					<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0">
					<TR>
						<TD width=25% ></TD>
						<TD width=25% ><B>&nbsp; &nbsp; Club Actuel :&nbsp;</B></TD> <TD  width=50% > <?=$nomClubActuelTid?> </TD>
					</TR>
					</TABLE>
				</td>
			</TR>
			
			<TR>
				<TD VALIGN="top">

					<B>Suivi par </B> <?=$dtnDuJoueur?><BR>
					<B>Age:&nbsp; </B><?=$lstJ[$j]["AgeAn"]?> ans / <b>XP</b> : <?=$lstCarac[$lstJ[$j]["idExperience_fk"]]["intituleCaracFR"]?><BR>
					<B><b>id</b> : ( <?=$lstJ[$j]["idHattrickJoueur"]?> )<BR>					
					<BR>
				</TD>
				<TD VALIGN="top">								
					<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0">
					<TR>
						<TD width=25% ><B>Note Gardien:&nbsp; </B></TD><TD width=25% > <?=$lstJ[$j]["scoreGardien"]?></TD>
						<TD width=25% ><B>&nbsp; &nbsp; Note D&eacute;fense:&nbsp;</B></TD> <TD  width=25% ><?=$lstJ[$j]["scoreDefense"]?></TD>

					</TR>
					<TR>
						<TD><B>Note Ailier:&nbsp; </B></TD><TD ><?=$lstJ[$j]["scoreAilierOff"]?></TD>
						<TD><B>&nbsp; &nbsp; Note Milieu:&nbsp; </B></TD><TD ><?=$lstJ[$j]["scoreMilieu"]?></TD>
					</TR>

					<TR>
						<TD><B>Note Attaquant: &nbsp;</B></TD> <TD ><?=$lstJ[$j]["scoreAttaquant"]?></TD>
						<TD><B>&nbsp; &nbsp; Mis &agrave; jour : &nbsp; </B></TD><TD ><span class=<?=$class?>> <?=$lstJ[$j]["dateDerniereModifJoueur"]?> </span></TD>
					</TR>
				<?php
				if($lstJ[$j]["archiveJoueur"] == 1){?>
					<tr><td colspan=2><font color="#FF0000"><strong>Ce joueur est archiv&eacute;&nbsp;</strong></font></td></tr>
				<?php } ?>
					</TABLE>
				</TD>

			</TR>
<?php
				$j=$j+1;
			}// fin while j<count(lstJ)
		}// fin if count(lstJ)>0
?>
		<TR>

				<TD COLSPAN="2"  ALIGN="center">
				&nbsp; 



				</TD>
			</TR>
<tr><td colspan=2>
<p align=left>
  
          <table width="100%" style="border:1px solid #C5C7C7" align="center" cellpadding="0" cellspacing="1" rules=COLS>
            <tr bgcolor="#85A275">
              <td width=12%><div align="center" style="font-size: 9pt;color: white"><b>Date</b></div></td>
              <td width=10%><div align="center" style="font-size: 9pt;color: white"><b>Créé par</b></div></td>
              <td width=8%><div align="center" style="font-size: 9pt;color: white"><b>Entrainement</b></div></td>
              <td width=7%><div align="center" style="font-size: 9pt;color: white"><b>Intensit&eacute;</b></div></td>
              <td width=5%><div align="center" style="font-size: 9pt;color: white"><b>Endu</b></div></td>
              <td width=5%><div align="center" style="font-size: 9pt;color: white"><b>Adj.</b></div></td>
              <td width=5%><div align="center" style="font-size: 9pt;color: white"><b>Medecin</b></div></td>
              <td width=5%><div align="center" style="font-size: 9pt;color: white"><b>Prepa. phys.</b></div></td>
              <td width=43%><div align="center" style="font-size: 9pt;color: white"><b>Info club [<?=$nomClub?>]</b></div></td>
            </tr>
            <?php $sqlClubsHisto = " SELECT DATE_FORMAT(ht_clubs_histo.date_histo,'%d/%m/%Y %H:%i:%s') AS date_histo,
                                        ht_clubs_histo.role_createur,
                                        ht_clubs_histo.cree_par,
                                        ht_type_entrainement.libelle_type_entrainement AS CaracEntraine,
                                        ht_clubs_histo.intensite,
                                        ht_clubs_histo.endurance,
                                        ht_clubs_histo.adjoints,
                                        ht_clubs_histo.medecin,
                                        ht_clubs_histo.physio,
                                        ht_clubs_histo.Commentaire,
                                        ht_clubs_histo.intensite
                                FROM    ($tbl_clubs_histo 
                                      INNER JOIN 
                                        $tbl_clubs
                                        ON ht_clubs.idClubHT=ht_clubs_histo.idClubHT)
                                      LEFT JOIN    
                                        $tbl_type_entrainement2
                                        ON ht_clubs_histo.idEntrainement=ht_type_entrainement.id_type_entrainement
                                WHERE ht_clubs_histo.idClubHT=".$idClubHT." 
                                ORDER BY ht_clubs_histo.date_histo desc ";
      
            $i=1;
            foreach($conn->query($sqlClubsHisto) as $lHisto){
              $lHisto["createur"]="";
              if ($lHisto["role_createur"]=="D") {$lHisto["createur"]='[DTN]';}
              else if ($lHisto["role_createur"]=="P") {$lHisto["createur"]='[Proprio]';}
              $lHisto["createur"].=$lHisto["cree_par"];?>
          
              <tr <?php if ($i % 2 == 0) {?>bgcolor="#EEEEEE"<?php } else {?>bgcolor="#FFFFFF"<?php }?>>
                <td > <div align="center"><?=$lHisto["date_histo"]?></div></td>
                <td > <div align="left"><?=$lHisto["createur"]?></div></td>
                <td > <div align="left"><?=$lHisto["CaracEntraine"]?></div></td>
                <td > <div align="center"><?=$lHisto["intensite"]?></div></td>
                <td > <div align="center"><?=$lHisto["endurance"]?></div></td>
                <td > <div align="center"><?=$lHisto["adjoints"]?></div></td>
                <td > <div align="center"><?=$lHisto["medecin"]?></div></td>
                <td > <div align="center"><?=$lHisto["physio"]?></div></td>
                <td > <div align="left"><?=$lHisto["Commentaire"]?></div></td>
              </tr>
              <?php $i++;
            }?>
          </table>

      </p>
      <hr width=100%>
                 
      <p>
		
		</td></tr>
  </table>
  <p align="center"><a href="javascript:history.go(-1);">Retour</a> <br>
    </p>
  
  <br>
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
