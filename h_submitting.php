<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />


<noscript><h1>Cette page nécessite Javascript. Merci d'activer Javascript dans votre explorateur et de rafraichir la page.</h1></noscript>

<form action="fff_help.php" method="post" enctype="application/x-www-form-urlencoded" name="submitPlayer">
  <div class="contenuJustifie">
    <?=OBJECTIF_IIIHELP?>
    <br /><br />
    <?=CRITERE_IIIHELP?>
    <br /><br />
  </div>


	<table border="0" align="center" cellpadding="0" cellspacing="0" width="600px">

	<tr>
	<td height="10" colspan="3"><img src="dtn/images/help_register_01_up.gif" width="100%" height="10" /></td>
	</tr>
	<tr>
	<td colspan="3" class="enteteiiiHelp"><?=ENTETE_FORMULAIRE_IIIHELP?></td>
	</tr>

	<tr>
	<td align="left" valign="middle"><br />Mail : </td>
	<td align="left" valign="middle"><br /><span id="sprytextfield1"><input name="email" type="text" id="email" style="width:180px" value="<?=$_POST['email']?>"/><span class="textfieldRequiredMsg"><br />Une valeur est requise.</span><span class="textfieldInvalidFormatMsg"><br />Format non valide.</span></span></td>
	<input name="action" type="hidden" value="submit_iiihelp"/><td height="40" align=right rowspan="8" valign="top"><input type="image" src="dtn/images/help_register_02_send.gif" /></td>
	</tr>

  <tr>
	<td align="left" valign="middle" ><?=ENTRAINEMENT_SOUHAITE_IIIHELP?> n°1 :</td>
	<td align="left" valign="middle">
		<select name="entrainement_voulu1" id="entrainement_voulu1">
		      <option value="99" <?php if ($_POST['entrainement_voulu1']=="99") {?>selected="selected"<?php }?>><?=LISTE_AUCUN_IIIHELP?></option>
		      <?php
		      for ($i=0;$i<count($liste_carac);$i++) {?>
            <option 
                value="<?php echo($liste_carac[$i]['idTypeCarac']);?>"  
                <?php if ($_POST['entrainement_voulu1']==$liste_carac[$i]['idTypeCarac']) {?>selected="selected"<?php }?> >
                <?php switch ($_SESSION['lang']) {
                  case "fr" :
                    echo($liste_carac[$i]['nomTypeCarac']);
                    break;
                  case "de" :
                    echo($liste_carac[$i]['nomTypeCarac_de']);
                    break;
                  case "en" :
                    echo($liste_carac[$i]['nomTypeCarac_en']);
                    break;
                  default :
                    echo($liste_carac[$i]['nomTypeCarac_en']);
                    break;
                } ?>
            </option>
          <?php }?>
		</select> <?=POUR_LES_IIIHELP?> : 
		<input type="checkbox" name="age_Entrainement1[]" value="1" <?php if ((isset($_POST['age_Entrainement1'])) && ((implode("",$_POST['age_Entrainement1'])=='1')||(implode("",$_POST['age_Entrainement1'])=='12'))){?>checked<?php }?>> <?=AGE_MOINS20_IIIHELP?>
		<input type="checkbox" name="age_Entrainement1[]" value="2" <?php if ((isset($_POST['age_Entrainement1'])) && ((implode("",$_POST['age_Entrainement1'])=='2')||(implode("",$_POST['age_Entrainement1'])=='12'))){?>checked<?php }?>> <?=AGE_PLUS20_IIIHELP?>
  </td>
  </tr>

	<tr>
	<td align="left" valign="middle" ><?=ENTRAINEMENT_SOUHAITE_IIIHELP?> n°2 :<br /><br /></td>
	<td align="left" valign="middle" nowrap>
		<select name="entrainement_voulu2" id="entrainement_voulu2">
    	    <option value="99" <?php if ($_POST['entrainement_voulu2']=="99") {?>selected="selected"<?php }?>><?=LISTE_AUCUN_IIIHELP?></option>
		      <?php
		      for ($i=0;$i<count($liste_carac);$i++) {?>
            <option 
                value="<?php echo($liste_carac[$i]['idTypeCarac']);?>"  
                <?php if ($_POST['entrainement_voulu2']==$liste_carac[$i]['idTypeCarac']) {?>selected="selected"<?php }?> >
                <?php switch ($_SESSION['lang']) {
                  case "fr" :
                    echo($liste_carac[$i]['nomTypeCarac']);
                    break;
                  case "de" :
                    echo($liste_carac[$i]['nomTypeCarac_de']);
                    break;
                  case "en" :
                    echo($liste_carac[$i]['nomTypeCarac_en']);
                    break;
                  default :
                    echo($liste_carac[$i]['nomTypeCarac_en']);
                    break;
                } ?>
            </option>
          <?php }?>
		</select> <?=POUR_LES_IIIHELP?> : 
		<input type="checkbox" name="age_Entrainement2[]" value="1" <?php if ((isset($_POST['age_Entrainement2'])) && ((implode("",$_POST['age_Entrainement2'])=='1')||(implode("",$_POST['age_Entrainement2'])=='12'))){?>checked<?php }?>> <?=AGE_MOINS20_IIIHELP?>
		<input type="checkbox" name="age_Entrainement2[]" value="2" <?php if ((isset($_POST['age_Entrainement2'])) && ((implode("",$_POST['age_Entrainement2'])=='2')||(implode("",$_POST['age_Entrainement2'])=='12'))){?>checked<?php }?>> <?=AGE_PLUS20_IIIHELP?>
		<br /><br />
  </td>
  </tr>

	<tr>
	<td colspan="2" bgcolor="#007DFF">&nbsp;</td>
	</tr>

	<tr>
	<td colspan="2" align="left" valign="middle"><br /><?=COMMENTAIRE_IIIHELP?><br /><textarea name="commentaire" id="commentaire" cols="48" rows="4"><?=$_POST['commentaire']?></textarea></td>
	</tr>

	<tr>
	<td height="20" colspan="3"><img src="dtn/images/help_register_03_down.gif" width="100%" height="20" /></td>
	</tr>

  </table>		  


  <br />
  <div align="justify" class="style40">
    <?=MODE_EMPLOI_IIIHELP?>
    <br /><br />
  </div>
</form>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "email", {validateOn:["blur"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["blur"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none", {validateOn:["blur"]});
//-->
</script>
