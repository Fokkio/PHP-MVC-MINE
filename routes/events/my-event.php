    <?php
        $userId = $_SESSION['user_id'];
        $events = getEventsByCreatorId($userId);
        creatorcheck($events[0]['creator_id'], '/events');
        renderView('my-events', ['title' => 'My Events', 'events' => $events]);