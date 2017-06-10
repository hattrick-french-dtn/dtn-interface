<?php
		ini_set("include_path", $DOCUMENT_ROOT . "/dtn/interface/fonctions" . PATH_SEPARATOR . ini_get("include_path"));
        require_once "../fonctions/phpxml.php"; // XML to Tree converter
        require_once "../fonctions/HTML/Table.php"; // fcontions pour faire des tables HTML
        


function majPays($maBase,$HTCli) {
// recupere info pays


	unset($xml);
	$xml = $HTCli->GetWorldDetails();	
	$tree = GetXMLTree($xml);


//	pour debug
//	print $xml;
//	print_r ($tree);
	$numberofleagues=count($tree['HATTRICKDATA'][0]['LEAGUELIST'][0]['LEAGUE']);
//	echo $numberofleagues;
	// insertion ligne blanche au début pour l'affichage
	for ($j = 0; $j <= 4; $pays[-1][$j]="", $j++) ;
	
	// recup des données dans un tableau à partir du xml
	for ($leagueid=0 ; $leagueid<$numberofleagues; $leagueid++) {
		
		$pays[$leagueid][0]=$tree['HATTRICKDATA'][0]['LEAGUELIST'][0]['LEAGUE'][$leagueid]['LEAGUEID'][0]['VALUE'];
		$pays[$leagueid][1]=$tree['HATTRICKDATA'] [0] ['LEAGUELIST'] [0] ['LEAGUE'] [$leagueid] ['LEAGUENAME'] [0] ['VALUE'];
		$pays[$leagueid][2]=$englishleaguename=$tree['HATTRICKDATA'] [0] ['LEAGUELIST'] [0] ['LEAGUE'] [$leagueid] ['ENGLISHNAME'] [0] ['VALUE'];
		
		// Rustine pour pays commençant par un caractère non standard (Ceska, Osterrecih, Island)

		switch ($pays[$leagueid][0]){
			case "38":
			$pays[$leagueid][1]="Island";
			break;
			
			case "39":
			$pays[$leagueid][1]="Osterreich";
			break;


			case "52":
			$pays[$leagueid][1]="Ceská republika";
			break;
		}
	}
	
	// vérification et mise à jour dans la base de données
	for ($leagueid=0 ; $leagueid<$numberofleagues; $leagueid++) {


								
		// regarde si le pays est déjà existant
		$maRequete="select * from ht_pays where idPays=".$pays[$leagueid][0];
		$retour = $maBase->select($maRequete);
		if(count($retour)>0) { // si le pays est bien présent on fait un update
				$pays[$leagueid][3]="modif";
				$maRequete="UPDATE ht_pays set nomPays ='".addslashes($pays[$leagueid][1])."', "
							." nomAnglais = '".addslashes($pays[$leagueid][2])."' "
							." WHERE idPays=".$pays[$leagueid][0] ;
//				echo "<br/>";
//				echo $maRequete;
//				echo "<br/>";						
							
				$retour = $maBase->update($maRequete);
				if(count($retour)>0) {
					$pays[$leagueid][4]="ok";										
				} else {
					$pays[$leagueid][4]="erreur";
				}										
		} else { // si le pays n'est pas présent, on fait un insert
				$pays[$leagueid][3]="rajout";
				$maRequete="INSERT INTO ht_pays ( idPays, nomPays, nomAnglais ) "
							." VALUES ( '".$pays[$leagueid][0]."' , '".addslashes($pays[$leagueid][1])."','".addslashes($pays[$leagueid][2])."' )" ;
//				echo "<br/>";
//				echo $maRequete;
//				echo "<br/>";						
							
				$retour = $maBase->insert($maRequete);
				if(count($retour)>0) {
					$pays[$leagueid][4]="ok";					
				} else {
					$pays[$leagueid][4]="erreur";
				}										
		}
		
					
					


	}
	
	
	// affichage résultat
	
	echo "<center/><h3>";
	echo "Nombre total de pays : ".$numberofleagues;
	echo "</h3></center>";
		
	$tableAttrs = array("align" => "center");
	$table =new HTML_Table($tableAttrs);    
	     
	foreach ($pays as $key => $value) {
		$table->addRow($pays[$key], array("bgcolor"=>"#F5F5F5"));
	}
	$altRow = array("bgcolor"=>"#FFD9F2");
	$table -> altRowAttributes(1, null, $altRow);
	
	$table -> setHeaderContents(0, 0, "id League");
	$table -> setHeaderContents(0, 1, "Nom Pays");
	$table -> setHeaderContents(0, 2, "Nom Pays Anglais");
	$table -> setHeaderContents(0, 3, "Action");
	$table -> setHeaderContents(0, 4, "Statut");
	
	$table -> setRowAttributes(0, array("bgcolor"=>"#CC3399"));
	
	echo $table->toHTML();
		
	
}


?>