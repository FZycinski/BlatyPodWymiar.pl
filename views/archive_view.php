<?php
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login_view.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archiwum</title>
    
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
    </style>
</head>

<body>
<?php include_once("structure/header.php"); ?>
    <h2>Archiwum</h2>

    <table border='1'>
        <tr>
            <th>Nr</th>
            <th>Drewno</th>
            <th>Wymiary</th>
            <th>Lakier</th>
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
        include_once '../config/order_status.php';
        require_once '../config/DatabaseConnection.php';

$mysqli = DatabaseConnection::getConnection();
require_once '../models/Archive.php';

$orderModel = new Archive($mysqli);
$orders = $orderModel->getAllOrders();

        if ($orders && $orders->num_rows > 0) {
            while ($row = $orders->fetch_assoc()) : ?>
                <tr>
    <form action="../controllers/delete_from_archive.php" method="GET">
        <td><?php echo $row['order_id']; ?></td>
        <td><?php echo $row['kind_of_wood']; ?></td>
        <td><?php echo $row['dimensions']; ?></td>
        <td><?php echo $row['is_varnished'] ? 'Tak' : ''; ?></td>
        <td><?php echo $row['is_oiled'] ? 'Tak' : ''; ?></td>
        <td><?php echo $row['is_milled'] ? 'Tak' : ''; ?></td>
        <td><a href="tel:<?php echo $row['phone_number']; ?>"><?php echo $row['phone_number']; ?></a></td>
        <td><?php echo $row['price'];?></td>
        <td><?php echo getStatus($row['order_status']); ?></td>
        <td><?php echo $row['source'];?></td>
        <td><?php echo $row['order_deadline']; ?></td>
        <td><?php echo $row['comments']; ?></td>
        <td>
            <input type="hidden" name="id" value="<?php echo $row['order_id']; ?>">
            <button type="submit" onclick="confirmDelete()">Usuń</button>
        </td>
    </form>
</tr>

            <?php endwhile;
        } else { ?>
            <tr>
                <td colspan='10'>Brak zamówień do wyświetlenia.</td>
            </tr>
        <?php } ?>
    </table>
</body>
<script>
function confirmDelete() {
    if (confirm("Czy na pewno chcesz usunąć to zamówienie?")) {
        document.getElementById("deleteForm").submit();
    }
}
</script>

</html>
