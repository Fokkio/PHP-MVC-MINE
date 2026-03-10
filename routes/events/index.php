<?php
declare(strict_types=1);

use LDAP\Result;

$keyword = $_GET['keyword'] ?? '';
$result = [];
$joinEvent = getJoinedEventsByUserId($_SESSION['user_id'] ?? null);
if (!empty($keyword)) {
    $result = getEventByKeyword($keyword);
} else {
    $result = getAllEvents();
}
$current_participants = getAllAmountOfApprovedParticipants();
renderView('events', [
    'events'  => $result, 
    'max_participants' => $result['max_participants'] ?? null,
    'current_participants' => $current_participants ?? null,
    'keyword' => $keyword,
    'joined_events' => !empty($joinEvent) ? array_column($joinEvent, 'event_id') : [],
    'title'   => !empty($keyword) ? "ผลการค้นหา: $keyword" : "กิจกรรมทั้งหมด"
]);