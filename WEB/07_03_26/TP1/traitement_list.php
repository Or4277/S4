<?php


session_start();
$fp = fopen("listes_nom.txt", "r");
$noms = [];

while ($line = fgets($fp)) {
    $noms[] = $line; 
}
fclose($fp);
$_SESSION['noms'] = $noms;

header("Location:liste.php");


?>