<?php
require_once '../models/UserModel.php';
require_once '../config/DatabaseConnection.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["email"]) && isset($_POST["password"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        $db = new DatabaseConnection();
        $userModel = new UserModel($db);

        if ($userModel->userExists($email)) {
            if ($userModel->verifyPassword($email, $password)) {
                session_start();
                $_SESSION['user_id'] = $email;
                var_dump($_SESSION['user_id']);
                $sessionId = session_id();
                echo "Identyfikator bieżącej sesji: $sessionId";

                header("Location: ../index.php");
                exit;
            } else {
                var_dump("2");
                $error_message = "Niepoprawne hasło. Spróbuj ponownie.";
            }
        } else {
            var_dump("3");
            $error_message = "Nie znaleziono użytkownika o podanym adresie e-mail.";
        }
    } else {
        $error_message = "Wprowadź adres e-mail i hasło.";
        var_dump("4");
    }
} else {
    header("Location: login_view.php");
    exit;
}
