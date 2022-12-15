<?php

require_once(__DIR__ . '/../db/TagAccessor.php');
require_once (__DIR__. '/../utils/ChromePhp.php');

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
}
else if ($method === "POST") {
    doPost();
}
else if ($method === "DELETE") {
    doDelete();
}
else if ($method === "PUT") {
    doPut();
}


function doGet() {
    // url = "menuService/categories" ==> get all categories
    if (!isset($_GET['tagID'])) {
        try {
            $mica = new TagAccessor();
            
            $results = $mica->getAllTags();
            $results = json_encode($results, JSON_NUMERIC_CHECK);
            ChromePhp::log($results);
            echo $results;
        }
        catch (Exception $e) {
            echo "ERROR" . $e->getMessage();
        }
    }
    // url = "menuService/categories/XXX" where XXX is an tagID  ==> get just the category with the matching ID
    else {
        ChromePhp::log($_GET['tagID']);
    }
}


function doDelete() {
}

// aka CREATE
function doPost() {
}

// aka UPDATE
function doPut() {
}













