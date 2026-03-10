<body class="flex flex-col min-h-screen bg-gray-50 text-gray-800">
    <header>
        <script src="https://cdn.tailwindcss.com"></script>
    </header>
    <nav class="bg-white border-b border-gray-200 px-6 sm:px-8 py-4 flex flex-col sm:flex-row items-center gap-4 sm:gap-6 shadow-sm">
        <h2 class="text-lg font-bold text-gray-800 sm:mr-auto"><?= htmlspecialchars($data['title'] ?? 'Event System') ?></h2>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="w-full sm:w-auto text-center">
                <a href="/events" class="text-gray-600 hover:text-blue-600 font-medium transition-colors block sm:inline-block px-2">Explore</a>
            </div>
            <div class="w-full sm:w-auto text-center">
                <a href="/join_event/my-registers" class="text-gray-600 hover:text-blue-600 font-medium transition-colors block sm:inline-block px-2">My Register</a>
            </div>
            <div class="w-full sm:w-auto text-center">
                <a href="/events/my-event" class="text-gray-600 hover:text-blue-600 font-medium transition-colors block sm:inline-block px-2">My Events</a>
            </div>
            <div class="w-full sm:w-auto text-center">
                <a href="/users/profile" class="text-gray-600 hover:text-blue-600 font-medium transition-colors block sm:inline-block px-2">Profile</a>
            </div>
            <div class="w-full sm:w-auto text-center mt-2 sm:mt-0">
                <a href="/users/logout" class="text-red-500 hover:text-white hover:bg-red-500 font-medium transition-colors block sm:inline-block px-4 py-1.5 rounded-lg border border-red-500 sm:ml-2 no-underline">ออกจากระบบ</a>
            </div>

        <?php else: ?>
            <div class="w-full sm:w-auto text-center">
                <a href="/events" class="text-gray-600 hover:text-blue-600 font-medium transition-colors block sm:inline-block px-2">Explore</a>
            </div>
            <div class="w-full sm:w-auto text-center">
                <a href="/users/login" class="text-blue-600 hover:text-blue-700 font-medium transition-colors block sm:inline-block px-2">เข้าสู่ระบบ</a>
            </div>
            <div class="w-full sm:w-auto text-center mt-2 sm:mt-0">
                <a href="/users/register" class="bg-blue-600 hover:bg-blue-700 text-white font-medium transition-colors block sm:inline-block px-4 py-1.5 rounded-lg border border-blue-600 sm:ml-2 no-underline">สมัครสมาชิก</a>
            </div>
        <?php endif; ?>
    </nav>