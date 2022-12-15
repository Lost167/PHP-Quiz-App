<?php

require_once(__DIR__ . '/../db/QuizAccessor.php');
require_once (__DIR__ . '/../entity/Question.php');
require_once (__DIR__ . '/../entity/Quiz.php');
require_once (__DIR__ . '/../utils/ChromePhp.php');

/*
 * Important Note:
 * 
 * Even if the method is not GET, the $_GET array will still contain any
 * parameters defined in the ".htaccess" file. The syntax "?key=value" is 
 * interpreted as a GET parameter and therefore stored in the $_GET array.
 */

$method = $_SERVER['REQUEST_METHOD'];

if ($method === "GET") {
    doGet();
} else if ($method === "POST") {
    doPost();
} else if ($method === "DELETE") {
    doDelete();
} else if ($method === "PUT") {
    doPut();
}

function doGet() {
    if (isset($_GET["quizID"])) {
        
        // ???
        
    } else {
// get all quizzes
        try {
            $qa = new QuizAccessor();
            $results = $qa->getAllQuizzes();
            $resultsJson = json_encode($results, JSON_NUMERIC_CHECK);
            echo $resultsJson;
        } catch (Exception $e) {
            echo "ERROR " . $e->getMessage();
        }
    }
}

// aka CREATE
function doPost() {
    if (isset($_GET['customerNumber'])) { 
        // The details of the item to insert will be in the request body.
        $body = file_get_contents('php://input');
        $contents = json_decode($body, true);

        // create a quiz object
        //$quizObj = new Quiz($contents['quizID'], $contents['quizTitle']);
        $quizObj = new QuizQuestion($contents['quizID'], $contents['quizTitle'], );

        // add the object to DB
        $mia = new CustomerAccessor();
        $success = $mia->insertItem($quizObj);
        echo $success;
    } else {
        // Bulk inserts not implemented.
        ChromePhp::log("Sorry, bulk inserts not allowed!");
    }
}
