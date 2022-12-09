window.onload = function () {
    document.querySelector("#getResultsButton").addEventListener("click", getResultsByUser);
    document.querySelector("#getScoreResultsButton").addEventListener("click", getResultsByScore);
    document.querySelector("#getAggregateResultsButton").addEventListener("click", getAggregateResultsByUser);
    document.querySelector("#getAggregateScoreResultsButton").addEventListener("click", getAggregateResultsByScore);
    document.querySelector("#searchButton").addEventListener("click", doSearch);
    getTags();
    loadUsers();
};

function getResultsByScore(e) {
    e.preventDefault();
    let min = document.querySelector("#scoreMin").value;
    let max = document.querySelector("#scoreMax").value;
    let url = "quizapp/quizResults/search:scoremin=" + min + "&scoremax=" + max;
    let title = "Quiz Results for Score Range from " + min + " to " + max;
    let element = document.querySelector("#scoreSearch .quizResults");
    buildResultsSection(url, element, title);
}

function getAggregateResultsByScore(e) {
    e.preventDefault();
    let min = document.querySelector("#scoreMin").value;
    let max = document.querySelector("#scoreMax").value;
    let url = "quizapp/quizResults/search:scoremin=" + min + "&scoremax=" + max;
    let element = document.querySelector("#scoreSearch .quizResults");
    buildAggregateResultsSection(url, element);
}

function getResultsByUser(e) {
    e.preventDefault();
    let username = document.querySelector("#userInput").value;
    let url = "quizapp/quizResults/search:user=" + username;
    let title = "Quiz Results for " + username;
    let element = document.querySelector("#userSearch .quizResults");
    buildResultsSection(url, element, title);
}

function getAggregateResultsByUser(e) {
    e.preventDefault();
    let username = document.querySelector("#userInput").value;
    let url = "quizapp/quizResults/search:user=" + username;
    let element = document.querySelector("#userSearch .quizResults");
    buildAggregateResultsSection(url, element);
}

function buildResultsSection(url, element, title) {
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let resp = xhr.responseText;
            let data = JSON.parse(resp);
            let html = "<h4>" + title + "</h4>";
            html += "<table class='table table-hover table-striped'>";
            html += "<tr class='table-dark'><th>User</th><th>Quiz ID</th><th>Quiz Title</th><th>Started</th><th>Submitted</th><th>Score</th><th>Percent</th></tr>";
            for (let i = 0; i < data.length; i++) {
                let temp = data[i];
                let startTime = temp.quizStartTime.split(" ");
                let endTime = temp.quizEndTime.split(" ");
                let percent = temp.scoreNumerator / temp.scoreDenominator * 100;

                html += `<tr><td>${temp.username}</td><td>${temp.quiz.quizID}</td><td>${temp.quiz.quizTitle}</td><td>${startTime[0]} at ${startTime[1]}</td>
                         <td>${endTime[0]} at ${endTime[1]}</td><td>${temp.scoreNumerator}/${temp.scoreDenominator}</td><td>${percent.toFixed(1)}</td></tr>`;
            }
            html += "</table>";
            element.innerHTML = html;
        }
    };
    xhr.open("GET", url, true);
    xhr.send();
}

function buildAggregateResultsSection(url, element) {
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let resp = xhr.responseText;
            let data = JSON.parse(resp);
            let html = "<h4>Aggregate Stats for Searched Quiz Results</h4>";
            html += "<table class='table table-hover table-striped'>";
            html += "<tr class='table-dark'><th>Average Score</th><th>Max Score</th><th>Min Score</th></tr>";
            let totalScore = 0;
            let maxScore = 0;
            let minScore = 100;
            for (let i = 0; i < data.length; i++) {
                let temp = data[i];
                let percent = temp.scoreNumerator / temp.scoreDenominator * 100;
                totalScore += percent;
                if (percent > maxScore) {
                    maxScore = percent;
                }
                if (percent < minScore) {
                    minScore = percent;
                }
            }
            let averageScore = totalScore / data.length;
            html += `<tr><td>${averageScore.toFixed(1)}%</td><td>${maxScore.toFixed(1)}%</td><td>${minScore.toFixed(1)}%</td></tr>`;
            html += "</table>";
            element.innerHTML = html;
        }
    };
    xhr.open("GET", url, true);
    xhr.send();
}

function loadUsers() {
    let url = "quizapp/users";
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let resp = xhr.responseText;
            let data = JSON.parse(resp);
            let html = "";
            for (let i = 0; i < data.length; i++) {
                html += "<option value='" + data[i].username + "'>" + data[i].username + "</option>";
            }
            let elem = document.querySelector("#userInput");
            elem.innerHTML += html;
        }
    };
    xhr.open("GET", url, true);
    xhr.send();
}

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
}
;

function doSearch() {
    //alert("coming soon");
    let searchTerm = document.querySelector("#tagInput").value;

    let url = "quizapp/quizzes";
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let resp = xhr.responseText;
            let matchingQuizzes = findMatchingQuizzes(resp, searchTerm);
            buildTable(matchingQuizzes);
        }
    };
    xhr.open("GET", url, true);
    xhr.send();
}

function findMatchingQuizzes(jsonData, searchTerm) {
    let quizzes = JSON.parse(jsonData);
    let matchingQuizzes = [];
    //let previousTag = "QU-1000";
    for (let i = 0; i < quizzes.length; i++) {
        let quiz = quizzes[i];
        let questions = quiz.questions;

//                    console.log(quiz.quizID);
//                    console.log("The previous Quiz Tag was: "+previousTag);
//                    if (previousTag === quiz.quizID) {
//                        break;
//                    } else {
//                        previousTag = quiz.quizID;
//                    }

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

    // Loop through matchingQuizzes
    // If matching quizID then skip
    // If new quizID add to new array
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