<?php 
include '../database/database_conn.php';

$customer_id = $_GET['customer_id'];
$query = "DELETE FROM customers WHERE customer_id='{$customer_id}'";

if (mysqli_query($db_connect, $query)) {
    $message = 3;
} else {
    $message = 4;
}

header("Location: ../public/index.php?message={$message}");