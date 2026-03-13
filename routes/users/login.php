<?php
    $method = $context['method'];
    if ($method === 'GET') {
        renderView('login', ['title' => 'Login']);
    } else if ($method === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = checkLogin($email, $password);

        if ($user) {
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['user_id'];           
            $_SESSION['username'] = $user['name'];
            $_SESSION['timestamp'] = time();

            header('Location: /events/index');
            exit();
        } else {
            $_SESSION['error'] = 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'; 

            header('Location: /users/login');
            exit();
        }
    } else {
        notFound();
    }

