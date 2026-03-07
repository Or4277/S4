<?php
$fp =fopen("test.csv","r");

$contenu = fgets($fp,255);

fclose($fp);

echo $contenu ;
?>