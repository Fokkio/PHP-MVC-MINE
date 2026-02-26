<?php
declare(strict_types=1);

$keyword = $_GET['keyword'] ?? '';
$result = [];
$joinEvent = getJoinedEventsByUserId($_SESSION['user_id'] ?? null);
if (!empty($keyword)) {
    $result = getEventByKeyword($keyword);
} else {
    $result = getAllEvents();
}
renderView('events', [
    'events'  => $result, 
    'keyword' => $keyword,
    'joined_events' => !empty($joinEvent) ? array_column($joinEvent, 'event_id') : [],
    'title'   => !empty($keyword) ? "ผลการค้นหา: $keyword" : "กิจกรรมทั้งหมด"
]);