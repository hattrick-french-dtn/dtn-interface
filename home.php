<?php if ( isset($_SESSION['HT']) ) {
  include("dtn_scan_team.php");
} else { ?>
  <div id="contenu">
  	<table width="760" border="0" align="center" cellpadding="0" cellspacing="0">
  		<tr>
  			<td rowspan="4" width="20">&nbsp;</td>
  			<td class="style40" align="left"><br />
  				<?php echo INTRO_HTFFF;?>
          <br /><br />
          <?php echo INTRO_HTFFF2;?>
          <br />
          <?php echo OBJECTIF_AUTORISATION_ACCES;?>
          <br />
          <?php echo EXPLICATION_AUTORISATION_ACCES;?>
          <br />
          <?php echo PRISE_CONTACT;?>
          <br /><br />
    		</td>
      </tr>
    	<tr>
      	<td class="style40">
      		<form name="formConnexionHT" method="get" action="">
      		  <input name="mode" type="hidden" value="redirectionHT">
      		  <input type="submit" value="<?php echo TEXTE_BOUTON_AUTORISATION;?>" class="bouton" /> <br /><br />
            <input type="checkbox" name="connexion_permanente" value="1" checked />&nbsp;<?php echo SESS_ACTIVE;?><br />
          </form>
      	</td>
      </tr>
      <tr>
        <td class="detailInfo">
        <img src="images/info.PNG" title="Information" alt="Information" height="20" width="20" />&nbsp;<?php echo EXPLICATION_SESS_ACTIVE;?>
        </td>
      </tr>
    	<tr>
        <td class="style40" align="right">     
          <br />
          <b><?php echo MESSAGE_BIENVENUE;?>&nbsp;&nbsp;&nbsp;</b>
          <br />
        </td>
      </tr>
    </table>
  </div>
<?php }?>