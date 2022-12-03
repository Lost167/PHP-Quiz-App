<?php

require_once(__DIR__ . '/../db/UserAccessor.php');
require_once (__DIR__ . '/../entity/User.php');


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
}

function doGet() {
    try {
        $ua = new UserAccessor();
        $results = $ua->getAllUsers();
        $resultsJson = json_encode($results, JSON_NUMERIC_CHECK);
        echo $resultsJson;
    } catch (Exception $e) {
        echo "ERROR " . $e->getMessage();
    }
}

function doPost() {
    if (isset($_GET['username'])) { 
        // The details of the item to insert will be in the request body.
        $body = file_get_contents('php://input');
        $contents = json_decode($body, true);

        // create a MenuItem object
        $UserObject = new User($contents['username'], $contents['password'], 'USER');

        // add the object to DB
        $mia = new UserAccessor();
        $success = $mia->addNewUser($UserObject);
        echo $success;
    } else {
        // Bulk inserts not implemented.
        ChromePhp::log("Sorry, bulk inserts not allowed!");
    }
}
