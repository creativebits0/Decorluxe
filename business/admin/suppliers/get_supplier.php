<?php
require_once '../../../data/admin/supplier_model.php';
header('Content-Type: application/json');

$suppliers = getAllSupplier();
echo json_encode($suppliers);
?>

