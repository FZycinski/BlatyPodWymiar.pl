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
        include '../views/structure/header.php';
        include '../views/calculate_prices.php';
        include '../views/structure/footer.php';
    }
}
?>
