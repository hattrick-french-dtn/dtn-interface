<?php
ini_set('display_errors','1');
if (isset($_GET["idJoueurHT"]) && $_GET["idJoueurHT"] !="") {
        $idHattrickJoueur = (int)$_GET["idJoueurHT"];
        if (!empty($idHattrickJoueur)) {
            header("location: ".$url."/dtn/interface/joueurs/fiche.php?htid=".$idHattrickJoueur);
            exit;
        } 
}
header("location: ".$url."/dtn/interface/joueurs/verifPlayer.php");
?>