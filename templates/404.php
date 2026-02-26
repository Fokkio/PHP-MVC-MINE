<?php include 'header.php' ?>

<main class="flex-grow flex flex-col items-center justify-center py-16 px-4 sm:px-8 text-center">
    
    <h1 class="text-9xl font-extrabold text-blue-600 tracking-widest drop-shadow-sm">404</h1>
    
    <div class="bg-white px-8 py-6 rounded-xl shadow-sm border border-gray-100 mt-8 max-w-lg w-full">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">Page Not Found</h2>
        <p class="text-gray-500 mb-6 leading-relaxed">
            ขออภัยครับ ไม่พบหน้าเว็บที่คุณกำลังค้นหา <br class="hidden sm:block">
            ลิงก์อาจเสียหาย หรือหน้านี้อาจถูกลบไปแล้ว
        </p>
        
        <a href="/events" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-sm transition-colors hover:shadow-md no-underline">
            🏠 กลับสู่หน้าหลัก (Events)
        </a>
    </div>

</main>

<?php include 'footer.php' ?>