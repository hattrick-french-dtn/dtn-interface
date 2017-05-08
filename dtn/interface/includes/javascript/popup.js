function openPopup(url) {

document.getElementById('page_action_iframe').contentWindow.document.body.innerHTML='Chargement en cours...';
document.getElementById("page_action").style.visibility = "visible";

// ici, l'iframe prend tout la place disponible, mais il est aussi pssible de definir une largeur et une hauteur finie
document.getElementById("page_action_iframe").style.width = "100%";
document.getElementById("page_action_iframe").style.height = "100%";
document.getElementById("page_action_iframe").style.top = ((document.body.clientHeight-document.getElementById("page_action_iframe").offsetHeight)/2)+document.body.scrollTop
document.getElementById("page_action_iframe").style.left = (document.body.clientWidth-document.getElementById("page_action_iframe").offsetWidth)/2
		
frames['page_action_iframe'].location.href= url ;

}

function hidePopup() {
document.getElementById("page_action").style.visibility = "hidden";
document.getElementById("page_action_iframe").style.width = "1px";
document.getElementById("page_action_iframe").style.height = "1px";
}
