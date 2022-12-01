<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Home Page - User</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
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
                <p>Show "SUPER" menu here.</p>
            </div>
        </div>
    </body>
</html>
