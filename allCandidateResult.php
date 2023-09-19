<?php 
	require 'connect.php';
		$sql = "SELECT candidate_id, first_name, middle_name, last_name, reg_num, subject_2, subject_3, subject_4, aggregate, status FROM candidate JOIN subjectcombination USING(candidate_id) JOIN results USING(candidate_id) ORDER BY aggregate DESC";
		$maxSql = "SELECT MAX(aggregate) highestScore FROM results";
		$minSql = "SELECT MIN(aggregate) lowestScore FROM results";
		$allResult = $connection->query($sql);
		$maxScore = $connection->query($maxSql);
		$minScore = $connection->query($minSql);
		// echo json_encode($allResult->fetch_all(MYSQLI_ASSOC));
	echo json_encode([$allResult->fetch_all(MYSQLI_ASSOC), $maxScore->fetch_assoc(), $minScore->fetch_assoc()]);
 ?>