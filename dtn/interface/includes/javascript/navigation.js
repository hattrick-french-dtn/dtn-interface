// JavaScript Document
function chgTri(ordre, sens, masque, position,age,nb){

if(sens == "DESC") sens2 = "ASC";
if(sens == "ASC") sens2 = "DESC";
var scrollT = document.body.scrollTop;

window.location = "?ordre="+ordre+"&sens="+sens2+"&scrollPos="+scrollT+"&masque="+masque+"&affPosition="+position+"&age="+age+"&nb="+nb;


}
// JavaScript Document
function chgTriSelection(ordre, sens, masque, position,selection){


if(sens == "DESC") sens2 = "ASC";
if(sens == "ASC") sens2 = "DESC";
var scrollT = document.body.scrollTop;


window.location = "?selection="+selection+"&ordre="+ordre+"&sens="+sens2+"&scrollPos="+scrollT+"&masque="+masque+"&affPosition="+position;

}

function supprAdmin(idAdmin,pagefrom){
	conf = confirm("Etes vous sur de vouloir supprimer ce compte ?");
	if(conf == true) window.location = "../form.php?mode=supprAdmin&from="+pagefrom+"&idAdmin="+idAdmin;
}

function modifAdmin(idAdmin, pagefrom){
	window.location = 'modifAdmin.php?titre=Modifier&idAdmin='+idAdmin+'&from='+pagefrom;

}

function supprimer(id,mode){
var scrollT = document.body.scrollTop;
conf = confirm("Etes vous sur ?");

if(conf == true) window.location = "../form.php?scrollPos="+scrollT+"&mode="+mode+"&id="+id;

}

function modifier(id,mode){
var scrollT = document.body.scrollTop;

popUpModifier = window.open('modifier.php?scrollPos='+scrollT+'&titre=Modifier&id='+id+'&mode='+mode+'','popUpModifier','toolbar=0,location=0,directories=0,status=0,scrollbars=1,resizable=0,copyhistory=0,menuBar=0,width=530,height=268');

}

function fiche(id,url){

popUpFiche = window.open('fiche.php?url='+url+'&id='+id,'popUpFiche','toolbar=0,location=0,directories=0,status=1,scrollbars=0,resizable=0,copyhistory=0,menuBar=0,width=530,height=352');

}
function fichePublic(id){

popUpFiche = window.open('joueurs/fichePublic.php?id='+id,'popUpFiche','toolbar=0,location=0,directories=0,status=1,scrollbars=0,resizable=0,copyhistory=0,menuBar=0,width=530,height=352');

}
function ficheDTN(id){

popUpFicheDTN = window.open('ficheDTN.php?id='+id,'popUpFicheDTN','toolbar=0,location=0,directories=0,status=1,scrollbars=0,resizable=0,copyhistory=0,menuBar=0,width=703,height=600');

}

function verifPlayer(){

popUpAddCoach = window.open('verifPlayer.php','popUpAddCoach','toolbar=0,location=0,directories=0,status=0,scrollbars=0,resizable=0,copyhistory=0,menuBar=0,width=200,height=100');

}

function majNiveau(champ,niveauAvant,niveauNew,idJoueur)
{
switch(champ){
	case "idExperience_fk":
	var niveau = "d'experience";
	var typeCarac = 8;
	break;
	
	case "idEndurance":
	var niveau = "d'endurance";
	var typeCarac = 9;
	break;
	
	case "idPA":
	var niveau = "de coup de pieds arretes";
	var typeCarac = 7;
	break;
	
	case "idConstruction":
	var niveau = "de construction";
	var typeCarac = 1;
	break;
	
	
	case "idAilier":
	var niveau = "d'ailier";
	var typeCarac = 2;
	break;
	
	case "idButeur":
	var niveau = "de buteur";
	var typeCarac = 3;
	break;
	
	case "idGardien":
	var niveau = "de gardien";
	var typeCarac = 4;
	break;
	
	case "idPasse":
	var niveau = "de passe";
	var typeCarac = 5;
	break;
	
	case "idDefense":
	var niveau = "de defense";
	var typeCarac = 6;
	break;

}



conf = confirm("Etes vous sur de vouloir augmenter le niveau "+niveau+" du joueur ?");
if(conf == true)
{
window.location = "../form.php?mode=addNiveau&idJoueur="+idJoueur+"&champ="+champ+"&niveau="+niveauNew+"&niveauInitial="+niveauAvant+"&idTypeCarac="+typeCarac;
}

}
function majNiveau2(champ,niveauAvant,niveauNew,idJoueur)
{
switch(champ){
	case "idExperience_fk":
	var niveau = "d'experience";
	var typeCarac = 8;
	break;
	
	case "idEndurance":
	var niveau = "d'endurance";
	var typeCarac = 9;
	break;
	
	case "idPA":
	var niveau = "de coup de pieds arretes";
	var typeCarac = 7;
	break;
	
	case "idConstruction":
	var niveau = "de construction";
	var typeCarac = 1;
	break;
	
	
	case "idAilier":
	var niveau = "d'ailier";
	var typeCarac = 2;
	break;
	
	case "idButeur":
	var niveau = "de buteur";
	var typeCarac = 3;
	break;
	
	case "idGardien":
	var niveau = "de gardien";
	var typeCarac = 4;
	break;
	
	case "idPasse":
	var niveau = "de passe";
	var typeCarac = 5;
	break;
	
	case "idDefense":
	var niveau = "de defense";
	var typeCarac = 6;
	break;
	case "ageJoueur":
	var niveau = " d'age ";
	var typeCarac = 10;

	break;

}



conf = confirm("Etes vous sur de vouloir diminuer le niveau "+niveau+" du joueur ?");
if(conf == true)
{
window.location = "../form.php?mode=addNiveau&baisse=1&idJoueur="+idJoueur+"&champ="+champ+"&niveau="+niveauNew+"&niveauInitial="+niveauAvant+"&idTypeCarac="+typeCarac;
}

}


function fermer(url){
window.opener.location = url;
window.close();
}

function verifValeur(){
	if(window.document.form1.valeurEnCours.value == ""){
		alert("Vous devez preciser le TSI du joueur");
		return false;
	}
	else return true;
}

function EtatHisto(){
	if(window.document.form1.noHistory2.value == 1){
		window.document.form1.noHistory2.value = 0;;
	}
	else
	{
		window.document.form1.noHistory2.value = 1 ;;
	}
}

function suppPerf(idJoueur,idPerf){
	conf = confirm("Etes vous sur de vouloir supprimer ce match ?");
	if(conf == true) window.location = "../form.php?mode=supprMatch&idJoueur="+idJoueur+"&idPerf="+idPerf;
}

function sortirJoueur(idJoueur, nom){
	conf = confirm("Etes vous sur de vouloir sortir "+nom+" de l'équipe ?");
	if(conf == true) window.location = "../form.php?mode=sortJoueur&idJoueur="+idJoueur;
}

function Blessure(){
	if(window.document.form1.blessure.checked == true){
		window.document.form1.typeMatch.disabled = true;
		window.document.form1.postePerf.disabled = true;
		window.document.form1.ordrePerf.disabled = true;
	}
	else
	{
		window.document.form1.typeMatch.disabled = false;
		window.document.form1.postePerf.disabled = false;
		window.document.form1.ordrePerf.disabled = false;
	}
}

function verifNb(){
if(window.document.form1.nb.value > 25) {
	 alert("Merci de choisir un nombre d'enregistrement inf�rieur a 25"); return false; } else return true;
}

function f_message(login, comment, mail) {
//Compatibilié :   FireFox : Toutes versions - Mozilla : 1 et + - Netscape Navigator : 3 et+ - Internet Explorer : 3 et +

			z_mess_leText = '<b>Mail de '+login+' : '+mail+'<br></b>'
			z_mess_leText += '<p>commentaire :'+comment+'</p>'
			z_mess_leText += '<table border="0px" cellpadding="0px" cellspacing="0px" width="100%"><tr><td>'
			z_mess_leText += '</td><td align="right"><input type="button" name"valFerm" ID="Bt_6" class="typBt" style="width:60px;height:20px" onclick="f_message(0)" value="Fermer"></td></tr></table>'
			z_mess_leCadr = '<table border="1px" cellpadding="20px" cellspacing="0px" class="cad" width="480px" BGCOLOR="#C0C0C0"><tr><td>'+z_mess_leText+'</td></tr></table>'

	if (login != 0 ){
		document.getElementById("Id_info").innerHTML = z_mess_leCadr
		document.getElementById("Id_info").style.top = ((document.body.clientHeight-document.getElementById("Id_info").offsetHeight)/2)+document.body.scrollTop
		document.getElementById("Id_info").style.left = (document.body.clientWidth-document.getElementById("Id_info").offsetWidth)/2
		document.getElementById("Id_info").style.zIndex = 10
		document.getElementById("Id_info").style.cursor = 'default'
		if (navigator.userAgent.indexOf("fox") == -1){ setTimeout('document.forms.laGrill[caseSel].blur()',10) }
		posScroll = 0
	}
	else {
		document.getElementById("Id_info").innerHTML = ""
		window.status=''
	}
}

function mailDTNs(mails) {
			z_mess_leText = '<b>Listes des Mails de vos DTNs :</b>'
			z_mess_leText += '<p>'+mails+'</p>'
			z_mess_leText += '<table border="0px" cellpadding="0px" cellspacing="0px" width="100%"><tr><td>'
			z_mess_leText += '</td><td align="right"><input type="button" name"valFerm" ID="Bt_6" class="typBt" style="width:60px;height:20px" onclick="mailDTNs(0)" value="Fermer"></td></tr></table>'
			z_mess_leCadr = '<table border="1px" cellpadding="20px" cellspacing="0px" class="cad" width="480px" BGCOLOR="#C0C0C0"><tr><td>'+z_mess_leText+'</td></tr></table>'

	if (mails != 0 ){
		document.getElementById("Id_info").innerHTML = z_mess_leCadr
		document.getElementById("Id_info").style.top = ((document.body.clientHeight-document.getElementById("Id_info").offsetHeight)/2)+document.body.scrollTop
		document.getElementById("Id_info").style.left = (document.body.clientWidth-document.getElementById("Id_info").offsetWidth)/2
		document.getElementById("Id_info").style.zIndex = 10
		document.getElementById("Id_info").style.cursor = 'default'
		if (navigator.userAgent.indexOf("fox") == -1){ setTimeout('document.forms.laGrill[caseSel].blur()',10) }
		posScroll = 0
	}
	else {
		document.getElementById("Id_info").innerHTML = ""
		window.status=''
	}
}

function degriserBouton(nomBouton)
{ 
  document.getElementById(nomBouton).disabled = '';
  return; 
} 
