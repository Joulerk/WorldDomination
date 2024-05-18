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
?>
