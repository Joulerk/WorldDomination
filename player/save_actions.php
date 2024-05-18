<?php
require_once '../includes/functions.php';

$game_data = loadGameData();
if (!$game_data) {
    die('Игра еще не началась.');
}

$country_name = $_POST['country'];
$country = null;

foreach ($game_data['countries'] as $c) {
    if ($c['name'] === $country_name) {
        $country = $c;
        break;
    }
}

if (!$country) {
    die('Страна не найдена.');
}

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
    $actions['launch_missiles'] = $_POST['launch_missiles'];
}

// Сохранение названий городов
if (isset($_POST['city_names'])) {
    foreach ($_POST['city_names'] as $index => $name) {
        $country['cities'][$index]['name'] = $name;
    }
}

$actions_data = loadActionsData();
$actions_data[$country_name] = $actions;
saveActionsData($actions_data);

// Обновление статуса готовности страны в game_data.json
$country_index = array_search($country, $game_data['countries']);
$game_data['countries'][$country_index] = $country;
$game_data['countries'][$country_index]['ready'] = true;
saveGameData($game_data);

header('Location: wait.php?country=' . urlencode($country_name));
exit();
?>
