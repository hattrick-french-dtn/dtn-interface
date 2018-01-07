<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=iso-8859-1" />

<link rel="stylesheet" type="text/css" href="../css/popup.css" />
<link rel="stylesheet" type="text/css" href="../css/style.css" />

</head>
<body>

<table class="subcenter">
  <form name="formAddHistoJoueur" method="post" target="_parent" action="../form.php">
    <tr><td valign="top">Commentaire sur le joueur :</td><td><textarea name="intituleHisto" rows=10 cols=40 id="intituleHisto"></textarea></td></tr>
    <input name="idJoueur" type="hidden" id="idJoueur" value="<?=$_GET['idJoueur']?>">
    <input name="mode" type="hidden" id="mode" value="addHistoJoueur">
    <tr><td><div align="center"><input type="submit" value="Ajouter"></div></td></tr>
  </form>
  

</table>
</body>
</html>
