<?php 
require_once("../includes/head.inc.php");
require("../includes/serviceEquipes.php");
require("../includes/serviceListesDiverses.php");


if(!$sesUser["idAdmin"])
{
	header("location: index.php?ErrorMsg=Session Expir�");
}
if(!isset($ordre)) $ordre = "nomJoueur";
if(!isset($sens)) $sens = "ASC";
if(!isset($lang)) $lang = "FR";


require("../includes/langue.inc.php");



$lstAgg = listAggres();
$lstCarac = listCaractere();
$lstHon = listHonnetete();
$lstLeader = listLeadership();
//



switch($sesUser["idNiveauAcces_fk"]){


case 1:
case 4:
// Full Acces
$sqlJ = "SELECT * FROM ht_joueurs  WHERE  affJoueur = 1 ";
$sqlJ .= "  order by $ordre $sens";


break;


case "2":
// Acc�s superviseur
// Acces limit� au joueur qu'il a soumis et au joueurs de son poste 


if($sesUser["idPosition_fk"] != ""){
$sqlJ = "SELECT * FROM ht_joueurs  WHERE  affJoueur = 1 AND (ht_posteAssigne = ".$sesUser["idPosition_fk"]." OR AdminSaisieJoueur_fk = ".$sesUser["idAdmin"].") ";
$sqlJ .= "  ORDER BY $ordre $sens";
}
else {
$sqlJ = "SELECT * FROM ht_joueurs  WHERE  affJoueur = 1  ";
$sqlJ .= "  ORDER BY $ordre $sens";
}






break;




case "3":
// Acces qu'au joueur qu'ils ont cr�e et qu'ils n'ont pas �t� valid�
$sqlJ = "SELECT * FROM ht_joueurs  WHERE  affJoueur = 1 AND joueurActif = 0 AND AdminSaisieJoueur_fk = ".$sesUser["idAdmin"]." ";
$sqlJ .= "  order by $ordre $sens";
break;




}

?>
<link href="../css/ht.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<?php
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


}










?><title>Superviseur</title>
<script language="JavaScript" type="text/JavaScript">
<!--
<!--


function fiche(id,url){
	
document.location='<?=$url?>/joueurs/fiche.php?url='+url+'&id='+id
}


//-->
function init()
{
var scrollPos = "<?=$scrollPos?>";
document.body.scrollTop = scrollPos;


}//-->


</script>
<body onLoad = "init();">
<table width="700" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td height="20" ><div align="center">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="20" bgcolor="#000000"><div align="center"><font color="#FFFFFF">Liste
                  des joueurs</font></div>
            </td>
          </tr>
          <tr>
            <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td width="289" height="20" onClick="chgTri('nomJoueur','<?=$sens?>')"><strong>&nbsp;Identit&eacute;</strong></td>
                  <td width="1" bgcolor="#000000" onClick="chgTri('nomJoueur','<?=$sens?>')"><img src="../images/spacer.gif" width="1" height="1"></td>
                  <td width="119" onClick="chgTri('nomJoueur','<?=$sens?>')"><div align="center"><strong>Fiche 
                      joueur </strong> :</div></td>
                  <td width="1" bgcolor="#000000"><strong><img src="../images/spacer.gif" width="1" height="1"> 
                    </strong></td>
                  <td width="127"><div align="center"><strong>Modifier</strong></div></td>
                  <td width="1" bgcolor="#000000"><div align="center"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></div></td>
                  <td width="132"><div align="center"><strong>Archiver</strong></div></td>
                </tr>
		<?php
		
		$lst = 1;
		foreach($conn->query($sqlJ) as $lstJoueurs){

			switch($lst){
			case 1:
			$bgcolor = "#D8D8D8";
			$lst = 0;
			break;
			
			case 0:
			$bgcolor = "white";
			$lst = 1;
			break;
			}
			
			?>
                <tr bgcolor="#000000"> 
                  <td colspan="3"><img src="../images/spacer.gif" width="1" height="1"></td>
                  <td width="1"><img src="../images/spacer.gif" width="1" height="1"></td>
                  <td><img src="../images/spacer.gif" width="1" height="1"></td>
                  <td width="1"><img src="../images/spacer.gif" width="1" height="1"></td>
                  <td><img src="../images/spacer.gif" width="1" height="1"></td>
                </tr>
                <tr bgcolor="<?=$bgcolor?>"> 
                  <td>&nbsp; 
                    <?=$lstJoueurs["nomJoueur"]?>
    <?=$lstJoueurs["prenomJoueur"]?>

                  </td>
                  <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                  <td onClick="javascript:fiche('<?=$lstJoueurs["idJoueur"]?>')"><div align="center"><a href="javascript:fiche('<?=$lstJoueurs["idJoueur"]?>');"><img src="../images/carre-rouge.gif" width="10" height="10" border="0"></a></div></td>
                  <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                  
				  <?php
				  $aff = false;
				  if($sesUser["idNiveauAcces_fk"] == 1) {
				  $aff = true;
				  }
				  else if($sesUser["idNiveauAcces_fk"] == 2) {
				  		if($sesUser["idPosition_fk"] == $lstJoueurs["ht_posteAssigne"] || $sesUser["idPosition_fk"] == "") $aff = true;
				  }
				  else if($sesUser["idNiveauAcces_fk"] == 3) {
				  		if($lstJoueurs["joueurActif"] == 0 && $sesUser["idAdmin"] == $lstJoueur["AdminSaisieJoueur_fk"]) $aff = true;
				  }
				  
			if($aff == true){				  ?>
				  <td onClick="javascript:modifier('<?=$lstJoueurs["idJoueur"]?>','modifJoueur')"><div align="center"><a href = "javascript:modifier('<?=$lstJoueurs["idJoueur"]?>','modifJoueur')"><img src="../images/carre-rouge.gif" border = "0" width="10" height="10"></a></div></td>
				  <?php
				  }
				  else echo "<td>&nbsp;</td>";
				  ?>
				  
                  <td width="1" bgcolor="#000000"><div align="center"><img src="../images/spacer.gif" width="1" height="1"></div></td>
                
			
				  <td bgcolor="<?=$bgcolor?>"><?php if($aff == true){				  ?><div align="center"><a href = "javascript:supprimer('<?=$lstJoueurs["idJoueur"]?>','archiveJoueur')"><img src="../images/carre-rouge.gif" border = "0" width="10" height="10"></a></div><?php }			  ?></td>
				
			  
				  
                </tr>
                <?php
			  }
			  ?>
              </table></td>
          </tr>
        </table>

      </div>
    </td>
  </tr>
</table>
</body>
