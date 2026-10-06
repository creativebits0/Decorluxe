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

require_once 'notification_model.php';

function addQuotation($appointment_id, $quote_no, $client_id, $project_name, $valid_until, $total, $items, $status,  $created_by)
{
    global $conn;
    $conn->begin_transaction();

    try {
        // insert into quotations
        $stmt = $conn->prepare("
            INSERT INTO quotations (appointment_id,quotation_no, client_id, project_name, valid_until, total_amount, status, created_by)
            VALUES (?,?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("isissdsi", $appointment_id, $quote_no, $client_id, $project_name, $valid_until, $total, $status, $created_by);
        $stmt->execute();
        $quotation_id = $conn->insert_id;
        $stmt->close();

        // insert quotation items
        $item_no = 1;

        foreach ($items as $it) {
            $itemStmt = $conn->prepare("
        INSERT INTO quotation_items 
        (quotation_id, item_no,area_name,item_id, quantity, unit_price, total, description)
        VALUES (?, ?,?, ?, ?, ?, ?, ?)
    ");

            $itemStmt->bind_param(
                "iisiddds",
                $quotation_id,
                $item_no,
                $it['area_name'],
                $it['item_id'],
                $it['qty'],
                $it['unit_price'],
                $it['sub_total'],
                $it['description']
            );

            $itemStmt->execute();
            $itemStmt->close();
            $item_no++;
        }

        $itemStmt->close();

        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Quotation Add Error: " . $e->getMessage());
        return false;
    }
}


function createQuotationFromAppointment($appointment_id)
{
    global $conn;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $services = getAppointmentServices($appointment_id);
    $appointment = getAppointmentById($appointment_id);

    if (!$appointment) {
        return false;
    }

    // CHECK MEASUREMENTS EXIST


    $measurementCheck = $conn->prepare("
        SELECT COUNT(*) as total
        FROM site_measurements
        WHERE appointment_id = ?
    ");

    $measurementCheck->bind_param("i", $appointment_id);
    $measurementCheck->execute();

    $measurementResult =
        $measurementCheck->get_result()->fetch_assoc();

    if (($measurementResult['total'] ?? 0) <= 0) {
        return false;
    }

    // CHECK APPOINTMENT STATUS


    if (
        strtolower($appointment['status']) !== 'completed'
    ) {
        return false;
    }

    // PREVENT DUPLICATE QUOTATIONS


    $existing = getQuotationByAppointment($appointment_id);

    if ($existing) {
        return $existing['quotation_id'];
    }

    // CREATE QUOTATION


    $quotation_no =
        'QTN-' . date('Y') . '-' . rand(1000, 9999);

    $project_name = '';
    $valid_until  =
        date('Y-m-d', strtotime('+30 days'));

    $total_amount = 0;

    $status = 'Pending';

    //LOGGED-IN USER


    $created_by = $_SESSION['id'] ?? 0;

    // CREATE QUOTATION
    $stmt = $conn->prepare("
    INSERT INTO quotations
    (
        quotation_no,
        appointment_id,
        client_id,
        project_name,
        valid_until,
        total_amount,
        status,
        created_by
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

    if (!$stmt) {
        die($conn->error);
    }

    $stmt->bind_param(
        "siissdsi",
        $quotation_no,
        $appointment_id,
        $appointment['client_id'],
        $project_name,
        $valid_until,
        $total_amount,
        $status,
        $created_by
    );

    $stmt->execute();

    $quotation_id = $conn->insert_id;


    if (!empty($services)) {

        $stmtItem = $conn->prepare("
        INSERT INTO quotation_services 
        (quotation_id, service_id)
        VALUES (?, ?)
    ");

        foreach ($services as $s) {

            $service_id = $s['service_id'];

            $stmtItem->bind_param(
                "ii",
                $quotation_id,
                $service_id
            );

            $stmtItem->execute();
        }

        $stmtItem->close();
    }
    return $quotation_id;
}

// function getQuotationById($id)
// {
//     global $conn;
//     $stmt = $conn->prepare("SELECT * FROM quotations WHERE quotation_id = ?");
//     $stmt->bind_param("i", $id);
//     $stmt->execute();
//     $quotation = $stmt->get_result()->fetch_assoc();
//     $stmt->close();
//     return $quotation;
// }

function getQuotationById($id)
{
    global $conn;

    $sql = "
        SELECT q.*, p.status AS project_status
        FROM quotations q
        LEFT JOIN projects p
            ON q.quotation_id = p.quotation_id
        WHERE q.quotation_id = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $quotation = $stmt->get_result()->fetch_assoc();

    $stmt->close();

    return $quotation;
}

function getQuotationItemsGrouped($quotation_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT 
            qi.*,
            qa.area_name,
            qa.material_total,
            qa.labour_charge,
            qa.transport_charge,
            qa.other_charge,
            qa.area_total,
            qa.description,
            i.itemName
        FROM quotation_items qi
        LEFT JOIN quotation_areas qa 
            ON qa.area_id = qi.area_id
        LEFT JOIN items i
            ON i.item_id = qi.item_id
        WHERE qi.quotation_id = ?
        ORDER BY qi.area_id, qi.item_no
    ");

    $stmt->bind_param("i", $quotation_id);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $grouped = [];

    foreach ($result as $row) {

        $areaId = $row['area_id'];

        $grouped[$areaId]['area_name'] = $row['area_name'];

        // store area-level values ONLY ONCE
        $grouped[$areaId]['area_total'] = $row['area_total'];
        $grouped[$areaId]['material_total'] = $row['material_total'];
        $grouped[$areaId]['labour_charge'] = $row['labour_charge'];
        $grouped[$areaId]['transport_charge'] = $row['transport_charge'];
        $grouped[$areaId]['other_charge'] = $row['other_charge'];
        $grouped[$areaId]['description'] = $row['description'];

        $grouped[$areaId]['items'][] = $row;
    }

    return $grouped;
}

function checkAndCreateQuotation($appointment_id)
{
    $appointment = getAppointmentById($appointment_id);

    if (!$appointment) {
        return false;
    }


    if (
        strtolower(trim($appointment['appointment_type'])) == "site visit" &&
        strtolower(trim($appointment['status'])) == "completed"
    ) {

        if (hasMeasurements($appointment_id)) {

            if (!getQuotationByAppointment($appointment_id)) {

                return createQuotationFromAppointment($appointment_id);
            }
        }
    }

    return false;
}
// function getQuotations($limit, $offset)
// {
//     global $conn;

//     $sql = "
//         SELECT 
//             q.*,
//             COUNT(qi.quotation_id) AS item_count
//         FROM quotations q
//         LEFT JOIN quotation_items qi 
//             ON q.quotation_id = qi.quotation_id
//         GROUP BY q.quotation_id
//         ORDER BY q.created_at DESC
//         LIMIT ? OFFSET ?
//     ";

//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("ii", $limit, $offset);
//     $stmt->execute();

//     $result = $stmt->get_result();
//     $quotes = $result->fetch_all(MYSQLI_ASSOC);

//     $stmt->close();
//     return $quotes;
// }


// function countQuotations()
// {
//     global $conn;

//     $sql = "SELECT COUNT(*) as total FROM quotations";
//     $result = mysqli_query($conn, $sql);
//     $row = mysqli_fetch_assoc($result);

//     return $row['total'];
// }

function getQuotations($limit, $offset, $search = '')
{
    global $conn;

    $searchParam = "%{$search}%";

    $sql = "
        SELECT
            q.*,
            c.fullname,
            COUNT(qi.quotation_id) AS item_count
        FROM quotations q

        LEFT JOIN clients c
            ON q.client_id = c.client_id

        LEFT JOIN quotation_items qi
            ON q.quotation_id = qi.quotation_id

        WHERE (
            q.quotation_no LIKE ?
            OR c.fullname LIKE ?
            OR q.status LIKE ?
            OR q.created_at LIKE ?
            OR q.valid_until LIKE ?
        )

        GROUP BY q.quotation_id

        ORDER BY q.created_at DESC

        LIMIT ? OFFSET ?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sssssii",
        $searchParam,
        $searchParam,
        $searchParam,
        $searchParam,
        $searchParam,
        $limit,
        $offset
    );

    $stmt->execute();

    $result = $stmt->get_result();
    $quotes = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $quotes;
}

function countQuotations($search = '')
{
    global $conn;

    $searchParam = "%{$search}%";

    $sql = "
        SELECT COUNT(*) AS total

        FROM quotations q

        LEFT JOIN clients c
            ON q.client_id = c.client_id

        WHERE (
            q.quotation_no LIKE ?
            OR c.fullname LIKE ?
            OR q.status LIKE ?
            OR q.created_at LIKE ?
            OR q.valid_until LIKE ?
        )
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sssss",
        $searchParam,
        $searchParam,
        $searchParam,
        $searchParam,
        $searchParam
    );

    $stmt->execute();

    $result = $stmt->get_result();

    $row = $result->fetch_assoc();

    $stmt->close();

    return $row['total'];
}

function getQuotationByAppointment($appointment_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT * FROM quotations 
        WHERE appointment_id = ?
        LIMIT 1
    ");

    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}



function editQuotation(
    $quotation_id,
    $client_id,
    $project_name,
    $valid_until,
    $total,
    $areas,
    $status,
    $created_by
) {

    global $conn;

    $conn->begin_transaction();

    try {

        // UPDATE QUOTATION
        $stmt = $conn->prepare("
            UPDATE quotations
            SET
                client_id=?,
                project_name=?,
                valid_until=?,
                total_amount=?,
                status=?,
                created_by=?
            WHERE quotation_id=?
        ");

        $stmt->bind_param(
            "issdsii",
            $client_id,
            $project_name,
            $valid_until,
            $total,
            $status,
            $created_by,
            $quotation_id
        );

        $stmt->execute();
        $stmt->close();

        // DELETE OLD AREAS
        $stmt = $conn->prepare("
            DELETE FROM quotation_areas
            WHERE quotation_id=?
        ");

        $stmt->bind_param("i", $quotation_id);

        $stmt->execute();
        $stmt->close();

        // INSERT AREAS
        foreach ($areas as $area) {

            $stmt = $conn->prepare("
                INSERT INTO quotation_areas
                (
                    quotation_id,
                    area_name,
                    material_total,
                    labour_charge,
                    transport_charge,
                    other_charge,
                    area_total,
                    description
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "isddddds",
                $quotation_id,
                $area['area_name'],
                $area['material_total'],
                $area['labour_charge'],
                $area['transport_charge'],
                $area['other_charge'],
                $area['area_total'],
                $area['description']
            );

            $stmt->execute();

            $area_id = $conn->insert_id;

            $stmt->close();

            // INSERT ITEMS
            $item_no = 1;

            foreach ($area['items'] as $item) {

                $itemStmt = $conn->prepare("
                    INSERT INTO quotation_items
                    (
                        quotation_id,
                        area_id,
                        item_no,
                        item_id,
                        quantity,
                        unit_price,
                        total
                    )
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");

                $itemStmt->bind_param(
                    "iiiiddd",
                    $quotation_id,
                    $area_id,
                    $item_no,
                    $item['item_id'],
                    $item['qty'],
                    $item['unit_price'],
                    $item['sub_total']
                );

                $itemStmt->execute();

                $item_no++;
            }
        }


        
        $conn->commit();

        return true;
    } catch (Exception $e) {

        $conn->rollback();

        error_log($e->getMessage());

        return false;
    }
}



function getQuotesImages($quotation_id)
{

    global $conn;

    $stmt = $conn->prepare(
        "SELECT * FROM quotation_images WHERE quotation_id=?"
    );

    $stmt->bind_param("i", $quotation_id);

    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getQuotationServices($quotation_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT service_id
        FROM quotation_services
        WHERE quotation_id = ?
    ");

    $stmt->bind_param("i", $quotation_id);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    return array_column($result, 'service_id');
}

function expireOldQuotations()
{
    global $conn;

    $stmt = $conn->prepare("
        UPDATE quotations
        SET status = 'Expired'
        WHERE status = 'Pending'
        AND valid_until < CURDATE()
    ");

    $stmt->execute();

    $stmt->close();
}

