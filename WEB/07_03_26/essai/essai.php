<?php

$fp =fopen("essai.txt" ,"r");
$contenu =fgets($fp,255);
fclose($fp);

echo "Notre fichier contient :" .$contenu;

?>