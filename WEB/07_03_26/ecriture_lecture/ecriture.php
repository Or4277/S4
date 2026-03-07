<?php


    $fp = fopen("test.txt", "r+");

    $nbr_visite = fgets ($fp ,11) ;

    $nbr_visite = $nbr_visite + 1;

    fseek($fp, 0);

    fputs($fp , $nbr_visite) ;

    fclose ($fp) ;

    echo  ' il a etait visiter  ' .$nbr_visite. 'fois'  ;




?>