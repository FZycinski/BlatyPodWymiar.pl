<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();

// Sprawdź, czy numer zamówień został przekazany
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["order_ids"])) {
    $orderIds = $_POST["order_ids"];

    // Ustawienie konfiguracji Allegro
    $config = include('config/api_config.php');
    define('CLIENT_ID', $config['client_id']);
    define('CLIENT_SECRET', $config['client_secret']);
    define('REDIRECT_URI', 'https://test.blatypodwymiar.pl/ShippingEntryPoint.php');
    define('AUTH_URL', 'https://allegro.pl/auth/oauth/authorize');
    define('TOKEN_URL', 'https://allegro.pl/auth/oauth/token');

    // Nawiązanie połączenia z bazą danych
    require_once 'config/DatabaseConnection.php';
    $mysqli = DatabaseConnection::getConnection();

    // Sprawdzenie czy udało się połączyć z bazą danych
    if ($mysqli) {
        echo "Połączenie z bazą danych nawiązane.<br>";
    } else {
        echo "Błąd połączenia z bazą danych.<br>";
        exit;
    }

    // Utworzenie instancji modelu i kontrolera
    require_once 'models/ShippingModel.php';
    require_once 'controllers/ShippingController.php';
    $model = new ShippingModel($mysqli);
    $controller = new ShippingController($model);

    // Wywołanie głównej metody kontrolera przekazując numery zamówień
    $controller->main($orderIds);

    exit; // Zakończenie skryptu po przekazaniu numerów zamówień
}

// Jeśli numer zamówień nie został przekazany, wyświetl formularz
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
        <textarea id="order_ids" name="order_ids" rows="1" cols="50"></textarea><br><br>
        <input type="submit" value="Stwórz etykiety">
    </form>
</body>
</html>
