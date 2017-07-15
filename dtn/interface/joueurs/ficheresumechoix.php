<?php
require_once("../includes/head.inc.php");

if(!$sesUser["idAdmin"])
{
	header("location: index.php?ErrorMsg=Session Expiree");
	exit();
}

if(!isset($lang)) $lang = "FR";
if($lang == "fr") $lang = "FR";
if($lang == "en") $lang = "EN";

require("../includes/serviceEntrainement.php");
require("../includes/serviceJoueur.php");
require("../includes/langue.inc.php");
require("../includes/serviceListesDiverses.php");

if (isset($origine) && $origine=="unique") //appel provient de la fiche d'un joueur
{
	if (isset($htid))
	{
		$infJs[1] = getJoueurHt($htid);
		$id = $infJs[1]["idJoueur"];
	}
	else
		$infJs[1] = getJoueur($id);
}
else  //appel provient de ficherecupchoix.php
{
	$origine = "";
	$tlistID = explode(";",$listID);  //extraire les différents id
	for($i=0;$i<count($tlistID);$i++)
    {
		$infJs[$i+1] = getJoueurHt($tlistID[$i]); //reconstruit un tableau contenant toutes les données des joueurs sélectionnés
    }
}

$infJs[0]=$infJs[1];
if ($origine!="unique")
{
	$infJs[0]['nomJoueur']="R&eacute;sum&eacute; Multijoueurs";
	$infJs[0]['prenomJoueur']=""; //au cas où !!!
}
  
$infJ = $infJs[0];

	
//Recherche le libellé du type d'entrainement
//Fireproofed le 28/01/2011
$sql="SELECT libelle_type_entrainement FROM ht_type_entrainement WHERE id_type_entrainement = '".$infJs[1]['entrainement_id']."' ";
$req = $conn->query($sql);
$result=$req->fetch();
$req=NULL;

$infJs[1]['entrainement_type']=$result['libelle_type_entrainement'];
if ($infJs[1]['entrainement_type']=='') $infJs[1]['entrainement_type']='non renseigné';


?><html>
<head>
<title>Fiche <?=$infJs[0]["prenomJoueur"]?> <?=$infJs[0]["nomJoueur"]?></title>
<script src="../../../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../../../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">


<style type="text/css">


<!--
.Style1 {color: #FF0000}
-->
</style>


</head>

<body>

<?php
	//prépare les variables HTML pour utilisation dans fonction JAVASCRIPT majtext()
	//Fireproofed 28/01/2011 et 30/04/2011
	$infJs[0]=$infJs[1];
  //âge du joueur
	for  ($k=1;$k<count($infJs);$k++)
	{
    $ageetjours = ageetjour($infJs[$k]["datenaiss"]);
  	$tabage[$k] = explode(" - ",$ageetjours);
    //calcul du nombre de jours depuis la dernière modification
    $datemaj = explode("-",$infJs[$k]["date_modif_effectif"]);
    $jdatemaj = mktime(0,0,0,$datemaj[1],$datemaj[2],$datemaj[0]);
    $jdateauj = mktime(0,0,0,date("m"),date("d"),date("Y"));
    $i=ceil(($jdateauj-$jdatemaj)/3600/24);
    $dermod[$k]=$i." jour";
    if ($i>1) $dermod[$k]=$dermod[$k]."s";
    $dermod[$k]=$dermod[$k].' ['.date("d/m/Y",mktime(0,0,0,$datemaj[1],$datemaj[2],$datemaj[0])).']';
    //calcul de l'âge du joueur en jours pour situation par rapport à la CDM
  	$jourj0 = ($sesUser["dateSemaine0"]+3600-574729200)/3600/24;
   	$jourjoueur[$k] = $jourj0 - $infJs[$k]["datenaiss"];
    }
?>


  
<script type="text/javascript">
function choixcompl()
  {
  if (document.forms.form3.fires.value!='') scanid();
  }
</script>

<script type="text/javascript">
function scanid()
  {
  var totalta='';
  var z='';
  <?php
  	for ($i=1;$i<count($infJs);$i++)
  	{
  ?>
    //met à jour en temps réel le textarea
    //Fireproofed le 28/01/2011
    
    // ajout du niveau de l'entraineur par jojoje86 le 21/07/09-->
  
    var a='';
    var b='';
    var c='';
    var couleur='0';
    
  	if ('<?=$sesUser["saison"]?>' % 2 == 0) {
  	// saison paire CDM
  		if ('<?=$jourjoueur[$i]?>' > 2067) {
  			if ('<?=$jourjoueur[$i]?>' < 2129)
  				a= '[color=blue]';
  			else if ('<?=$jourjoueur[$i]?>' < 2178)
  				a= '[color=red]';
  			else if ('<?=$jourjoueur[$i]?>' < 2292)
  				a= '[color=green]';
  			else if ('<?=$jourjoueur[$i]?>' < 2239)
  				a= '[color=green]';
  			else if ('<?=$jourjoueur[$i]?>' < 2255)
  				a= '[color=orange]';
  			else if ('<?=$jourjoueur[$i]?>' < 2290)
  				a= '[color=violet]';
  		}
  	}
  	else {
  		if ('<?=$jourjoueur[$i]?>' < 2127)
  			a= '[color=green]';
  		else if ('<?=$jourjoueur[$i]?>' < 2143)
  			a= '[color=orange]';
  		else if ('<?=$jourjoueur[$i]?>' < 2178)
  			a= '[color=violet]';
  		else if ('<?=$jourjoueur[$i]?>' < 2241)
  			a= '[color=blue]';
  		else if ('<?=$jourjoueur[$i]?>' < 2290)
  			a= '[color=red]';
  	}
  	if (a!='') couleur='1';
  
    if ('<?=$infJs[$i]["niv_Entraineur"]?>'<7) a=a+' :evil: ';
    if ('<?=$infJs[$i]["niv_Entraineur"]?>'==7) a=a+' :arrow: ';
    if ('<?=$infJs[$i]["niv_Entraineur"]?>'==8) a=a+' :D ';
    
    a=a+'[b]';
    a=a+'<?=ucwords(strtolower($infJs[$i]["prenomJoueur"]))?> ';
    a=a+'<?=ucwords(strtolower($infJs[$i]["nomJoueur"]))?>';
    a=a+' (<?=strtolower($infJs[$i]["idHattrickJoueur"])?>)';
    a=a+'[/b]'; 
  	a=a+' <?=$tabage[$i][0]?> ans et <?=$tabage[$i][1]?> jour';
  	if ('<?=$tabage[$i][1]?>'>1) a=a+'s';
  	a=a+' ';
  
    //modification sur toutes les fiches pour l'ajout du CF par jojoje86 le 21/07/09
    //détermination du choix de l'utilisateur : on regarde le type de résumé choisi par bouton radio
    //Fireproofed le 28/01/2011
    
    if (document.forms.form1.typeresume[0].checked) {	
      //choix=GK
      b='(G/D'; 
      if ('<?=$infJs[$i]["entrainement_id"]?>'==10) c='[b][i]';
      c=c+'<?=$infJs[$i]["idGardien"]?>+<?=$infJs[$i]["nbSemaineGardien"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==10) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJs[$i]["entrainement_id"]?>'==4) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idDefense"]?>+<?=$infJs[$i]["nbSemaineDefense"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==4) c=c+'[/i][/b]';
      }
  
    if (document.forms.form1.typeresume[1].checked) {
      //choix=CD
      b='(D/P/C/A';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==4) c='[b][i]';
      c=c+'<?=$infJs[$i]["idDefense"]?>+<?=$infJs[$i]["nbSemaineDefense"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==4) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJs[$i]["entrainement_id"]?>'==8) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idPasse"]?>+<?=$infJs[$i]["nbSemainePasses"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==8) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJs[$i]["entrainement_id"]?>'==9) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idConstruction"]?>+<?=$infJs[$i]["nbSemaineConstruction"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==9) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJs[$i]["entrainement_id"]?>'==6) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idAilier"]?>+<?=$infJs[$i]["nbSemaineAilier"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==6) c=c+'[/i][/b]'; 
      }
  
    if (document.forms.form1.typeresume[2].checked) {
      //choix=CDoff
      b='(C/D/P';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==9) c='[b][i]';
      c=c+'<?=$infJs[$i]["idConstruction"]?>+<?=$infJs[$i]["nbSemaineConstruction"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==9) c=c+'[/i][/b]';
      c=c+' / '; 
  		if ('<?=$infJs[$i]["entrainement_id"]?>'==4) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idDefense"]?>+<?=$infJs[$i]["nbSemaineDefense"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==4) c=c+'[/i][/b]';
      c=c+' / '; 
  		if ('<?=$infJs[$i]["entrainement_id"]?>'==8) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idPasse"]?>+<?=$infJs[$i]["nbSemainePasses"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==8) c=c+'[/i][/b]'; 
      }
    
    if (document.forms.form1.typeresume[3].checked) {
      //choix=WB
      b='(A/D/P/C';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==6) c='[b][i]';
      c=c+'<?=$infJs[$i]["idAilier"]?>+<?=$infJs[$i]["nbSemaineAilier"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==6) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJs[$i]["entrainement_id"]?>'==4) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idDefense"]?>+<?=$infJs[$i]["nbSemaineDefense"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==4) c=c+'[/i][/b]';
      c=c+' / ';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==8) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idPasse"]?>+<?=$infJs[$i]["nbSemainePasses"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==8) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJs[$i]["entrainement_id"]?>'==9) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idConstruction"]?>+<?=$infJs[$i]["nbSemaineConstruction"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==9) c=c+'[/i][/b]';
      }
  
    if (document.forms.form1.typeresume[4].checked) {
      //choix=WG
      b='(A/P/C/D';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==6) c='[b][i]';
      c=c+'<?=$infJs[$i]["idAilier"]?>+<?=$infJs[$i]["nbSemaineAilier"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==6) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJs[$i]["entrainement_id"]?>'==8) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idPasse"]?>+<?=$infJs[$i]["nbSemainePasses"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==8) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJs[$i]["entrainement_id"]?>'==9) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idConstruction"]?>+<?=$infJs[$i]["nbSemaineConstruction"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==9) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJs[$i]["entrainement_id"]?>'==4) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idDefense"]?>+<?=$infJs[$i]["nbSemaineDefense"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==4) c=c+'[/i][/b]';
      }
      
    if (document.forms.form1.typeresume[5].checked) {
      //choix=WGpm
      b='(A/C/P';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==6) c='[b][i]';
      c=c+'<?=$infJs[$i]["idAilier"]?>+<?=$infJs[$i]["nbSemaineAilier"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==6) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJs[$i]["entrainement_id"]?>'==9) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idConstruction"]?>+<?=$infJs[$i]["nbSemaineConstruction"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==9) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJs[$i]["entrainement_id"]?>'==8) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idPasse"]?>+<?=$infJs[$i]["nbSemainePasses"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==8) c=c+'[/i][/b]';
      }
    
    if (document.forms.form1.typeresume[6].checked) {
      //choix=IM
      b='(E/C/P/D';
      c='<?=$infJs[$i]["idEndurance"]?> / ';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==9) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idConstruction"]?>+<?=$infJs[$i]["nbSemaineConstruction"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==9) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJs[$i]["entrainement_id"]?>'==8) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idPasse"]?>+<?=$infJs[$i]["nbSemainePasses"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==8) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJs[$i]["entrainement_id"]?>'==4) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idDefense"]?>+<?=$infJs[$i]["nbSemaineDefense"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==4) c=c+'[/i][/b]';
      }
  
    if (document.forms.form1.typeresume[7].checked) {
      //choix=FW
      b='(B/P/A';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==5) c='[b][i]';
      c=c+'<?=$infJs[$i]["idButeur"]?>+<?=$infJs[$i]["nbSemaineButeur"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==5) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJs[$i]["entrainement_id"]?>'==8) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idPasse"]?>+<?=$infJs[$i]["nbSemainePasses"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==8) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJs[$i]["entrainement_id"]?>'==6) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idAilier"]?>+<?=$infJs[$i]["nbSemaineAilier"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==6) c=c+'[/i][/b]';
      }
  
    if (document.forms.form1.typeresume[8].checked) {
      //choix=FWtw
      b='(B/A/P';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==5) c='[b][i]';
      c=c+'<?=$infJs[$i]["idButeur"]?>+<?=$infJs[$i]["nbSemaineButeur"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==5) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJs[$i]["entrainement_id"]?>'==6) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idAilier"]?>+<?=$infJs[$i]["nbSemaineAilier"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==6) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJs[$i]["entrainement_id"]?>'==8) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idPasse"]?>+<?=$infJs[$i]["nbSemainePasses"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==8) c=c+'[/i][/b]';
      }
  
    if (document.forms.form1.typeresume[9].checked) {
      //choix=FWdef
      b='(C/P/B/A';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==9) c='[b][i]';
      c=c+'<?=$infJs[$i]["idConstruction"]?>+<?=$infJs[$i]["nbSemaineConstruction"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==9) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJs[$i]["entrainement_id"]?>'==8) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idPasse"]?>+<?=$infJs[$i]["nbSemainePasses"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==8) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJs[$i]["entrainement_id"]?>'==5) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idButeur"]?>+<?=$infJs[$i]["nbSemaineButeur"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==5) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJs[$i]["entrainement_id"]?>'==6) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idAilier"]?>+<?=$infJs[$i]["nbSemaineAilier"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==6) c=c+'[/i][/b]';
      }
  
    //on regarde si des paramètres supplémentaires doivent être ajoutés à la fiche
    //avec test pour éviter les redondances
    //Fireproofed le 28/01/2011
    if ((document.forms.form2.parasup[0].checked)&&(b.indexOf('G')==-1)) {
      //choix=GK
      b=b+'/G';
      c=c+' / ';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==10) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idGardien"]?>+<?=$infJs[$i]["nbSemaineGardien"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==10) c=c+'[/i][/b]';
      }
  
    if ((document.forms.form2.parasup[1].checked)&&(b.indexOf('D')==-1)) {
      //choix=Défense
      b=b+'/D';
      c=c+' / ';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==4) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idDefense"]?>+<?=$infJs[$i]["nbSemaineDefense"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==4) c=c+'[/i][/b]';
      }
  
    if ((document.forms.form2.parasup[2].checked)&&(b.indexOf('C')==-1)) {
      //choix=Construction
      b=b+'/C';
      c=c+' / ';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==9) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idConstruction"]?>+<?=$infJs[$i]["nbSemaineConstruction"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==9) c=c+'[/i][/b]';
      }
  
    if ((document.forms.form2.parasup[3].checked)&&(b.indexOf('A')==-1)) {
      //choix=Ailier
      b=b+'/A';
      c=c+' / ';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==6) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idAilier"]?>+<?=$infJs[$i]["nbSemaineAilier"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==6) c=c+'[/i][/b]';
      }
  
    if ((document.forms.form2.parasup[4].checked)&&(b.indexOf('P')==-1)) {
      //choix=Passe
      b=b+'/P';
      c=c+' / ';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==8) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idPasse"]?>+<?=$infJs[$i]["nbSemainePasses"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==8) c=c+'[/i][/b]';
      }
  
    if ((document.forms.form2.parasup[5].checked)&&(b.indexOf('B')==-1)) {
      //choix=Buteur
      b=b+'/B';
      c=c+' / ';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==5) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idButeur"]?>+<?=$infJs[$i]["nbSemaineButeur"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==5) c=c+'[/i][/b]';
      }
  
    if ((document.forms.form2.parasup[6].checked)||(document.forms.form1.typeresume[0].checked)) {
      //choix=CF : case à cocher CF ou choix de type de résumé = GK
      b=b+'/CF';
      c=c+' / ';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==3) c=c+'[b][i]';
      c=c+'<?=$infJs[$i]["idPA"]?>';
      if ('<?=$infJs[$i]["entrainement_id"]?>'==3) c=c+'[/i][/b]';
      }
  
    if ((document.forms.form2.parasup[7].checked)&&(b.indexOf('E')==-1)) {
      //choix=Endurance
      b=b+'/E';
      c=c+' / <?=$infJs[$i]["idEndurance"]?>';    
      }
      
      
    b=b+') ';
    
    //Rajouter l'XP
    c=c+' / Xp <?=$infJs[$i]["idExperience_fk"]?>';
    
    //Rajouter le TDC si honorable
    if ('<?=$infJs[$i]["idLeader_fk"]?>'==7) c=c+' / [b]TDC 7[/b]';
    
    //Rajouter la spé. si il y en a une
    if ('<?=$infJs[$i]["optionJoueur"]?>'!=0) c=c+' / '+'[i]<?=$option[$infJs[$i]["optionJoueur"]]["FR"]?>[/i]';
    
    //Rajouter la date de dernière modification
    c=c+' / Dern. Modif : <?=$dermod[$i]?>';
    
    //Rajouter le nom du DTN en charge du joueur
    if (document.forms.form2.parasup[8].checked) {
      if ('<?=$infJs[$i]["dtnSuiviJoueur_fk"]?>'!=0) {
        c=c+' / Suivi:<?=$infJs[$i]["loginAdminSuiveur"]?>';    
      }
      else c=c+' / Non suivi';
    }
    
    //On rajoute [/color] si besoin = si couleur=true
    if (couleur=='1') c=c+'[/color]';
  
    //On concatène
    totalta=totalta+a+b+c;
    if ('<?=$origine?>'!='unique') totalta=totalta+'\n';
  <?php
    }
  ?>
  //et on affiche dans le textarea quand la boucle for est terminée
  document.forms.form3.fires.value=totalta;
  }
</script>


<script language="Javascript"> 
function copy2Clipboard(obj)
  {
      var textRange = document.body.createTextRange();
      textRange.moveToElementText(obj);
      textRange.execCommand("Copy");
  }
</script>


<?php
global $sesUser;
if ($origine=="unique") {
	switch($sesUser["idNiveauAcces"]){
	case "1":
		require("../menu/menuAdmin.php");
		break;
		
	case "2":
		require("../menu/menuSuperviseur.php");
		break;

	case "3":
		require("../menu/menuDTN.php");
		break;
		
	case "4":
		require("../menu/menuCoach.php");
		break;
		
	default;
		break;
	}
} else {
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
}

$idClubHT=$infJs[1]['teamid'];
$idHT=$infJs[1]['idHattrickJoueur'];
$lstPos = listPositionResume();
$lstSect = listAllPosition();
$listID = $idHT;

$pos=$lstSect[$infJs[1]['ht_posteAssigne']-1]["descriptifPosition"];
if ($pos=="") $pos="aucun";

$dtnsuivi="aucun";
if ($infJs[1]["dtnSuiviJoueur_fk"]!=0) $dtnsuivi=$infJs[1]["loginAdminSuiveur"];    

if ($origine=="unique") require("../menu/menuJoueur.php");

?>

<table width="99%" border="1" cellspacing="0" cellpadding="0">
    <tr>
    <?php
    if ($origine=="unique")
    {
    ?>
        <td width="100%" colspan="2" bgcolor="#000000"><div align="center"><b><font color="#FFFFFF">Fiche R&eacute;sum&eacute; : <?=$infJs[1]['prenomJoueur']?> <?=$infJs[1]['nomJoueur']?>&nbsp;-&nbsp;<?=$listID?>&nbsp;-&nbsp;Entrainement : <?=$infJs[1]['entrainement_type']?>&nbsp;-&nbsp;Secteur : <?=$pos?>&nbsp;-&nbsp;DTN : <?=$dtnsuivi?>
    <?php
    }
    else
    {
    ?>
		<td width="100%" colspan="2" bgcolor="#000000"><div align="center"><b><font color="#FFFFFF">Fiche R&eacute;sum&eacute; Multijoueurs : <?=count($infJ)-1?>&nbsp; joueur(s)        
    <?php
    }
    ?>
      </font></b></div>
      </td>
    </tr>
    
    <tr>
      <td width="50%" align="left" valign="top">
        <form name="form1" id="form1" method="get">
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr><td colspan="3" align="center">Choisissez le type de r&eacute;sum&eacute; souhait&eacute; :<br><br></td></tr>
            <tr>
              <td width="33%"><input name="typeresume" type="Radio" onClick="scanid();" Value="GK">GK (G/D/CF)<br></td>
              <td width="33%"><input name="typeresume" type="Radio" onClick="scanid();" Value="CD">CD (D/P/C/A)<br></td>
              <td width="33%"><input name="typeresume" type="Radio" onClick="scanid();" Value="CDoff">CDoff (C/D/P)<br></td>
            </tr>
            <tr>
              <td><input name="typeresume" type="Radio" onClick="scanid();" Value="Wb">Wb (A/D/P/C)<br></td>
              <td><input name="typeresume" type="Radio" onClick="scanid();" Value="Wg">Wg (A/P/C/D)<br></td>
              <td><input name="typeresume" type="Radio" onClick="scanid();" Value="Wgpm">Wgpm (A/C/P)<br></td>
            </tr>
            <tr>
              <td><input name="typeresume" type="Radio" onClick="scanid();" Value="IM">IM (C/P/D)<br></td>
              <td><input name="typeresume" type="Radio" onClick="scanid();" Value="Fw">Fw (B/P/A)<br></td>
              <td><input name="typeresume" type="Radio" onClick="scanid();" Value="Fwtw">Fwtw (B/A/P)<br></td>
            </tr>
            <tr>
              <td><input name="typeresume" type="Radio" onClick="scanid();" Value="Fwdef">Fwdef (C/P/B/A)<br></td>
            </tr>
          </table>
        </form>
      </td>

      <td width="50%" align="left" valign="top">
        <form name="form2" method="get">
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr><td colspan="3" align="center">Choisissez les param&egrave;tres suppl&eacute;mentaires :<br><br></td></tr>
            <tr>
              <td width="33%"><input name="parasup" type="Checkbox" onClick="choixcompl();" value="gk">Gardien<br></td>
              <td width="33%"><input name="parasup" type="Checkbox" onClick="choixcompl();" value="de">D&eacute;fense<br></td>
              <td width="33%"><input name="parasup" type="Checkbox" onClick="choixcompl();" value="pm">Construction<br></td>            </tr>
            </tr>
            <tr>  
              <td><input name="parasup" type="Checkbox" onClick="choixcompl();" value="ai">Ailier<br></td>
              <td><input name="parasup" type="Checkbox" onClick="choixcompl();" value="pa">Passe<br></td>
              <td><input name="parasup" type="Checkbox" onClick="choixcompl();" value="bu">Buteur<br></td>
            </tr>
            <tr>
              <td><input name="parasup" type="Checkbox" onClick="choixcompl();" value="cf">CF<br></td>
              <td><input name="parasup" type="Checkbox" onClick="choixcompl();" value="en">Endurance<br></td>
              <td><input name="parasup" type="Checkbox" onClick="choixcompl();" value="dtn">DTN<br></td>
            </tr>                
          </table>
        </form>
      </td>
    </tr>
    
    <tr> 
      <td border="0" colspan="2" bgcolor="#000000"><img src="../images/spacer.gif" width="1" height="1"></td>
    </tr>    	    
</table>

&nbsp;<br>

  
<!--********************************************************************
Mise en place pour affichage de la fiche en temps réel selon modif.
Nécessite une mise en fonction du calcul de la chaine de sortie
Fireproofed le 28/01/2011
*********************************************************************-->
      
<table width="99%" rules="none" border="1" cellspacing="0" cellpadding="0">
    <tr> 
      <td width="100%" colspan="2" bgcolor="#000000"><div align="center"><b><font color="#FFFFFF">R&eacute;sultat de la requ&ecirc;te 
      </font></b></div>
      </td>
    </tr>
    <tr>
      <td>
        &nbsp;
      </td>
    </tr>
    <tr>
      <td align="center" valign="center">  
        <form name=form3>
          <TextArea name="fires" style="font-size:8pt;font-family:Arial" onFocus="select();" cols=200 rows=20 wrap="off"></TextArea>
          <!--<Input type="text" name="fires" size="150" style="font-size:10pt;font-family:Arial" onFocus="select();">-->          
        </form>
      </td>
    </tr>
</table>



<script type="text/javascript">
//ajuste la hauteur du textarea
if ('<?=count($infJ)?>'<21) document.forms.form3.fires.rows='<?=count($infJ)-1?>';
//si le joueur est géré par un secteur, alors sélectionne un type de résumé adéquat
//et appelle une première fois la fonction majtext()
var i='<?=$infJs[1]['ht_posteAssigne']?>';
var j='0';
if (i>'5') i='0';
if ((i=='1') || (i=='2')) j=i-'1';
if (i=='3') j='4';
if (i=='4') j='6';
if (i=='5') j='7';
if (i>'0') document.forms.form1.typeresume[j].click();
</script>


<A HREF=# style=\"text-decoration:none\" onClick=\"copy2Clipboard(document.getElementById('textespan'));return(false)\">Copier cette fiche dans le presse-papier en un click (sous Internet Explorer)</A>

<div align="center"><a href="javascript:history.go(-1);">Retour</a></div>
<script type="text/javascript">
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1", {validateOn:["change"]});
</script>
    </body>
</html>
<?php  deconnect(); ?>

