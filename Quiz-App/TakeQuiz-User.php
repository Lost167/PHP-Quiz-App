<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login Page</title>
        <link rel="icon" type="image/x-icon" href="images/logo/favicon.ico">
        <link rel="stylesheet" href="quizcss.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    </head>
    <body>
        <!-- | Navbar | -->
        <nav class="navbar navbar-light bg-light">
            <div class="container-fluid">
                <div class="col-lg-3">
                    <a class="navbar-brand" href="index.php">
                        <img src="images/logo/logo.png" alt="" width="65" height="65">
                    </a>
                </div>
                <div class="col-lg-3">
                    <h1>Quiz App</h1>
                </div>
                <div class="col-lg-3">
                    <div id="logIn" class="d-flex float-end">
                        <a id="loginButton" class="btn btn-outline-secondary me-3" href="login-php.php">Log in</a>
                        <a id="createAccountButton"  class="btn btn-outline-secondary me-3" href="signup-php.php">Sign up</a>
                    </div>
                </div>
            </div>
        </nav>
        <br>
        <!--|Would use PHP echo to have name!|-->
        <div class="question-title">
            <div class="row">
                <h2 class="col-lg-4">TEST QUIZ NAME</h2>
                <div class="quiz-timer col-lg-2"><p>THIS IS THE TIMER</p></div>
            </div>
        </div>
        <div class="question question-box col-lg-3 float-start">
            <div class="row">
                <div class="">
                    <!--|Load Questions here|-->
                    <p>QUESTION 1</p>
                    <p>QUESTION 2</p>
                </div>
            </div>
        </div>
        <div class="question question-container col-lg-7 float-start">
            <div class="row">
                <div class="">
                    <!--|Current Question gets loaded here|-->
                    <p>TEMP TEMP TEMP TEMP</p>
                </div>
            </div>
        </div>
    </body>
</html>
