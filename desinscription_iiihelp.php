<?php
// Affiche toutes les erreurs
error_reporting(E_ALL);

require_once "dtn/interface/_config/CstGlobals.php"; // fonctions d'admin
require_once "dtn/interface/fonctions/AdminDtn.php"; // fonctions d'admin
require_once("dtn/interface/includes/head.inc.php");


if (!isset($id))	$id="erreur";
if (!isset($good))	$good="";

if ($id!="erreur")
{
  $sqlsupression= "delete from ht_iiihelp_repreneur where id_iiihelp_repreneur=".$_REQUEST['id'];
  $req=mysql_query($sqlsupression);
  $good="good";
}

if ($good=="good")
{
  header("Refresh: 5; url=http://".$_SERVER['SERVER_NAME']."/fff_help.php"); ?>
  Vous avez bien &eacute;t&eacute; d&eacute;sinscrit de iiihelp.<br />
  Vous serez redirig&eacute; sur le portail de la DTN dans quelques instants.
<?php }
else
{
?>
erreur
<?php}?>

