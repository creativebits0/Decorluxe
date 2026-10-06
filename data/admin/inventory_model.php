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

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// function getItems($limit, $offset)
// {
//     global $conn;


//     $sql = "SELECT * FROM items  ORDER BY item_id DESC LIMIT ? OFFSET ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("ii", $limit, $offset);

//     $stmt->execute();
//     $result = $stmt->get_result();
//     $items = $result->fetch_all(MYSQLI_ASSOC);
//     $stmt->close();
//     return $items;
// }


// function countItems()
// {
//     global $conn;

//     $sql = "SELECT COUNT(*) as total FROM items";
//     $result = mysqli_query($conn, $sql);
//     $row = mysqli_fetch_assoc($result);

//     return $row['total'];
// }

function getItems($limit, $offset, $search = '')
{
    global $conn;

    $searchParam = "%{$search}%";

    $sql = "
        SELECT *
        FROM items
        WHERE (
            itemCode LIKE ?
            OR itemName LIKE ?
            OR brand LIKE ?
            OR unit LIKE ?
        )
        ORDER BY item_id ASC
        LIMIT ? OFFSET ?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssssii",
        $searchParam,
        $searchParam,
        $searchParam,
        $searchParam,
        $limit,
        $offset
    );

    $stmt->execute();

    $result = $stmt->get_result();
    $items = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $items;
}

function countItems($search = '')
{
    global $conn;

    $searchParam = "%{$search}%";

    $sql = "
        SELECT COUNT(*) AS total
        FROM items
        WHERE (
            itemCode LIKE ?
            OR itemName LIKE ?
            OR brand LIKE ?
            OR unit LIKE ?
        ) 
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssss",
        $searchParam,
        $searchParam,
        $searchParam,
        $searchParam
    );

    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stmt->close();

    return $row['total'];
}

// function addItems($itemCode, $itemName, $brand, $unit, $sellPrice, $min_stock, $imagePath)
// {
//     global $conn;

//     $stmt = $conn->prepare("
//         INSERT INTO items (itemCode, itemName, brand, unit, sellPrice, min_stock, image_path)
//         VALUES (?, ?, ?, ?, ?, ?, ?)
//     ");

//     $stmt->bind_param("ssssdis", $itemCode, $itemName, $brand, $unit, $sellPrice, $min_stock, $imagePath);

//     $result = $stmt->execute();
//     $stmt->close();
//     return $result;
// }

function addItems($itemCode, $itemName, $brand, $unit, $sellPrice, $min_stock, $imagePath)
{
    global $conn;

    // Check if item code already exists
    $check = $conn->prepare("SELECT item_id FROM items WHERE itemCode = ?");
    $check->bind_param("s", $itemCode);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['alert_message'] = "Item code already exists.";
        $_SESSION['alert_type'] = "danger";
        $check->close();
        return false;
    }
    $check->close();

    // Insert item
    $stmt = $conn->prepare("
        INSERT INTO items (itemCode, itemName, brand, unit, sellPrice, min_stock, image_path)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssssdis",
        $itemCode,
        $itemName,
        $brand,
        $unit,
        $sellPrice,
        $min_stock,
        $imagePath
    );

    $result = $stmt->execute();
    $stmt->close();

    if ($result) {
        $_SESSION['success_message'] = "✅Item added successfully.";
        $_SESSION['alert_type'] = "success";
    } else {
        $_SESSION['alert_message'] = "❌ Failed to add item.";
        $_SESSION['alert_type'] = "danger";
    }

    return $result;
}


function getItemsById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM items WHERE item_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $items = $result->fetch_assoc();
    $stmt->close();
    return $items;
}

// function updateItems($id, $itemCode,$itemName, $brand, $unit, $sellPrice, $min_stock, $imagePath)
// {
//     global $conn;
//     $stmt = $conn->prepare("UPDATE items SET itemCode=?,itemName=?,brand=?,unit=?, sellPrice=?, min_stock=?, image_path=? WHERE item_id=?");
//     $stmt->bind_param("ssssdisi", $itemCode,$itemName, $brand, $unit, $sellPrice, $min_stock, $imagePath, $id);
//     $result = $stmt->execute();
//     $stmt->close();
//     return $result;
// }

function updateItems($id, $itemCode, $itemName, $brand, $unit, $sellPrice, $min_stock, $imagePath = null)
{
    global $conn;

    // Check if item code already exists
    $check = $conn->prepare("SELECT item_id FROM items WHERE itemCode = ? AND item_id != ?");
    $check->bind_param("si", $itemCode, $id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['alert_message'] = "Item code already exists.";
        $_SESSION['alert_type'] = "danger";
        $check->close();
        return false;
    }
    $check->close();

    if ($imagePath) {
        $stmt = $conn->prepare("
            UPDATE items 
            SET itemCode=?, itemName=?, brand=?, unit=?, sellPrice=?, min_stock=?, image_path=? 
            WHERE item_id=?
        ");
        $stmt->bind_param("ssssdisi", $itemCode, $itemName, $brand, $unit, $sellPrice, $min_stock, $imagePath, $id);
    } else {
        $stmt = $conn->prepare("
            UPDATE items 
            SET itemCode=?, itemName=?, brand=?, unit=?, sellPrice=?, min_stock=? 
            WHERE item_id=?
        ");
        $stmt->bind_param("ssssdii", $itemCode, $itemName, $brand, $unit, $sellPrice, $min_stock, $id);
    }

    $result = $stmt->execute();
    $stmt->close();

    if ($result) {
        $_SESSION['success_message'] = "✅ Item Updated successfully.";
        $_SESSION['alert_type'] = "success";
    } else {
        $_SESSION['alert_message'] = "❌ Failed to update item.";
        $_SESSION['alert_type'] = "danger";
    }
    return $result;
}
// Delete item in edit form

function clearItemImage($id)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE items SET image_path = NULL WHERE item_id = ?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function getAllitems()
{
    global $conn;
    $stmt = $conn->prepare("SELECT item_id,itemCode, itemName, brand,sellPrice, image_path FROM items ORDER BY itemName ASC");
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $item;
}

// Purchase

function addPurchase(
    $supplier_id,
    $invoice_no,
    $purchase_date,
    $discount_type,
    $discount,
    $total,
    $status,
    $created_by,
    $bill_file,
    $items
) {

    global $conn;

    // Check if bill number already exists
    $check = $conn->prepare("SELECT purchase_id FROM purchases WHERE bill_no = ?");
    $check->bind_param("s", $invoice_no);
    $check->execute();

    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['alert_message'] = "Invoice number already exists.";
        $check->close();
        return false;
    }

    $check->close();

    $conn->begin_transaction();

    try {

        /* ---------- Insert Purchase ---------- */

        $stmt = $conn->prepare("INSERT INTO purchases 
        (supplier_id,bill_no,purchase_date,discount, discount_type,total_amount,status,created_by,bill_file)
        VALUES (?,?,?,?,?,?,?,?,?)");

        $stmt->bind_param(
            "isssddsss",
            $supplier_id,
            $invoice_no,
            $purchase_date,
            $discount_type,
            $discount,
            $total,
            $status,
            $created_by,
            $bill_file
        );

        $stmt->execute();

        $purchase_id = $conn->insert_id;

        /* ---------- Insert Items ---------- */

        $itemStmt = $conn->prepare("INSERT INTO purchase_items
        (purchase_id,item_id,quantity,unit_price, discount_type,discount,total)
        VALUES (?,?,?,?,?,?,?)");

        foreach ($items as $item) {

            $itemStmt->bind_param(
                "iiddsdd",
                $purchase_id,
                $item['item_id'],
                $item['qty'],
                $item['price'],
                $item['discount_type'],
                $item['discount'],
                $item['total']
            );

            $itemStmt->execute();

            /* ---------- Update Stock ---------- */

            // updateStock($item['item_id'], $item['qty']);
            if ($status == 'Verified') {

                addStock(
                    $item['item_id'],
                    $item['qty'],
                    'purchase',
                    $purchase_id
                );
            }
        }

        $conn->commit();
        $_SESSION['success_message'] = "✅ Purchase Details Added Successfully.";
        return true;
    } catch (Exception $e) {

        $conn->rollback();
        $_SESSION['alert_message'] = "❌ Failed to add purchase details.<br>" . $e->getMessage();
        return false;
    }
}

// function getAllPurchase()
// {
//     global $conn;

//     $stmt = $conn->prepare("
//         SELECT 
//             p.*,
//             s.supplier_name
//         FROM purchases p
//         LEFT JOIN suppliers s
//             ON p.supplier_id = s.supplier_id
//         ORDER BY p.purchase_id DESC
//     ");

//     $stmt->execute();

//     $result = $stmt->get_result();
//     $purchases = $result->fetch_all(MYSQLI_ASSOC);

//     $stmt->close();

//     return $purchases;
// }

// function getAllPurchase($limit, $offset)
// {
//     global $conn;

//     $sql = "
//         SELECT 
//             p.*,
//             s.supplier_name
//         FROM purchases p
//         LEFT JOIN suppliers s
//             ON p.supplier_id = s.supplier_id
//         ORDER BY p.purchase_id DESC
//         LIMIT ? OFFSET ?
//     ";

//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("ii", $limit, $offset);
//     $stmt->execute();

//     $result = $stmt->get_result();
//     $purchases = $result->fetch_all(MYSQLI_ASSOC);

//     $stmt->close();

//     return $purchases;
// }

// function countPurchases()
// {
//     global $conn;

//     $sql = "SELECT COUNT(*) as total FROM purchases";
//     $result = mysqli_query($conn, $sql);
//     $row = mysqli_fetch_assoc($result);

//     return $row['total'];
// }

function getAllPurchase($limit, $offset, $search = '')
{
    global $conn;

    $searchParam = "%{$search}%";

    $sql = "
        SELECT
            p.*,
            s.supplier_name
        FROM purchases p
        LEFT JOIN suppliers s
            ON p.supplier_id = s.supplier_id
        WHERE (
            p.bill_no LIKE ?
            OR s.supplier_name LIKE ?
            OR p.status LIKE ?
            OR p.purchase_date LIKE ?
            OR p.total_amount LIKE ?
        )
        ORDER BY p.purchase_id DESC
        LIMIT ? OFFSET ?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sssssii",
        $searchParam,
        $searchParam,
        $searchParam,
        $searchParam,
        $searchParam,
        $limit,
        $offset
    );

    $stmt->execute();

    $result = $stmt->get_result();
    $purchases = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $purchases;
}

function countPurchases($search = '')
{
    global $conn;

    $searchParam = "%{$search}%";

    $sql = "
        SELECT COUNT(*) AS total
        FROM purchases p
        LEFT JOIN suppliers s
            ON p.supplier_id = s.supplier_id
        WHERE (
            p.bill_no LIKE ?
            OR s.supplier_name LIKE ?
            OR p.status LIKE ?
            OR p.purchase_date LIKE ?
            OR p.total_amount LIKE ?
        )
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sssss",
        $searchParam,
        $searchParam,
        $searchParam,
        $searchParam,
        $searchParam
    );

    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stmt->close();

    return $row['total'];
}

function updatePurchase(
    $purchase_id,
    $supplier_id,
    $bill_no,
    $updated_by,
    $purchase_date,
    $discount_type,
    $discount,
    $total,
    $status,
    $items
) {

    global $conn;

    // Check if bill number already exists
    $check = $conn->prepare("SELECT purchase_id FROM purchases WHERE bill_no = ? AND purchase_id != ?");
    $check->bind_param("si", $bill_no, $purchase_id);
    $check->execute();

    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['alert_message'] = "Invoice number already exists.";
        $check->close();
        return false;
    }

    $check->close();

    $conn->begin_transaction();

    try {

        /* Get Old Purchase Status */

        $oldPurchase = getPurchaseById($purchase_id);
        $oldStatus = $oldPurchase['status'];

        /* Get Old Items (For Stock Reverse) */

        $oldItems = getPurchaseItems($purchase_id);


        /* Update Purchase */

        $stmt = $conn->prepare("
            UPDATE purchases
            SET supplier_id=?,
            bill_no=?,
            purchase_date=?,
            discount_type=?,
            discount=?,
            total_amount=?,
            status=?,
            updated_by=?
            WHERE purchase_id=?
        ");

        $stmt->bind_param(
            "isssddssi",
            $supplier_id,
            $bill_no,
            $purchase_date,
            $discount_type,
            $discount,
            $total,
            $status,
            $updated_by,
            $purchase_id
        );

        $stmt->execute();


        /* Reverse Old Stock (If already verified) */

        if ($oldStatus == 'Verified') {

            foreach ($oldItems as $old) {

                reduceStock(
                    $old['item_id'],
                    $old['quantity'],
                    'purchase_edit',
                    $purchase_id
                );
            }
        }


        /* Delete Old Items */

        $conn->query("
            DELETE FROM purchase_items
            WHERE purchase_id=$purchase_id
        ");


        /* Insert New Items */

        foreach ($items as $item) {

            $stmt = $conn->prepare("
                INSERT INTO purchase_items
                (
                purchase_id,
                item_id,
                quantity,
                unit_price,
                discount_type,
                discount,
                total
                )
                VALUES(?,?,?,?,?,?,?)
            ");

            $stmt->bind_param(
                "iiidsdd",
                $purchase_id,
                $item['item_id'],
                $item['qty'],
                $item['price'],
                $item['discount_type'],
                $item['discount'],
                $item['total']
            );

            $stmt->execute();
        }


        /* Add Stock If Verified */

        if ($status == 'Verified') {

            foreach ($items as $item) {

                addStock(
                    $item['item_id'],
                    $item['qty'],
                    'purchase',
                    $purchase_id
                );
            }
        }


        $conn->commit();
        $_SESSION['success_message'] = "✅ Purchase Details Added Successfully.";

        return true;
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['alert_message'] = "❌ Failed to add purchase details.<br>" . $e->getMessage();

        die($e->getMessage());
    }
}

function getPurchaseById($id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT 
            p.*,
            s.supplier_name
        FROM purchases p
        LEFT JOIN suppliers s 
            ON p.supplier_id = s.supplier_id
        WHERE p.purchase_id = ?
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $purchase = $result->fetch_assoc();

    $stmt->close();

    return $purchase;
}

function isPurchaseVerified($purchase_id)
{
    global $conn;

    $stmt = $conn->prepare("SELECT status FROM purchases WHERE purchase_id = ?");
    $stmt->bind_param("i", $purchase_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return ($row['status'] === 'Verified');
    }

    return false;
}


function getPurchaseItems($purchase_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT 
            pi.*,
            i.itemName
        FROM purchase_items pi
        LEFT JOIN items i
            ON pi.item_id = i.item_id
        WHERE pi.purchase_id = ?
        ORDER BY pi.item_id ASC
    ");

    $stmt->bind_param("i", $purchase_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $items = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $items;
}
// SALES

function addSales(
    $invoice_no,
    $sales_date,
    $discount_type,
    $discount,
    $total,
    $created_by,
    $bill_file,
    $items
) {

    global $conn;
    // Check if bill number already exists
    $check = $conn->prepare("SELECT sales_id FROM sales WHERE invoice_no = ?");
    $check->bind_param("s", $invoice_no);
    $check->execute();

    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['alert_message'] = "Invoice number already exists.";
        $check->close();
        return false;
    }

    $check->close();
    $conn->begin_transaction();

    try {

        /* ---------- Insert Purchase ---------- */

        $stmt = $conn->prepare("INSERT INTO sales 
        (invoice_no,sales_date,discount_type,discount,total_amount,created_by,bill_file)
        VALUES (?,?,?,?,?,?,?)");

        $stmt->bind_param(
            "sssddss",
            $invoice_no,
            $sales_date,
            $discount_type,
            $discount,
            $total,
            $created_by,
            $bill_file
        );

        $stmt->execute();

        $sales_id = $conn->insert_id;

        /* ---------- Insert Items ---------- */

        $itemStmt = $conn->prepare("INSERT INTO sales_items
        (sales_id,item_id,qty,unit_price, discount_type,discount,total)
        VALUES (?,?,?,?,?,?,?)");

        foreach ($items as $item) {

            $itemStmt->bind_param(
                "iiddsdd",
                $sales_id,
                $item['item_id'],
                $item['qty'],
                $item['price'],
                $item['discount_type'],
                $item['discount'],
                $item['total']
            );

            $itemStmt->execute();

            /* ---------- Update Stock ---------- */

            // updateStock($item['item_id'], $item['qty']);
            reduceStock(
                $item['item_id'],
                $item['qty'],
                'sales',
                $sales_id
            );
        }

        $conn->commit();
        $_SESSION['success_message'] = "Sales Details added successfully.";

        return true;
    } catch (Exception $e) {

        $conn->rollback();
        $_SESSION['alert_message'] = "Sales details failed to add.";

        return false;
    }
}




// function getAllSales($limit, $offset)
// {
//     global $conn;

//     $stmt = $conn->prepare("
//         SELECT 
//             s.*,

//             (
//                 SELECT IFNULL(SUM(total),0)
//                 FROM sales_items si
//                 WHERE si.sales_id = s.sales_id
//             ) AS subtotal,

//             (
//                 SELECT IFNULL(SUM(sri.qty * si.unit_price),0)
//                 FROM sales_returns sr
//                 JOIN sales_return_items sri ON sr.return_id = sri.return_id
//                 JOIN sales_items si 
//                     ON si.sales_id = s.sales_id
//                     AND si.item_id = sri.item_id
//                 WHERE sr.sales_id = s.sales_id
//             ) AS returned_amount

//         FROM sales s
//         ORDER BY s.sales_id DESC
//         LIMIT ? OFFSET ?
//     ");

//     $stmt->bind_param("ii", $limit, $offset);
//     $stmt->execute();

//     $result = $stmt->get_result();
//     $sales = $result->fetch_all(MYSQLI_ASSOC);

//     $stmt->close();

//     return $sales;
// }
// function countSales()
// {
//     global $conn;

//     $sql = "SELECT COUNT(*) as total FROM sales";
//     $result = mysqli_query($conn, $sql);
//     $row = mysqli_fetch_assoc($result);

//     return $row['total'];
// }

function getAllSales($limit, $offset, $search = '')
{
    global $conn;

    $searchParam = "%{$search}%";

    $stmt = $conn->prepare("
        SELECT
            s.*,

            (
                SELECT IFNULL(SUM(total),0)
                FROM sales_items si
                WHERE si.sales_id = s.sales_id
            ) AS subtotal,

            (
                SELECT IFNULL(SUM(sri.qty * si.unit_price),0)
                FROM sales_returns sr
                JOIN sales_return_items sri
                    ON sr.return_id = sri.return_id
                JOIN sales_items si
                    ON si.sales_id = s.sales_id
                    AND si.item_id = sri.item_id
                WHERE sr.sales_id = s.sales_id
            ) AS returned_amount

        FROM sales s

        WHERE (
            s.invoice_no LIKE ?
            OR s.sales_date LIKE ?
            OR s.discount_type LIKE ?
        )

        ORDER BY s.sales_id DESC

        LIMIT ? OFFSET ?
    ");

    $stmt->bind_param(
        "sssii",
        $searchParam,
        $searchParam,
        $searchParam,
        $limit,
        $offset
    );

    $stmt->execute();

    $result = $stmt->get_result();
    $sales = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $sales;
}

function countSales($search = '')
{
    global $conn;

    $searchParam = "%{$search}%";

    $stmt = $conn->prepare("
        SELECT COUNT(*) AS total

        FROM sales s

        WHERE (
            s.invoice_no LIKE ?
            OR s.sales_date LIKE ?
            OR s.discount_type LIKE ?
        )
    ");

    $stmt->bind_param(
        "sss",
        $searchParam,
        $searchParam,
        $searchParam
    );

    $stmt->execute();

    $result = $stmt->get_result();

    $row = $result->fetch_assoc();

    $stmt->close();

    return $row['total'];
}

function updateSales(
    $sales_id,
    $invoice_no,
    $sales_date,
    $discount_type,
    $discount,
    $total,
    $bill_file,
    $items
) {
    global $conn;

    // Check if bill number already exists
    $check = $conn->prepare("SELECT sales_id FROM sales WHERE invoice_no = ? AND sales_id != ?");
    $check->bind_param("si", $invoice_no, $sales_id);
    $check->execute();

    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['alert_message'] = "Invoice number already exists.";
        $check->close();
        return false;
    }

    $check->close();

    $conn->begin_transaction();

    try {

        /* Get old sold items */
        $oldItems = getSalesItems($sales_id);

        /* Restore stock from old items */
        foreach ($oldItems as $old) {

            addStock(
                $old['item_id'],
                $old['qty'],
                'sales_edit_restore',
                $sales_id
            );
        }

        /* Update sales header */
        $stmt = $conn->prepare("
            UPDATE sales
            SET invoice_no=?,
                sales_date=?,
                discount_type=?,
                discount=?,
                total_amount=?,
                bill_file=?
            WHERE sales_id=?
        ");

        $stmt->bind_param(
            "sssddsi",
            $invoice_no,
            $sales_date,
            $discount_type,
            $discount,
            $total,
            $bill_file,
            $sales_id
        );

        $stmt->execute();

        /* Delete old items */
        $stmt = $conn->prepare("
            DELETE FROM sales_items
            WHERE sales_id=?
        ");
        $stmt->bind_param("i", $sales_id);
        $stmt->execute();

        /* Insert new items */
        foreach ($items as $item) {

            $stmt = $conn->prepare("
                INSERT INTO sales_items
                (sales_id,item_id,qty,unit_price, discount_type,discount,total)
                VALUES (?,?,?,?,?,?,?)
            ");

            $stmt->bind_param(
                "iiddsdd",
                $sales_id,
                $item['item_id'],
                $item['qty'],
                $item['price'],
                $item['discount_type'],
                $item['discount'],
                $item['total']
            );

            $stmt->execute();

            /* Reduce stock again */
            reduceStock(
                $item['item_id'],
                $item['qty'],
                'sales_edit',
                $sales_id
            );
        }

        $conn->commit();
        $_SESSION['success_message'] = "Sales Details Updated Successfully.";

        return true;
    } catch (Exception $e) {

        $conn->rollback();
        $_SESSION['alert_message'] = "Sales Details Failed to Update.";

        return false;
    }
}

function getSalesById($id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT * 
        FROM sales
        WHERE sales_id = ?
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $sales = $result->fetch_assoc();

    $stmt->close();

    return $sales;
}

function getSalesItems($sales_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT 
            si.*,
            i.itemName
        FROM sales_items si
        LEFT JOIN items i
            ON si.item_id = i.item_id
        WHERE si.sales_id = ?
        ORDER BY si.item_id ASC
    ");

    $stmt->bind_param("i", $sales_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $items = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $items;
}

// Add stock 
function addStock($item_id, $qty, $type, $ref_id)
{
    global $conn;

    // Check if exists
    $stmt = $conn->prepare("
        SELECT qty FROM stock 
        WHERE item_id=?
    ");

    $stmt->bind_param("i", $item_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $conn->query("
            UPDATE stock 
            SET qty = qty + $qty 
            WHERE item_id=$item_id
        ");
    } else {

        $conn->query("
            INSERT INTO stock (item_id,qty)
            VALUES ($item_id,$qty)
        ");
    }

    /* Log */

    $conn->query("
        INSERT INTO stock_logs
        (item_id,qty,type,reference_id)
        VALUES ($item_id,$qty,'$type',$ref_id)
    ");
}

// Reduce addStock
function reduceStock($item_id, $qty, $type, $ref_id)
{
    global $conn;

    $conn->query("
        UPDATE stock
        SET qty = qty - $qty
        WHERE item_id=$item_id
    ");

    /* Log */

    $conn->query("
        INSERT INTO stock_logs
        (item_id,qty,type,reference_id)
        VALUES ($item_id,-$qty,'$type',$ref_id)
    ");
}

function getReturnedQty($sales_id, $item_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT COALESCE(SUM(sri.qty),0) AS returned_qty
        FROM sales_return_items sri
        INNER JOIN sales_returns sr
            ON sr.return_id = sri.return_id
        WHERE sr.sales_id = ?
        AND sri.item_id = ?
    ");

    $stmt->bind_param("ii", $sales_id, $item_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc()['returned_qty'];
}



function processSalesReturn(
    $sales_id,
    $return_date,
    $reason,
    $items
) {

    global $conn;

    $conn->begin_transaction();

    try {

        $stmt = $conn->prepare("
INSERT INTO sales_returns
(sales_id,return_date,reason)
VALUES(?,?,?)
");

        // $user = isset($_SESSION['username']) ? $_SESSION['username'] : 'admin';
        $stmt->bind_param(
            "iss",
            $sales_id,
            $return_date,
            $reason,
            // $user
        );

        $stmt->execute();

        $return_id = $conn->insert_id;


        /* Insert Return Items */

        foreach ($items as $item) {

            $stmt = $conn->prepare("
INSERT INTO sales_return_items
(return_id,item_id,qty)
VALUES(?,?,?)
");

            $stmt->bind_param(
                "iid",
                $return_id,
                $item['item_id'],
                $item['qty']
            );

            $stmt->execute();


            /* Add Stock Back */

            addStock(
                $item['item_id'],
                $item['qty'],
                'return',
                $return_id
            );
        }

        $conn->commit();

        return true;
    } catch (Exception $e) {

        $conn->rollback();

        return false;
    }
}


function getStockItems($limit, $offset, $search, $status)
{
    global $conn;

    $sql = "
        SELECT 
            i.item_id,
            i.itemCode,
            i.itemName,
            i.brand,
            i.unit,
            i.sellPrice,
            i.min_stock,
            i.image_path,
            s.allocated_qty,
            COALESCE(s.qty,0) AS stock_qty,

            sl.type AS last_type,
            sl.created_at AS last_update

        FROM items i
        LEFT JOIN stock s ON i.item_id = s.item_id

        LEFT JOIN stock_logs sl 
            ON sl.log_id = (
                SELECT log_id 
                FROM stock_logs 
                WHERE item_id = i.item_id 
                ORDER BY log_id DESC 
                LIMIT 1
            )

        WHERE 1=1
    ";


    if (!empty($search)) {
        $sql .= " AND (i.itemName LIKE ? OR i.itemCode LIKE ? OR i.brand LIKE ?)";
        $search = "%$search%";
    }

    if (!empty($status)) {
        if ($status == "low") {
            $sql .= " AND COALESCE(s.qty,0) <= i.min_stock";
        } elseif ($status == "ok") {
            $sql .= " AND COALESCE(s.qty,0) > i.min_stock";
        }
    }

    $sql .= " LIMIT ? OFFSET ?";

    $stmt = $conn->prepare($sql);

    if (!empty($search)) {
        $stmt->bind_param("sssii", $search, $search, $search, $limit, $offset);
    } else {
        $stmt->bind_param("ii", $limit, $offset);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $items = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $items;
}

function getStockItemById($id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT 
            i.*,
            COALESCE(s.qty,0) AS stock_qty
        FROM items i
        LEFT JOIN stock s 
            ON i.item_id = s.item_id
        WHERE i.item_id = ?
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $item = $result->fetch_assoc();

    $stmt->close();

    return $item;
}

function countStockItems($search, $status)
{
    global $conn;

    $sql = "
        SELECT COUNT(*) as total
        FROM items i
        LEFT JOIN stock s ON i.item_id = s.item_id
        WHERE 1=1
    ";

    if (!empty($search)) {
        $sql .= " AND (i.itemName LIKE ? OR i.itemCode LIKE ? OR i.brand LIKE ?)";
        $search = "%$search%";
    }

    if (!empty($status)) {
        if ($status == "low") {
            $sql .= " AND COALESCE(s.qty,0) <= i.min_stock";
        } elseif ($status == "ok") {
            $sql .= " AND COALESCE(s.qty,0) > i.min_stock";
        }
    }

    $stmt = $conn->prepare($sql);

    if (!empty($search)) {
        $stmt->bind_param("sss", $search, $search, $search);
    }

    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stmt->close();

    return $row['total'];
}

function getStockLogs($limit, $offset, $item_id, $type)
{
    global $conn;

    $sql = "
        SELECT 
            sl.*,
            i.itemName,
            i.itemCode
        FROM stock_logs sl
        LEFT JOIN items i ON sl.item_id = i.item_id
        WHERE 1=1
    ";

    if (!empty($item_id)) {
        $sql .= " AND sl.item_id = ?";
    }

    if (!empty($type)) {
        $sql .= " AND sl.type = ?";
    }

    $sql .= " ORDER BY sl.log_id DESC LIMIT ? OFFSET ?";

    $stmt = $conn->prepare($sql);

    if (!empty($item_id) && !empty($type)) {

        $stmt->bind_param("isii", $item_id, $type, $limit, $offset);
    } elseif (!empty($item_id)) {

        $stmt->bind_param("iii", $item_id, $limit, $offset);
    } elseif (!empty($type)) {

        $stmt->bind_param("sii", $type, $limit, $offset);
    } else {

        $stmt->bind_param("ii", $limit, $offset);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $logs = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $logs;
}


function countStockLogs($item_id, $type)
{
    global $conn;

    $sql = "
        SELECT COUNT(*) as total
        FROM stock_logs
        WHERE 1=1
    ";

    if (!empty($item_id)) {
        $sql .= " AND item_id = ?";
    }

    if (!empty($type)) {
        $sql .= " AND type = ?";
    }

    $stmt = $conn->prepare($sql);

    if (!empty($item_id) && !empty($type)) {

        $stmt->bind_param("is", $item_id, $type);
    } elseif (!empty($item_id)) {

        $stmt->bind_param("i", $item_id);
    } elseif (!empty($type)) {

        $stmt->bind_param("s", $type);
    }

    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stmt->close();

    return $row['total'];
}

// Low stock notification
function getLowStockItems()
{
    global $conn;

    $sql = "
        SELECT
            i.item_id,
            i.itemCode,
            i.itemName,
            i.min_stock,
            COALESCE(s.qty,0) AS stock_qty
        FROM items i
        LEFT JOIN stock s ON i.item_id = s.item_id
        WHERE COALESCE(s.qty,0) <= i.min_stock
        ORDER BY stock_qty ASC
    ";

    $result = mysqli_query($conn, $sql);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
