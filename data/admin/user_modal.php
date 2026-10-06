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



function getUserByUsername($username)
{
    global $conn;

    $sql = "SELECT id, username, password, role, is_password_changed, status 
            FROM Users 
            WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $stmt->close();
    return $user;
}



/**
 * Fetch user’s current hashed password by ID
 */
function getUserPasswordById($user_id)
{
    global $conn;
    $sql = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['password'];
}


/**
 * Update user's password and mark as changed
 */
function updateUserPassword($user_id, $new_hashed_password)
{
    global $conn;
    $sql = "UPDATE users SET password = ?, is_password_changed = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_hashed_password, $user_id);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

function getUserByEmail($email)
{
    global $conn;
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user;
}

/**
 * Save password reset token and expiry time
 */
function saveResetToken($email, $token, $expires)
{
    global $conn;
    $sql = "UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $token, $expires, $email);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

/**
 * Get user details for reset verification
 */
function getUserResetData($email)
{
    global $conn;
    $sql = "SELECT id, reset_token, reset_expires FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user;
}

// user Management 

/**
 * Fetch paginated user data
  */
// function getUsers($userType, $limit, $offset)
// {
//     global $conn;

//     if ($userType !== 'all') {
//         $sql = "SELECT * FROM users WHERE role = ? LIMIT ? OFFSET ?";
//         $stmt = $conn->prepare($sql);
//         $stmt->bind_param("sii", $userType, $limit, $offset);
//     } else {
//         $sql = "SELECT * FROM users LIMIT ? OFFSET ?";
//         $stmt = $conn->prepare($sql);
//         $stmt->bind_param("ii", $limit, $offset);
//     }

//     $stmt->execute();
//     $result = $stmt->get_result();
//     $users = $result->fetch_all(MYSQLI_ASSOC);
//     $stmt->close();
//     return $users;
// }
function getUsers($userType, $limit, $offset, $search = '')
{
    global $conn;

    $searchParam = "%{$search}%";

    if ($userType !== 'all') {

        $sql = "
            SELECT *
            FROM users
            WHERE role = ?
            AND (
                username LIKE ?
                OR email LIKE ?
                OR phone1 LIKE ?
            )
            LIMIT ? OFFSET ?
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssii",
            $userType,
            $searchParam,
            $searchParam,
            $searchParam,
            $limit,
            $offset
        );

    } else {

        $sql = "
            SELECT *
            FROM users
            WHERE (
                username LIKE ?
                OR email LIKE ?
                OR phone1 LIKE ?
            )
            LIMIT ? OFFSET ?
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssii",
            $searchParam,
            $searchParam,
            $searchParam,
            $limit,
            $offset
        );
    }

    $stmt->execute();

    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $users;
}
/**
 * Count total users (for pagination)
 */
// function countUsers()
// {
//     global $conn;

//     $sql = "SELECT COUNT(*) as total FROM users";
//     $result = mysqli_query($conn, $sql);
//     $row = mysqli_fetch_assoc($result);

//     return $row['total'];
// }
function countUsers($userType, $search = '')
{
    global $conn;

    $searchParam = "%{$search}%";

    if ($userType !== 'all') {

        $sql = "
            SELECT COUNT(*) as total
            FROM users
            WHERE role = ?
            AND (
                username LIKE ?
                OR email LIKE ?
                OR phone1 LIKE ?
            )
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssss",
            $userType,
            $searchParam,
            $searchParam,
            $searchParam
        );

    } else {

        $sql = "
            SELECT COUNT(*) as total
            FROM users
            WHERE (
                username LIKE ?
                OR email LIKE ?
                OR phone1 LIKE ?
            )
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sss",
            $searchParam,
            $searchParam,
            $searchParam
        );
    }

    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['total'];
}

// function addUser($firstName, $username, $password, $email, $role, $address, $phone1, $phone2, $status)
// {
//     global $conn;

//     $stmt = $conn->prepare("
//         INSERT INTO users (firstname, username, password, email, role, address, phone1, phone2, status, is_password_changed)
//         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)
//     ");

//     $stmt->bind_param("ssssssssi", $firstName, $username, $password, $email, $role, $address, $phone1, $phone2, $status);

//     $result = $stmt->execute();
//     $stmt->close();
//     return $result;
// }

function addUser($firstName, $username, $password, $email, $role, $address, $phone1, $phone2, $status)
{
    global $conn;

    // Check if username already exists
    $checkUsername = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $checkUsername->bind_param("s", $username);
    $checkUsername->execute();
    $usernameResult = $checkUsername->get_result();

    if ($usernameResult->num_rows > 0) {
        $_SESSION['alert_message'] = "Username already exists.";
        $_SESSION['alert_type'] = "danger";
        $checkUsername->close();
        return false;
    }
    $checkUsername->close();

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $emailResult = $checkEmail->get_result();

    if ($emailResult->num_rows > 0) {
        $_SESSION['7'] = "Email address already exists.";
        $_SESSION['alert_type'] = "danger";
        $checkEmail->close();
        return false;
    }
    $checkEmail->close();

    // Insert user
    $stmt = $conn->prepare("
        INSERT INTO users (firstname, username, password, email, role, address, phone1, phone2, status, is_password_changed)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)
    ");

    $stmt->bind_param(
        "ssssssssi",
        $firstName,
        $username,
        $password,
        $email,
        $role,
        $address,
        $phone1,
        $phone2,
        $status
    );

    $result = $stmt->execute();
    $stmt->close();

    if ($result) {
        $_SESSION['success_message'] = "✅ User added successfully.";
        $_SESSION['alert_type'] = "success";
    } else {
        $_SESSION['alert_message'] = " ❌ Failed to add user.";
        $_SESSION['alert_type'] = "danger";
    }

    return $result;
}

function getUserById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user;
}

// function updateUser($id, $firstName, $username, $email, $role, $address, $phone1, $phone2)
// {
//     global $conn;
//     $stmt = $conn->prepare("UPDATE users SET firstname=?, username=?, email=?, role=? , address=?, phone1=?, phone2=? WHERE id=?");
//     $stmt->bind_param("sssssssi", $firstName, $username, $email, $role, $address, $phone1, $phone2, $id);
//     $result = $stmt->execute();
//     $stmt->close();
//     return $result;
// }

function updateUser($id, $firstName, $username, $email, $role, $address, $phone1, $phone2)
{
    global $conn;

    // Check if email already exists for another user
    $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $checkEmail->bind_param("si", $email, $id);
    $checkEmail->execute();
    $emailResult = $checkEmail->get_result();

    if ($emailResult->num_rows > 0) {
        $_SESSION['alert_message'] = "Email address already exists.";
        $_SESSION['alert_type'] = "danger";
        $checkEmail->close();
        return false;
    }
    $checkEmail->close();

    // Check if username already exists for another user
    $checkUsername = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $checkUsername->bind_param("si", $username, $id);
    $checkUsername->execute();
    $usernameResult = $checkUsername->get_result();

    if ($usernameResult->num_rows > 0) {
        $_SESSION['alert_message'] = "Username already exists.";
        $_SESSION['alert_type'] = "danger";
        $checkUsername->close();
        return false;
    }
    $checkUsername->close();

    // Update user
    $stmt = $conn->prepare("UPDATE users SET firstname=?, username=?, email=?, role=?, address=?, phone1=?, phone2=? WHERE id=?");
    $stmt->bind_param("sssssssi", $firstName, $username, $email, $role, $address, $phone1, $phone2, $id);

    $result = $stmt->execute();
    $stmt->close();

    if ($result) {
        $_SESSION['success_message'] = "✅ User updated successfully.";
        $_SESSION['alert_type'] = "success";
    } else {
        $_SESSION['alert_message'] = "❌ Failed to update user.";
        $_SESSION['alert_type'] = "danger";
    }

    return $result;
}

function updateUserStatus($id, $status)
{
    global $conn;
    $sql = "UPDATE users SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $status, $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
