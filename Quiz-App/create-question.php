<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Create Question</title>
        <link rel="icon" type="image/x-icon" href="images/logo/favicon.ico">
        <script src="questionCreation.js"></script>
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
        <!-- | Create Question | -->
        <br>
        <div class="container">
            <div class="float-start" style="width: 30%;">
            <h2>Create a Question:</h2>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">  
                <div class="mb-3">
                    <label for="questionIDInput" class="form-label">Question ID:</label>
                    <input id="questionIDInput" type="text" class="form-control" name="questionID" pattern="QU-\d{3}" value="QU-" required>
                </div>
                <div class="mb-3">
                    <label for="questionTagInput" class="form-label">Question Tag:</label>
                    <input id="questionTagInput" type="password" class="form-control" name="questionTag"  value="" required>
                </div>
                <div class="mb-3">
                    <label for="questionTitleInput" class="form-label">Question Title:</label>
                    <input id="questionTitleInput" type="password" class="form-control" name="questionTitle"  value="" required>
                </div>
                <div class="mb-3">
                    <label for="questionChoiceInput" class="form-label">Number of Choices:</label>
                    <input id="questionChoiceInput" type="number" class="form-control" name="numberOfChoices" onchange="numberOfQuestions()" value="4" min="3" max="8" required>
                </div>
                <div class="mb-3">
                    <label for="questionAnswerInput" class="form-label">Answer:</label>
                    <input id="questionAnswerInput" type="password" class="form-control" name="questionAnswer"  value="" required>
                </div>
            </div>
            <div class="float-end" style="width: 60%;">
                <div class="form-group questionText">
                    Number of Choices here!
                </div>
                <button id="submitButton" class="btn btn-outline-primary" type="submit">Submit</button>
                <button id="cancelButton" class="btn btn-outline-danger" type="submit">Cancel</button>
            </div>
            </form>
        </div>
    </body>
</html>
