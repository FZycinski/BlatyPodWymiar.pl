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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = json_decode($_POST['allFormData'], true);
            $calculator = new Calculator();
            $results = $calculator->calculatePrices($formData);
            echo json_encode($results);
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
        }
    }
}

// Routing
if (isset($_GET['action']) && $_GET['action'] === 'handleRequest') {
    $controller = new CalculatorController();
    $controller->handleRequest();
}
