<?php
session_start();
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

mysqli_query($conn, "DELETE FROM homepage_settings");

# Save Services
if (!empty($_POST['services'])) {

    foreach ($_POST['services'] as $id) {

        $id = (int)$id;

        mysqli_query($conn, "
            INSERT INTO homepage_settings(type, ref_id)
            VALUES('service', $id)
        ");
    }
}

# Save Projects
if (!empty($_POST['projects'])) {

    foreach ($_POST['projects'] as $id) {

        $id = (int)$id;

        $field = "project_desc_" . $id;

        $desc = mysqli_real_escape_string(
            $conn,
            $_POST[$field] ?? ''
        );

        mysqli_query($conn, "
            INSERT INTO homepage_settings(type, ref_id, description)
            VALUES('project', $id, '$desc')
        ");
    }
}

echo "Saved";
?>