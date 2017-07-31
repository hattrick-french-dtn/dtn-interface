<?php
// créeation image etoile

$image = imagecreatefrompng("images/etoilejaune.png");

$couleur = imagecolorallocate($image, 238, 84, 73);        // couleur rouge
$haut = 117 - ($tot1 + 8)*116/16;
$polygone = array (129,149,110,117,129,$haut,148,117);     // nombre de positions pour les points
$nb_sommets = count ($polygone)/2;                      // nombre de pointes
imagepolygon($image, $polygone, $nb_sommets, $couleur); // affiche un polygone en losange
imagefilltoborder ($image, 130, 100, $couleur, $couleur);

$y = 75 + 59 - ($tot2 + 8)*59/16;
$x = ($tot2 + 8)*100/16+159;

$couleur = imagecolorallocate($image, 65, 174, 222);        // couleur bleu
$haut = ($tot1 + 8)*116/16 + 1;
$polygone = array (129,149,148,117,$x,$y,166,149);     // nombre de positions pour les points
$nb_sommets = count ($polygone)/2;                      // nombre de pointes
imagepolygon($image, $polygone, $nb_sommets, $couleur); // affiche un polygone en losange
imagefilltoborder ($image, 160, 120, $couleur, $couleur);

$y = 164 + ($tot5 + 8)*59/16;
$x = ($tot5 + 8)*100/16+159;

$couleur = imagecolorallocate($image, 168, 179, 83);        // couleur vert s5
$haut = ($tot1 + 8)*116/16 + 1;
$polygone = array (129,149,166,149,$x,$y,148,181);     // nombre de positions pour les points
$nb_sommets = count ($polygone)/2;                      // nombre de pointes
imagepolygon($image, $polygone, $nb_sommets, $couleur); // affiche un polygone en losange
imagefilltoborder ($image, 140, 160, $couleur, $couleur);

$couleur = imagecolorallocate($image, 155, 115, 65);        // couleur marron
$haut = 184 + ($tot4 + 8)*116/16;
$polygone = array (129,149,148,181,129,$haut,110,181);     // nombre de positions pour les points
$nb_sommets = count ($polygone)/2;                      // nombre de pointes
imagepolygon($image, $polygone, $nb_sommets, $couleur); // affiche un polygone en losange
imagefilltoborder ($image, 130, 160, $couleur, $couleur);

$y = 75 + 59 - ($tot3 + 8)*59/16;
$x = 101 - ($tot3 + 8)*100/16;

$couleur = imagecolorallocate($image, 155, 147, 187);        // couleur violet
$haut = ($tot1 + 8)*116/16 + 1;
$polygone = array (129,149,110,117,$x,$y,92,149);     // nombre de positions pour les points
$nb_sommets = count ($polygone)/2;                      // nombre de pointes
imagepolygon($image, $polygone, $nb_sommets, $couleur); // affiche un polygone en losange
imagefilltoborder ($image, 110, 120, $couleur, $couleur);

$y = 164 + ($tot6 + 8)*59/16;
$x = 101 - ($tot6 + 8)*100/16;

$couleur = imagecolorallocate($image, 247, 163, 73);        // couleur orange
$haut = ($tot1 + 8)*116/16 + 1;
$polygone = array (129,149,92,149,$x,$y,110,181);     // nombre de positions pour les points
$nb_sommets = count ($polygone)/2;                      // nombre de pointes
imagepolygon($image, $polygone, $nb_sommets, $couleur); // affiche un polygone en losange
imagefilltoborder ($image, 110, 160, $couleur, $couleur);




imagepng($image);
/* $im = imagecreate($bande,$tailletot);
$colfinale=imagecolorallocate($im,$r2,$v2,$b2);
imagerectangle($im,0,0,$bande,$tailletot,$colfinale);
 */


?>