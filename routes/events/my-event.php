<?php
    $userId = $_SESSION['user_id'];
    
    $events = getEventsByCreatorId($userId);
    
    renderView('my-events', ['title' => 'My Events', 'events' => $events]);
?>