<?php

session_start();

require_once __DIR__ . '/../../../data/admin/quotation_model.php';
require_once __DIR__ . '/../../../data/admin/project_model.php';
require_once __DIR__ . '/../../../data/admin/notification_model.php';
require_once __DIR__ . '/../../../data/admin/material_request_model.php';

$redirect = "/decorluxe/presentation/admin/quotation.php";

if ($_SESSION['role'] === 'project_incharge') {
    $redirect = "/decorluxe/presentation/project_incharge/quotation.php";
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("Invalid Request");
}

global $conn;

/*
|--------------------------------------------------------------------------
| GET LOGIN USER
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['id'])) {
    exit("Session Expired");
}

$login_user = $_SESSION['id'];

/*
|--------------------------------------------------------------------------
| MAIN FORM VALUES
|--------------------------------------------------------------------------
*/

$quotation_id = $_POST['quotation_id'] ?? 0;
$client_id = $_POST['client_id'] ?? null;
$project_name = trim($_POST['project_name'] ?? '');
$valid_until = $_POST['valid_till'] ?? null;
$total = (float)($_POST['total'] ?? 0);
// $status = $_POST['status'] ?? 'Pending';
$services = $_POST['services'] ?? [];
$old_status = $_POST['old_status'] ?? '';
$new_status = $_POST['status'] ?? 'Pending';
/*
|--------------------------------------------------------------------------
| VALIDATIONS
|--------------------------------------------------------------------------
*/

if (!$quotation_id) {
    exit("❌ Invalid quotation");
}

if (!$client_id) {
    exit("❌ Client required");
}

/*
|--------------------------------------------------------------------------
| CHECK QUOTATION OWNER
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT created_by
    FROM quotations
    WHERE quotation_id = ?
");

$stmt->bind_param("i", $quotation_id);

$stmt->execute();

$result = $stmt->get_result();

$quotation = $result->fetch_assoc();

$stmt->close();

if (!$quotation) {
    exit("❌ Quotation not found");
}

/*
|--------------------------------------------------------------------------
| ONLY CREATOR CAN EDIT
|--------------------------------------------------------------------------
*/

if ($quotation['created_by'] !== $login_user) {

    exit("❌ Only quotation creator can edit this quotation");
}

/*
|--------------------------------------------------------------------------
| BUILD AREA DATA
|--------------------------------------------------------------------------
*/

$areas = [];

if (isset($_POST['item_id']) && is_array($_POST['item_id'])) {

    foreach ($_POST['item_id'] as $aIndex => $itemList) {

        $area_name =
            trim($_POST['area_name'][$aIndex] ?? 'Unnamed Area');

        $description =
            trim($_POST['area_description'][$aIndex] ?? '');

        $labour =
            (float)($_POST['labour_charge'][$aIndex] ?? 0);

        $transport =
            (float)($_POST['transport'][$aIndex] ?? 0);

        $other =
            (float)($_POST['other_charges'][$aIndex] ?? 0);

        $area_total =
            (float)($_POST['area_total'][$aIndex] ?? 0);

        $material_total = 0;

        $items = [];

        foreach ($itemList as $iIndex => $item_id) {

            if (empty($item_id)) {
                continue;
            }

            $qty =
                (float)($_POST['qty'][$aIndex][$iIndex] ?? 0);

            $price =
                (float)($_POST['unit_price'][$aIndex][$iIndex] ?? 0);

            $sub_total = $qty * $price;

            $material_total += $sub_total;

            $items[] = [

                'item_id' => $item_id,

                'qty' => $qty,

                'unit_price' => $price,

                'sub_total' => $sub_total
            ];
        }

        if (!empty($items)) {

            $areas[] = [

                'area_name' => $area_name,

                'description' => $description,

                'material_total' => $material_total,

                'labour_charge' => $labour,

                'transport_charge' => $transport,

                'other_charge' => $other,

                'area_total' => $area_total,

                'items' => $items
            ];
        }
    }
}

/*
|--------------------------------------------------------------------------
| VALIDATE ITEMS
|--------------------------------------------------------------------------
*/

if (empty($areas)) {
    // exit("❌ At least one area with items required");
    $_SESSION['alert_message'] = "❌ At least one area with items required";
    header("Location: $redirect");
    exit;
}

/*
|--------------------------------------------------------------------------
| START TRANSACTION
|--------------------------------------------------------------------------
*/

$conn->begin_transaction();

try {

    /*
    |--------------------------------------------------------------------------
    | UPDATE QUOTATION
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        UPDATE quotations
        SET
            client_id = ?,
            project_name = ?,
            valid_until = ?,
            total_amount = ?,
            status = ?,
            created_by = ?
        WHERE quotation_id = ?
    ");

    $stmt->bind_param(
        "issdssi",
        $client_id,
        $project_name,
        $valid_until,
        $total,
        $new_status,
        $login_user,
        $quotation_id
    );

    $stmt->execute();

    $stmt->close();

    /*
    |--------------------------------------------------------------------------
    | DELETE OLD AREA ITEMS FIRST
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        DELETE qi
        FROM quotation_items qi
        INNER JOIN quotation_areas qa
        ON qi.area_id = qa.area_id
        WHERE qa.quotation_id = ?
    ");

    $stmt->bind_param("i", $quotation_id);

    $stmt->execute();

    $stmt->close();

    /*
    |--------------------------------------------------------------------------
    | DELETE OLD AREAS
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        DELETE FROM quotation_areas
        WHERE quotation_id = ?
    ");

    $stmt->bind_param("i", $quotation_id);

    $stmt->execute();

    $stmt->close();

    /*
    |--------------------------------------------------------------------------
    | INSERT NEW AREAS
    |--------------------------------------------------------------------------
    */

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

        /*
        |--------------------------------------------------------------------------
        | INSERT ITEMS
        |--------------------------------------------------------------------------
        */

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

            $itemStmt->close();

            $item_no++;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | IMAGE UPLOADS
    |--------------------------------------------------------------------------
    */

    if (!empty($_FILES['images']['name'][0])) {

        foreach ($_FILES['images']['tmp_name'] as $key => $tmp) {

            if (empty($tmp)) {
                continue;
            }

            $name =
                time() . '_' .
                basename($_FILES['images']['name'][$key]);

            move_uploaded_file(
                $tmp,
                "../../../uploads/quotation/" . $name
            );

            $stmt = $conn->prepare("
                INSERT INTO quotation_images
                (
                    quotation_id,
                    image
                )
                VALUES (?, ?)
            ");

            $stmt->bind_param(
                "is",
                $quotation_id,
                $name
            );

            $stmt->execute();

            $stmt->close();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PROJECT CREATION
    |--------------------------------------------------------------------------
    */

    if ($new_status === 'Approved') {

        if (!projectExistsFromQuotation($quotation_id)) {

            $project = createProjectFromQuotation($quotation_id);

            addNotification(
                "New project {$project['project_no']} has been created.",
                "project_created",
                $project['project_id']
            );
        }
        if (!notificationExistsForQuotation($quotation_id)) {

            createRoomManagerNotification($quotation_id);
        }
        if (!materialRequestExists($quotation_id)) {

            createMaterialRequestsFromQuotation($quotation_id);

            addNotification(
                "Materials are waiting to be allocated for quotation #{$quotation_id}",
                "material_allocation",
                $quotation_id
            );
        }
    }
    /*
|--------------------------------------------------------------------------
| UPDATE SERVICES (SYNC)
|--------------------------------------------------------------------------
*/

    // DELETE OLD SERVICES
    $stmt = $conn->prepare("
    DELETE FROM quotation_services
    WHERE quotation_id = ?
");

    $stmt->bind_param("i", $quotation_id);
    $stmt->execute();
    $stmt->close();


    // 2. INSERT NEW SERVICES
    if (!empty($services)) {

        $stmt = $conn->prepare("
        INSERT INTO quotation_services (quotation_id, service_id)
        VALUES (?, ?)
    ");

        foreach ($services as $service_id) {

            $stmt->bind_param("ii", $quotation_id, $service_id);
            $stmt->execute();
        }

        $stmt->close();
    }

    /*
|--------------------------------------------------------------------------
| STATUS CHANGE NOTIFICATIONS
|--------------------------------------------------------------------------
*/

    if ($old_status !== $new_status) {

        if ($new_status === 'Approved') {

            addNotification(
                "Quotation #{$quotation_id} has been approved.",
                "quotation_approved",
                $quotation_id
            );
        } elseif ($new_status === 'Rejected') {

            addNotification(
                "Quotation #{$quotation_id} has been rejected.",
                "quotation_rejected",
                $quotation_id
            );
        } elseif ($new_status === 'Pending') {

            addNotification(
                "Quotation #{$quotation_id} has been changed back to pending.",
                "quotation_pending",
                $quotation_id
            );
        }
    }

    // delete image 
    if (!empty($_POST['deleted_images'])) {

        foreach ($_POST['deleted_images'] as $imageId) {

            // Get filename
            $stmt = $conn->prepare("
            SELECT image
            FROM quotation_images
            WHERE id=?
        ");

            $stmt->bind_param("i", $imageId);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {

                $path = "../../../uploads/quotation/" . $row['image'];

                if (file_exists($path)) {
                    unlink($path);
                }
            }

            // Delete DB record
            $stmt = $conn->prepare("
            DELETE FROM quotation_images
            WHERE id=?
        ");

            $stmt->bind_param("i", $imageId);
            $stmt->execute();
        }
    }
    /*
    |--------------------------------------------------------------------------
    | COMMIT
    |--------------------------------------------------------------------------
    */

    $conn->commit();

    $_SESSION['success_message'] = "✅ Quotation Updated Successfully";
    header("Location: $redirect");
    exit;
    // echo "✅ Quotation Updated Successfully";
} catch (Exception $e) {

    $conn->rollback();

    error_log($e->getMessage());

    $_SESSION['alert_message'] = "❌ Failed To Update Quotation";
    header("Location: $redirect");
    exit;
    // echo "❌ Failed To Update Quotation";
}
