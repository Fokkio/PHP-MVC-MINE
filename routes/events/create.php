<?php
declare(strict_types=1);

$method = $context['method'];
$cloudinaryPreset = 'project1'; 

if ($method === 'GET') {
    renderView('create-event', ['title' => 'Create Event']);

} elseif ($method === 'POST') {
    $name        = $_POST['name']        ?? '';
    $description = $_POST['description'] ?? '';
    $event_start = $_POST['event_start'] ?? '';
    $event_end   = $_POST['event_end']   ?? '';
    $creator_id  = $_SESSION['user_id'];

    if(empty($name) || empty($description) || empty($event_start) || empty($event_end)) {
        echo '<script>alert("กรุณากรอกข้อมูลให้ครบถ้วน"); window.location.href = "/events/create";</script>';
        exit;
    }
    
    if ($event_start > $event_end) {
        echo '<script>alert("วันที่เริ่มต้นต้องไม่มากกว่าวันที่สิ้นสุด"); window.location.href = "/events/create";</script>';
        exit;
    }

    // 1. สร้าง Event ในฐานข้อมูลก่อน
    $eventId = createEvent($name, $description, $event_start, $event_end, $creator_id);

    if ($eventId) {
        // 2. ตรวจสอบว่ามีการอัปโหลดรูปภาพมาไหม
        if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === UPLOAD_ERR_OK) {
            
            $ext = strtolower(pathinfo($_FILES['event_image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($ext, $allowed)) {
                // 3. เรียกใช้ฟังก์ชันอัปโหลดไป imgBB (รับค่ากลับมาเป็น Array)
                $uploadResult = uploadToCloudinary($_FILES['event_image']['tmp_name'], $cloudinaryPreset);

                if ($uploadResult) {
                    // 4. บันทึกทั้ง Direct URL และ Delete Hash ลงฐานข้อมูล
                    // ใช้ฟังก์ชัน saveImage ที่เราเพิ่งแก้ให้รับ 3 พารามิเตอร์
                    saveImage((int)$eventId, $uploadResult['url'], $uploadResult['delete_hash']);
                }
            }
        }
        
        header("Location: /events/$eventId/detail");
        exit;
    }
} else {
    notFound();
}