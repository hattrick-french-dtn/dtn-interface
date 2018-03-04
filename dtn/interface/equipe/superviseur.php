<?php 
require_once("../includes/head.inc.php");
require("../includes/serviceListesDiverses.php");

if(!$sesUser["idAdmin"])
{
	header("location: ../index.php?ErrorMsg=Session Expir�");
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
		require("../menu/menuDTNGestion.php");
		break;
}

$sql = "select * from $tbl_niveauAcces where idNiveauAcces = 2";
$req = $conn->query($sql);
$lstNA = $req->fetch(PDO::FETCH_ASSOC);


$lstPosition = listPosition();
?>

<?php


if(!isset($ordre)) $ordre = "affAdmin DESC,idPosition_fk,loginAdmin ";
if(!isset($sens)) $sens = "ASC";



?><title>Superviseur</title>
<form name="form1" method="post" action="../form.php">  
<table width="700" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td height="20" ><div align="center">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="20" bgcolor="#000000"><div align="center"><font color="#FFFFFF">Gestion
                  des superviseurs</font></div>
            </td>
          </tr>
          <tr>
            <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
          </tr>
          <tr>
            <td><br><table width="50%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td height="20">Login</td>
                  <td height="20"><input name="loginAdmin" type="text" id="loginAdmin"></td>
                </tr>
                <tr>
                  <td height="20">Mot de passe</td>
                  <td height="20"><input name="passAdmin" type="text" id="passAdmin"></td>
                </tr>
                <tr>
                  <td height="10">ID User Hattrick</td>
                  <td height="10"><input name="idAdminHT" type="text" id="idAdminHT"></td>
                </tr>
                <tr>
                  <td height="10">E-Mail</td>
                  <td height="10"><input name="emailAdmin" type="text" id="emailAdmin"></td>
                </tr>
                <tr>
                  <td height="20">Niveau d'acc&egrave;s</td>
                  <td height="20"><input type = "hidden" name="idNiveauAcces_fk" value = "<?=$lstNA["idNiveauAcces"]?>">
				  <?php
				  	echo $lstNA["IntituleNiveauAcces"];
				  ?>
                 </td>
                </tr>
                <tr>
                  <td height="20">Position supervis&eacute;</td>
                  <td height="20"><select name="idPosition_fk" id="idPosition_fk"><option value = "">DTN%</option>
                  <option value = "0">Tous Secteurs</option>
				  <?php
				  for($i=0;$i<count($lstPosition);$i++){
				  echo "<option value = ".$lstPosition[$i]["idPosition"].">".$lstPosition[$i]["intitulePosition"]."</option>";
				  
				  }
				  ?>
				  
                  </select></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2"><div align="center">
                    <input type="submit" name="Submit" value="Ajouter ce superviseur">
                    <input name="mode" type="hidden" id="mode" value="ajoutAdmin">
                    <input name="from" type="hidden" id="from" value="superviseur">
</div></td>
                  </tr>
              </table>
              <br><?php
			  if (isset($msg)) echo "<h3><center><font color = red>".stripslashes($msg)."</font></center></h2>";
			  ?></td>	
          </tr>
        </table>
      </div>
    </td>
  </tr>
</table>
</form>
<p>&nbsp;</p>
<table width="700" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td height="20" ><div align="center">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="20" bgcolor="#000000"><div align="center"><font color="#FFFFFF">Liste
                  des superviseurs</font></div>
            </td>
          </tr>
          <tr>
            <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="151" onClick = "chgTri('loginAdmin','<?=$sens?>')"><div align="left"><strong>&nbsp;Login</strong></div></td>
                <td width="1" bgcolor="#000000"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></td>
                <td width="135"onClick = "chgTri('passAdmin','<?=$sens?>')"><strong>&nbsp;Code d'acc&egrave;s</strong></td>
                <td width="1" bgcolor="#000000"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></td>
                <td width="119"onClick = "chgTri('intitulePosition','<?=$sens?>')"><div align="center"><strong>Cat&eacute;gorie</strong></div></td>
                <td width="1" bgcolor="#000000"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></td>
                <td width="106"><div align="center"><strong>Modifier</strong></div></td>
                <td width="1" bgcolor="#000000"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></td>
                <td width="96"><div align="center"><strong>Supprimer</strong></div></td>
                <td width="1" bgcolor="#000000"><strong><img src="../images/spacer.gif" width="1" height="1"></strong></td>
                <td width="96"  onClick = "chgTri('dateDerniereConnexion','<?=$sens?>')"><div align="center"><strong>Date derni&egrave;re connexion</strong></div></td>
              </tr>
			  
			  <?php
			  $i=0;
			  $sql = "select * from $tbl_admin  left join $tbl_position on idPosition = idPosition_fk where idNiveauAcces_fk = 2 ";
			  $sql .= "order by $ordre $sens";

			  foreach($conn->query($sql) as $l){
				if($i%2 == 0) $bgcolor = "E8E8E8"; else $bgcolor = "#FFFFFF";
			  ?>
			  
              <tr bgcolor="<?=$bgcolor?>">
                <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width="1" height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width="1" height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width="1" height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width="1" height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td width="1" height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
              </tr>
              <tr bgcolor="<?=$bgcolor?>">
                <td>&nbsp;<a href = "fiche.php?dtn=<?=$l["idAdmin"]?>"><?=$l["loginAdmin"]?></a></td>
                <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td>&nbsp;<?=$l["passAdmin"]?></td>
                <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td>&nbsp;<?=$l["intitulePosition"]?></td>
                <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
				
				
<?php
if($l["affAdmin"] == 1){

?>
 <td><div align="center"><a href="javascript:modifAdmin('<?=$l["idAdmin"]?>','dtn')"><img src="../images/carre-rouge.gif" width="10" height="10" border="0"></a></div></td>
                <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td><div align="center"><a href = "javascript:supprAdmin('<?=$l["idAdmin"]?>','dtn')"><img src="../images/carre-rouge.gif" width="10" border = "0" height="10"></a></div></td>
                <td width="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
                <td><div align="center"><?=dateToHTML($l["dateDerniereConnexion"])?></div></td>
             
<?php
} else echo "<td colspan='5'><center><a href = '../form.php?from=superviseur&mode=reactivAdmin&id=".$l["idAdmin"]."'>R&eacute;activer</a></center><td>";
?>
              </tr>
			  
			  <?php
			  $i++;
			  }
			  ?>
			  
			  
			  
            </table> 
            </td>
          </tr>
        </table>
      </div>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
<?php  deconnect(); ?>
