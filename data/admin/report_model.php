<?php

$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "decorluxe_prms_db";
// $port = 3307;

$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die("Connection Failed : " . mysqli_connect_error());
} else {
    // echo"It's Working";
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// report filtering
function getReportPurchase($whereSQL = '')
{
    global $conn;

    $sql = "
        SELECT 
            p.purchase_id,
            p.purchase_date,
            p.bill_no,
            p.discount,
            p.total_amount,

            s.supplier_name,

            pi.quantity,
            pi.discount,
            pi.unit_price,
            pi.total,

            i.itemName

        FROM purchases p

        LEFT JOIN suppliers s
            ON p.supplier_id = s.supplier_id

        LEFT JOIN purchase_items pi
            ON p.purchase_id = pi.purchase_id

        LEFT JOIN items i
            ON pi.item_id = i.item_id

        $whereSQL

        ORDER BY p.purchase_id DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

function getSalesReport($whereSQL = '')
{
    global $conn;

    $sql = "
        SELECT
            s.sales_id,
            s.sales_date,
            s.invoice_no,
            s.total_amount,

            si.item_id,
            si.qty,
            si.discount,
            si.unit_price,
            si.total,

            i.itemName

        FROM sales s

        LEFT JOIN sales_items si
            ON s.sales_id = si.sales_id

        LEFT JOIN items i
            ON si.item_id = i.item_id

        $whereSQL

        ORDER BY s.sales_id DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}



function getExpenseCategories()
{
    global $conn;

    $sql = "SELECT * FROM expense_category ORDER BY expense_name ASC";
    $result = mysqli_query($conn,$sql);

    $data = [];

    while($row=mysqli_fetch_assoc($result))
    {
        $data[] = $row;
    }

    return $data;
}



function getExpenseCategoryById($id)
{
    global $conn;

    $id = mysqli_real_escape_string($conn, $id);

    $sql = "SELECT * FROM expense_category WHERE id='$id'";
    $result = mysqli_query($conn, $sql);

    return mysqli_fetch_assoc($result);
}

function checkExpenseLimit($category_id, $expense_date, $exclude_id = 0)
{
    global $conn;

    $query = "
        SELECT 
            ea.id,
            ea.expense_date,
            ec.expense_period,
            ec.expense_name
        FROM expenses ea
        INNER JOIN expense_category ec
            ON ea.category_id = ec.id
        WHERE ea.category_id = '$category_id'
        AND ea.id != '$exclude_id'
    ";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {

        $period = $row['expense_period'];

        /*
        -------------------------
        DAILY
        -------------------------
        */

        if ($period == 'Daily') {

            if ($row['expense_date'] == $expense_date) {

                return [
                    'status' => true,
                    'warning' => true,
                    'message' => 'This expense is already added today'
                ];
            }
        }

        /*
        -------------------------
        MONTHLY
        -------------------------
        */

        elseif ($period == 'Monthly') {

            if (
                date('Y-m', strtotime($row['expense_date'])) ==
                date('Y-m', strtotime($expense_date))
            ) {

                return [
                    'status' => false,
                    'message' => 'Monthly expense already added for this month'
                ];
            }
        }

        /*
        -------------------------
        YEARLY
        -------------------------
        */

        elseif ($period == 'Yearly') {

            if (
                date('Y', strtotime($row['expense_date'])) ==
                date('Y', strtotime($expense_date))
            ) {

                return [
                    'status' => false,
                    'message' => 'Yearly expense already added for this year'
                ];
            }
        }
    }

    return [
        'status' => true
    ];
}

function saveExpense($category_id, $amount, $expense_date, $remark)
{
    global $conn;

    $category_id  = mysqli_real_escape_string($conn, $category_id);
    $amount       = mysqli_real_escape_string($conn, $amount);
    $expense_date = mysqli_real_escape_string($conn, $expense_date);
    $remark       = mysqli_real_escape_string($conn, $remark);

    $sql = "INSERT INTO expenses
            (category_id, amount, expense_date, remark)
            VALUES
            ('$category_id', '$amount', '$expense_date', '$remark')";

    return mysqli_query($conn, $sql);
}

function updateExpenseCategory($id, $expense_name, $expense_period)
{
    global $conn;

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE expense_category
         SET expense_name = ?, expense_period = ?
         WHERE id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssi",
        $expense_name,
        $expense_period,
        $id
    );

    return mysqli_stmt_execute($stmt);
}

function getTodayExpenses()
{
    global $conn;

    $sql = "SELECT e.*, c.expense_name
            FROM expenses e
            JOIN expense_category c
            ON c.id = e.category_id
            WHERE e.expense_date = CURDATE()
            ORDER BY e.id DESC";

    return mysqli_query($conn, $sql);
}


function getMonthlyExpenses()
{
    global $conn;

    $sql = "SELECT e.*, c.expense_name
            FROM expenses e
            JOIN expense_category c
            ON c.id = e.category_id
            WHERE MONTH(e.expense_date)=MONTH(CURDATE())
            AND YEAR(e.expense_date)=YEAR(CURDATE())
            ORDER BY e.id DESC";

    return mysqli_query($conn, $sql);
}


function getYearlyExpenses()
{
    global $conn;

    $sql = "SELECT e.*, c.expense_name
            FROM expenses e
            JOIN expense_category c
            ON c.id = e.category_id
            WHERE YEAR(e.expense_date)=YEAR(CURDATE())
            ORDER BY e.id DESC";

    return mysqli_query($conn, $sql);
}
function getExpenseById($id)
{
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM expenses WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_assoc();
}

function updateExpense($id,$category,$amount,$date,$remark)
{
    global $conn;

    $sql = "UPDATE expenses SET
            category_id='$category',
            amount='$amount',
            expense_date='$date',
            remark='$remark'
            WHERE id='$id'";

    return mysqli_query($conn,$sql);
}

function getEmployeeSalary($user_id){
    global $conn;

    $sql = "SELECT * FROM employee_salary
            WHERE user_id='$user_id'
            AND status=1
            ORDER BY effective_from DESC
            LIMIT 1";

    $res = mysqli_query($conn,$sql);

    return mysqli_fetch_assoc($res);
}
function addWageAuto($user_id, $date, $desc, $qty = 1)
{
    $salary = getEmployeeSalary($user_id);

    if (!$salary) return false;

    $type = $salary['salary_type'];
    $rate = $salary['amount'];

    if ($type == 'daily') {
        $amount = $rate;
    }
    elseif ($type == 'task') {
        $amount = $rate * $qty;
    }
    elseif ($type == 'monthly') {
        // handled separately
        return false;
    }

    return insertWage($user_id, $date, $amount, $desc);
}

// function generateMonthlySalary($user_id, $month, $year)
// {
//     $salary = getEmployeeSalary($user_id);

//     if ($salary['salary_type'] != 'monthly') return false;

//     $amount = $salary['amount'];

//     $date = $year . '-' . $month . '-01';

//     return insertWage($user_id, $date, $amount, 'Monthly Salary');
// }


function insertWage($user_id, $date, $amount, $desc, $month = null, $year = null)
{
    global $conn;

    try {

        $user_id = mysqli_real_escape_string($conn, $user_id);
        $date    = mysqli_real_escape_string($conn, $date);
        $amount  = mysqli_real_escape_string($conn, $amount);
        $desc    = mysqli_real_escape_string($conn, $desc);

        $sql = "INSERT INTO wages(user_id, work_date, amount, description, salary_month, salary_year)
                VALUES('$user_id','$date','$amount','$desc','$month','$year')";

        mysqli_query($conn, $sql);

        return "success";

    } catch (mysqli_sql_exception $e) {

        if ($e->getCode() == 1062) {
            return "duplicate";
        }

        return "error";
    }
}

function generateMonthlySalary($user_id, $month, $year)
{
    global $conn;

    try {

        // 1. get salary details
        $salary = getEmployeeSalary($user_id);

        if (!$salary) {
            return "error";
        }

        if ($salary['salary_type'] != 'monthly') {
            return "error";
        }

        $amount = $salary['amount'];

        // 2. insert monthly salary
        $sql = "INSERT INTO wages(user_id, work_date, amount, description, salary_month, salary_year)
                VALUES(
                    '$user_id',
                    CURDATE(),
                    '$amount',
                    'Monthly Salary - ".date('F Y')."',
                    '$month',
                    '$year'
                )";

        mysqli_query($conn, $sql);

        return "success";

    } catch (mysqli_sql_exception $e) {

        if ($e->getCode() == 1062) {
            return "duplicate";
        }

        return "error";
    }
}

function saveEmployeeSalary($user_id,$type,$amount,$date){
    global $conn;

    // deactivate old salary
    mysqli_query($conn,"UPDATE employee_salary 
        SET status=0 
        WHERE user_id='$user_id'");

    // insert new
    $sql = "INSERT INTO employee_salary(user_id,salary_type,amount,effective_from)
            VALUES('$user_id','$type','$amount','$date')";

    return mysqli_query($conn,$sql);
}
function getAllEmployees(){
    global $conn;

    $sql = "SELECT id,firstname FROM users;";
    $res = mysqli_query($conn,$sql);

    return mysqli_fetch_all($res,MYSQLI_ASSOC);
}

function getWagesByEmployee($user_id)
{
    global $conn;

    $sql = "SELECT * FROM wages 
            WHERE user_id='$user_id'
            ORDER BY work_date DESC";

    $res = mysqli_query($conn,$sql);

    return mysqli_fetch_all($res,MYSQLI_ASSOC);
}

function getTodayWages()
{
    global $conn;

    $sql = "SELECT w.*, u.firstname
            FROM wages w
            JOIN users u
            ON u.id = w.user_id
            WHERE w.work_date = CURDATE()
            ORDER BY u.id DESC";

    return mysqli_query($conn, $sql);
}


function getMonthlyWages()
{
    global $conn;

    $sql = "SELECT w.*, u.firstname
            FROM wages w
            JOIN users u
            ON u.id = w.user_id
            WHERE MONTH(w.work_date)=MONTH(CURDATE())
            AND YEAR(w.work_date)=YEAR(CURDATE())
            ORDER BY u.id DESC";

    return mysqli_query($conn, $sql);
}
function getYearlyWages()
{
    global $conn;

    $sql = "SELECT w.*, u.firstname
            FROM wages w
            JOIN users u
            ON u.id = w.user_id
            WHERE YEAR(w.work_date)=YEAR(CURDATE())
            ORDER BY u.id DESC";

    return mysqli_query($conn, $sql);
}
function getWageWithSalary($id)
{
    global $conn;

    $sql = "SELECT 
                w.*,
                u.firstname,
                s.salary_type,
                s.amount AS base_salary
            FROM wages w
            JOIN users u ON u.id = w.user_id
            LEFT JOIN employee_salary s ON s.user_id = w.user_id
            WHERE w.id='$id'";

    $res = mysqli_query($conn,$sql);

    return mysqli_fetch_assoc($res);
}
function updateWage($id,$amount,$date,$desc)
{
    global $conn;

    $sql = "UPDATE wages SET
            amount='$amount',
            work_date='$date',
            description='$desc'
            WHERE id='$id'";

    return mysqli_query($conn,$sql);
}

// for profit reort

function getNetSales($whereSQL = '')
{
    global $conn;

    $sql = "
        SELECT 
            SUM(total_amount - IFNULL(discount,0)) AS net_sales
        FROM sales
        $whereSQL
    ";

    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);

    return $row['net_sales'] ?? 0;
}


function getNetPurchases($whereSQL = '')
{
    global $conn;

    $sql = "
        SELECT 
            SUM(total_amount - IFNULL(discount,0)) AS net_purchase
        FROM purchases
        $whereSQL
    ";

    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);

    return $row['net_purchase'] ?? 0;
}

function getTotalExpenses($whereSQL = '')
{
    global $conn;

    $sql = "
        SELECT SUM(amount) AS total_expense
        FROM expenses
        $whereSQL
    ";

    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);

    return $row['total_expense'] ?? 0;
}

function getProfitReport($whereSQL = '')
{
    $sales    = getNetSales($whereSQL);
    $purchase = getNetPurchases($whereSQL);
    $expense  = getTotalExpenses($whereSQL);

    $profit = $sales - $purchase - $expense;

    return [
        'sales' => $sales,
        'purchase' => $purchase,
        'expense' => $expense,
        'profit' => $profit
    ];
}