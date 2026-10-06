<?php
require_once '../../data/admin/project_model.php';

$search = trim($_GET['search'] ?? '');

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Fetch data
$projects = getAllProjects($limit, $offset, $search);
$totalRows = countProjects($search);
$totalPages = ceil($totalRows / $limit);



// $projects = getAllProjects();
?>

<table class="table table-striped table-hover border">

    <thead>
        <tr>
            <th>Project No</th>
            <th>Client</th>
            <th>Status</th>
            <th>Start</th>
            <th>Completed</th>
            <th>Note*</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>

        <?php foreach ($projects as $row): ?>
            <?php
            // $expected = strtotime($row['expected_end_date']);
            // $actual = strtotime($row['actual_end_date']);

            // $days = floor(($actual - $expected) / 86400);

            // display concept

            $display = '';

            if ($row['status'] === 'Completed') {
                $display = 'd-block';
            } else {
                $display = 'd-none';
            }
            ?>

            <tr>
                <td><?= $row['project_no'] ?></td>
                <td><?= $row['fullname'] ?></td>
                <td><?= $row['status'] ?></td>
                <td><?= !empty($row['start_date']) ? date('d-m-Y', strtotime($row['start_date'])) : '' ?></td>
                <td><?= !empty($row['actual_end_date']) ? date('d-m-Y', strtotime($row['actual_end_date'])) : '' ?></td>
                <td>
                    <?php
                    if (!empty($row['expected_end_date']) && !empty($row['actual_end_date'])) {

                        $expected = new DateTime(date('Y-m-d', strtotime($row['expected_end_date'])));
                        $actual   = new DateTime(date('Y-m-d', strtotime($row['actual_end_date'])));

                        $days = (int)$expected->diff($actual)->format('%r%a');

                        if ($days < 0) {
                            echo "Completed " . abs($days) . " day(s) early";
                        } elseif ($days == 0) {
                            echo "Completed on time";
                        } else {
                            echo "Delayed by {$days} day(s)";
                        }
                    } else {
                        echo "-";
                    }
                    ?>
                </td>

                <td class="d-flex justify-content-center gap-2">
                    <a
                        href="/decorluxe/business/admin/projects/project_pdf.php?id=<?= $row['project_id'] ?>"
                        target="_blank"
                        class="btn text-white <?= $display ?>" style="background-color: #a94442;">

                        Generate PDF

                    </a>
                    <button
                        onclick="viewProject(<?= $row['project_id'] ?>)"
                        class="btn btn-primary btn-sm d-flex align-items-center gap-2" style="background-color: #2D332D;">
                        <i class="material-icons">visibility</i> View
                    </button>
                    <button
                        onclick="editProject(<?= $row['project_id'] ?>)"
                        class="btn btn-success btn-sm d-flex align-items-center gap-2" style="background-color: #6c757d;">
                        <i class="material-icons">edit_square</i> Edit
                    </button>

                </td>

            </tr>

        <?php endforeach ?>

    </tbody>

</table>

<nav class="mt-3">
    <ul class="pagination justify-content-center">

        <!-- Previous -->
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link"
                    href="#"
                    onclick="loadProjects(<?= $page - 1 ?>, '<?= htmlspecialchars($search, ENT_QUOTES) ?>'); return false;">
                    Previous
                </a>
            </li>
        <?php endif; ?>

        <!-- Page Numbers -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link"
                    href="#"
                    onclick="loadProjects(<?= $i ?>, '<?= htmlspecialchars($search, ENT_QUOTES) ?>'); return false;">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <!-- Next -->
        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link"
                    href="#"
                    onclick="loadProjects(<?= $page + 1 ?>, '<?= htmlspecialchars($search, ENT_QUOTES) ?>'); return false;">
                    Next
                </a>
            </li>
        <?php endif; ?>

    </ul>
</nav>

<script>
    function loadProjects(page = 1, search = '') {

        fetch(
                `/decorluxe/business/admin/project_management_process.php?page=${page}&search=${encodeURIComponent(search)}`
            )
            .then(response => response.text())
            .then(html => {

                document.getElementById("dynamic-content").innerHTML = html;

            });
    }
</script>