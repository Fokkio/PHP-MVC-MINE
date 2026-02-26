<html>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 flex flex-col min-h-screen font-sans text-gray-800">
    <nav class="bg-white border-b border-gray-200 px-6 sm:px-8 py-4 flex flex-col sm:flex-row items-center gap-4 sm:gap-6 shadow-sm">
        <h2 class="text-lg font-bold text-gray-800 sm:mr-auto"><?= htmlspecialchars($data['title'] ?? '') ?></h2>
        <?php
        $uri = strtolower(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        if (isset($_SESSION['timestamp'])): ?>
            <div class="w-full sm:w-auto text-center">
                <a href="/events" class="text-gray-600 hover:text-blue-600 font-medium transition-colors block sm:inline-block px-2">Explore</a>
            </div>
            <div class="w-full sm:w-auto text-center">
                <a href="/my-register" class="text-gray-600 hover:text-blue-600 font-medium transition-colors block sm:inline-block px-2">My Register</a>
            </div>
            <div class="w-full sm:w-auto text-center">
                <a href="/my-events" class="text-gray-600 hover:text-blue-600 font-medium transition-colors block sm:inline-block px-2">My Events</a>
            </div>
            <div class="w-full sm:w-auto text-center">
                <a href="/profile" class="text-gray-600 hover:text-blue-600 font-medium transition-colors block sm:inline-block px-2">Profile</a>
            </div>
        <?php endif; ?>
    </nav>
    <main class="flex-grow flex flex-col items-center justify-center px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">เข้าสู่ระบบ</h1>

        <form action="/users/login" method="post" class="bg-white p-8 rounded-xl shadow-lg w-full max-w-sm border border-gray-100">
            <label for="username" class="text-sm font-semibold text-gray-700">อีเมลผู้ใช้</label><br>
            <input type="text" name="email" id="email" class="w-full mt-1 mb-4 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" /><br>

            <label for="password" class="text-sm font-semibold text-gray-700">รหัสผ่าน</label><br>
            <input type="password" name="password" id="password" class="w-full mt-1 mb-6 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" /><br>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg transition-colors shadow-sm">เข้าสู่ระบบ</button>

            <div class="mt-5 text-center">
                <span class="text-sm text-gray-600">หากยังไม่มีบัญชีผู้ใช้ <a href="/users/register" class="text-blue-600 hover:text-blue-800 font-semibold hover:underline transition-colors">สมัครสมาชิก</a></span>
            </div>

            <div class="mt-3 text-center">
                <?php if (isset($_SESSION['error'])): ?>
                    <span class="text-sm text-red-500 font-medium">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                    </span>
                    <?php unset($_SESSION['error']); 
                    ?>
                <?php endif; ?>
            </div>
        </form>
    </main>
    <?php include 'footer.php' ?>
</body>

</html>