<?php 
require("../includes/head.inc.php");


if(!$sesUser["idAdmin"])
	{
	header("location: index.php?ErrorMsg=Session Expir�");
	}
require("../includes/langue.inc.php");



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
		
		case "4":
		require("../menu/menuCoach.php");
		require("../menu/menuCoachConsulter.php"); 
		break;
		
		default;
		break;


}


?><title>TopList DTN Formules</title>
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>

<body >
<br>

  <center>
    <br>
    <b><span class="breadvar">Les Formules des Tops!</span></b>
  </center>

<p>
Cette page est simplement une copie du code en place permettant &agrave; 
la plupart des DTN de comprendre les notes des joueurs et d'aider &agrave; d&eacute;celer les &eacute;ventuelles erreurs. <br>

<p>


 <table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr> <td align=left>
<pre>

if ($carac["endurance"]==9){
    $alphaEndu=0.22+0.13*8;
    $betaEndu=0.7+(0.05*8);
}else{
    $alphaEndu=0.22+0.13*($carac["endurance"]);
    $betaEndu=0.7+(0.05*$carac["endurance"]);
}
$gammaEndu = 1 (en attente des réformes des HTs)

$niveauGK=$carac["gardien"]+ $semaine["gardien"] * 0.1;
$niveauDef=$carac["defense"] + $semaine["defense"]* 0.1;
$niveauConstruction=$carac["construction"] + $semaine["construction"] * 0.1;
$niveauAilier=$carac["ailier"] + $semaine["ailier"] * 0.1;
$niveauAttaquant=$carac["buteur"] + $semaine["buteur"] * 0.1;
$niveauPasse=$carac["passe"] + $semaine["passe"] * 0.1;
$xp=$carac["xp"];

$scoreGk=[(0.75*$niveauGk*$gammaEndu)+(0.25*$niveauDef*$betaEndu)]*(0.94+$xp*0.01)

(DC)$scoreDefense=[($niveauDef*0.7+$niveauPasse*0.15)* $betaEndu+ (0.15*$niveauConstruction)*$alphaEndu]*(0.94+$xp*0.01);
(DCOff)$scoreDefense=[($niveauDef*0.6+$niveauPasse*0.1)* $betaEndu+ (0.3*$niveauConstruction)*$alphaEndu]*(0.94+$xp*0.01);

(Wb)$scoreDefense=[($niveauDef*0.6+$ niveauAilier*0.3)* $betaEndu+(0.1*$niveauConstruction)*$alphaEndu]*(0.94+$xp*0.01);
(WbDef)$scoreDefense=($niveauDef*0.8+$niveauAilier*0.2)* $betaEndu*(0.94+$xp*0.01);
(WbOff)$scoreDefense=[($niveauDef*0.5+$niveauAilier*0.35)* $betaEndu+(0.15*$niveauConstruction)*$alphaEndu]*(0.94+$xp*0.01);

(Wg)$scoreAilier=[($niveauConstruction*0.2)*$alphaEndu +($niveauAilier*0.5 + $niveauPasse*0.2 + $niveauDef*0.1)* $betaEndu] *(0.94+$xp*0.01);
(WgOff)$scoreAilier=[($niveauConstruction*0.1)*$alphaEndu +($niveauAilier*0.6 + $niveauPasse*0.3)* $betaEndu] *(0.94+$xp*0.01);
(WgDef)$scoreAilier=[($niveauConstruction*0.15)*$alphaEndu +($niveauAilier*0.4 + $niveauPasse*0.15 + $niveauDef*0.3)* $betaEndu] *(0.94+$xp*0.01);
(Wg vers le centre)$scoreAilier=[($niveauConstruction*0.35)*$alphaEndu +($niveauAilier*0.35 + $niveauPasse*0.15 + $niveauDef*0.15)* $betaEndu] *(0.94+$xp*0.01);

(IM)$scoreMilieu=(($niveauConstruction*0.7)*$alphaEndu + ($niveauPasse*0.15 + $niveauDef*0.15) *$betaEndu)*(0.94+$xp*0.01);
(IMoff)$scoreMilieuOff=(($niveauConstruction*0.65)*$alphaEndu + ($niveauPasse*0.25 + $niveauDef*0.1) *$betaEndu)*(0.94+$xp*0.01);
(IM def)$scoreMilieuDef=(($niveauConstruction*0.65)*$alphaEndu + ($niveauDef*0.25 + $niveauPasse*0.1) *$betaEndu)*(0.94+$xp*0.01);
(IM vers l'aile)$scoreMilieuDef=[($niveauConstruction*0.6)*$alphaEndu + ($niveauDef*0.1 + $niveauPasse*0.1+$niveauAilier*0.2) *$betaEndu)*(0.94+$xp*0.01);

(Fw)$scoreAttaquant=[($niveauAttaquant*0.7 + $niveauPasse*0.2 + $niveauAilier*0.1) *$betaEndu]*(0.94+$xp*0.01) ;
(FwDef)$scoreAttaquant=[($niveauConstruction*0.5)*$alphaEndu + ($niveauPasse*0.2 + $niveauAttaquant*0.3) *$betaEndu]*(0.94+$xp*0.01) (si pas technique) ;
(FwDef)$scoreAttaquant=[($niveauConstruction*0.5)*$alphaEndu + ($niveauPasse*0.3 + $niveauAttaquant*0.3) *$betaEndu]*(0.94+$xp*0.01) (si technique) ;
(Fw vers l'aile)$scoreAttaquant=($niveauAttaquant*0.4 + $niveauPasse*0.2 + $niveauAilier*0.4) *$betaEndu*(0.94+$xp*0.01) ; 

</pre>
        </div></td>
    </tr>
  </table>

  <br>



</body>
<?php  deconnect(); ?>

