
<link href="<?=$url?>/css/ht2.css" rel="stylesheet" type="text/css">

<script>
function submitNumServeurHT()
{ 
    var xhr_object = null; 
	 
    if(window.XMLHttpRequest) // Firefox 
    	   xhr_object = new XMLHttpRequest(); 
    else if(window.ActiveXObject) // Internet Explorer 
    	   xhr_object = new ActiveXObject("Microsoft.XMLHTTP"); 
    else { // XMLHttpRequest non supporté par le navigateur 
    	   alert("Votre navigateur ne supporte pas cette fonctionnalite. Merci d'utiliser IE ou Firefox."); 
    	   return; 
    } 
    
    // Valeur précisé dans le text box du formulaire
    if(document.ajax.numServeurHT.value=="")
    { 
    	   alert("Vous devez saisir le numero du serveur auquel vous etes connecte sur Hattrick"); 
    	   return; 
    } 
    else {var data="numServeurHT="+document.ajax.numServeurHT.value;}
    
    // Spécifiction du mode de transmission
  	xhr_object.open("POST", location.href, true);
    xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    
    // Message à afficher lorsque le script sera exécuté
    xhr_object.onreadystatechange = function() {
	     	if(xhr_object.readyState == 4) {alert("Numero Serveur hattrick modifie !")};
    }
    // Envoi des données
  	xhr_object.send(data);     
} 
</script>



<?php
//session_start();
if(!empty($_POST['numServeurHT'])) { 
    $_SESSION['numServeurHT']=$_POST['numServeurHT'];
}
?>

<table cellpadding="0" cellspacing="2" width="100%" border="0">
  <tr align="left" nowrap>
  	<td class="bred" nospan nowrap>
      <FORM method="POST" name="ajax" action="" style="display: inline; margin: 0;">
    	<?php if (empty($_SESSION['numServeurHT'])){$color="#FFFFFF;";} else {$color="#CCFF66;";}?>
      <font size="-3"> Num Serveur HT : <INPUT type="text" name="numServeurHT" value="<?php if (isset($_SESSION['numServeurHT'])){echo $_SESSION['numServeurHT'];}?>" style="height:16px; font-family: Verdana;font-weight: bold; font-size: 8px;background-color: <?=$color?>;" MAXLENGTH="2" size="2"> 
      <INPUT border=0 src="<?=$url?>/images/valide.png" type="image" Value="submit" ONCLICK="submitNumServeurHT()" title="Valider le numero saisi"></font>
      </FORM>
    </td>
  </tr>
</table>