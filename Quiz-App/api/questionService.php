<?php

require_once(__DIR__ . '/../db/QuestionAccessor.php');
require_once(__DIR__ . '/../db/QuestionTagAccessor.php');
require_once(__DIR__ . '/../db/TagAccessor.php');
require_once (__DIR__ . '/../entity/Question.php');
require_once (__DIR__ . '/../entity/Tag.php');
require_once(__DIR__ . '/../entity/QuestionTag.php');
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
    // url = "menuService/categories" ==> get all categories
    if (!isset($_GET['questionID'])) {
        try {
            $qa = new QuestionAccessor();
            
            $results = $qa->getAllQuestions();
            //$results = $qa->getQuestionsForQuiz(questionID);
            $resultsJson = json_encode($results, JSON_NUMERIC_CHECK);
            echo $resultsJson;
        }
        catch (Exception $e) {
            echo "ERROR" . $e->getMessage();
        }
    }
    // url = "menuService/categories/XXX" where XXX is an tagID  ==> get just the category with the matching ID
    else {
        ChromePhp::log($_GET['questionID']);
    }
}

// aka CREATE
function doPost() {
    if (isset($_GET['questionID'])) { 
        // The details of the item to insert will be in the request body.
        $body = file_get_contents('php://input');
        $contents = json_decode($body, true);

        // create a quiz object
        //$quizObj = new Quiz($contents['quizID'], $contents['quizTitle']);
        $questionObj = new Question($contents['questionID'], $contents['questionText'], $contents['choices'], $contents['answer'], $contents['tags']);

        // add the object to DB
        $mia = new QuestionAccessor();
        $success = $mia->insertItem($questionObj);
        echo $success;
    } else {
        // Bulk inserts not implemented.
        ChromePhp::log("Sorry, bulk inserts not allowed!");
    }
}

function doDelete() {
    if (isset($_GET['questionID'])) { 
        $questionID = $_GET['questionID']; 
        // Only the ID of the item matters for a delete,
        // but the accessor expects an object, 
        // so we need a dummy object.
        $questionObj = new Question($questionID, "dummyText", "dummyChoices", -1, "");

        // delete the object from DB
        $mia = new QuestionAccessor();
        $success = $mia->deleteItem($questionObj);
        echo $success;
    } else {
        // Bulk deletes not implemented.
        ChromePhp::log("Sorry, bulk deletes not allowed!");
    }
}

// aka UPDATE
function doPut() {
    if (isset($_GET['questionID'])) { 
        // The details of the item to update will be in the request body.
        $body = file_get_contents('php://input');
        $contents = json_decode($body, true);

        // create a MenuItem object
        $questionObj = new Question($contents['questionID'], $contents['questionText'], $contents['choices'], $contents['answer'], $contents['tags']);

        // update the object in the  DB
        $mia = new QuestionAccessor();
        $success = $mia->updateItem($questionObj);
        echo $success;
    } else {
        // Bulk updates not implemented.
        ChromePhp::log("Sorry, bulk updates not allowed!");
    }
}
