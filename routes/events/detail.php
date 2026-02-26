<?php
declare(strict_types=1);

$id = $context['id'];
$event = getEventById($id);
$userId = null;
if(isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];   
}

if (!$event) {
    notFound();
}
renderView('event-detail', ['title' => 'Event Detail', 'event' => $event, 'userId' => $userId]);