<?php include 'header.php'; ?>

<main class="flex-grow py-12 px-4 sm:px-8 max-w-7xl mx-auto w-full">

    <?php if (empty($events)) : ?>
        <div class="no-data bg-white p-12 rounded-xl shadow-sm border border-gray-200 text-center mx-auto max-w-2xl mt-8">
            <p class="text-gray-500 text-lg font-medium">คุณยังไม่ได้สร้างกิจกรรมใดๆ</p>
        </div>
    <?php else : ?>
        <div class="event-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($events as $event) : ?>
                <div class="event-card bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md hover:border-blue-400 transition-all flex flex-col overflow-hidden h-full">

                    <div class="group h-48 w-full bg-gray-100 overflow-hidden border-b border-gray-100 relative">
                        <?php
                        $images = getImagesByEventId($event['id']);
                        if (!empty($images)) :
                        ?>
                            <div class="image-slider flex overflow-x-auto snap-x snap-mandatory h-full w-full scrollbar-hide" style="-ms-overflow-style: none; scrollbar-width: none;">
                                <?php foreach ($images as $img) : ?>
                                    <img src="<?= $img ?>" alt="Event Image" class="w-full h-full object-cover flex-shrink-0 snap-center">
                                <?php endforeach; ?>
                            </div>

                            <?php if (count($images) > 1): ?>
                                <button type="button" onclick="slideImage(this, 'left')"
                                    class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white p-2 rounded-full backdrop-blur-sm transition-all shadow-md z-10 opacity-0 group-hover:opacity-100 focus:opacity-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                    </svg>
                                </button>

                                <button type="button" onclick="slideImage(this, 'right')"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white p-2 rounded-full backdrop-blur-sm transition-all shadow-md z-10 opacity-0 group-hover:opacity-100 focus:opacity-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                </button>
                            <?php endif; ?>

                        <?php else : ?>
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                <span class="text-3xl mb-1">🖼️</span>
                                <span class="text-xs">No Image</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 pb-3 border-b border-gray-100">
                            <?= htmlspecialchars($event['name'] ?? 'ไม่มีชื่อกิจกรรม') ?>
                        </h3>

                        <p class="text-gray-600 text-sm mb-5 leading-relaxed flex-grow">
                            <strong class="text-gray-800 font-semibold block mb-1">รายละเอียด:</strong>
                            <?= htmlspecialchars($event['event_description'] ?? $event['description'] ?? 'ไม่มีรายละเอียด') ?>
                        </p>

                        <div class="space-y-1 mb-6">
                            <p class="text-gray-700 text-sm m-0 bg-blue-50 px-4 py-2 rounded-t-lg border-b border-blue-100 flex items-center">
                                <strong class="text-blue-800 font-semibold w-20">เวลาเริ่ม:</strong>
                                <?= htmlspecialchars($event['event_start'] ?? '-') ?>
                            </p>

                            <p class="text-gray-700 text-sm m-0 bg-blue-50 px-4 py-2 rounded-b-lg flex items-center">
                                <strong class="text-blue-800 font-semibold w-20">เวลาจบ:</strong>
                                <?= htmlspecialchars($event['event_end'] ?? '-') ?>
                            </p>
                        </div>

                        <a href="/events/<?= $event['id'] ?? $event['event_id'] ?? '' ?>/detail" class="btn block text-center w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition-colors shadow-sm mt-auto no-underline">ดูรายละเอียด</a>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</main>

<script>
    function slideImage(buttonElement, direction) {
        // หา container เลื่อนรูปภาพ (image-slider) ที่อยู่ในบล็อกเดียวกับปุ่มที่ถูกคลิก
        const container = buttonElement.parentElement.querySelector('.image-slider');
        
        if (container) {
            // คำนวณระยะการเลื่อน ให้เท่ากับความกว้างของคอนเทนเนอร์ (เลื่อนทีละ 1 รูปพอดี)
            const scrollAmount = container.clientWidth; 
            
            if (direction === 'left') {
                container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            } else {
                container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            }
        }
    }
</script>

<?php include 'footer.php'; ?>