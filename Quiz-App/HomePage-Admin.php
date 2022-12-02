<?php
        session_start();
        $userInfo = json_decode($_SESSION["currentUser"], true);
        $username = $userInfo["username"];
        $permissionLevel = $userInfo["permissionLevel"];
        ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Home Page - <?php echo $username; ?></title>
        <link rel="icon" type="image/x-icon" href="images/logo/favicon.ico">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <script src="sample.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">
                    <img src="images/logo/logo.png" alt="" width="65" height="65">
                </a>
                <h1>Quiz App</h1>
                <div id="logIn" class="d-flex">
                    <a id="loginButton" class="btn btn-outline-secondary me-3" href="login-js.php">Log in</a>
                    <a id="createAccountButton"  class="btn btn-secondary" href="#">Sign up</a>
                </div>
            </div>
        </nav>
        <br>
        
        <div class="container">
            <h1 class="mb-4">Welcome, <?php echo $username; ?>!</h1>

            <div class="card border-dark mb-3" id="userSearch">
                <div class="card-header fs-2">Search Quiz Results by User</div>
                <div class="card-body text-secondary">
                    <form>
                    <div class="form-floating">
                        <select class="form-control floatingSelectGrid" id="userInput"></select>
                        <label for="floatingSelectGrid">Select a User</label>
                    </div>
                    <button id="getResultsButton" class="btn btn-primary" style="margin-top:1em;">Get Results</button>
                    </form>
                    
                    <div class="quizResults container" style="margin-top:1em;"></div>
                </div>
            </div>

            
            <div class="card border-dark mb-3" id="scoreSearch">
                <div class="card-header fs-2">Search Quiz Results by Results</div>
                <div class="card-body text-secondary">
                    <form>
                    <div class="form-floating">
                        <input type="number" class="form-control floatingInputGrid" id="scoreMin" min="0" max="100">
                        <label for="floatingInputGrid">Min Score</label>
                    </div>
                    <div class="form-floating" style="margin-top:1em;">
                        <input type="number" class="form-control floatingInputGrid" id="scoreMax" min="0" max="100">
                        <label for="floatingInputGrid">Max Score</label>
                    </div>
                    <button id="getScoreResultsButton" class="btn btn-primary" style="margin-top:1em;">Get Results</button>
                    </form>
                    
                    <div class="quizResults container" style="margin-top:1em;"></div>
                </div>
            </div>
        </div>
    </body>
</html>
