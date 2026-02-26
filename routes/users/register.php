<?php

declare(strict_types=1);
$method = $context['method']; // รับค่า method

// 1. เพิ่มส่วนนี้: ถ้าเป็นการเปิดหน้าเว็บ (GET) ให้แสดงฟอร์ม
if ($method === 'GET') {
    renderView('register', ['title' => 'Register']); 
    return; // หรือ exit; เพื่อจบการทำงาน
}
if ($context['method'] === 'POST') {
    // 1. รับค่าและล้างหัวท้าย
    $fname = trim($_POST['first_name'] ?? '');
    $lname = trim($_POST['last_name'] ?? '');

    // 2. เช็ค Pattern (ใช้ !preg_match เพื่อดูว่า "ถ้าไม่ตรงตามนี้")
    // เพิ่มตัว u (Unicode modifier) ไว้ข้างหลังเพื่อให้รองรับภาษาไทย
    // [a-zA-Z\x{0e01}-\x{0e5b}]+ หมายถึง อังกฤษ หรือ ไทย กี่ตัวก็ได้ แต่ต้องไม่มีช่องว่าง
    $thaiEngPattern = '/^[a-zA-Z\x{0e01}-\x{0e5b}]+$/u';

    $isFnameInvalid = !preg_match($thaiEngPattern, $fname);
    $isLnameInvalid = !preg_match($thaiEngPattern, $lname);

    // 3. รวมเงื่อนไข: ถ้าว่าง OR ชื่อผิด OR นามสกุลผิด
    if ($fname === '' || $lname === '' || $isFnameInvalid || $isLnameInvalid) {
        $_SESSION['error'] = "ชื่อและนามสกุลต้องเป็นภาษาอังกฤษ (a-z) หรือภาษาไทย เท่านั้น และห้ามมีช่องว่าง";
        header('Location: /users/register');
        exit;
    }
    $name = $fname . ' ' . $lname;
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $birthday = $_POST['birthday'] ?? '';

    if (!$birthday) {
        $_SESSION['error'] = "กรุณากรอกวันเกิด";
        header('Location: /users/register');
        exit;
    }

    $phone = trim($_POST['phone'] ?? '');
    $genderInput = $_POST['gender'] ?? '';
    if (!$genderInput) {
        $_SESSION['error'] = "กรุณาเลือกเพศ";
        header('Location: /users/register');
        exit;
    }

    $gender = Gender::from($genderInput);
    if($birthday > date('Y-m-d')) {
        $_SESSION['error'] = "วันเกิดไม่ถูกต้อง";
        header('Location: /users/register');
        exit;
    }

    if($phone && !preg_match('/^\d{10}$/', $phone)) {
        $_SESSION['error'] = "เบอร์โทรศัพท์ไม่ถูกต้อง (ต้องเป็นตัวเลข 10 หลัก)";
        header('Location: /users/register');
        exit;
    }

    if(isEmailRegistered($email)) {
        $_SESSION['error'] = "อีเมลนี้ถูกใช้งานแล้ว";
        header('Location: /users/register');
        exit;
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = "รหัสผ่านต้องมีความยาวอย่างน้อย 6 ตัวอักษร";
        header('Location: /users/register');
        exit;
    }

      createUser($name, $email, $password, $birthday, $phone, $gender);
    header('Location: /users/login');
} else {
    notFound();
}
