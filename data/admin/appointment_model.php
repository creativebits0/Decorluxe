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


function getAllAppointments()
{
    global $conn;

    $sql = "SELECT 
a.*,
c.fullname

FROM appointments a
LEFT JOIN clients c 
ON a.client_id = c.client_id
LEFT JOIN quotations q 
ON q.appointment_id = a.appointment_id

ORDER BY appointment_date DESC";

    $result = mysqli_query($conn, $sql);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}



function getAppointments($limit, $offset, $search = '')
{
    global $conn;

    $searchParam = "%{$search}%";

    $sql = "
        SELECT
            a.*,
            c.fullname,
            q.quotation_id,
            q.quotation_no,
            p.project_id,
            p.project_no 
        FROM appointments a
        JOIN clients c ON a.client_id = c.client_id
        LEFT JOIN quotations q ON q.appointment_id = a.appointment_id
        LEFT JOIN projects p ON p.appointment_id = a.parent_appointment_id
        WHERE (
            c.fullname LIKE ?
            OR a.appointment_type LIKE ?
            OR a.status LIKE ?
            OR a.appointment_date LIKE ?
        )
        ORDER BY a.appointment_date DESC
        LIMIT ? OFFSET ?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssssii",
        $searchParam,
        $searchParam,
        $searchParam,
        $searchParam,
        $limit,
        $offset
    );

    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function countAppointments($search = '')
{
    global $conn;

    $searchParam = "%{$search}%";

    $sql = "
        SELECT COUNT(*) AS total
        FROM appointments a
        JOIN clients c ON a.client_id = c.client_id
        WHERE (
            c.fullname LIKE ?
            OR a.appointment_type LIKE ?
            OR a.status LIKE ?
            OR a.appointment_date LIKE ?
        )
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssss",
        $searchParam,
        $searchParam,
        $searchParam,
        $searchParam
    );

    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_assoc()['total'];
}


function addAppointment(
    $client,
    $type,
    $date,
    $address_id,
    $notes
) {
    global $conn;

    $stmt = $conn->prepare("
        INSERT INTO appointments
        (client_id,appointment_type,appointment_date,address_id,notes)
        VALUES(?,?,?,?,?)
    ");

    $stmt->bind_param(
        "issis",
        $client,
        $type,
        $date,
        $address_id,
        $notes
    );

    if ($stmt->execute()) {
        return $conn->insert_id;
    }

    return false;
}
function addAppointmentService(
    $appointment_id,
    $service_id
) {
    global $conn;

    $stmt = $conn->prepare("
        INSERT INTO appointment_services
        (appointment_id, service_id)
        VALUES (?, ?)
    ");

    $stmt->bind_param(
        "ii",
        $appointment_id,
        $service_id
    );

    return $stmt->execute();
}
function getAppointmentById($id)
{

    global $conn;

    $stmt = $conn->prepare("

    SELECT 
        a.*,
        c.fullname,
        ca.address

    FROM appointments a

    LEFT JOIN clients c 
        ON a.client_id = c.client_id

    LEFT JOIN client_addresses ca 
        ON a.address_id = ca.address_id

    WHERE a.appointment_id = ?

    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

function getAppointmentServices($appointment_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT s.*
        FROM appointment_services aps
        INNER JOIN services s
            ON aps.service_id = s.service_id
        WHERE aps.appointment_id = ?
    ");

    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function updateAppointment(
    $id,
    $client,
    $type,
    $date,
    $location,
    $status,
    $notes
) {

    global $conn;

    $sql = "UPDATE appointments
SET
client_id=?,
appointment_type=?,
appointment_date=?,
address_id=?,
status=?,
notes=?
WHERE appointment_id=?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ississi",
        $client,
        $type,
        $date,
        $location,
        $status,
        $notes,
        $id
    );

    return $stmt->execute();
}

function updateAppointmentServices($appointment_id, $services)
{
    global $conn;

    // Remove existing services
    $stmt = $conn->prepare("
        DELETE FROM appointment_services
        WHERE appointment_id = ?
    ");

    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();
    $stmt->close();

    // Insert selected services
    foreach ($services as $service_id) {

        $stmt = $conn->prepare("
            INSERT INTO appointment_services
            (appointment_id, service_id)
            VALUES (?, ?)
        ");

        $stmt->bind_param(
            "ii",
            $appointment_id,
            $service_id
        );

        $stmt->execute();
        $stmt->close();
    }

    return true;
}
function deleteAppointment($id)
{

    global $conn;

    $stmt = $conn->prepare("
DELETE FROM appointments
WHERE appointment_id=?
");

    $stmt->bind_param("i", $id);

    return $stmt->execute();
}



function saveMeasurement(
    $appointment_id,
    $area_name,
    $image_path,
    $notes
){
    global $conn;

    $stmt = $conn->prepare("
        INSERT INTO site_measurements
        (
            appointment_id,
            area_name,
            image_path,
            notes
        )
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "isss",
        $appointment_id,
        $area_name,
        $image_path,
        $notes
    );

    return $stmt->execute();
}

function updateMeasurement(
    $measurement_id,
    $area_name,
    $image_path,
    $notes
){
    global $conn;

    $stmt = $conn->prepare("
        UPDATE site_measurements
        SET
            area_name = ?,
            image_path = ?,
            notes = ?
        WHERE measurement_id = ?
    ");

    $stmt->bind_param(
        "sssi",
        $area_name,
        $image_path,
        $notes,
        $measurement_id
    );

    return $stmt->execute();
}

function deleteMeasurementsByAppointment($appointment_id)
{
    global $conn;

    // Get all image names first
    $stmt = $conn->prepare("
        SELECT image_path
        FROM site_measurements
        WHERE appointment_id = ?
    ");

    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();

    $result = $stmt->get_result();

    $uploadDir = __DIR__ . "/../../uploads/measurements/";

    while ($row = $result->fetch_assoc()) {

        if (!empty($row['image_path'])) {

            $file = $uploadDir . $row['image_path'];

            if (file_exists($file)) {
                unlink($file);
            }
        }
    }

    $stmt->close();

    // Delete all measurement records
    $stmt = $conn->prepare("
        DELETE FROM site_measurements
        WHERE appointment_id = ?
    ");

    $stmt->bind_param("i", $appointment_id);

    return $stmt->execute();
}

function getMeasurementsByAppointment($id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT *
        FROM site_measurements
        WHERE appointment_id = ?
        ORDER BY measurement_id ASC
    ");

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}


function getMeasurementIdsByAppointment($appointment_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT measurement_id
        FROM site_measurements
        WHERE appointment_id = ?
    ");

    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();

    $result = $stmt->get_result();

    $measurementIds = [];

    while ($row = $result->fetch_assoc()) {
        $measurementIds[] = $row['measurement_id'];
    }

    return $measurementIds;
}

function deleteMeasurement($measurement_id)
{
    global $conn;

    $stmt = $conn->prepare("
        DELETE FROM site_measurements
        WHERE measurement_id=?
    ");

    $stmt->bind_param("i",$measurement_id);

    return $stmt->execute();
}
// check the status for appointment is changeable
function canUpdateStatus($appointment_id, $newStatus)
{
    global $conn;

    // Always allow Completed status
    if (trim(strtolower($newStatus)) == 'completed') {
        return true;
    }

    $stmt = $conn->prepare("
        SELECT measurement_id
        FROM site_measurements
        WHERE appointment_id = ?
        LIMIT 1
    ");

    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();

    $result = $stmt->get_result();

    // block other status changes after measurement
    return $result->num_rows == 0;
}
// check whether measurement has been saved
function hasMeasurements($id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT COUNT(*) cnt
        FROM site_measurements
        WHERE appointment_id=?
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $row = $stmt->get_result()->fetch_assoc();

    return $row['cnt'] > 0;
}

function createProjectStart($parent_id, $client, $date, $location, $notes)
{
    global $conn;

    $type = "Project Start";
    $status = "Scheduled";

    $stmt = $conn->prepare("
        INSERT INTO appointments
        (client_id,appointment_type,appointment_date,address_id,status,notes,parent_appointment_id)
        VALUES(?,?,?,?,?,?,?)
    ");

    $stmt->bind_param(
        "ississi",
        $client,
        $type,
        $date,
        $location,
        $status,
        $notes,
        $parent_id
    );

    return $stmt->execute();
}

function getProjectStartByParent($parent_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT * FROM appointments
        WHERE parent_appointment_id = ?
        AND appointment_type = 'Project Start'
        LIMIT 1
    ");

    $stmt->bind_param("i", $parent_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}
function updateProjectStartDate($parent_id, $date)
{
    global $conn;

    $stmt = $conn->prepare("
        UPDATE appointments
        SET appointment_date = ?
        WHERE parent_appointment_id = ?
        AND appointment_type = 'Project Start'
    ");

    $stmt->bind_param("si", $date, $parent_id);

    return $stmt->execute();
}
