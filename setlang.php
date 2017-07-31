<?php
  session_start();
	$lang = $_GET["language"];
	if (!isset($lang)){
		$lang="en";
	}

	$_SESSION['lang']=$lang;

	if (isset($_GET["urlsource"])){
		header("location: ".$_GET["urlsource"]);
	} else {
    header("location: index.php");
  }
?>
