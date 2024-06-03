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