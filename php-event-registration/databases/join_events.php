<?php
require_once INCLUDES_DIR . '/Enum.php';
function joinEvent(int $user_id, int $event_id): bool
{
    global $connection;
    $checkSql = "SELECT user_id FROM join_event WHERE user_id = ? AND event_id = ?";
    $checkStmt = $connection->prepare($checkSql);
    $checkStmt->bind_param("ii", $user_id, $event_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        return true; 
    }
    $sql = "INSERT INTO join_event (user_id, event_id) VALUES (?, ?)";
    $stmt = $connection->prepare($sql);

    if (!$stmt) {
        throw new Exception($connection->error);
    }

    $stmt->bind_param("ii", $user_id, $event_id);
    return $stmt->execute();
}

function leaveEvent(int $joinEventId)
{
    global $connection;
    $sql = "DELETE FROM join_event WHERE join_event_id = ?";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        throw new Exception($connection->error);
    }
    $stmt->bind_param("i", $joinEventId);

    return $stmt->execute();
}

function updateCheckInEvent(int $joinEventId, bool $checkInStatus)
{
    global $connection;
    $sql = "UPDATE join_event SET checkin_status = ?, checkin_at = NOW() WHERE join_event_id = ? AND join_status = 'approved'";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        throw new Exception($connection->error);
    }
    $status_value = $checkInStatus ? 1 : 0;
    $stmt->bind_param("ii", $status_value, $joinEventId);
    return $stmt->execute();
}

function updateJoinStatus(int $joinEventId, string $status)
{
    global $connection;
    $sql = "UPDATE join_event SET join_status = ? WHERE join_event_id = ?";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        throw new Exception($connection->error);
    }
    $stmt->bind_param("si", $status, $joinEventId);
    return $stmt->execute();
}

function getJoinedEventsByUserId($userId)
{
    global $connection;
    $sql = "SELECT  je.join_event_id, 
                            je.user_id, 
                            je.event_id, 
                            je.join_status, 
                            je.checkin_status,
                            e.event_name,
                            e.event_description, 
                            e.event_start,
                            e.event_end
                    FROM join_event je JOIN events e 
                    ON je.event_id = e.event_id
                    WHERE je.user_id = ?";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        throw new Exception($connection->error);
    }
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
        throw new Exception($connection->error);
    }
    $joinedEvents = [];

    while ($row = $result->fetch_assoc()) {
        $joinedEvents[] = $row;
    }

    return $joinedEvents;
}

function getJoinEventById($joinEventId)
{
    global $connection;
    $sql = "SELECT join_event_id, user_id, event_id, join_status, checkin_status, totp_secret
                    FROM join_event
                    WHERE join_event_id = ?";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        throw new Exception($connection->error);
    }
    $stmt->bind_param("i", $joinEventId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if (!$row) return null;
    return $row;
}

function getAllJoinedStatusByUserId($userId)
{
    global $connection;
    $sql = "SELECT event_id , join_status FROM join_event WHERE user_id = ?";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        throw new Exception($connection->error);
    }
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
        throw new Exception($connection->error);
    }
    $eventIds = [];

    while ($row = $result->fetch_assoc()) {
        $eventIds[(int)$row['event_id']] = $row['join_status'];
    }

    return $eventIds;
}

function getByUserAndEvent($userId, $eventId)
{
    global $connection;
    $sql = "SELECT join_event_id, user_id, event_id, join_status, checkin_status, checkin_at
                    FROM join_event
                    WHERE user_id = ? AND event_id = ?";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        throw new Exception($connection->error);
    }
    $stmt->bind_param("ii", $userId, $eventId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if (!$row) return null;
    return $row;
}

function getAlluserByEventID(int $eventId): array
{
    global $connection;
    $sql = "SELECT je.join_event_id, 
                   je.user_id, 
                   je.join_status, 
                   je.checkin_status,
                   u.name as participant_name
            FROM join_event je 
            JOIN users u ON je.user_id = u.user_id
            WHERE je.event_id = ?";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $joinedEvents = [];
    while ($row = $result->fetch_assoc()) {
        $joinedEvents[] = $row;
    }
    return $joinedEvents;
}

function getJoinEventStatusByUserAndEvent($userId, $eventId)
{
    global $connection;
    $sql = "SELECT join_status FROM join_event WHERE user_id = ? AND event_id = ?";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        throw new Exception($connection->error);
    }
    $stmt->bind_param("ii", $userId, $eventId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if (!$row) return null;
    return $row['join_status'];
}

function getJoinEventIdByUserIdAndEventId(int $userId, int $eventId){
    global $connection;
    $sql = "SELECT join_event_id FROM join_event WHERE user_id = ? AND  event_id = ?";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        throw new Exception($connection->error);
    }
    $stmt->bind_param("ii", $userId, $eventId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if (!$row) return null;
    return $row['join_event_id'];
}

function getCheckInStatusByJoinEventId($joinEventId)
{
    global $connection;

    $sql = "SELECT checkin_status FROM join_event WHERE join_event_id = ?";
    $stmt = $connection->prepare($sql);

    if (!$stmt) {
        return [
            "success" => false,
            "error" => $connection->error
        ];
    }

    $stmt->bind_param("i", $joinEventId);
    
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if (!$row) {
        return null;
    }
    return [
        "success" => true,
        "checkin_status" => (bool)$row['checkin_status']
    ];
}

function saveTotpSecret(int $joinEventId, string $secret)
{
    global $connection;
    $sql = "UPDATE join_event SET totp_secret = ? WHERE join_event_id = ?";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        throw new Exception($connection->error);
    }
    $stmt->bind_param("si", $secret, $joinEventId);
    return $stmt->execute();
}

function getTotpSecret(int $joinEventId)
{
    global $connection;
    $sql = "SELECT totp_secret FROM join_event WHERE join_event_id = ?";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        throw new Exception($connection->error);
    }
    $stmt->bind_param("i", $joinEventId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if (!$row) return null;
    return $row['totp_secret'];
}