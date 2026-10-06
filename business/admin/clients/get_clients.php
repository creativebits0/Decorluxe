<?php
require_once '../../../data/admin/client_model.php';
header('Content-Type: application/json');

$clients = getAllClientsWithAddresses();
echo json_encode($clients);
?>

