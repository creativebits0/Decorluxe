<?php
require_once '../../../data/admin/project_model.php';
require_once '../../../data/admin/client_model.php';
require_once '../../../data/admin/service_model.php';


$id = $_GET['id'];

$project = getProjectById($id);
$items = getProjectItems($id);
$areas = getProjectAreas($id);


$projectServices = getProjectServices($id);
$selectedServices = array_column($projectServices, 'service_id');
$allServices = getServicesForSelect();


/* GROUP ITEMS */
$groupedItems = [];

while ($item = $items->fetch_assoc()) {

    $area = $item['area_name'] ?? 'General';

    $groupedItems[$area][] = $item;
}

/* AREA DATA */
$areaData = [];

while ($area = $areas->fetch_assoc()) {

    $areaData[$area['area_name']] = [
        'subtotal' => $area['subtotal'],
        'description' => $area['description']
    ];
}
?>

<style>
    .project-view {
        background: #f5f5f5;
        padding: 25px;
        border-radius: 15px;
    }

    .top-card {
        background: linear-gradient(135deg, #1f1f1f, #aa8c2c);
        color: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .info-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .area-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .area-header {
        background: #aa8c2c;
        color: white;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table th {
        background: #1f1f1f;
        color: white;
        vertical-align: middle;
    }

    .table td {
        vertical-align: middle;
    }

    .subtotal-box {
        background: #f8f9fa;
        border-left: 5px solid #aa8c2c;
        padding: 15px;
        border-radius: 10px;
    }

    .label-title {
        font-size: 13px;
        color: #888;
        margin-bottom: 3px;
    }

    .value-text {
        font-weight: 600;
        font-size: 15px;
    }

    .grand-total {
        background: #1f1f1f;
        color: white;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
    }

    .project-images img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        border-radius: 12px;
        transition: 0.3s;
    }

    .project-images img:hover {
        transform: scale(1.03);
    }

    .badge-status {
        padding: 10px 18px;
        border-radius: 30px;
        font-size: 14px;
        font-weight: bold;
    }
</style>

<div class="project-view">

    <!-- PROJECT HEADER -->

    <div class="top-card">

        <div class="d-flex justify-content-between align-items-start flex-wrap">

            <div>

                <h2 class="fw-bold mb-2">
                    <?= $project['project_no'] ?>
                </h2>

                <h5>
                    <?= htmlspecialchars($project['project_name']) ?>
                </h5>

                <p class="mb-0 mt-3">
                    <?= nl2br(htmlspecialchars($project['notes'])) ?>
                </p>

            </div>

            <div>

                <?php
                $statusClass = 'bg-secondary';

                if ($project['status'] == 'Pending') {
                    $statusClass = 'bg-warning text-dark';
                } elseif ($project['status'] == 'Started') {
                    $statusClass = 'bg-primary';
                } elseif ($project['status'] == 'In Progress') {
                    $statusClass = 'bg-info text-dark';
                } elseif ($project['status'] == 'Completed') {
                    $statusClass = 'bg-success';
                }
                ?>

                <span class="badge-status <?= $statusClass ?>">
                    <?= $project['status'] ?>
                </span>

            </div>

        </div>

    </div>


    <!-- CLIENT & PROJECT DETAILS -->

    <div class="row">

        <div class="col-md-6">

            <div class="info-card h-100">

                <h5 class="fw-bold mb-4 text-dark">
                    Client Details
                </h5>

                <div class="row mb-3">
                    <div class="col-5 label-title">Client Name</div>
                    <div class="col-7 value-text">
                        <?= htmlspecialchars($project['fullname']) ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-5 label-title">Project No</div>
                    <div class="col-7 value-text">
                        <?= $project['project_no'] ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-5 label-title">Status</div>
                    <div class="col-7 value-text">
                        <?= $project['status'] ?>
                    </div>
                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="info-card h-100">

                <h5 class="fw-bold mb-4 text-dark">
                    Timeline Details
                </h5>

                <div class="row mb-3">
                    <div class="col-5 label-title">Start Date</div>
                    <div class="col-7 value-text">
                        <?= !empty($project['start_date']) ? date('d M Y h:i A', strtotime($project['start_date'])) : 'N/A' ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-5 label-title">Expected End</div>
                    <div class="col-7 value-text">
                        <?= !empty($project['expected_end_date']) ? date('d M Y h:i A', strtotime($project['expected_end_date'])) : 'N/A' ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-5 label-title">Completed Date</div>
                    <div class="col-7 value-text">
                        <?= !empty($project['actual_end_date']) ? date('d M Y h:i A', strtotime($project['actual_end_date'])) : 'N/A' ?>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <div class="mb-3 mt-3 p-3 bg-dark text-light rounded">
        <h5 class="form-label">Requested Services</h5>

        <?php
        $serviceNames = [];

        foreach ($allServices as $service) {

            if (in_array($service['service_id'], $selectedServices)) {
                $serviceNames[] = $service['service_name'];
            }
        }

        echo htmlspecialchars(implode(', ', $serviceNames));
        ?>
    </div>
    <!-- AREA WISE ITEMS -->

    <?php foreach ($groupedItems as $area => $areaItems): ?>

        <div class="area-card">

            <div class="area-header">

                <div>
                    <h5 class="mb-0 fw-bold">
                        <?= htmlspecialchars($area) ?>
                    </h5>
                </div>

                <div>
                    <strong>
                        Subtotal : Rs.
                        <?= number_format($areaData[$area]['subtotal'] ?? 0, 2) ?>
                    </strong>
                </div>

            </div>


            <div class="p-3">

                <div class="table-responsive">

                    <table class="table table-bordered align-middle">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item Name</th>
                                <th width="120">Qty</th>
                                <th width="150">Unit Price</th>
                                <th width="150">Amount</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($areaItems as $index => $item): ?>

                                <tr>

                                    <td>
                                        <?= $index + 1 ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($item['item_name']) ?>
                                    </td>

                                    <td>
                                        <?= $item['quantity'] ?>
                                    </td>

                                    <td>
                                        Rs. <?= number_format($item['price'], 2) ?>
                                    </td>

                                    <td>
                                        <strong>
                                            Rs. <?= number_format($item['amount'], 2) ?>
                                        </strong>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>


                <!-- AREA DESCRIPTION -->

                <div class="subtotal-box mt-3">

                    <h6 class="fw-bold mb-2">
                        Area Description
                    </h6>

                    <p class="mb-0 text-muted">
                        <?= htmlspecialchars(preg_replace('/\r\n|\r|\n/', ' ', ($areaData[$area]['description'] ?? 'No Description'))) ?>
                    </p>

                </div>

            </div>

        </div>

    <?php endforeach; ?>


    <!-- GRAND TOTAL -->

    <div class="grand-total mb-4">

        <h3 class="fw-bold mb-0">
            Grand Total : Rs.
            <?= number_format($project['total_amount'], 2) ?>
        </h3>

    </div>


    <!-- PROJECT IMAGES -->

    <?php
    $images = getProjectImages($id);
    ?>

    <?php if (!empty($images)): ?>

        <div class="info-card">

            <h4 class="fw-bold mb-4">
                Project Images
            </h4>

            <div class="row g-4 project-images">

                <?php foreach ($images as $img): ?>

                    <div class="col-md-4">

                        <img
                            src="/decorluxe/uploads/projects/<?= $img['image_path'] ?>"
                            alt="project image">

                    </div>

                <?php endforeach; ?>

            </div>

        </div>

    <?php endif; ?>

</div>