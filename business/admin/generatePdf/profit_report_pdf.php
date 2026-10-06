<?php

require_once __DIR__ . '/../../../lib/mpdf/vendor/autoload.php';
require_once __DIR__ . '/../../../data/admin/report_model.php';

$reportType = $_GET['report_type'] ?? '';

/*
--------------------------------------
FILTERS (same logic as report page)
--------------------------------------
*/

$whereSales = "";
$wherePurchase = "";
$whereExpense = "";
$whereProject = "";

if ($reportType == 'daily') {

    $whereSales    = "WHERE DATE(sales_date) = CURDATE()";
    $wherePurchase = "WHERE DATE(purchase_date) = CURDATE()";
    $whereExpense  = "WHERE DATE(expense_date) = CURDATE()";
    $whereProject  = "WHERE status='completed' AND DATE(actual_end_date) = CURDATE()";
} elseif ($reportType == 'monthly') {

    $whereSales    = "WHERE MONTH(sales_date)=MONTH(CURDATE()) AND YEAR(sales_date)=YEAR(CURDATE())";
    $wherePurchase = "WHERE MONTH(purchase_date)=MONTH(CURDATE()) AND YEAR(purchase_date)=YEAR(CURDATE())";
    $whereExpense  = "WHERE MONTH(expense_date)=MONTH(CURDATE()) AND YEAR(expense_date)=YEAR(CURDATE())";
    $whereProject  = "WHERE status='completed'
                      AND MONTH(actual_end_date)=MONTH(CURDATE())
                      AND YEAR(actual_end_date)=YEAR(CURDATE())";
} elseif ($reportType == 'yearly') {

    $whereSales    = "WHERE YEAR(sales_date)=YEAR(CURDATE())";
    $wherePurchase = "WHERE YEAR(purchase_date)=YEAR(CURDATE())";
    $whereExpense  = "WHERE YEAR(expense_date)=YEAR(CURDATE())";
    $whereProject  = "WHERE status='completed'
                      AND YEAR(actual_end_date)=YEAR(CURDATE())";
} else {

    $whereProject = "WHERE status='completed'";
}

/*
--------------------------------------
DATA CALCULATION
--------------------------------------
*/

// Sales Revenue
$salesSql = "
    SELECT SUM((si.qty * si.unit_price) - IFNULL(si.discount,0)) AS revenue
    FROM sales_items si
    INNER JOIN sales s ON s.sales_id = si.sales_id
    $whereSales
";
$salesRow = mysqli_fetch_assoc(mysqli_query($conn, $salesSql));
$salesRevenue = $salesRow['revenue'] ?? 0;


// Project Revenue
$projectSql = "
    SELECT SUM(total_amount) AS project_revenue
    FROM projects
    $whereProject
";
$projectRow = mysqli_fetch_assoc(mysqli_query($conn, $projectSql));
$projectRevenue = $projectRow['project_revenue'] ?? 0;


// Total Revenue
$totalRevenue = $salesRevenue + $projectRevenue;


// Purchase Cost
$purchaseSql = "
    SELECT SUM((pi.quantity * pi.unit_price) - IFNULL(pi.discount,0)) AS purchase_total
    FROM purchase_items pi
    INNER JOIN purchases p ON p.purchase_id = pi.purchase_id
    $wherePurchase
";
$purchaseRow = mysqli_fetch_assoc(mysqli_query($conn, $purchaseSql));
$totalPurchase = $purchaseRow['purchase_total'] ?? 0;


// Expenses
$expenseSql = "
    SELECT SUM(amount) AS expense_total
    FROM expenses
    $whereExpense
";
$expenseRow = mysqli_fetch_assoc(mysqli_query($conn, $expenseSql));
$totalExpense = $expenseRow['expense_total'] ?? 0;


// Profit
$netProfit = $totalRevenue - $totalPurchase - $totalExpense;

/*
--------------------------------------
PDF HTML
--------------------------------------
*/

$html = '
<style>

body{
    font-family:sans-serif;
    font-size:10pt;
    color:#000;
}

.header{
    border-bottom:2px solid #aa8c2c;
    padding-bottom:10px;
    margin-bottom:20px;
}

.logo{
    font-size:24pt;
    font-weight:bold;
}

.gold{ color:#aa8c2c; }

.title{
    text-align:right;
    font-size:18pt;
    font-weight:bold;
}

.filter-box{
    margin-top:15px;
    margin-bottom:20px;
    padding:5px;
    background:#f5f5f5;
    border:1px solid #ddd;
}

.report-table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}

.report-table th{
    background:#000;
    color:#fff;
    padding:10px;
    border:1px solid #fff;
}

.report-table td{
    border:1px solid #000;
    padding:8px;
}

.footer{
    margin-top:30px;
    font-size:9pt;
    color:#555;
}

</style>

<table width="100%" class="header">
    <tr>

        <td width="60%">
            <div class="logo">
                <span class="gold">DECOR</span>LUXE
            </div>
            <div>INTERIORS (PVT) LTD</div>
        </td>

        <td width="40%" class="title">
            Profit Report
        </td>

    </tr>
</table>

<table width="100%" class="filter-box">
    <tr>
        <td><strong>Report Type:</strong> ';

if ($reportType == 'daily') $html .= 'Daily';
elseif ($reportType == 'monthly') $html .= 'Monthly';
elseif ($reportType == 'yearly') $html .= 'Yearly';
else $html .= 'All';

$html .= '
        </td>
    </tr>
</table>

<table class="report-table">

    <tr>
        <th>Description</th>
        <th>Amount (Rs.)</th>
    </tr>

    <tr>
        <td>Sales Revenue</td>
        <td>' . number_format($salesRevenue, 2) . '</td>
    </tr>

    <tr>
        <td>Project Revenue</td>
        <td>' . number_format($projectRevenue, 2) . '</td>
    </tr>

    <tr>
        <td><b>Total Revenue</b></td>
        <td><b>' . number_format($totalRevenue, 2) . '</b></td>
    </tr>

    <tr>
        <td>Purchase Cost</td>
        <td>' . number_format($totalPurchase, 2) . '</td>
    </tr>

    <tr>
        <td>Expenses</td>
        <td>' . number_format($totalExpense, 2) . '</td>
    </tr>

    <tr>
        <td><b>Net Profit</b></td>
        <td><b>' . number_format($netProfit, 2) . '</b></td>
    </tr>

</table>

<div class="footer">
    <hr>
    Generated on ' . date('F d, Y h:i A') . '
</div>
';

$mpdf = new \Mpdf\Mpdf([
    'margin_top' => 15,
    'margin_bottom' => 15,
    'margin_left' => 10,
    'margin_right' => 10
]);

$mpdf->WriteHTML($html);
$mpdf->Output('Profit_Report.pdf', 'I');
