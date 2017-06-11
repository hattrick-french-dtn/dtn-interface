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
