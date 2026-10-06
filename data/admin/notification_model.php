<?php


// Admin notification
function addNotification($message, $type = null, $reference_id = null)
{
    global $conn;

    $stmt = $conn->prepare("
        INSERT INTO notifications
        (message, type, reference_id)
        VALUES (?, ?, ?)
    ");

    $stmt->bind_param(
        "ssi",
        $message,
        $type,
        $reference_id
    );

    return $stmt->execute();
}

function getUnreadNotifyCountForProjectIncharge()
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT COUNT(*) AS cnt
        FROM notifications
        WHERE project_incharge_read = 0
    ");

    $stmt->execute();

    return $stmt->get_result()->fetch_assoc()['cnt'];
}

function getUnreadNotifyCountForAdmin()
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT COUNT(*) AS cnt
        FROM notifications
        WHERE admin_read = 0
    ");

    $stmt->execute();

    return $stmt->get_result()->fetch_assoc()['cnt'];
}

function getUnreadNotify()
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT *
        FROM notifications
        WHERE is_read = 0
        ORDER BY created_at DESC
        LIMIT 10
    ");

    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function markAsReadForAdmin($id)
{
    global $conn;

    $stmt = $conn->prepare("
        UPDATE notifications
SET admin_read = 1
WHERE notification_id = ?
    ");

    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
function markAsReadForProjectIncharge($id)
{
    global $conn;

    $stmt = $conn->prepare("
        UPDATE notifications
SET project_incharge_read = 1
WHERE notification_id = ?
    ");

    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
function getNotifications()
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT *
        FROM notifications
        ORDER BY created_at DESC
        LIMIT 20
    ");

    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
function getNotifyById($id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT *
        FROM notifications
        WHERE notification_id = ?
    ");

    $stmt->bind_param("i", $id);

    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}
// lowStock,  quotation approved

function createRoomManagerNotification($quotation_id)
{
    global $conn;

    $message = "Quotation #{$quotation_id} needs material allocation.";

    $stmt = $conn->prepare("
        INSERT INTO room_manager_notifications
        (
            quotation_id,
            message
        )
        VALUES (?, ?)
    ");

    $stmt->bind_param(
        "is",
        $quotation_id,
        $message
    );

    $result = $stmt->execute();

    $stmt->close();

    return $result;
}


function getUnreadNotificationCount()
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT COUNT(*) AS total
        FROM room_manager_notifications
        WHERE is_read = 0
    ");

    $stmt->execute();

    $count = $stmt
        ->get_result()
        ->fetch_assoc()['total'];

    $stmt->close();

    return $count;
}
function getQuotationNo($quotation_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT quotation_no
        FROM quotations
        WHERE quotation_id = ?
    ");

    $stmt->bind_param("i", $quotation_id);

    $stmt->execute();

    $result = $stmt->get_result();

    $quotation = $result->fetch_assoc();

    $stmt->close();

    return $quotation['quotation_no'] ?? '';
}
function getUnreadNotifications()
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT *
        FROM room_manager_notifications
        WHERE is_read = 0
        ORDER BY created_at DESC
    ");

    $stmt->execute();

    $result = $stmt->get_result();

    $notifications = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $notifications;
}

function markNotificationAsRead($notification_id)
{
    global $conn;

    $stmt = $conn->prepare("
        UPDATE room_manager_notifications
        SET is_read = 1
        WHERE notification_id = ?
    ");

    $stmt->bind_param(
        "i",
        $notification_id
    );

    $result = $stmt->execute();

    $stmt->close();

    return $result;
}

function getNotificationById($notification_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT *
        FROM room_manager_notifications
        WHERE notification_id = ?
    ");

    $stmt->bind_param(
        "i",
        $notification_id
    );

    $stmt->execute();

    $notification = $stmt
        ->get_result()
        ->fetch_assoc();

    $stmt->close();

    return $notification;
}

function getQuotationItemTotals($quotation_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT
            i.itemName,
            SUM(qi.quantity) AS total_qty
        FROM quotation_items qi
        INNER JOIN items i
            ON qi.item_id = i.item_id
        WHERE qi.quotation_id = ?
        GROUP BY qi.item_id
        ORDER BY i.itemName
    ");

    $stmt->bind_param(
        "i",
        $quotation_id
    );

    $stmt->execute();

    $result = $stmt->get_result();

    $items = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $items;
}

function notificationExistsForQuotation($quotation_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT notification_id
        FROM room_manager_notifications
        WHERE quotation_id = ?
        LIMIT 1
    ");

    $stmt->bind_param("i", $quotation_id);

    $stmt->execute();

    $exists = $stmt
        ->get_result()
        ->num_rows > 0;

    $stmt->close();

    return $exists;
}

