<?php
function saveImage($eventId, $imagePath, $deleteHash = null)
{
    global $connection;
    $sql = "INSERT INTO image_storage (event_id, image_path, delete_hash) VALUES (?, ?, ?)";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        throw new Exception($connection->error);
    }
    $stmt->bind_param("iss", $eventId, $imagePath, $deleteHash);
    return $stmt->execute();
}

function getImagesByEventId($eventId)
{
    global $connection;
    $sql = "SELECT image_path FROM image_storage WHERE event_id = ?";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        throw new Exception($connection->error);
    }
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();

    $images = [];
    while ($row = $result->fetch_assoc()) {
        $images[] = htmlspecialchars($row['image_path'], ENT_QUOTES, 'UTF-8');
    }

    return $images;
}

function deleteImagesByEventId($eventId)
{
    global $connection;
    $sql = "DELETE FROM image_storage WHERE event_id = ?";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        throw new Exception($connection->error);
    }
    $stmt->bind_param("i", $eventId);

    return $stmt->execute();
}


function uploadToCloudinary(string $imageTmpPath, string $uploadPreset): ?array {
    $cloudName = $_ENV['CLOUDINARY_CLOUD_NAME']; 
    $url = $_ENV['IMAGE_CLOUD_URL'] . "/$cloudName/image/upload";

    $ch = curl_init();
    $postData = [
        'file' => new CURLFile($imageTmpPath),
        'upload_preset' => $uploadPreset
    ];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    
    $result = curl_exec($ch);
    curl_close($ch);
    
    $response = json_decode($result, true);

    if (isset($response['secure_url'])) {
        return [
            'url' => $response['secure_url'],
            'delete_hash' => $response['public_id'] 
        ];
    }
    return null;
}

function deleteFromCloudinary(string $publicId): bool {
    $cloudName = $_ENV['CLOUDINARY_CLOUD_NAME'];
    $apiKey = $_ENV['CLOUDINARY_API_KEY'];
    $apiSecret = $_ENV['CLOUDINARY_API_SECRET'];
    $url = $_ENV['IMAGE_CLOUD_URL'] . "/$cloudName/image/destroy";
    $timestamp = time();
    $paramsToSign = [
        'public_id' => $publicId,
        'timestamp' => $timestamp
    ];
    
    ksort($paramsToSign);

    $strToSign = "";
    foreach ($paramsToSign as $key => $value) {
        $strToSign .= "{$key}={$value}&";
    }
    $strToSign = rtrim($strToSign, '&') . $apiSecret;

    $signature = sha1($strToSign);

    $postData = [
        'public_id' => $publicId,
        'api_key' => $apiKey,
        'timestamp' => $timestamp,
        'signature' => $signature
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if (isset($result['result']) && $result['result'] === 'ok') {
        return true;
    }

    return false;
}

function getFullImagesByEventId($eventId)
{
    global $connection;
    $sql = "SELECT image_path, delete_hash FROM image_storage WHERE event_id = ?";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        throw new Exception($connection->error);
    }
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();

    $images = [];
    while ($row = $result->fetch_assoc()) {
        $images[] = [
            'image_path'  => $row['image_path'],
            'delete_hash' => $row['delete_hash'] 
        ];
    }

    return $images;
}
