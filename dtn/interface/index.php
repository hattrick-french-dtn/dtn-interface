<?php
$_SESSION['HT'] = null;
include_once($_SERVER["DOCUMENT_ROOT"]."/dtn/interface/includes/head.inc.php");
$_SESSION['acces']="INTERFACE"; // sert à avoir un affichage personnalisé pour les composants utilisés dans le portail et l'interface



/******************************************************/
// Si menu de déconnexion a été sélectionné
/******************************************************/
if (isset($_GET['action']) && $_GET['action']=='logout') {
  session_destroy();
  
  header('Location: index.php');
  exit;
}


/******************************************************/
// Si formulaire de connexion a été soumis
/******************************************************/
if (isset($_POST['nomForm']) && $_POST['nomForm']=='formConnexion') {

  $erreurForm=false;

  // On rend inoffensives les balises HTML que le visiteur a pu rentrer
  $_POST['login'] = stripslashes(trim(htmlspecialchars($_POST['login'])));
  $_POST['password'] = stripslashes(trim(htmlspecialchars($_POST['password'])));

  // Vérification zones obligatoires
  if (empty($_POST['login']) || empty($_POST['password']) )
  {
    $erreurObligatoire="Vous devez compl&eacute;ter toutes les zones !";
    $erreurForm=true;
  }

  if (isset($_POST['horsConnexion']) && $_POST['horsConnexion']==1) {
    $_SESSION['horsConnexion']=$_POST['horsConnexion'];
  }

  //Envoyer mail et afficher message succès
  if (!$erreurForm) {

    $sql = "SELECT 
                  $tbl_admin.*,
                  $tbl_niveauAcces.*
            FROM  $tbl_admin, 
                  $tbl_niveauAcces
            WHERE $tbl_admin.loginAdmin = '".$_POST['login']."' 
            AND   $tbl_admin.passAdmin = '".$_POST['password']."' 
            AND   $tbl_admin.idNiveauAcces_fk = $tbl_niveauAcces.idNiveauAcces";
    
    $req  = $conn->query($sql);

    if(!$req){
		echo('Erreur : Prenez contact avec les d&eacute;veloppeurs ou les administrateurs de la DTN');
		exit;
    } elseif ($req->rowCount() != 1) {
		$erreurAuthentification="Le login et/ou le password saisis sont inconnus.";
    } else {
    
	// Le Login et le password existe dans la base
	$_SESSION['sesUser'] = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	$req = NULL;
//var_dump($_SESSION);echo('<br /><br />');
   
    if (!isset($_SESSION['sesUser']['idAdminHT'])) {
		// Ne devrait plus se produire lorsque tous les adminHT seront correctement renseignés
        echo ('ID utilisateur absent - Contactez un administrateur de la DTN afin de reconfigurer votre compte');
        exit;
	}
	// Information sur le club
	$sql = "SELECT 
				$tbl_clubs.idClubHT,
				$tbl_clubs.nomClub,
				$tbl_clubs.nomUser
		  FROM  $tbl_clubs 
		  WHERE $tbl_clubs.idUserHT = ".$_SESSION['sesUser']['idAdminHT'];
      
    $req  = $conn->query($sql);
  
    if(!$req){
        echo('Erreur : Prenez contact avec les d&eacute;veloppeurs ou les administrateurs de la DTN');
        exit;
    } else {
        $_SESSION['sesUser']['club'] = $req->fetch(PDO::FETCH_ASSOC);
        $req = NULL;
//print_r($_SESSION['sesUser']['club']);echo('<br /><br />');
    }

    // On calcule le numéro de saison et le nombre de secondes écoulé entre la saison 0 jour 1 et le premier jour de la saison courante
    $sql = " SELECT
      		truncate((UNIX_TIMESTAMP(sysdate())-UNIX_TIMESTAMP('1997-05-31'))/86400/112,0) as saison,
      		UNIX_TIMESTAMP('1997-05-31') as date0
      		FROM dual";
	$req  = $conn->query($sql);
	$res = $req->fetch(PDO::FETCH_ASSOC);
      
    $_SESSION['sesUser']["saison"] = $res["saison"];
    $_SESSION['sesUser']["dateSemaine0"] = $res["date0"]+112*86400*$_SESSION['sesUser']["saison"];
	$req = NULL;

    // MAJ des dates de connexion du membre
    if(count($_SESSION['sesUser']) > 0){
      
        // Date avant dernière connexion = Date dernière connexion
        $sql = "UPDATE $tbl_admin SET 
                  dateAvantDerniereConnexion = dateDerniereConnexion, 
                  heureAvantDerniereConnexion = heureDerniereConnexion
                WHERE idAdmin = ".$_SESSION['sesUser']["idAdmin"];
        $req  = $conn->exec($sql);
        
        // Date dernière connexion = Date courante
        $sql = "UPDATE $tbl_admin SET 
                  dateDerniereConnexion = '".date("Y-m-d")."',
                  heureDerniereConnexion = '".date("H:i:s")."'
                WHERE idAdmin = ".$_SESSION['sesUser']["idAdmin"];
        $req  = $conn->exec($sql);
        if ($req != 1) {
          echo('ERREUR LORS DE LA CONNEXION : Prenez contact avec les d&eacute;veloppeurs ou les administrateurs de la DTN');
          exit;
        }
      }

      $sesUser = $_SESSION["sesUser"];
      header("location: index2.php");

    }
  }
}?>



<html>
<head>
<title>DTN - Interface administration</title>
<link href="css/ht.css" rel="stylesheet" type="text/css">
<link href="css/style.css" rel="stylesheet" type="text/css">
</head>

<body>

<br />
<br />

<form name="form" method="post" action="">
  <table class="ContenuCentrer">
  <tr>
    <td>
      <tr>
        <td width="700" height="26" >
        <div align="center"> DTN - <i>Interface d'administration.</i><br /> Identifiez vous</div>
        <hr />
        <br />
        </td>
      </tr>

      <tr>
        <td>
        <table width="50%" class="ContenuCentrer">
          <tr>
            <td width="51%">Login</td>
            <td width="49%">
              <input name="login" type="text" id="login" <?php if (isset($_POST['login'])) {?>value="<?php echo($_POST['login']);?>"<?php }?>>
            </td>
          </tr>
          <tr>
            <td>Password</td>
            <td>
              <input name="password" type="password" id="password" <?php if (isset($_POST['password'])) {?>value="<?php echo($_POST['password']);?>"<?php }?>>
            </td>
          </tr>
          <tr>
            <td>
              <br />
              <input type="checkbox" name="horsConnexion" value="1"> Mode Hors Connexion
              <br />
            </td>
          </tr>
        </table>
        </td>
      </tr>
      <tr>
        <td class="MsgErreur">
          <?php
		    $ErrorMsg = isset($_GET['ErrorMsg'])?$_GET['ErrorMsg']:NULL;
      		if($ErrorMsg) echo(stripslashes($ErrorMsg."<br />"));
      		if(isset($erreurObligatoire)) echo ($erreurObligatoire."<br />");
      		if(isset($erreurAuthentification)) echo ($erreurAuthentification."<br />");
      		?>&nbsp;
        </td>
      </tr>
      <tr>
        <td class="ContenuCentrer">
          <input type="submit" name="Submit" value="SE CONNECTER" class="boutonGris">
          <input name="nomForm" type="hidden" id="nomForm" value="formConnexion">
          <br />
          <br />
        </td>
      </tr>
    </td>
  </tr>
  </table>
</form>


<font size="-1">
<p>
En cas de probl&egrave;me ou demande : <a href="/bug/main_page.php">Utilisez Mantis</a>
</p>
</font>
</body>
</html>
