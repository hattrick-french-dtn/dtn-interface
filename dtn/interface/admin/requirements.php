<?php
// Affiche toutes les erreurs
error_reporting(E_ALL);

require_once "../_config/CstGlobals.php"; // fonctions d'admin
require_once "../fonctions/AccesBase.php"; // fonction de connexion � la base
require_once "../fonctions/AdminDtn.php"; // fonctions d'admin
require_once("../includes/head.inc.php");
require_once("../includes/serviceListesDiverses.php");

$maBase = initBD();

if(!$sesUser["idAdmin"])
{
    header("location: ../index.php?ErrorMsg=Session_Expire");
}

?>
<link href="../css/ht.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../includes/javascript/navigation.js"></script>
<?php
switch($sesUser["idNiveauAcces"]){
case "1":
	require("../menu/menuAdmin.php");
	require("../menu/menuAdminGestion.php");
	break;

case "2":
	require("../menu/menuSuperviseur.php");
	require("../menu/menuSuperviseurGestion.php");
	break;

case "3":
	require("../menu/menuDTN.php");
	require("../menu/menuDTNGestion.php");
	break;

case "4":
	require("../menu/menuCoach.php");
	require("../menu/menuCoachGestion.php");
	break;

default;
	break;
}
$lstPosition = listPosition();
$lstCarac=listTypeCarac();
$lstLevel=listCarac("ASC",23);

if (isset($_POST['action']))
	$action = $_POST['action'];
elseif (isset($_GET['action'])) 
	$action = $_GET['action'];
?>
<title>Minima</title>
<body>

<?php
if (isset ($action)){
//  ***************** Suppression  de profil de joueurs acceptes dans la base	
	if($action=="suppr"){
		if(isset ($age) && isset ($position_id) && isset ($carac_1) && isset($carac_2) && isset ($week)){
			$sqld = "delete from ht_requirements where age='".$age."' and position_id='".$position_id."' and ".
					" carac_1='".$carac_1."' and  carac_2='".$carac_2."' and week='".$week."' limit 1";
			$data = $maBase->delete($sqld);
		}else{
		?>
			op&eacute;ration supprimer impossible.
			
			<?php
			return;
		}
		
	}
//  ***************** Sauvegarde d'une modification de profil	
	if($action=="savemodif"){
		if (isset ($age) && isset($position_id) && isset ($carac_1) && isset ($week) && isset ($level_1)   && isset($carac_2) && isset ($level_2)  ){
			$sqld = "update ht_requirements set level_1='".$level_1."', level_2='".$level_2."' where age='".$age."' and position_id='".$position_id."' and ".
					" carac_1='".$carac_1."' and  carac_2='".$carac_2."'  and week='".$week."'  limit 1";
			$data = $maBase->update($sqld);
			
		}else{
		?>
			op&eacute;ration sauvegarde des modifications impossible.
		<?php
			return;
		}
	}
//  ***************** Sauvegarde d'une modification de profil	
	if($action=="ajoutprofil"){
		if (isset ($age) && isset($position_id) && isset ($carac_1) && isset ($week) && isset ($level_1)   && isset($carac_2) && isset ($level_2)  ){
			$sqld = "insert into  ht_requirements ".
					"( `age` , `position_id` , `week` , `carac_1` , `level_1` , `carac_2` , `level_2` ) ".
					" VALUES ('".$age."','".$position_id."','".$week."','".$carac_1."','".$level_1."','".$carac_2."','".$level_2."') ";

			$data = $maBase->insert($sqld);
			
		}else{
		?>
			op&eacute;ration ajout impossible.
		<?php
			return;
		}
	}

//  ***************** Modification de profil 
	if($action=="modif"){
		if (isset ($age) && isset($position_id) && isset ($carac_1) && isset ($week) && isset ($level_1)   && isset($carac_2) && isset ($level_2)  ){
?>
	<form name="formModifReq" method="post" action="requirements.php" >	
	<ul>
		<input type="hidden" name="action" value="savemodif">
		<input type="hidden" name="age" value="<?=$age?>">
		<input type="hidden" name="position_id" value="<?=$position_id?>">
		<input type="hidden" name="week" value="<?=$week?>">
		<input type="hidden" name="carac_1" value="<?=$carac_1?>">
	<li> Age :<?=$age?>
	<li> Position : <?=$lstPosition[$position_id]["descriptifPosition"]?>
	<li> Semaine : <?=$week?>
	<P>
	<li> Carac 1 : <?=$lstCarac[($carac_1)-1]["nomTypeCarac"]?>
	<li> niveau en Carac 1 <select name="level_1" size="1" >
    <?php
		for($i=0;$i<count($lstLevel);$i++)
		{
			if($lstLevel[$i]["idCarac"] == $level_1) $etat = "selected"; else $etat = "";
			echo "<option value=".$lstLevel[$i]["idCarac"]." $etat>".$lstLevel[$i]["idCarac"]." - ".$lstLevel[$i]["intituleCaracFR"]."|".$lstLevel[$i]["intituleCaracUK"]."</option>";    
		}
	?>
        </select>
<?php if   ($carac_2==-1){?>
	    <input type="hidden" name="carac_2" value="-1">
	    <input type="hidden" name="level_2" value="0">
<?php }else{?>
		<P>	
		<input type="hidden" name="carac_2" value="<?=$carac_2?>">
		<li> Carac 2 : <?=$lstCarac[($carac_2)-1]["nomTypeCarac"]?>
		<li> niveau en Carac 2 <select name="level_2" size="1" >
        <?php
			for($i=0;$i<count($lstLevel);$i++)
			{
				if($lstLevel[$i]["idCarac"] == $level_2) $etat = "selected"; else $etat = "";
				echo "<option value=".$lstLevel[$i]["idCarac"]." $etat>".$lstLevel[$i]["idCarac"]." - ".$lstLevel[$i]["intituleCaracFR"]."|".$lstLevel[$i]["intituleCaracUK"]."</option>";    
			}
		?>
		</select>
<?php } ?>
</ul>		
<input type="submit" name="Submit" value="Modifier">
</form>
	<?php
		return;
	}else{
	?>
		Op&eacute;ration modifier impossible .<br>
	</html>
	<?php
		return;
	}
}

//  ***************** Ajout de profil 
	if($action=="ajouter"){
?>
<form name="formAjoutReq" method="post" action="requirements.php" >
<ul>
	<input type="hidden" name="action" value="ajoutprofil">
	<li>Age :	<select name="age" size="1" >
		<option value="17">17</option>
		<option value="18">18</option>
		<option value="19">19</option>
		<option value="20">20</option>
		<option value="21">21</option>
		<option value="99">22 et plus</option>
	</select>
	<li>Poste :	
	<select name="position_id" size="1" >
		<option value="1">Gardien</option>
		<option value="2">D&eacute;fenseur</option>
		<option value="3">Ailier</option>
		<option value="4">Milieu</option>
		<option value="5">Attaquant</option>
	</select>
	<li>Semaine :	
	<select name="week" size="1" >
<?php
	for($z=0;$z<16;$z++)
	{
	?><option value="<?=$z?>" ><?=$z?></option>
<?php } ?>					
	</select>
	<li> Carac 1 :
	<select name="carac_1" size="1" >
<?php for($z=1;$z<=9;$z++)
	{
   	if ($z==7) $z=9;
	?><option value="<?=$z?>" ><?=$lstCarac[$z-1]["nomTypeCarac"]?></option>
<?php } ?>					
	</select>
	<li> niveau en Carac 1 <select name="level_1" size="1" >
<?php
	for($i=0;$i<count($lstLevel);$i++)
	{
		echo "<option value=".$lstLevel[$i]["idCarac"]." >".$lstLevel[$i]["idCarac"]." - ".$lstLevel[$i]["intituleCaracFR"]."|".$lstLevel[$i]["intituleCaracUK"]."</option>";    
	}
?>
    </select>

	<li> Carac 2 :
	<select name="carac_2" size="1" >
	<option value="-1" >-</option>
<?php for($z=1;$z<=9;$z++)
	{
   	if ($z==7) $z=9;
	?><option value="<?=$z?>" ><?=$lstCarac[$z-1]["nomTypeCarac"]?></option>
<?php } ?>					
	</select>
	<li> niveau en Carac 2 <select name="level_2" size="1" >
<?php
	for($i=0;$i<count($lstLevel);$i++)
	{
		echo "<option value=".$lstLevel[$i]["idCarac"]." >".$lstLevel[$i]["idCarac"]." - ".$lstLevel[$i]["intituleCaracFR"]."|".$lstLevel[$i]["intituleCaracUK"]."</option>";    
	}
?>
    </select>
</ul>
<input type="submit" name="Submit" value="Ajouter">
</form>
	
<?php
		return;
	}
}

if (!isset ($showposition)){
	$showposition=1;
}
	$sql = "select * from ht_requirements where position_id='".$showposition."' order by position_id,age,week ";
	$data = $maBase->select($sql);
?>
<p>
<a href="requirements.php?action=ajouter" class="smliensorange"> Ajouter un profil </a> | 

<?php if ($showposition==1){ ?>
	Gardien | 
	<?php }else{?>
<a href="requirements.php?showposition=1" class="smliensorange">Gardien</a> | 
	<?php } ?>
<?php if ($showposition==2){ ?>
	D&eacute;fenseur | 
	<?php }else{?>
<a href="requirements.php?showposition=2" class="smliensorange">D&eacute;fenseur</a> | 
	<?php } ?>
<?php if ($showposition==3){ ?>
	Ailier | 
	<?php }else{?>
<a href="requirements.php?showposition=3" class="smliensorange">Ailier</a> | 
	<?php } ?>
<?php if ($showposition==4){ ?>
	Milieu | 
	<?php }else{?>
<a href="requirements.php?showposition=4" class="smliensorange">Milieu</a> | 
	<?php } ?>
<?php if ($showposition==5){ ?>
	Attaquant
	<?php }else{?>
<a href="requirements.php?showposition=5" class="smliensorange">Attaquant</a>
	<?php } ?>

<p>

<table border=1>
<tr><td> Age </td><td> Position </td><td>Semaine</td><td> carac 1</td><td>Niveau 1</td><td>carac 2</td><td>Niveau 2</td><td>Modifier/Supprimer</td></tr>
<?php
$nb=count($data);
$idx=0;
while($idx < $nb){
	$l=$data[$idx++];	
	
?>
<tr>
<td><?=$l["age"]?> </td><td> <?=$lstPosition[$l["position_id"]-1]["descriptifPosition"]?></td><td><?=$l["week"]?></td>

<td><?=$lstCarac[$l["carac_1"]-1]["nomTypeCarac"]?></td><td><?=$lstLevel[$l["level_1"]]["intituleCaracFR"]?> </td>
<?php if ($l["carac_2"]==-1 ){ ?>
	<td>-</td><td>-</td>
<?php	
}else{
?>
	<td><?=$lstCarac[$l["carac_2"]-1]["nomTypeCarac"]?></td><td><?=$lstLevel[$l["level_2"]]["intituleCaracFR"]?> </td>
<?php } ?>
<td><a href="requirements.php?action=modif&age=<?=$l["age"]?>&position_id=<?=$l["position_id"]?>&carac_1=<?=$l["carac_1"]?>&carac_2=<?=$l["carac_2"]?>&week=<?=$l["week"]?>&level_1=<?=$l["level_1"]?>&level_2=<?=$l["level_2"]?>" class="btn">modifier</a> / 
<a href="requirements.php?action=suppr&age=<?=$l["age"]?>&position_id=<?=$l["position_id"]?>&carac_1=<?=$l["carac_1"]?>&carac_2=<?=$l["carac_2"]?>&week=<?=$l["week"]?>"  class="btn">supprimer</a></td>
</tr>

<?php }?>
</table>

</body>
</html>