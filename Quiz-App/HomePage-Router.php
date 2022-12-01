<?php

session_start();
$userInfo = json_decode($_SESSION["currentUser"], true);
$username = $userInfo["username"];
$permissionLevel = $userInfo["permissionLevel"];

if ($permissionLevel === "USER") {
    header("location: HomePage-User.php");
} else if ($permissionLevel === "ADMIN") {
    header("location: HomePage-Admin.php");
} else if ($permissionLevel === "SUPER") {
    header("location: HomePage-Super.php");
} else {
    echo "<p>Unknown permission level '" . $permissionLevel . "'.</p>";
    echo "<p>You should not be seeing this ... contact technical support!</p>";
}
