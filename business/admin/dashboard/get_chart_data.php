<?php

require_once '../../../data/admin/dashboard_model.php';

$type = $_GET['type'] ?? '';
$filter = $_GET['filter'] ?? 'overall';

$response = [];

switch ($type) {

    case 'sales':
        $response = getSalesChartData($filter);
        break;

    case 'expense':
        $response = getExpenseChartData($filter);
        break;

    case 'purchase':
        $response = getPurchaseChartData($filter);
        break;
}

header('Content-Type: application/json');
echo json_encode($response);