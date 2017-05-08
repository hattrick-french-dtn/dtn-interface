<?php
require("lang.php");

function teste_joueur($joueur_brut,$U20=1)
{
 global $en,$fr,$note_en,$note_fr,$corres;
 
 if(strstr($joueur_brut,"Stamina"))
 {
  // langue = anglais
  $tag_lang = $en;
  $note = $note_en;
  $tab["lang"] = "en";
  $carac = $carac_en;
  
  $spe = "Speciality";
   }else{
  // langue = français
  $tag_lang = $fr;
  $note = $note_fr;
  $tab["lang"] = "fr";
    $carac = $carac_fr;
	$spe = "Spécialité";

 }

//print_r($joueur_brut);


 if(strpos($joueur_brut,"".$spe.": ") != ""){
$longueur =  strpos($joueur_brut,"Nationalit") - strpos($joueur_brut,"".$spe.": ") - 12;
$val["specialite"] = trim(substr($joueur_brut,strpos($joueur_brut,"".$spe.": ")+12,$longueur));
}

$val["age"] = trim(substr($joueur_brut,strpos($joueur_brut,") ")+3,5));


$val["identite"] = trim(substr($joueur_brut,0,strpos($joueur_brut, "(")));
//$val["specialite"] = ""
$val["id"] =  substr($joueur_brut, strpos($joueur_brut,"(")+1, strpos($joueur_brut,")")- strpos($joueur_brut,"(")-1);

   $pos4 = strpos($joueur_brut,$tag_lang["Stamina"]);
   $pos5 = strpos($joueur_brut,$tag_lang["Keeper"],$pos4+1);
   $tab["stamina"] = trim(substr($joueur_brut,$pos4+strlen($tag_lang["Stamina"])+1,$pos5-($pos4+strlen($tag_lang["Stamina"])+1)));
   $size_keeper = strlen($tag_lang["Keeper"]);
   // cas du goaltending
   if(strlen($tab["stamina"]) >=20)
   {
    $pos5 = strpos($joueur_brut,$tag_lang["Keeper2"],$pos4+1);
    $tab["stamina"] = trim(substr($joueur_brut,$pos4+strlen($tag_lang["Stamina"])+1,$pos5-($pos4+strlen($tag_lang["Stamina"])+1)));
    $size_keeper = strlen($tag_lang["Keeper2"]);
   }
   
   $pos6 = strpos($joueur_brut,$tag_lang["Playmaking"],$pos5+1);
   $tab["keeper"] = trim(substr($joueur_brut,$pos5+$size_keeper+1,$pos6-($pos5+$size_keeper+1)));
   
   $pos7 = strpos($joueur_brut,$tag_lang["Passing"],$pos6+1);
   $tab["playmaking"] = trim(substr($joueur_brut,$pos6+strlen($tag_lang["Playmaking"])+1,$pos7-($pos6+strlen($tag_lang["Playmaking"])+1)));
 
   $pos8 = strpos($joueur_brut,$tag_lang["Winger"],$pos7+1);
   $tab["passing"] = trim(substr($joueur_brut,$pos7+strlen($tag_lang["Passing"])+1,$pos8-($pos7+strlen($tag_lang["Passing"])+1)));
   
   $pos9 = strpos($joueur_brut,$tag_lang["Defending"],$pos8+1);
   $tab["winger"] = trim(substr($joueur_brut,$pos8+strlen($tag_lang["Winger"])+1,$pos9-($pos8+strlen($tag_lang["Winger"])+1)));
   
   $pos10 = strpos($joueur_brut,$tag_lang["Scoring"],$pos9+1);
   $tab["defending"] = trim(substr($joueur_brut,$pos9+strlen($tag_lang["Defending"])+1,$pos10-($pos9+strlen($tag_lang["Defending"])+1)));
   
   $pos11 = strpos($joueur_brut,$tag_lang["Set Pieces"],$pos10+1);
   $tab["scoring"] = trim(substr($joueur_brut,$pos10+strlen($tag_lang["Scoring"])+1,$pos11-($pos10+strlen($tag_lang["Scoring"])+1)));
   
   $pos12 = strpos($joueur_brut,"\n",$pos11+1);
   if (!$pos12)
      $tab["set_pieces"] = trim(substr($joueur_brut,$pos11+strlen($tag_lang["Set Pieces"])+1));
   else
      $tab["set_pieces"] = trim(substr($joueur_brut,$pos11+strlen($tag_lang["Set Pieces"])+1,$pos12-($pos11+strlen($tag_lang["Set Pieces"])+1)));
// echo "age -> ".$tab["age"]."*<BR>";
 /*
 echo "Nom -> ".$tab["nom"]."*<BR>";
 echo "forme -> ".$tab["form"]."*<BR>";
 echo "stamina -> ".$tab["stamina"]."*<BR>";
 echo "keeper -> ".$tab["keeper"]."*<BR>";
 echo "playmkg -> ".$tab["playmaking"]."*<BR>";
 echo "passing -> ".$tab["passing"]."*<BR>";
 echo "winger -> ".$tab["winger"]."*<BR>";
 echo "defending -> ".$tab["defending"]."*<BR>";
 echo "scoring -> ".$tab["scoring"]."*<BR>";
 echo "set_pieces -> ".$tab["set_pieces"]."*<BR>";
*/



 $val["stamina"]["value"] = $note[$tab["stamina"]];
 $val["keeper"]["value"]  = $note[$tab["keeper"]];
 $val["playmaking"]["value"] = $note[$tab["playmaking"]];
 $val["passing"]["value"]  = $note[$tab["passing"]];
 $val["winger"]["value"] = $note[$tab["winger"]];
 $val["defending"]["value"] = $note[$tab["defending"]];
 $val["scoring"]["value"] = $note[$tab["scoring"]];
 $val["set_pieces"]["value"]  = $note[$tab["scoring"]];


 $val["stamina"]["libelle"] = $corres[$val["stamina"]["value"]];
 $val["keeper"]["libelle"]  = $corres[$val["keeper"]["value"]];
 $val["playmaking"]["libelle"] = $corres[ $val["playmaking"]["value"]];
 $val["passing"]["libelle"]  = $corres[$val["passing"]["value"]];
 $val["winger"]["libelle"] = $corres[$val["winger"]["value"]];
 $val["defending"]["libelle"] = $corres[$val["defending"]["value"]];
 $val["scoring"]["libelle"] = $corres[$val["scoring"]["value"]];
 $val["set_pieces"]["libelle"]  = $corres[$val["set_pieces"]["value"]];
// $tab["keeper"] = $k;
 
  if(($st == "") ||($k == "") ||($pl == "") ||($pa == "") ||($w == "") ||($d == "") ||($sc == "") )
 {
    $tab[erreur] = "Une erreur est survenu lors de l'analyse.<BR>Vérifiez que votre copier coller est correct."; 
    return $val;  
 }




   return $val;
}


?>