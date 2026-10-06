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

// function getSupplier($limit, $offset)
// {
//     global $conn;


//     $sql = "SELECT * FROM suppliers ORDER BY supplier_id DESC LIMIT ? OFFSET ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("ii", $limit, $offset);

//     $stmt->execute();
//     $result = $stmt->get_result();
//     $suppliers = $result->fetch_all(MYSQLI_ASSOC);
//     $stmt->close();
//     return $suppliers;
// }


// function countSupplier()
// {
//     global $conn;

//     $sql = "SELECT COUNT(*) as total FROM suppliers";
//     $result = mysqli_query($conn, $sql);
//     $row = mysqli_fetch_assoc($result);

//     return $row['total'];
// }

function getSupplier($limit, $offset, $search = '')
{
    global $conn;

    $searchParam = "%{$search}%";

    $sql = "
        SELECT *
        FROM suppliers
        WHERE (
            supplier_name LIKE ?
            OR address LIKE ?
            OR phone1 LIKE ?
            OR email LIKE ?
        )
        ORDER BY supplier_id DESC
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
    $result = $stmt->get_result();

    $suppliers = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    return $suppliers;
}

function countSupplier($search = '')
{
    global $conn;

    $searchParam = "%{$search}%";

    $sql = "
        SELECT COUNT(*) as total
        FROM suppliers
        WHERE (
            supplier_name LIKE ?
            OR address LIKE ?
            OR phone1 LIKE ?
            OR email LIKE ?
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

    $row = $result->fetch_assoc();

    $stmt->close();
    return $row['total'];
}


function addSupplier($supplier_name, $email, $address, $phone1, $phone2, $item_id)
{
    global $conn;

    $stmt = $conn->prepare("
        INSERT INTO suppliers (supplier_name, email, address, phone1, phone2, item_id)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("sssssi", $supplier_name, $email, $address, $phone1, $phone2, $item_id);

    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function getSupplierById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM suppliers WHERE supplier_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $suppliers = $result->fetch_assoc();
    $stmt->close();
    return $suppliers;
}

function updateSupplier($id, $supplier_name, $email, $address, $phone1, $phone2)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE suppliers SET supplier_name=?,email=?,address=?, phone1=?, phone2=? WHERE supplier_id=?");
    $stmt->bind_param("sssssi", $supplier_name, $email, $address, $phone1, $phone2, $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function updateSupplierStatus($id, $status)
{
    global $conn;
    $sql = "UPDATE suppliers SET status = ? WHERE supplier_id = ?"; 
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function getAllSupplier() {
  global $conn;
  $stmt = $conn->prepare("SELECT supplier_id, supplier_name FROM suppliers ORDER BY supplier_name ASC");
  $stmt->execute();
  $result = $stmt->get_result();
  $suppliers = $result->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
  return $suppliers;
}