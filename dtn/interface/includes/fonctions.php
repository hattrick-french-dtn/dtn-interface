<?php $dbName = $db_c;


function dateToHtml ($string = "")
{

  if (empty ($string))
    return date ("d/m/y");
  $date = split ("-", $string);
  return $date[2]."/".$date[1]."/".substr ($date[0], 2, 2);

}

function timeToHtml ($string)
{
  return substr ($string, 0, 5);
}

function convertToHtml ($string)
{

  $string = ereg_replace ("\n", "<br>", htmlentities ($string));
  $string =
    ereg_replace ("(http://[a-zA-Z0-9./_\?%=\-]+)",
                  "<a href=\"\\1\" target=\"_blank\">\\1</a>", $string);
  // email :
  //      $string = ereg_replace ('[_a-zA-z0-9\-]+(\.[_a-zA-z0-9\-]+)*\@'.'[_a-zA-z0-9\-]+(\.[a-zA-z]{1,3})+', '<a href="mailto:\\0">\\0</a>', $string);
  return $string;

}                                // function convertToHtml($string)

function dateToBD ($string = "")
{
  //La date fournie est utilisable (JJ/MM/AAAA)
  if(!preg_match("#^[0-9]*/[0-9]*/[0-9]*$#",$string))
    return $string;

  if (empty ($string))
    return date ("Y-m-d");
  $date = split ("/", $string);
  if ($date[2] < 100)
    $date[2] += 2000;
  return $date[2]."-".$date[1]."-".$date[0];

}                                // function dateToBD($string = "")


function construitListe ($sql)
{

  global $dbName;
  if (!isset ($tableau))
    $tableau = array ();
  // Recuperation du nombre de table concernes par la requette
  $arg = array ();
  $nbArgs = func_num_args ();
  for ($a = 1; $a < $nbArgs; $a++)
    {
      $arg[$a] = func_get_arg ($a);
    }

  // Execution de la requette
  $req = mysql_query ($sql);
  if ($req && mysql_num_rows ($req) > 0)
    {
      // Connexion au serveur a la bdd pour retourner le nombre de champ par argument
      $tblChamp = array ();
      $k = 0;
      for ($a = 1; $a <= count ($arg); $a++)
        {

          //This section has been changed due to errors
          //after reading deprecated informations on mysql_list_fields command
          $result_columns = mysql_query ("SHOW COLUMNS FROM ".$arg[$a]);
          if (!$result_columns)
            {
              echo 'Could not run query: '.mysql_error ();

              //      exit;
            }
          if (mysql_num_rows ($result_columns) > 0)
            {
              while ($row_found = mysql_fetch_assoc ($result_columns))
                {

                  $typeField[$k] = $row_found["Type"];
                  $tblChamp[$k] = $row_found["Field"];
                  $k++;
                }
            }

          //                              $list = mysql_list_fields($dbName,$arg[$a]);
          //                              $nbChamp = mysql_num_fields($list);
          // Recuperation du nombre de champ et libelle par table
          //                                      for ($j=0; $j < $nbChamp; $j++)
          //                                      {
          //                                       $typeField[$k] = mysql_field_type($list, $j);
          //                                       $tblChamp[$k] = mysql_field_name($list, $j);
          //                                      $k++;
          //                                      }

        }

      $i = 0;
      while ($res = mysql_fetch_array ($req))
        {
          // Construction du tableau
          for ($j = 0; $j < count ($tblChamp); $j++)
            {
              switch ($typeField[$j])
                {

                case "date":
                  if ($res["$tblChamp[$j]"] != NULL)
                    {
                      $tableau[$i]["$tblChamp[$j]"] =
                        dateToHtml ($res["$tblChamp[$j]"]);
                    }
                  break;

                case "time":
                  if ($res["$tblChamp[$j]"] != NULL)
                    {
                      $tableau[$i]["$tblChamp[$j]"] =
                        timeToHtml ($res["$tblChamp[$j]"]);
                    }
                  break;

                default:
                  $tableau[$i]["$tblChamp[$j]"] =
                    stripslashes ($res["$tblChamp[$j]"]);
                  break;
                }
            }
          $i++;
        }
    }
  return $tableau;
  mysql_free_result ($req);
}


function query ($sql)
{

  global $dbName;

  // Recuperation des champs qui concerne la table passe en argument.
  $sql = func_get_arg (0);
  $req = mysql_query ($sql);
  // Creation d'un tableau contenant tout les champs de cette table
}

function gt_mysql_list_fields(){



$result = mysql_query("SHOW COLUMNS FROM sometable");
if (!$result) {
   echo 'Could not run query: ' . mysql_error();
   exit;
}
if (mysql_num_rows($result) > 0) {
   while ($row = mysql_fetch_assoc($result)) {
       print_r($row);
   }
}
}

function insertDB ($table)
{

  global $dbName;
  if(!isset($nb))  $nb=0;

  $dbName = "dtn_htfff";

  // Recuperation des champs qui concerne la table passe en argument.
  $arg = func_get_arg (0);
  // Creation d'un tableau contenant tout les champs de cette table
  $list = mysql_list_fields($dbName, $arg);
  $nbChamp = mysql_num_fields($list);
  $k = 0;
  // Recuperation du nombre de champ et libelle par table
  for ($j = 0; $j < $nbChamp; $j++)
    {
      $typeField[$k] = mysql_field_type ($list, $j);
      $tblChamp[$k] = mysql_field_name ($list, $j);
      $detailChamp[$k] = mysql_field_flags ($list, $j);
      $k++;
    }
  // Construction de la requete :
  foreach ($_POST as $cle =>$val)
  {
    if ($val != "")
      {
        //      echo $val;
        for ($i = 0; $i < count ($tblChamp); $i++)
          {
            if ($cle == $tblChamp[$i])
              $nb += 1;
          }

      }

  }
  $sql = "INSERT INTO $arg (";
  $abc = 0;
  for ($i = 0; $i < count ($tblChamp); $i++)
    {

    if (isset($_POST["$tblChamp[$i]"])) 
    {
      if ($_POST["$tblChamp[$i]"] != "")
        {

          $detailChamp1[$i] = explode (" ", $detailChamp[$i]);
          $aff = true;
          $abc += 1;
          // Verifie si le champs est un auto increment
          for ($j = 0; $j < count ($detailChamp1[$i]); $j++)
            {
              if ($detailChamp1[$i][$j] == "auto_increment")
                $aff = false;

              //              if($detailChamp[$i] != "auto_increment")
            }                        //                    for($j = 0; $j<count($detailChamp[$i]);$j++)

          // Si non, la requette prend en compte ce champ

          if ($aff == true)
            {
              $sql.= "$tblChamp[$i]";
              if ($abc != $nb)
                {
                  $sql.= ",";
                }                //                            if($i != count($tblChamp)-1){
            }
        }                        //            if(isset($_POST["$tblChamp[$i]"])){
      }

    }                                //     for($i=1;$i<count($tblChamp);$i++)


  $sql.= ") VALUES (";


  $abc = 0;

  for ($i = 0; $i < count ($tblChamp); $i++)
    {
    if (isset($_POST["$tblChamp[$i]"])) 
    {
      if ($_POST["$tblChamp[$i]"] != "")
        {

          $detailChamp2[$i] = explode (" ", $detailChamp[$i]);
          $aff = true;
          $nn = false;
          $caps = true;
          for ($j = 0; $j < count ($detailChamp2[$i]); $j++)
            {
              if ($detailChamp2[$i][$j] == "auto_increment")
                $aff = false;
              if ($detailChamp2[$i][$j] == "not_null")
                $nn = true;
            }                        //                    for($j = 0; $j<count($detailChamp[$i]);$j++)

          if ($typeField[$i] == "int" && $nn == true)
            $caps = true;

          if ($aff == true)
            {
              if ($nn == true && $_POST["$tblChamp[$i]"] == NULL)
                $_POST["$tblChamp[$i]"] = "0";
              if ($nn == false && $_POST["$tblChamp[$i]"] == NULL)
                {
                  $_POST["$tblChamp[$i]"] = '""';
                  $caps = false;
                }
              if ($caps == true)
                $sql.= '"';

              switch ($typeField[$i])
                {

                case "date":
                  $_POST["$tblChamp[$i]"] =
                    dateToBD ($_POST["$tblChamp[$i]"]);
                  $abc += 1;
                  break;

                default:
                  $_POST["$tblChamp[$i]"] = $_POST["$tblChamp[$i]"];
                  $abc += 1;
                  break;
                }
              $sql.= $_POST["$tblChamp[$i]"];


              if ($caps == true)
                $sql.= '"';

              if ($abc != $nb)
                {
                  $sql.= ",";
                }                //             if($i != count($tblChamp)-1){


            }                        //             if($aff == true)

        }
      }
    }                                //     for($i=1;$i<count($tblChamp);$i++)
  $sql.= ")";
  // Execution de la requette

  $req = mysql_query ($sql);
  return $sql;
}

function majDB ()
{

  global $dbName;
  $k = 0;
  $caps = true;
  $nn = false;
  // Recuperation des champs qui concerne la table passe en argument.
  $arg1 = func_get_arg (0);
  $arg2 = func_get_arg (1);
  // Creation d'un tableau contenant tout les champs de cette table

  $result_columns = mysql_query ("SHOW COLUMNS FROM ".$arg1);
  if (!$result_columns)
    {
      echo 'Could not run query: '.mysql_error ();

      //      exit;
    }
  if (mysql_num_rows ($result_columns) > 0)
    {
      while ($row_found = mysql_fetch_assoc ($result_columns))
        {

          $typeField[$k] = $row_found["Type"];
          $tblChamp[$k] = $row_found["Field"];
          $primKey[$k] = $row_found["Key"];
          $mayBeNull[$k] = $row_found["Null"];
          //print_r($typeField[$k]);
          //print_r($tblChamp[$k]);
          //print_r($primKey[$k]);
          //print_r($mayBeNull[$k]);
                  //print_r(" // end of filed ".$k." / <br>");
          $k++;
        }
    }



  // Construction de la requete :
  $sql = "UPDATE $arg1 SET ";

  $nbTotal = count ($tblChamp);
  for ($i = 0; $i < count ($tblChamp); $i++)
    {
      $nn = true;
      if ($primKey[$i] == "PRI")
        {
          $ClePrimaire = $tblChamp[$i];
        }
      if ($mayBeNull[$i] == "YES")
        {
          $nn = false;
        }

      // Si le champ est la clef primaire, on l'ignore dans la mise a jour
      if ($ClePrimaire != $tblChamp[$i] && $_POST["$tblChamp[$i]"] != "")
        {
          $sql.= "$tblChamp[$i] = ";


          if ($caps == true)
            $sql.= '"';

          switch ($typeField[$i])
            {

            case "date":
              $_POST["$tblChamp[$i]"] = dateToBD ($_POST["$tblChamp[$i]"]);
              break;

            default:
              $_POST["$tblChamp[$i]"] = $_POST["$tblChamp[$i]"];
              break;
            }

          if ($nn == true && $_POST["$tblChamp[$i]"] == NULL)
            $_POST["$tblChamp[$i]"] = "";
          if ($nn == false && $_POST["$tblChamp[$i]"] == NULL)
            {
              $_POST["$tblChamp[$i]"] = '""';
              //      echo "ok";
              //      echo "<br>";
            }
          $sql.= $_POST["$tblChamp[$i]"];

          if ($caps == true)
            $sql.= '"';
          if ($i != $nbTotal - 1)
            {
              $sql.= ",";
            }

        }
    }
  $sql.= " WHERE $ClePrimaire =  $arg2";

  $req = mysql_query ($sql);

  return $sql;

}




function majDBAncienneVersion ()
{

  global $dbName;
  $k = 0;
  $caps = true;
  $nn = false;
  // Recuperation des champs qui concerne la table passee en argument.
  $arg1 = func_get_arg (0);
  $arg2 = func_get_arg (1);
  // Creation d'un tableau contenant tout les champs de cette table
  $list = mysql_list_fields ($dbName, $arg1);
  $nbChamp = mysql_field_count($list);

  for ($j = 0; $j < $nbChamp; $j++)
    {
      $typeField[$k] = mysql_field_type ($list, $j);
      $tblChamp[$k] = mysql_field_name ($list, $j);
      $detailChamp[$k] = mysql_field_flags ($list, $j);
      $k++;
    }

  // Construction de la requete :
  $sql = "UPDATE $arg1 SET ";

  $nbTotal = count ($tblChamp);
  for ($i = 0; $i < count ($tblChamp); $i++)
    {

      // Definition de la cle primaire
      $detailChamp[$i] = explode (" ", $detailChamp[$i]);
      for ($j = 0; $j < count ($detailChamp[$i]); $j++)
        {
          if ($detailChamp[$i][$j] == "primary_key")
            {
              $ClePrimaire = $tblChamp[$i];
            }
          if ($detailChamp[$i][$j] == "not_null")
            {
              $nn = true;
            }

        }

      // Si le champ est la clef primaire, on l'ignore dans la mise a jour
      if ($ClePrimaire != $tblChamp[$i] && $_POST["$tblChamp[$i]"] != "")
        {
          $sql.= "$tblChamp[$i] = ";
          if ($caps == true)
            $sql.= '"';
          switch ($typeField[$i])
            {
            case "date":
              $_POST["$tblChamp[$i]"] = dateToBD ($_POST["$tblChamp[$i]"]);
              break;

            default:
              $_POST["$tblChamp[$i]"] = $_POST["$tblChamp[$i]"];
              break;
            }

          if ($nn == true && $_POST["$tblChamp[$i]"] == NULL)
            $_POST["$tblChamp[$i]"] = "";
          if ($nn == false && $_POST["$tblChamp[$i]"] == NULL)
            {
              $_POST["$tblChamp[$i]"] = '""';
            }
          $sql.= $_POST["$tblChamp[$i]"];

          if ($caps == true)
            $sql.= '"';
          if ($i != $nbTotal - 1)
            {
              $sql.= ",";
            }

        }
    }
  $sql.= " WHERE $ClePrimaire =  $arg2";
  $req = mysql_query ($sql);
  return $sql;

}

function supprDB ()
{

  global $dbName;
  $k = 0;
  // Recuperation des champs qui concerne la table passee en argument.
  $arg1 = func_get_arg (0);
  $arg2 = func_get_arg (1);
  // Creation d'un tableau contenant tout les champs de cette table
  $list = mysql_list_fields ($dbName, $arg1);
  $nbChamp = mysql_num_fields($list);

  for ($j = 0; $j < $nbChamp; $j++)
    {
      $typeField[$k] = mysql_field_type ($list, $j);
      $tblChamp[$k] = mysql_field_name ($list, $j);
      $detailChamp[$k] = mysql_field_flags ($list, $j);
      $k++;
    }
  //Construction de la requette
  for ($i = 0; $i < count ($tblChamp); $i++)
    {
      $detailChamp[$i] = explode (" ", $detailChamp[$i]);
      for ($j = 0; $j < count ($detailChamp[$i]); $j++)
        {
          if ($detailChamp[$i][$j] == "primary_key")
            {
              $ClePrimaire = $tblChamp[$i];
            }
        }
    }
  $sql = "delete from $arg1 where $ClePrimaire = $arg2";
  $req = mysql_query ($sql);
}

function date_us_vers_fr ($dateUS)        // $dateUS=AAAA-MM-JJ
{
  //$elementsdate=chunk_split($dateUS , 2 , "-");
  $elementsdate = explode ("-", $dateUS);
  $jour = $elementsdate[2];
  $mois = $elementsdate[1];
  $annee = $elementsdate[0];
  return $dateFR = $jour.$mois.$annee;
}

function date_us_vers_fr2 ($dateUS)        // $dateUS=AAAA-MM-JJ
{
  //$elementsdate=chunk_split($dateUS , 2 , "-");
  $elementsdate = explode ("-", $dateUS);
  $jour = $elementsdate[2];
  $mois = $elementsdate[1];
  $annee = $elementsdate[0];
  return $dateFR = $jour.'/'.$mois.'/'.$annee;
}


function date_fr_vers_us ($dateFR)
{
  $elementsdate = chunk_split ($dateFR, 2, "-");
  $elementsdate = explode ("-", $elementsdate);
  $annee = $elementsdate[2].$elementsdate[3];
  $mois = $elementsdate[1];
  $jour = $elementsdate[0];
  $dateUS = $annee."-".$mois."-".$jour;
  return $dateUS;
}


?>
