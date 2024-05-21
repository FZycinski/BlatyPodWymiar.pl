<?php

require_once '../config/DatabaseConnection.php';
require_once '../models/UserModel.php';
require_once '../models/User.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["pin"]) &&
        !empty($_POST["first_name"]) && !empty($_POST["last_name"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["pin"])) {
        
        $db = new DatabaseConnection();
        $userModel = new UserModel($db);

        $first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $pin = $_POST["pin"];

        if (!$userModel->userExists($email)) {
            $newUser = new User(null, $first_name, $last_name, $email, $password, $pin);
            
            $userModel->saveUser($newUser);

            header("Location: /index.php");
            exit;
        } else {
            $error_message = "Użytkownik o podanym adresie email już istnieje.";
        }
    } else {
        $error_message = "Wypełnij wszystkie pola formularza.";
    }
} else {
    header("Location: /index.php");
    exit;
}
