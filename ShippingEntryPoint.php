<?php
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();

include_once("views/structure/header.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: views/login_view.php");
    exit;
}

$config = include('config/api_config.php');
define('CLIENT_ID', $config['client_id']);
define('CLIENT_SECRET', $config['client_secret']);
define('REDIRECT_URI', 'https://test.blatypodwymiar.pl/ShippingEntryPoint.php');
define('AUTH_URL', 'https://allegro.pl/auth/oauth/authorize');
define('TOKEN_URL', 'https://allegro.pl/auth/oauth/token');

require_once 'config/DatabaseConnection.php';
require_once 'models/ShippingModel.php';
require_once 'controllers/ShippingController.php';

$mysqli = DatabaseConnection::getConnection();

$model = new ShippingModel($mysqli);
$controller = new ShippingController($model);
$controller->main();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Entry Point</title>
</head>
<body>
    <h1>Wprowadź numery zamówień do wysłania</h1>
    <form action="ShippingEntryPoint.php" method="post">
        <label for="order_ids">Numery zamówień (oddzielone przecinkami):</label><br>
        <textarea id="order_ids" name="order_ids" rows="4" cols="50"></textarea><br><br>
        <input type="submit" value="Wyślij zamówienia">
    </form>
</body>
</html>

