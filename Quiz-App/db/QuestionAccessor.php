<?php

require_once 'dbconnect.php';
require_once(__DIR__ . '/../entity/Question.php');
require_once('TagAccessor.php');
require_once('QuestionTagAccessor.php');
require_once(__DIR__ . '/../entity/Tag.php');
require_once(__DIR__ . '/../entity/QuestionTag.php');

class QuestionAccessor {
    private $getByIDStatementString = "select * from Question where itemID = :itemID";
    private $deleteStatementString = "delete from Question where questionID = :questionID";
    private $insertStatementString = "insert INTO Question values (:questionID, :questionText, :choices, :answer)";
   
    private $updateStatementString = "update Question set questionText = :questionText, choices = :choices, answer = :answer where questionID = :questionID";
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
        
        $tagID = $item->getTags();
        echo $tagID. " <br> ";
        
        try {
            $this->insertStatement->bindParam(":questionID", $questionID);
            $this->insertStatement->bindParam(":questionText", $questionText); 
            $this->insertStatement->bindParam(":choices", $choices);
            $this->insertStatement->bindParam(":answer", $ans);
            
            $success = $this->insertStatement->execute();
            
            $questionTag = new QuestionTag($questionID, $tagID);
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
    
    //by bharati
    public function updateItem($item) {
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
        
        $tagID = $item->getTags();
        echo $tagID. " <br> ";
        
        try {
            $this->updateStatement->bindParam(":questionID", $questionID);
            $this->updateStatement->bindParam(":questionText", $questionText);
            $this->updateStatement->bindParam(":choices", $choices);
            $this->updateStatement->bindParam(":answer", $ans);
            
            $success = $this->updateStatement->execute();
            
            $questionTag = new QuestionTag($questionID, $tagID);
            $qta->updateQuestionTag($questionTag);
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
