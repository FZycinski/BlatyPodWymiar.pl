<?php
class EditOrder {
    private $order_id;
    private $kind_of_wood;
    private $dimensions;
    private $is_varnished;
    private $is_oiled;
    private $is_milled;
    private $phone_number;
    private $price;
    private $order_status;
    private $source;
    private $order_deadline;
    private $comments;

    public function __construct() {
        $this->order_id = $_POST['order_id'];
        $this->kind_of_wood = $_POST['kind_of_wood'];
        $this->dimensions = $_POST['dimensions'];
        $this->is_varnished = isset($_POST['is_varnished']) ? 1 : 0;
        $this->is_oiled = isset($_POST['is_oiled']) ? 1 : 0;
        $this->is_milled = isset($_POST['is_milled']) ? 1 : 0;
        $this->phone_number = $_POST['phone_number'];
        $this->price = $_POST['price'];
        $this->order_status = $_POST['order_status'];
        $this->source = $_POST['source'];
        $this->order_deadline = $_POST['order_deadline'];
        $this->comments = $_POST['comments'];
    }

    public function getOrderId() {
        return $this->order_id;
    }

    public function getKindOfWood() {
        return $this->kind_of_wood;
    }

    public function getDimensions() {
        return $this->dimensions;
    }

    public function getIsVarnished() {
        return $this->is_varnished;
    }

    public function getIsOiled() {
        return $this->is_oiled;
    }

    public function getIsMilled() {
        return $this->is_milled;
    }

    public function getPhoneNumber() {
        return $this->phone_number;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getOrderStatus() {
        return $this->order_status;
    }

    public function getSource() {
        return $this->source;
    }

    public function getOrderDeadline() {
        return $this->order_deadline;
    }

    public function getComments() {
        return $this->comments;
    }
}
?>
