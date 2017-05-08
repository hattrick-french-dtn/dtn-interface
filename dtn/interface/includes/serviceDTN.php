<?php

function getDTN($id){
		
		$sql = "SELECT * FROM ht_admin LEFT JOIN ht_position on idPosition = idPosition_fk WHERE idAdmin = $id";
		$result= mysql_query($sql);
		
		$tabS = mysql_fetch_array($result);
		mysql_free_result($result);
		
		return	$tabS;
}

function getCoeffSelectionneur($id_poste){

global $sesUser;

$sql = "SELECT * FROM ht_settings WHERE idAdmin = '".$sesUser["idAdmin"]."' AND id_poste = '".$id_poste."'";
		$result= mysql_query($sql);
		
		$tabS = mysql_fetch_array($result);
		mysql_free_result($result);
		
		return	$tabS;

}
?>