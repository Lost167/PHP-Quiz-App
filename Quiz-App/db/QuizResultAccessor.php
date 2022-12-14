<?php

require_once('dbconnect.php');
require_once('QuizAccessor.php');
require_once(__DIR__ . '/../entity/QuizResult.php');
require_once(__DIR__ . '/../utils/ChromePhp.php');

class QuizResultAccessor {

    public function getResultsByScore($scoremin, $scoremax) {
        $queryString = "SELECT *  FROM `quizresult` WHERE scoreNumerator / scoreDenominator * 100 between " . $scoremin . " and " . $scoremax;
        return $this->getResultsByQuery($queryString);
    }
    
    public function getResultsByDate($startDate, $endDate) {
        $queryString = "SELECT *  FROM `quizresult` WHERE " . $startDate . " < cast(quizStartTime as DATE) and " . $endDate . " > cast(quizEndTime as DATE)";
        return $this->getResultsByQuery($queryString);
    }

    public function getResultsByQuery($query) {
        $results = [];
        $stmt = null;
        $dbresults = null;
        try {
            $conn = connect_db();
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ChromePhp::log("Got the results: " . count($dbresults));
        } catch (Exception $e) {
            $results = [];
        } finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }

        $quizAcc = new QuizAccessor();

        foreach ($dbresults as $r) {
            $quiz = $quizAcc->getQuizByID($r["quizID"]);
            $resultID = $r["resultID"];
            $username = $r["username"];
            $quizStartTime = $r["quizStartTime"];
            $quizEndTime = $r["quizEndTime"];
            $userAnswers = $r["userAnswers"];
            $scoreNumerator = $r["scoreNumerator"];
            $scoreDenominator = $r["scoreDenominator"];
            $obj = new QuizResult($resultID, $quiz, $username, $quizStartTime, $quizEndTime, $userAnswers, $scoreNumerator, $scoreDenominator);
            array_push($results, $obj);
        }

        return $results;
    }

    public function getAllResults() {
        return $this->getResultsByQuery("select * from QuizResult");
    }

    public function getResultsByUser($username) {
        return $this->getResultsByQuery("select * from QuizResult where username = '" . $username . "'");
    }

}
