<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

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

// ==============================
$selectedProjects = [];
$projectDescriptions = [];

$result = mysqli_query($conn, "SELECT * FROM homepage_settings WHERE type='project'");

while($row = mysqli_fetch_assoc($result)){

    $selectedProjects[] = $row['ref_id'];
    $projectDescriptions[$row['ref_id']] = $row['description'];

}
// =========================
$sql = "
SELECT 
    p.project_id,
    p.project_name,
    p.project_no,
    (
        SELECT pi.image_path
        FROM project_images pi
        WHERE pi.project_id = p.project_id
        LIMIT 1
    ) AS project_image
FROM projects p
ORDER BY p.project_id DESC
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die(mysqli_error($conn));
}

echo '<div class="row">'; // start row

while ($row = mysqli_fetch_assoc($result)) {

    $id = $row['project_id'];

    $name = trim($row['project_name']);

    if ($name == '') {
        $name = !empty($row['project_no'])
            ? $row['project_no']
            : 'Project #' . $id;
    }

    $img = !empty($row['project_image'])
        ? '/decorluxe/uploads/projects/' . $row['project_image']
        : '/decorluxe/assets/images/no-image.png';

  echo '
<div class="col-md-3 mb-3">
    <div class="card shadow-sm h-100 text-center">

        <div class="card-body">

            <input class="form-check-input mb-2"
                type="checkbox"
                name="projects[]"
                value="' . $id . '"
                id="project' . $id . '" ' .
                (in_array($id, $selectedProjects) ? 'checked' : '') .
            '>

            <img src="' . $img . '"
                 class="img-fluid mb-2"
                 style="height:120px; object-fit:cover; border-radius:8px;">

            <h6 class="fw-bold">' . htmlspecialchars($name) . '</h6>

            <textarea
                class="form-control mt-2"
                rows="2"
                name="project_desc_' . $id . '"
                placeholder="Description">' .
                htmlspecialchars($projectDescriptions[$id] ?? '') .
            '</textarea>

        </div>
    </div>
</div>';
}

echo '</div>'; // end row