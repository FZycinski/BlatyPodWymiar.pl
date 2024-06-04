<?php

class EditOrderForm {
    private $orderData;

    public function __construct($orderData) {
        $this->orderData = $orderData;
    }

    public function render() {
        $form = '<form action="index.php" method="post">';
        $form .= '<input type="hidden" name="order_id" value="' . $this->orderData['order_id'] . '">';
        $form .= '<input type="hidden" name="edit_id" value="' . $this->orderData['order_id'] . '">';
        $form .= '<table>';
        $form .= '<tr>Edytujesz zamówienie nr: ' .$this ->orderData['order_id'] . '</tr><br>';
        $form .= '<tr><td>Rodzaj drewna:</td><td><select name="kind_of_wood">';
        $form .= '<option value="Buk"' . ($this->orderData['kind_of_wood'] == "Buk" ? ' selected' : '') . '>Buk</option>';
        $form .= '<option value="Dąb"' . ($this->orderData['kind_of_wood'] == "Dąb" ? ' selected' : '') . '>Dąb</option>';
        $form .= '<option value="Jesion"' . ($this->orderData['kind_of_wood'] == "Jesion" ? ' selected' : '') . '>Jesion</option>';        
        $form .= '<tr><td>Wymiary:</td><td><input type="text" name="dimensions" value="' . $this->orderData['dimensions'] . '"></td></tr>';
        $form .= '<tr><td>Lakier:</td><td><input type="checkbox" name="is_varnished" ' . ($this->orderData['is_varnished'] == '1' ? 'checked' : '') . '></td></tr>';
        $form .= '<tr><td>Olej:</td><td><input type="checkbox" name="is_oiled" ' . ($this->orderData['is_oiled'] == '1' ? 'checked' : '') . '></td></tr>';
        $form .= '<tr><td>Frez:</td><td><input type="checkbox" name="is_milled" ' . ($this->orderData['is_milled'] == '1' ? 'checked' : '') . '></td></tr>';
        
        
        $form .= '<tr><td>Telefon:</td><td><input type="number" name="phone_number" value="' . $this->orderData['phone_number'] . '"></td></tr>';
        $form .= '<tr><td>Cena:</td><td><input type="number" step="any" name="price" value="' . $this->orderData['price'] . '"></td></tr>';
        $form .= '<tr><td>Status:</td><td><select name="order_status">';
        $form .= '<option value="0"' . ($this->orderData['order_status'] == 0 ? ' selected' : '') . '>W realizacji</option>';
        $form .= '<option value="1"' . ($this->orderData['order_status'] == 1 ? ' selected' : '') . '>Do wysłania</option>';
        $form .= '<option value="2"' . ($this->orderData['order_status'] == 2 ? ' selected' : '') . '>Wysłane</option>';
        $form .= '<option value="3"' . ($this->orderData['order_status'] == 3 ? ' selected' : '') . '>Zakończone</option>';
        $form .= '<tr><td>Źródło:</td><td><select name="source">';
        $form .= '<option value="Allegro"' . ($this->orderData['source'] == 0 ? ' selected' : '') . '>Allegro</option>';
        $form .= '<option value="E-mail"' . ($this->orderData['source'] == 'E-mail' ? ' selected' : '') . '>E-mail</option>';
        $form .= '<option value="SMS"' . ($this->orderData['source'] == 'SMS' ? ' selected' : '') . '>SMS</option>';
        $form .= '<option value="WhatsApp"' . ($this->orderData['source'] == 'WhatsApp' ? ' selected' : '') . '>WhatsApp</option>';
        $form .= '<option value="OLX"' . ($this->orderData['source'] == 'OLX' ? ' selected' : '') . '>OLX</option>';
        $form .= '<option value="inne"' . ($this->orderData['source'] == 'inne' ? ' selected' : '') . '>inne</option>';
        $form .= '<tr><td>Termin wykonania:</td><td><input type="date" name="order_deadline" value="' . $this->orderData['order_deadline'] . '"></td></tr>';
        $form .= '<tr><td>Komentarze:</td><td><textarea name="comments">' . $this->orderData['comments'] . '</textarea></td></tr>';
        $form .= '<tr><td colspan="2"><input type="submit" value="Zapisz zmiany"></td></tr>';
        $form .= '</table>';
        $form .= '</form>';

        return $form;
    }

    public function getData() {
        $data = array(
            'order_id' => $_POST['order_id'],
            'kind_of_wood' => $_POST['kind_of_wood'],
            'dimensions' => $_POST['dimensions'],
            'is_varnished' => ($_POST['is_varnished']) ? 1 : 0,
            'is_oiled' => ($_POST['is_oiled']) ? 1 : 0,
            'is_milled' => ($_POST['is_milled']) ? 1 : 0,
            'phone_number' => $_POST['phone_number'],
            'price' => $_POST['price'],
            'order_status' => $_POST['order_status'],
            'source' => $_POST['source'],
            'order_deadline' => $_POST['order_deadline'],
            'comments' => $_POST['comments']
        );
        return $data;
    }
    
}