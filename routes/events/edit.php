<?php

declare(strict_types=1);

// 1. เตรียมข้อมูลพื้นฐาน
$method = $context['method'];
$id     = $context['id'];
$cloudinaryPreset = 'project1';

/**
 * แก้ไขจุด Error: 
 * เราต้องดึงข้อมูล Event มาก่อน เพื่อให้ตัวแปร $event มีค่า 
 * ก่อนที่จะนำไปใช้ใน creatorcheck() หรือ renderView()
 */
$event = getEventById($id);

// ตรวจสอบว่ามีข้อมูลกิจกรรมนี้จริงไหม
if (!$event) {
    notFound();
    exit;
}

// ตรวจสอบสิทธิ์ว่าเป็นเจ้าของกิจกรรมจริงไหม (ใช้ $event['id'] ได้แล้วเพราะดึงข้อมูลมาแล้ว)
creatorcheck($event['creator_id'], '/events');

// --- แยกการทำงานตาม Method ---

if ($method === 'GET') {
    // แสดงหน้าฟอร์มแก้ไข (ส่ง $event ที่ดึงไว้ด้านบนเข้าไปใน View)
    renderView('edit-event', [
        'title' => 'Edit Event', 
        'event' => $event
    ]);

} elseif ($method === 'POST') {
    // รับค่าจากฟอร์ม
    $name        = $_POST['name']        ?? '';
    $description = $_POST['description'] ?? '';
    $event_start = $_POST['event_start'] ?? '';
    $event_end   = $_POST['event_end']   ?? '';

    // อัปเดตข้อมูลข้อความในฐานข้อมูล
    updateEvent($id, $name, $description, $event_start, $event_end);

    // --- ส่วนจัดการรูปภาพ (Cloudinary) ---
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['event_image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ext, $allowed)) {
            // 1. ดึงข้อมูลรูปเดิมเพื่อลบออกจาก Cloudinary
            $oldImages = getFullImagesByEventId($id);

            if (!empty($oldImages)) {
                foreach ($oldImages as $img) {
                    if (!empty($img['delete_hash'])) {
                        // ลบรูปภาพออกจาก Cloudinary
                        deleteFromCloudinary($img['delete_hash']);
                    }
                }
            }

            // 2. ลบข้อมูลรูปเดิมออกจากฐานข้อมูลของเรา
            deleteImagesByEventId($id);

            // 3. อัปโหลดรูปใหม่ไปยัง Cloudinary
            $uploadResult = uploadToCloudinary($_FILES['event_image']['tmp_name'], $cloudinaryPreset);

            if ($uploadResult) {
                // 4. บันทึกข้อมูลรูปใหม่ลง Database
                saveImage((int)$id, $uploadResult['url'], $uploadResult['delete_hash']);
            }
        }
    }

    // เมื่อทุกอย่างเสร็จสิ้น ให้ Redirect ไปหน้ารายละเอียด
    // (Headers already sent จะไม่เกิดขึ้นแล้วเพราะไม่มี Warning ด้านบน)
    header("Location: /events/$id/detail");
    exit;

} else {
    // กรณี Method ไม่ใช่ GET หรือ POST
    notFound();
    exit;
}

