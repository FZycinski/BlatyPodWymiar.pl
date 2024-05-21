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
    
    // Sprawdź, czy nowe hasło zostało przekazane
    if (!empty($password)) {
        // Zaktualizuj hasło tylko jeśli zostało przekazane
        $user->setHashedPassword($password);
    }
    
    // Sprawdź, czy nowy PIN został przekazany
    if (!empty($pin)) {
        // Zaktualizuj PIN tylko jeśli został przekazany
        $user->setHashedPin($pin);
    }

    // Zapisz zaktualizowane dane użytkownika do bazy danych
    $userModel->updateUser($user);

    // Przekieruj użytkownika do strony głównej lub dowolnej innej strony po edycji konta
    header("Location: ../index.php");
    exit;
} else {
    // Jeśli dane nie zostały przesłane z formularza, przekieruj użytkownika do formularza edycji konta
    header("Location: edit_account.php");
    exit;
}
?>