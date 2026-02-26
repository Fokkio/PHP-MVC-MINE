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
            <label class="text-sm font-semibold text-gray-700">Event Image:</label>

            <?php
            $images = getImagesByEventId($event['id']);
            // ตรวจสอบว่ามีรูปภาพใน Database หรือไม่
            $currentImage = !empty($images) ? $images[0] : null;
            ?>

            <div class="relative mt-2 mb-4 group">
                <img id="preview"
                    src="<?= $currentImage ?>"
                    class="<?= $currentImage ? '' : 'hidden' ?> w-full h-48 object-cover rounded-lg border shadow-sm">

                <?php if ($currentImage): ?>
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-lg">
                        <p class="text-white text-xs font-bold">Current Image</p>
                    </div>
                <?php endif; ?>

                <div id="no-image-placeholder" class="<?= $currentImage ? 'hidden' : '' ?> mt-2 mb-4 w-full h-48 bg-gray-100 border-2 border-dashed border-gray-200 rounded-lg flex flex-col items-center justify-center text-gray-400">
                    <span class="text-2xl">🖼️</span>
                    <p class="text-xs">No image uploaded</p>
                </div>
            </div>

            <input type="file" name="event_image" id="imageInput" accept="image/*"
                class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all bg-gray-50 cursor-pointer file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="text-[10px] text-gray-400 mt-1 italic">* การอัปโหลดรูปใหม่จะบันทึกทับรูปเดิมทันที</p>
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

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition-colors shadow-sm text-lg">Update Event</button>
    </form>

    <a href="/events/<?= $event['id'] ?>/detail" class="text-gray-500 hover:text-gray-800 font-semibold transition-colors no-underline">Cancel</a>

</main>

<script>
    imageInput.onchange = evt => {
        const [file] = imageInput.files
        if (file) {
            const preview = document.getElementById('preview');
            const placeholder = document.getElementById('no-image-placeholder');

            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        }
    }
</script>

<?php include 'footer.php'; ?>