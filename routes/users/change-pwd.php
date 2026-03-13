<?php
    $method = $context['method'];
    if ($method === 'GET') {
        renderView('change-pwd', ['title' => 'Change Password']);
    } else if ($method === 'POST') {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: /users/login');
            exit();
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = 'รหัสผ่านใหม่และยืนยันรหัสผ่านไม่ตรงกัน';
            header('Location: /users/change-pwd');
            exit();
        }

        $hashedPassword = getHashedPasswordById($userId);
        if (!$hashedPassword || !password_verify($currentPassword, $hashedPassword)) {
            $_SESSION['error'] = 'รหัสผ่านปัจจุบันไม่ถูกต้อง';
            header('Location: /users/change-pwd');
            exit();
        }

        if(password_verify($newPassword, $hashedPassword)) {
            $_SESSION['error'] = 'รหัสผ่านใหม่ต้องแตกต่างจากรหัสผ่านปัจจุบัน';
            header('Location: /users/change-pwd');
            exit();
        }

        if(strlen($newPassword) < 6) {
            $_SESSION['error'] = 'รหัสผ่านใหม่ต้องมีความยาวอย่างน้อย 6 ตัวอักษร';
            header('Location: /users/change-pwd');
            exit();
        }

        updatePassword($userId, password_hash($newPassword, PASSWORD_DEFAULT));


        $_SESSION['success'] = 'เปลี่ยนรหัสผ่านสำเร็จ';
        header('Location: /users/profile');
        exit();
    } else {
        notFound();
    }