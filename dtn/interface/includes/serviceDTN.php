<?php

function getDTN($id){
	global $conn;	
	$sql = "SELECT * FROM ht_admin LEFT JOIN ht_position on idPosition = idPosition_fk WHERE idAdmin = $id";
	$result= $conn->query($sql);
	
	$tabS = $result->fetch();
	$result=NULL;
	
	return	$tabS;
}

function getCoeffSelectionneur($id_poste){
	global $conn;	
	global $sesUser;

	$sql = "SELECT * FROM ht_settings WHERE idAdmin = '".$sesUser["idAdmin"]."' AND id_poste = '".$id_poste."'";
	$result= $conn->query($sql);
	
	$tabS = $result->fetch();
	$result=NULL;
	
	return	$tabS;
}
?>