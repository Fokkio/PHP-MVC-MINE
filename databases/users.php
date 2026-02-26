<?php
require_once INCLUDES_DIR . '/Enum.php';
function getUserById($userId)
{
    global $connection;
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function checkLogin($email, $password)
{
    global $connection;
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return false;
}

function isEmailRegistered($email)
{
    global $connection;
    $sql = "SELECT user_id FROM users WHERE email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

function createUser(
    string $name,
    string $email,
    string $password,
    string $birthday,
    string $phone,
    Gender $gender
) {
    global $connection;
    $sql = "INSERT INTO users (name, email, password, birthday, phone, gender) VALUES (?, ?, ?, ?, ?, ?)";
    if($birthday > date('Y-m-d')){
        throw new Exception("วันเกิดไม่สามารถเป็นอนาคตได้");
    }
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        throw new Exception("DB prepare failed: " . $connection->error);
    }
    $genderValue = $gender->value; // เก็บใส่ตัวแปรก่อน
    $stmt->bind_param(
        "ssssss",
        $name,
        $email,
        password_hash($password, PASSWORD_DEFAULT),
        $birthday,
        $phone,
        $genderValue
    );
    return $stmt->execute();
}

function getUserProfileDetails($userId)
{
    global $connection;
    $sql = "SELECT name, email, birthday, phone, gender FROM users WHERE user_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row) {
        return $row;
    }
    return null;
}

function getUsernameById($userId)
{
    global $connection;
    $sql = "SELECT name FROM users WHERE user_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['name'] ?? null;
}

function updatePassword(int $userId, string $newPassword)
{
    global $connection;
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET password = ? WHERE user_id = ?";
    $stmt = $connection->prepare($sql);

    $stmt->bind_param("si", $hashedPassword, $userId);
    return $stmt->execute();
}
