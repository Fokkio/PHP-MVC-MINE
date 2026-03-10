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
    $max_participants = $_POST['max_participants'];

    if($max_participants < 1) {
        echo '<script>alert("การจำกัดจำนวนผู้เข้าร่วมต้องมากกว่า 0"); window.location.href = "/events/create";</script>';
        exit;
    }

    if(is_numeric($max_participants) == false) {
        echo '<script>alert("กรุณากรอกจำนวนผู้เข้าร่วมเป็นตัวเลข"); window.location.href = "/events/create";</script>';
        exit;
    }

    if(empty($name) || empty($description) || empty($event_start) || empty($event_end)) {
        echo '<script>alert("กรุณากรอกข้อมูลให้ครบถ้วน"); window.location.href = "/events/create";</script>';
        exit;
    }
    
    if ($event_start > $event_end) {
        echo '<script>alert("วันที่เริ่มต้นต้องไม่มากกว่าวันที่สิ้นสุด"); window.location.href = "/events/create";</script>';
        exit;
    }

    // 1. สร้าง Event ในฐานข้อมูลก่อน
    $eventId = createEvent($name, $description, $event_start, $event_end, $creator_id, (int)$max_participants);

     if (!$eventId) {
        die("เกิดข้อผิดพลาดในการสร้างกิจกรรม");
    }

    if ($eventId) {
        // 2. ตรวจสอบว่ามีการอัปโหลดรูปภาพมาไหม (แบบ Array หลายไฟล์)
        if (isset($_FILES['event_images']['name']) && $_FILES['event_images']['name'][0] != '') {
            
            $totalFiles = count($_FILES['event_images']['name']);
            
            // จำกัดจำนวนไฟล์ไม่เกิน 5 รูป
            if ($totalFiles > 5) {
                $totalFiles = 5;
            }

            $allowed = ['jpg', 'jpeg', 'png', 'webp'];

            // 3. วนลูปอัปโหลดทีละไฟล์
            for ($i = 0; $i < $totalFiles; $i++) {
                if ($_FILES['event_images']['error'][$i] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES['event_images']['name'][$i], PATHINFO_EXTENSION));
                    
                    if (in_array($ext, $allowed)) {
                        $uploadResult = uploadToCloudinary($_FILES['event_images']['tmp_name'][$i], $cloudinaryPreset);

                        if ($uploadResult) {
                            // 4. บันทึกลงฐานข้อมูล
                            saveImage((int)$eventId, $uploadResult['url'], $uploadResult['delete_hash']);
                        }
                    }
                }
            }
        }
        
        header("Location: /events/$eventId/detail");
        exit;
    }
} else {
    notFound();
}