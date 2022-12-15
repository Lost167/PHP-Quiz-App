<?php

//require_once('dbconnect.php');
require_once 'ConnectionManager.php';
require_once('QuestionTagAccessor.php');
require_once('QuestionAccessor.php');
require_once(__DIR__ . '/../entity/Tag.php');
require_once(__DIR__ . '/../entity/QuestionTag.php');
require_once(__DIR__ . '/../entity/Question.php');
require_once (__DIR__ .  '/../utils/ChromePhp.php');

class TagAccessor {

    public function getAllTags() {
        $results = [];
        $cm = new ConnectionManager();

        try {
            $conn = $cm->connect_db();
            $stmt = $conn->prepare("select * from tag");
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dbresults as $r) {
                $tagID = intval($r["tagID"]);
                $tagName = $r['tagName'];
                $tagCategory = $r['tagCategoryName'];
                
                $obj = new Tag($tagID, $tagName, $tagCategory);
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
    
    public function getTagsForQuestion($questionID) {
        $results = [];
        $cm = new ConnectionManager();
        try {
            $conn = $cm->connect_db();
            $stmt = $conn->prepare("select * from QuestionTag join Tag using(tagID) where questionID = :questionID");
            $stmt->bindParam(":questionID", $questionID);
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dbresults as $r) {
                //$obj = new QuestionTag($r["questionID"], intval($r["tagID"]));
                //$obj = new Tag(intval($r["tagID"]), $r["tagName"], $r["tagCategoryName"]);
                $obj = new Tag($r["tagID"], $r["tagName"], $r["tagCategoryName"]);
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
