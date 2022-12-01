<?php

require_once('dbconnect.php');
require_once('QuestionAccessor.php');
require_once(__DIR__ . '/../entity/Quiz.php');

class QuizAccessor {

   
    
    public function getQuizByID($quizID) {
        $result = null;
        $stmt = null;
        try {
            $conn = connect_db();
            $stmt = $conn->prepare("select * from Quiz where quizID = :quizID");
            $stmt->bindParam(":quizID", $quizID);
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($dbresults) !== 1) {
                throw new Exception("duplicate quizIDs found in Quiz table!");
            }

            $dbquiz = $dbresults[0];
            $questionAcc = new QuestionAccessor();
            $quizTitle = $dbquiz["quizTitle"];
            $questions = $questionAcc->getQuestionsForQuiz($quizID);
            $points = $this->getPointsForQuiz($quizID);
            $result = new Quiz($quizID, $quizTitle, $questions, $points);
        } catch (Exception $e) {
            $result = null;
        } finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }

        return $result;
    }

    public function getAllQuizzes() {
        $results = [];
        $stmt = null;
        try {
            $conn = connect_db();
            $stmt = $conn->prepare("select * from Quiz");
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $questionAcc = new QuestionAccessor();

            foreach ($dbresults as $r) {
                $quizID = $r["quizID"];
                $quizTitle = $r['quizTitle'];
                $questions = $questionAcc->getQuestionsForQuiz($quizID);
                $points = $this->getPointsForQuiz($quizID);
                $obj = new Quiz($quizID, $quizTitle, $questions, $points);
                array_push($results, $obj);
            }
        } catch (Exception $e) {
            $results = [];
        } finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }

        return $results;
    }

    private function getPointsForQuiz($quizID) {
        $points = [];
        $stmt = null;
        try {
            $conn = connect_db();
            $stmt = $conn->prepare("select points from QuizQuestion where quizID = :quizID");
            $stmt->bindParam(":quizID", $quizID);
            $stmt->execute();
            $dbpoints = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $points = [];
            foreach ($dbpoints as $p) {
                array_push($points, intval($p));
            }
        } catch (Exception $e) {
            $points = [];
        } finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }

        return $points;
    }

}
