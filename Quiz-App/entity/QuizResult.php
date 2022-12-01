<?php

class QuizResult implements JsonSerializable {

    private $resultID;
    private $quiz;
    private $username;
    private $quizStartTime;
    private $quizEndTime;
    private $userAnswers;
    private $scoreNumerator;
    private $scoreDenominator;

    public function __construct($resultID, $quiz, $username, $quizStartTime, $quizEndTime, $userAnswers, $scoreNumerator, $scoreDenominator) {
        $this->resultID = $resultID;
        $this->quiz = $quiz;
        $this->username = $username;
        $this->quizStartTime = $quizStartTime;
        $this->quizEndTime = $quizEndTime;
        $this->userAnswers = $userAnswers;
        $this->scoreNumerator = $scoreNumerator;
        $this->scoreDenominator = $scoreDenominator;
    }

    public function getResultID() {
        return $this->resultID;
    }

    public function getQuiz() {
        return $this->quiz;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getQuizStartTime() {
        return $this->quizStartTime;
    }

    public function getQuizEndTime() {
        return $this->quizEndTime;
    }

    public function getUserAnswers() {
        return $this->userAnswers;
    }

    public function getScoreNumerator() {
        return $this->scoreNumerator;
    }

    public function getScoreDenominator() {
        return $this->scoreDenominator;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }

}
