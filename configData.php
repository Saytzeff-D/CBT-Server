<?php
    require_once 'index.php';
    $_POST = json_decode(file_get_contents('php://input'));
    $_FILES = json_decode(file_get_contents('php://input'));
    if($_POST->type == 'registration'){
        $registrationNumber = $_POST->regNum;
		$fname = $_POST->Fname;
		$middleName = $_POST->Mname;
		$lname = $_POST->Lname;
		$DOB = $_POST->dateOfBirth;
		$email = $_POST->Email;
		$phoneNum = $_POST->Phone_num;
        $candImage = $_POST->candPic;
        $regCand = new InsertMyData;
        $regCand->insertCand($registrationNumber, $fname, $middleName, $lname, $DOB, $email, $phoneNum, $candImage);
    }
    elseif($_POST->type == 'candSubject'){
        $candidate_id = $_POST->id;
		$sub_1 = $_POST->subject1;
		$sub_2 = $_POST->subject2;
		$sub_3 = $_POST->subject3;
		$sub_4 = $_POST->subject4;
        $setCandSubj = new InsertMyData;
        $setCandSubj->insertCandSubj($candidate_id, $sub_1, $sub_2, $sub_3, $sub_4);
    }
    elseif($_POST->type == 'regslip'){
        $id = $_POST->id;
        $getMyRegSlip = new FetchMeData;
        $getMyRegSlip->regSlip($id);
    }
    elseif($_POST->type == 'login'){
        $regNum = $_POST->reg_num;
        $pword = $_POST->pword;
        $candLogin = new Login;
        $candLogin->loginCandidate($regNum, $pword);
    }
    elseif($_POST->type == 'validate'){
        $id = $_POST->id;
        $validCand = new Login;
        $validCand->validateCandidate($id);
    }
    elseif($_POST->type == 'welcome'){
        $id = $_POST->id;
        $welcomeCand = new FetchMeData;
        $welcomeCand->regSlip($id);
    }
    elseif($_POST->type == 'candQuestion'){
        $candId = $_POST->id;
        $myQuest = new FetchMeData;
        $myQuest->allQuestions($candId);
    }
    elseif($_POST->type == 'savedQuestion'){
        $questId = $_POST->question_id;
        $candId = $_POST->candidate_id;
        $subj = $_POST->subject;
        $quest = $_POST->question;
        $opt1 = $_POST->option_1;
        $opt2 = $_POST->option_2;
        $opt3 = $_POST->option_3;
        $opt4 = $_POST->option_4;
        $crtOpt = $_POST->correct_option;
        $setSavedQuest = new InsertMyData;
        $setSavedQuest->insertSavedQuestion($questId, $candId, $subj, $quest, $opt1, $opt2, $opt3, $opt4, $crtOpt);
     }
     elseif($_POST->type == 'setSavedCrtOption'){
         $opt = $_POST->option;
         $questId = $_POST->questionId;
         $candidateId = $_POST->candId;
         $setOption = new InsertMyData;
         $setOption->updateSavedOption($opt, $questId, $candidateId);
     }
     elseif($_POST->type == 'submit'){
        $candId = $_POST->id;
		$firstScore = $_POST->score1;
		$secondScore = $_POST->score2;	
		$thirdScore = $_POST->score3;
		$fourthScore = $_POST->score4;
        $totalScore = $_POST->aggregate;
        $setCandResult = new InsertMyData;
        $setCandResult->insertCandidateScore($candId, $firstScore, $secondScore, $thirdScore, $fourthScore, $totalScore);
     }
     elseif($_POST->type == 'setQuestion'){
        $subject = $_POST->subject;
		$question = $_POST->question;
		$optA = $_POST->optA;
		$optB = $_POST->optB;
		$optC = $_POST->optC;
		$optD = $_POST->optD;
        $correctOpt = $_POST->correctOpt;
        $setMyQuestion = new InsertMyData;
        $setMyQuestion->questionBank($subject, $question, $optA, $optB, $optC, $optD, $correctOpt);
     }
     elseif($_POST->type == 'viewAllResult'){
         $viewAll = new FetchMeData;
         $viewAll->allCandidateResult();
     }
    else{
        echo json_encode($_POST);
    }
    // $ori_fname = $_FILES['file']['name'];
    // echo json_encode($_POST);
    // $actual_fname = $_FILES['file']['name'];
    // if(move_uploaded_file($_FILES['file']['tmp_name'],'C:/Users/YEMISHOT/Desktop/Angular Class/stock-app/src/assets/' . $actual_fname)){
    //     echo json_encode('Uploaded');
    // }
    // else{
    //     echo json_encode('Failed');
    // }
?>
