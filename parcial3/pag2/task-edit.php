<?php

  include('database.php');

if(isset($_POST['id'])) {
  $task_name = $_POST['nombre'];
  $task_precio = $_POST['precio'];
  $task_existencia = $_POST['existencia'];
  $task_imagen = $_POST['imagen'];
  $task_medida = $_POST['medida'];
  $id = $_POST['id'];
  $query = "UPDATE productos SET nombre = '$task_name', precio = $task_precio, existencia = $task_existencia, imagen = '$task_imagen', medida= '$task_medida' WHERE id = '$id'";
  $result = mysqli_query($connection, $query);

  if (!$result) {
    die('Query Failed.');
  }
  echo "product Update Successfully";  

}

?>
