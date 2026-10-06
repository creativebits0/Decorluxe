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
require_once __DIR__ . '/inventory_model.php';
require_once __DIR__ . '/notification_model.php';

function createProjectFromQuotation($quotation_id)
{
    global $conn;

    // Get quotation
    $stmt = $conn->prepare(
        "SELECT * FROM quotations WHERE quotation_id=?"
    );

    $stmt->bind_param("i", $quotation_id);
    $stmt->execute();

    $quotation = $stmt->get_result()->fetch_assoc();

    // Generate Project No
    $year = date("Y");

    $quote_last4 = substr(
        str_pad($quotation['quotation_no'], 4, "0", STR_PAD_LEFT),
        -4
    );

    $project_no = "ST" . $year . "-I" . $quote_last4;


    // Insert Project
    $insert = $conn->prepare(
        "INSERT INTO projects
        (project_no, quotation_id, appointment_id, client_id, project_name, total_amount, status)
        VALUES(?,?,?,?,?,?, 'Pending')"
    );

    $insert->bind_param(
        "siiisd",
        $project_no,
        $quotation['quotation_id'],
        $quotation['appointment_id'],
        $quotation['client_id'],
        $quotation['project_name'],
        $quotation['total_amount']
    );

    $insert->execute();
    $project_id = $insert->insert_id;

    copyQuotationAreasToProject(
        $quotation_id,
        $project_id
    );
    copyQuotationServicesToProject(
        $quotation_id,
        $project_id
    );

    return [
    'project_id' => $project_id,
    'project_no' => $project_no
];
}


function copyQuotationAreasToProject(
    $quotation_id,
    $project_id
) {
    global $conn;

    $stmt = $conn->prepare("
        SELECT *
        FROM quotation_areas
        WHERE quotation_id = ?
    ");

    $stmt->bind_param("i", $quotation_id);
    $stmt->execute();

    $areas = $stmt->get_result();

    while ($area = $areas->fetch_assoc()) {

        $insertArea = $conn->prepare("
            INSERT INTO project_areas
            (
                project_id,
                area_name,
                material_cost,
                labour_charge,
                transport_charge,
                other_charge,
                subtotal,
                description
            )
            VALUES
            (
                ?, ?, ?, ?, ?, ?, ?, ?
            )
        ");

        $insertArea->bind_param(
            "isddddds",
            $project_id,
            $area['area_name'],
            $area['material_total'],
            $area['labour_charge'],
            $area['transport_charge'],
            $area['other_charge'],
            $area['area_total'],
            $area['description']
        );

        $insertArea->execute();

        $project_area_id = $conn->insert_id;

        copyAreaItems(
            $area['area_id'],
            $project_area_id,
            $project_id,
            $area['area_name']
        );
    }
}

function copyAreaItems(
    $quotation_area_id,
    $project_area_id,
    $project_id,
    $area_name
) {
    global $conn;

    $stmt = $conn->prepare("
        SELECT
            qi.*,
            i.itemName
        FROM quotation_items qi
        LEFT JOIN items i
            ON qi.item_id = i.item_id
        WHERE qi.area_id = ?
    ");

    $stmt->bind_param(
        "i",
        $quotation_area_id
    );

    $stmt->execute();

    $items = $stmt->get_result();

    while ($row = $items->fetch_assoc()) {

        $insert = $conn->prepare("
            INSERT INTO project_items
            (
                project_id,
                area_id,
                area_name,
                item_id,
                item_name,
                quantity,
                price,
                amount
            )
            VALUES
            (
                ?, ?, ?, ?, ?, ?, ?, ?
            )
        ");

        $insert->bind_param(
            "iisissdd",
            $project_id,
            $project_area_id,
            $area_name,
            $row['item_id'],
            $row['itemName'],
            $row['quantity'],
            $row['unit_price'],
            $row['total']
        );

        $insert->execute();
    }
}

function copyQuotationServicesToProject($quotation_id, $project_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT service_id 
        FROM quotation_services 
        WHERE quotation_id = ?
    ");

    $stmt->bind_param("i", $quotation_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) return;

    $insert = $conn->prepare("
        INSERT INTO project_services (project_id, service_id)
        VALUES (?, ?)
    ");

    while ($row = $result->fetch_assoc()) {

        $service_id = $row['service_id'];

        $insert->bind_param("ii", $project_id, $service_id);
        $insert->execute();
    }

    $insert->close();
    $stmt->close();
}

// function copyQuotationItemsToProject($quotation_id, $project_id)
// {
//     global $conn;

//     $stmt = $conn->prepare("
//         SELECT qi.*, i.itemName
//         FROM quotation_items qi
//         LEFT JOIN items i
//         ON qi.item_id = i.item_id
//         WHERE qi.quotation_id=?
//     ");

//     $stmt->bind_param("i", $quotation_id);
//     $stmt->execute();

//     $items = $stmt->get_result();

//     while ($row = $items->fetch_assoc()) {

//         $insert = $conn->prepare("
//             INSERT INTO project_items
//             (
//                 project_id,
//                 area_name,
//                 item_id,
//                 item_name,
//                 description,
//                 quantity,
//                 price,
//                 amount
//             )
//             VALUES(?,?,?,?,?,?,?,?)
//         ");

//         $insert->bind_param(
//             "isissidd",
//             $project_id,
//             $row['area_name'],
//             $row['item_id'],
//             $row['item_name'],
//             $row['description'],
//             $row['quantity'],
//             $row['unit_price'],
//             $row['total']
//         );

//         $insert->execute();
//     }
// }
function projectExistsFromQuotation($quotation_id)
{
    global $conn;

    $stmt = $conn->prepare(
        "SELECT project_id FROM projects 
        WHERE quotation_id=?"
    );

    $stmt->bind_param("i", $quotation_id);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result->num_rows > 0;
}

function addProjectItem(
    $project_id,
    $item,
    $description,
    $qty,
    $price,
    $amount
) {

    global $conn;

    $stmt = $conn->prepare(
        "INSERT INTO project_items
(project_id,item_name,quantity,price,amount)
VALUES(?,?,?,?,?)"
    );

    $stmt->bind_param(
        "isidd",
        $project_id,
        $item,
        $qty,
        $price,
        $amount
    );

    return $stmt->execute();
}
function updateProjectServices($project_id, $services)
{
    global $conn;

    $conn->query("DELETE FROM project_services WHERE project_id = $project_id");

    $stmt = $conn->prepare("
        INSERT INTO project_services (project_id, service_id)
        VALUES (?, ?)
    ");

    foreach ($services as $service_id) {

        $stmt->bind_param("ii", $project_id, $service_id);
        $stmt->execute();
    }

    $stmt->close();
}
function updateProjectCompletedDate($project_id)
{

    global $conn;

    $stmt = $conn->prepare(
        "UPDATE projects
SET actual_end_date = NOW()
WHERE project_id=?"
    );

    $stmt->bind_param("i", $project_id);

    $stmt->execute();
}


// function getAllProjects($limit, $offset)
// {
//     global $conn;

//     $sql = "
//         SELECT p.*, c.fullname
//         FROM projects p
//         LEFT JOIN clients c ON p.client_id = c.client_id
//         ORDER BY p.project_id DESC
//         LIMIT ? OFFSET ?
//     ";

//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("ii", $limit, $offset);
//     $stmt->execute();

//     $result = $stmt->get_result();

//     return $result->fetch_all(MYSQLI_ASSOC);
// }

// function countProjects()
// {
//     global $conn;

//     $sql = "SELECT COUNT(*) as total FROM projects";
//     $result = mysqli_query($conn, $sql);
//     $row = mysqli_fetch_assoc($result);

//     return $row['total'];
// }

function getAllProjects($limit, $offset, $search = '')
{
    global $conn;

    $searchParam = "%{$search}%";

    $sql = "
        SELECT
            p.*,
            c.fullname
        FROM projects p
        LEFT JOIN clients c
            ON p.client_id = c.client_id
        WHERE (
            p.project_no LIKE ?
            OR c.fullname LIKE ?
            OR p.status LIKE ?
            OR p.start_date LIKE ?
        )
        ORDER BY p.project_id DESC
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

    return $result->fetch_all(MYSQLI_ASSOC);
}

function countProjects($search = '')
{
    global $conn;

    $searchParam = "%{$search}%";

    $sql = "
        SELECT COUNT(*) AS total
        FROM projects p
        LEFT JOIN clients c
            ON p.client_id = c.client_id
        WHERE (
            p.project_no LIKE ?
            OR c.fullname LIKE ?
            OR p.status LIKE ?
            OR p.start_date LIKE ?
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
function getProjectById($id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT 
            p.*,

            c.fullname,
            c.phone1,

            ca.address

        FROM projects p

        LEFT JOIN clients c
            ON p.client_id = c.client_id

        LEFT JOIN quotations q
            ON p.quotation_id = q.quotation_id

        LEFT JOIN appointments a
            ON q.appointment_id = a.appointment_id

        LEFT JOIN client_addresses ca
            ON a.address_id = ca.address_id

        WHERE p.project_id = ?
    ");

    $stmt->bind_param("i", $id);

    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}


function getProjectItems($project_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT 
            pi.*,
            i.itemName AS item_name,
            i.itemCode,
            i.sellPrice

        FROM project_items pi

        LEFT JOIN items i
            ON pi.item_id = i.item_id

        WHERE pi.project_id = ?

        ORDER BY pi.project_item_id ASC
    ");

    $stmt->bind_param("i", $project_id);

    $stmt->execute();

    return $stmt->get_result();
}



function updateProject(
    $id,
    $start,
    $end,
    $status,
    $total,
    $notes
) {
    global $conn;

    /* Get old status */
    $oldProject = getProjectById($id);
    $oldStatus = $oldProject['status'];

    $stmt = $conn->prepare(
        "UPDATE projects
        SET start_date=?,
        expected_end_date=?,
        status=?,
        total_amount=?,
        notes=?
        WHERE project_id=?"
    );

    $stmt->bind_param(
        "sssdsi",
        $start,
        $end,
        $status,
        $total,
        $notes,
        $id
    );

    $result = $stmt->execute();


    /* If status changed to Completed */
    if ($result && $status == 'Completed' && $oldStatus != 'Completed') {

        useProjectStock($id);              // Reduce stock
        updateProjectCompletedDate($id);   // Save completed date
        $project = getProjectById($id);

        addNotification(
            "Project {$project['project_no']} has been completed.",
            "project_completed",
            $id
        );
    }

    return $result;
}

function getProjectStatus($project_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT status
        FROM projects
        WHERE project_id = ?
    ");

    $stmt->bind_param("i", $project_id);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();

    $stmt->close();

    return $result['status'] ?? null;
}

function updateAppointmentProjectStartCompleted($project_id)
{
    global $conn;

    $stmt = $conn->prepare("
        UPDATE appointments a
        INNER JOIN projects p
            ON p.appointment_id = a.parent_appointment_id
        SET a.status = 'Completed'
        WHERE p.project_id = ?
    ");

    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $stmt->close();
}
function updateProjectItem(
    $id,
    $item,
    $description,
    $qty,
    $price,
    $amount,
    $area_name
) {

    global $conn;

    $stmt = $conn->prepare(
        "UPDATE project_items
        SET
        item_name=?,
        description=?,
        quantity=?,
        price=?,
        amount=?,
        area_name=?
        WHERE project_item_id=?"
    );

    $stmt->bind_param(
        "ssiddsi",
        $item,
        $description,
        $qty,
        $price,
        $amount,
        $area_name,
        $id
    );

    return $stmt->execute();
}

function saveProjectImage($project_id, $image)
{
    global $conn;

    $stmt = $conn->prepare(
        "INSERT INTO project_images
        (project_id,image_path)
        VALUES(?,?)"
    );

    $stmt->bind_param("is", $project_id, $image);

    return $stmt->execute();
}

function deleteProjectItem($id)
{
    global $conn;

    $stmt = $conn->prepare(
        "DELETE FROM project_items
        WHERE project_item_id=?"
    );

    $stmt->bind_param("i", $id);

    return $stmt->execute();
}

function deleteProjectArea($project_id, $area_name)
{
    global $conn;

    $stmt = $conn->prepare("
        DELETE FROM project_areas 
        WHERE project_id=? AND area_name=?
    ");

    $stmt->bind_param("is", $project_id, $area_name);

    return $stmt->execute();
}
function getAllProject()
{
    global $conn;

    $sql = "SELECT 
                p.*,
                IFNULL(h.show_on_home,0) as show_on_home,
                h.homepage_description,
                pi.image_path
            FROM projects p
            LEFT JOIN homepage_projects h
            ON p.project_id = h.project_id
            LEFT JOIN project_images pi
            ON p.project_id = pi.project_id
            GROUP BY p.project_id
            ORDER BY p.project_id DESC";

    $stmt = $conn->prepare($sql);

    $stmt->execute();

    $result = $stmt->get_result();

    $projects = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $projects;
}

function useProjectStock($project_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT item_id, quantity 
        FROM project_items
        WHERE project_id=?
    ");

    $stmt->bind_param("i", $project_id);
    $stmt->execute();

    $items = $stmt->get_result();

    while ($row = $items->fetch_assoc()) {

        reduceStock(
            $row['item_id'],
            $row['quantity'],
            'project',
            $project_id
        );
    }
}

function saveProjectArea(
    $project_id,
    $area_name,
    $material_cost,
    $labour_charge,
    $transport_charge,
    $other_charge,
    $subtotal,
    $description
) {

    global $conn;

    $stmt = $conn->prepare("
        INSERT INTO project_areas
        (
            project_id,
            area_name,
            material_cost,
            labour_charge,
            transport_charge,
            other_charge,
            subtotal,
            description
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "isddddds",
        $project_id,
        $area_name,
        $material_cost,
        $labour_charge,
        $transport_charge,
        $other_charge,
        $subtotal,
        $description
    );

    return $stmt->execute();
}

function saveOrUpdateProjectArea(
    $project_id,
    $area_name,
    $material_cost,
    $labour_charge,
    $transport_charge,
    $other_charge,
    $subtotal,
    $description
) {

    global $conn;

    $check = $conn->prepare("
        SELECT area_id
        FROM project_areas
        WHERE project_id = ?
        AND area_name = ?
    ");

    $check->bind_param(
        "is",
        $project_id,
        $area_name
    );

    $check->execute();

    $result = $check->get_result();

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        $stmt = $conn->prepare("
            UPDATE project_areas
            SET material_cost = ?,
                labour_charge = ?,
                transport_charge = ?,
                other_charge = ?,
                subtotal = ?,
                description = ?
            WHERE area_id = ?
        ");

        $stmt->bind_param(
            "ddddssi",
            $material_cost,
            $labour_charge,
            $transport_charge,
            $other_charge,
            $subtotal,
            $description,
            $row['area_id']
        );
    } else {

        $stmt = $conn->prepare("
            INSERT INTO project_areas
            (
                project_id,
                area_name,
                material_cost,
                labour_charge,
                transport_charge,
                other_charge,
                subtotal,
                description
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "isddddds",
            $project_id,
            $area_name,
            $material_cost,
            $labour_charge,
            $transport_charge,
            $other_charge,
            $subtotal,
            $description
        );
    }

    return $stmt->execute();
}

function getProjectAreas($project_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT *
        FROM project_areas
        WHERE project_id=?
        ORDER BY area_id ASC
    ");

    $stmt->bind_param("i", $project_id);

    $stmt->execute();

    return $stmt->get_result();
}

function getProjectImages($project_id)
{
    global $conn;

    $stmt = $conn->prepare(
        "SELECT * FROM project_images WHERE project_id=?"
    );

    $stmt->bind_param("i", $project_id);

    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
function getProjectServices($project_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT 
            ps.service_id,
            s.service_name
        FROM project_services ps
        LEFT JOIN services s 
            ON s.service_id = ps.service_id
        WHERE ps.project_id = ?
    ");

    $stmt->bind_param("i", $project_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $services = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $services;
}

// function addNotification($message, $type = null, $reference_id = null)
// {
//     global $conn;

//     $stmt = $conn->prepare("
//         INSERT INTO notifications
//         (message, type, reference_id)
//         VALUES (?, ?, ?)
//     ");

//     $stmt->bind_param(
//         "ssi",
//         $message,
//         $type,
//         $reference_id
//     );

//     return $stmt->execute();
// }