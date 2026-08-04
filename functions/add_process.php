<?php
include '../database/database_conn.php';

if (count($_POST) > 0)
{
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $created = date('Y-m-d');

    $query = "INSERT INTO customers (firstname, lastname, email, created) VALUES ('{$firstname}', '{$lastname}', '{$email}', '{$created}')";

    if (mysqli_query($db_connect, $query)) {
        $message = 1;
    } else {
        $message = 4;
    }
}

header("Location: ../public/index.php?message={$message}");