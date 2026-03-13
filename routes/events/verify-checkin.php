<?php
declare(strict_types=1);

$method = $context['method'] ?? 'POST';
$eventId = (int)($context['id'] ?? 0);

if ($method !== 'POST') {
    notFound();
}

$inputOtp = $_POST['otp'] ?? '';
$getAllparticipant = getApprovedButNotCheckedInJoinEventByEventId($eventId);

if (!$getAllparticipant) {
    die("ไม่พบข้อมูลผู้เข้าร่วม");
}

$ttl = 60;
$now = time();

$otpvertify = false;
$foundJoinId = null;

foreach ($getAllparticipant as $participant){
    $secret = $participant['totp_secret'] ?? '';

    $expectedOtp = getTOTP($secret);

    if ($inputOtp === $expectedOtp) {
        $otpvertify = true;
        $foundJoinId = $participant['join_event_id']; 
        break;
    }
}

if (!$otpvertify && $foundJoinId === null) {
    echo "<script>
        alert('❌ OTP ไม่ถูกต้องหรือหมดอายุ!');
        window.location.href = '/events/{$eventId}/checkin';
    </script>";
    exit;
}

try {
    updateCheckInEvent($foundJoinId, true); 
    
    echo "<script>
        alert('✅ Check-in สำเร็จ!');
        window.location.href = '/events/{$eventId}/participants';
    </script>";
    exit;
} catch (Exception $e) {
    die("เกิดข้อผิดพลาดในการอัปเดตสถานะ: " . $e->getMessage());
}