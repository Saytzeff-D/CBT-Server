<?php 
	require 'connect.php';
	$sql = "SELECT * FROM admin";
	$adminData = $connection->query($sql);
	echo json_encode($adminData->fetch_all(MYSQLI_ASSOC));
 ?>