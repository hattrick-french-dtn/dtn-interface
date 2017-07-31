<?php
  /* Mise à jour de la zone teamid en vue de la suppression de idClubActuelJoueur_fk
   *   [bug 0000033]
   */

   //Service réservé aux Administrateurs
   if($sesUser["idNiveauAcces"] == 1){
?>
<form name="idclubht" method="post" action="<?=$_SERVER['REQUEST_URI']?>">
<table width="700" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td height="20" ><div align="center">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
              <td height="20" bgcolor="#000000">
<div align="center"><font color="#FFFFFF">Mise à jour zone teamid</font></div>
            </td>
          </tr>
          <tr>
            <td height="1" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
          </tr>
          <tr>
            <td><br />
              <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td height="20">
                                    Permet la mise a jour des HT-id de la table joueur
                                  </td>
                </tr>
                <tr>
                  <td>
                    <div align="center">
                      <br />
                      <input type="hidden" name="idclubht" value="Oui" />
                      <input type="submit" name="Submit" value="MAJ teamid" />
                    </div>
                              </td>
                </tr>
                <tr>
                  <td align="center">
<?php
   //Si une purge a été demandée
   if(isset($_POST['idclubht'])){
     $clubmaj= array();
     $sql    = "SELECT MAX(idClub) from ht_clubs";
     $rsttab = $maBase->select($sql);
     $maxid  = $rsttab[0][0];
     $clubmaj= array_pad($clubmaj,$maxid+1,0);
     unset($clubmaj[0]);

     $sql    = "select idClub,idClubHT
                 from  ht_clubs";
     $rsttab = $maBase->select($sql);
     foreach($rsttab as $rst)
        $clubmaj[$rst[0]] = $rst[1];

        $sql = "UPDATE ht_joueurs
                  SET teamid=ELT(idClubActuelJoueur_fk,".implode(",",$clubmaj).")";
        $maBase->update($sql);
     ?>
                                 Mise à jour effectuée !!!
<?php }
?>
                              </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </div>
    </td>
  </tr>
</table>
</form>
<?php
   //Fin de la fonction de purge
   }
