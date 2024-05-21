<?php

function getStatus($status) {
    switch ($status) {
        case 0:
            return "W realizacji";
        case 1:
            return "Do wysłania";
        case 2:
            return "Wysłane";
        case 3:
            return "Zakończone";
        case 4:
            return "Anulowane";
    }
}

?>
