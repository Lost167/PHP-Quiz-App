<?php

require __DIR__ . "/../db/UserAccessor.php";

$body = file_get_contents('php://input');
$contents = json_decode($body, true);
$username = $contents['username'];
$password = $contents['password'];

$accessor = new UserAccessor();
$account = $accessor->getAccountForUser($username);

if ($account == null) {
    echo "no such user";
} else {
    $ok = password_verify($password, $account->getPassword());
    if (!$ok) {
        echo "wrong password";
    } else {
        session_start();
        $_SESSION["currentUser"] = json_encode($account);
        echo "login successful";
    }
}
