<?php
function getAllEvents()
{
    global $connection;
    $sql = "SELECT e.event_id AS id,
                e.event_name AS name,
                e.event_description AS description,
                e.event_start,
                e.event_end,
                e.user_id AS creator_id,
                u.name AS creator_name
            FROM events e
            JOIN users u ON e.user_id = u.user_id
            ORDER BY e.event_start DESC";
    $result = $connection->query($sql);
    if (!$result) {
        throw new Exception($connection->error);
    }
    $events = [];

    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    return $events;
}

function getEventById($eventId)
{
    global $connection;
    $sql = "SELECT e.event_id AS id,
                   e.event_name AS name,
                   e.event_description AS description,
                   e.event_start,
                   e.event_end,
                   u.name AS creator_name,
                   u.user_id AS creator_id
            FROM events e
            JOIN users u ON e.user_id = u.user_id
            WHERE e.event_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if (!$row) return null;
    return $row;
}

function createEvent(
    string $name,
    string $description,
    string $event_start,
    string $event_end,
    int $creator_id
): int { 
    global $connection;
    $sql = "INSERT INTO events 
            (
                    event_name, 
                    event_description, 
                    event_start, 
                    event_end, 
                    user_id
            ) VALUES (?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        throw new Exception($connection->error);
    }

    if($event_start > $event_end) {
        throw new Exception("Event start time must be before end time");
    }
    /*
    if($event_start < date('Y-m-d H:i:s')) {
        throw new Exception("Event start time must be in the future");
    }*/
    $stmt->bind_param(
        "ssssi",
        $name,
        $description,
        $event_start,
        $event_end,
        $creator_id
    );
    if ($stmt->execute()) {
        return (int)$connection->insert_id; 
    }
    return 0; 
}

function getEventByKeyword($keyword)
{
    global $connection;
    $search = '%' . mb_strtolower($keyword, 'UTF-8') . '%';
    
    $sql = "SELECT e.event_id AS id,
                   e.event_name AS name,
                   e.event_description AS description,
                   e.event_start,
                   e.event_end,
                   e.user_id AS creator_id,
                   u.name AS creator_name
            FROM events e
            JOIN users u ON e.user_id = u.user_id

            WHERE LOWER(e.event_name) LIKE LOWER(?)
               OR LOWER(e.event_description) LIKE LOWER(?)
               OR LOWER(u.name) LIKE LOWER(?)
            ORDER BY e.event_start DESC";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sss", $search, $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();

    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    return $events;
}

function updateEvent(
    $eventId,
    ?string $name = null,
    ?string $description = null,
    ?string $event_start = null,
    ?string $event_end = null
): bool {
    global $connection;
    $fields = [];
    $params = [];
    $types = "";
    // ตรวจสอบทีละฟิลด์ ถ้าไม่ว่างให้เก็บลง array
    if ($name !== null) {
        $fields[] = "event_name = ?";
        $params[] = $name;
        $types .= "s";
    }
    if ($description !== null) {
        $fields[] = "event_description = ?";
        $params[] = $description;
        $types .= "s";
    }
    if ($event_start !== null) {
        $fields[] = "event_start = ?";
        $params[] = $event_start;
        $types .= "s";
    }
    if ($event_end !== null) {
        $fields[] = "event_end = ?";
        $params[] = $event_end;
        $types .= "s";
    }
    if (empty($fields)) return false; // ไม่มีอะไรให้อัปเดต
    $types .= "i"; // สำหรับ eventId
    $params[] = $eventId;
    $sql = "UPDATE events 
                    SET " . implode(', ', $fields) . "
                    WHERE event_id = ?";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        throw new Exception($connection->error);
    }
    $stmt->bind_param($types, ...$params);
    if (!$stmt->execute()) {
        return false; // SQL รันไม่ผ่านจริงๆ (เช่น Database หลุด)
    }

    return $stmt->affected_rows > 0;
}

function deleteEvent(int $eventId): bool
{
    global $connection;
    $sql = "DELETE FROM events WHERE event_id = ?";
    $stmt = $connection->prepare($sql);

    if (!$stmt) {
        throw new Exception($connection->error);
    }

    $stmt->bind_param("i", $eventId);

    if (!$stmt->execute()) {
        return false;
    }

    return $stmt->affected_rows > 0;
}

function getEventsByCreatorId(int $creatorId): array
{
    global $connection;
    $sql = "SELECT e.event_id AS id,
                    e.event_name AS name,
                    e.event_description AS description,
                    e.event_start,
                    e.event_end,
                    u.name AS creator_name,
                    e.user_id AS creator_id
            FROM events e
            JOIN users u ON e.user_id = u.user_id
            WHERE e.user_id = ?
            ORDER BY e.event_start DESC";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $creatorId);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
        throw new Exception($connection->error);
    }
    $events = [];

    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    return $events;
}

function getEventStatistics(int $eventId): array {
    global $connection;
    
    $sql = "SELECT 
                COUNT(*) as total_joined,
                SUM(CASE WHEN je.join_status = 'approved' THEN 1 ELSE 0 END) as total_approved,
                SUM(CASE WHEN je.checkin_status = 1 THEN 1 ELSE 0 END) as total_checked_in,

                SUM(CASE WHEN u.gender = 'male' THEN 1 ELSE 0 END) as male_count,
                SUM(CASE WHEN u.gender = 'female' THEN 1 ELSE 0 END) as female_count,
                SUM(CASE WHEN u.gender = 'other' THEN 1 ELSE 0 END) as other_count,
                
                SUM(CASE WHEN (YEAR(CURDATE()) - YEAR(u.birthday)) < 18 THEN 1 ELSE 0 END) as age_under_18,
                SUM(CASE WHEN (YEAR(CURDATE()) - YEAR(u.birthday)) BETWEEN 18 AND 24 THEN 1 ELSE 0 END) as age_18_24,
                SUM(CASE WHEN (YEAR(CURDATE()) - YEAR(u.birthday)) BETWEEN 25 AND 34 THEN 1 ELSE 0 END) as age_25_34,
                SUM(CASE WHEN (YEAR(CURDATE()) - YEAR(u.birthday)) BETWEEN 35 AND 44 THEN 1 ELSE 0 END) as age_35_44,
                SUM(CASE WHEN (YEAR(CURDATE()) - YEAR(u.birthday)) >= 45 THEN 1 ELSE 0 END) as age_45_plus
            FROM join_event je
            JOIN users u ON je.user_id = u.user_id
            WHERE je.event_id = ?";
            
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}