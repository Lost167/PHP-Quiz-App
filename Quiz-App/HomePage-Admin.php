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
                    <a id="loginButton" class="btn btn-outline-secondary me-3" href="login-php.php">Log in</a>
                    <a id="createAccountButton" class="btn btn-outline-secondary me-3" href="signup-php.php">Sign up</a>
                </div>
            </div>
        </nav>
        <br>
        
        <div class="container">
            <h1 class="mb-4">Welcome, <?php echo $username; ?>!</h1>
            
            <div class="card border-dark mb-3" id="questionSearch">
                <div class="card-header fs-2">Search for Questions</div>
                <div class="card-body text-secondary">
                    
                </div>
            </div>
            
            <div class="card border-dark mb-3" id="createQuestion">
                <div class="card-header fs-2">Create a Question</div>
                <div class="card-body text-secondary">
                    <a id="createQuestionButton" class="btn btn-outline-secondary me-3" href="create-question.php">Create Question</a>
                </div>
            </div>
            
            <div class="card border-dark mb-3">
                <div class="card-header fs-3">Quiz Search</div>
                <div class="card-body text-secondary">
                    <div class="form-floating">
                        <select class="form-control floatingSelectGrid" id="tagInput"></select>
                        <label for="floatingSelectGrid">Select a Tag</label>
                    </div>
                    <button id="searchButton" class="btn btn-primary" style="margin-top:1em;">Search</button>
                </div>
                <div id="matchingQuizzes"></div>
            </div>
            
            <div class="card border-dark mb-3" id="createQuiz">
                <div class="card-header fs-2">Create a Quiz</div>
                <div class="card-body text-secondary">
                    <a id="createQuestionButton" class="btn btn-outline-secondary me-3" href="create-quiz.php">Create Quiz</a>
                </div>
            </div>

            <div class="card border-dark mb-3" id="userSearch">
                <div class="card-header fs-2">Search Quiz Results by User</div>
                <div class="card-body text-secondary">
                    <form>
                    <div class="form-floating">
                        <select class="form-control floatingSelectGrid" id="userInput"></select>
                        <label for="floatingSelectGrid">Select a User</label>
                    </div>
                    <button id="getResultsButton" class="btn btn-primary" style="margin-top:1em;margin-right:10px;">Get Results</button><button id="getAggregateResultsButton" class="btn btn-primary" style="margin-top:1em;">Get Aggregate Results</button>
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
                    <button id="getScoreResultsButton" class="btn btn-primary" style="margin-top:1em;margin-right:10px;">Get Results</button><button id="getAggregateScoreResultsButton" class="btn btn-primary" style="margin-top:1em;">Get Aggregate Results</button>
                    </form>
                    
                    <div class="quizResults container" style="margin-top:1em;"></div>
                </div>
            </div>
        </div>
    </body>
</html>
