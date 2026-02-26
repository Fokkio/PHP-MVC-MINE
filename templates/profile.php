<?php include 'header.php'; ?>

<main class="flex-grow py-12 px-4 sm:px-8 flex flex-col items-center">
    <div class="bg-white p-8 sm:p-10 rounded-xl shadow-lg w-full max-w-2xl border border-gray-100">
        
        <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6 border-b border-gray-200 pb-4">ข้อมูลผู้ใช้</h3>
        
        <p class="flex flex-col sm:flex-row sm:items-center text-gray-600 text-base sm:text-lg bg-gray-50 p-4 rounded-lg border border-gray-100 mb-4 transition-colors hover:bg-white hover:border-blue-200">
            <strong class="text-gray-900 font-semibold w-full sm:w-32 mb-1 sm:mb-0">ชื่อ:</strong> <?= htmlspecialchars($user['name'] ?? '') ?>
        </p>
        
        <p class="flex flex-col sm:flex-row sm:items-center text-gray-600 text-base sm:text-lg bg-gray-50 p-4 rounded-lg border border-gray-100 mb-4 transition-colors hover:bg-white hover:border-blue-200">
            <strong class="text-gray-900 font-semibold w-full sm:w-32 mb-1 sm:mb-0">อีเมล:</strong> <?= htmlspecialchars($user['email'] ?? '') ?>
        </p>
        
        <p class="flex flex-col sm:flex-row sm:items-center text-gray-600 text-base sm:text-lg bg-gray-50 p-4 rounded-lg border border-gray-100 mb-4 transition-colors hover:bg-white hover:border-blue-200">
            <strong class="text-gray-900 font-semibold w-full sm:w-32 mb-1 sm:mb-0">วันเกิด:</strong> <?= htmlspecialchars($user['birthday'] ?? '') ?>
        </p>
        
        <p class="flex flex-col sm:flex-row sm:items-center text-gray-600 text-base sm:text-lg bg-gray-50 p-4 rounded-lg border border-gray-100 mb-4 transition-colors hover:bg-white hover:border-blue-200">
            <strong class="text-gray-900 font-semibold w-full sm:w-32 mb-1 sm:mb-0">เบอร์โทร:</strong> <?= htmlspecialchars($user['phone'] ?? '') ?>
        </p>
        
        <p class="flex flex-col sm:flex-row sm:items-center text-gray-600 text-base sm:text-lg bg-gray-50 p-4 rounded-lg border border-gray-100 mb-0 transition-colors hover:bg-white hover:border-blue-200">
            <strong class="text-gray-900 font-semibold w-full sm:w-32 mb-1 sm:mb-0">เพศ:</strong> <?= htmlspecialchars($user['gender'] ?? '') ?>
        </p>

    </div>
</main>

<?php include 'footer.php'; ?>