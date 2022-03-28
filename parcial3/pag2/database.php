<?php

$connection = mysqli_connect(
  'localhost', 'root', '', 'pagina'
);

// for testing connection
#if($connection) {
#  echo 'database is connected';
#}

?>
