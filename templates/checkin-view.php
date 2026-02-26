<?php include 'header.php'; ?>
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-gray-100">
        <div>
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100">
                <span class="text-3xl">🔐</span>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                ยืนยัน Check-in
            </h2>
            <p class="mt-2 text-center text-sm text-gray-500">
                กรุณากรอกรหัส OTP 6 หลักที่ผู้เข้าร่วมได้รับ
            </p>
            <p class="text-center text-xs font-semibold text-blue-600 mt-1">
                คุณกำลังเช็คอินให้: <?= htmlspecialchars($participant['participant_name'] ?? 'ผู้เข้าร่วม') ?>
            </p>
        </div>

        <form class="mt-8 space-y-6" action="/events/<?= $joinEventId ?>/verify-checkin" method="POST">
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="otp" class="sr-only">รหัส OTP</label>
                    <input id="otp" name="otp" type="text" required autocomplete="off"
                           class="appearance-none relative block w-full px-4 py-4 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center tracking-[0.5em] font-bold text-2xl transition-all shadow-sm" 
                           placeholder="XXXXXX" maxlength="6">
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-md">
                    ✅ ยืนยันรหัส OTP
                </button>
            </div>
        </form>

        <div class="text-center mt-6">
            <a href="/events/<?= htmlspecialchars($eventId) ?>/participants" class="font-medium text-gray-500 hover:text-gray-700 transition-colors text-sm">
                ⬅ ยกเลิกและกลับหน้า Participants
            </a>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>