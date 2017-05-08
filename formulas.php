<style type="text/css">
<!--	
.styleTitreBleu {
	font-family: "Century Gothic";
	font-size: 14px;
	color: #2491BD;
}
.styleTitreJaune {
	font-family: "Century Gothic";
	font-size: 14px;
	color: #FFCE42;
	font-weight: bold;
}
.styleFont14 {
	font-family: Century Gothic;
	font-size: 13px;
	color: #000000;
}
.styleFont12 {
	font-family: Century Gothic;
	font-size: 12px;
	color: #000000;
}
a:visited {
	color: #000000;
	text-decoration: none;
}
a:hover {
	color: #000000;
	text-decoration: none;
}
a:active {
	color: #000000;
	text-decoration: none;
}
a:link {
	color: #000000;
	text-decoration: none;
}
body,td,th {
	font-family: Century Gothic;
	font-size: 12px;
	color: #000000;
}
-->
</style>
<table width="760" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20" valign="top">&nbsp;</td>
    <td valign="top"><p align="justify"><br>
      <span class="styleFont12">Les formules utilis&eacute;es pour les <a href="dtn_tops.php"><u>tops</u></a> 
	  sont adapt&eacute;es de celles de LA-Loko.<br>
      LA-Loko est r&eacute;put&eacute; pour son pr&eacute;cieux 
	  <a href="http://hem.passagen.se/hammervald/abc_of_tactics_index.htm" target="_new"><u>abc des tactiques</u></a>
	  , et ses impressionnants succ&egrave;s.</span></p>
      <table width="100%" border="0" cellspacing="0" cellpadding="1">
        <tr>
          <td><div align="left"> <span class="styleFont14"><b>Lokomotiv Lund (9952)</b></span></div></td>
          <td width="110" rowspan="5"><img src="http://www.ht-fff.org/images/lokomotivlund.gif" name="lokomotivlund" width="110" height="90" id="lokomotivlund">            <div align="right"></div></td>
        </tr>
        <tr>
          <td><div align="left"> <span class="styleFont14"><strong>Pays:</strong> Sverige</span></div></td>
        </tr>
        <tr>
          <td><div align="left"> <span class="styleFont14"><strong>R&eacute;gion:</strong> Sk&aring;ne</span></div></td>
        </tr>
        <tr>
          <td><div align="left"><span class="styleFont14"><strong>Propri&eacute;taire:</strong> Robin Gustafsson</span></div></td>
        </tr>
        <tr>
          <td><div align="left"> <span class="styleFont14"><strong>Nom d'utilisateur:</strong> LA-Loko</span></div></td>
        </tr>
      </table>
      <br>
      <p class="styleFont12">
<span class="styleFont12">      Voici les diff&eacute;rentes formules...
</p>
<p class="styleFont12">
      <b>Tous les joueurs </b><br>
    La note globale - la valeur fondamentale - est calcul&eacute;e de la m&ecirc;me fa&ccedil;on pour tous les joueurs: <br>
    {globale} = {comp&eacute;tences} * (0.94 + {exp&eacute;rience} * 0.01) <br>
Valeur maximum pour {endurance} : 8<br>
&alpha;| alpha : l'endurance appliqu&eacute;e à la construction.  <br>
{&alpha;} = 0.22 + 0.13 * {endurance} <br> 
&beta;| beta : l'endurance appliqu&eacute;e aux autres caract&eacute;ristiques (sauf gardien). <br>
 {&beta;} = 0.7 + 0.05 * {endurance} <br>
&gamma;| gamma : l'endurance appliqu&eacute;e à la caractéristique gardien, pour l'instant fixée à 1 en attente des réformes des HT. <br>
 {&gamma;} = 1      <br>
 </p>
      <p class="styleFont12"><b>gK| goalKeeper ; gardien</b><br>
{comp&eacute;tences} = 0.75 * {gardien} * {&gamma;} + 0.25 * {d&eacute;fense} * {&beta;}</p> 
      <p class="styleFont12"><b>cD| centralDefender ; d&eacute;fenseur central, normal </b><br>
{comp&eacute;tences} = 0.15 * {construction} * {&alpha;} + (0.7 * {d&eacute;fense} + 0.15 * {passe}) * {&beta;}</p>
      <p class="styleFont12"><b>cD| centralDefender ; d&eacute;fenseur central, offensif </b><br>
{comp&eacute;tences} = 0.3 * {construction} * {&alpha;} + (0.6 * {d&eacute;fense} + 0.1 * {passe}) * {&beta;}</p>
      <p class="styleFont12"><b>wB| wingBack ; arrière latéral, normal</b><br>
{comp&eacute;tences} = 0.1 * {construction} * {&alpha;} + (0.6 * {d&eacute;fense} + 0.3 * {ailier}) * {&beta;}</p>
	  <p class="styleFont12"><b>wB| wingBack ; arrière latéral, offensif </b><br>
{comp&eacute;tences} = 0.15 * {construction} * {&alpha;} + (0.5 * {d&eacute;fense} + 0.35 * {ailier}) * {&beta;}</p> 
      <p class="styleFont12"><b>iM| innerMidfielder ; milieu central, d&eacute;fensif </b><br>
{comp&eacute;tences} = 0.65 * {construction} * {&alpha;} +  (0.25 * {d&eacute;fense} + 0.1 * {passe}) * {&beta;}</p>
      <p class="styleFont12"><b>iM| innerMidfielder ; milieu central, normal</b><br>
{comp&eacute;tences} = 0.7 * {construction} * {&alpha;} +  (0.15 * {d&eacute;fense} + 0.15 * {passe}) * {&beta;}</p>
      <p class="styleFont12"><b>iM| innerMidfielder ; milieu central, offensif </b><br>
{comp&eacute;tences} = 0.65 * {construction} * {&alpha;} +  (0.1 * {d&eacute;fense} + 0.25 * {passe}) * {&beta;}</p>
      <p class="styleFont12"><b>Wg| Winger ; ailier, vers le centre </b><br>
{comp&eacute;tences} = 0.35 * {construction} * {&alpha;} +  (0.35 * {ailier} + 0.15 * {passe} + 0.15 * {d&eacute;fense}) * {&beta;}</p>
      <p class="styleFont12"><b>Wg| Winger ; ailier, normal </b><br>
{comp&eacute;tences} = 0.2 * {construction} * {&alpha;} +  (0.5 * {ailier} + 0.2 * {passe} + 0.1 * {d&eacute;fense}) * {&beta;}</p>
      <p class="styleFont12"><b>Wg| Winger ; ailier, offensif</b><br>
{comp&eacute;tences} = 0.1 * {construction} * {&alpha;} +  (0.6 * {ailier} + 0.3 * {passe}) * {&beta;}</p>
      <p class="styleFont12"><b>Fw| Forward ; attaquant, d&eacute;fensif</b> <br>
Si Technique : {comp&eacute;tences} = 0.5 * {construction} * {&alpha;} +  (0.3 * {passe} + 0.3 * {buteur}) * {&beta;} <br>
Sinon : {comp&eacute;tences} = 0.5 * {construction} * {&alpha;} +  (0.2 * {passe} + 0.3 * {buteur}) * {&beta;}</p>
	  <p class="styleFont12"><b>Fw| Forward ; attaquant, vers l'aile </b> <br>
{comp&eacute;tences} = (0.4 * {buteur} + 0.2 * {passe} + 0.4 * {ailier}) * {&beta;}</p>
      <p class="styleFont12"><b>Fw| Forward ; attaquant, normal </b><br>
{comp&eacute;tences} = (0.7 * {buteur} + 0.2 * {passe} + 0.1 * {ailier}) * {&beta;}</p>
</td>
    
  </tr>
</table>
