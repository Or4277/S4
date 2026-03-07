<?php
session_start();
var_dump($_SESSION['listes']);
$listes =$_SESSION['listes'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>La liste fameuse liste</h1>
    <?php
        for($i = 0; $i < count($listes);$i++)  {
            $element= $listes[$i];
            echo  '<li>'. $element . '</li>'; 
        }

    ?>
    
</body>
</html>

