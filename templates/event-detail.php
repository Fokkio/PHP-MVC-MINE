<?php include 'header.php'; ?>

<main class="flex-grow py-10 px-4 flex flex-col items-center bg-gray-100">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 w-full max-w-2xl overflow-hidden order-gray-100">

        <?php $images = getImagesByEventId($event['id']); if (!empty($images)): ?>
        <img src="<?= htmlspecialchars($images[0]) ?>" alt="<?= htmlspecialchars($event['name']) ?>"
             class="w-full h-64 object-cover">
        <?php endif; ?>

        <div class="p-8">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-6"><?= htmlspecialchars($event['name']) ?></h1>

            <?php if(isset($maxParticipants, $currentParticipants, $pendingParticipants)):
                $pct     = $maxParticipants > 0 ? min($currentParticipants / $maxParticipants * 100, 100) : 0;
                $pendPct = $maxParticipants > 0 ? min($pendingParticipants / $maxParticipants * 100, 100 - $pct) : 0;
                $left    = max(0, $maxParticipants - $currentParticipants);
                $isFull  = $currentParticipants >= $maxParticipants;
                $isAlmost= $pct > 80 && !$isFull;
                $clr     = $isFull ? 'bg-red-500' : ($isAlmost ? 'bg-orange-400' : 'bg-emerald-500');
                $txt     = $isFull ? 'text-red-600' : ($isAlmost ? 'text-orange-500' : 'text-emerald-600');
                $badge   = $isFull ? 'bg-red-50 text-red-600' : ($isAlmost ? 'bg-orange-50 text-orange-500' : 'bg-emerald-50 text-emerald-600');
                $label   = $isFull ? 'เต็มแล้ว' : ($isAlmost ? 'ใกล้เต็ม' : 'เปิดรับสมัคร');
            ?>
            <div class="mb-8 rounded-xl border border-gray-100 overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full <?= $isFull ? 'bg-red-500' : 'bg-emerald-500 animate-pulse' ?>"></span>
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">สถานะกิจกรรม</span>
                    </div>
                    <span class="text-xs font-bold px-3 py-1 rounded-full <?= $badge ?>"><?= $label ?></span>
                </div>

                <!-- Progress bar -->
                <div class="px-4 pt-4 pb-3">
                    <div class="flex justify-between items-baseline mb-2">
                        <span class="text-xs text-gray-400">ความจุที่นั่ง</span>
                        <div class="flex items-baseline gap-0.5">
                            <span class="text-xl font-black <?= $txt ?>"><?= number_format($currentParticipants) ?></span>
                            <span class="text-gray-300 mx-1">/</span>
                            <span class="text-base font-semibold text-gray-400"><?= number_format($maxParticipants) ?></span>
                        </div>
                    </div>
                    <div class="relative w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                        <div class="<?= $clr ?> absolute left-0 top-0 h-full transition-all duration-700"
                             style="width:<?= $pct ?>%; border-radius: <?= $pendPct > 0 ? '9999px 0 0 9999px' : '9999px' ?>"></div>
                        <div class="absolute top-0 h-full bg-amber-300 opacity-80 transition-all duration-700"
                             style="left:<?= $pct ?>%; width:<?= $pendPct ?>%; border-radius: 0 9999px 9999px 0"></div>
                    </div>
                    <div class="flex gap-4 mt-2">
                        <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-sm <?= $clr ?>"></span><span class="text-xs text-gray-400">ยืนยันแล้ว</span></div>
                        <?php if($pendingParticipants > 0): ?>
                        <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-sm bg-amber-300"></span><span class="text-xs text-gray-400">รออนุมัติ</span></div>
                        <?php endif; ?>
                        <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-sm bg-gray-200"></span><span class="text-xs text-gray-400">ว่าง</span></div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-4 divide-x divide-gray-100 border-t border-gray-100">
                    <div class="px-3 py-3 text-center">
                        <p class="text-base font-black text-gray-700"><?= number_format($maxParticipants) ?></p>
                        <p class="text-xs text-gray-400 mt-0.5">ทั้งหมด</p>
                    </div>
                    <div class="px-3 py-3 text-center">
                        <p class="text-base font-black <?= $txt ?>"><?= number_format($currentParticipants) ?></p>
                        <p class="text-xs text-gray-400 mt-0.5">ยืนยันแล้ว</p>
                    </div>
                    <div class="px-3 py-3 text-center">
                        <p class="text-base font-black text-amber-500"><?= number_format($pendingParticipants) ?></p>
                        <p class="text-xs text-gray-400 mt-0.5">รออนุมัติ</p>
                    </div>
                    <div class="px-3 py-3 text-center">
                        <p class="text-base font-black <?= $isFull ? 'text-red-500' : 'text-blue-500' ?>"><?= number_format($left) ?></p>
                        <p class="text-xs text-gray-400 mt-0.5">ที่นั่งว่าง</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <p class="text-gray-600 leading-relaxed mb-6 text-sm"><?= nl2br(htmlspecialchars($event['description'])) ?></p>

            <div class="flex gap-3 text-sm text-blue-800 bg-blue-50 border border-blue-100 rounded-xl px-5 py-4 mb-6">
                <div><span class="font-semibold">📅 เริ่ม</span> <?= htmlspecialchars($event['event_start']) ?></div>
                <div class="text-blue-200">|</div>
                <div><span class="font-semibold">🏁 สิ้นสุด</span> <?= htmlspecialchars($event['event_end']) ?></div>
            </div>

            <?php if(isset($alert)): ?>
            <div class="bg-green-50 border border-green-200 rounded-xl px-5 py-3 mb-6 text-center text-green-700 text-sm font-medium"><?= htmlspecialchars($alert) ?></div>
            <?php endif; ?>

            <div class="flex flex-wrap gap-2 pt-6 border-t border-gray-100">
                <?php if($userId && $event && $userId == $event['creator_id']): ?>
                <a href="/events/<?= $event['id'] ?>/edit"         class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-bold py-2 px-4 rounded-lg transition-colors no-underline">✏ Edit</a>
                <a href="/events/<?= $event['id'] ?>/participants"  class="bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-bold py-2 px-4 rounded-lg transition-colors no-underline">👥 Participants</a>
                <a href="/events/<?= $event['id'] ?>/statistics"    class="bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold py-2 px-4 rounded-lg transition-colors no-underline">📊 Statistics</a>
                <a href="/events/<?= $event['id'] ?>/delete-event"  onclick="return confirm('คุณแน่ใจหรือไม่?')"
                   class="bg-red-500 hover:bg-red-600 text-white text-sm font-bold py-2 px-4 rounded-lg transition-colors no-underline">🗑 Delete</a>
                <?php endif; ?>
                <a href="/events" class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-bold py-2 px-4 rounded-lg border border-gray-200 transition-colors no-underline">⬅ Back</a>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>