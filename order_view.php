<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }
    
    th, td {
        padding: 8px;
        text-align: left;
        margin: 0;
    }


    input[type="text"],
    input[type="number"],
    select,
    textarea {
        width: 100%;
        padding: 6px;
        box-sizing: border-box;
        margin: -5;
    }


    input[type="checkbox"] {
        width: auto; 
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    input[type="date"] {
        width: 100%; 
    }

    input[type="submit"],
    button {
        padding: 8px 16px;
        cursor: pointer;
        background-color: #48693E;
        color: white;
        border: none;
        border-radius: 4px;
    }

    .header a.active {
        background-color: #4CAF50;
    }
    select[name="kind_of_wood"] {
        width: 50px; 
    }
    </style>
<body>

    <h2>Zamówienia</h2>

    <table border='1'>
        <tr>
            <th>Nr</th>
            <th>Drewno</th>
            <th>Wymiary</th>
            <th style="border-right: 0px";>Lakier</th>
            <th>Olej</th>
            <th>Frez</th>
            <th>Telefon</th>
            <th>Cena</th>
            <th>Status</th>
            <th>Źródło</th>
            <th>Termin wykonania</th>
            <th>Komentarze</th>
            <th>Akcje</th>
        </tr>
        <?php
        include_once 'config/order_status.php';
        require_once 'config/DatabaseConnection.php';
        require_once 'Order.php';
        $mysqli = DatabaseConnection::getConnection();


        $orderModel = new Order($mysqli);
        $orders = $orderModel->getAllOrders();

        if ($orders && $orders->num_rows > 0) {
            while ($row = $orders->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['order_id']; ?></td>
                    <td><?php echo $row['kind_of_wood']; ?></td>
                    <td><?php echo $row['dimensions']; ?></td>
                    <td><?php echo $row['is_varnished'] ? 'Tak' : ''; ?></td>
                    <td><?php echo $row['is_oiled'] ? 'Tak' : ''; ?></td>
                    <td><?php echo $row['is_milled'] ? 'Tak' : ''; ?></td>
                    <td><a href="tel:<?php echo $row['phone_number']; ?>"><?php echo $row['phone_number']; ?></a></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><?php echo getStatus($row['order_status']); ?></td>
                    <td><?php echo $row['source']; ?></td>
                    <td><?php echo $row['order_deadline']; ?></td>
                    <td><?php echo $row['comments']; ?></td>
                    <?php echo "<td><button onclick='openForm(" . $row["order_id"] . ")'>Edytuj</button>"; ?>
                    <a href="#" onclick="confirmDelete(<?php echo $row['order_id']; ?>)">Usuń</a>
                    <input type="hidden" name="edit_id" value="<?php echo $row['order_id'] ?>">

                    </td>
                    </form>

                </tr>
            <?php endwhile;
        } else { ?>
            <tr>
                <td colspan='10'>Brak zamówień do wyświetlenia.</td>
            </tr>
        <?php } ?>
        <tr>
            <form action="add_order.php" method="post">
                <td><input  name="order_id" placeholder="Auto" disabled type="hidden"></td>
                <td><select type="text" name="kind_of_wood">
                        <option value="Buk">Buk</option>
                        <option value="Dąb">Dąb</option>
                        <option value="Jesion">Jesion</option>
                    </select>
                </td>
                <td><input type="text" name="dimensions"></td>
                <td><input type="checkbox" name="is_varnished"></td>
                <td><input type="checkbox" name="is_oiled"></td>
                <td><input type="checkbox" name="is_milled"></td>
                <td><input type="text" name="phone_number"></td>
                <td><input type="number" step="any" name="price"></td>
                <td>
                    <select name="order_status">
                        <option value="0">W realizacji</option>
                        <option value="1">Do wysłania</option>
                        <option value="2">Wysłane</option>
                        <option value="3">Zakończone</option>
                    </select>
                </td>
                <td><select type="text" name="source">
                        <option value="Allegro">Allegro</option>
                        <option value="E-mail">E-mail</option>
                        <option value="SMS">SMS</option>
                        <option value="WhatsApp">WhatsApp</option>
                        <option value="OLX">OLX</option>
                        <option value="inne">inne</option>
                    </select>
                </td>
                <td><input type="date" name="order_deadline" value="<?php echo date('Y-m-d', strtotime('+13 days')); ?>"></td>
                <td><textarea name="comments"></textarea></td>
                <td colspan="2"><input type="submit" value="Dodaj zamówienie"></td>
            </form>
        </tr>
    </table><br>
    <script>
        function openForm(orderId) {
            this.orderId = orderId;

            var currentUrl = window.location.href;

            if (currentUrl.indexOf('edit_id=') === -1) {

                var separator = currentUrl.indexOf('?') !== -1 ? '&' : '?';
                var editLink = currentUrl + separator + "edit_id=" + this.orderId;

                window.location.href = editLink;
            } else {

                var updatedLink = currentUrl.replace(/edit_id=\d+/g, 'edit_id=' + this.orderId);

                window.location.href = updatedLink;
            }
        }
        function confirmDelete(orderId) {
    
    var confirmation = confirm("Czy na pewno chcesz usunąć to zamówienie? A nie przenieść do Archiwum?");
    if (confirmation) {
        window.location.href = "delete_order.php?id=" + orderId;
    } else {}
}
    </script>
</body>


<?php require_once 'OrderEditor.php';
require_once 'EditOrderForm.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["edit_id"])) {
    $order_id = $_GET["edit_id"];
    $orderEditor = new OrderEditor($mysqli);
    $orderData = $orderEditor->getOrderById($order_id);

    if ($orderData) {

        $editOrderForm = new EditOrderForm($orderData);
        echo $editOrderForm->render();
    } else {
        echo "Nie można znaleźć zamówienia.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_id"])) {
    $order_id = $_POST["edit_id"];
    $formData = $_POST;
    $orderEditor = new OrderEditor($mysqli);
    $success = $orderEditor->updateOrder($order_id, $formData);

    if ($success) {
        echo "Juhuuu! Zamówienie zostało zaktualizowane pomyślnie.";
        echo "<script>setTimeout(function() { window.location.href = 'index.php'; }, 1000);</script>";
    } else {
        echo "Wystąpił problem podczas aktualizacji zamówienia.";
    }
}
