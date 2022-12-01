<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login Page</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <script>
            window.onload = function () {
                document.querySelector("#loginButton").addEventListener("click", doLogin);
            };

            function doLogin(e) {
                e.preventDefault();

                document.querySelector("#usernameError").innerHTML = "";
                document.querySelector("#passwordError").innerHTML = "";

                let username = document.querySelector("#usernameInput").value;
                let password = document.querySelector("#passwordInput").value;

                let obj = {
                    username: username,
                    password: password
                };

                let url = "login"
                let xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        resp = xhr.responseText;
                        if (resp === "no such user") {
                            document.querySelector("#usernameError").innerHTML = resp;
                        } else if (resp === "wrong password") {
                            document.querySelector("#passwordError").innerHTML = resp;
                        } else if (resp === "login successful") {
                            window.location.href = "HomePage-Router.php";
                        } else {
                            alert("BAD RESPONSE from server - investigate! (Response: '" + resp + "')");
                        }
                    }
                }
                xhr.open("POST", url, true);
                xhr.send(JSON.stringify(obj));
            }
        </script>
    </head>
    <body>
        <div class="container">
            <h1 class="mb-4">Login Page</h1>

            <form>
                <div class="mb-3">
                    <label for="usernameInput" class="form-label">User Name</label>
                    <input id="usernameInput" type="text" class="form-control" required value="admin1">
                    <span id="usernameError" class="text-danger"></span>
                </div>
                <div class="mb-3">
                    <label for="passwordInput" class="form-label">Password</label>
                    <input id="passwordInput" type="password" class="form-control" required value="admin1">
                    <span id="passwordError" class="text-danger"></span>
                </div>
                <button id="loginButton" class="btn btn-primary" type="submit">Login</button>
            </form>
        </div>
    </body>
</html>
