<?php
ini_set('error_reporting', E_ERROR);
require_once("../../includes/head.inc.php");


$sql = "select * from $tbl_joueurs, $tbl_position where idJoueur = $id and ht_posteAssigne  = idPosition";
$lstJoueur = construitListe($sql, $tbl_joueurs, $tbl_position);


$titre="de ".$lstJoueur[0]["prenomJoueur"]." ".$lstJoueur[0]["nomJoueur"];
//$sql =  'select * from '.$tbl_perf.', '.$tbl_caracteristiques.', '.$tbl_joueurs.' ' .
$sql =  "select * from ".$tbl_perf.", ".$tbl_joueurs.
		" where id_joueur = idHattrickJoueur " .
		" and idJoueur='$id'  order by date_match desc ";
				
//$sql =  'select * from '.$tbl_perf.', '.$tbl_caracteristiques.', '.$tbl_joueurs.' where idJoueur_fk = idJoueur and formePerf = idCarac and idJoueur_fk = '.$id.' and affPerf = 1 and postePerf != "BLS" ';


if(!isset($tri)) $tri = 10;
$sql .= " limit 0,".$tri;


$req = $conn->query($sql);


$echelleDate[] = 0;
$formePerf[] = 0;
$valeurPerf[] =  0;
$scorePerf[] = 0;


foreach($req as $lstPerf){
	$echelleDate[] = substr($lstPerf["date_match"],0,10);

	$formePerf[] =  $lstPerf["forme"];
	$valeurPerf[] =  $lstPerf["tsi"];
	$scorePerf[] =  $lstPerf["etoile"];

}




require("../../graph/jpgraph.php");
require("../../graph/jpgraph_line.php");
require("../../graph/jpgraph_bar.php");


// A medium complex example of JpGraph
// Note: You can create a graph in far fewwr lines of code if you are
// willing to go with the defaults. This is an illustrative example of
// some of the capabilities of JpGraph.



// Create some datapoints 
$steps=$req->rowCount();
for($i=1; $i<$steps; $i++) {
	
	$datay[]=$formePerf[$i];
	$datatsiy[]=$valeurPerf[$i];
	 
	$databary[] = $scorePerf[$i];
	$databarx[] = $echelleDate[$i];
}


// New graph with a background image and drop shadow
$graph = new Graph(680,330,"auto");
$graph->SetShadow();


// Use text X-scale so we can text labels on the X-axis
$graph->SetScale("textlin");


// Y2-axis is linear
$graph->SetY2Scale("lin");


// Color the two Y-axis to make them easier to associate
// to the corresponding plot (we keep the axis black though)
$graph->yaxis->SetColor("black","blue");
$graph->y2axis->SetColor("black","red");


// Set title and subtitle
$graph->title->Set("Rapport forme / performance $titre");


// Use built in font (don't need TTF support)
$graph->title->SetFont(FF_FONT1,FS_BOLD);


// Make the margin around the plot a little bit bigger then default
$graph->img->SetMargin(40,140,40,80);	


// Slightly adjust the legend from it's default position in the
// top right corner to middle right side
$graph->legend->Pos(0.03,0.5,"right","center");


// Display every 6:th tickmark
$graph->xaxis->SetTextTickInterval(1);


// Label every 2:nd tick mark
$graph->xaxis->SetTextLabelInterval(1);


// Setup the labels
$graph->xaxis->SetTickLabels($databarx);
$graph->xaxis->SetLabelAngle(90);


// Create a red line plot
$p1 = new LinePlot($datay);
$p1->SetColor("blue");
$p1->SetLegend("Forme");


// Create a green line plot
$p2 = new LinePlot($datatsiy);
$p2->SetColor("red");
$p2->SetLegend("TSI");




// Create the bar plot
$b1 = new BarPlot($databary);
$b1->SetLegend("Etoiles");
$b1->SetFillColor("yellow");
$b1->SetAbsWidth(10);


// Drop shadow on bars adjust the default values a little bit
$b1->SetShadow("steelblue",2,2);


// The order the plots are added determines who's ontop
$graph->AddY2($p2);
$graph->Add($p1);
$graph->Add($b1);
// Finally output the  image
$graph->Stroke();

?>