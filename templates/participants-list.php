<?php include 'header.php'; ?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-4 text-sm font-semibold text-gray-700">ลำดับ</th>
                <th class="px-6 py-4 text-sm font-semibold text-gray-700">ชื่อผู้เข้าร่วม</th>
                <th class="px-6 py-4 text-sm font-semibold text-gray-700">สถานะการลงทะเบียน</th>
                <th class="px-6 py-4 text-sm font-semibold text-gray-700">การเช็คอิน</th>
                <th class="px-6 py-4 text-sm font-semibold text-gray-700 text-center">จัดการ</th>
                <th class="px-6 py-4 text-sm font-semibold text-gray-700 text-center">ตรวจสอบ</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php foreach ($participants as $index => $user): ?>
                <tr class="hover:bg-gray-50 transition-colors relative hover:z-20">
                    <td class="px-6 py-4 text-sm text-gray-500"><?= $index + 1 ?></td>
                    <td class="px-6 py-4 font-medium text-gray-900"><?= htmlspecialchars($user['participant_name']) ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold shadow-sm 
                            <?= $user['join_status'] === 'approved' ? 'bg-green-100 text-green-700 border border-green-200' : ($user['join_status'] === 'rejected' ? 'bg-red-100 text-red-700 border border-red-200' :
                                    'bg-yellow-100 text-yellow-700 border border-yellow-200') ?>">
                            <?= htmlspecialchars(ucfirst($user['join_status'] ?? 'pending')) ?>
                        </span>
                    </td>

                    <td class="px-6 py-4 text-sm">
                        <?php if ($user['checkin_status']): ?>
                            <span class="text-green-600 font-medium">✅ เช็คอินแล้ว</span>
                        <?php else: ?>
                            <span class="text-gray-400">ยังไม่เช็คอิน</span>
                        <?php endif; ?>
                    </td>

                    <td class="px-6 py-4 text-center relative">
                        <div class="inline-block text-left">
                            <button
                                onclick="toggleDropdown(event, <?= $user['join_event_id'] ?>)"
                                <?= $user['checkin_status'] ? 'disabled' : '' ?>
                                class="dropdown-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all border shadow-sm
                                <?= $user['join_status'] === 'approved' ? 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100' : ($user['join_status'] === 'rejected' ? 'bg-red-50 text-red-700 border-red-200 hover:bg-red-100' :
                                        'bg-gray-100 hover:bg-gray-200 text-gray-700 border-gray-300') ?>">
                                <?= htmlspecialchars(ucfirst($user['join_status'] ?? 'pending')) ?> ▼
                            </button>

                            <div id="dropdown-<?= $user['join_event_id'] ?>"
                                class="dropdown-menu hidden absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-xl z-50 overflow-hidden origin-top-right">

                                <?php if (($user['join_status'] ?? '') !== 'approved'): ?>
                                    <a href="/join_event/<?= $user['join_event_id'] ?>/approve?event_id=<?= $eventId ?>"
                                        onclick="return confirm('ยืนยันการอนุมัติ?');"
                                        class="block px-4 py-2.5 text-xs text-green-600 hover:bg-green-50 font-bold no-underline border-b border-gray-50">
                                        ✅ Approve
                                    </a>
                                <?php endif; ?>

                                <?php if (($user['join_status'] ?? '') !== 'rejected'): ?>
                                    <a href="/join_event/<?= $user['join_event_id'] ?>/reject?event_id=<?= $eventId ?>"
                                        onclick="return confirm('ยืนยันการปฏิเสธผู้เข้าร่วม?');"
                                        class="block px-4 py-2.5 text-xs text-red-600 hover:bg-red-50 font-bold no-underline border-b border-gray-50">
                                        ❌ Reject
                                    </a>
                                <?php endif; ?>

                                <?php if (($user['join_status'] ?? '') !== 'pending'): ?>
                                    <a href="/join_event/<?= $user['join_event_id'] ?>/pending?event_id=<?= $eventId ?>"
                                        onclick="return confirm('ต้องการเปลี่ยนสถานะกลับเป็นรอดำเนินการหรือไม่?');"
                                        class="block px-4 py-2.5 text-xs text-gray-600 hover:bg-gray-50 font-bold no-underline">
                                        🔄 Reset to Pending
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php if ($user['join_status'] === 'approved' && !$user['checkin_status']): ?>
                            <a href="/events/<?= $user['join_event_id'] ?>/checkin"
                                class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-1.5 px-3 rounded-lg text-xs transition-colors no-underline">
                                🔐 Check-in
                            </a>
                        <?php elseif ($user['join_status'] === 'approved' && $user['checkin_status']): ?>
                            <span class="text-gray-400 font-medium">เช็คอินแล้ว</span>
                        <?php else: ?>
                            <span class="text-gray-400">ไม่สามารถเช็คอินได้</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>

<script>
    function toggleDropdown(event, id) {
        // ป้องกันไม่ให้ event ไหลไปถึง window.onclick ทันที
        event.stopPropagation();

        const dropdown = document.getElementById('dropdown-' + id);

        // ปิด dropdown ตัวอื่นทั้งหมดก่อน
        document.querySelectorAll('.dropdown-menu').forEach(el => {
            if (el.id !== 'dropdown-' + id) {
                el.classList.add('hidden');
                el.closest('tr').classList.remove('z-30'); // คืนค่า z-index แถวอื่น
            }
        });

        // สลับสถานะตัวปัจจุบัน
        const isHidden = dropdown.classList.toggle('hidden');

        // ถ้าเปิดอยู่ ให้ยกแถวนี้ให้สูงกว่าเพื่อนเป็นพิเศษ
        if (!isHidden) {
            dropdown.closest('tr').classList.add('z-30');
        } else {
            dropdown.closest('tr').classList.remove('z-30');
        }
    }

    // คลิกที่ไหนก็ได้เพื่อปิด (ยกเว้นในเมนู)
    window.onclick = function(event) {
        if (!event.target.closest('.dropdown-menu')) {
            document.querySelectorAll('.dropdown-menu').forEach(el => {
                el.classList.add('hidden');
                el.closest('tr').classList.remove('z-30');
            });
        }
    }
</script>