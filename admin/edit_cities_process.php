<?php
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_name = $_POST['room_name'];
    $game_data = loadGameData($room_name);

    foreach ($_POST['countries'] as $country_index => $country_data) {
        $game_data['countries'][$country_index]['name'] = $country_data['name'];
        $game_data['countries'][$country_index]['password'] = $country_data['password'];
        $game_data['countries'][$country_index]['money'] = (int)$country_data['money'];
        $game_data['countries'][$country_index]['nuclear_missiles'] = (int)$country_data['nuclear_missiles'];
        $game_data['countries'][$country_index]['nuclear_technology'] = (bool)$country_data['nuclear_technology'];
        $game_data['countries'][$country_index]['alive'] = (bool)$country_data['alive'];

        $total_development = 0;
        $num_cities = count($country_data['cities']);

        foreach ($country_data['cities'] as $city_index => $city_data) {
            $game_data['countries'][$country_index]['cities'][$city_index]['name'] = $city_data['name'];
            $game_data['countries'][$country_index]['cities'][$city_index]['development'] = (int)$city_data['development'];
            $game_data['countries'][$country_index]['cities'][$city_index]['shield'] = (bool)$city_data['shield'];
            $game_data['countries'][$country_index]['cities'][$city_index]['alive'] = (bool)$city_data['alive'];

            // Обновление логики для мертвого города
            if (!$city_data['alive']) {
                $game_data['countries'][$country_index]['cities'][$city_index]['development'] = 0;
                $game_data['countries'][$country_index]['cities'][$city_index]['shield'] = false;
            }

            $total_development += $game_data['countries'][$country_index]['cities'][$city_index]['development'];
        }

        // Пересчет развития страны
        if ($num_cities > 0) {
            $game_data['countries'][$country_index]['development'] = $total_development / $num_cities;
        } else {
            $game_data['countries'][$country_index]['development'] = 0;
        }
    }

    // Устанавливаем поле ready для всех стран в false, чтобы не сохранялось значение
    foreach ($game_data['countries'] as &$country) {
        $country['ready'] = false;
    }

    saveGameData($room_name, $game_data);
    header("Location: edit_cities.php?room=" . urlencode($room_name));
    exit();
}
?>
