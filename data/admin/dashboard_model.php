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

/*TOTAL BOOKINGS*/

function getTotalBookings()
{
    global $conn;

    $sql = "
        SELECT COUNT(*) AS total
        FROM appointments
    ";

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    return $row['total'] ?? 0;
}


/*COMPLETED BOOKINGS*/

function getCompletedBookings()
{
    global $conn;

    $sql = "
        SELECT COUNT(*) AS total
        FROM appointments
        WHERE status = 'completed'
    ";

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    return $row['total'] ?? 0;
}


/*PENDING BOOKINGS*/

function getPendingBookings()
{
    global $conn;

    $sql = "
        SELECT COUNT(*) AS total
        FROM appointments
        WHERE status = 'Scheduled'
    ";

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    return $row['total'] ?? 0;
}

/*TOTAL Projects*/

function getTotalProjects()
{
    global $conn;

    $sql = "
        SELECT COUNT(*) AS total
        FROM projects
    ";

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    return $row['total'] ?? 0;
}

/*COMPLETED Projects*/

function getCompletedProjects()
{
    global $conn;

    $sql = "
        SELECT COUNT(*) AS total
        FROM projects
        WHERE status = 'completed'
    ";

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    return $row['total'] ?? 0;
}


/*PENDING Projects*/

function getPendingProjects()
{
    global $conn;

    $sql = "
        SELECT COUNT(*) AS total
        FROM projects
        WHERE status = 'Scheduled'
    ";

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    return $row['total'] ?? 0;
}
/*
------------------------------------------------
TODAY APPOINTMENTS
------------------------------------------------
*/

function getTodayAppointments()
{
    global $conn;

    $today = date('Y-m-d');

    $sql = "
        SELECT 
            a.*,
            c.fullname
        FROM appointments a
        LEFT JOIN clients c
        ON c.client_id = a.client_id
        WHERE DATE(a.appointment_date) = '$today'
        ORDER BY a.appointment_date ASC
    ";

    return mysqli_query($conn, $sql);
}


/*
------------------------------------------------
UPCOMING APPOINTMENTS
------------------------------------------------
*/

function getUpcomingAppointments()
{
    global $conn;

    $sql = "
        SELECT 
            a.*,
            c.fullname
        FROM appointments a
        LEFT JOIN clients c
        ON c.client_id = a.client_id
        WHERE a.appointment_date >= NOW()
        ORDER BY a.appointment_date ASC
        LIMIT 5
    ";

    return mysqli_query($conn, $sql);
}


/*
------------------------------------------------
MOST SALES ITEMS
------------------------------------------------
*/

// function getSalesChartData()
// {
//     global $conn;

//     $sql = "
//         SELECT 
//             i.itemName,
//             SUM(si.qty) AS total_qty
//         FROM sales_items si
//         JOIN items i
//         ON i.item_id = si.item_id
//         GROUP BY si.item_id
//         ORDER BY total_qty DESC
//         LIMIT 5
//     ";

//     $result = mysqli_query($conn, $sql);

//     $labels = [];
//     $data   = [];

//     while ($row = mysqli_fetch_assoc($result)) {

//         $labels[] = $row['itemName'];
//         $data[]   = $row['total_qty'];
//     }

//     return [
//         'labels' => $labels,
//         'data'   => $data
//     ];
// }
function getSalesChartData($filter = 'overall')
{
    global $conn;

    $where = "";

    if ($filter == 'today') {

        $where = "WHERE DATE(s.sales_date) = CURDATE()";
    } elseif ($filter == 'monthly') {

        $where = "WHERE MONTH(s.sales_date) = MONTH(CURDATE())
                  AND YEAR(s.sales_date) = YEAR(CURDATE())";
    }

    $sql = "
        SELECT 
            i.itemName,
            SUM(si.qty) AS total
        FROM sales_items si

        INNER JOIN items i
            ON i.item_id = si.item_id

        INNER JOIN sales s
            ON s.sales_id = si.sales_id

        $where

        GROUP BY si.item_id

        ORDER BY total DESC

        LIMIT 5
    ";

    $result = mysqli_query($conn, $sql);

    $labels = [];
    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {

        $labels[] = $row['itemName'];
        $data[] = $row['total'];
    }

    return [
        'labels' => $labels,
        'data' => $data
    ];
}

/*
------------------------------------------------
TOP EXPENSES
------------------------------------------------
*/

// function getExpenseChartData()
// {
//     global $conn;

//     $sql = "
//         SELECT 
//             ec.expense_name,
//             SUM(e.amount) AS total_amount
//         FROM expenses e
//         JOIN expense_category ec
//         ON ec.id = e.category_id
//         GROUP BY e.category_id
//         ORDER BY total_amount DESC
//         LIMIT 5
//     ";

//     $result = mysqli_query($conn, $sql);

//     $labels = [];
//     $data   = [];

//     while ($row = mysqli_fetch_assoc($result)) {

//         $labels[] = $row['expense_name'];
//         $data[]   = $row['total_amount'];
//     }

//     return [
//         'labels' => $labels,
//         'data'   => $data
//     ];
// }
function getExpenseChartData($filter = 'overall')
{
    global $conn;

    $where = "";

    if ($filter == 'today') {

        $where = "WHERE DATE(e.expense_date) = CURDATE()";

    } elseif ($filter == 'monthly') {

        $where = "WHERE MONTH(e.expense_date) = MONTH(CURDATE())
                  AND YEAR(e.expense_date) = YEAR(CURDATE())";
    }

    $sql = "
        SELECT 
            ec.expense_name,
            SUM(e.amount) AS total

        FROM expenses e

        INNER JOIN expense_category ec
            ON ec.id = e.category_id

        $where

        GROUP BY e.category_id

        ORDER BY total DESC

        LIMIT 5
    ";

    $result = mysqli_query($conn, $sql);

    $labels = [];
    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {

        $labels[] = $row['expense_name'];
        $data[] = $row['total'];
    }

    return [
        'labels' => $labels,
        'data' => $data
    ];
}

/*
------------------------------------------------
MOST PURCHASED ITEMS
------------------------------------------------
*/

// function getPurchaseChartData()
// {
//     global $conn;

//     $sql = "
//         SELECT 
//             i.itemName,
//             SUM(pi.quantity) AS total_qty
//         FROM purchase_items pi
//         JOIN items i
//         ON i.item_id = pi.item_id
//         GROUP BY pi.item_id
//         ORDER BY total_qty DESC
//         LIMIT 5
//     ";

//     $result = mysqli_query($conn, $sql);

//     $labels = [];
//     $data   = [];

//     while ($row = mysqli_fetch_assoc($result)) {

//         $labels[] = $row['itemName'];
//         $data[]   = $row['total_qty'];
//     }

//     return [
//         'labels' => $labels,
//         'data'   => $data
//     ];
// }
function getPurchaseChartData($filter = 'overall')
{
    global $conn;

    $where = "";

    if ($filter == 'today') {

        $where = "WHERE DATE(p.purchase_date) = CURDATE()";
    } elseif ($filter == 'monthly') {

        $where = "WHERE MONTH(p.purchase_date) = MONTH(CURDATE())
                  AND YEAR(p.purchase_date) = YEAR(CURDATE())";
    }

    $sql = "
        SELECT 
            i.itemName,
            SUM(pi.quantity) AS total

        FROM purchase_items pi

        INNER JOIN items i
            ON i.item_id = pi.item_id

        INNER JOIN purchases p
            ON p.purchase_id = pi.purchase_id

        $where

        GROUP BY pi.item_id

        ORDER BY total DESC

        LIMIT 5
    ";

    $result = mysqli_query($conn, $sql);

    $labels = [];
    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {

        $labels[] = $row['itemName'];
        $data[] = $row['total'];
    }

    return [
        'labels' => $labels,
        'data' => $data
    ];
}
