<?php

require_once('dbconnect.php');
require_once(__DIR__ . '/../entity/Tag.php');

class TagAccessor {

    public function getAllTags() {
        
    }

    public function getTagsForQuestion($questionID) {
        $results = [];
        try {
            $conn = connect_db();
            $stmt = $conn->prepare("select * from QuestionTag join Tag using(tagID) where questionID = :questionID");
            $stmt->bindParam(":questionID", $questionID);
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dbresults as $r) {
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
