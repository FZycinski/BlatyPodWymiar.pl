<?php
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login_view.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once '../views/structure/header.php'; 
    include_once '../models/UserModel.php';
    include_once '../models/User.php';
    $user = new User();
    ?>
    <title>Edycja konta</title>
</head>
<body>
    <h2>Edycja konta</h2>
    <form action="edit_account_process.php" method="post">
        <label for="first_name">Imię:</label><br>
        <input type="text" id="first_name" name="first_name" value="<?php echo $user->getName(); ?>" required><br><br>
        
        <label for="last_name">Nazwisko:</label><br>
        <input type="text" id="last_name" name="last_name" value="<?php echo $user->getSurname(); ?>" required><br><br>
        
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo $user->getEmail(); ?>" required><br><br>
        
        <label for="password">Nowe hasło:</label><br>
        <input type="password" id="password" name="password"><br><br>
        
        <input type="submit" value="Zapisz zmiany">
    </form>
</body>
</html>
