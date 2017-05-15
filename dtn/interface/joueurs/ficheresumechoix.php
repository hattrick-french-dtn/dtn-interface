<?php
require("../includes/head.inc.php");

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

if ($origine=="unique") //appel provient de la fiche d'un joueur
  {
  if (isset($htid))
  {
  	$infJ[1] = getJoueurHt($htid);
  	$id = $infJ[1]["idJoueur"];
  }
  else
  	$infJ[1] = getJoueur($id);
  }
else  //appel provient de ficherecupchoix.php
  {
  $tlistID = split(";",$listID);  //extraire les différents id
  for($i=0;$i<count($tlistID);$i++)
    {
    $infJ[$i+1] = getJoueurHt($tlistID[$i]); //reconstruit un tableau contenant toutes les données des joueurs sélectionnés
    }
  }

$infJ[0]=$infJ[1];
if ($origine!="unique")
  {
  $infJ[0][nomJoueur]="Résumé Multijoueurs";
  $infJ[0][prenomJoueur]=""; //au cas où !!!
  }  

	
//Recherche le libellé du type d'entrainement
//Fireproofed le 28/01/2011
$sql="SELECT libelle_type_entrainement FROM ht_type_entrainement WHERE id_type_entrainement = '".$infJ[1]['entrainement_id']."' ";
$req = mysql_query($sql);
$result=mysql_fetch_array($req);
mysql_free_result($req);
$infJ[1]['entrainement_type']=$result['libelle_type_entrainement'];
if ($infJ[1]['entrainement_type']=='') $infJ[1]['entrainement_type']='non renseigné';


?><html>
<head>
<title>Fiche <?=$infJ[0]["nomJoueur"]?> <?=$infJ[0]["prenomJoueur"]?></title>
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
	$infJ[0]=$infJ[1];
  //âge du joueur
	for  ($k=1;$k<count($infJ);$k++)
	  {
    $ageetjours = ageetjour($infJ[$k]["datenaiss"]);
  	$tabage[$k] = explode(" - ",$ageetjours);
    //calcul du nombre de jours depuis la dernière modification
    $datemaj = explode("-",$infJ[$k]["date_modif_effectif"]);
    $jdatemaj = mktime(0,0,0,$datemaj[1],$datemaj[2],$datemaj[0]);
    $jdateauj = mktime(0,0,0,date("m"),date("d"),date("Y"));
    $i=ceil(($jdateauj-$jdatemaj)/3600/24);
    $dermod[$k]=$i." jour";
    if ($i>1) $dermod[$k]=$dermod[$k]."s";
    $dermod[$k]=$dermod[$k].' ['.date("d/m/Y",mktime(0,0,0,$datemaj[1],$datemaj[2],$datemaj[0])).']';
    //calcul de l'âge du joueur en jours pour situation par rapport à la CDM
  	$jourj0 = ($sesUser["dateSemaine0"]+3600-574729200)/3600/24;
   	$jourjoueur[$k] = $jourj0 - $infJ[$k]["datenaiss"];
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
  for ($i=1;$i<count($infJ);$i++)
    {
    $infJ[0]=$infJ[$i];
    $jourjoueur[0]=$jourjoueur[$i];
    $tabage[0][0]=$tabage[$i][0];
    $tabage[0][1]=$tabage[$i][1];
    $dermod[0]=$dermod[$i];
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
  		if ('<?=$jourjoueur[0]?>' > 2067) {
  			if ('<?=$jourjoueur[0]?>' < 2129)
  				a= '[color=blue]';
  			else if ('<?=$jourjoueur[0]?>' < 2178)
  				a= '[color=red]';
  			else if ('<?=$jourjoueur[0]?>' < 2292)
  				a= '[color=green]';
  			else if ('<?=$jourjoueur[0]?>' < 2239)
  				a= '[color=green]';
  			else if ('<?=$jourjoueur[0]?>' < 2255)
  				a= '[color=orange]';
  			else if ('<?=$jourjoueur[0]?>' < 2290)
  				a= '[color=violet]';
  		}
  	}
  	else {
  		if ('<?=$jourjoueur[0]?>' < 2127)
  			a= '[color=green]';
  		else if ('<?=$jourjoueur[0]?>' < 2143)
  			a= '[color=orange]';
  		else if ('<?=$jourjoueur[0]?>' < 2178)
  			a= '[color=violet]';
  		else if ('<?=$jourjoueur[0]?>' < 2241)
  			a= '[color=blue]';
  		else if ('<?=$jourjoueur[0]?>' < 2290)
  			a= '[color=red]';
  	}
  	if (a!='') couleur='1';
  
    if ('<?=$infJ[0]["niv_Entraineur"]?>'<7) a=a+' :evil: ';
    if ('<?=$infJ[0]["niv_Entraineur"]?>'==7) a=a+' :arrow: ';
    if ('<?=$infJ[0]["niv_Entraineur"]?>'==8) a=a+' :D ';
    
    a=a+'[b]';
    a=a+'<?=ucwords(strtolower($infJ[0]["nomJoueur"]))?>';
    a=a+'<?=ucwords(strtolower($infJ[0]["prenomJoueur"]))?>';
    a=a+' (<?=strtolower($infJ[0]["idHattrickJoueur"])?>)';
    a=a+'[/b]'; 
  	a=a+' <?=$tabage[0][0]?> ans et <?=$tabage[0][1]?> jour';
  	if ('<?=$tabage[0][1]?>'>1) a=a+'s';
  	a=a+' ';
  
    //modification sur toutes les fiches pour l'ajout du CF par jojoje86 le 21/07/09
    //détermination du choix de l'utilisateur : on regarde le type de résumé choisi par bouton radio
    //Fireproofed le 28/01/2011
    
    if (document.forms.form1.typeresume[0].checked) {	
      //choix=GK
      b='(G/D'; 
      if ('<?=$infJ[0]["entrainement_id"]?>'==10) c='[b][i]';
      c=c+'<?=$infJ[0]["idGardien"]?>+<?=$infJ[0]["nbSemaineGardien"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==10) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJ[0]["entrainement_id"]?>'==4) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idDefense"]?>+<?=$infJ[0]["nbSemaineDefense"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==4) c=c+'[/i][/b]';
      }
  
    if (document.forms.form1.typeresume[1].checked) {
      //choix=CD
      b='(D/P/C/A';
      if ('<?=$infJ[0]["entrainement_id"]?>'==4) c='[b][i]';
      c=c+'<?=$infJ[0]["idDefense"]?>+<?=$infJ[0]["nbSemaineDefense"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==4) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJ[0]["entrainement_id"]?>'==8) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idPasse"]?>+<?=$infJ[0]["nbSemainePasses"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==8) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJ[0]["entrainement_id"]?>'==9) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idConstruction"]?>+<?=$infJ[0]["nbSemaineConstruction"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==9) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJ[0]["entrainement_id"]?>'==6) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idAilier"]?>+<?=$infJ[0]["nbSemaineAilier"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==6) c=c+'[/i][/b]'; 
      }
  
    if (document.forms.form1.typeresume[2].checked) {
      //choix=CDoff
      b='(C/D/P';
      if ('<?=$infJ[0]["entrainement_id"]?>'==9) c='[b][i]';
      c=c+'<?=$infJ[0]["idConstruction"]?>+<?=$infJ[0]["nbSemaineConstruction"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==9) c=c+'[/i][/b]';
      c=c+' / '; 
  		if ('<?=$infJ[0]["entrainement_id"]?>'==4) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idDefense"]?>+<?=$infJ[0]["nbSemaineDefense"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==4) c=c+'[/i][/b]';
      c=c+' / '; 
  		if ('<?=$infJ[0]["entrainement_id"]?>'==8) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idPasse"]?>+<?=$infJ[0]["nbSemainePasses"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==8) c=c+'[/i][/b]'; 
      }
    
    if (document.forms.form1.typeresume[3].checked) {
      //choix=WB
      b='(A/D/P/C';
      if ('<?=$infJ[0]["entrainement_id"]?>'==6) c='[b][i]';
      c=c+'<?=$infJ[0]["idAilier"]?>+<?=$infJ[0]["nbSemaineAilier"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==6) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJ[0]["entrainement_id"]?>'==4) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idDefense"]?>+<?=$infJ[0]["nbSemaineDefense"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==4) c=c+'[/i][/b]';
      c=c+' / ';
      if ('<?=$infJ[0]["entrainement_id"]?>'==8) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idPasse"]?>+<?=$infJ[0]["nbSemainePasses"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==8) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJ[0]["entrainement_id"]?>'==9) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idConstruction"]?>+<?=$infJ[0]["nbSemaineConstruction"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==9) c=c+'[/i][/b]';
      }
  
    if (document.forms.form1.typeresume[4].checked) {
      //choix=WG
      b='(A/P/C/D';
      if ('<?=$infJ[0]["entrainement_id"]?>'==6) c='[b][i]';
      c=c+'<?=$infJ[0]["idAilier"]?>+<?=$infJ[0]["nbSemaineAilier"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==6) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJ[0]["entrainement_id"]?>'==8) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idPasse"]?>+<?=$infJ[0]["nbSemainePasses"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==8) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJ[0]["entrainement_id"]?>'==9) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idConstruction"]?>+<?=$infJ[0]["nbSemaineConstruction"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==9) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJ[0]["entrainement_id"]?>'==4) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idDefense"]?>+<?=$infJ[0]["nbSemaineDefense"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==4) c=c+'[/i][/b]';
      }
      
    if (document.forms.form1.typeresume[5].checked) {
      //choix=WGpm
      b='(A/C/P';
      if ('<?=$infJ[0]["entrainement_id"]?>'==6) c='[b][i]';
      c=c+'<?=$infJ[0]["idAilier"]?>+<?=$infJ[0]["nbSemaineAilier"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==6) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJ[0]["entrainement_id"]?>'==9) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idConstruction"]?>+<?=$infJ[0]["nbSemaineConstruction"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==9) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJ[0]["entrainement_id"]?>'==8) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idPasse"]?>+<?=$infJ[0]["nbSemainePasses"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==8) c=c+'[/i][/b]';
      }
    
    if (document.forms.form1.typeresume[6].checked) {
      //choix=IM
      b='(E/C/P/D';
      c='<?=$infJ[0]["idEndurance"]?> / ';
      if ('<?=$infJ[0]["entrainement_id"]?>'==9) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idConstruction"]?>+<?=$infJ[0]["nbSemaineConstruction"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==9) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJ[0]["entrainement_id"]?>'==8) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idPasse"]?>+<?=$infJ[0]["nbSemainePasses"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==8) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJ[0]["entrainement_id"]?>'==4) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idDefense"]?>+<?=$infJ[0]["nbSemaineDefense"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==4) c=c+'[/i][/b]';
      }
  
    if (document.forms.form1.typeresume[7].checked) {
      //choix=FW
      b='(B/P/A';
      if ('<?=$infJ[0]["entrainement_id"]?>'==5) c='[b][i]';
      c=c+'<?=$infJ[0]["idButeur"]?>+<?=$infJ[0]["nbSemaineButeur"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==5) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJ[0]["entrainement_id"]?>'==8) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idPasse"]?>+<?=$infJ[0]["nbSemainePasses"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==8) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJ[0]["entrainement_id"]?>'==6) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idAilier"]?>+<?=$infJ[0]["nbSemaineAilier"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==6) c=c+'[/i][/b]';
      }
  
    if (document.forms.form1.typeresume[8].checked) {
      //choix=FWtw
      b='(B/A/P';
      if ('<?=$infJ[0]["entrainement_id"]?>'==5) c='[b][i]';
      c=c+'<?=$infJ[0]["idButeur"]?>+<?=$infJ[0]["nbSemaineButeur"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==5) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJ[0]["entrainement_id"]?>'==6) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idAilier"]?>+<?=$infJ[0]["nbSemaineAilier"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==6) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJ[0]["entrainement_id"]?>'==8) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idPasse"]?>+<?=$infJ[0]["nbSemainePasses"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==8) c=c+'[/i][/b]';
      }
  
    if (document.forms.form1.typeresume[9].checked) {
      //choix=FWdef
      b='(C/P/B/A';
      if ('<?=$infJ[0]["entrainement_id"]?>'==9) c='[b][i]';
      c=c+'<?=$infJ[0]["idConstruction"]?>+<?=$infJ[0]["nbSemaineConstruction"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==9) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJ[0]["entrainement_id"]?>'==8) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idPasse"]?>+<?=$infJ[0]["nbSemainePasses"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==8) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJ[0]["entrainement_id"]?>'==5) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idButeur"]?>+<?=$infJ[0]["nbSemaineButeur"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==5) c=c+'[/i][/b]';
      c=c+' / '; 
      if ('<?=$infJ[0]["entrainement_id"]?>'==6) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idAilier"]?>+<?=$infJ[0]["nbSemaineAilier"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==6) c=c+'[/i][/b]';
      }
  
    //on regarde si des paramètres supplémentaires doivent être ajoutés à la fiche
    //avec test pour éviter les redondances
    //Fireproofed le 28/01/2011
    if ((document.forms.form2.parasup[0].checked)&&(b.indexOf('G')==-1)) {
      //choix=GK
      b=b+'/G';
      c=c+' / ';
      if ('<?=$infJ[0]["entrainement_id"]?>'==10) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idGardien"]?>+<?=$infJ[0]["nbSemaineGardien"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==10) c=c+'[/i][/b]';
      }
  
    if ((document.forms.form2.parasup[1].checked)&&(b.indexOf('D')==-1)) {
      //choix=Défense
      b=b+'/D';
      c=c+' / ';
      if ('<?=$infJ[0]["entrainement_id"]?>'==4) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idDefense"]?>+<?=$infJ[0]["nbSemaineDefense"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==4) c=c+'[/i][/b]';
      }
  
    if ((document.forms.form2.parasup[2].checked)&&(b.indexOf('C')==-1)) {
      //choix=Construction
      b=b+'/C';
      c=c+' / ';
      if ('<?=$infJ[0]["entrainement_id"]?>'==9) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idConstruction"]?>+<?=$infJ[0]["nbSemaineConstruction"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==9) c=c+'[/i][/b]';
      }
  
    if ((document.forms.form2.parasup[3].checked)&&(b.indexOf('A')==-1)) {
      //choix=Ailier
      b=b+'/A';
      c=c+' / ';
      if ('<?=$infJ[0]["entrainement_id"]?>'==6) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idAilier"]?>+<?=$infJ[0]["nbSemaineAilier"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==6) c=c+'[/i][/b]';
      }
  
    if ((document.forms.form2.parasup[4].checked)&&(b.indexOf('P')==-1)) {
      //choix=Passe
      b=b+'/P';
      c=c+' / ';
      if ('<?=$infJ[0]["entrainement_id"]?>'==8) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idPasse"]?>+<?=$infJ[0]["nbSemainePasses"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==8) c=c+'[/i][/b]';
      }
  
    if ((document.forms.form2.parasup[5].checked)&&(b.indexOf('B')==-1)) {
      //choix=Buteur
      b=b+'/B';
      c=c+' / ';
      if ('<?=$infJ[0]["entrainement_id"]?>'==5) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idButeur"]?>+<?=$infJ[0]["nbSemaineButeur"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==5) c=c+'[/i][/b]';
      }
  
    if ((document.forms.form2.parasup[6].checked)||(document.forms.form1.typeresume[0].checked)) {
      //choix=CF : case à cocher CF ou choix de type de résumé = GK
      b=b+'/CF';
      c=c+' / ';
      if ('<?=$infJ[0]["entrainement_id"]?>'==3) c=c+'[b][i]';
      c=c+'<?=$infJ[0]["idPA"]?>';
      if ('<?=$infJ[0]["entrainement_id"]?>'==3) c=c+'[/i][/b]';
      }
  
    if ((document.forms.form2.parasup[7].checked)&&(b.indexOf('E')==-1)) {
      //choix=Endurance
      b=b+'/E';
      c=c+' / <?=$infJ[0]["idEndurance"]?>';    
      }
      
      
    b=b+') ';
    
    //Rajouter l'XP
    c=c+' / Xp <?=$infJ[0]["idExperience_fk"]?>';
    
    //Rajouter le TDC si honorable
    if ('<?=$infJ[0]["idLeader_fk"]?>'==7) c=c+' / [b]TDC 7[/b]';
    
    //Rajouter la spé. si il y en a une
    if ('<?=$infJ[0]["optionJoueur"]?>'!=0) c=c+' / '+'[i]<?=$option[$infJ[0]["optionJoueur"]]["FR"]?>[/i]';
    
    //Rajouter la date de dernière modification
    c=c+' / Dern. Modif : <?=$dermod[0]?>';
    
    //Rajouter le nom du DTN en charge du joueur
    if (document.forms.form2.parasup[8].checked) {
      if ('<?=$infJ[0]["dtnSuiviJoueur_fk"]?>'!=0) {
        c=c+' / Suivi:<?=$infJ[0]["loginAdminSuiveur"]?>';    
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

$idClubHT=$infJ[1]['teamid'];
$idHT=$infJ[1]['idHattrickJoueur'];
$lstPos = listPositionResume();
$lstSect = listAllPosition();
$listID = $idHT;

$pos=$lstSect[$infJ[1]['ht_posteAssigne']-1]["descriptifPosition"];
if ($pos=="") $pos="aucun";

$dtnsuivi="aucun";
if ($infJ[1]["dtnSuiviJoueur_fk"]!=0) $dtnsuivi=$infJ[1]["loginAdminSuiveur"];    

if ($origine=="unique") require("../menu/menuJoueur.php");
?>

<table width="99%" border="1" cellspacing="0" cellpadding="0">
    <tr>
      <?php
      if ($origine=="unique")
        {
      ?>
        <td width="100%" colspan="2" bgcolor="#000000"><div align="center"><b><font color="#FFFFFF">Fiche R&eacute;sum&eacute; : <?=$infJ[1]['nomJoueur']?>&nbsp;<?=$infJ[1]['prenomJoueur']?>&nbsp;-&nbsp;<?=$listID?>&nbsp;-&nbsp;Entrainement : <?=$infJ[1]['entrainement_type']?>&nbsp;-&nbsp;Secteur : <?=$pos?>&nbsp;-&nbsp;DTN : <?=$dtnsuivi?>
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
            <tr><td colspan="3" align="center">Choisissez le type de résumé souhaité :<br><br></td></tr>
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
            <tr><td colspan="3" align="center">Choisissez les paramètres supplémentaires :<br><br></td></tr>
            <tr>
              <td width="33%"><input name="parasup" type="Checkbox" onClick="choixcompl();" value="gk">Gardien<br></td>
              <td width="33%"><input name="parasup" type="Checkbox" onClick="choixcompl();" value="de">Défense<br></td>
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
      <td width="100%" colspan="2" bgcolor="#000000"><div align="center"><b><font color="#FFFFFF">Résultat de la requête 
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
var i='<?=$infJ[1]['ht_posteAssigne']?>';
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
<!--
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1", {validateOn:["change"]});
//-->
</script>
    </body>
</html>
<?php  deconnect(); ?>

