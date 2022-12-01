<?php

require_once(__DIR__ . '/../db/QuizResultAccessor.php');

/*
 * Important Note:
 * 
 * We know that $_GET["scoremin"] $_GET["scoremin"] and  exists because .htaccess creates it.
 */

$scoremin = $_GET["scoremin"];
$scoremax = $_GET["scoremax"];
try {
    $qra = new QuizResultAccessor();
    $results = $qra->getResultsByScore($scoremin, $scoremax);
    $resultsJson = json_encode($results, JSON_NUMERIC_CHECK);
    echo $resultsJson;
} catch (Exception $e) {
    echo "ERROR " . $e->getMessage();
}