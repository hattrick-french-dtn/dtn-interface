<?php
/* FCT qui retourne la liste des membres de la DTN dans un tableau tout fait comme il faut (hein rainCow ^^).
$id_position : correspond au secteur de jeu du DTN 
$intitule_position : parce que les données dans la table sont "moches"
$libelle_position : meme remarque que pour l'intitule
Correspond à un chiffre entre 1 et 5, si possible ajouter des catégories pour les développeurs et traducteurs...
*/
function afficheMembres( $id_position, $intitule_position, $libelle_position ) {

   connect();
   //Les conditions des requêtes sont différentes s'il s'agit d'un affichage des DTN d'un secteur ou s'il s'agit du reste du staff DTN#/DTN~...
   if( $id_position != 0 ) $req_position = " AND idPosition_fk = ".$id_position; //utilisé pour les DTN secteurs
   else $req_position = " AND ((idNiveauAcces = 1 OR idNiveauAcces = 4) OR (idNiveauAcces = 2 AND idPosition_fk = 0))"; //utilisé pour les DTN#
   $req =  "SELECT ht_admin.loginAdmin, ht_admin.idAdminHT, ht_niveauacces.IntituleNiveauAcces, ht_clubs.nomClub, ht_pays.idPays, ht_pays.nomPays
            FROM ht_admin 
                  LEFT JOIN ht_clubs ON ht_admin.idAdminHT = ht_clubs.idUserHT 
                  LEFT JOIN ht_pays ON ht_clubs.idPays_fk = ht_pays.idPays 
                  LEFT JOIN ht_niveauacces ON ht_admin.idNiveauAcces_fk = ht_niveauacces.idNiveauAcces 
            WHERE ht_admin.idAdminHT IS NOT NULL
              AND ht_admin.affAdmin = 1 ".$req_position."
            ORDER BY idNiveauAcces_fk ASC, loginAdmin  ASC";

   $sql = mysql_query($req);
   
   $out = "<table border='0' width='730' cellpadding='0' cellspacing='0'>";
   $i = 0;
   
   while( $rst = mysql_fetch_object($sql) ) {
   
      // En tete de tableau avec le nom du poste
      if( $i == 0 )
      { 
         $out .= "<tr>
          <td height='17' width='45' bgcolor='#000000'><div align='right' class='style40'><font color='#FFFFFF'><strong>".$intitule_position."</strong></font></div></td>
               <td width='200' bgcolor='#7B00C6'><div align='left' class='style40'><font color='#FFFFFF'><strong>&nbsp;".$libelle_position."</strong></font></div></td>
               <td width='130' bgcolor='#CCCCCC'><div align='center' class='style40'><strong>status</strong></div></td>
               <td width='120' bgcolor='#CCCCCC'><div align='center' class='style40'><strong>UserID</strong></div></td>
               <td width='220' bgcolor='#CCCCCC'><div align='center' class='style40'><strong>Team</strong></div></td>
               <td width='100' bgcolor='#CCCCCC'><div align='center' class='style40'><strong>country</strong></div></td></tr>";
         $i = 1;
      }
   
      $out .= "<tr><td height='15' colspan='2'><div align='left' class='style40'>".$rst->loginAdmin."</div></td>
            <td><div align='left' class='style40'>".$rst->IntituleNiveauAcces."</div></td>";
      
      /*
    //Certains ID de club affichent 0
      if( $rst->idClubHT != 0 )      
         $out .= "<td><div align='center' class='style40'>".$rst->idClubHT."</div></td>";
      else
         $out .= "<td>&nbsp;</td>";
    */
    
    //Id Hattrick du User conservé dans idAdminHT de la table ht_admin
      if( $rst->idAdminHT != null )   //si non renseigné alors doit être null et pas 0 sinon possibilité d'erreur liée à la non-unicité   
         $out .= "<td><div align='left' class='style40'>".$rst->idAdminHT."</div></td>";
      else
         $out .= "<td>&nbsp;</td>";
    
      //Nom du Club par Fireproofed
    if( $rst->nomClub != null )      
         $out .= "<td><div align='left' class='style40'>".$rst->nomClub."</div></td>";
      else
         $out .= "<td>&nbsp;</td>";

      /*
    //idUserHT par Fireproofed
    if( $rst->idUserHT != 0 )      
         $out .= "<td><div align='center' class='style40'>".$rst->idUserHT."</div></td>";
      else
         $out .= "<td>&nbsp;</td>";

      //Nom du User par Fireproofed
    if( $rst->nomUser != null )      
         $out .= "<td><div align='center' class='style40'>".$rst->nomUser."</div></td>";
      else
         $out .= "<td>&nbsp;</td>";
      */
      
      if( $rst->idPays != null )
         $out .= "<td><div align='center'><img alt='".$rst->nomPays."' border='1' src='http://".$_SERVER["HTTP_HOST"]."/images/flags/"
            .$rst->idPays."flag.gif' /></div></td></tr>";
      else
         $out .= "<td>&nbsp;</td></tr>";
      
   }
   deconnect();
   return( $out."</table>" );
}
?>