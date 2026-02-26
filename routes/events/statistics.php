<?php
declare(strict_types=1);

$eventId = (int)$context['id'];
$stats = getEventStatistics($eventId);
$event = getEventById($eventId);

creatorcheck($event['creator_id'], '/events');

renderView('event-stats', [
    'title' => 'Event Statistics',
    'stats' => $stats,
    'event' => $event
]);