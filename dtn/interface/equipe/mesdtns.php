<?php 
require_once "../fonctions/AccesBase.php"; // fonction de connexion ? la base
require_once "../fonctions/AdminDtn.php"; // fonctions d'admin
require_once("../includes/head.inc.php");
require_once("../includes/serviceListesDiverses.php");
require_once "../_config/CstGlobals.php";


if(!$sesUser["idAdmin"])
{
	header("location: ../index.php?ErrorMsg=Session Expiree");
}


?>
<link href="../css/ht.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<?php
switch($sesUser["idNiveauAcces"]){
	case "1":
		require("../menu/menuAdmin.php");
		break;
		
	case "2":
		require("../menu/menuSuperviseur.php");
		require("../menu/menuSuperviseurGestion.php");
		break;

	case "3":
		require("../menu/menuDTN.php");
		exit;
		break;
		
	case "4":
		require("../menu/menuCoach.php");
		exit;
		break;
		
	default;
		break;

}



if(!isset($ordre)) $ordre = "loginAdmin";
if(!isset($sens)) $sens = "ASC";
if(!isset($lesmails)) $lesmails = "";

$sql = "select * from $tbl_admin  left join $tbl_position on idPosition = idPosition_fk where idPosition_fk = ".$sesUser["idPosition_fk"]." and affAdmin!=0 ";
$sql .= "order by $ordre $sens";
$req = $conn->query($sql);

$huit = 60 * 60 * 24 * 8; //time_0
$quinze = 60 * 60 * 24 * 15; //time_1
$trente = 60 * 60 * 24 * 30; //time_2
$twomonths = 60 * 60 * 24 * 60; //time_3
$fourmonths = 60 * 60 * 24 * 120; //time_4

// Date du jour
$mkday = mktime(0,0,0,date('m'), date('d'),date('Y'));

?><title>DTN</title>
<p>&nbsp;</p>
<table width="700" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td height="55" ><div align="center">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="20" bgcolor="#000000"><div align="center"><font color="#FFFFFF">Liste de mes DTNs</font></div>
            </td>
          </tr>
          <tr>
            <td><div align="center">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="150" onclick = "chgTri('loginAdmin','<?=$sens?>')"><div align="left"><strong>&nbsp;Login</strong></div></td>
                    <td width="1" bgcolor="#000000"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></td>
                    <td width="150"onclick = "chgTri('dateDerniereConnexion ','<?=$sens?>')"><div align="center"><strong>&nbsp;Derni&egrave;re connexion</strong></div></td>
                    <td width="1" bgcolor="#000000"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></td>
                    <td><div align="center"><strong><CENTER>Voir la liste</CENTER> </strong></div></td>
                    <td width="1" bgcolor="#000000"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></td>
                    <td><div align="center"><strong><CENTER>Nb joueurs suivis</CENTER> </strong></div></td>
                    <td width="1" bgcolor="#000000"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></td>
					<td><div align="center"><strong><CENTER>ID User Hattrick</CENTER> </strong></div></td>
                    <td width="1" bgcolor="#000000"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></td>
                    
              </tr>
			      
			  <?php
				$j=0;
				while($lstSuperviseur = $req->fetch(PDO::FETCH_ASSOC)){
			 		if($j%2 == 0) $bgcolor = "#e8e8e8"; else $bgcolor="#ffffff";

					$sqlnb = "select count(*) from ht_joueurs where dtnSuiviJoueur_fk ='".$lstSuperviseur["idAdmin"]."' ";
					$nbjsuivis=current($conn->query($sqlnb)->fetch(PDO::FETCH_ASSOC));


					$date = explode("-",$lstSuperviseur["dateDerniereConnexion"]);
					$datemaj= mktime(0,0,0,$date[1],$date[2],$date[0]);
			
					$img_nb=0;
					if ($datemaj >$mkday -$huit){
						$img_nb=0;
						$strtiming="moins de 8 jours";	
					}else if ($datemaj >$mkday -$quinze){
						$img_nb=1;
						$strtiming="moins de 15 jours";
					}else if ($datemaj >$mkday -$trente){
						$img_nb=2;
						$strtiming="moins de 30 jours";
						
					}else if ($datemaj >$mkday -$twomonths){
						$img_nb=3;
						$strtiming="moins de 2 mois";
						
					}else if ($datemaj >$mkday -$fourmonths){
						$img_nb=4;
						$strtiming="moins de 4 mois";
					 
					}else{
							$img_nb=5;
						$strtiming="plus que 4 mois";
					}
			 
					// Date de la dernier modif de ce joueur
					$zealt="[ Connect&eacute; il y a  ".round(($mkday - $datemaj)/(60*60*24) )." jours ] ";

			  ?>
			      
                  <tr bgcolor="<?=$bgcolor;?>">
                    <td>&nbsp;<a href = "fiche.php?dtn=<?=$lstSuperviseur["idAdmin"]?>" class=bred1><?=$lstSuperviseur["loginAdmin"]?></a>
                    &nbsp;<img src="../images/time_<?=$img_nb?>.gif" onmouseover="return escape('<?=$zealt?>')" >
                    
                    </td>
                    <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td>                     <CENTER> <?=dateToHTML($lstSuperviseur["dateDerniereConnexion"])?>
                      - 
                      <?=$lstSuperviseur["heureDerniereConnexion"]?>       </CENTER>             
                      </td>
                    <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td><CENTER>
                      <a href="../joueurs/liste_suivi.php?dtn=<?=$lstSuperviseur["idAdmin"]?>">Voir les joueurs suivis</a>
                    </CENTER></td>
                    <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td><div align="center"><?=$nbjsuivis?></td>
                    <td width="1" bgcolor="#000000"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></td>
                    <td><div align="center"><?=$lstSuperviseur["idAdminHT"]?></td>
                    <td width="1" bgcolor="#000000"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></td>
                </tr>
			      
			  <?php
			  $lesmails.=$lstSuperviseur["loginAdmin"]."; ";
			  $j++;
			  }
			  ?>
			      
			  
			      
          </table>
            </div></td>
          </tr>
        </table>
      </div>    </td>
  </tr>
</table>
<br>
<div align="center">
Tous les DTNs :<br>
<font face="arial" size="8"><textarea READONLY name="touslesmails" style="font-size:8pt;font-family:Arial" rows="3" cols="132"><?=$lesmails?></textarea></font>
</div>
<br>
<?php  deconnect(); ?>

<table width="450"  border="0" align="center" cellspacing=0 >
    <tr>
      <td colspan=5 width="450" ><center>L&eacute;gende : </center></td>
      
    </tr>
    <tr>
      <td><img src="../images/time_0.gif"></td>
      <td>Joueur mis &agrave; jour r&eacute;cemment </td>
      <td width="1" bgcolor="#000000"></td>
      <td>&nbsp;<img src="../images/time_3.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 30 jours </td>
    </tr>
    <tr>
      <td><img src="../images/time_1.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 8 jours </td>
      <td width="1" bgcolor="#000000"></td>
      <td>&nbsp;<img src="../images/time_4.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 60 jours </td>
    </tr>
    <tr>
      <td><img src="../images/time_2.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 15 jours </td>
      <td width="1" bgcolor="#000000"></td>
      <td>&nbsp;<img src="../images/time_5.gif"></td>
      <td>Joueur mis &agrave; jour il y a + de 120 jours </td>
    </tr>
  </table>

<script language="JavaScript" type="text/javascript" src="../includes/javascript/tooltips.js"></script>

</body></html>
