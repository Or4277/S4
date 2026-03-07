<?php
    session_start();
    

    $fp = fopen("liste_noms.txt", "r");

    $listes = [];
    while ($liste = fgets ($fp) ) {

        $listes[] =  $liste;  
    }
     
     $_SESSION['listes']= $listes;

    fclose ($fp) ;

    header("Location:liste.php");




?>