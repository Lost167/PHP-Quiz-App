<?php

//require_once 'dbconnect.php';
require_once 'ConnectionManager.php';
require_once(__DIR__ . '/../entity/Question.php');
require_once('TagAccessor.php');
require_once('QuestionTagAccessor.php');
require_once(__DIR__ . '/../entity/Tag.php');
require_once(__DIR__ . '/../entity/QuestionTag.php');

class QuestionAccessor {
//Question($contents['questionID'], $contents['questionText'], $contents['choices'], $contents['answer'], $contents['tags']);
    private $getByIDStatementString = "select * from Question where itemID = :itemID";
    private $deleteStatementString = "delete from Question where questionID = :questionID";
    //private $insertStatementString = "insert INTO Question values (:questionID, :questionText, :choices, :answer, :tags)";
    private $insertStatementString = "insert INTO Question values (:questionID, :questionText, :choices, :answer)";
   
    private $updateStatementString = "update MenuItem set itemCategoryID = :itemCategoryID, description = :description, price = :price, vegetarian = :vegetarian where itemID = :itemID";
    private $conn = NULL;
    private $getByIDStatement = NULL;
    private $deleteStatement = NULL;
    private $insertStatement = NULL;
    private $updateStatement = NULL;

    // Constructor will throw exception if there is a problem with ConnectionManager,
    // or with the prepared statements.
    public function __construct() {
        $cm = new ConnectionManager();

        $this->conn = $cm->connect_db();
        if (is_null($this->conn)) {
            throw new Exception("no connection");
        }
        $this->getByIDStatement = $this->conn->prepare($this->getByIDStatementString);
        if (is_null($this->getByIDStatement)) {
            throw new Exception("bad statement: '" . $this->getAllStatementString . "'");
        }

        $this->deleteStatement = $this->conn->prepare($this->deleteStatementString);
        if (is_null($this->deleteStatement)) {
            throw new Exception("bad statement: '" . $this->deleteStatementString . "'");
        }

        $this->insertStatement = $this->conn->prepare($this->insertStatementString);
        if (is_null($this->insertStatement)) {
            throw new Exception("bad statement: '" . $this->getAllStatementString . "'");
        } 
//        else {
//            $this->insertStatement22 = $this->conn->prepare($this->sql2);
//            echo 'data submitted successfully';
//        }

        $this->updateStatement = $this->conn->prepare($this->updateStatementString);
        if (is_null($this->updateStatement)) {
            throw new Exception("bad statement: '" . $this->updateStatementString . "'");
        }
    }

    
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
    
    //by bharati
    public function getAllQuestions() {
        $results = [];
        $cm = new ConnectionManager();

        try {
            $conn = $cm->connect_db();
            $stmt = $conn->prepare("select * from question");
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $ta = new TagAccessor();
            //$qta = new QuestionTagAccessor();
            
            foreach ($dbresults as $r) {
                $questionID = $r["questionID"];
                $questionText = $r['questionText'];
                $choices = explode("|", $r["choices"]);
                $answer = intval($r['answer']);
                
                $tags = $ta->getTagsForQuestion($r["questionID"]);
                
                //$questionTag = new QuestionTag($questionID, $tagID);
                //$qta->insertQuestionTag($questionTag);
                //$tags = $qta->getTagsForQuestion($questionID);
                
                //print_r($tags);

                
                //$tag = json_encode($tags, JSON_NUMERIC_CHECK);
                
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
    
    /**
     * Inserts a menu item into the database.
     * 
     * @param MenuItem $item an object of type MenuItem
     * @return boolean indicates if the item was inserted
     */
    public function insertItem($item) {
        $success;
//        $conn = connect_db();
        $questionID = $item->getQuestionID();
        $questionText = $item->getQuestionText();
        $choices1 = $item->getChoices();
        
        print_r ($choices1); 
        echo "hi .$questionText.....";
        $a = count($choices1);
        echo "$a";
        $choices = implode ("|",$choices1); 
        echo join ("|",$choices1). " <br> ";  
        
        $answer = $item->getAnswer();
        //need to convert answer string to number 
        for ($i = 0; $i < count($choices1); $i++) {
            if($choices1[$i] == $answer){
                $ans = $i;
            }
        }
        
        $qta = new QuestionTagAccessor();
        
        $tagID = $item->getTags();
        echo $tagID. " <br> ";
        
        try {
            $this->insertStatement->bindParam(":questionID", $questionID);
            $this->insertStatement->bindParam(":questionText", $questionText); 
            $this->insertStatement->bindParam(":choices", $choices);
            $this->insertStatement->bindParam(":answer", $ans);
            //$this->insertStatement->bindParam(":tags", $tags);
            //$this->insertStatement22->bindParam(":tagID", $tagID);
            $success = $this->insertStatement->execute();
            
            //$tags = $ta->getTagsForQuestion($r["questionID"]);
//            foreach ($tags as $tag) {
//                $questionTag = new QuestionTag($questionID, $tag->getTagID());
//                //$questionTag = new QuestionTag($questionID, $tag);
//                $qta->insertQuestionTag($questionTag);
//                //$success = $qta->insertQuestionTag($questionTag);
//            }
         $questionTag = new QuestionTag($questionID, $tagID);
//                //$questionTag = new QuestionTag($questionID, $tag);
                $qta->insertQuestionTag($questionTag);
            
        }
        catch (PDOException $e) {
            $success = false;
        }
        finally {
            if (!is_null($this->insertStatement)) {
                $this->insertStatement->closeCursor();
            }
            return $success;
        }
    }
    
    //by Bharati
    public function deleteItem($item) {
        $success;

        $questionID = $item->getQuestionID(); // only the ID is needed

        try {
            $this->deleteStatement->bindParam(":questionID", $questionID);
            $success = $this->deleteStatement->execute();
        }
        catch (PDOException $e) {
            $success = false;
        }
        finally {
            if (!is_null($this->deleteStatement)) {
                $this->deleteStatement->closeCursor();
            }
            return $success;
        }
    }

}
