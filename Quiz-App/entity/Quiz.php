<?php

class Quiz implements JsonSerializable {

    private $quizID;
    private $quizTitle;
    private $questions; // array of Question objects
    private $points; // array of integers

    public function __construct($quizID, $quizTitle, $questions, $points) {
        $this->quizID = $quizID;
        $this->quizTitle = $quizTitle;
        $this->questions = $questions;
        $this->points = $points;
    }

    public function getQuizID() {
        return $this->quizID;
    }

    public function getQuizTitle() {
        return $this->quizTitle;
    }

    public function getQuestions() {
        return $this->questions;
    }

    public function getPoints() {
        return $this->points;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }

}
