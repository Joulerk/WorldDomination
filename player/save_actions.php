<?php
require_once '../includes/functions.php';

session_start();
$room_name = $_POST['room'];
$game_data = loadGameData($room_name);
if (!$game_data) {
    die('Игра еще не началась.');
}

$country_name = $_POST['country'];
$country_index = null;

foreach ($game_data['countries'] as $index => $c) {
    if ($c['name'] === $country_name) {
        $country_index = $index;
        break;
    }
}

if ($country_index === null) {
    die('Страна не найдена.');
}

$country = $game_data['countries'][$country_index];
$actions = [];

// Запись действий игрока
if (isset($_POST['build_nuclear_technology'])) {
    $actions['build_nuclear_technology'] = true;
}
if (isset($_POST['build_nuclear_missile']) && $country['nuclear_technology']) {
    $actions['build_nuclear_missile'] = (int)$_POST['build_nuclear_missile'];
}
if (isset($_POST['invest_in_ecology'])) {
    $actions['invest_in_ecology'] = true;
}
if (isset($_POST['build_shield'])) {
    $actions['build_shield'] = array_map('intval', $_POST['build_shield']);
}
if (isset($_POST['improve_city'])) {
    $actions['improve_city'] = array_map('intval', $_POST['improve_city']);
}
if (isset($_POST['launch_missiles'])) {
    $actions['launch_missiles'] = [];
    foreach ($_POST['launch_missiles'] as $target_country => $cities) {
        foreach ($cities as $city_index) {
            $actions['launch_missiles'][] = [
                'target_country' => htmlspecialchars($target_country),
                'target_city_index' => intval($city_index)
            ];
        }
    }
}

// Сохранение названий городов
if (isset($_POST['city_names'])) {
    foreach ($_POST['city_names'] as $index => $name) {
        $country['cities'][$index]['name'] = htmlspecialchars($name); // Применяем htmlspecialchars для защиты от XSS
    }
}

if (isset($_POST['loan_amount']) && $_POST['loan_amount'] > 0) {
    $actions['loan_amount'] = (int)$_POST['loan_amount'];
}

// Добавление кредита к стране
if (isset($actions['loan_amount'])) {
    $country['money'] += $actions['loan_amount'];
}

$actions_data = loadActionsData($room_name);
$actions_data[$country_name] = $actions;
saveActionsData($room_name, $actions_data);

// Обновление статуса готовности страны в game_data.json
$country['ready'] = true;
$game_data['countries'][$country_index] = $country;
saveGameData($room_name, $game_data);

// Перенаправление на страницу ожидания
header('Location: wait.php?country=' . urlencode($country_name) . '&room=' . urlencode($room_name));
exit();
?>
