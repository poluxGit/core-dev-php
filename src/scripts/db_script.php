<?php

/**
 * DB Script
 *
 * Script de génération de script de base de données.
 * Génération depuis une définition métier des objets fonctionnels
 *
 * @author poluxGit
 *
 */

// Nombre arguments OK ?
if($argc != 2 || is_null($argv))
{
  echo "Usage : php db_script.php <DICO_CSV_FILE> \n";
  exit;
}


// <------------------------- begin of the script -------------------------> //
$dico_line = null;

$row = 1;
if (($handle = @fopen($argv[2], "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
    $num = count($data);
    echo "<p> $num champs à la ligne $row: <br /></p>\n";
    $row++;
    for ($c=0; $c < $num; $c++) {
      echo $data[$c] . "<br />\n";
    }
  }
  fclose($handle);
}
else {
  echo "ERROR : '".$argv[2]."' can't be reached ! \n";
}


// <------------------------- end of the script -------------------------> //
exit;

 ?>
