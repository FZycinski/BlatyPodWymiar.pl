<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ShippingController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function createShippingLabel($accessToken, $shippingData) {
        $url = "https://api.allegro.pl/shipment-management/shipments/create-commands";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($shippingData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $accessToken",
            "Content-Type: application/vnd.allegro.public.v1+json",
            "Accept: application/vnd.allegro.public.v1+json"
        ));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!$httpCode == 200) {
            echo "Failed to create shipping label. HTTP Code: " . $httpCode . ". Response: " . $response;
        }

        return json_decode($response, true);
    }

    public function checkShipmentStatus($accessToken, $commandId) {
        $url = "https://api.allegro.pl/shipment-management/shipments/create-commands/$commandId";
        $status = "IN_PROGRESS";
        $errors = null;
        $shipmentId = null;

        while ($status == "IN_PROGRESS") {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization: Bearer $accessToken",
                "Content-Type: application/vnd.allegro.public.v1+json",
                "Accept: application/vnd.allegro.public.v1+json"
            ));

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode == 200) {
                $responseArray = json_decode($response, true);
                $status = $responseArray['status'];
                $errors = $responseArray['errors'];
                $shipmentId = $responseArray['shipmentId'];
                if ($status != "IN_PROGRESS") {
                    break;
                }
                sleep(5);
            } else {
                echo "Failed to check shipment status. HTTP Code: " . $httpCode . ". Response: " . $response;
                return null;
            }
        }

        return [
            'commandId' => $commandId,
            'status' => $status,
            'errors' => $errors,
            'shipmentId' => $shipmentId
        ];
    }

    public function getShipmentLabel($accessToken, $shipmentIds, $pageSize = "A4", $cutLine = false) {
        $url = "https://api.allegro.pl/shipment-management/label";
        $data = [
            'shipmentIds' => $shipmentIds,
            'pageSize' => $pageSize,
            'cutLine' => $cutLine
        ];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $accessToken",
            "Content-Type: application/vnd.allegro.public.v1+json",
            "Accept: application/vnd.allegro.public.v1+json"
        ));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200) {
            echo "Shipment label fetched successfully: " . $response;
        } else if ($httpCode == 204){
            echo "Failed to fetch shipment label. HTTP Code: " . $httpCode . ". Response: " . $response;
        }
        else {
            echo "Failed to fetch shipment label. HTTP Code: " . $httpCode . ". Response: " . $response;
        }

        return json_decode($response, true);
    }

    public function main() {
        if (isset($_GET["code"])) {
            $accessToken = $this->getAccessToken($_GET["code"]);
    
            if (isset($_SESSION['order_ids'])) {
                $orderIds = $_SESSION['order_ids'];
                unset($_SESSION['order_ids']);
                
                foreach ($orderIds as $orderId) {
                    $shippingData = $this->model->getShippingData($orderId);
    
                    if ($shippingData) {
                        $labelResponse = $this->createShippingLabel($accessToken, $shippingData);
                        print_r($labelResponse);
    
                        if (isset($labelResponse['commandId'])) {
                            $commandId = $labelResponse['commandId'];
                            $statusResponse = $this->checkShipmentStatus($accessToken, $commandId);
                            print_r($statusResponse);
    
                            if (isset($statusResponse['shipmentId'])) {
                                $shipmentId = $statusResponse['shipmentId'];
                                $label = $this->getShipmentLabel($accessToken, [$shipmentId]);
                                include('views/shipping_view.php');
                            } else {
                                echo "Failed to retrieve shipmentId from status response.";
                            }
                        } else {
                            echo "Failed to retrieve commandId from label response.";
                        }
                    } else {
                        echo "No shipping data found for order ID: $orderId";
                    }
                }
            } else {
                echo "No order IDs provided.";
            }
        } else {
            $this->getAuthorizationCode();
        }
    }
    

    private function getAuthorizationCode() {
        $authorization_redirect_url = AUTH_URL . "?response_type=code&client_id="
            . CLIENT_ID . "&redirect_uri=" . REDIRECT_URI . '&prompt=confirm';
        echo '<html><body><a href="' . $authorization_redirect_url . '">Zaloguj do Allegro</a></body></html>';
    }

    private function getAccessToken($authorization_code) {
        $authorization = base64_encode(CLIENT_ID . ':' . CLIENT_SECRET);
        $headers = array("Authorization: Basic {$authorization}", "Content-Type: application/x-www-form-urlencoded");
        $content = "grant_type=authorization_code&code=" . urlencode($authorization_code) . "&redirect_uri=" . REDIRECT_URI;
        $ch = curl_init(TOKEN_URL);
        curl_setopt_array($ch, array(
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $content
        ));
        $tokenResult = curl_exec($ch);
        $resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($tokenResult === false || $resultCode !== 200) {
            exit("Something went wrong $resultCode $tokenResult");
        }

        return json_decode($tokenResult)->access_token;
    }
}
?>
