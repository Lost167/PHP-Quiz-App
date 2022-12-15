<?php

class ConnectionManager {

    public function connect_db() {

//       $db = new PDO("mysql:host=localhost;dbname=RestaurantDB", "Bharati", "Bharati");
       $db = new PDO("mysql:host=localhost;dbname=quizappdb", "quizappfinal", "quizappfinal");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    }

}
