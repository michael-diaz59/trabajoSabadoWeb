<?php

include('database.php');
var_dump($_POST);
if(isset($_POST['nombre'])) {
  echo $_POST['nombre'] . ', ' . $_POST['precio'] . ', ' . $_POST['existencia'] . ', ' . $_POST['imagen'] . ', ' . $_POST['medida'];
  $task_nombre = $_POST['nombre'];
  $task_precio = $_POST["precio"];
  $task_existencia = $_POST['existencia'];
  $task_imagen = $_POST['imagen'];
  $task_medida = $_POST['medida'];
  $query = "INSERT INTO productos (nombre, precio, existencia, imagen, medida) VALUES ('$task_nombre', $task_precio, $task_existencia, '$task_imagen', '$task_medida')";
  #$query = "INSERT INTO tarea (nombre, descripcion) VALUES ('$task_nombre', '$task_description')";
  $result = mysqli_query($connection, $query);

  if (!$result) {
    die('Query Failed.');
  }

  echo "product Added Successfully";  

}

?>