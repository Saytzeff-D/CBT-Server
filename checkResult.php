<?php 
	require 'connect.php';
		$sql = "SELECT * FROM candidate JOIN subjectcombination USING(candidate_id) JOIN results USING(candidate_id)";
		$allResult = $connection->query($sql);
		echo json_encode($allResult->fetch_all(MYSQLI_ASSOC));
 ?>