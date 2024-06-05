<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/Calculator.php';

use App\Models\Calculator;

class CalculatorController
{
    public function showForm()
    {
        include __DIR__ . '/../views/calculate_prices.php';
    }

    public function handleRequest()
    {
        echo 'juhuuu'; // Powinno wyświetlić się na stronie
        error_log('handleRequest called'); // Zapisuje do loga PHP
        if (isset($_POST['allFormData'])) {
            error_log('Form data received: ' . $_POST['allFormData']);
            $formData = json_decode($_POST['allFormData'], true);
            $calculator = new Calculator();
            $results = $calculator->calculatePrices($formData);
            echo json_encode($results);
        } else {
            error_log('No form data received.');
        }
    }
}
?>

    //przywroc ta wersje pozniej
    // public function handleRequest()
    // {
    //     $formData = json_decode($_POST['allFormData'], true);
    //     $calculator = new Calculator();
    //     $results = $calculator->calculatePrices($formData);
    //     echo json_encode($results);
    // }
}
?>
