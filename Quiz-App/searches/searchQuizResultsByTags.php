<?php

require_once(__DIR__ . '/../db/QuizAccessor.php');

$tags = explode("|", $_GET["tag"]); // array of tags


        try {
            $qra = new QuizResultAccessor();
            $results = $qra->getResultsByQuery("select * from QuizResult join QuizQuestion using(quizID) join QuestionTag using(questionID) "
                    . "join Tag using (tagID) where tagID = '" . $_GET['tagID'] . "'");
            $resultsJson = json_encode($results, JSON_NUMERIC_CHECK);
            echo $resultsJson;
        } catch (Exception $e) {
            echo "ERROR " . $e->getMessage();
        }
    