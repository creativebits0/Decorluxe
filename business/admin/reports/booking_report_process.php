<?php
include_once 'booking_controller.php';

$clients = getAllClients();
$services = getAllServices();
?>

<div class="container-fluid mt-4">

    <div class="card shadow-sm">
        <!-- 
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Booking Report</h4>
            </div> -->

        <div class="card-body">

            <form method="GET">

                <div class="row">

                    <div class="col-md-3">
                        <label>Report Type</label>

                        <select name="report_type" id="reportType" class="form-control">
                            <option value="">All</option>

                            <option value="daily"
                                <?= ($_GET['report_type'] ?? '') == 'daily' ? 'selected' : ''; ?>>
                                Daily
                            </option>

                            <option value="monthly"
                                <?= ($_GET['report_type'] ?? '') == 'monthly' ? 'selected' : ''; ?>>
                                Monthly
                            </option>

                            <option value="yearly"
                                <?= ($_GET['report_type'] ?? '') == 'yearly' ? 'selected' : ''; ?>>
                                Yearly
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3 d-none" id="dailyFrom">
                        <label>From Date</label>
                        <input type="date"
                            name="from_date"
                            class="form-control"
                            value="<?= $_GET['from_date'] ?? ''; ?>">
                    </div>

                    <div class="col-md-3 d-none" id="dailyTo">
                        <label>To Date</label>
                        <input type="date"
                            name="to_date"
                            class="form-control"
                            value="<?= $_GET['to_date'] ?? ''; ?>">
                    </div>

                    <!-- <div class="col-md-3 d-none" id="monthSelect">
                        <label>Select Month</label>
                        <select name="month" class="form-control">
                            <option value="">Select Month</option>
                            <?php
                            for ($m = 1; $m <= 12; $m++) {
                                echo "<option value='$m'>" . date('F', mktime(0, 0, 0, $m, 1)) . "</option>";
                            }
                            ?>
                        </select>
                    </div> -->

                    <div class="col-md-3 d-none" id="monthSelect">
                        <label>Select Month</label>

                        <input type="month"
                            name="selected_month"
                            class="form-control"
                            value="<?= $_GET['selected_month'] ?? ''; ?>">
                    </div>
                    <div class="col-md-3 d-none" id="yearSelect">
                        <label>Select Year</label>

                        <select name="year" class="form-control">
                            <option value="">Select Year</option>

                            <?php
                            $currentYear = date('Y');

                            for ($y = 2020; $y <= $currentYear; $y++) {
                            ?>
                                <option value="<?= $y; ?>"
                                    <?= ($_GET['year'] ?? '') == $y ? 'selected' : ''; ?>>
                                    <?= $y; ?>
                                </option>
                            <?php } ?>

                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>Client</label>
                        <select name="client" class="form-control">
                            <option value="">All Clients</option>

                            <?php foreach ($clients as $client): ?>
                                <option value="<?= $client['client_id'] ?>">
                                    <?= $client['fullname'] ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>

                </div>

                <br>

                <div class="row">

                    <div class="col-md-3">
                        <label>Service</label>
                        <select name="service" class="form-control">
                            <option value="">All Services</option>

                            <?php foreach ($services as $service): ?>
                                <option value="<?= $service['service_id'] ?>">
                                    <?= $service['service_name'] ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">All</option>
                            <option>Pending</option>
                            <option>Completed</option>
                            <option>Cancelled</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>&nbsp;</label>

                        <button type="submit" class="btn btn-success form-control">
                            Generate Report
                        </button>
                    </div>

                    <div class="col-md-3">
                        <label>&nbsp;</label>

                        <a href="/decorluxe/business/admin/generatePdf/booking_report_pdf.php?<?= http_build_query($_GET); ?>"
                            target="_blank"
                            class="btn btn-danger form-control">

                            Generate PDF
                        </a>
                    </div>

                </div>

            </form>

        </div>
    </div>

    <br>

    <div class="card shadow-sm">

        <div class="card-header">
            <h5>Report Result</h5>
        </div>

        <!-- Filter details  -->
        <?php
        $reportType = $_GET['report_type'] ?? '';
        $fromDate = $_GET['from_date'] ?? '';
        $toDate = $_GET['to_date'] ?? '';
        $selectedMonth = $_GET['selected_month'] ?? '';
        $year = $_GET['year'] ?? '';
        $client = $_GET['client'] ?? '';
        $service = $_GET['service'] ?? '';
        $status = $_GET['status'] ?? '';
        ?>

        <div class="card shadow-sm mb-3">

            <div class="card-body">

                <div class="row g-3">

                    <!-- Report Type -->
                    <div class="col-md-2">
                        <strong>Report Type:</strong><br>

                        <?php
                        if ($reportType == 'daily') {
                            echo "Daily";
                        } elseif ($reportType == 'monthly') {
                            echo "Monthly";
                        } elseif ($reportType == 'yearly') {
                            echo "Yearly";
                        } else {
                            echo "All";
                        }
                        ?>
                    </div>

                    <!-- Daily -->
                    <?php if ($reportType == 'daily') { ?>

                        <div class="col-md-2">
                            <strong>From:</strong><br>
                            <?= $fromDate ?: 'N/A'; ?>
                        </div>

                        <div class="col-md-2">
                            <strong>To:</strong><br>
                            <?= $toDate ?: 'N/A'; ?>
                        </div>

                    <?php } ?>

                    <!-- Monthly -->
                    <?php if ($reportType == 'monthly') { ?>

                        <div class="col-md-2">
                            <strong>Month:</strong><br>

                            <?php
                            if ($selectedMonth) {
                                echo date('F Y', strtotime($selectedMonth));
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </div>

                    <?php } ?>

                    <!-- Yearly -->
                    <?php if ($reportType == 'yearly') { ?>

                        <div class="col-md-2">
                            <strong>Year:</strong><br>
                            <?= $year ?: 'N/A'; ?>
                        </div>

                    <?php } ?>

                    <!-- Client -->
                    <div class="col-md-2">
                        <strong>Client:</strong><br>

                        <?php
                        $clientName = 'All Clients';

                        foreach ($clients as $c) {
                            if ($c['client_id'] == $client) {
                                $clientName = $c['fullname'];
                            }
                        }

                        echo $clientName;
                        ?>
                    </div>

                    <!-- Service -->
                    <div class="col-md-2">
                        <strong>Service:</strong><br>

                        <?php
                        $serviceName = 'All Services';

                        foreach ($services as $s) {
                            if ($s['service_id'] == $service) {
                                $serviceName = $s['service_name'];
                            }
                        }

                        echo $serviceName;
                        ?>
                    </div>

                    <!-- Status -->
                    <!-- <div class="col-md-3">
                <strong>Status:</strong><br>
                <?= $status ?: 'All'; ?>
            </div> -->

                </div>

            </div>

        </div>
        <!-- Table of result -->
        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Appointment No</th>
                        <th>Client</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Appointment Type</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    $report = getBookingReport($_GET ?? []);
                    $i = 1;

                    foreach ($report as $row):
                    ?>

                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= date('d-m-Y', strtotime($row['appointment_date'])) ?></td>
                            <td><?= $row['appointment_id'] ?></td>
                            <td><?= $row['fullname'] ?></td>
                            <td><?= $row['service_name'] ?></td>
                            <td><?= $row['status'] ?></td>
                            <td><?= $row['appointment_type'] ?></td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>


<!-- 
<script>
    const reportType = document.getElementById('reportType');

    function toggleFields() {

        document.getElementById('dailyFrom').classList.add('d-none');
        document.getElementById('dailyTo').classList.add('d-none');
        document.getElementById('monthSelect').classList.add('d-none');
        document.getElementById('yearSelect').classList.add('d-none');

        if (reportType.value === 'daily') {
            document.getElementById('dailyFrom').classList.remove('d-none');
            document.getElementById('dailyTo').classList.remove('d-none');
        }

        if (reportType.value === 'monthly') {
            document.getElementById('monthSelect').classList.remove('d-none');
        }

        if (reportType.value === 'yearly') {
            document.getElementById('yearSelect').classList.remove('d-none');
        }
    }

    reportType.addEventListener('change', toggleFields);

    // Run on page load
    toggleFields();
</script> -->