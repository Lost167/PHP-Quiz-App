<?php

require_once 'dbconnect.php';
require_once(__DIR__ . '/../entity/Question.php');
require_once('TagAccessor.php');
require_once('QuestionAccessor.php');
require_once(__DIR__ . '/../entity/Tag.php');
require_once(__DIR__ . '/../entity/QuestionTag.php');

class QuestionTagAccessor {
    private $getByIDStatementString = "select * from Question where itemID = :itemID";
    private $deleteStatementString = "delete from MenuItem where itemID = :itemID";
    private $insertStatementString = "insert INTO QuestionTag values (:questionID, :tagID)";
   
    private $updateStatementString = "update QuestionTag set tagID = :tagID where questionID = :questionID";
    private $conn = NULL;
    private $getByIDStatement = NULL;
    private $deleteStatement = NULL;
    private $insertStatement = NULL;
    private $updateStatement = NULL;

    // Constructor will throw exception if there is a problem with ConnectionManager,
    // or with the prepared statements.
    public function __construct() {
        $this->conn = connect_db();
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
        $conn = connect_db();
        
        try {
            $stmt = $conn->prepare("select * from question");
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $ta = new TagAccessor();
            
            foreach ($dbresults as $r) {
                $questionID = $r["questionID"];
                $questionText = $r['questionText'];
                $choices = explode("|", $r["choices"]);
                $answer = intval($r['answer']);
                
                $obj = new Question($questionID, $questionText, $choices, $answer, "");
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
        $questionID = $item->getQuestionID();
        $questionText = $item->getQuestionText();
         
        $choices1 = $item->getChoices();
        $choices = implode ("|",$choices1); 
        
        $answer = $item->getAnswer();
        //need to convert answer string to number 
        for ($i = 0; $i < count($choices1); $i++) {
            if($choices1[$i] == $answer){
                $ans = $i;
            }
        }
        
        $qta = new QuestionTagAccessor();
        
        $tags = $item->getTags();
        
        try {
            $this->insertStatement->bindParam(":questionID", $questionID);
            $this->insertStatement->bindParam(":questionText", $questionText); 
            $this->insertStatement->bindParam(":choices", $choices);
            $this->insertStatement->bindParam(":answer", $ans);
            $this->insertStatement->bindParam(":tags", $tags);
            
            $success = $this->insertStatement->execute();
            
            foreach ($tags as $tag) {
                $questionTag = new QuestionTag($questionID, $tag->getTagID());
                $qta->insertQuestionTag($questionTag);
            }
           
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
    
    //bby Bharati
    public function insertQuestionTag($item) {
        $success;
        $questionID = $item->getQuestionID();
        $tagID = $item->getTagID();
        
        try {
            $this->insertStatement->bindParam(":questionID", $questionID);
            $this->insertStatement->bindParam(":tagID", $tagID); 
          
            $success = $this->insertStatement->execute();
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
    
    
    //By bharati
    public function getTagsForQuestion($questionID) {
        $results = [];
        
        try {
            $stmt = $this->conn->prepare("select * from QuestionTag where questionID = :questionID");
            //$stmt = $this->conn->prepare("select * from QuestionTag join Tag using(tagID) where questionID = :questionID");
            
            $stmt->bindParam(":questionID", $questionID);
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //$ta = new TagAccessor();
            foreach ($dbresults as $r) {
                //$tagID = intval($r["tagID"]);
                $obj = new QuestionTag($r["questionID"], $r["tagID"]);
                //$obj = new Tag($r["tagID"], $r["tagName"], $r["tagCategoryName"]);
                
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
    public function updateQuestionTag($item) {
        $success;
        $questionID = $item->getQuestionID();
        $tagID = $item->getTagID();
        
        try {
            $this->updateStatement->bindParam(":questionID", $questionID);
            $this->updateStatement->bindParam(":tagID", $tagID); 
          
            $success = $this->updateStatement->execute();
        }
        catch (PDOException $e) {
            $success = false;
        }
        finally {
            if (!is_null($this->updateStatement)) {
                $this->updateStatement->closeCursor();
            }
            return $success;
        }
    }
    
}
