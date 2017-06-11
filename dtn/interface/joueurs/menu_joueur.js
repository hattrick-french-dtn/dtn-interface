function MM_jumpMenu(targ,selObj,restore){ //v3.0
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) selObj.selectedIndex=0;
}

function init()
{
	var scrollPos = "<?=$scrollPos?>";
	document.body.scrollTop = scrollPos;
}

function fiche(id,url){
	document.location='<?=$url?>/joueurs/fiche.php?url='+url+'&id='+id
}

function chgFormule(affposition){
	if(window.document.form1.useFormule.checked == true){
		document.location = "liste.php?affPosition="+affposition+">&useFormule=1";
	}
	else document.location = "liste.php?affPosition="+affposition+"&useFormule=0";
}

// Fonction générant une alerte si un des champs du formulaire d'ajout transfert n'est pas renseigné
function testChamp1(){
	if (document.form1.htlogin.value == "" || document.form1.htseccode.value == "") {
		alert("Vous devez saisir votre login et password HT");
		return false;
	}
	else return true;
}

// Fonction générant une alerte si un des champs du formulaire d'ajout transfert n'est pas renseigné
function testListId(){
	if (document.form1.listID.value == "" ) {
		alert("Vous devez saisir les identifiants Hattrick des joueurs à ajouter dans la base");
		return false;
	}
	else return true;
}

