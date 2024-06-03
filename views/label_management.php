<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Label Management</title>
</head>
<body>
    <h1>Shipping Label Management</h1>

    <form action="" method="post">
        <input type="submit" name="createLabel" value="Create Shipping Label">
    </form>

    <form action="" method="post">
        <input type="submit" name="checkStatus" value="Check Shipment Status">
    </form>

    <form action="" method="post">
        <input type="submit" name="getLabel" value="Get Shipment Label">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['createLabel'])) {
            $labelResponse = $controller->createShippingLabel($accessToken, $shippingData);
            echo "<pre>";
            print_r($labelResponse);
            echo "</pre>";
        }

        if (isset($_POST['checkStatus'])) {
            $statusResponse = $controller->checkShipmentStatus($accessToken, $commandId);
            echo "<pre>";
            print_r($statusResponse);
            echo "</pre>";
        }

        if (isset($_POST['getLabel'])) {
            $label = $controller->getShipmentLabel($accessToken, [$shipmentId]);
            include('shipping_view.php');
        }
    }
    ?>

</body>
</html>
