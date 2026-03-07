<?php
    function creatorcheck($eventCreatorId , $location): void {
         if ($_SESSION['user_id'] !== $eventCreatorId) {
            header("Location: $location");
            exit;
        }
    }