<?php
include_once("models/ShippingModel.php");

class ShippingController {
    public function generateShippingData($customerId) {
        $shippingModel = new ShippingModel();
        $shippingData = $shippingModel->getShippingData($customerId);
        
        include("views/shipping_view.php");
    }
}

$shippingController = new ShippingController();
$shippingController->generateShippingData($customerId);