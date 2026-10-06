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
/*
|--------------------------------------------------------------------------
| Check already generated
|--------------------------------------------------------------------------
*/

function materialRequestExists($quotation_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT request_id
        FROM material_requests
        WHERE quotation_id=?
        LIMIT 1
    ");

    $stmt->bind_param("i",$quotation_id);

    $stmt->execute();

    $stmt->store_result();

    $exists=$stmt->num_rows>0;

    $stmt->close();

    return $exists;
}

function createMaterialRequestsFromQuotation($quotation_id)
{
    global $conn;

    /*
    Get Project ID
    */

    $stmt=$conn->prepare("
        SELECT project_id
        FROM projects
        WHERE quotation_id=?
    ");

    $stmt->bind_param("i",$quotation_id);

    $stmt->execute();

    $project=$stmt->get_result()->fetch_assoc();

    $stmt->close();

    if(!$project){
        return;
    }

    $project_id=$project['project_id'];



    /*
    Get quotation items
    */

    $stmt=$conn->prepare("

        SELECT

        item_id,

        quantity

        FROM quotation_items

        WHERE quotation_id=?

    ");

    $stmt->bind_param("i",$quotation_id);

    $stmt->execute();

    $items=$stmt->get_result();



    while($row=$items->fetch_assoc()){

        $insert=$conn->prepare("

            INSERT INTO material_requests(

                quotation_id,

                project_id,

                item_id,

                required_qty

            )

            VALUES(?,?,?,?)

        ");

        $insert->bind_param(

            "iiid",

            $quotation_id,

            $project_id,

            $row['item_id'],

            $row['quantity']

        );

        $insert->execute();

        $insert->close();

    }

    $stmt->close();
}

function getRequiredProjectCount($item_id)
{
    global $conn;

    $stmt=$conn->prepare("

        SELECT COUNT(DISTINCT project_id) total

        FROM material_requests

        WHERE item_id=?

        AND status='Pending'

    ");

    $stmt->bind_param("i",$item_id);

    $stmt->execute();

    $result=$stmt->get_result()->fetch_assoc();

    $stmt->close();

    return $result['total'];
}

function getProjectsUsingItem($item_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT
            mr.request_id,
            mr.project_id,
            p.project_no,
            p.project_name,
            mr.required_qty,
            mr.allocated_qty,
            mr.status
        FROM material_requests mr
        INNER JOIN projects p
            ON mr.project_id = p.project_id
        WHERE mr.item_id = ?
        AND mr.status = 'Pending'
        ORDER BY p.project_no
    ");

    $stmt->bind_param("i", $item_id);
    $stmt->execute();

    return $stmt->get_result();
}

function getProjectTooltip($item_id)
{
    $projects=getProjectsUsingItem($item_id);

    $html="";

    while($p=$projects->fetch_assoc()){

        $html.="<b>".$p['project_name']."</b>";

        $html.="<br>";

        $html.="Need : ".$p['required_qty'];

        $html.="<hr style='margin:4px'>";
    }

    return htmlspecialchars($html,ENT_QUOTES);
}

