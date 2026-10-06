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

function getBookingReportData($filters = [])
{
    global $conn;

    $where = [];

    $report_type = $filters['report_type'] ?? '';

    /*
    |--------------------------------------------------------------------------
    | Daily Report
    |--------------------------------------------------------------------------
    */
    if ($report_type == 'daily') {

        $from_date = $filters['from_date'] ?? '';
        $to_date   = $filters['to_date'] ?? '';

        if (!empty($from_date) && !empty($to_date)) {

            $from_date = mysqli_real_escape_string($conn, $from_date);
            $to_date   = mysqli_real_escape_string($conn, $to_date);

            $where[] = "a.appointment_date 
                        BETWEEN '$from_date' AND '$to_date'";
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Monthly Report
    |--------------------------------------------------------------------------
    */ elseif ($report_type == 'monthly') {

        $selected_month = $filters['selected_month'] ?? '';

        if (!empty($selected_month)) {

            $month = date('m', strtotime($selected_month));
            $year  = date('Y', strtotime($selected_month));

            $where[] = "MONTH(a.appointment_date) = '$month'";
            $where[] = "YEAR(a.appointment_date) = '$year'";
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Yearly Report
    |--------------------------------------------------------------------------
    */ elseif ($report_type == 'yearly') {

        $year = $filters['year'] ?? '';

        if (!empty($year)) {

            $year = mysqli_real_escape_string($conn, $year);

            $where[] = "YEAR(a.appointment_date) = '$year'";
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Client Filter
    |--------------------------------------------------------------------------
    */
    if (!empty($filters['client'])) {

        $client = mysqli_real_escape_string($conn, $filters['client']);

        $where[] = "a.client_id = '$client'";
    }

    /*
    |--------------------------------------------------------------------------
    | Service Filter
    |--------------------------------------------------------------------------
    */
    if (!empty($filters['service'])) {

        $service = mysqli_real_escape_string($conn, $filters['service']);

        // $where[] = "a.service_id = '$service'";
        $where[] = "aps.service_id = '$service'";
    }

    /*
    |--------------------------------------------------------------------------
    | Status Filter
    |--------------------------------------------------------------------------
    */
    if (!empty($filters['status'])) {

        $status = mysqli_real_escape_string($conn, $filters['status']);

        $where[] = "a.status = '$status'";
    }

    /*
    |--------------------------------------------------------------------------
    | Build WHERE SQL
    |--------------------------------------------------------------------------
    */
    $whereSQL = '';

    if (count($where) > 0) {
        $whereSQL = "WHERE " . implode(" AND ", $where);
    }

    /*
    |--------------------------------------------------------------------------
    | Main Query
    |--------------------------------------------------------------------------
    */
    // $sql = "
    //     SELECT
    //         a.appointment_id,
    //         a.appointment_date,
    //         a.status,
    //         a.appointment_type,

    //         c.fullname,

    //         s.service_name

    //     FROM appointments a

    //     LEFT JOIN clients c
    //         ON a.client_id = c.client_id

    //     LEFT JOIN services s
    //         ON a.service_id = s.service_id

    //     $whereSQL

    //     ORDER BY a.appointment_id DESC
    // ";

    $sql = "
    SELECT
        a.appointment_id,
        a.appointment_date,
        a.status,
        a.appointment_type,

        c.fullname,

        GROUP_CONCAT(s.service_name SEPARATOR ', ') AS service_name

    FROM appointments a

    LEFT JOIN clients c
        ON a.client_id = c.client_id

    LEFT JOIN appointment_services aps
        ON a.appointment_id = aps.appointment_id

    LEFT JOIN services s
        ON aps.service_id = s.service_id

    $whereSQL

    GROUP BY a.appointment_id

    ORDER BY a.appointment_id DESC
";

    $result = mysqli_query($conn, $sql);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function fetchAllClients()
{

    global $conn;

    $result = mysqli_query($conn, "SELECT * FROM clients");

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


function fetchAllServices()
{

    global $conn;

    $result = mysqli_query($conn, "SELECT * FROM services");

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
