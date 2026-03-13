<?php

declare(strict_types=1);
$method = $context['method'];
$id     = $context['id'];
$cloudinaryPreset = 'project1';
$event = getEventById($id);
if (!$event) {
    notFound();
    exit;
}
creatorcheck($event['creator_id'], '/events');
if ($method === 'GET') {
    renderView('edit-event', [
        'title' => 'Edit Event', 
        'event' => $event
    ]);
} elseif ($method === 'POST') {
    $name        = $_POST['name']        ?? '';
    $description = $_POST['description'] ?? '';
    $event_start = $_POST['event_start'] ?? '';
    $event_end   = $_POST['event_end']   ?? '';
    $max_participants = $_POST['max_participants'];
    if($max_participants < 1) {
        renderView('edit-event', [
            'title' => 'Edit Event', 
            'event' => $event,
            'error' => 'Maximum participants must be greater than 0.'
        ]);
        exit;
    }
    if(is_numeric($max_participants) == false) {
        renderView('edit-event', [
            'title' => 'Edit Event', 
            'event' => $event,
            'error' => 'Maximum participants must be a number.'
        ]);
        exit;
    }
    updateEvent($id, $name, $description, $event_start, $event_end, (int)$max_participants);
    if($event_start > $event_end) {
        renderView('edit-event', [
            'title' => 'Edit Event', 
            'event' => $event,
            'error' => 'Event start time must be before end time.'
        ]);
        exit;
    }
    if (isset($_FILES['event_images']['name']) && $_FILES['event_images']['name'][0] != '') {
        $totalFiles = count($_FILES['event_images']['name']);
        if ($totalFiles > 5) {
            $totalFiles = 5;
        }
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $hasValidImage = false;
        for ($i = 0; $i < $totalFiles; $i++) {
            if ($_FILES['event_images']['error'][$i] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['event_images']['name'][$i], PATHINFO_EXTENSION));
                if (in_array($ext, $allowed)) {
                    $hasValidImage = true;
                    break;
                }
            }
        }
        if ($hasValidImage) {
            $oldImages = getFullImagesByEventId($id);

            if (!empty($oldImages)) {
                foreach ($oldImages as $img) {
                    if (!empty($img['delete_hash'])) {
                        deleteFromCloudinary($img['delete_hash']);
                    }
                }
            }
            deleteImagesByEventId($id);
            for ($i = 0; $i < $totalFiles; $i++) {
                if ($_FILES['event_images']['error'][$i] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES['event_images']['name'][$i], PATHINFO_EXTENSION));
                    if (in_array($ext, $allowed)) {
                        $uploadResult = uploadToCloudinary($_FILES['event_images']['tmp_name'][$i], $cloudinaryPreset);
                        if ($uploadResult) {
                            saveImage((int)$id, $uploadResult['url'], $uploadResult['delete_hash']);
                        }
                    }
                }
            }
        }
    }
    header("Location: /events/$id/detail");
    exit;
} else {
    notFound();
    exit;
}