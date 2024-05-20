<?php

function loadGameData() {
    if (file_exists('../data/game_data.json')) {
        $json_data = file_get_contents('../data/game_data.json');
        return json_decode($json_data, true);
    } else {
        return null;
    }
}

function saveGameData($data) {
    $json_data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents('../data/game_data.json', $json_data);
}

function loadActionsData() {
    if (file_exists('../data/actions.json')) {
        $json_data = file_get_contents('../data/actions.json');
        return json_decode($json_data, true);
    } else {
        return [];
    }
}

function saveActionsData($data) {
    $json_data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents('../data/actions.json', $json_data);
}

function loadSettings() {
    if (file_exists('../data/settings.json')) {
        $json_data = file_get_contents('../data/settings.json');
        return json_decode($json_data, true);
    } else {
        return null;
    }
}

function saveSettings($data) {
    $json_data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents('../data/settings.json', $json_data);
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


?>
