<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Home Page - User</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <script src="sample.js"></script>
    </head>
    <body>
        <?php
        session_start();
        $userInfo = json_decode($_SESSION["currentUser"], true);
        $username = $userInfo["username"];
        $permissionLevel = $userInfo["permissionLevel"];
        ?>

        <div class="container">
            <h1 class="mb-4">Welcome, <?php echo $username; ?>!</h1>

            <div id="menu">
                <p>Show "ADMIN" menu here.</p>
            </div>

            <section id="userSearch" class="border border-secondary p-3 mb-3">
                <h3>Search for Quiz Results by User</h3>

                <form>
                    <select id="userInput"></select>
                    <button id="getResultsButton">Get Results</button>
                </form>
                
                <div class="quizResults"></div>
            </section>

            <section id="scoreSearch"class="border border-secondary p-3">
                <h3>Search for Quiz Results By Score</h3>

                <form>
                    Min: <input id="scoreMin" type="number" min="0" max="100"><br>
                    Max: <input id="scoreMax" type="number" min="0" max="100"><br>
                    <button id="getScoreResultsButton">Get Results</button>
                </form>
            
                <div class="quizResults"></div>
            </section>
        </div>
    </body>
</html>
