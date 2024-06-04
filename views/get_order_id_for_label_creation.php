<!DOCTYPE html>
<html lang="en">
<head>
    <?php
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();
include_once("../views/structure/header.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: views/login_view.php");
    exit;
}
?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stwórz etykietę</title>
</head>
<body>
    <h2>Wprowadź numery zamówień do wysłania</h2>
    <form action="../ShippingEntryPoint.php" method="post">
        <label for="order_ids">Numery zamówień (oddzielone przecinkami):</label><br>
        <textarea id="order_ids" name="order_ids" rows="1" cols="50"></textarea><br><br>
        <input type="submit" value="Stwórz etykiety">
    </form>
</body>
</html>