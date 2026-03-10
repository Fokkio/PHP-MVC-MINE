<?php include 'header.php'; ?>

<main class="flex-grow py-12 px-4 sm:px-8 flex flex-col items-center">

    <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center"><?= htmlspecialchars($title) ?></h1>

    <form method="POST" action="/events/<?= $event['id'] ?>/edit" enctype="multipart/form-data" class="bg-white p-6 sm:p-10 rounded-xl shadow-lg w-full max-w-xl border border-gray-100 mb-6">

        <div class="mb-4">
            <label class="text-sm font-semibold text-gray-700">Event Name:</label>
            <input type="text" name="name"
                value="<?= htmlspecialchars($event['name']) ?>" required
                class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-gray-50 focus:bg-white">
        </div>

        <div class="mb-4">
            <label class="text-sm font-semibold text-gray-700">Event Images (สูงสุด 5 รูป):</label>

            <?php
            $images = getImagesByEventId($event['id']);
            $hasImages = !empty($images);
            ?>

            <div id="preview-container" class="<?= $hasImages ? '' : 'hidden' ?> relative mt-2 mb-4 p-2 bg-gray-50 rounded-xl border border-gray-200">
                <div class="absolute -top-3 left-2 bg-blue-500 text-white text-[10px] font-bold px-2 py-1 rounded-full z-10 shadow-sm pointer-events-none">
                    Current / Preview
                </div>
                <div id="image-preview-list" class="flex overflow-x-auto gap-2 snap-x mt-2 pb-2 scrollbar-hide">
                    <?php if ($hasImages): ?>
                        <?php foreach ($images as $img): ?>
                            <img src="<?= $img ?>" class="w-32 h-24 object-cover rounded-lg border shadow-sm flex-shrink-0 snap-center">
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div id="no-image-placeholder" class="<?= $hasImages ? 'hidden' : '' ?> mt-2 mb-4 w-full h-32 bg-gray-100 border-2 border-dashed border-gray-200 rounded-lg flex flex-col items-center justify-center text-gray-400">
                <span class="text-2xl mb-1">🖼️</span>
                <p class="text-xs">No image uploaded</p>
            </div>

            <input type="file" name="event_images[]" id="imageInput" accept="image/*" multiple
                class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all bg-gray-50 cursor-pointer file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="text-[10px] text-red-500 mt-2 font-medium">* การเลือกรูปใหม่จะบันทึกทับรูปเดิมทั้งหมดทันที หากไม่ต้องการเปลี่ยนรูปภาพ ไม่ต้องเลือกไฟล์ใดๆ</p>
        </div>

        <div class="mb-4">
            <label class="text-sm font-semibold text-gray-700">Description:</label>
            <textarea name="description" required
                class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-gray-50 focus:bg-white min-h-[120px] resize-y"><?= htmlspecialchars($event['description']) ?></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="text-sm font-semibold text-gray-700">Start Date:</label>
                <input type="datetime-local" name="event_start"
                    value="<?= date('Y-m-d\TH:i', strtotime($event['event_start'])) ?>" required
                    class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none cursor-pointer bg-gray-50">
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-700">End Date:</label>
                <input type="datetime-local" name="event_end"
                    value="<?= date('Y-m-d\TH:i', strtotime($event['event_end'])) ?>" required
                    class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none cursor-pointer bg-gray-50">
            </div>
        </div>

        <div class="mb-6">
            <label class="text-sm font-semibold text-gray-700">Max Participants:</label>
            <input type="number" name="max_participants" min="1" required
                value="<?= htmlspecialchars($event['max_participants'] ?? '') ?>"
                class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all bg-gray-50">
        </div>
        
        <?php if (isset($error)): ?>
            <div class="bg-red-100 mt-2 mb-2 border border-red-300 rounded-lg w-full">
                <p class="text-md text-red-500 font-medium mt-4 mb-4 text-center"><?= htmlspecialchars($error) ?></p>
            </div>
        <?php endif; ?>
        
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition-colors shadow-sm text-lg">Update Event</button>
    </form>

    <a href="/events/<?= $event['id'] ?>/detail" class="text-gray-500 hover:text-gray-800 font-semibold transition-colors no-underline">Cancel</a>

</main>

<script>
    const imageInput = document.getElementById('imageInput');
    
    imageInput.onchange = evt => {
        const previewList = document.getElementById('image-preview-list');
        const container = document.getElementById('preview-container');
        const placeholder = document.getElementById('no-image-placeholder');
        
        // เช็คว่าเกิน 5 รูปไหม
        if (imageInput.files.length > 5) {
            alert("คุณสามารถอัปโหลดได้สูงสุด 5 รูปภาพเท่านั้น (ยกเลิกการเลือกรูปใหม่)");
            imageInput.value = ""; // เคลียร์ไฟล์ที่เลือก
            // ให้รีเฟรชหน้าเบาๆ หรือไม่ต้องทำอะไรเพื่อให้กลับไปแสดงรูปเดิม
            return;
        }

        if (imageInput.files && imageInput.files.length > 0) {
            previewList.innerHTML = ""; // ล้างรูปพรีวิว/รูปเก่าออกเตรียมแสดงรูปใหม่ที่กำลังจะอัปโหลด
            container.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');

            for (let i = 0; i < imageInput.files.length; i++) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = "w-32 h-24 object-cover rounded-lg border shadow-sm flex-shrink-0 snap-center";
                    previewList.appendChild(img);
                }
                reader.readAsDataURL(imageInput.files[i]);
            }
        }
    }
</script>

<?php include 'footer.php'; ?>