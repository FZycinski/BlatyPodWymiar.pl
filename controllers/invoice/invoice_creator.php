<?php
require_once('../../vendor/autoload.php');
require_once('../../vendor/tecnickcom/tcpdf/tcpdf.php');

function getCurrentInvoiceNumber() {
    $filename = 'invoice_number.txt';
    if (!file_exists($filename)) {
        file_put_contents($filename, '1');
    }
    return intval(file_get_contents($filename));
}

function updateInvoiceNumber($currentNumber) {
    $filename = 'invoice_number.txt';
    $newNumber = $currentNumber + 1;
    file_put_contents($filename, strval($newNumber));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $delivery_price = $_POST['delivery_cost_amount'];
    $price = $_POST['order_paid_amount'];
    $access_token = $_POST['access_token'];
    $invoice_address_street = $_POST['invoice_address_street'];
    $invoice_address_zipCode = $_POST['invoice_address_zipCode'];
    $invoice_address_city = $_POST['invoice_address_city'];
    $invoice_company_name = $_POST['invoice_company_name'];
    $invoice_company_taxId = $_POST['invoice_company_taxId'];
}

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

$pdf->SetFont('dejavusans', '', 12);

$pdf->AddPage();

$currentInvoiceNumber = getCurrentInvoiceNumber();
$invoice_number = $currentInvoiceNumber . '/' . date('m') . '/' . date('Y');
$file_name = $currentInvoiceNumber . '_' . date('m') . '_' . date('Y');

$klient = array(
    'Nazwa' => $invoice_company_name,
    'Adres' => $invoice_address_street,
    'Miasto' => $invoice_address_zipCode . ' ' . $invoice_address_city,
    'NIP' => $invoice_company_taxId
);

$sprzedawca = array(
    'Nazwa' => 'Dekor-Stone',
    'Adres' => 'ul. Słowackiego 43',
    'Miasto' => '38-500 Sanok',
    'NIP' => '687-131-85-76'
);


$header = array('Lp.', 'Nazwa produktu', 'Ilość', 'Cena jedn.', 'Wartość');

$produkty = array(
    array('Blaty drewniane', 1, $price),
    array('Wysyłka', 1, 0)
);

$lp = 1;

$pdf->SetFont('dejavusans', 'B', 14);
$pdf->Cell(0, 10, 'Faktura nr ' . $invoice_number, 0, 1, 'R');
$pdf->Ln();

$pdf->SetFont('dejavusans', '', 10); 
$pdf->Cell(0, 10, 'Data wystawienia: ' . date('Y-m-d'), 0, 0, 'R');
$pdf->Ln();


$pdf->Cell(0, 10, 'Termin płatności: ' . date('Y-m-d', strtotime('+14 days')), 0, 0, 'R');
$pdf->Ln(15);
$pdf->Ln(5);


$pdf->SetFont('dejavusans', '', 12);
$pdf->Cell(90, 10, 'Sprzedawca:', 0, 0, 'L');
$pdf->Cell(90, 10, 'Nabywca:', 0, 1, 'L');
$pdf->Cell(90, 10, $sprzedawca['Nazwa'], 0, 0, 'L');
$pdf->Cell(90, 10, $klient['Nazwa'], 0, 1, 'L');
$pdf->Cell(90, 10, $sprzedawca['Adres'] . ', ' . $sprzedawca['Miasto'], 0, 0, 'L');
$pdf->Cell(90, 10, $klient['Adres'] . ', ' . $klient['Miasto'], 0, 1, 'L');
$pdf->Cell(90, 10, 'NIP: ' . $sprzedawca['NIP'], 0, 0, 'L');
$pdf->Cell(90, 10, 'NIP: ' . $klient['NIP'], 0, 1, 'L');
$pdf->Ln(10);


$pdf->SetFont('dejavusans', 'B', 12);
$pdf->SetFillColor(200, 220, 255);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetLineWidth(0.3);
$pdf->Cell(10, 10, 'Lp.', 1, 0, 'C', 1);
$pdf->Cell(50, 10, 'Nazwa produktu', 1, 0, 'C', 1);
$pdf->Cell(20, 10, 'Ilość', 1, 0, 'C', 1);
$pdf->Cell(50, 10, 'Cena jedn. (zł)', 1, 0, 'C', 1);
$pdf->Cell(50, 10, 'Wartość (zł)', 1, 1, 'C', 1);


$pdf->SetFont('dejavusans', '', 12);
$total = 0;
foreach ($produkty as $produkt) {
    
    $wartosc = $produkt[1] * $produkt[2];
    $total += $wartosc;
    $pdf->Cell(10, 10, $lp++, 1, 0, 'C');
    $pdf->Cell(50, 10, $produkt[0], 1);
    $pdf->Cell(20, 10, $produkt[1], 1, 0, 'C');
    $pdf->Cell(50, 10, number_format($produkt[2], 2, ',', '.'), 1, 0, 'R');
    $pdf->Cell(50, 10, number_format($wartosc, 2, ',', '.'), 1, 1, 'R');
}

// Suma
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->Cell(130, 10, 'Razem:', 1, 0, 'R', 1);
$pdf->Cell(50, 10, number_format($total, 2, ',', '.') . ' zł', 1, 1, 'R');



$pdf->Ln(10);
$pdf->SetFont('dejavusans', '', 10);
$pdf->MultiCell(0, 10, '* Zwolniony z podatku VAT ze względu na nieprzekroczenie limitu wartości sprzedaży w ubiegłym roku podatkowym (art. 113 ust. 1 i 9).', 0, 'L');

updateInvoiceNumber($currentInvoiceNumber);

header('Content-Type: application/pdf');


$pdf->Output($file_name . '.pdf', 'I');
