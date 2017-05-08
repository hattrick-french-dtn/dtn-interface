<?php 
require("../includes/head.inc.php");
require("../includes/serviceListesDiverses.php");

if(!$sesUser["idAdmin"])
	{
	header("location: index.php?ErrorMsg=Session Expir�");
	}
if(!isset($ordre)) $ordre = "nomJoueur";
if(!isset($sens)) $sens = "ASC";
if(!isset($lang)) $lang = "FR";
if(!isset($masque)) $masque = 0;
if(!isset($affPosition)) $affPosition = 0;

require("../includes/langue.inc.php");

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





?><title>Aide</title>
<body >
<br>
<br>



<center><h3>Contacts</h3></center>
<b>J'ai besoin d'aide, &agrave; qui m'adresser?</b>
<p>

<ul>
<li>le forum de ht-fff :
<a href="http://www.ht-fff.org/dtn/forum/">http://www.ht-fff.org/dtn/forum/</a><br>
<br>
<li>l'outil de gestion de bug :
<a href="http://www.ht-fff.org/bug/">http://www.ht-fff.org/bug/</a><br>


<!-------------------------------------            DTN+                      -->
<br>
<li>Votre DTN+<br>
<ul>
<?php
    $sql = "SELECT * FROM $tbl_admin A, ht_position P ";
    $sql = $sql ."WHERE A.idPosition_fk = P.idPosition AND A.idNiveauAcces_fk=2 AND A.affAdmin=1 AND A.idPosition_fk != 8 ORDER BY A.idPosition_fk";  	 
	  $result= mysql_query($sql) or die("Erreur : ".$sql);;
	  
		while($lst = mysql_fetch_array($result)){
			
		?>
		<li>[<?=$lst["intitulePosition"]?>] : <?=$lst["loginAdmin"]?> / <?=$lst["emailAdmin"]?> 
	  <?php
	  }


	mysql_free_result($result);

	  ?>
</ul>
<!----------------------------------------------------------------------------->


<!-------------------------------------            Admin                     -->
<br>
<li>Un administrateur<br>
<ul>
<?php
		$sql = "SELECT * FROM $tbl_admin WHERE idNiveauAcces_fk=1 and affAdmin=1 ORDER BY loginAdmin";
	  $result= mysql_query($sql) or die("Erreur : ".$sql);;
	  
		while($lst = mysql_fetch_array($result)){
			
		?>
		<li><?=$lst["loginAdmin"]?> / <?=$lst["emailAdmin"]?> 
	  <?php
	  }


	mysql_free_result($result);

	  ?>
</ul>
<!----------------------------------------------------------------------------->


<!-------------------------------------            Sélectionneur             -->
<br>
<li>Un s&eacute;lectionneur<br>
<ul>
<?php
		$sql = "SELECT * FROM $tbl_admin A, ht_coach C WHERE A.loginAdmin=C.loginAdmin AND C.affCoach=1 ORDER BY C.loginAdmin";
	  $result= mysql_query($sql) or die("Erreur : ".$sql);;
	  
		while($lst = mysql_fetch_array($result)){
			
		?>
		<li>[<?=$lst["selection"]?>] : <?=$lst["loginCoach"]?> / <?=$lst["emailCoach"]?> 
	  <?php
	  }


	mysql_free_result($result);

	  ?>
</ul>
<!----------------------------------------------------------------------------->


 </ul>


<b>Codifications </b><p>

25 Jul 2005 : modification des sp&eacute;cialit&eacute;s. <br>
<ul>
<li>0 = "Aucune" / "-"
<li>1 = "Technique" / "T"
<li>2 = "Rapide" / "R"
<li>3 = "Costaud" / "C"
<li>4 = "Imprevisible" / "I"
<li>5 = "Joueur de t&ecirc;te" / "J"
</ul>

</body>
</html>
>>>>