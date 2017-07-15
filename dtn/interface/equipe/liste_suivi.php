<?php 
require_once("../includes/head.inc.php");


if(!$sesUser["idAdmin"])
	{
	header("location: ../entry.php?ErrorMsg=Session Expiree");
	}


?>
<link href="../css/ht.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<?php
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
		break;
		
		case "4":
		break;
		
		default;
		break;


}



$sql = "select * from ht_joueurs where dtnSuiviJoueur_fk ='".$dtn."' ";
$req = $conn->query($sql);


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
            <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
          </tr>
          <tr>
            <td><div align="center">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="450" onclick = "chgTri('loginAdmin','<?=$sens?>')"><div align="left"><strong> &nbsp;(id) Nom</strong></div></td>
                    <td width="1" bgcolor="#000000"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></td>
                    <td width="150"onclick = "chgTri('dateDerniereConnexion ','<?=$sens?>')"><div align="center"><strong>&nbsp;Derni&egrave;re mise &agrave; jour</strong></div></td>
                    <td width="1" bgcolor="#000000"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></td>
                    <td><div align="center"><strong><CENTER>
                      Voir la fiche
                    </CENTER> </strong></div></td>
                    <td width="1" bgcolor="#000000"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></td>
                    
              </tr>
			      
			  <?php
			 foreach($req as $lstJoueur){
			  ?>
			      
              <tr>
                    <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td width="1" height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td width="1" height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td width="1" height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                  </tr>
                  <tr>  
                    <td>&nbsp;<a href = "fiche.php?dtn=<?=$lstJoueur["idJoueur"]?>">(<?=$lstJoueur["idHattrickJoueur"]?>)&nbsp;<?=$lstJoueur["prenomJoueur"]?> <?=$lstJoueur["nomJoueur"]?></a></td>
                    <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td>                     <CENTER> <?=($lstJoueur["dateDerniereModifJoueur"])?>
                    </CENTER>             </td>
                    <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                    <td><CENTER>
                      <a href ="javascript:fiche('<?=$lstJoueur["idJoueur"]?>')">Voir </a>
                    </CENTER></td>
                    <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                </tr>
			      
			  <?php
			  }
			  ?>
			      
			  
			      
          </table>
            </div></td>
          </tr>
        </table>
      </div>    </td>
  </tr>
</table>
<p>&nbsp;</p>
