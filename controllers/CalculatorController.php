<?php
namespace App\Controllers;
echo __DIR__ . '/models/Calculator.php';
require_once __DIR__ . '/models/Calculator.php';

use App\Models\Calculator;

class CalculatorController
{
    public function showForm()
    {
        include '../views/calculate_prices.php';
    }

    public function handleRequest()
    {
        $formData = json_decode($_POST['allFormData'], true);
        $calculator = new Calculator();
        $results = $calculator->calculatePrices($formData);
        echo json_encode($results);
    }
}
?>
