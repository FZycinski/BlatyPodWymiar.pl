<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Data</title>
</head>
<?php
// views/shipping_view.php

echo "<h1>Shipment Label Created</h1>";

echo "<h2>Label Response:</h2>";
echo "<pre>";
print_r($labelResponse);
echo "</pre>";

echo "<h2>Shipment Status:</h2>";
echo "<pre>";
if (isset($statusResponse)) {
    exit;
} else {
    echo "Failed to retrieve shipment status.";
}
echo "</pre>";

echo "<h2>Shipment Label:</h2>";
echo "<pre>";
if (isset($labelResponse)) {
    print_r($labelResponse);
    if (isset($labelResponse['url'])) {
        echo "<a href='" . $labelResponse['url'] . "' target='_blank'>Download Label</a>";
    } else {
        echo "URL not found in the response.";
    }
} else {
    echo "Failed to retrieve shipment label response.";
}
echo "</pre>";
?>





</body>
</html>