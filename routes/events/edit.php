<?php

declare(strict_types=1);

$method = $context['method'];
$id     = $context['id'];

$cloudinaryPreset = 'project1';

// ตรวจสอบว่าเป็นเจ้าของกิจกรรมจริงไหม
creatorcheck($event['id'], '/events');

if ($method === 'GET') {
    $event = getEventById($id);
    if (!$event) {
        notFound();
    }
    renderView('edit-event', ['title' => 'Edit Event', 'event' => $event]);
} elseif ($method === 'POST') {
    $name        = $_POST['name']        ?? '';
    $description = $_POST['description'] ?? '';
    $event_start = $_POST['event_start'] ?? '';
    $event_end   = $_POST['event_end']   ?? '';

    updateEvent($id, $name, $description, $event_start, $event_end);

    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['event_image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ext, $allowed)) {

            // --- ส่วนการลบรูปภาพเดิม ---
            $oldImages = getFullImagesByEventId($id);

            if (!empty($oldImages)) {
                foreach ($oldImages as $img) {
                    // หมายเหตุ: การลบผ่าน API ของ Cloudinary แบบ Unsigned จะซับซ้อนกว่า ImgBB
                    // เบื้องต้นถ้ายังไม่ได้เขียนฟังก์ชัน delete จาก Cloudinary ให้คอมเมนต์ไว้ก่อนครับ
                    if (!empty($img['delete_hash'])) {
                        deleteFromCloudinary($img['delete_hash']);
                    }
                }
            }

            // ลบข้อมูลรูปเดิมออกจากฐานข้อมูลของเรา
            deleteImagesByEventId($id);

            // 2. เปลี่ยนมาเรียกใช้ฟังก์ชันอัปโหลด Cloudinary โดยส่ง Preset เข้าไป (วิธีเดิม)
            $uploadResult = uploadToCloudinary($_FILES['event_image']['tmp_name'], $cloudinaryPreset);

            if ($uploadResult) {
                // บันทึกลง Database (ใช้ฟังก์ชันเดิมได้เลยเพราะส่งคืน url และ public_id เหมือนกัน)
                saveImage((int)$id, $uploadResult['url'], $uploadResult['delete_hash']);
            }
        }
    }

    header("Location: /events/$id/detail");
    exit;
} else {
    notFound();
}

