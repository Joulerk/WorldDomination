<?php

function loadGameData($room_name) {
    $file_path = "../data/$room_name/game_data.json";
    if (file_exists($file_path)) {
        $json_data = file_get_contents($file_path);
        return json_decode($json_data, true);
    } else {
        return null;
    }
}

function saveGameData($room_name, $data) {
    $file_path = "../data/$room_name/game_data.json";
    if (!file_exists(dirname($file_path))) {
        mkdir(dirname($file_path), 0777, true);
    }
    $json_data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($file_path, $json_data);
}

function loadActionsData($room_name) {
    $file_path = "../data/$room_name/actions.json";
    if (file_exists($file_path)) {
        $json_data = file_get_contents($file_path);
        return json_decode($json_data, true);
    } else {
        return [];
    }
}

function saveActionsData($room_name, $data) {
    $file_path = "../data/$room_name/actions.json";
    if (!file_exists(dirname($file_path))) {
        mkdir(dirname($file_path), 0777, true);
    }
    $json_data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($file_path, $json_data);
}

function loadSettings($room_name) {
    $file_path = "../data/$room_name/settings.json";
    if (file_exists($file_path)) {
        $json_data = file_get_contents($file_path);
        return json_decode($json_data, true);
    } else {
        return null;
    }
}

function saveSettings($room_name, $data) {
    $file_path = "../data/$room_name/settings.json";
    if (!file_exists(dirname($file_path))) {
        mkdir(dirname($file_path), 0777, true);
    }
    $json_data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($file_path, $json_data);
}

function loadRoomsData() {
    $file_path = "../data/rooms.json";
    if (file_exists($file_path)) {
        $json_data = file_get_contents($file_path);
        return json_decode($json_data, true);
    } else {
        return [];
    }
}

function saveRoomsData($data) {
    $file_path = "../data/rooms.json";
    $json_data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($file_path, $json_data);
}

function loadDefaultSettings() {
    return [
        "nuclear_technology_cost" => 450,
        "nuclear_missile_cost" => 150,
        "ecology_investment_cost" => 50,
        "nuclear_missile_ecology_penalty" => 5,
        "nuclear_technology_ecology_penalty" => 5,
        "nuclear_missile_build_ecology_penalty" => 2.5,
        "city_improvement_cost" => 300,
        "city_improvement_development_increase" => 15,
        "city_shield_cost" => 300,
        "city_destruction_development_penalty" => 25,
        "loan_interest_rate" => 1.25,
        "earnings_per_5_percent_development" => 150
    ];
}

function addCountryPassword(&$game_data, $country_name, $password) {
    foreach ($game_data['countries'] as &$country) {
        if ($country['name'] === $country_name) {
            $country['password'] = $password;
            return true;
        }
    }
    return false;
}

function addRoomPassword(&$rooms_data, $room_name, $password) {
    foreach ($rooms_data as &$room) {
        if ($room['name'] === $room_name) {
            $room['password'] = $password;
            return true;
        }
    }
    return false;
}
?>
