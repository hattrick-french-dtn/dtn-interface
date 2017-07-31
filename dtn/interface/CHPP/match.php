<?php
require_once "HT_Client.php"; // Hattrick Client New (with Advanced HTTP Client)
require_once "phpxml.php"; // Hattrick Client New (with Advanced HTTP Client)


$infMatch = $HTCli->GetLastMatch("94583");
$tree = GetXMLTree($infMatch);
echo "<pre>";
var_dump($tree);
echo "</pre>";
?>
