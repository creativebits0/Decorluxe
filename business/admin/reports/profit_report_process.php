<?php

require_once __DIR__ . '/../../../data/admin/report_model.php';

$reportType = $_GET['report_type'] ?? '';

// -------------------------------------------------
// BUILD DATE FILTER
// -------------------------------------------------

$whereSales = "";
$wherePurchase = "";
$whereExpense = "";
$whereProject = "";

if ($reportType == 'daily') {

    $whereSales = "WHERE DATE(s.sales_date) = CURDATE()";
    $wherePurchase = "WHERE DATE(p.purchase_date) = CURDATE()";
    $whereExpense = "WHERE DATE(e.expense_date) = CURDATE()";
    $whereProject = "WHERE p.status = 'completed' AND DATE(p.actual_end_date) = CURDATE()";

} elseif ($reportType == 'monthly') {

    $whereSales = "WHERE MONTH(s.sales_date) = MONTH(CURDATE())
                   AND YEAR(s.sales_date) = YEAR(CURDATE())";

    $wherePurchase = "WHERE MONTH(p.purchase_date) = MONTH(CURDATE())
                      AND YEAR(p.purchase_date) = YEAR(CURDATE())";

    $whereExpense = "WHERE MONTH(e.expense_date) = MONTH(CURDATE())
                     AND YEAR(e.expense_date) = YEAR(CURDATE())";

    $whereProject = "WHERE p.status = 'completed'
                     AND MONTH(p.actual_end_date) = MONTH(CURDATE())
                     AND YEAR(p.actual_end_date) = YEAR(CURDATE())";

} elseif ($reportType == 'yearly') {

    $whereSales = "WHERE YEAR(s.sales_date) = YEAR(CURDATE())";
    $wherePurchase = "WHERE YEAR(p.purchase_date) = YEAR(CURDATE())";
    $whereExpense = "WHERE YEAR(e.expense_date) = YEAR(CURDATE())";
    $whereProject = "WHERE p.status = 'completed'
                     AND YEAR(p.actual_end_date) = YEAR(CURDATE())";

} else {

    $whereSales = "";
    $wherePurchase = "";
    $whereExpense = "";
    $whereProject = "WHERE p.status = 'completed'";
}

/*
-------------------------------------------------
SALES REVENUE (ITEM LEVEL + DISCOUNT)
-------------------------------------------------
*/

$salesSql = "
    SELECT 
        SUM((si.qty * si.unit_price) - IFNULL(si.discount,0)) AS revenue
    FROM sales_items si
    INNER JOIN sales s ON s.sales_id = si.sales_id
    $whereSales
";

$salesResult = mysqli_query($conn, $salesSql);
$salesRow = mysqli_fetch_assoc($salesResult);
$salesRevenue = $salesRow['revenue'] ?? 0;

/*
-------------------------------------------------
PROJECT REVENUE (ONLY COMPLETED)
-------------------------------------------------
*/

$projectSql = "
    SELECT 
        SUM(total_amount) AS project_revenue
    FROM projects p
    $whereProject
";

$projectResult = mysqli_query($conn, $projectSql);
$projectRow = mysqli_fetch_assoc($projectResult);
$projectRevenue = $projectRow['project_revenue'] ?? 0;

/*
-------------------------------------------------
TOTAL REVENUE
-------------------------------------------------
*/

$totalRevenue = $salesRevenue + $projectRevenue;

/*
-------------------------------------------------
PURCHASE COST (ITEM LEVEL + DISCOUNT)
-------------------------------------------------
*/

$purchaseSql = "
    SELECT 
        SUM((pi.quantity * pi.unit_price) - IFNULL(pi.discount,0)) AS purchase_total
    FROM purchase_items pi
    INNER JOIN purchases p ON p.purchase_id = pi.purchase_id
    $wherePurchase
";

$purchaseResult = mysqli_query($conn, $purchaseSql);
$purchaseRow = mysqli_fetch_assoc($purchaseResult);
$totalPurchase = $purchaseRow['purchase_total'] ?? 0;

/*
-------------------------------------------------
EXPENSES
-------------------------------------------------
*/

$expenseSql = "
    SELECT 
        SUM(e.amount) AS expense_total
    FROM expenses e
    $whereExpense
";

$expenseResult = mysqli_query($conn, $expenseSql);
$expenseRow = mysqli_fetch_assoc($expenseResult);
$totalExpense = $expenseRow['expense_total'] ?? 0;

/*
-------------------------------------------------
NET PROFIT
-------------------------------------------------
*/

$netProfit = $totalRevenue - $totalPurchase - $totalExpense;

$chartData = [
    [
        "label" => "Total Revenue",
        "value" => (float) $totalRevenue
    ],
    [
        "label" => "Net Profit",
        "value" => (float) $netProfit
    ],
    [
        "label" => "Expenses",
        "value" => (float) $totalExpense
    ],
    [
        "label" => "Purchase Cost",
        "value" => (float) $totalPurchase
    ]
];
?>

<div class="container mt-4">

    <div class="row g-4">

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6>Sales Revenue</h6>
                    <h4 class="text-success">Rs. <?= number_format($salesRevenue,2) ?></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6>Project Revenue</h6>
                    <h4 class="text-primary">Rs. <?= number_format($projectRevenue,2) ?></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6>Total Revenue</h6>
                    <h4>Rs. <?= number_format($totalRevenue,2) ?></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6>Purchase Cost</h6>
                    <h4 class="text-warning">Rs. <?= number_format($totalPurchase,2) ?></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6>Expenses</h6>
                    <h4 class="text-danger">Rs. <?= number_format($totalExpense,2) ?></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6>Net Profit</h6>
                    <h4 class="<?= $netProfit >= 0 ? 'text-success' : 'text-danger' ?>">
                        Rs. <?= number_format($netProfit,2) ?>
                    </h4>
                </div>
            </div>
        </div>

    </div>

</div>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">Profit Overview Chart</h5>

            <canvas id="profitChart" height="100"></canvas>
        </div>
    </div>
</div>
    <script src="/decorluxe/assets/js/chart.min.js"></script>

<script>
    const chartLabels = <?= json_encode(array_column($chartData, 'label')) ?>;
    const chartValues = <?= json_encode(array_column($chartData, 'value')) ?>;

    const ctx = document.getElementById('profitChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Amount (Rs)',
                data: chartValues,
                backgroundColor: [
                    'rgba(40, 167, 69, 0.7)',   // Revenue
                    'rgba(0, 123, 255, 0.7)',   // Net Profit
                    'rgba(220, 53, 69, 0.7)',   // Expenses
                    'rgba(255, 193, 7, 0.7)'    // Purchase
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(0, 123, 255, 1)',
                    'rgba(220, 53, 69, 1)',
                    'rgba(255, 193, 7, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>