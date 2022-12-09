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
            };

            function doSearch() {
                //alert("coming soon");
                let searchTerm = document.querySelector("#searchInput").value;

                let url = "quizapp/quizzes"
                let xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        let resp = xhr.responseText;
                        let matchingQuizzes = findMatchingQuizzes(resp, searchTerm);
                        buildTable(matchingQuizzes);
                    }
                }
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
                            if (tag.includes(searchTerm)) {
                                matchingQuizzes.push(quiz);
                                break;
                            }
                        }
                    }
                }
                return matchingQuizzes;
            }

            function buildTable(quizzes) {
                let resultsElement = document.querySelector("#matchingQuizzes");
                let html = "<ul>";
                for (let i = 0; i < quizzes.length; i++) {
                    let quiz = quizzes[i];
                    html += "<li>" + quiz.quizTitle + "</li>";
                }
                html += "</ul>";
                resultsElement.innerHTML = html;
            }
        </script>
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
                    <label for="searchInput" class="form-label">Search by Tags</label>
                    <div class="form-floating">
                        <input type="text" class="form-control" id="searchInput floatingInputGrid" aria-describedby="searchHelp">
                        <label for="floatingInputGrid">Enter a search word or phrase.</label>
                    </div>
                    <button id="searchButton" class="btn btn-primary" style="margin-top:1em;">Search</button>
                </div>
            </div>
            
            <div>
                <div id="matchingQuizzes"></div>
            </div>
        </div>
    </body>
</html>
