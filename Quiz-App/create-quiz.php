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
            <h1 class="mb-4">Create Quiz: </h1>
            <!--| Needs to be done |-->
            <div class="border rounded" style="padding: 1em;">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">  
                    <div class="mb-3">
                        <label for="usernameInput" class="form-label">Enter a Username</label>
                        <input id="usernameInput" type="text" class="form-control" name="username" value="" required>
                    </div>
                    <div class="mb-3">
                        <label for="passwordInput" class="form-label">Enter a Password</label>
                        <input id="passwordInput" type="password" class="form-control" name="password"  value="" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPasswordInput" class="form-label">Confirm your Password</label>
                        <input id="confirmPasswordInput" type="password" class="form-control" name="confirmPassword"  value="" required>
                    </div>
                    <button id="loginButton" class="btn btn-primary" type="submit">Sign Up!</button>
                </form>
            </div>
        </div>
    </body>
</html>
