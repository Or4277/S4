<?php
    $nom = $_POST['nom'];
    
    $fp = fopen("liste_noms.txt", "a+");

    fputs($fp , $nom. "\n") ;

    fclose ($fp);

    // echo 'Le nom insere  est ' .$nom ;

    header("Location:tp.php");
    
?>