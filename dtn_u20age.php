<?php
// Variable paramétrage de la page
$nomFicPhpCourant = explode("?",$_SERVER['REQUEST_URI']);
$urlsource = $nomFicPhpCourant[0]; // Utilisé pour setlang.php
$callbackUrl="http://".$_SERVER['HTTP_HOST'].$nomFicPhpCourant[0]."?mode=retour"; // Url de retour après authentification sur HT

include("init.php");
require("dtn/interface/includes/nomTables.inc.php");
//require("dtn/interface/includes/serviceMatchs.php");
require("dtn/interface/includes/serviceListesDiverses.php");
require("dtn/interface/includes/serviceJoueur.php");
require("inc/fct_requirements.php"); 

/*$sql = " SELECT
		truncate((UNIX_TIMESTAMP(sysdate())-UNIX_TIMESTAMP('1997-05-31'))/86400/112,0) as saison,
		UNIX_TIMESTAMP('1997-05-31')+112*86400*truncate((UNIX_TIMESTAMP(sysdate())-UNIX_TIMESTAMP('1997-05-31'))/86400/112,0) as dateSemaine0,
		FROM_UNIXTIME(UNIX_TIMESTAMP('1997-05-31')+112*86400*truncate((UNIX_TIMESTAMP(sysdate())-UNIX_TIMESTAMP('1997-05-31'))/86400/112,0)) as dateclair
		FROM dual";
		
$req=  mysql_query($sql);
$res = mysql_fetch_array($req);

$_SESSION["sesUser"]["saison"] = $res["saison"]; // Saison
$_SESSION["sesUser"]["dateSemaine0"] = $res["dateSemaine0"]; // Timestamp du premier jour de la saison

print_r($res);*/

/******************************************************************************/
/******************************************************************************/
/*      GESTION TRADUCTION                                                    */
/******************************************************************************/
/******************************************************************************/
// Ajout Traduction Anglais/Allemand par Musta le 08/08/2009	
// Ajout Traduction Italien par Alpa le 07/08/2019
switch($_SESSION['lang']) {
	case "fr" :
  $motSaison="Saison";
  $motSemaine="Semaine";
  $motDate="Date";
  $motJournee="Journ&eacute;e";
  $motAgeMaxi="Age Maxi actuel";
  $motTour="Tour";
  $motanset="ans et";
  $motjours="jours";
  $motDemi="Demi-finale";
  $motFinale="Finale";
  $parfait="Age parfait !";
  $motJoueur="Joueurs";
  $phraseIntro="Les joueurs doivent avoir moins de 21 ans pour jouer en U20 donc au maximum 20 ans et 111 jours le jour d'un match.";
	break;
	case "de" :
  $motSaison="Saison";
  $motSemaine="Woche";
  $motDate="Datum";
  $motJournee="Spieltag";
  $motAgeMaxi="Maximales Alter heute";
  $motTour="Runde";
  $motanset="Jahre und";
  $motjours="Tage";
  $motDemi="Halbfinale";
  $motFinale="Finale";
  $parfait="Perfekte Alter !";
  $motJoueur="Spieler";
  $phraseIntro="Spieler m&uuml;ssen j&uuml;nger als 21 Jahre sein um in der U20 spielen zu k&ouml;nnen, also maximal 20 jahre 111 Tage am Tag des Spieles.";
	break;
	case "es" :
  $motSaison="Temporada";
  $motSemaine="Semana";
  $motDate="Fecha";
  $motJournee="Fecha";
  $motAgeMaxi="Edad m&aacute;xima actual";
  $motTour="Ronda";
  $motanset="a&ntilde;os y";
  $motjours="d&iacute;as";
  $motDemi="Semifinal";
  $motFinale="Final";
  $parfait="Edad perfecta!";
  $motJoueur="Jugadores";
  $phraseIntro="Los jugadores tienen que tener menos de 21 a&ntilde;os para jugar con la selecci&oacute;n U20, as&iacute; que una edad m&aacute;xima de 20 a&ntilde;os y 111 d&iacute;as el d&iacute;a de un partido.";
	break;
	case "it" :
  $motSaison="Stagione";
  $motSemaine="Settimana";
  $motDate="Data";
  $motJournee="Partita";
  $motAgeMaxi="Max et&agrave; attuale";
  $motTour="Girone";
  $motanset="anni e";
  $motjours="giorni";
  $motDemi="Semi-finale";
  $motFinale="Finale";
  $parfait="Et&agrave; perfetta!";
  $motJoueur="Giocatori";
  $phraseIntro="I giocatori devono avere meno di 21 anni per giocare in U20 quindi 20 anni e 111 giorni il giorno della partita.";
	break;
	case "nl" :
  $motSaison="Seizoen";
  $motSemaine="Week";
  $motDate="Datum";
  $motJournee="Wedstrijddag";
  $motAgeMaxi="Maximumleeftijd vandaag";
  $motTour="Ronde";
  $motanset="jaar en";
  $motjours="dagen";
  $motDemi="Halve finale";
  $motFinale="Finale";
  $parfait="Ideale leeftijd!";
  $motJoueur="Spelers";
  $phraseIntro="Spelers moeten jonger dan 21 jaar zijn om in de U20 te spelen, dat betekent een maximumleeftijd van 20 jaar en 111 dagen op de wedstrijddag.";
	break;
	case "sv" :
  $motSaison="S&auml;song";
  $motSemaine="Vecka";
  $motDate="Datum";
  $motJournee="Partita";
  $motAgeMaxi="Max &aring;lder idag";
  $motTour="Omg&aring;ng";
  $motanset="&aring;r och";
  $motjours="dagar";
  $motDemi="Semifinal";
  $motFinale="Final";
  $parfait="Perfekt &aring;lder!";
  $motJoueur="Spelare";
  $phraseIntro="Spelare m&aring;ste vara under 21 f&ouml;r att spela i U20, det betyder maximalt 20 &aring;r och 111 dagar n&auml;r matchen spelas.";
	break;
 	default :
  $motSaison="Season";
  $motSemaine="Week";
  $motDate="Date";
  $motJournee="Matchday";
  $motAgeMaxi="Max Age today";
  $motTour="Round";
  $motanset="years and";
  $motjours="days";
  $motDemi="Semifinal";
  $motFinale="Final";
  $parfait="Perfect age !";
  $motJoueur="Players";
  $phraseIntro="Players must be under 21 to take part of U20 (team), it means a maximum of 20 years and 111 days the day of the match.";
	break;
}


/******************************************************************************/
/******************************************************************************/
/*      AFFICHAGE CONTENU PAGE                                                */
/******************************************************************************/
/******************************************************************************/

// Initialisation variables
$list_matchs = listMatchsU20(); // Listes des matchs U20 à venir

// Liste des joueurs de l'équipe connecté de moins de 21 ans
unset($playersU20);
if (isset($_SESSION['HT'])) {
  $players=getDataMesJoueursFromHT_usingPHT($_SESSION['HT']->getTeam()->getTeamId());

  foreach ($players as $p) {
    if (intval($p['AGE']) < 21) {
      $playersU20[]=$p;
    }
  }
  unset($players);
  unset($p);
}

?>

<table width="90%" class="ContenuCentrer">
  <tr>
  <td class="Contenu">
    <br />
    <?php echo($phraseIntro);?>
    <br />
    <br />
    <table class="Tableau">
      <thead class="entete">
      <tr>
        <td><?php echo($motSemaine);?></td>
        <td><?php echo($motDate);?></td>
        <td><?php echo($motJournee);?></td>
        <td><?php echo($motAgeMaxi);?></td>
        <?php if (isset($playersU20)) {?>
          <td><?php echo($motJoueur);?></td>
        <?php }?>
      </tr>
      </thead>
    
      <tr class="rupture">
        <td colspan="5"><?php echo($motSaison." ".$list_matchs[0]['season']." - World Cup ".$list_matchs[0]['numWC']);?></td>
      </tr>
      <?php
      for ($i=0;$i<count($list_matchs);$i++) {?>

        <tr class=<?php if ($i%2==0) {?>"paire"<?php } else {?>"impaire"<?php }?>>
      		<td><?php echo($list_matchs[$i]['week']);?></td>
      		<td nowrap="nowrap"><?php echo($list_matchs[$i]['date_match']);?></td>
      		<td nowrap="nowrap">
      		  <?php 
            if ($list_matchs[$i]['tour']=='FINALE') {echo($motFinale);}
            elseif ($list_matchs[$i]['tour']=='DEMI') {echo($motDemi);}
            else {echo($motTour." ".$list_matchs[$i]['tour']." - ".$motJournee." ".$list_matchs[$i]['journee']);}?>
          </td>
      		<td nowrap="nowrap">
            <?php echo($list_matchs[$i]['ageAnMaxi']." ".$motanset." ".$list_matchs[$i]['ageJourMaxi']." ".$motjours); 
                  if ( ($list_matchs[$i]['tour']=='1' && $list_matchs[$i]['journee']=='14') || ($list_matchs[$i]['tour']=='FINALE') ) {?><span class="MsgSucces"><?php echo(" (".$parfait.")");?></span><?php }?>
          </td>
          <?php if (isset($playersU20)) {?>
        		<td nowrap="nowrap">
              <?php for ($j=0;$j<count($playersU20);$j++) {
                if ($playersU20[$j]['AGE']<=$list_matchs[$i]['ageAnMaxi'] && // 17 <= 17
                    $playersU20[$j]['AGE']>=$list_matchs[$i+1]['ageAnMaxi'] && //  17 >= 17
                    $playersU20[$j]['AGEDAYS']<=$list_matchs[$i]['ageJourMaxi'] && // 86 <= 89 
                    $playersU20[$j]['AGEDAYS']>$list_matchs[$i+1]['ageJourMaxi'] ) { // 86 > 86
                    echo("[".$playersU20[$j]['idHattrickJoueur']."] ".$playersU20[$j]['nomJoueur']." - ".$playersU20[$j]['AGE']." ".$motanset." ".$playersU20[$j]['AGEDAYS']." ".$motjours."<br />");
                }
              }?>
            </td>
          <?php }?>
  		  </tr>
  		  
        <?php if ( (($i+1) < count($list_matchs)) && $list_matchs[$i]['season']!=$list_matchs[$i+1]['season']) {?>
          <tr class="rupture">
            <td colspan="5"><?php echo($motSaison." ".$list_matchs[$i+1]['season']." - World Cup ".$list_matchs[$i+1]['numWC']);?></td>
          </tr>
        <?php }?>
  		  
      <?php }?>
    
    </table>
  </td>
  </tr>
</table>

<?php 
include("menu_bas.php");
?>