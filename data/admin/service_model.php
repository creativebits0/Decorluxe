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
function getServicesForSelect()
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT service_id, service_name
        FROM services
        WHERE status = 1
        ORDER BY service_name
    ");

    $stmt->execute();

    $result = $stmt->get_result();
    $services = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $services;
}
function getService($limit, $offset, $search = '')
{
    global $conn;
    $searchParam = "%{$search}%";

    $sql = "SELECT service_id, service_name, base_price, description, status FROM services
            WHERE ( service_name LIKE ? OR base_price LIKE ? OR description LIKE ?) ORDER BY service_name ASC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii",$searchParam,$searchParam,$searchParam, $limit, $offset);

    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $service;
}


function countService($search = '')
{
    global $conn;

    $searchParam = "%{$search}%";

    $sql = "SELECT COUNT(*) AS total
            FROM services
            WHERE service_name LIKE ?
               OR base_price LIKE ?
               OR description LIKE ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stmt->close();

    return $row['total'];
}

function addService($service_name, $base_price, $description)
{
    global $conn;

    $stmt = $conn->prepare("
        INSERT INTO services (service_name, base_price, description)
        VALUES (?, ?, ?)
    ");

    $stmt->bind_param("sds", $service_name, $base_price, $description);

    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function getServiceById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM services WHERE service_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();
    $stmt->close();
    return $service;
}

function updateService($id, $service_name, $base_price, $description)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE services SET service_name=?,base_price=?,description=? WHERE service_id=?");
    $stmt->bind_param("sdsi", $service_name, $base_price, $description, $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}


function updateServiceStatus($id, $status)
{
    global $conn;
    $sql = "UPDATE services SET status = ? WHERE service_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $status, $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}


function getAllServices()
{
    global $conn;

    $sql = "SELECT 
                s.*, 
                IFNULL(h.show_on_home,0) as show_on_home
            FROM services s
            LEFT JOIN homepage_services h
            ON s.service_id = h.service_id
            ORDER BY s.service_name ASC";

    $stmt = $conn->prepare($sql);

    $stmt->execute();

    $result = $stmt->get_result();

    $services = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $services;
}
