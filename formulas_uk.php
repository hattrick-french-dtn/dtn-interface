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
      <span class="styleFont12">The formulas used for the <a href="dtn_tops.php"><u>tops</u></a> are adapted from the LA-Loko  ones.<br>
      LA-Loko is well-know in hattrick for his interesting <a href="http://hem.passagen.se/hammervald/abc_of_tactics_index.htm" target="_new"><u>abc of tactics</u></a>, and his impressive achievements.</span></p>
      <table width="100%" border="0" cellspacing="0" cellpadding="1">
        <tr>
          <td><div align="left"> <span class="styleFont14"><b>Lokomotiv Lund (9952)</b></span></div></td>
          <td width="110" rowspan="5"><img src="/images/lokomotivlund.gif" name="lokomotivlund" width="110" height="90" id="lokomotivlund">            <div align="right"></div></td>
        </tr>
        <tr>
          <td><div align="left"> <span class="styleFont14"><strong>Country:</strong> Sverige</span></div></td>
        </tr>
        <tr>
          <td><div align="left"> <span class="styleFont14"><strong>Region:</strong> Sk&aring;ne</span></div></td>
        </tr>
        <tr>
          <td><div align="left"><span class="styleFont14"><strong>Owner: </strong> Robin Gustafsson</span></div></td>
        </tr>
        <tr>
          <td><div align="left"> <span class="styleFont14"><strong>Alias: </strong> LA-Loko</span></div></td>
        </tr>
      </table>
      <br>
      <p class="styleFont12">
<span class="styleFont12">      Here are the different formulas...
</p>
<p class="styleFont12">
      <b>All players </b><br>
    The overall - the most important value - is calculated in the same way for all types of players: <br>
    {overall} = {skills} * (0.94 + {experience} * 0.01) <br>
{stamina} maximum value is 8.<br>
&alpha;| alpha:  stamina linked with playmaking. <br>
{&alpha;} = 0.22 + 0.13 * {stamina}<br>
&beta;| beta: stamina linked with any other ability (except from goalkeeping). <br>
 {&beta;} = 0.7 + 0.05 * {stamina}      <br>
&gamma;| gamma : stamina linked goalkeeping, 1 until HTs decision. <br>
 {&gamma;} = 1      <br>
 </p>
      <p class="styleFont12"><b>gK| goalKeeper </b><br>
{skills} = 0.75 * {goalkeeping} * {&gamma;} + 0.25 * {defending} * {&beta;}</p> 
      <p class="styleFont12"><b>cD| centralDefender, normal </b><br>
{skills} = 0.15 * {playmaking} * {&alpha;} + (0.7 * {defending} + 0.15 * {passing}) * {&beta;}</p>
      <p class="styleFont12"><b>cD| centralDefender, offensive </b><br>
{skills} = 0.3 * {playmaking} * {&alpha;} + (0.6 * {defending} + 0.1 * {passing}) * {&beta;}</p>
      <p class="styleFont12"><b>wB| wingBack, normal</b><br>
{skills} = 0.1 * {playmaking} * {&alpha;} + (0.6 * {defending} + 0.3 * {winger}) * {&beta;}</p>
	  <p class="styleFont12"><b>wB| wingBack, offensive </b><br>
{skills} = 0.15 * {playmaking} * {&alpha;} + (0.5 * {defending} + 0.35 * {winger}) * {&beta;}</p> 
      <p class="styleFont12"><b>iM| innerMidfielder, defensive </b><br>
{skills} = 0.65 * {playmaking} * {&alpha;} +  (0.25 * {defending} + 0.1 * {passing}) * {&beta;}</p>
      <p class="styleFont12"><b>iM| innerMidfielder, normal</b><br>
{skills} = 0.7 * {playmaking} * {&alpha;} +  (0.15 * {defending} + 0.15 * {passing}) * {&beta;}</p>
      <p class="styleFont12"><b>iM| innerMidfielder, offensive </b><br>
{skills} = 0.65 * {playmaking} * {&alpha;} +  (0.1 * {defending} + 0.25 * {passing}) * {&beta;}</p>
      <p class="styleFont12"><b>Wg| Winger, towards middle </b><br>
{skills} = 0.35 * {playmaking} * {&alpha;} +  (0.35 * {winger} + 0.15 * {passing} + 0.15 * {defending}) * {&beta;}</p>
      <p class="styleFont12"><b>Wg| Winger, normal </b><br>
{skills} = 0.2 * {playmaking} * {&alpha;} +  (0.5 * {winger} + 0.2 * {passing} + 0.1 * {defending}) * {&beta;}</p>
      <p class="styleFont12"><b>Wg| Winger, offensive</b><br>
{skills} = 0.1 * {playmaking} * {&alpha;} +  (0.6 * {winger} + 0.3 * {passing}) * {&beta;}</p>
      <p class="styleFont12"><b>Fw| Forward, defensive</b> <br>
If Technical : {skills} = 0.5 * {playmaking} * {&alpha;} +  (0.3 * {passing} + 0.3 * {scoring}) * {&beta;} <br>
Otherwise : {skills} = 0.5 * {playmaking} * {&alpha;} +  (0.2 * {passing} + 0.3 * {scoring}) * {&beta;}</p>
	  <p class="styleFont12"><b>Fw| Forward, towards wing </b> <br>
{skills} = (0.4 * {scoring} + 0.2 * {passing} + 0.4 * {winger}) * {&beta;} </p>
      <p class="styleFont12"><b>Fw| Forward, normal </b><br>
{skills} = (0.7 * {scoring} + 0.2 * {passing} + 0.1 * {winger}) * {&beta;}</p>
</td>
    
  </tr>
</table>
