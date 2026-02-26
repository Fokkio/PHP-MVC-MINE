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
            <label class="block text-sm font-bold text-gray-700 mb-2">Event Image</label>
            
            <div id="preview-container" class="hidden mb-4 relative">
                <img id="image-preview" src="#" alt="Preview" class="w-full h-48 object-cover rounded-xl border shadow-sm">
                <button type="button" onclick="removeImage()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <input type="file" name="event_image" id="event_image" accept="image/*" required
                   onchange="previewImage(this)"
                   class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all bg-gray-50 cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="text-[10px] text-gray-400 mt-2">* รองรับไฟล์ JPG, PNG, WEBP (แนะนำขนาด 16:9)</p>
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

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg hover:shadow-blue-200 text-lg active:scale-[0.98]">
            🚀 Create Event
        </button>
    </form>

    <a href="/events" class="text-gray-400 hover:text-gray-600 font-medium transition-colors no-underline pb-10">
        Cancel and return
    </a>
</main>

<script>
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const container = document.getElementById('preview-container');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImage() {
        const input = document.getElementById('event_image');
        const container = document.getElementById('preview-container');
        input.value = ""; 
        container.classList.add('hidden'); 
    }
</script>

<?php include 'footer.php' ?>