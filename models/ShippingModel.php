<?php
class ShippingModel {
    private $mysqli;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    public function getShippingData($customerId) {
        // Przygotuj zapytanie SQL do pobrania danych klienta
        $query = "SELECT * FROM additional_order_data WHERE customer_id = ?";
        
        // Przygotuj zapytanie
        if ($stmt = $this->mysqli->prepare($query)) {
            // Przypisz parametry i wykonaj zapytanie
            $stmt->bind_param('i', $customerId);
            $stmt->execute();
            
            // Pobierz wynik zapytania
            $result = $stmt->get_result()->fetch_assoc();
            
            if ($result) {
                // Przetwórz dane na strukturę danych wysyłki zgodną z wymaganiami Allegro
                $shippingData = [
                    'sender' => [
                        'name' => 'Artur Życiński',
                        'company' => 'Dekor-Stone Artur Życiński',
                        'street' => 'Słowackiego',
                        'streetNumber' => '43',
                        'postalCode' => '38-500',
                        'city' => 'Sanok',
                        'state' => 'AL',
                        'countryCode' => 'PL',
                        'email' => 'artur.zycinski.dekor.stone@gmail.com',
                        'phone' => '535026224',
                    ],
                    'receiver' => [
                        'name' => $result['delivery_address_firstName'] . ' ' . $result['delivery_address_lastName'],
                        'phoneNumber' => $result['delivery_address_phoneNumber'],
                        'street' => $result['delivery_address_street'],
                        'streetNumber' => $result[''],
                        'postalCode' => $result['delivery_address_zipCode'],
                        'city' => $result['delivery_address_city'],
                        'state' => 'AL',
                        'countryCode' => 'PL',
                        'email' => $result['buyer_email'],
                        'phone' => $result['delivery_address_phoneNumber'],
                    ],
                    'pickup' => [
                        'name' => $result['delivery_address_firstName'] . ' ' . $result['delivery_address_lastName'],
                        'phoneNumber' => $result['delivery_address_phoneNumber'],
                        'street' => $result['delivery_address_street'],
                        'streetNumber' => $result[''],
                        'postalCode' => $result['delivery_address_zipCode'],
                        'city' => $result['delivery_address_city'],
                        'state' => 'AL',
                        'countryCode' => 'PL',
                        'email' => $result['buyer_email'],
                        'phone' => $result['delivery_address_phoneNumber'],
                    ],
                    'referenceNumber' => $result['order_id'],
                    'description' => $result['item_namme'], 
                    'packages' => [
                        [
                            'waybill' => 'string',
                            'type' => 'DOX',
                            'length' => [
                                'value' => 12,
                                'unit' => 'CENTIMETER'
                            ],
                            'width' => [
                                'value' => 12,
                                'unit' => 'CENTIMETER'
                            ],
                            'height' => [
                                'value' => 12,
                                'unit' => 'CENTIMETER'
                            ],
                            'weight' => [
                                'value' => 12.45,
                                'unit' => 'KILOGRAMS'
                            ]
                        ]
                    ],
                    'insurance' => [
                        'amount' => $result['order_paid_amount'],
                        'currency' => 'PLN'
                    ],
                    'cashOnDelivery' => [
                        'amount' => '2.50',
                        'currency' => 'PLN',
                        'ownerName' => 'Jan Kowalski',
                        'iban' => 'PL48109024022441789739167589'
                    ],
                    'createdDate' => '2023-05-29T12:34:56Z',
                    'canceledDate' => '2023-06-29T12:34:56Z',
                    'carrier' => 'string',
                    'labelFormat' => 'ZPL',
                    'additionalServices' => [
                        'ADDITIONAL_HANDLING'
                    ],
                    'additionalProperties' => [
                        'property1' => 'string',
                        'property2' => 'string'
                    ]
                ];
                
                return $shippingData;
            } else {
                // Brak wyników - obsłuż ten przypadek w odpowiedni sposób
                return null;
            }
        } else {
            // Obsłuż błąd przygotowania zapytania
            echo "Błąd przygotowania zapytania: " . $this->mysqli->error;
            return null;
        }
    }
}

$mysqli = DatabaseConnection::getConnection();
$shippingModel = new ShippingModel($mysqli);
$customerId = 123; 
$shippingData = $shippingModel->getShippingData($customerId);

echo '<pre>';
print_r($shippingData);
echo '</pre>';
?>