<?php


    $fp = fopen("compteur.txt", "r+");

    $nbr_visite = fgets ($fp ,11) ;

    $nbr_visite = $nbr_visite + 1;

    fseek($fp, 0);

    fputs($fp , $nbr_visite) ;

    fclose ($fp) ;

    echo  ' Ce site compte ' .$nbr_visite. 'visiteurs'  ;




?>