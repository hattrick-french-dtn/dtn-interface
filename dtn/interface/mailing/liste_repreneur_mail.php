<?php
// Affiche toutes les erreurs
error_reporting(E_ALL);

require_once "../_config/CstGlobals.php"; // fonctions d'admin
//require_once "../fonctions/HT_Client.php"; // Hattrick Client New (with Advanced HTTP Client)
//require_once "../fonctions/phpxml.php"; // XML to Tree converter
require_once "../fonctions/AccesBase.php"; // fonction de connexion � la base
require_once "../fonctions/AdminDtn.php"; // fonctions d'admin
require_once("../includes/head.inc.php");
require_once("../includes/serviceListesDiverses.php");
require_once ("../includes/serviceJoueur.php");
require_once ("../includes/serviceiihelp.php");

require_once ("../includes/langue.inc.php"); // 23/01/2010 jojoje86 besoin pour la spécialité

$headers ='from:contact@ht-fff.org'."\n";
$headers .='Content-Type: text/html; charset="iso-8859-1"'."\n";
$headers .='Content-Transfer-Encoding: 8bit'; 
$i=0;
$erreur="Personne pour cet entrainement";
$maBase = initBD();

if (isset($_POST['training'])) $training = $_POST['training'];
if (isset($_POST['id_HT'])) $id_HT = $_POST['id_HT'];

if(!$sesUser["idAdmin"])
{
    header("location: ../index.php?ErrorMsg=Session_Expire");
    exit();
}
				 
				$AgeAnneeSQL=getCalculAgeAnneeSQL();
				$AgeJourSQL=getCalculAgeJourSQL();		
						
				$sql = "select *,ht_joueurs.optionJoueur as specialite,ht_iiihelp_joueur.commentaire as comment,".$AgeAnneeSQL." as AgeAn,".$AgeJourSQL." as AgeJour from ht_iiihelp_joueur, ht_joueurs, ht_clubs, ht_pays where ht_iiihelp_joueur.id_dtn = ht_joueurs.idJoueur and ht_iiihelp_joueur.id_HT = $id_HT and ht_iiihelp_joueur.id_HT = ht_joueurs.idHattrickJoueur and ht_iiihelp_joueur.entrainement_souhaite = $training and ht_joueurs.teamid = ht_clubs.idClubHT and ht_pays.idPays=ht_clubs.idPays_fk";
				$req=  mysql_query($sql);
				$res = mysql_fetch_object($req);
				 
				$carac=get_Carac_byID($res->entrainement_souhaite);
				
				$sql3 = "select intituleCaracFR from ht_caracteristiques where numCarac=$res->idEndurance";
				$req3 = mysql_query($sql3);
				$endu = mysql_fetch_object($req3);
				
				$sql3 = "select *, idCaractere as nbCaractere from ht_caractere where numCaractere=$res->idCaractere_fk";
				$req3 = mysql_query($sql3);
				$caractere = mysql_fetch_object($req3);
				
				$sql3 = "select *, idAggres-1 as nbAggres from ht_aggres where numAggres=$res->idAggre_fk";
				$req3 = mysql_query($sql3);
				$aggres = mysql_fetch_object($req3);

				$sql3 = "select *, idHonnetete as nbHonnetete from ht_honnetete where numHonnetete=$res->idHonnetete_fk";
				$req3 = mysql_query($sql3);
				$honnetete = mysql_fetch_object($req3);
				
				$sql3 = "select intituleCaracFR from ht_caracteristiques where idCarac=$res->idExperience_fk";
				$req3 = mysql_query($sql3);
				$xp = mysql_fetch_object($req3);

				$sql3 = "select intituleCaracFR from ht_caracteristiques where idCarac=$res->idLeader_fk";
				$req3 = mysql_query($sql3);
				$tdc = mysql_fetch_object($req3);	

				$sql3 = "select intituleCaracFR from ht_caracteristiques where idCarac=$res->idGardien";
				$req3 = mysql_query($sql3);
				$gb = mysql_fetch_object($req3);		

				$sql3 = "select intituleCaracFR from ht_caracteristiques where idCarac=$res->idDefense";
				$req3 = mysql_query($sql3);
				$def = mysql_fetch_object($req3);	

				$sql3 = "select intituleCaracFR from ht_caracteristiques where idCarac=$res->idConstruction";
				$req3 = mysql_query($sql3);
				$const = mysql_fetch_object($req3);				

				$sql3 = "select intituleCaracFR from ht_caracteristiques where idCarac=$res->idAilier";
				$req3 = mysql_query($sql3);
				$ailier = mysql_fetch_object($req3);
				
				$sql3 = "select intituleCaracFR from ht_caracteristiques where idCarac=$res->idPasse";
				$req3 = mysql_query($sql3);
				$passe = mysql_fetch_object($req3);
				
				$sql3 = "select intituleCaracFR from ht_caracteristiques where idCarac=$res->idButeur";
				$req3 = mysql_query($sql3);
				$but = mysql_fetch_object($req3);
				
				$sql3 = "select intituleCaracFR from ht_caracteristiques where idCarac=$res->idPA";
				$req3 = mysql_query($sql3);
				$cf = mysql_fetch_object($req3);
				
				
				$type_age="erreur";
				if ($res->cat_age=="+21 ans")
				{
				$type_age="21 ans et +";
				}
				if ($res->cat_age=="17-20 ans")
				{
				$type_age="17-20 ans";
				}

$messagemail = "Bonjour,<br><br> 

Vous recevez ce message suite &agrave; votre inscription &agrave; iiihelp pour un entrainement <b>".$carac['nomTypeCarac']."</b> pour des joueurs de <b>".$type_age."</b>.<br><br>

Le joueur suivant : <br><br>

<b>".$res->nomJoueur." (".$id_HT.")</b><br>
Age : <b>".$res->AgeAn." ans</b> et <b>".$res->AgeJour." jours</b><br><br>

Endurance : ".$endu->intituleCaracFR." (".$res->idEndurance.")<br><br>

Un type ".$caractere->intituleCaractereFR." (".$caractere->nbCaractere.") qui est ".$aggres->intituleAggresFR." (".$aggres->nbAggres.") et ".$honnetete->intituleHonneteteFR." (".$honnetete->nbHonnetete.")<br>
Poss&egrave;de un ".$xp->intituleCaracFR." (".$res->idExperience_fk.") niveau d'exp&eacute;rience et un ".$tdc->intituleCaracFR." (".$res->idLeader_fk.") temp&eacute;rament de chef.<br><br>

Salaire : ".(($res->salary)/10)." &euro;<br>
Salaire en France : ".((ROUND($res->salary/$res->coefSalary))/10)." &euro;<br><br>";

if ($res->specialite != 0)
{
$messagemail .= "Sp&eacute;cialit&eacute; : ".$option[$res->specialite]["FR"]. "<br><br>";
}

$messagemail .= "Caract&eacute;ristiques :<br>
Gardien : ".$gb->intituleCaracFR." (".$res->idGardien.")<br>
D&eacute;fense : ".$def->intituleCaracFR." (".$res->idDefense.")<br>
Construction : ".$const->intituleCaracFR." (".$res->idConstruction.")<br>
Ailier : ".$ailier->intituleCaracFR." (".$res->idAilier.")<br>
Passe : ".$passe->intituleCaracFR." (".$res->idPasse.")<br>
Buteur : ".$but->intituleCaracFR." (".$res->idButeur.")<br>
Coup Franc : ".$cf->intituleCaracFR." (".$res->idPA.")<br>";
if ($type == 1)
{
$messagemail .= "<br>

va &ecirc;tre mis en vente sous peu<br>";
}
else
{
$messagemail .= "<br>

est en vente actuellement<br>";
}
if ($map != "")
{
	$messagemail .= "
MAP : ".$map." &euro;<br><br>";
}
	$messagemail .= "

Vente suivie par : ".stripslashes($dtn)."<br><br>

Commentaire : ".stripslashes($commentaire);

  $listmail = "";
  $sql  = get_iiihelp_repreneur_clubs_SQL();
	$sql .= " AND etat = 0 
	          AND (
                (entrainement_voulu1 in ($res->entrainement_souhaite,-1) AND age_voulu1='Tous')
            OR  (entrainement_voulu2 in ($res->entrainement_souhaite,-1) AND age_voulu2='Tous')";
	if ($res->cat_age=="+21 ans")
	{
  	$sql .= " OR (entrainement_voulu1 in ($res->entrainement_souhaite,-1) AND age_voulu1='+21 ans')";
  	$sql .= " OR (entrainement_voulu2 in ($res->entrainement_souhaite,-1) AND age_voulu2='+21 ans')";
	}
	if ($res->cat_age=="17-20 ans")
	{
  	$sql .= " OR (entrainement_voulu1 in ($res->entrainement_souhaite,-1) AND age_voulu1='17-20 ans')";
  	$sql .= " OR (entrainement_voulu2 in ($res->entrainement_souhaite,-1) AND age_voulu2='17-20 ans')";
	}
	$sql .= ") ORDER BY idClubHT";
	$req2 = mysql_query($sql) or die(mysql_error()."\n".$sql);
  while ($res2 = mysql_fetch_object($req2))
  {
  $listmail = $res2->email; 
				
  $desinscription = "<br /><br /><br />
Pour vous d&eacute;sincrire de iiihelp : <a href='http://".$_SERVER['SERVER_NAME']."/desinscription_iiihelp.php?id=".$res2->id_iiihelp_repreneur."'>Cliquez ici</a>";

  $modifinscription = "<br />
Pour modifier votre inscription iiihelp : <a href='http://".$_SERVER['SERVER_NAME']."/fff_help.php'>Cliquez ici</a>";

if(mail($listmail, "DTN Hattrick : vente de joueur", $messagemail.$desinscription.$modifinscription, $headers)) 
{
  $erreur="good";
  $i=$i+1;
}
else
{
  $erreur="Echec sur un ou plusieurs envois";
}
//echo $listmail;
/*mail("g.fayollecoinde@free.fr", "DTN Hattrick : vente de joueur", utf8_decode($messagemail), "from:contact@ht-fff.org");
mail("pouin23@hotmail.com", "DTN Hattrick : vente de joueur", utf8_decode($messagemail), "from:contact@ht-fff.org");*/
}
	$newetat = $type;
	$sqlupdate = "update ht_iiihelp_joueur set etat = $newetat where ht_iiihelp_joueur.id_HT = $id_HT ";
	$req=  mysql_query($sqlupdate);

if ($erreur=="good")
{?>
Le mailing a correctement &eacute;t&eacute; effectu&eacute; sur <?=$i?> personnes !<br>
<?php }
else
{?>
Erreur, <?=$erreur?>, pr&eacute;venir les DTN~<br>
<?php }?>
<A HREF=# style=\"text-decoration:none\" onClick="javascript:history.go(-2);">Retour</A>
<?php 
// header("location:liste_joueur_iiihelp.php");
exit();
?>