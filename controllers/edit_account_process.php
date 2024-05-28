<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $pin = $_POST["pin"];

    $userModel = new UserModel($db);
    $user = $userModel->userExists($_SESSION['user_id']); 

    $user->setFirstName($first_name);
    $user->setLastName($last_name);
    $user->setEmail($email);
    
    if (!empty($password)) {
        $user->setHashedPassword($password);
    }
    
    if (!empty($pin)) {
        $user->setHashedPin($pin);
    }

    $userModel->updateUser($user);

    header("Location: ../index.php");
    exit;
} else {
    header("Location: edit_account.php");
    exit;
}
?>