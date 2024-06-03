<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Data</title>
</head>
<?php
echo "<h1>Shipment Label Created</h1>";

echo "<h2>Label Response:</h2>";
echo "<pre>";
print_r($labelResponse);
echo "</pre>";

echo "<h2>Shipment Status:</h2>";
echo "<pre>";
print_r($statusResponse);
echo "</pre>";
?>
</body>
</html>