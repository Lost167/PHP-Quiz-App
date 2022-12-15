<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Create Question</title>
        <link rel="icon" type="image/x-icon" href="images/logo/favicon.ico">
        <script src="questionCreation.js"></script>
        <link rel="stylesheet" href="dbDemoStyles.css">
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
            <!--<div class="float-start" style="width: 30%;">-->
            <div>
                <h2>Create a Question:</h2>
                <button id="GetButton">View All Question</button>
                <button id="DeleteButton" disabled>Delete</button>
                <button id="UpdateButton" disabled>Update</button>
                
                <form method="POST" id="frm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">  
                    <div class="mb-3">
                        <label for="questionIDInput" class="form-label">Question ID:</label>
                        <input id="questionID" type="text" class="form-control" name="questionID" pattern="QN-\d{4}" value="QN-" required>
                    </div>
                    <div class="mb-3">
                        <label for="questionTagInput" class="form-label">Question Tag:</label>
                        <select id="categorySelect" class="form-control">

                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="questionTitleInput" class="form-label">Question Text:</label>
                        <input id="questionText" type="text" class="form-control" name="questionText"  value="" required>
                    </div>
                    <div class="mb-3">
                        <label for="questionChoiceInput" class="form-label">Number of Choices:</label>
                        <select id="choices" name="choices" class="form-control">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="mb-3" id="inputChoices">
                        
                    </div>
                    <div class="mb-3">
                        <label for="questionAnswerInput" class="form-label">Answer:</label>
                        <input id="answer" type="text" class="form-control" name="answer"  value="" required><label id="error"></label>
                    </div>
                    <!--            </div>
                                <div class="float-end" style="width: 60%;">-->
                    <!--                <div class="form-group questionText">
                                        Number of Choices here!
                                    </div>-->
                    <button id="DoneButton" class="btn btn-outline-primary" type="submit">Submit</button>
                    <button id="CancelButton" class="btn btn-outline-danger" >Cancel</button>
                </form>
            </div>
        </div>
    
        <table>
            <tr>
                <th>ID</th>
                <th>Question Text</th>
                <th>Choices</th>
                <th>Answer</th>
                <th>Tag</th>
            </tr>
        </table>
</body>
</html>
