<?php
    // echo 'Test';

    $fp = fopen("essai.txt", "r");

    $contenu = fgets ($fp ,1000) ;

    fclose ($fp) ;

    echo  'Le fichier txt contient ' .$contenu ;




?>