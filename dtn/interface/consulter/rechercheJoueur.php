<?php 
session_cache_limiter('public');
error_reporting(E_ALL);

require_once "../_config/CstGlobals.php"; // fonctions d'admin
require_once "../fonctions/AccesBase.php"; // fonction de connexion a la base
require_once "../fonctions/AdminDtn.php"; // fonctions d'admin
require_once("../includes/head.inc.php");
require("../includes/serviceListesDiverses.php");
require("../includes/serviceMatchs.php");

if(!$sesUser["idAdmin"])
{
	header("location: ../index.php?ErrorMsg=Session Expiree");
}
if(!isset($ordre)) $ordre = "nomJoueur";
if(!isset($sens)) $sens = "ASC";
if(!isset($lang)) $lang = "FR";
if(!isset($masque)) $masque = 0;
if(!isset($secteur)) $secteur = 0;

//require("../includes/langue.inc.php");



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



$lstPos = listAllPosition();


 ?>

<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" WIDTH="600">
<TR>
		<TD WIDTH="20">&nbsp;</TD>
		<TD VALIGN="top">
			<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0">
<TR>
				<TD COLSPAN="2" VALIGN="top">
		      <BR>		

					<FORM ACTION="recherche_result.php" METHOD="GET">

					<DIV CLASS=rub1>Recherche dans la base de donn&eacute;es DTN France.</DIV><BR>
					<TABLE BORDER="0" CELLPADDING="2" CELLSPACING="0" WIDTH="355">
					<TR>
						<TD VALIGN="top" colspan=2>
						<B>Secteur de jeu : </B>
						<SELECT NAME="ht_posteAssigne" SIZE=1>
								<OPTION VALUE="0" SELECTED>Tout</OPTION>
								<OPTION VALUE="1">Gardien</OPTION>
								<OPTION VALUE="2">D&eacute;fenseur</OPTION>
								<OPTION VALUE="4">Milieu de terrain</OPTION>
								<OPTION VALUE="3">Ailier</OPTION>
								<OPTION VALUE="5">Attaquant</OPTION>
						</SELECT>
						</TD>
					  </TR>
					</TABLE>

					
					<BR>					
					<TABLE BORDER="0" CELLPADDING="4" CELLSPACING="0">
				  <TR>
						<TD COLSPAN="1" VALIGN="middle"><B>Age:&nbsp; </B></TD>
			  <TD COLSPAN="1" VALIGN="top">

							Mini 
<INPUT TYPE="text" NAME="minAge" VALUE="" SIZE="3" MAXLENGTH="3"> 
							ans 
					    <input type="text" name="minjour" value="0" size="3" maxlength="3" id="minjour" /> 
			        jours</TD>
			 <TD COLSPAN="1" VALIGN="top">
							 Maxi 
				    <INPUT TYPE="text" NAME="maxAge" VALUE="" SIZE="3" MAXLENGTH="3"> ans								
						     <input type="text" name="maxjour" value="112" size="3" maxlength="3" id="maxjour" />
jours</TD>
					</TR>
					<TR>
						<TD COLSPAN="1" VALIGN="middle"><B>TSI :&nbsp; </B></TD>

						<TD COLSPAN="1" VALIGN="top">
							Mini <INPUT TYPE="text" NAME="minValue" VALUE="" SIZE="8" MAXLENGTH="10">						</TD>
						<TD COLSPAN="1" VALIGN="top">
							 Maxi <INPUT TYPE="text" NAME="maxValue" VALUE="" SIZE="10" MAXLENGTH="10">						</TD>
					</TR>
					<TR>
					  <TD COLSPAN="1" VALIGN="middle"><strong>Salaire</strong></TD>
					  <td colspan="1" valign="top"> Mini
					    <input type="text" name="minSalaire" value="" size="8" maxlength="10" id="minSalaire" />
                      </td>
					  <td colspan="1" valign="top"> Maxi
					    <input type="text" name="maxSalaire" value="" size="10" maxlength="10" id="maxSalaire" />
                      </td>
					</TR>
					<TR>
						<TD COLSPAN="1" VALIGN="middle"><B>Sp&eacute;cialit&eacute;:&nbsp; </B></TD>
						<TD COLSPAN="2" VALIGN="top">
							<Select NAME="specialty">
							<Option VALUE="-1" SELECTED>-- Toutes --</OPTION>
							<Option VALUE="0" >Aucune</OPTION>
							<Option VALUE="1" >Technique</OPTION>
							<Option VALUE="2" >Rapide</OPTION>
							<Option VALUE="3" >Costaud</OPTION>
							<Option VALUE="4" >Impr&eacute;visible</OPTION>
							<Option VALUE="5" >Joueur de t&ecirc;te</OPTION>
                            <Option VALUE="6" >R&eacute;sistant</OPTION>
                            <Option VALUE="8" >Chef d'Orchestre</OPTION>
							</Select>						</TD>
					</TR>
					<TR>
						<TD COLSPAN="3" VALIGN="middle"><B>Crit&egrave;res de comp&eacute;tences</B></TD>
					</TR>
					<TR>
						<TD COLSPAN="3" VALIGN="top">
							<table>

							 <tr>
							  <td valign="top">
						<SELECT NAME="SkillType1" SIZE=1>
							<OPTION VALUE="">---Comp&eacute;tence 1---</OPTION>
	
							<OPTION VALUE="9">Endurance</OPTION>
							<OPTION VALUE="4">Gardien</OPTION>
							<OPTION VALUE="1">Construction</OPTION>
							<OPTION VALUE="5">Passe</OPTION>
							<OPTION VALUE="2">Ailier</OPTION>
							<OPTION VALUE="6">D&eacute;fense</OPTION>
							<OPTION VALUE="3">Buteur</OPTION>
							<OPTION VALUE="7">Coups francs</OPTION>
							<OPTION VALUE="8">Experience</OPTION>
							<OPTION VALUE="10">Temp&eacute;rament de chef</OPTION>
						</SELECT>							  </td>
							  <td valign="top">						
						<SELECT NAME="SkillMin1" SIZE=1>
							<OPTION VALUE="" selected="selected">---Mini---</OPTION>
	
							<OPTION VALUE="0">inexistant</OPTION>
	
							<OPTION VALUE="1">catastroph</OPTION>
	
							<OPTION VALUE="2">mauvais</OPTION>
	
							<OPTION VALUE="3">m&eacute;diocre</OPTION>
	
							<OPTION VALUE="4">faible</OPTION>
	
							<OPTION VALUE="5">inad&eacute;quat</OPTION>
	
							<OPTION VALUE="6">passable</OPTION>
	
							<OPTION VALUE="7">honorable</OPTION>
	
							<OPTION VALUE="8">excellent</OPTION>

							<OPTION VALUE="9">formidable</OPTION>
	
							<OPTION VALUE="10">impression</OPTION>
	
							<OPTION VALUE="11">brillant</OPTION>
	
							<OPTION VALUE="12">inoubliabl</OPTION>
	
							<OPTION VALUE="13">l&eacute;gendaire</OPTION>
	
							<OPTION VALUE="14">surnaturel</OPTION>
	
							<OPTION VALUE="15">titanesque</OPTION>
	
							<OPTION VALUE="16">extra-terr</OPTION>
	
							<OPTION VALUE="17">mythique</OPTION>
	
							<OPTION VALUE="18">magique</OPTION>
	
							<OPTION VALUE="19">utopique</OPTION>
	
							<OPTION VALUE="20">divin</OPTION>
						</SELECT>							  </td>
							  <td valign="top">						
						<SELECT NAME="SkillMax1" SIZE=1>
							<OPTION VALUE="" selected="selected">---Maxi---</OPTION>
	
							<OPTION VALUE="0">inexistant</OPTION>
	
							<OPTION VALUE="1">catastroph</OPTION>
	
							<OPTION VALUE="2">mauvais</OPTION>
	
							<OPTION VALUE="3">m&eacute;diocre</OPTION>
	
							<OPTION VALUE="4">faible</OPTION>
	
							<OPTION VALUE="5">inad&eacute;quat</OPTION>
	
							<OPTION VALUE="6">passable</OPTION>
	
							<OPTION VALUE="7">honorable</OPTION>
	
							<OPTION VALUE="8">excellent</OPTION>
	
							<OPTION VALUE="9">formidable</OPTION>
	
							<OPTION VALUE="10">impression</OPTION>
	
							<OPTION VALUE="11">brillant</OPTION>
	
							<OPTION VALUE="12">inoubliabl</OPTION>
	
							<OPTION VALUE="13">l&eacute;gendaire</OPTION>
	
							<OPTION VALUE="14">surnaturel</OPTION>
	
							<OPTION VALUE="15">titanesque</OPTION>
	
							<OPTION VALUE="16">extra-terr</OPTION>
	
							<OPTION VALUE="17">mythique</OPTION>
	
							<OPTION VALUE="18">magique</OPTION>
	
							<OPTION VALUE="19">utopique</OPTION>
	
							<OPTION VALUE="20">divin</OPTION>
						</SELECT>						      </td>
						     </tr>
							</table>						</TD>
					</TR>
					<TR>
						<TD COLSPAN="3" VALIGN="top">
							<table>

							 <tr>
							  <td valign="top">						
						<SELECT NAME="SkillType2" SIZE=1>
							<OPTION VALUE="">---Comp&eacute;tence 2---</OPTION>
							<OPTION VALUE="9">Endurance</OPTION>
							<OPTION VALUE="4">Gardien</OPTION>
							<OPTION VALUE="1">Construction</OPTION>
							<OPTION VALUE="5">Passe</OPTION>
							<OPTION VALUE="2">Ailier</OPTION>
							<OPTION VALUE="6">D&eacute;fense</OPTION>
							<OPTION VALUE="3">Buteur</OPTION>
							<OPTION VALUE="7">Coups francs</OPTION>
							<OPTION VALUE="8">Experience</OPTION>
							<OPTION VALUE="10">Temp&eacute;rament de chef</OPTION>
						</SELECT>							  </td>
							  <td valign="top">							
						<SELECT NAME="SkillMin2" SIZE=1>
							<OPTION VALUE="" selected="selected">---Mini---</OPTION>
	
							<OPTION VALUE="0">inexistant</OPTION>
	
							<OPTION VALUE="1">catastroph</OPTION>
	
							<OPTION VALUE="2">mauvais</OPTION>
	
							<OPTION VALUE="3">m&eacute;diocre</OPTION>
	
							<OPTION VALUE="4">faible</OPTION>
	
							<OPTION VALUE="5">inad&eacute;quat</OPTION>
	
							<OPTION VALUE="6">passable</OPTION>
	
							<OPTION VALUE="7">honorable</OPTION>
	
							<OPTION VALUE="8">excellent</OPTION>
	
							<OPTION VALUE="9">formidable</OPTION>
	
							<OPTION VALUE="10">impression</OPTION>
	
							<OPTION VALUE="11">brillant</OPTION>
	
							<OPTION VALUE="12">inoubliabl</OPTION>
	
							<OPTION VALUE="13">l&eacute;gendaire</OPTION>
	
							<OPTION VALUE="14">surnaturel</OPTION>
	
							<OPTION VALUE="15">titanesque</OPTION>
	
							<OPTION VALUE="16">extra-terr</OPTION>
	
							<OPTION VALUE="17">mythique</OPTION>
	
							<OPTION VALUE="18">magique</OPTION>
	
							<OPTION VALUE="19">utopique</OPTION>
	
							<OPTION VALUE="20">divin</OPTION>
						</SELECT>							  </td>
							  <td valign="top">							
						<SELECT NAME="SkillMax2" SIZE=1>
							<OPTION VALUE="" selected="selected">---Maxi---</OPTION>
	
							<OPTION VALUE="0">inexistant</OPTION>
	
							<OPTION VALUE="1">catastroph</OPTION>
	
							<OPTION VALUE="2">mauvais</OPTION>
	
							<OPTION VALUE="3">m&eacute;diocre</OPTION>
	
							<OPTION VALUE="4">faible</OPTION>
	
							<OPTION VALUE="5">inad&eacute;quat</OPTION>
	
							<OPTION VALUE="6">passable</OPTION>
	
							<OPTION VALUE="7">honorable</OPTION>
	
							<OPTION VALUE="8">excellent</OPTION>
	
							<OPTION VALUE="9">formidable</OPTION>
	
							<OPTION VALUE="10">impression</OPTION>
	
							<OPTION VALUE="11">brillant</OPTION>
	
							<OPTION VALUE="12">inoubliabl</OPTION>
	
							<OPTION VALUE="13">l&eacute;gendaire</OPTION>
	
							<OPTION VALUE="14">surnaturel</OPTION>
	
							<OPTION VALUE="15">titanesque</OPTION>
	
							<OPTION VALUE="16">extra-terr</OPTION>
	
							<OPTION VALUE="17">mythique</OPTION>
	
							<OPTION VALUE="18">magique</OPTION>
	
							<OPTION VALUE="19">utopique</OPTION>
	
							<OPTION VALUE="20">divin</OPTION>
						</SELECT>						      </td>
						     </tr>
							</table>						</TD>
					</TR>
					<TR>
						<TD COLSPAN="3" VALIGN="top">
							<table>

							 <tr>
							  <td valign="top">								
						<SELECT NAME="SkillType3" SIZE=1>
							<OPTION VALUE="">---Comp&eacute;tence 3---</OPTION>
	
							<OPTION VALUE="9">Endurance</OPTION>
							<OPTION VALUE="4">Gardien</OPTION>
							<OPTION VALUE="1">Construction</OPTION>
							<OPTION VALUE="5">Passe</OPTION>
							<OPTION VALUE="2">Ailier</OPTION>
							<OPTION VALUE="6">D&eacute;fense</OPTION>
							<OPTION VALUE="3">Buteur</OPTION>
							<OPTION VALUE="7">Coups francs</OPTION>
							<OPTION VALUE="8">Experience</OPTION>
							<OPTION VALUE="10">Temp&eacute;rament de chef</OPTION>
						</SELECT>							  </td>
							  <td valign="top">								
						<SELECT NAME="SkillMin3" SIZE=1>
							<OPTION VALUE="" selected="selected">---Mini---</OPTION>
	
							<OPTION VALUE="0">inexistant</OPTION>
	
							<OPTION VALUE="1">catastroph</OPTION>
	
							<OPTION VALUE="2">mauvais</OPTION>
	
							<OPTION VALUE="3">m&eacute;diocre</OPTION>
	
							<OPTION VALUE="4">faible</OPTION>
	
							<OPTION VALUE="5">inad&eacute;quat</OPTION>
	
							<OPTION VALUE="6">passable</OPTION>
	
							<OPTION VALUE="7">honorable</OPTION>
	
							<OPTION VALUE="8">excellent</OPTION>
	
							<OPTION VALUE="9">formidable</OPTION>
	
							<OPTION VALUE="10">impression</OPTION>
	
							<OPTION VALUE="11">brillant</OPTION>
	
							<OPTION VALUE="12">inoubliabl</OPTION>
	
							<OPTION VALUE="13">l&eacute;gendaire</OPTION>
	
							<OPTION VALUE="14">surnaturel</OPTION>
	
							<OPTION VALUE="15">titanesque</OPTION>
	
							<OPTION VALUE="16">extra-terr</OPTION>
	
							<OPTION VALUE="17">mythique</OPTION>
	
							<OPTION VALUE="18">magique</OPTION>
	
							<OPTION VALUE="19">utopique</OPTION>
	
							<OPTION VALUE="20">divin</OPTION>
						</SELECT>							  </td>
							  <td valign="top">								
						<SELECT NAME="SkillMax3" SIZE=1>
							<OPTION VALUE="" selected="selected">---Maxi---</OPTION>
	
							<OPTION VALUE="0">inexistant</OPTION>
	
							<OPTION VALUE="1">catastroph</OPTION>
	
							<OPTION VALUE="2">mauvais</OPTION>
	
							<OPTION VALUE="3">m&eacute;diocre</OPTION>
	
							<OPTION VALUE="4">faible</OPTION>
	
							<OPTION VALUE="5">inad&eacute;quat</OPTION>
	
							<OPTION VALUE="6">passable</OPTION>
	
							<OPTION VALUE="7">honorable</OPTION>
	
							<OPTION VALUE="8">excellent</OPTION>
	
							<OPTION VALUE="9">formidable</OPTION>
	
							<OPTION VALUE="10">impression</OPTION>
	
							<OPTION VALUE="11">brillant</OPTION>
	
							<OPTION VALUE="12">inoubliabl</OPTION>
	
							<OPTION VALUE="13">l&eacute;gendaire</OPTION>
	
							<OPTION VALUE="14">surnaturel</OPTION>
	
							<OPTION VALUE="15">titanesque</OPTION>
	
							<OPTION VALUE="16">extra-terr</OPTION>
	
							<OPTION VALUE="17">mythique</OPTION>
	
							<OPTION VALUE="18">magique</OPTION>
	
							<OPTION VALUE="19">utopique</OPTION>
	
							<OPTION VALUE="20">divin</OPTION>
						</SELECT>						      </td>
						     </tr>
							</table>						</TD>
					</TR>
					<TR>
						<TD COLSPAN="3" VALIGN="top">
							<table>

							 <tr>
							  <td valign="top">								
						<SELECT NAME="SkillType4" SIZE=1>
							<OPTION VALUE="">---Comp&eacute;tence 4---</OPTION>
	
							<OPTION VALUE="9">Endurance</OPTION>
							<OPTION VALUE="4">Gardien</OPTION>
							<OPTION VALUE="1">Construction</OPTION>
							<OPTION VALUE="5">Passe</OPTION>
							<OPTION VALUE="2">Ailier</OPTION>
							<OPTION VALUE="6">D&eacute;fense</OPTION>
							<OPTION VALUE="3">Buteur</OPTION>
							<OPTION VALUE="7">Coups francs</OPTION>
							<OPTION VALUE="8">Experience</OPTION>
							<OPTION VALUE="10">Temp&eacute;rament de chef</OPTION>
						</SELECT>							  </td>
							  <td valign="top">		
						<SELECT NAME="SkillMin4" SIZE=1>
							<OPTION VALUE="" selected="selected">---Mini---</OPTION>
	
							<OPTION VALUE="0">inexistant</OPTION>
	
							<OPTION VALUE="1">catastroph</OPTION>
	
							<OPTION VALUE="2">mauvais</OPTION>
	
							<OPTION VALUE="3">m&eacute;diocre</OPTION>
	
							<OPTION VALUE="4">faible</OPTION>
	
							<OPTION VALUE="5">inad&eacute;quat</OPTION>
	
							<OPTION VALUE="6">passable</OPTION>
	
							<OPTION VALUE="7">honorable</OPTION>
	
							<OPTION VALUE="8">excellent</OPTION>
	
							<OPTION VALUE="9">formidable</OPTION>
	
							<OPTION VALUE="10">impression</OPTION>
	
							<OPTION VALUE="11">brillant</OPTION>
	
							<OPTION VALUE="12">inoubliabl</OPTION>
	
							<OPTION VALUE="13">l&eacute;gendaire</OPTION>
	
							<OPTION VALUE="14">surnaturel</OPTION>
	
							<OPTION VALUE="15">titanesque</OPTION>
	
							<OPTION VALUE="16">extra-terr</OPTION>
	
							<OPTION VALUE="17">mythique</OPTION>
	
							<OPTION VALUE="18">magique</OPTION>
	
							<OPTION VALUE="19">utopique</OPTION>
	
							<OPTION VALUE="20">divin</OPTION>
						</SELECT>							  </td>
							  <td valign="top">								
						<SELECT NAME="SkillMax4" SIZE=1>
							<OPTION VALUE="" selected="selected">---Maxi---</OPTION>
	
							<OPTION VALUE="0">inexistant</OPTION>
	
							<OPTION VALUE="1">catastroph</OPTION>
	
							<OPTION VALUE="2">mauvais</OPTION>
	
							<OPTION VALUE="3">m&eacute;diocre</OPTION>
	
							<OPTION VALUE="4">faible</OPTION>
	
							<OPTION VALUE="5">inad&eacute;quat</OPTION>
	
							<OPTION VALUE="6">passable</OPTION>
	
							<OPTION VALUE="7">honorable</OPTION>
	
							<OPTION VALUE="8">excellent</OPTION>
	
							<OPTION VALUE="9">formidable</OPTION>
	
							<OPTION VALUE="10">impression</OPTION>
	
							<OPTION VALUE="11">brillant</OPTION>
	
							<OPTION VALUE="12">inoubliabl</OPTION>
	
							<OPTION VALUE="13">l&eacute;gendaire</OPTION>
	
							<OPTION VALUE="14">surnaturel</OPTION>
	
							<OPTION VALUE="15">titanesque</OPTION>
	
							<OPTION VALUE="16">extra-terr</OPTION>
	
							<OPTION VALUE="17">mythique</OPTION>
	
							<OPTION VALUE="18">magique</OPTION>
	
							<OPTION VALUE="19">utopique</OPTION>
	
							<OPTION VALUE="20">divin</OPTION>
						</SELECT>						      </td>
						     </tr>
							</table>						</TD>
					</TR>
					<TR>
						<TD COLSPAN="2" VALIGN="top">
						<BR>
						<INPUT TYPE="radio" NAME="joueurArchive" VALUE="0" CHECKED>Cacher les joueurs archiv&eacute;s.<BR>
						<INPUT TYPE="radio" NAME="joueurArchive" VALUE="1" >Seulement dans les joueurs archiv&eacute;s<BR>
					</TR>
          <TR>
						<TD COLSPAN="2" VALIGN="top">
						<BR><B>Niveau de l'entraineur :</B><BR>
						<INPUT TYPE="radio" NAME="NivEntraineur" VALUE="-1" CHECKED>Tous
						<INPUT TYPE="radio" NAME="NivEntraineur" VALUE="8" >Excellent
						<INPUT TYPE="radio" NAME="NivEntraineur" VALUE="7" >Honorable
						<INPUT TYPE="radio" NAME="NivEntraineur" VALUE="6" >Passable ou inférieur
						<BR>
						<BR><B>Tri des r&eacute;sultats :</B><BR>
						<SELECT NAME="ordreDeTriNb" SIZE=1>
							<OPTION VALUE="">---Tri-par-D&eacute;faut---</OPTION>
							<OPTION VALUE="1">Tri par mise &agrave; jour DTN</OPTION>
							<OPTION VALUE="2">Tri par mise &agrave; jour DTN invers&eacute;e</OPTION>
							<OPTION VALUE="18">Tri par mise &agrave; jour propri&eacute;taire</OPTION>
							<OPTION VALUE="19">Tri par mise &agrave; jour propri&eacute;taire invers&eacute;e</OPTION>
							<OPTION VALUE="3">Tri par TSI </OPTION>
							<OPTION VALUE="20">Tri par salaire réel</OPTION>
							<OPTION VALUE="21">Tri par salaire de base</OPTION>
              <OPTION VALUE="23">Tri par salaire de base invers&eacute;</OPTION>
              <OPTION VALUE="24">Tri par âge</OPTION>
              <OPTION VALUE="25">Tri par âge invers&eacute;</OPTION>
              <OPTION VALUE="26">Tri par TDC</OPTION>
              <OPTION VALUE="4">Tri par id Hattrick </OPTION>
							<OPTION VALUE="5">Tri par note gK </OPTION>
							<OPTION VALUE="6">Tri par note cD normal </OPTION>
							<OPTION VALUE="7">Tri par note cD off </OPTION>
							<OPTION VALUE="8">Tri par note wB off </OPTION>
							<OPTION VALUE="9">Tri par note wB normal </OPTION>
							<OPTION VALUE="10">Tri par note iM normal </OPTION>
							<OPTION VALUE="11">Tri par note iM off </OPTION>
							<OPTION VALUE="12">Tri par note iM def </OPTION>
							<OPTION VALUE="13">Tri par note Wg normal </OPTION>
							<OPTION VALUE="14">Tri par note Wg off </OPTION>
							<OPTION VALUE="15">Tri par note Wg towards middle </OPTION>
							<OPTION VALUE="16">Tri par note Fw normal </OPTION>
							<OPTION VALUE="22">Tri par note Fw towards wings </OPTION>
							<OPTION VALUE="17">Tri par note Fw def </OPTION>
						</SELECT>						</TD>
						<TD COLSPAN="1" ALIGN="RIGHT" VALIGN="bottom">
						<input type="hidden" name="action" value="submitted" >
						<INPUT TYPE="submit" VALUE="   rechercher !   ">						</TD>
					</TR>
					</TABLE>
				  </FORM>
					<BR>
				</TD>
			</TR>
			</TABLE>
	  </TD>
	  <FORM ACTION="recherche_match_result.php" METHOD="GET">
  
<table width=100% bgcolor=#EEEEFF>
<tr><td colspan=2>
<center><b>Rechercher une performance en match</b></center><p>
</td></tr><tr><td>
<b>Poste occup&eacute; :</b></td><td> 

						<SELECT NAME="posteTerrain" SIZE=1>
							<OPTION VALUE="0">un gardien </OPTION>
							<OPTION VALUE="1">un d&eacute;fenseur </OPTION>
							<OPTION VALUE="2">un ailier</OPTION>
							<OPTION VALUE="3">un milieu </OPTION>
							<OPTION VALUE="4">un attaquant </OPTION>
							<OPTION VALUE="-1">--ou plus pr&eacute;cisemment :--</OPTION>
							<OPTION VALUE="5">cD normal </OPTION>
							<OPTION VALUE="6">cD off </OPTION>
							<OPTION VALUE="7">cD towards wings </OPTION>
							<OPTION VALUE="8">wB off </OPTION>
							<OPTION VALUE="9">wB normal </OPTION>
							<OPTION VALUE="10">wB def </OPTION>
							<OPTION VALUE="11">wB towards middle </OPTION>
							<OPTION VALUE="12">iM normal </OPTION>
							<OPTION VALUE="13">iM off </OPTION>
							<OPTION VALUE="14">iM def </OPTION>
							<OPTION VALUE="15">iM towards wings </OPTION>
							<OPTION VALUE="16">Wg normal </OPTION>
							<OPTION VALUE="17">Wg off </OPTION>
							<OPTION VALUE="18">Wg def </OPTION>
							<OPTION VALUE="19">Wg towards middle </OPTION>
							<OPTION VALUE="20">Fw normal </OPTION>
							<OPTION VALUE="21">Fw towards wings </OPTION>
							<OPTION VALUE="22">Fw def </OPTION>
						</SELECT>

						<SELECT NAME="formeMin" SIZE=1>
							<OPTION VALUE="-1" SELECTED>---Forme mini indiff&eacute;rente---</OPTION>
							<OPTION VALUE="1">catastroph</OPTION>
							<OPTION VALUE="2">mauvais</OPTION>
							<OPTION VALUE="3">m&eacute;diocre</OPTION>
							<OPTION VALUE="4">faible</OPTION>
							<OPTION VALUE="5">inad&eacute;quat</OPTION>
							<OPTION VALUE="6">passable</OPTION>
							<OPTION VALUE="7">honorable</OPTION>
							<OPTION VALUE="8">excellent</OPTION>
						</select>

						<SELECT NAME="formeMax" SIZE=1>
							<OPTION VALUE="-1" SELECTED>---Forme maxi indiff&eacute;rente---</OPTION>
							<OPTION VALUE="1">catastroph</OPTION>
							<OPTION VALUE="2">mauvais</OPTION>
							<OPTION VALUE="3">m&eacute;diocre</OPTION>
							<OPTION VALUE="4">faible</OPTION>
							<OPTION VALUE="5">inad&eacute;quat</OPTION>
							<OPTION VALUE="6">passable</OPTION>
							<OPTION VALUE="7">honorable</OPTION>
							<OPTION VALUE="8">excellent</OPTION>
						</select>
						</td>
					</tr>
					<TR>
						<TD  VALIGN="middle"><B>Age:&nbsp; </B>
						 &nbsp; &nbsp; &nbsp;</td><td>

							Mini <INPUT TYPE="text" NAME="minAge" VALUE="" SIZE="3" MAXLENGTH="3"> ans
							&nbsp; &nbsp; &nbsp; &nbsp; 
							 Maxi <INPUT TYPE="text" NAME="maxAge" VALUE="" SIZE="3" MAXLENGTH="3"> ans
							  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
	Sp&eacute;cialit&eacute; :<Select NAME="specialty">
							<Option VALUE="-1" SELECTED>-- Toutes --</OPTION>
							<Option VALUE="0" >Aucune</OPTION>
							<Option VALUE="1" >Technique</OPTION>
							<Option VALUE="2" >Rapide</OPTION>
							<Option VALUE="3" >Costaud</OPTION>
							<Option VALUE="4" >Impr&eacute;visible</OPTION>
							<Option VALUE="5" >Joueur de t&ecirc;te</OPTION>
							
							</Select>
							 								
						</TD>
					</TR>
					<TR>
						<TD  VALIGN="middle">
						<b>Etoiles :</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td><td>
							<SELECT NAME="etoilesMin" SIZE=1>
								<OPTION VALUE="-1" SELECTED>---Nombre d'&eacute;toiles mini---</OPTION>
								<OPTION VALUE="1">1</OPTION>
								<OPTION VALUE="2">2</OPTION>
								<OPTION VALUE="3">3</OPTION>
								<OPTION VALUE="4">4</OPTION>
								<OPTION VALUE="5">5</OPTION>
								<OPTION VALUE="6">6</OPTION>
								<OPTION VALUE="7">7</OPTION>
								<OPTION VALUE="8">8</OPTION>
								<OPTION VALUE="9">9</OPTION>
								<OPTION VALUE="9">10</OPTION>
							</select>
							<SELECT NAME="etoilesMax" SIZE=1>
								<OPTION VALUE="-1" SELECTED>---Nombre d'&eacute;toiles maxi---</OPTION>
								<OPTION VALUE="1">1</OPTION>
								<OPTION VALUE="2">2</OPTION>
								<OPTION VALUE="3">3</OPTION>
								<OPTION VALUE="4">4</OPTION>
								<OPTION VALUE="5">5</OPTION>
								<OPTION VALUE="6">6</OPTION>
								<OPTION VALUE="7">7</OPTION>
								<OPTION VALUE="8">8</OPTION>
								<OPTION VALUE="9">9</OPTION>
								<OPTION VALUE="9">10</OPTION>
							</select>
						
						
						</td>
					</tr>
					<TR>
						<TD  VALIGN="middle">
						<b>Rechercher depuis :</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td><td VALIGN="middle">
							<SELECT NAME="depuisDate" SIZE=1>
<?php
$todaySeason=getSeasonWeekOfMatch(mktime(0,0,0,date('m'), date('d'),date('Y')));
$compteur=0;

$mseason=$todaySeason["season"];
$mweek=$todaySeason["week"];?>
							<OPTION VALUE="<?=$mseason?>-<?=$mweek?>">S<?=$mseason?> W<?=$mweek?></OPTION>
<?php while ($compteur <10){
	$compteur=$compteur+1;
	if ($mweek==0){
		$mseason=$mseason-1;
		$mweek=16;
	}
	$mweek=$mweek-1;
	?>
							<OPTION VALUE="<?=$mseason?>-<?=$mweek?>">S<?=$mseason?> W<?=$mweek?></OPTION>
	<?php
}
?>							
							</select>
							<div align=middle>
								<INPUT TYPE="submit" VALUE="   rechercher !   "></div>
						</td>
					</tr>

</table>  
  </form>
		</tr>
  </table>
</body>
</html>

