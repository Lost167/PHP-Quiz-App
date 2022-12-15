<?php

class QuestionTag implements JsonSerializable {

    private $questionID;
    private $tagID;

    public function __construct($questionID, $tagID) {
        $this->questionID = $questionID;
        $this->tagID = $tagID;
    }

    public function getQuestionID() {
        return $this->questionID;
    }

    public function getTagID() {
        return $this->tagID;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }

}
