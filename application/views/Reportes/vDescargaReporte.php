<?php
$variable="Hola esto es ejemplo"; 
header("Content-Type: text/plain");
header('Content-Disposition: attachment; filename="filename.txt"');
echo $variable;
 ?>