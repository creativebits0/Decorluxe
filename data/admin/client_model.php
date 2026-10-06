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

// function getClientsWithAddresses($limit, $offset)
// {
//     global $conn;

//     $sql = "
//         SELECT 
//             c.client_id,
//             c.fullname,
//             c.email,
//             c.phone1,
//             c.phone2,
//             a.address_id,
//             a.address
//         FROM clients c
//         LEFT JOIN client_addresses a 
//         ON c.client_id = a.client_id
//         ORDER BY c.client_id DESC
//         LIMIT ? OFFSET ?
//     ";

//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("ii", $limit, $offset);
//     $stmt->execute();

//     $result = $stmt->get_result();

//     $clients = [];

//     while ($row = $result->fetch_assoc()) {

//         $id = $row['client_id'];

//         if (!isset($clients[$id])) {
//             $clients[$id] = [
//                 'client_id' => $id,
//                 'fullname' => $row['fullname'],
//                 'email' => $row['email'],
//                 'phone1' => $row['phone1'],
//                 'phone2' => $row['phone2'],
//                 'addresses' => []
//             ];
//         }

//         if ($row['address']) {
//             $clients[$id]['addresses'][] = [
//                 'address_id' => $row['address_id'],
//                 'address' => $row['address']
//             ];
//         }
//     }

//     return array_values($clients);
// }

function getClientsWithAddresses($limit, $offset, $search = '')
{
    global $conn;

    $searchParam = "%{$search}%";

    $sql = "
        SELECT
            c.client_id,
            c.fullname,
            c.email,
            c.phone1,
            c.phone2,
            a.address_id,
            a.address
        FROM clients c
        LEFT JOIN client_addresses a
            ON c.client_id = a.client_id
        WHERE (
            c.fullname LIKE ?
            OR c.email LIKE ?
            OR c.phone1 LIKE ?
            OR c.phone2 LIKE ?
            OR a.address LIKE ?
        )
        ORDER BY c.client_id DESC
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

    $clients = [];

    while ($row = $result->fetch_assoc()) {

        $id = $row['client_id'];

        if (!isset($clients[$id])) {

            $clients[$id] = [
                'client_id' => $id,
                'fullname' => $row['fullname'],
                'email' => $row['email'],
                'phone1' => $row['phone1'],
                'phone2' => $row['phone2'],
                'addresses' => []
            ];
        }

        if (!empty($row['address'])) {

            $clients[$id]['addresses'][] = [
                'address_id' => $row['address_id'],
                'address' => $row['address']
            ];
        }
    }

    $stmt->close();

    return array_values($clients);
}
// function countClients()
// {
//     global $conn;

//     $sql = "SELECT COUNT(*) as total FROM clients";
//     $result = mysqli_query($conn, $sql);
//     $row = mysqli_fetch_assoc($result);

//     return $row['total'];
// }

function countClients($search = '')
{
    global $conn;

    $searchParam = "%{$search}%";

    $sql = "
        SELECT COUNT(DISTINCT c.client_id) AS total
        FROM clients c
        LEFT JOIN client_addresses a
            ON c.client_id = a.client_id
        WHERE (
            c.fullname LIKE ?
            OR c.email LIKE ?
            OR c.phone1 LIKE ?
            OR c.phone2 LIKE ?
            OR a.address LIKE ?
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

function addClient(
    $fullname,
    $email,
    $phone1,
    $phone2
) {

    global $conn;

    $stmt = $conn->prepare("
INSERT INTO clients
(fullname,email,phone1,phone2)
VALUES (?,?,?,?)
");

    $stmt->bind_param(
        "ssss",
        $fullname,
        $email,
        $phone1,
        $phone2
    );

    if ($stmt->execute()) {

        return $conn->insert_id;
    }

    return false;
}



function addClientAddress(
    $client_id,
    $address
) {

    global $conn;

    $stmt = $conn->prepare("
INSERT INTO client_addresses
(client_id,address)
VALUES (?,?)
");

    $stmt->bind_param(
        "is",
        $client_id,
        $address
    );

    return $stmt->execute();
}

function getClientById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM clients WHERE client_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $clients = $result->fetch_assoc();
    $stmt->close();
    return $clients;
}

function updateClient($id, $fullname, $email, $phone1, $phone2)
{
    global $conn;

    $stmt = $conn->prepare("
        UPDATE clients 
        SET 
            fullname=?,
            email=?,
            phone1=?,
            phone2=? 
        WHERE client_id=?
    ");

    $stmt->bind_param(
        "ssssi",
        $fullname,
        $email,
        $phone1,
        $phone2,
        $id
    );

    $result = $stmt->execute();

    $stmt->close();

    return $result;
}

function getAllClients()
{
    global $conn;
    $stmt = $conn->prepare("SELECT client_id, fullname FROM clients ORDER BY fullname ASC");
    $stmt->execute();
    $result = $stmt->get_result();
    $clients = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $clients;
}

function getClientAddresses($client_id)
{
    global $conn;

    $stmt = $conn->prepare("
    SELECT address_id,address 
    FROM client_addresses 
    WHERE client_id=?
    ");

    $stmt->bind_param("i", $client_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getAllClientsWithAddresses()
{
    global $conn;

    $sql = "
    SELECT 
        c.client_id,
        c.fullname,
        c.email,
        c.phone1,
        c.phone2,
        a.address_id,
        a.address
    FROM clients c
    LEFT JOIN client_addresses a 
    ON c.client_id = a.client_id
    ORDER BY c.fullname
    ";

    $result = mysqli_query($conn, $sql);

    $clients = [];

    while ($row = mysqli_fetch_assoc($result)) {

        $client_id = $row['client_id'];

        if (!isset($clients[$client_id])) {
            $clients[$client_id] = [
                'client_id' => $client_id,
                'fullname' => $row['fullname'],
                'email' => $row['email'],
                'phone1' => $row['phone1'],
                'phone2' => $row['phone2'],
                'addresses' => []
            ];
        }

        if ($row['address']) {
            $clients[$client_id]['addresses'][] = [
                'address_id' => $row['address_id'],
                'address' => $row['address']
            ];
        }
    }

    return array_values($clients);
}

function getAddressById($address_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT address_id, address
        FROM client_addresses
        WHERE address_id = ?
        LIMIT 1
    ");

    $stmt->bind_param("i", $address_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}
function deleteRemovedAddresses($client_id, $ids)
{
    global $conn;

    $ids = array_filter($ids);

    if (empty($ids)) {

        $stmt = $conn->prepare("
        DELETE FROM client_addresses 
        WHERE client_id=?
        ");

        $stmt->bind_param("i", $client_id);
        $stmt->execute();

        return;
    }

    $idList = implode(',', $ids);

    $sql = "
    DELETE FROM client_addresses 
    WHERE client_id=? 
    AND address_id NOT IN ($idList)
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
}

function updateClientAddress($address_id, $address)
{
    global $conn;

    $stmt = $conn->prepare("
        UPDATE client_addresses 
        SET address=? 
        WHERE address_id=?
    ");

    $stmt->bind_param("si", $address, $address_id);

    return $stmt->execute();
}
