<?php
session_start();
$userInfo = json_decode($_SESSION["currentUser"], true);
$username = $userInfo["username"];
$permissionLevel = $userInfo["permissionLevel"];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Home Page - Super</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <script>
            window.onload = function () {
                document.querySelector("#showAllAccounts").addEventListener("click", loadUsers);
            };

            function loadUsers() {
                let url = "quizapp/accounts";
                let xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        let resp = xhr.responseText;
                        let data = JSON.parse(resp);
                        let html = "<h4>Users</h4>";
                        html += "<table class='table table-hover table-striped'>";
                        html += "<tr class='table-dark'><th>Username</th><th>Permission Level</th><th>Change Password</th><th>Delete Account</th></tr>";
                        for (let i = 0; i < data.length; i++) {
                            html += `<tr><td>${data[i].username}</td><td>${data[i].permissionLevel}</td>
                                    <td><button id='changePassword' class='btn btn-outline-success' name='${data[i].username}'>Change Password</button></td>
                                    <td><button id='deleteAccount' class='btn btn-outline-danger' name='${data[i].username}'>Delete Account</button></td></tr>`;
                        }
                        let elem = document.querySelector("#loadUsers");
                        elem.innerHTML += html;
                    }
                };
                xhr.open("GET", url, true);
                xhr.send();
            }
            
            function deleteItem() {
                let id = document.querySelector(".highlighted").querySelector("td").innerHTML;
                let url = "quizapp/accounts/" + id; // entity, not action
                let xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                        let resp = xmlhttp.responseText;
                        if (resp.search("ERROR") >= 0 || resp != 1) {
                            alert("could not complete request");
                            console.log(resp);
                        } else {
                            getAllItems();
                        }
                    }
                };
                xmlhttp.open("DELETE", url, true); // "DELETE" is the action, "url" is the entity
                xmlhttp.send();
            }
        </script>
    </head>
    <body>
        <?php
        $userInfo = json_decode($_SESSION["currentUser"], true);
        $username = $userInfo["username"];
        $permissionLevel = $userInfo["permissionLevel"];

        require_once './db/UserAccessor.php';
        $newUsername = "";
        $newPassword = "";
        $newPermissionLevel = "";
        $confirmNewPassword = "";
        $usernameError = "";
        $confirmPasswordError = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $newUsername = $_POST["newUsername"];
            $newPassword = $_POST["newPassword"];
            $confirmNewPassword = $_POST["confirmNewPassword"];
            $newPermissionLevel = $_POST["permissionSelect"];

            $accessor = new UserAccessor();
            $account = $accessor->getAccountForUser($newUsername);

            if ($account != null) {
                $usernameError = "This Username is Already Used";
            } else {
                if (strlen($newPassword) < 6) {
                    $passwordError = "Password must be 6+ characters";
                } else if ($newPassword !== $confirmNewPassword) {
                    $confirmPasswordError = "Your password does not Match!";
                } else {
                    # Create new User
                    $encryptedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $user = new User($newUsername, $encryptedPassword, $newPermissionLevel);
                    $newAccount = $accessor->addNewUser($user);
                }
            }
        }
        ?>
        
        <nav class="navbar navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">
                    <img src="images/logo/logo.png" alt="" width="65" height="65">
                </a>
                <h1>Quiz App</h1>
                <div id="logIn" class="d-flex">
                    <a id="loginButton" class="btn btn-outline-secondary me-3" href="login-php.php">Log in</a>
                    <a id="createAccountButton" class="btn btn-outline-secondary me-3" href="signup-php.php">Sign up</a>
                </div>
            </div>
        </nav>
        <br>

        <div class="container">
            <h1 class="mb-4">Welcome, <?php echo $username; ?>!</h1>

            <div class="card border-dark mb-3" id="userSearch">
                <div class="card-header fs-2">Create a System or Admin Account</div>
                <div class="card-body text-secondary">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">  
                        <div class="mb-3">
                            <label for="usernameInput" class="form-label">Enter a Username</label>
                            <input id="usernameInput" type="text" class="form-control" name="newUsername" value="<?php echo $newUsername; ?>" required>
                            <span id="usernameError" class="text-danger floatingSelectGrid"><?php echo $usernameError; ?></span>
                        </div>
                        <div class="mb-3">
                            <label for="passwordInput" class="form-label">Enter a Password</label>
                            <input id="passwordInput" type="password" class="form-control" name="newPassword"  value="<?php echo $newPassword; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPasswordInput" class="form-label">Confirm your Password</label>
                            <input id="confirmPasswordInput" type="password" class="form-control" name="confirmNewPassword"  value="<?php echo $confirmNewPassword ?>" required>
                            <span id="confirmPasswordError" class="text-danger"><?php echo $confirmPasswordError; ?></span>
                        </div>
                        <div class="mb-3">
                            <label for="permissionSelect" class="form-label">Select a permission level</label>
                            <select name="permissionSelect">
                                <option value="ADMIN">ADMIN</option>
                                <option value="SUPER">SUPER</option>
                            </select>
                        </div>
                        <button id="systemCreateAccountButton" class="btn btn-primary" type="submit">Sign Up!</button>
                    </form>
                </div>
            </div>


            <div class="card border-dark mb-3" id="scoreSearch">
                <div class="card-header fs-2">All Accounts</div>
                <div class="card-body text-secondary">
                    <button id="showAllAccounts" class="btn btn-primary" style="margin-top:1em;">Show All Accounts</button>
                </div>

                <div id="loadUsers" class="container" style="margin-top:1em;"></div>
            </div>
        </div>
    </body>
</html>
