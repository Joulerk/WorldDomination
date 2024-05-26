<?php
require_once '../includes/functions.php';

session_start();
if (!isset($_SESSION['room'])) {
    die('Комната не найдена.');
}

$room_name = $_SESSION['room'];
$game_data = loadGameData($room_name);
if (!$game_data) {
    die('Игра еще не началась.');
}

$events = json_decode(file_get_contents("../data/events.json"), true);
$notifications_data = json_decode(file_get_contents("../data/$room_name/notifications.json"), true) ?: [];

foreach ($game_data['countries'] as &$country) {
    $event = $events[array_rand($events)];

    switch ($event['effect']) {
        case 'reduce_development':
            $country['development'] -= $event['value'];
            if ($country['development'] < 0) $country['development'] = 0;
            break;
        case 'reduce_money':
            $country['money'] -= $event['value'];
            if ($country['money'] < 0) $country['money'] = 0;
            break;
        case 'increase_development':
            $country['development'] += $event['value'];
            break;
    }

    $notifications_data[] = [
        'country' => $country['name'],
        'event' => $event['name'],
        'description' => $event['description']
    ];
}

saveGameData($room_name, $game_data);
file_put_contents("../data/$room_name/notifications.json", json_encode($notifications_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

header('Location: manage_round.php?room=' . urlencode($room_name));
exit();
