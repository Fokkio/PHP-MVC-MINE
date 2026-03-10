<?php include 'header.php' ?>

<main class="flex-grow flex flex-col items-center py-10 px-4 sm:px-8">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-8 text-center"><?= htmlspecialchars($title) ?></h1>

    <form method="POST" action="/events/create" enctype="multipart/form-data" class="bg-white p-6 sm:p-10 rounded-2xl shadow-xl w-full max-w-xl border border-gray-100 mb-6">

        <div class="mb-5">
            <label class="block text-sm font-bold text-gray-700 mb-2">Event Name</label>
            <input type="text" name="name" placeholder="ระบุชื่อกิจกรรมของคุณ" required
                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all bg-gray-50 focus:bg-white">
        </div>

        <div class="mb-5">
            <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
            <textarea name="description" placeholder="รายละเอียดกิจกรรม..." required
                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all bg-gray-50 focus:bg-white min-h-[120px] resize-y"></textarea>
        </div>

        <div class="mb-5">
            <label class="block text-sm font-bold text-gray-700 mb-2">Event Images (สูงสุด 5 รูป)</label>

            <div id="preview-container" class="hidden mb-4 relative p-2 bg-gray-50 rounded-xl border border-gray-200">
                <button type="button" onclick="removeImages()" class="absolute -top-3 -right-3 bg-red-500 text-white rounded-full p-1.5 hover:bg-red-600 shadow-md z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div id="image-preview-list" class="flex overflow-x-auto gap-2 snap-x"></div>
            </div>

            <input type="file" name="event_images[]" id="event_images" accept="image/*" multiple
                onchange="previewImages(this)"
                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all bg-gray-50 cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="text-[10px] text-gray-400 mt-2">* รองรับไฟล์ JPG, PNG, WEBP (เลือกได้สูงสุด 5 รูป แนะนำขนาด 16:9)</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Start Date</label>
                <input type="datetime-local" name="event_start" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all bg-gray-50 cursor-pointer">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">End Date</label>
                <input type="datetime-local" name="event_end" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all bg-gray-50 cursor-pointer">
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-2">Max Participants</label>
            <input type="number" name="max_participants" min="1" required
                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all bg-gray-50">
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg hover:shadow-blue-200 text-lg active:scale-[0.98]">
            🚀 Create Event
        </button>
    </form>

    <a href="/events" class="text-gray-400 hover:text-gray-600 font-medium transition-colors no-underline pb-10">
        Cancel and return
    </a>
</main>

<script>
    function previewImages(input) {
        const previewList = document.getElementById('image-preview-list');
        const container = document.getElementById('preview-container');
        previewList.innerHTML = ""; // เคลียร์รูปเก่า

        if (input.files && input.files.length > 0) {
            // เช็คว่าเกิน 5 รูปไหม
            if (input.files.length > 5) {
                alert("คุณสามารถอัปโหลดได้สูงสุด 5 รูปภาพเท่านั้น");
                removeImages(); // เคลียร์ค่าทิ้ง
                return;
            }

            container.classList.remove('hidden');

            for (let i = 0; i < input.files.length; i++) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = "w-32 h-24 object-cover rounded-lg border shadow-sm flex-shrink-0 snap-center";
                    previewList.appendChild(img);
                }
                reader.readAsDataURL(input.files[i]);
            }
        } else {
            container.classList.add('hidden');
        }
    }

    function removeImages() {
        const input = document.getElementById('event_images');
        const container = document.getElementById('preview-container');
        const previewList = document.getElementById('image-preview-list');
        
        input.value = ""; // เคลียร์ไฟล์
        previewList.innerHTML = "";
        container.classList.add('hidden');
    }
</script>

<?php include 'footer.php' ?>