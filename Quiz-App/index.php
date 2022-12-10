<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Home Page</title>
        <link rel="icon" type="image/x-icon" href="images/logo/favicon.ico">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <script>
            window.onload = function () {
                document.querySelector("#searchButton").addEventListener("click", doSearch);
                getTags();
            };

            function getTags() {
                let url = "quizapp/tags";
                let xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        let resp = xhr.responseText;
                        let data = JSON.parse(resp);
                        let html = "";
                        for (let i = 0; i < data.length; i++) {
                            html += "<option value='" + data[i].tagName + "'>" + data[i].tagName + "</option>";
                        }
                        let elem = document.querySelector("#tagInput");
                        elem.innerHTML += html;
                    }
                };
                xhr.open("GET", url, true);
                xhr.send();
            };

            function doSearch() {
                //alert("coming soon");
                let searchTerm = document.querySelector("#tagInput").value;

                let url = "quizapp/quizzes";
                let xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        let resp = xhr.responseText;
                        let matchingQuizzes = findMatchingQuizzes(resp, searchTerm);
                        //buildTable(matchingQuizzes);
                        buildCard(matchingQuizzes)
                    }
                };
                xhr.open("GET", url, true);
                xhr.send();
            }

            function findMatchingQuizzes(jsonData, searchTerm) {
                let quizzes = JSON.parse(jsonData);
                let matchingQuizzes = [];
                for (let i = 0; i < quizzes.length; i++) {
                    let quiz = quizzes[i];
                    let questions = quiz.questions;
                    for (let j = 0; j < questions.length; j++) {
                        let question = questions[j];
                        let tags = question.tags;
                        
                        for (let k = 0; k < tags.length; k++) {
                            let tag = tags[k];
                            if (tag.tagName === searchTerm) {
                                matchingQuizzes.push(quiz);
                                break;
                            }
                        }
                    }
                }
                
                let previousQuizID = "";
                let finalMatchingQuizzes = [];
                for (let i = 0; i < matchingQuizzes.length; i++) {
                    let quiz = matchingQuizzes[i];
                    
                    console.log(quiz.quizID);
                    console.log(previousQuizID);
                    if (quiz.quizID !== previousQuizID) {
                        finalMatchingQuizzes.push(quiz);
                        previousQuizID = quiz.quizID;
                    }
                }
                
                return finalMatchingQuizzes;
            }

            function buildTable(data) {
                let resultsElement = document.querySelector("#matchingQuizzes");
                let html = "<h4>Matching Quizzes</h4>";
                html += "<table class='table table-hover table-striped'>";
                html += "<tr class='table-dark'><th>Quiz ID</th><th>Quiz Title</th><th>Number of Questions</th><th>Total Points</th></tr>";
                for (let i = 0; i < data.length; i++) {
                    let temp = data[i];
                    let questions = temp.questions.length;
                    let points = temp.points;
                    let pointsTotal = 0;
                    for (let j = 0; j < points.length; j++) {
                        pointsTotal += points[j];
                        console.log(points[j]);
                    }

                    html += `<tr><td>${temp.quizID}</td><td>${temp.quizTitle}</td><td>${questions}</td>
                         <td>${pointsTotal}</td></tr>`;
                }
                html += "</table>";
                resultsElement.innerHTML = html;
            }
            
            function buildCard(data) {
                let resultsElement = document.querySelector("#matchingQuizzes");
                html = `<div class="container"><h4>Matching Quizzes</h4><div class="row row-cols-1 row-cols-md-3 g-4">`;
                for (let i = 0; i < data.length; i++) {
                    let temp = data[i];
                    let questions = temp.questions.length;
                    let points = temp.points;
                    let pointsTotal = 0;
                    for (let j = 0; j < points.length; j++) {
                        pointsTotal += points[j];
                        console.log(points[j]);
                    }

                    html += `<div class="col">
                                <div class="card border-dark" id="questionCard">
                                    <div class="card-header">
                                        <div class="tag-colour"></div>
                                        <h5 class="card-title">${temp.quizTitle}</h5>
                                        <h6 class="card-subtitle text-secondary float-end">Number of Questions: <span>${questions}</span></h6><br>
                                        <h6 class="card-subtitle text-secondary float-end">Number of Points: <span>${pointsTotal}</span></h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <button class="btn btn-outline-primary" style="width: 100%;">Take Quiz!</button>
                                    </div>
                                </div>
                            </div>`;
                }
                html += "</div></div>";
                resultsElement.innerHTML = html;
            }
            
        </script>
        <style>
            .tag-colour {
                position: absolute;
                background-color: #804FB3;
                border-radius: 5px 0px 0px 5px;
                height: 100%;
                width: 10px;
                top: 0px;
                left: 0px;
            }
        </style>
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
        <div class="container">
            <h3 id="welcomeUser" class="mb-4">Welcome, Guest!</h3>

            <div class="card border-dark mb-3">
                <div class="card-header fs-3">Quiz Search</div>
                <div class="card-body text-secondary">
                    <div class="form-floating">
                        <select class="form-control floatingSelectGrid" id="tagInput" onchange="doSearch()"></select>
                        <label for="floatingSelectGrid">Select a Tag</label>
                    </div>
                    <button id="searchButton" class="btn btn-primary" style="margin-top:1em;">Search</button>
                </div>
            </div>
        </div>
        <div id="matchingQuizzes"></div>
    </body>
</html>
