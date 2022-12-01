<?php

require_once 'dbconnect.php';
require_once(__DIR__ . '/../entity/Question.php');
require_once('TagAccessor.php');
require_once(__DIR__ . '/../entity/Tag.php');

class QuestionAccessor {

    public function getQuestionsForQuiz($quizID) {
        $results = [];
        try {
            $conn = connect_db();
            $stmt = $conn->prepare("select questionID, questionText, choices, answer from QuizQuestion join Question using(questionID) where quizID = :quizID");
            $stmt->bindParam(":quizID", $quizID);
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $ta = new TagAccessor();

            foreach ($dbresults as $r) {
                $questionID = $r["questionID"];
                $questionText = $r["questionText"];
                $choices = explode("|", $r["choices"]);
                $answer = intval($r["answer"]);
                $tags = $ta->getTagsForQuestion($r["questionID"]);
                $obj = new Question($questionID, $questionText, $choices, $answer, $tags);
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

}
