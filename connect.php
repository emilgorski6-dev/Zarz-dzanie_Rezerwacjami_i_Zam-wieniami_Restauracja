<?php
$con = mysqli_connect("localhost", "root", "", "restauracja");

if (mysqli_connect_errno()) {
    echo "Nieprawidłowe połączenie do bazy: " . mysqli_connect_error();
    exit();
}
?>