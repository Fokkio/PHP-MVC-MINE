<?php
declare(strict_types=1);
$userId = $_SESSION['user_id'];
$userProfile = getUserProfileDetails($userId);
renderView('profile', ['title' => 'My Profile', 'user' => $userProfile]);