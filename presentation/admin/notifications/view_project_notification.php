<?php
session_start();

require_once '../../../data/admin/notification_model.php';
require_once '../../../data/admin/project_model.php';
require_once '../../../data/admin/quotation_model.php';

$role = $_SESSION['role'] ?? '';
$id = $_GET['id'] ?? 0;

$notification = getNotifyById($id);

if (!$notification) {
    exit("Notification not found");
}
if ($role === 'admin') {

    if (!$notification['admin_read']) {
        markAsReadForAdmin($id);
    }
}
elseif ($role === 'project_incharge') {

    if (!$notification['project_incharge_read']) {
        markAsReadForProjectIncharge($id);
    }
}

// markAsRead($id);

echo "<h5>{$notification['message']}</h5>";
echo "<p><strong>Date:</strong> {$notification['created_at']}</p>";

switch ($notification['type']) {

    case 'quotation_approved':

        $quotation =
            getQuotationById($notification['reference_id']);

        if ($quotation) {

            echo "
                <hr>
                <h6>Quotation Details</h6>

                <table class='table table-bordered'>
                    <tr>
                        <th>Quotation No</th>
                        <td>{$quotation['quotation_no']}</td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td>{$quotation['status']}</td>
                    </tr>

                    <tr>
                        <th>Total</th>
                        <td>{$quotation['total_amount']}</td>
                    </tr>
                </table>
            ";
        }

        break;

    case 'project_completed':

        $project =
            getProjectById($notification['reference_id']);

        if ($project) {

            echo "
                <hr>
                <h6>Project Details</h6>

                <table class='table table-bordered'>
                    <tr>
                        <th>Project No</th>
                        <td>{$project['project_no']}</td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td>{$project['status']}</td>
                    </tr>

                    <tr>
                        <th>Total</th>
                        <td>{$project['total_amount']}</td>
                    </tr>
                </table>
            ";
        }

        break;

    default:

        echo "
            <div class='alert alert-info'>
                No additional details available.
            </div>
        ";
}