<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once '../../../includes/db.inc.php';

$selected = [];

$q = mysqli_query($conn, "
    SELECT ref_id
    FROM homepage_settings
    WHERE type='service'
");

while ($r = mysqli_fetch_assoc($q)) {
    $selected[] = $r['ref_id'];
}

$result = mysqli_query($conn, "
    SELECT service_id, service_name
    FROM services
    WHERE status = 1
    ORDER BY service_name ASC
");

while ($row = mysqli_fetch_assoc($result)) {

    $checked = in_array($row['service_id'], $selected) ? 'checked' : '';

    echo '
    <div class="form-check mb-2 text-start">
        <input class="form-check-input"
            type="checkbox"
            name="services[]"
            value="' . $row['service_id'] . '"
            id="service' . $row['service_id'] . '"
            ' . $checked . '>

        <label class="form-check-label"
            for="service' . $row['service_id'] . '">
            ' . htmlspecialchars($row['service_name']) . '
        </label>
    </div>';
}
?>