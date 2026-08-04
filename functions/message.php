<?php
$messages = [
     1 => 'Data successfully added',
     2 => 'Data successfully updated',
     3 => 'Data successfully deleted',
     4 => 'MySQL Database Error, please check the entered query'
];

$messages_id = isset($_GET['message']) ? (int) $_GET['message'] : 0;

echo $messages[$messages_id] ?? 'Something went wrong, please consult with the administrator';