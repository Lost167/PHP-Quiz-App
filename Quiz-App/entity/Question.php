<?php

class Question implements JsonSerializable {

    private $questionID;
    private $questionText;
    private $choices; // array of strings
    private $answer;
    private $tags; // array of Tag objects.

    public function __construct($questionID, $questionText, $choices, $answer, $tags) {
        $this->questionID = $questionID;
        $this->questionText = $questionText;
        $this->choices = $choices;
        $this->answer = $answer;
        $this->tags = $tags;
    }

    public function getQuestionID() {
        return $this->questionID;
    }

    public function getQuestionText() {
        return $this->questionText;
    }

    public function getChoices() {
        return $this->choices;
    }

    public function getAnswer() {
        return $this->answer;
    }

    public function getTags() {
        return $this->tags;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }

}
