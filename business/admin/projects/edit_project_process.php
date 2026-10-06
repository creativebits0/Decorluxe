<?php
session_start();
require_once __DIR__ . '/../../../data/admin/project_model.php';


$redirect = "/decorluxe/presentation/admin/projects.php";

if ($_SESSION['role'] === 'project_incharge') {
    $redirect = "/decorluxe/presentation/project_incharge/projects.php";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $project_id = $_POST['project_id'];
    $oldStatus = getProjectStatus($project_id);
    $status = $_POST['status'] ?? 'Pending';
    $start = $_POST['start_date'] ?? null;
    $end = $_POST['expected_end_date'] ?? null;
    $notes = $_POST['notes'] ?? '';
    $total = $_POST['total'] ?? 0;
    $services = $_POST['services'] ?? [];
    $deleted_items = $_POST['deleted_items'] ?? [];
    $deleted_areas = $_POST['deleted_areas'] ?? [];
    /* Update Project */

    $conn->begin_transaction();

    try {
        updateProject(
            $project_id,
            $start,
            $end,
            $status,
            $total,
            $notes
        );

        if (!empty($services)) {
            updateProjectServices($project_id, $services);
        } else {
            // if no services selected clear old ones
            global $conn;
            $conn->query("DELETE FROM project_services WHERE project_id = $project_id");
        }
        /*Update Project Items */
        $item_ids = $_POST['item_id'] ?? [];
        $item_names = $_POST['item_name'] ?? [];
        $descriptions = $_POST['description'] ?? [];
        $qtys = $_POST['qty'] ?? [];
        $prices = $_POST['price'] ?? [];
        $material_costs = $_POST['material_cost'] ?? [];
        $labour_Charges = $_POST['labour_charge'] ?? [];
        $transport_Charges = $_POST['transport_charge'] ?? [];
        $other_Charges = $_POST['other_charge'] ?? [];
        $area_totals = $_POST['area_total'] ?? [];
        $area_names = $_POST['area_name_group'] ?? [];

        foreach ($item_ids as $index => $item_id) {

            $item = $item_names[$index];
            $description = $descriptions[$index] ?? '';
            $qty = $qtys[$index];
            $price = $prices[$index];
            $amount = $qty * $price;
            $area_name = $_POST['area_name'][$index] ?? '';

            updateProjectItem(
                $item_id,
                $item,
                $description,
                $qty,
                $price,
                $amount,
                $area_name
            );
        }

        foreach ($area_names as $index => $area) {

            $material_cost = $material_costs[$index] ?? 0;
            $labour_Charge = $labour_Charges[$index] ?? 0;
            $transport_Charge = $transport_Charges[$index] ?? 0;
            $other_Charge = $other_Charges[$index] ?? 0;

            $areatotal = $area_totals[$index] ?? 0;

            $description = $descriptions[$index] ?? '';

            saveOrUpdateProjectArea(
                $project_id,
                $area,
                $material_cost,
                $labour_Charge,
                $transport_Charge,
                $other_Charge,
                $areatotal,
                $description
            );
        }
        /*Add New Items */
        $new_items = $_POST['new_item_name'] ?? [];
        $new_item_ids = $_POST['new_item_id'] ?? [];
        $new_area_names = $_POST['new_area_name'] ?? [];
        $new_desc = $_POST['new_description'] ?? [];
        $new_qty = $_POST['new_qty'] ?? [];
        $new_price = $_POST['new_price'] ?? [];

        foreach ($new_items as $i => $item) {

            if (empty($item)) continue;

            $description = $new_desc[$i] ?? '';
            $qty = $new_qty[$i] ?? 0;
            $price = $new_price[$i] ?? 0;

            $amount = $qty * $price;

            $stmt = $conn->prepare("
        INSERT INTO project_items
        (
            project_id,
            area_name,
            item_id,
            item_name,
            description,
            quantity,
            price,
            amount
        )
        VALUES(?,?,?,?,?,?,?,?)
    ");

            $stmt->bind_param(
                "isissidd",
                $project_id,
                $new_area_names[$i],
                $new_item_ids[$i],
                $item,
                $description,
                $qty,
                $price,
                $amount
            );

            $stmt->execute();
        }


        /*Upload Images */

        if (!empty($_FILES['images']['name'][0])) {

            foreach ($_FILES['images']['tmp_name'] as $key => $tmp) {

                $name = time() . '_' . $_FILES['images']['name'][$key];

                move_uploaded_file(
                    $tmp,
                    '../../../uploads/projects/' . $name
                );

                saveProjectImage(
                    $project_id,
                    $name
                );
            }
        }
        // $deleted = $_POST['deleted_items'] ?? [];


        /*DELETE REMOVED ITEMS */

        $deleted_items = $_POST['deleted_items'] ?? [];

        if (is_array($deleted_items)) {

            foreach ($deleted_items as $id) {

                if (!empty($id)) {

                    deleteProjectItem($id);
                }
            }
        }



        /*DELETE REMOVED AREAS */

        $deleted_areas = $_POST['deleted_areas'] ?? [];

        if (is_array($deleted_areas)) {

            foreach ($deleted_areas as $area_id) {

                if (!empty($area_id)) {

                    deleteProjectArea(
                        $project_id,
                        $area_id
                    );
                }
            }
        }

        if (!empty($_POST['deleted_images'])) {

            foreach ($_POST['deleted_images'] as $imageId) {

                // Get filename
                $stmt = $conn->prepare("
            SELECT image_path
            FROM project_images
            WHERE image_id=?
        ");

                $stmt->bind_param("i", $imageId);
                $stmt->execute();

                $result = $stmt->get_result();

                if ($row = $result->fetch_assoc()) {

                    $path = "../../../uploads/projects/" . $row['image_path'];

                    if (file_exists($path)) {
                        unlink($path);
                    }
                }

                // Delete DB record
                $stmt = $conn->prepare("
            DELETE FROM project_images
            WHERE image_id=?
        ");

                $stmt->bind_param("i", $imageId);
                $stmt->execute();
            }
        }
        // Change appointment project start status to completed
        if ($oldStatus !== 'Started' && $status === 'Started') {

            updateAppointmentProjectStartCompleted($project_id);
        }
        /*Completion Date */

        if ($status == 'Completed') {

            updateProjectCompletedDate($project_id);
        }

        $conn->commit();
    } catch (Exception $e) {

        $conn->rollback();
        throw $e;
    }

    $_SESSION['success_message'] =
        "✅ Project Updated Successfully!";
    header("Location: $redirect");
    exit();
    // echo "✅ Project Updated Successfully";
}
