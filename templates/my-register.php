    <?php include 'header.php'; ?>

    <main class="flex-grow py-12 px-4 sm:px-8 max-w-7xl mx-auto w-full">
        <h3 class="text-2xl font-bold text-gray-800 mb-8 px-4 border-l-4 border-blue-600">กิจกรรมที่ฉันลงทะเบียน</h3>

        <?php if (empty($joinEvents)) : ?>
            <div class="no-data bg-white p-12 rounded-xl shadow-sm border border-gray-200 text-center mx-auto max-w-2xl mt-8">
                <p class="text-gray-500 text-lg font-medium">คุณยังไม่ได้ลงทะเบียนเข้าร่วมกิจกรรมใดๆ ในขณะนี้</p>
                <a href="/events" class="inline-block mt-4 text-blue-600 hover:underline">สำรวจกิจกรรมที่น่าสนใจ</a>
            </div>
        <?php else : ?>
            <div class="event-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($joinEvents as $joinEvent) : ?>
                    <div class="event-card bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md hover:border-blue-400 transition-all flex flex-col h-full relative overflow-hidden">

                        <div class="h-48 w-full bg-gray-100 overflow-hidden relative border-b border-gray-100">
                            <?php
                            $images = getImagesByEventId($joinEvent['event_id']);
                            if (!empty($images)) :
                            ?>
                                <img src="<?= htmlspecialchars($images[0]) ?>" alt="Event Image" class="w-full h-full object-cover">
                            <?php else : ?>
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                    <span class="text-3xl mb-1">🖼️</span>
                                    <span class="text-xs">ไม่มีรูปภาพ</span>
                                </div>
                            <?php endif; ?>

                            <div class="absolute top-3 right-3">
                                <?php
                                $status = $joinEvent['join_status'] ?? 'pending';
                                if ($status === 'approved') {
                                    $class = 'bg-green-500 text-white border-green-600';
                                } elseif ($status === 'rejected') {
                                    $class = 'bg-red-500 text-white border-red-600';
                                } else {
                                    $class = 'bg-yellow-400 text-gray-900 border-yellow-500';
                                }
                                ?>
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold shadow-md border uppercase tracking-wider <?= $class ?>">
                                    <?= htmlspecialchars($status) ?>
                                </span>
                            </div>
                        </div>

                        <div class="p-6 flex flex-col flex-grow">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 pb-3 border-b border-gray-100">
                                <?= htmlspecialchars($joinEvent['event_name'] ?? 'ไม่มีชื่อกิจกรรม') ?>
                            </h3>

                            <p class="text-gray-600 text-sm mb-5 leading-relaxed flex-grow">
                                <strong class="text-gray-800 font-semibold block mb-1">รายละเอียด:</strong>
                                <?= htmlspecialchars($joinEvent['event_description'] ?? 'ไม่มีรายละเอียด') ?>
                            </p>

                            <div class="space-y-1 mb-6">
                                <p class="text-gray-700 text-sm m-0 bg-blue-50 px-4 py-2 rounded-t-lg border-b border-blue-100 flex items-center">
                                    <strong class="text-blue-800 font-semibold w-20">เวลาเริ่ม:</strong>
                                    <?= htmlspecialchars($joinEvent['event_start'] ?? '-') ?>
                                </p>

                                <p class="text-gray-700 text-sm m-0 bg-blue-50 px-4 py-2 rounded-b-lg flex items-center">
                                    <strong class="text-blue-800 font-semibold w-20">เวลาจบ:</strong>
                                    <?= htmlspecialchars($joinEvent['event_end'] ?? '-') ?>
                                </p>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-2 mt-auto">
                                <a href="/events/<?= $joinEvent['event_id'] ?? '' ?>/detail"
                                    class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition-colors shadow-sm no-underline text-sm">
                                    รายละเอียด
                                </a>

                                <?php
                                // 1. ตั้ง Timezone เป็นไทย และดึงเวลาปัจจุบัน
                                date_default_timezone_set('Asia/Bangkok');
                                $nowTimestamp = time();
                                $startTimestamp = strtotime($joinEvent['event_start']);
                                $endTimestamp = strtotime($joinEvent['event_end']);

                                // 2. สถานะ
                                $isApproved = ($joinEvent['join_status'] ?? '') === 'approved';
                                $isCheckedIn = !empty($joinEvent['checkin_status']);
                                ?>

                                <?php if ($isCheckedIn): ?>
                                    <span class="flex-1 text-center bg-green-400 text-white font-semibold py-2.5 rounded-lg shadow-sm cursor-not-allowed select-none text-sm">
                                        ✅ เช็คอินแล้ว
                                    </span>

                                <?php else: ?>
                                    <?php if ($isApproved): ?>
                                        <?php if ($nowTimestamp < $startTimestamp): ?>
                                            <span class="flex-1 text-center bg-gray-300 text-gray-500 font-semibold py-2.5 rounded-lg shadow-sm cursor-not-allowed text-sm">
                                                ⏳ ยังไม่ถึงเวลา
                                            </span>
                                        <?php else: ?>
                                            <a href="/events/<?= $joinEvent['event_id'] ?? '' ?>/generate-otp"
                                                class="flex-1 text-center bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-lg transition-colors shadow-sm no-underline text-sm">
                                                แสดง OTP
                                            </a>
                                        <?php endif; ?>
                                    <?php elseif($nowTimestamp <= $endTimestamp): ?>
                                        <span class="flex-1 text-center bg-yellow-400 text-yellow-800 font-semibold py-2.5 rounded-lg shadow-sm cursor-not-allowed select-none text-sm">
                                            ⏳ รอการอนุมัติ
                                        </span>
                                    <?php elseif ($nowTimestamp > $endTimestamp): ?>
                                            <span class="flex-1 text-center bg-gray-300 text-gray-500 font-semibold py-2.5 rounded-lg shadow-sm cursor-not-allowed text-sm">
                                                ❌ หมดเวลา
                                            </span>
                                    <?php endif; ?>
                                    

                                    <?php if ($nowTimestamp <= $endTimestamp): ?>
                                        <a href="/join_event/<?= $joinEvent['join_event_id'] ?? '' ?>/leave"
                                            onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการยกเลิกการเข้าร่วมกิจกรรมนี้?');"
                                            class="flex-1 text-center bg-white hover:bg-red-50 text-red-500 border border-red-200 font-semibold py-2.5 rounded-lg transition-colors no-underline text-sm">
                                            ยกเลิก
                                        </a>
                                    <?php endif; ?>

                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php include 'footer.php'; ?>