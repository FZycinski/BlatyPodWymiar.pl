<?php
class ShippingModel {
    private $mysqli;

    public function __construct($mysqli) {
        if ($mysqli) {
            echo "Obiekt mysqli przekazany do ShippingModel.<br>";
        } else {
            echo "Brak obiektu mysqli w konstruktorze ShippingModel.<br>";
        }
        $this->mysqli = $mysqli;
    }
    

    public function getShippingData($orderId) {
        $query = "SELECT * FROM additional_order_data WHERE order_id = ?";
        
        if ($stmt = $this->mysqli->prepare($query)) {
            $stmt->bind_param('i', $orderId);
            $stmt->execute();
            
            $result = $stmt->get_result()->fetch_assoc();
            
            if ($result) {
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
                        'street' => $result['delivery_address_street'],
                        'streetNumber' => ' ',
                        'postalCode' => $result['delivery_address_zipCode'],
                        'city' => $result['delivery_address_city'],
                        'state' => 'AL',
                        'countryCode' => 'PL',
                        'email' => $result['buyer_email'],
                        'phone' => $result['delivery_address_phoneNumber'],
                    ],
                    'pickup' => [
                        'name' => $result['delivery_address_firstName'] . ' ' . $result['delivery_address_lastName'],
                        'street' => $result['delivery_address_street'],
                        'streetNumber' => ' ',
                        'postalCode' => $result['delivery_address_zipCode'],
                        'city' => $result['delivery_address_city'],
                        'state' => 'AL',
                        'countryCode' => 'PL',
                        'email' => $result['buyer_email'],
                        'phone' => $result['delivery_address_phoneNumber'],
                    ],
                    'referenceNumber' => $result['order_id'],
                    'description' => $result['item_name'], 
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
                    // 'cashOnDelivery' => [
                    //     'amount' => '',
                    //     'currency' => 'PLN',
                    //     'ownerName' => 'Jan Kowalski',
                    //     'iban' => 'PL48109024022441789739167589'
                    // ],
                    'createdDate' => date("Y-m-d H:i:s"),
                    'canceledDate' => date("Y-m-d H:i:s", strtotime("+2 weeks")),
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
                echo "Brak wyników dla podanego order_id: " . $orderId;
                return null;
            }
        } else {
            echo "Błąd przygotowania zapytania: " . $this->mysqli->error;
            return null;
        }
    }
}
?>
