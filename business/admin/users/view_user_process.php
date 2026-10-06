<?php
require_once __DIR__ . '/../../../data/admin/user_modal.php';



function getUserForView($id) {
    if (!$id || !is_numeric($id)) {
        return null;
    }

    $user = getUserById($id);
    if (!$user) return null;

    // Convert flag to readable text and color
    if ($user['is_password_changed'] == 1) {
        $user['password_changed_text'] = 'Yes';
        $user['password_changed_class'] = 'bg-success text-white';
    } else {
        $user['password_changed_text'] = 'No';
        $user['password_changed_class'] = 'bg-danger text-white';
    }

    return $user;
}




?>