<?php

require_once(__DIR__ . '/../db/QuizResultAccessor.php');

/*
 * Important Note:
 * 
 * We know that $_GET["username"] exists because .htaccess creates it.
 */

$username = $_GET["username"];
try {
    $qra = new QuizResultAccessor();
    $results = $qra->getResultsByQuery("select * from QuizResult where username = '" . $_GET['username'] . "'");
    $resultsJson = json_encode($results, JSON_NUMERIC_CHECK);
    echo $resultsJson;
} catch (Exception $e) {
    echo "ERROR " . $e->getMessage();
}
    



