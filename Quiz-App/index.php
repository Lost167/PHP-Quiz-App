<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Home Page</title>
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
        <div class="container">
            <div class="row p-3 mb-3 bg-light border border-secondary">
                <div class="col-9 col-xl-10 fs-2">Quiz App</div>
                <div class="col-3 col-xl-2">
                    <a id="loginButton" class="btn btn-outline-secondary me-3" href="login-js.php">Log in</a>
                    <a id="createAccountButton"  class="btn btn-secondary" href="#">Sign up</a>
                </div>
            </div>
            <h3 class="mb-4">Welcome, Guest!</h3>

            <div class="border border-dark p-3">
                <h4>Quiz Search</h4>

                <div class="mb-3">
                    <label for="searchInput" class="form-label">Tags</label>
                    <input type="text" class="form-control" id="searchInput" aria-describedby="searchHelp">
                    <div id="searchHelp" class="form-text">Enter a search word or phrase.</div>
                </div>
                <button id="searchButton" class="btn btn-primary">Search</button>

                <div id="matchingQuizzes"></div>
            </div>
        </div>
    </body>
</html>
