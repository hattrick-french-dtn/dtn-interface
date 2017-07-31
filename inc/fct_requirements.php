<?php
/*
FCT qui renvoi dans une chaine de caractère, un tableau tout fait des requirements
 Type : variable pour définir si U20 ou A
 "U20" -> recherche joueurs - de 20
 "A" -> recherche joueurs A (par défaut)
 Lang : langue pour l'affichage des carac des joueurs
 "fr" -> français
 "en" -> english (par défaut)
*/
function afficheRequirements( $type, $lang ) 
{
	global $conn;
	//Semaine en cours
	$todaySeason = getSeasonWeekOfMatch(mktime(0,0,0,date('m'), date('d'),date('Y')));
	$week = $todaySeason["week"];
	
	
	if( $type == "U20" ) $age = "AND age <= 20 ";
	else $age = "AND age >= 21 ";
	
	//Requete
	$req = "SELECT * FROM ht_requirements r, ht_position p, ht_caracteristiques c 
			WHERE r.position_id = p.idPosition
			AND r.level_1 = c.idCarac ".$age.
			"AND week = ".$week." ORDER BY position_id, age";
	
	$rst = $conn->query($req);
	
	//remplissage d'un tableau à 2D pour les monocaracs
	foreach($rst as $lst)
	{
		if( $lang == "fr" ) {$carac = $lst['intituleCaracFR'];}
		else {if( $lang == "de" ) {$carac = $lst['intituleCaracDE'];}
		      else {$carac = $lst['intituleCaracUK'];}
		}
	
		$tab_rqrm[$lst['descriptifPosition']][$lst['age']] = $lst['level_1'];
		$carac_nom[$lst['level_1']] = $carac;
	}
	
	//deconnect();
	
	$i = 1;
	$out = "<table width='95%' border='0' cellpadding='0' cellspacing='0' align='center'><tr>";
	
	foreach($tab_rqrm as $pos => $rqrm)
	{
		// Pti test pour faire une présentation de type 3 tableaux sur une ligne...
		// C'est toujours plus beau quand c'est codé hein ^^ :p
		if( $i % 3 == 1 && $i != 1 ) {
			$out .= "</tr></table><table width='80%' border='0' cellpadding='0' cellspacing='0'><tr><td>&nbsp;</td></tr></table>
				<table width='80%' border='0' cellpadding='0' cellspacing='0' align='center'><tr>";
		}
		else {
			if( $i != 1 ) $out .= "<td>&nbsp;</td>";
		}
		
		if( $lang == "fr" )	{$entete = "Niveau"; $enteteAge="Age";}
		else {
      if( $lang == "de" )	{$entete = "Hauptf&auml;higkeit"; $enteteAge="Alter";}
		  else {$entete = "Primary";$enteteAge="Age";}
		}
		
		if( $lang == "en" )
		{
			switch($pos) {
				case "Gardien" :
				$pos = "goalKeeper";
				break;
				case "Défense" :
				$pos = "defender";
				break;
				case "Milieu" :
				$pos = "innerMidfield";
				break;
				case "Ailier" :
				$pos = "Winger";
				break;
				case "Attaquant" :
				$pos = "Forward";
				break;																				
			}
		}
		
    if( $lang == "de" )
		{
			switch($pos) {
				case "Gardien" :
				$pos = "Torwart";
				break;
				case "Défense" :
				$pos = "Verteidiger";
				break;
				case "Milieu" :
				$pos = "Mittelfeldspieler";
				break;
				case "Ailier" :
				$pos = "Fl&uuml;gelspiel";
				break;
				case "Attaquant" :
				$pos = "St&uuml;rmer";
				break;																				
			}
		}
		
		$out .= "<td width='200'>	
			<table width='80' border='0' cellpadding='0' cellspacing='0'>
			  <tr>
				<td colspan='2' height='5'><img src='/images/index_submit_01_up.gif' width='200' height='5'/></td>
			  </tr>
			  <tr>
				<td colspan='2' bgcolor='#7B00C6' height='15'><div align='center' class='style49'><strong>".$pos."</strong></div></td>
			  </tr>
			  <tr>
				<td bgcolor='#7B00C6' width='60' height='15'><div align='center' class='style49'>".$enteteAge."</div></td>
				<td bgcolor='#7B00C6' width='140'><div align='center' class='style49'>".$entete."</div></td>
			  </tr>";
			
		foreach($rqrm as $age => $carac_num)
		{
		if ($age == "22") $age = "22+";
		$out .= "<tr>
					<td height='20'><div align='center'><font color='#7B00C6' style='font-size:11px'>".$age."</font></div></td>
					<td><div align='left'><font color='#7B00C6' style='font-size:11px'>&nbsp;&nbsp;"
					.$carac_nom[$carac_num]."&nbsp;&nbsp;(".$carac_num.")</font></div></td>
				</tr>";
		}	
			
		$out .= "<tr>
			<td colspan='2' height='10'><img src='/images/index_submit_03_down.gif' width='200' height='10'/></td>
				</tr>
			</table>
		  </td>";
	
		$i++;
	}
	
	$out .= "</tr></table>";
	return($out);
}
?>
