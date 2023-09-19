<?php 
	require 'connect.php';
	$_POST = json_decode(file_get_contents('php://input'));
	$status = $_POST->adminResponse;
	$candId = $_POST->id;
	if ($_POST) {
		$sql = "UPDATE results SET status = '$status' WHERE candidate_id = '$candId'";
		$querySql = $connection->query($sql);
		if ($querySql) {
			$fetchUpdatedData = "SELECT status FROM results WHERE candidate_id = '$candId'";
			$queryMyFetch = $connection->query($fetchUpdatedData);
			echo json_encode($queryMyFetch->fetch_assoc());
		}
		else{
			echo "Error";
		}
	}
 ?>