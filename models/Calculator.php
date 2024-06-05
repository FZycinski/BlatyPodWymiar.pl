<?php
namespace App\Models;

class Calculator
{
    public function calculatePrices($formsData)
    {
        $results = [];

        foreach ($formsData as $index => $form) {
            $woodType = $form['woodType'];
            $thickness = floatval($form['thickness']);
            $length = floatval($form['length']);
            $width = floatval($form['width']);
            $piece = intval($form['piece']);
            $varnish = isset($form['varnish']);
            $stain = isset($form['stain']);
            $oil = isset($form['oil']);
            $mill = isset($form['mill']);

            $basePricePerM2 = 0;
            switch ($woodType) {
                case "Jesion":
                    $basePricePerM2 = ($thickness === 38) ? 6.00 : (($thickness === 27) ? 4.10 : 0);
                    break;
                case "Dąb":
                    $basePricePerM2 = ($thickness === 38) ? 6.50 : (($thickness === 27) ? 4.60 : (($thickness === 19) ? 3.20 : 0));
                    break;
                case "Buk":
                    $basePricePerM2 = ($thickness === 38) ? 5.10 : (($thickness === 27) ? 3.60 : (($thickness === 19) ? 2.50 : 0));
                    break;
                default:
                    $basePricePerM2 = 0;
            }

            $area = $length * $width / 100;
            $priceForWider = ($width > 65) ? 1.2 : 1;
            $totalPrice = $area * $basePricePerM2 * $priceForWider;

            $varnishPrice = 2.00;
            $stainPrice = 0.5;
            $oilPrice = 2.00;
            $additionalPrice = 0;
            if ($varnish) $additionalPrice += $area * $varnishPrice;
            if ($stain) $additionalPrice += $area * $stainPrice;
            if ($oil) $additionalPrice += $area * $oilPrice;
            if ($mill) $additionalPrice += 10.00;

            $totalPrice += $additionalPrice;
            $totalPrice *= $piece;

            $priceWithDiscount = $totalPrice * 0.9;
            $allegroPieces = ceil($totalPrice / $basePricePerM2);

            $results[] = [
                'priceAllegro' => $totalPrice,
                'priceOutsideAllegro' => $priceWithDiscount,
                'piecesAllegro' => $allegroPieces
            ];
        }

        $summaryTotalAllegro = array_sum(array_column($results, 'priceAllegro'));
        $summaryTotalPrice = array_sum(array_column($results, 'priceOutsideAllegro'));
        $summaryDiscountedPrice = array_sum(array_column($results, 'piecesAllegro'));

        return [
            'results' => $results,
            'summary' => [
                'totalAllegro' => $summaryTotalAllegro,
                'totalPrice' => $summaryTotalPrice,
                'discountedPrice' => $summaryDiscountedPrice
            ]
        ];
    }
}
?>
