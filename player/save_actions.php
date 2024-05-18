<?php
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $country_name = $_POST['country'];
    $actions = [];

    if (isset($_POST['build_nuclear_technology'])) {
        $actions['build_nuclear_technology'] = true;
    }

    if (isset($_POST['build_nuclear_missile'])) {
        $actions['build_nuclear_missile'] = (int)$_POST['build_nuclear_missile'];
    }

    if (isset($_POST['invest_in_ecology'])) {
        $actions['invest_in_ecology'] = true;
    }

    if (isset($_POST['improve_city'])) {
        $actions['improve_city'] = array_map('intval', $_POST['improve_city']);
    }

    if (isset($_POST['build_shield'])) {
        $actions['build_shield'] = array_map('intval', $_POST['build_shield']);
    }

    if (isset($_POST['launch_missiles'])) {
        $actions['launch_missiles'] = [];
        foreach ($_POST['launch_missiles'] as $target_country => $cities) {
            $actions['launch_missiles'][$target_country] = array_map('intval', $cities);
        }
    }

    if (isset($_POST['loan_amount']) && $_POST['loan_amount'] > 0) {
        $actions['loan_amount'] = (int)$_POST['loan_amount'];
    }

    $actions_data = loadActionsData();
    $actions_data[$country_name] = $actions;
    saveActionsData($actions_data);

    $game_data = loadGameData();
    foreach ($game_data['countries'] as &$country) {
        if ($country['name'] === $country_name) {
            $country['ready'] = true;
            break;
        }
    }
    saveGameData($game_data);

    header('Location: wait.php');
    exit();
}
?>
