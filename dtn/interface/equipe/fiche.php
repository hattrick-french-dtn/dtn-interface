<?php 
require("../includes/head.inc.php");
require("../includes/serviceDTN.php");

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

}

$infDTN = getDTN($dtn);


$sql = $conn->query("select * from $tbl_histomodif, $tbl_admin where idAdmin = $dtn and idAdmin = idAdmin_fk ");
$numMaxHisto = $sql->rowCount();



if(!isset($nbParPage)) $nbParPage = 25;
if(!isset($numEnr)) $numEnr = 0;


$next = $numEnr+$nbParPage;
$prev = $numEnr-$nbParPage;



?><title>DTN</title>
<style type="text/css">
<!--
.Style1 {color: #FFFFFF}
-->
</style>
<br>
<br>
<table width="600"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr bgcolor="#000000">
    <td height="30" colspan="2"><div align="center"><span class="Style1"><?=$infDTN["loginAdmin"]?></span></div></td>
  </tr>
  <tr>
    <td width="154"><div align="center"><br>
    Derniere connexion : </div></td>
    <td width="446"><br>      <?=dateToHTML($infDTN["dateDerniereConnexion"])?> - <?=($infDTN["heureDerniereConnexion"])?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td height="1" colspan="2" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
      </tr>
      <tr>
        <td width="1" bgcolor="#000000">
          <div align="center"><img src="/images/spacer.gif" width="1" height="1"></div></td>
        <td width="70"><div align="center">date</div></td>
        <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td width="91"><div align="center">heure</div></td>
        <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td width="918"><div align="center">libell&eacute;</div></td>
        <td width="1" bgcolor="#000000"><img src="/images/spacer.gif" width="1" height="1"></td>
      </tr>
      <tr>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
      </tr>
      <?php
		$sqls = "select * from $tbl_histomodif, $tbl_admin where idAdmin = $dtn and idAdmin = idAdmin_fk order by dateHisto desc, heureHisto desc ";
		$sqls .= " limit $numEnr, $nbParPage";
		foreach($conn->query($sqls) as $l){
		   
		   
		   ?>
      <tr>
        <td width="1" height="20" bgcolor="#000000">
          <div align="center"><img src="/images/spacer.gif" width="1" height="1"></div></td>
        <td width="70" height="20"> <div align="center"><?=dateToHTML($l["dateHisto"])?></div></td>
        <td width="1" height="20" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td width="91" height="20">
          <div align="center">
            <?=$l["heureHisto"]?>
        </div></td>
        <td width="1" height="20" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td width="918" height="20">
          <div align="left">&nbsp;
              <?=$l["intituleHisto"]?>
        </div></td>
        <td width="1" height="20" bgcolor="#000000"><img src="/images/spacer.gif" width="1" height="1"></td>
      </tr>
      <?php } ?>
	        <tr>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
        <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
      </tr>

    </table>
      <br>
      <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="26%" height="18">
          <?php
		  
if($numEnr >= $nbParPage){
echo "<a href = '?from=$from&dtn=$dtn&numEnr=$prev'>Pr&eacute;c&eacute;dent</A>";
}
		  ?>
        </td>
        <td width="36%"> </td>
        <td width="38%"><div align="right">
            <?php
if($numEnr +$nbParPage < $numMaxHisto){
echo "<a href = '?from=$from&dtn=$dtn&numEnr=$next'>Suivant</A>";
}
?>
        </div></td>
      </tr>
      <tr>
        <td height="18">&nbsp;</td>
        <td></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="18" colspan="3"><div align="center"><a href="javascript:history.go(-1)">Retour page pr&eacute;c&eacute;dente</a></div></td>
        </tr>
    </table></td>
  </tr>
</table>
