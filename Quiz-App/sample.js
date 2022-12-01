window.onload = function () {
    document.querySelector("#getResultsButton").addEventListener("click", getResultsByUser);
    document.querySelector("#getScoreResultsButton").addEventListener("click", getResultsByScore);
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

function getResultsByUser(e) {
    e.preventDefault();
    let username = document.querySelector("#userInput").value;
    let url = "quizapp/quizResults/search:user=" + username;
    let title = "Quiz Results for " + username;
    let element = document.querySelector("#userSearch .quizResults");
    buildResultsSection(url, element, title);
}

function buildResultsSection(url, element, title) {
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let resp = xhr.responseText;
            let data = JSON.parse(resp);
            let html = "<h4>" + title + "</h4>";
            html += "<table class='table table-bordered table-hover'>";
            html += "<tr class='table-dark'><th>User</th><th>Quiz ID</th><th>Quiz Title</th><th>Started</th><th>Submitted</th><th>Score</th><th>Percent</th></tr>";
            for (let i = 0; i < data.length; i++) {
                let temp = data[i];
                html += "<tr>";
                html += "<td>" + temp.username + "</td>";
                html += "<td>" + temp.quiz.quizID + "</td>";
                html += "<td>" + temp.quiz.quizTitle + "</td>";
                let startTime = temp.quizStartTime.split(" ");
                let endTime = temp.quizEndTime.split(" ");
                html += "<td>" + startTime[0] + " at " + startTime[1] + "</td>";
                html += "<td>" + endTime[0] + " at " + endTime[1] + "</td>";
                html += "<td>" + temp.scoreNumerator + "/" + temp.scoreDenominator + "</td>";
                let percent = temp.scoreNumerator / temp.scoreDenominator * 100;
                html += "<td>" + percent.toFixed(1) + "%</td>";
                html += "</tr>";
            }
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