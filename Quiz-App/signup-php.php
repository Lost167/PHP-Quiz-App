<?php
    require_once './db/UserAccessor.php';
    include "./utils/ChromePhp.php";
    $username = "";
    $password = "";
    $passwordError = "";
    $permissionLevel = "USER";
    $confirmPassword = "";
    $usernameError = "";
    $confirmPasswordError = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $confirmPassword = $_POST["confirmPassword"];

        $accessor = new UserAccessor();
        $account = $accessor->getAccountForUser($username);
        
        if ($account != null) {
            $usernameError = "This Username is Already Used"; 
        } else {
            if (strlen($password) < 6) {
                $passwordError = "Password must be 6+ characters";
            }
            else if ($password !== $confirmPassword) {
                $confirmPasswordError = "Your password does not Match!";
            }
            else {
                # Create new User
                session_start();
                $encryptedPassword = password_hash($password, PASSWORD_DEFAULT);
                $user = new User($username, $encryptedPassword, $permissionLevel);
                $newAccount = $accessor->addNewUser($user);
                $_SESSION["currentUser"] = json_encode($user);
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
            <h1 class="mb-4">Signup Page</h1>
            
            <div class="border rounded" style="padding: 1em;">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">  
                    <div class="mb-3">
                        <label for="usernameInput" class="form-label">Enter a Username</label>
                        <input id="usernameInput" type="text" class="form-control" name="username" value="<?php echo $username; ?>" required>
                        <span id="usernameError" class="text-danger floatingSelectGrid"><?php echo $usernameError; ?></span>
                    </div>
                    <div class="mb-3">
                        <label for="passwordInput" class="form-label">Enter a Password</label>
                        <input id="passwordInput" type="password" class="form-control" name="password"  value="<?php echo $password; ?>" required>
                        <span id="passwordError" class="text-danger floatingSelectGrid"><?php echo $passwordError; ?></span>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPasswordInput" class="form-label">Confirm your Password</label>
                        <input id="confirmPasswordInput" type="password" class="form-control" name="confirmPassword"  value="<?php echo $confirmPassword ?>" required>
                        <span id="confirmPasswordError" class="text-danger floatingSelectGrid"><?php echo $confirmPasswordError; ?></span>
                    </div>
                    <button id="signUpButton" class="btn btn-primary" type="submit">Sign Up!</button>
                </form>
            </div>
        </div>
    </body>
</html>
