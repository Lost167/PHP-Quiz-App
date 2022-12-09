<?php
    require_once './db/UserAccessor.php';
    $username = "";
    $password = "";
    $usernameError = "";
    $passwordError = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $accessor = new UserAccessor();
        $account = $accessor->getAccountForUser($username);

        if ($account == null) {
            $usernameError = "no such user";
        } else {
            $ok = password_verify($password, $account->getPassword());
            if (!$ok) {
                $passwordError = "wrong password";
            } else {
                session_start();
                $_SESSION["currentUser"] = json_encode($account);
                header("location: HomePage-Router.php");
            }
        }
    }
    ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login Page</title>
        <link rel="icon" type="image/x-icon" href="images/logo/favicon.ico">
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
        <div class="container">
            <h1 class="mb-4">Login Page</h1>
            
            <div class="border rounded" style="padding: 1em;">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">  
                    <div class="mb-3">
                        <label for="usernameInput" class="form-label">User Name</label>
                        <input id="usernameInput" type="text" class="form-control" name="username" value="<?php echo $username; ?>" required>
                        <span id="usernameError" class="text-danger floatingSelectGrid"><?php echo $usernameError; ?></span>
                    </div>
                    <div class="mb-3">
                        <label for="passwordInput" class="form-label">Password</label>
                        <input id="passwordInput" type="password" class="form-control" name="password"  value="<?php echo $password; ?>" required>
                        <span id="passwordError" class="text-danger"><?php echo $passwordError; ?></span>
                    </div>
                    <button id="loginButton" class="btn btn-primary" type="submit">Login</button>
                </form>
            </div>
        </div>
    </body>
</html>
