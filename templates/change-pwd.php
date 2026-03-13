<?php include 'header.php'; ?>

<main class="flex-grow py-12 px-4 sm:px-8 max-w-7xl mx-auto w-full flex justify-center items-start">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 w-full max-w-md overflow-hidden">

        <!-- Header -->
        <div class="px-8 py-6 border-b border-gray-100">
            <h1 class="text-2xl font-extrabold text-gray-900">🔒 เปลี่ยนรหัสผ่าน</h1>
            <p class="text-sm text-gray-400 mt-1">กรอกรหัสผ่านเดิมและรหัสผ่านใหม่ของคุณ</p>
        </div>

        <div class="px-8 py-6">

            <!-- Alert -->
            <?php if(isset($_SESSION['error'])): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm font-medium rounded-lg px-4 py-3 mb-6">
                ❌ <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['success'])): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm font-medium rounded-lg px-4 py-3 mb-6">
                ✅ <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST" action="/users/change-pwd" class="space-y-5">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">รหัสผ่านปัจจุบัน</label>
                    <input type="password" name="current_password" required
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
                           placeholder="••••••••">
                </div>

                <div class="border-t border-gray-100 pt-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">รหัสผ่านใหม่</label>
                    <input type="password" name="new_password" required
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
                           placeholder="••••••••">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">ยืนยันรหัสผ่านใหม่</label>
                    <input type="password" name="confirm_password" required
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
                           placeholder="••••••••">
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition-colors shadow-sm text-sm">
                        บันทึกรหัสผ่านใหม่
                    </button>
                </div>

            </form>
        </div>

        <!-- Footer -->
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100">
            <a href="/users/profile" class="text-sm text-gray-400 hover:text-gray-600 transition-colors no-underline">⬅ กลับไปหน้าโปรไฟล์</a>
        </div>

    </div>
</main>

<?php include 'footer.php'; ?>