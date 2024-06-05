<?php
namespace App\Controllers;

use App\Models\Calculator;

class CalculatorController
{
    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formsData = json_decode($_POST['allFormData'], true);

            $calculator = new Calculator();
            $results = $calculator->calculatePrices($formsData);

            echo json_encode($results);
        } else {
            $this->showForm();
        }
    }

    public function showForm()
    {
        include '../app/Views/header.php';
        include '../app/Views/calculate_prices.php';
    }
}
?>
