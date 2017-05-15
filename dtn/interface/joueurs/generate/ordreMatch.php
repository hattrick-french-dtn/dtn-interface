<?php
// A medium complex example of JpGraph
// Note: You can create a graph in far fewwr lines of code if you are
// willing to go with the defaults. This is an illustrative example of
// some of the capabilities of JpGraph.


require("../../includes/head.inc.php");
require("../../CHPP/config.php");

$sql =" SELECT * , count( id_match ) AS nbPerf, AVG( etoile ) AS perfMoyenne ".
	"FROM ht_perfs_individuelle, ht_joueurs, ht_position_correspondance, ht_position_match ".
	"WHERE id_joueur = idHattrickJoueur ".
	"AND id_role = id_role_fk ".
	"AND id_behaviour = id_behaviour_fk ".
	"AND idJoueur = '$id' ".
	"AND ht_position_match.id_position_match = id_position_match_fk ".
	"GROUP BY id_position_match_fk ".
	"ORDER BY nbPerf DESC "; 



foreach($conn->query($sql) as $lstPerf){
	$data[] = $lstPerf["nbPerf"];

	$legend[] = $lstPerf["nom_position_match"]. " (".round($lstPerf["perfMoyenne"],2)."* sur ".$lstPerf["nbPerf"]." match(s))";
}

require("../../graph/jpgraph.php");
require("../../graph/jpgraph_pie.php");

// A new pie graph
$graph = new PieGraph(680,330,"auto");
$graph->SetShadow();

// Title setup
$graph->title->Set("Repartition par : poste  (Moyenne des etoiles par poste)");
$graph->title->SetFont(FF_FONT1,FS_BOLD);

// Setup the pie plot
$p1 = new PiePlot($data);

// Adjust size and position of plot
$p1->SetSize(0.35);
$p1->SetCenter(0.3,0.52);

// Setup slice labels and move them into the plot
$p1->value->SetFont(FF_FONT1,FS_BOLD);
$p1->value->SetColor("darkred");
$p1->value->SetFormat('%.1f%%'); 

$p1->SetLabelPos(0.65);

// Explode all slices
$p1->ExplodeAll(10);

// Add drop shadow
$p1->SetShadow();

// Finally add the plot
$p1->value->SetFormat('%.1f%%'); 

$p1->SetLegends($legend);
$graph->legend->Pos(0.05,0.2);
$graph->Add($p1);



// ... and stroke it
$graph->Stroke();

?>