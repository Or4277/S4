<?php
$fp = fopen("listes_nom.txt","a+");
$nom = $_GET["nom"];

fputs($fp,$nom ."\n");

fclose($fp);


header("Location:formulaire.php");

?>