<?php
session_start();

require_once("clase.php");

$usar_db = new DBControl();

if(!empty($_GET["accion"])) 
{
switch($_GET["accion"]) 
{
	case "agregar":
		if(!empty($_POST["txtcantidad"])) 
		{
			$codproducto = $usar_db->vaiQuery("SELECT * FROM productos WHERE id='" . $_GET["id"] . "'");
			$items_array = array($codproducto[0]["id"]=>array(
			'vai_id'		=>$codproducto[0]["id"], 
			'vai_nombre'		=>$codproducto[0]["nombre"], 
			'txtcantidad'	=>$_POST["txtcantidad"], 
			'vai_precio'		=>$codproducto[0]["precio"],
			'vai_existencia'		=>$codproducto[0]["existencia"],
			'vai_medida'		=>$codproducto[0]["medida"],
			'vai_imagen'		=>$codproducto[0]["imagen"]
			));
			
			if(!empty($_SESSION["items_carrito"])) 
			{
				if(in_array($codproducto[0]["id"],
				array_keys($_SESSION["items_carrito"]))) 
				{
					foreach($_SESSION["items_carrito"] as $i => $j) 
					{
							if($codproducto[0]["id"] == $i) 
							{
								if(empty($_SESSION["items_carrito"][$i]["txtcantidad"])) 
								{
									$_SESSION["items_carrito"][$i]["txtcantidad"] = 0;
								}
								$_SESSION["items_carrito"][$i]["txtcantidad"] += $_POST["txtcantidad"];
							}
					}
				} else 
				{
					$_SESSION["items_carrito"] = array_merge($_SESSION["items_carrito"],$items_array);
				}
			} 
			else 
			{
				$_SESSION["items_carrito"] = $items_array;
			}
		}
	break;
	case "eliminar":
		if(!empty($_SESSION["items_carrito"])) 
		{
			foreach($_SESSION["items_carrito"] as $i => $j) 
			{
				if($_GET["eliminarid"] == $i)
				{
					unset($_SESSION["items_carrito"][$i]);	
				}			
				if(empty($_SESSION["items_carrito"]))
				{
					unset($_SESSION["items_carrito"]);
				}
			}
		}
	break;
	case "vacio":
		unset($_SESSION["items_carrito"]);
	break;	
	case "pagar":
	echo "<script> alert('Gracias por su compra - VaidrollTeam');window.location= 'index2.php' </script>";
		unset($_SESSION["items_carrito"]);
	
	break;	
}
}
?>
<html>
<meta charset="UTF-8">
<head>
<title>VaidrollTeam</title>
<link href="style.css" rel="stylesheet" />
</head>
<body>
<div align="center"><h1>Carrito de compras</h1></div>
<div>
<div><h2>Lista de productos a comprar.</h2></div>


<?php
if(isset($_SESSION["items_carrito"]))
{
    $totcantidad = 0;
    $totprecio = 0;
?>	

<table>
<tr>
<th style="width:30%">Descripción</th>
<th style="width:10%">Código</th>
<th style="width:10%">Cantidad</th>
<th style="width:10%">Precio x unidad</th>
<th style="width:10%">Precio</th>
<th style="width:10%"><a href="index2.php?accion=vacio">Limpiar</a></th>
</tr>	
<?php		
    foreach ($_SESSION["items_carrito"] as $item){
        $item_price = $item["txtcantidad"]*$item["vai_precio"];
		?>
				<tr>
				<td><img src="<?php echo $item["vai_imagen"]; ?>" class="imagen_peque" /><?php echo $item["vai_nombre"]; ?></td>
				<td><?php echo $item["vai_id"]; ?></td>
				<td><?php echo $item["txtcantidad"]; ?></td>
				<td><?php echo "$ ".$item["vai_precio"]; ?></td>
				<td><?php echo "$ ". number_format($item_price,2); ?></td>
				<td><a href="index2.php?accion=eliminar&eliminarid=<?php echo $item["vai_id"]; ?>">Eliminar</a></td>
				</tr>
				<?php
				$totcantidad += $item["txtcantidad"];
				$totprecio += ($item["vai_precio"]*$item["txtcantidad"]);
		}
		?>

<tr style="background-color:#f3f3f3">
<td colspan="2"><b>Total de productos:</b></td>
<td><b><?php echo $totcantidad; ?></b></td>
<td colspan="2"><strong><?php echo "$ ".number_format($totprecio, 2); ?></strong></td>
<td><a href="index2.php?accion=pagar">Pagar</a></td>
</tr>

</table>		
  <?php
} else {
?>
<div align="center"><h3>¡El carrito esta vacío!</h3></div>

<?php
}
?>
</div>

<div>
<div><h2>Productos</h2></div>
<div class="contenedor_general">
	<?php
	$productos_array = $usar_db->vaiquery("SELECT * FROM productos ORDER BY id ASC");
	if (!empty($productos_array)) 
	{ 
		foreach($productos_array as $i=>$k)
		{
	?>
		<div class="contenedor_productos">
			<form method="POST" action="index2.php?accion=agregar&id=
			<?php echo $productos_array[$i]["id"]; ?>">
			<div><img src="<?php echo $productos_array[$i]["imagen"]; ?>"></div>
			<div>
			<div style="padding-top:20px;font-size:18px;"><?php echo $productos_array[$i]["nombre"]; ?></div>
			<div style="padding-top:10px;font-size:20px;"><?php echo "$".$productos_array[$i]["precio"]; ?></div>
			<div><input type="text" name="txtcantidad" value="1" size="2" /><input type="submit" value="agregar" />
			</div>
			</div>
			</form>
		</div>
	<?php
		}
	}
	?>
	<button onclick="location.href='/pag2/index.html'">crud</button>
</div>
</body>
</html>