<?php
    header("Access-Control-Allow-Origin:http://localhost:4200");
    header("Access-Control-Allow-Headers:Content-Type");
    class Connection{
        protected $server = 'localhost';
        protected $username = 'root';
        protected $password = '';
        protected $dbName = 'school_db';
        public $connect;
        public function __construct()
        {
            $this->connect = new mysqli($this->server, $this->username, $this->password, $this->dbName);
        }
    }

    class InsertMyData extends Connection{
        public function questionBank($subject, $question, $optA, $optB, $optC, $optD, $correctOpt)
        {
            $sql = "INSERT INTO questions (subject, question, option_1, option_2, option_3, option_4, correct_option) VALUES(?,?,?,?,?,?,?)";
            $prepareMyQuestion = $this->connect->prepare($sql);
            $prepareMyQuestion->bind_param("sssssss", $subject, $question, $optA, $optB, $optC, $optD, $correctOpt);	
			$insertDb = $prepareMyQuestion->execute();
			if ($insertDb) {
				echo json_encode('Sucessful');
			}
			else{
				echo json_encode('Connection Error');
			}
        }
        public function insertCand($registrationNumber, $fname, $middleName, $lname, $DOB, $email, $phoneNum, $candImage)
        {
            $queryInsert = "INSERT INTO candidate (reg_num, first_name, middle_name, last_name, DOB, email, phone_num, profilepics) VALUES(?,?,?,?,?,?,?,?)";
            $prepareInsert = $this->connect->prepare($queryInsert);
            $prepareInsert->bind_param("ssssssss", $registrationNumber, $fname, $middleName, $lname, $DOB, $email, $phoneNum, $candImage);
            $insertCand = $prepareInsert->execute();
            if ($insertCand) {
                $selectId = "SELECT candidate_id FROM candidate WHERE reg_num = '$registrationNumber'";
                $candidate_id = $this->connect->query($selectId);
                echo json_encode(['True', $candidate_id->fetch_assoc()]);
            }
            else{
                echo json_encode('Fail');
            }
        }
        public function insertCandSubj($candidate_id, $sub_1, $sub_2, $sub_3, $sub_4)
        {
            $sql = "INSERT INTO subjectcombination (candidate_id, subject_1, subject_2, subject_3, subject_4) VALUES('$candidate_id', '$sub_1', '$sub_2', '$sub_3', '$sub_4')";
		     $insertDb = $this->connect->query($sql);
            if ($insertDb) {
			   echo json_encode('Success');
		   }
		   else{
			echo json_encode('Failure');
		   }
       }
       public function insertSavedQuestion($questId, $candId, $subj, $quest, $opt1, $opt2, $opt3, $opt4, $crtOpt)
       {
           $sql = "INSERT INTO savedquestion (question_id, candidate_id, savedSubject, question, option_1, option_2, option_3, option_4, correct_option) VALUES (?,?,?,?,?,?,?,?,?)";
           $prepareSql = $this->connect->prepare($sql);
           $prepareSql->bind_param("sssssssss", $questId, $candId, $subj, $quest, $opt1, $opt2, $opt3, $opt4, $crtOpt);
           $querySql = $prepareSql->execute();
           if($querySql){
               echo json_encode('True');
           }
           else{
               echo json_encode('False');
           }
       }
       public function updateSavedOption($opt, $questId, $candidateId)
       {
           $updateQuery = "UPDATE savedquestion SET saved_crt_opt = '$opt' WHERE question_id = '$questId' and candidate_id = '$candidateId'";
           $setUpdate = $this->connect->query($updateQuery);
           if($setUpdate){
               echo json_encode('True');
           }
           else{
               echo json_encode('False');
           }
       }
       public function insertCandidateScore($candId, $firstScore, $secondScore, $thirdScore, $fourthScore, $totalScore)
       {
        $sql = "INSERT INTO results (candidate_id, score_1, score_2, score_3, score_4, aggregate) VALUES('$candId', '$firstScore', '$secondScore', '$thirdScore', '$fourthScore', '$totalScore')";
		$insertDb = $this->connect->query($sql);
		if ($insertDb) {
            $delQuery = "DELETE FROM savedquestion WHERE candidate_id = '$candId'";
            $delSavedQuestion = $this->connect->query($delQuery);
            if($delSavedQuestion){
                echo json_encode('True');
            } else{
                echo json_encode('False');
            }
		} else {
			echo json_encode('False');
		}
       }
        
    }

    class FetchMeData extends Connection{
        public function regSlip($id)
        {
            $sql = "SELECT candidate_id, first_name, reg_num, middle_name, last_name, email, phone_num, DOB, subject_1, subject_2, subject_3, subject_4, profilepics FROM candidate join subjectcombination using(candidate_id) WHERE candidate_id = '$id'";
		$fetchData = $this->connect->query($sql);
		echo json_encode($fetchData->fetch_assoc());
        }
        public function allQuestions($candId)
        {
            $checkQuery = "SELECT * FROM savedquestion WHERE candidate_id = '$candId'";
            $checkSavedQuest = $this->connect->query($checkQuery);
            if($checkSavedQuest->num_rows>0){
                echo json_encode([$checkSavedQuest->fetch_all(MYSQLI_ASSOC), 'SavedQuestion']);
            }
            else{
                $sql = "SELECT * FROM questions";
                $allQuestions = $this->connect->query($sql);
                echo json_encode([$allQuestions->fetch_all(MYSQLI_ASSOC), 'StartQuestion']);
            }
        }
        public function allCandidateResult()
        {
            $sql = "SELECT candidate_id, first_name, middle_name, last_name, reg_num, subject_2, subject_3, subject_4, aggregate, status FROM candidate JOIN subjectcombination USING(candidate_id) JOIN results USING(candidate_id) ORDER BY aggregate DESC";
		   $maxSql = "SELECT MAX(aggregate) highestScore FROM results";
		   $minSql = "SELECT MIN(aggregate) lowestScore FROM results";
		   $allResult = $this->connect->query($sql);
		   $maxScore = $this->connect->query($maxSql);
		   $minScore = $this->connect->query($minSql);
	       echo json_encode([$allResult->fetch_all(MYSQLI_ASSOC), $maxScore->fetch_assoc(), $minScore->fetch_assoc()]);
        }
    }

    class Login extends Connection{
        public function loginCandidate($regNum, $pword)
        {
            $sql = "SELECT applicant_id, surname FROM applicant where exam_num = ? && surname = ?";
            $fetchFromDb = $this->connect->prepare($sql);
            $fetchFromDb->bind_param("ss", $regNum, $pword);
            $fetchFromDb->execute();
            $myArr = $fetchFromDb->get_result();
            $arrDb = $myArr->fetch_assoc();
            if($myArr->num_rows>0){
                echo json_encode($arrDb['applicant_id']);
            }
            else{
                echo json_encode('Login Fail');
            }
        }
        public function validateCandidate($id)
        {
            $sql = "SELECT * FROM results where applicant_id = '$id'";
		$allResult = $this->connect->query($sql);
		if ($allResult->num_rows !== 0) {
			echo json_encode('Candidate has attempted once');
		}
		else{
			echo json_encode('Candidate can go ahead');
		}		
        }
    }
    
?>