
<script src="https://cdn.tailwindcss.com"></script>
<div class="min-h-screen flex items-center justify-center bg-gray-50 p-4">
    <div class="bg-white shadow-2xl rounded-3xl p-8 w-full max-w-sm text-center border border-gray-100">
        
        <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-50 rounded-full mb-6">
            <span class="text-4xl">🔐</span>
        </div>

        <h1 class="text-2xl font-bold text-gray-800 mb-2">
            OTP สำหรับ Check-in
        </h1>
        <p class="text-sm text-gray-400 mb-8">
            แสดงรหัสนี้แก่เจ้าหน้าที่เพื่อยืนยันการเข้าร่วม
        </p>

        <div class="border-2 border-blue-500 rounded-xl py-6 mb-6 bg-white relative">
            <div class="text-4xl font-bold tracking-[0.2em] text-gray-800 flex justify-center items-center">
                <span><?= $otp ?></span>
            </div>
        </div>

        <div class="flex flex-col gap-2 mb-8 text-sm">
            <div class="flex justify-between text-gray-500">
                <span>เวลาที่เหลือ:</span>
                <span id="countdown" class="font-bold text-red-500"></span>
            </div>
        </div>

        <a href="/events/<?= $eventId ?>/detail" class="text-gray-400 hover:text-gray-600 text-sm flex items-center justify-center gap-1 transition">
            <span>⬅️</span> กลับหน้า Event Details
        </a>

    </div>
</div>

<script>

    document.addEventListener("DOMContentLoaded", function() {
        // Countdown Logic
        let remaining = <?= (int)$remaining ?>; 
        const countdownEl = document.getElementById('countdown');

        function updateCountdown() {
            if (remaining <= 0) {
                countdownEl.innerText = "หมดอายุแล้ว";
                setTimeout(() => { window.location.reload(); }, 1000);
                return;
            }
            let mins = Math.floor(remaining / 60);
            let secs = remaining % 60;
            countdownEl.innerText = `${mins} นาที ${secs < 10 ? '0' : ''}${secs} วินาที`;
            remaining--;
        }
        updateCountdown();
        setInterval(updateCountdown, 1000);

        // Polling (ตรวจสอบสถานะเช็คอินอัตโนมัติ)
        const join_event_id = <?= (int)$participant['join_event_id'] ?>;
        setInterval(() => {
    fetch(`/events/client-verify-checkin?join_event_id=${join_event_id}`)
    .then(res => res.json())
    .then(data => {
        if(data.status === "FOUND"){
            window.location.href = `/events/<?= $eventId ?>/detail?success=1`;
        }
    })
    .catch(err => {
        console.error("Polling error:", err);
    });
}, 3000);
    });
</script>