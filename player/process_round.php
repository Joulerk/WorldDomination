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

// Проверка текущего раунда
if ($game_data['current_round'] > 7) {
    header('Location: manage_round.php?room=' . urlencode($room_name));
    exit();
}

$actions_data = loadActionsData($room_name);
$notifications_data = json_decode(file_get_contents("../data/$room_name/notifications.json"), true) ?: [];

function handleNuclearTechnology(&$country, &$notifications, &$global_ecology) {
    $country['nuclear_technology'] = true;
    $country['money'] -= 450;
    $global_ecology -= 5;
    $notifications[] = "Построена ядерная технология (-450 монет, -5% экологии)";
}

function handleNuclearMissileBuild(&$country, $num_missiles, &$notifications, &$global_ecology) {
    $country['nuclear_missiles'] += $num_missiles;
    $country['money'] -= $num_missiles * 150;
    $global_ecology -= $num_missiles * 2.5;
    $notifications[] = "Построено ядерных ракет: {$num_missiles} (-" . ($num_missiles * 150) . " монет, -" . ($num_missiles * 2.5) . "% экологии)";
}

function handleEcologyInvestment(&$country, &$notifications, &$global_ecology) {
    $global_ecology += 5;
    $country['money'] -= 50;
    $notifications[] = "Вложено в экологию (-50 монет, +5% экологии)";
}

function handleCityImprovement(&$country, $city_index, &$notifications) {
    $city = &$country['cities'][$city_index];
    $city['development'] += 15;
    $country['money'] -= 300;
    $notifications[] = "Улучшено развитие города: " . $city['name'] . " (+15% развития, -300 монет)";
}

function handleShieldBuild(&$country, $city_index, &$notifications) {
    $city = &$country['cities'][$city_index];
    $city['shield'] = true;
    $country['money'] -= 300;
    $notifications[] = "Построен щит в городе: " . $city['name'] . " (-300 монет)";
}

function handleMissileLaunch(&$game_data, &$country, $target_country_name, $target_city_index, &$notifications) {
    foreach ($game_data['countries'] as &$target_country) {
        if ($target_country['name'] === $target_country_name) {
            $city = &$target_country['cities'][$target_city_index];
            if ($city['shield']) {
                $city['shield'] = false;
                $notifications[] = "Щит в городе: " . $city['name'] . " страны " . $target_country_name . " был уничтожен";
            } else {
                $city['alive'] = false;
                $city['development'] = 0;
                $notifications[] = "Город: " . $city['name'] . " страны " . $target_country_name . " был уничтожен";
            }
            $country['nuclear_missiles'] -= 1;
            $game_data['global_ecology'] -= 5;
            $notifications[] = "Запущена ядерная ракета по городу: " . $city['name'] . " страны " . $target_country_name . " (-5% экологии)";
            break;
        }
    }
}

function handleLoan(&$country, $loan_amount, &$notifications) {
    $country['money'] += $loan_amount;
    $country['loan'] = $loan_amount;
    $notifications[] = "Взят кредит: {$loan_amount} монет";
}

function handleLoanRepayment(&$country, &$notifications) {
    $loan_payment = $country['loan'] * 1.25;
    $country['money'] -= $loan_payment;
    $notifications[] = "Возвращен кредит с процентами: -{$loan_payment} монет";
    unset($country['loan']);
}

function calculateEarnings(&$country, $global_ecology, &$notifications) {
    $total_development = 0;
    $city_count = 0;
    foreach ($country['cities'] as $city) {
        if ($city['alive']) {
            $total_development += $city['development'];
            $city_count++;
        }
    }
    $average_development = $city_count ? $total_development / $city_count : 0;
    $country['development'] = $average_development;

    $earnings = ($average_development / 5) * 150;
    $earnings = $earnings * ($global_ecology / 100);
    $country['money'] += $earnings;
    $notifications[] = "Заработано: {$earnings} монет на основе развития и экологии";

    foreach ($country['cities'] as $city) {
        if (!$city['alive']) {
            $country['development'] -= 25;
        }
    }

    if ($country['development'] < 0) {
        $country['development'] = 0;
    }
}

// Обработка действий игроков
foreach ($game_data['countries'] as &$country) {
    $country_name = $country['name'];
    $notifications = [];
    $actions = $actions_data[$country_name] ?? null;

    if ($actions) {
        // Обработка постройки ядерной технологии
        if (!empty($actions['build_nuclear_technology'])) {
            handleNuclearTechnology($country, $notifications, $game_data['global_ecology']);
        }

        // Обработка постройки ядерных ракет
        if (!empty($actions['build_nuclear_missile'])) {
            handleNuclearMissileBuild($country, (int)$actions['build_nuclear_missile'], $notifications, $game_data['global_ecology']);
        }

        // Обработка вложений в экологию
        if (!empty($actions['invest_in_ecology'])) {
            handleEcologyInvestment($country, $notifications, $game_data['global_ecology']);
        }

        // Обработка улучшения городов
        if (!empty($actions['improve_city'])) {
            foreach ($actions['improve_city'] as $city_index) {
                handleCityImprovement($country, $city_index, $notifications);
            }
        }

        // Обработка постройки щитов
        if (!empty($actions['build_shield'])) {
            foreach ($actions['build_shield'] as $city_index) {
                handleShieldBuild($country, $city_index, $notifications);
            }
        }

        // Обработка запуска ядерных ракет
        if (!empty($actions['launch_missiles'])) {
            foreach ($actions['launch_missiles'] as $launch) {
                handleMissileLaunch($game_data, $country, htmlspecialchars($launch['target_country']), (int)$launch['target_city_index'], $notifications);
            }
        }

        // Обработка кредита
        if (!empty($actions['loan_amount'])) {
            handleLoan($country, (int)$actions['loan_amount'], $notifications);
        }

        // Сброс готовности страны
        $country['ready'] = false;

        // Сохранение уведомлений
        $notifications_data[$country['name']] = $notifications;
    }

    // Возврат кредита с процентами
    if (!empty($country['loan'])) {
        handleLoanRepayment($country, $notifications_data[$country['name']]);
    }

    // Расчет заработка
    calculateEarnings($country, $game_data['global_ecology'], $notifications_data[$country['name']]);
}

// Увеличение номера раунда
$game_data['current_round'] += 1;
$game_data['round_calculated'] = true; // Метка для завершенного пересчета раунда

// Сохранение данных
saveGameData($room_name, $game_data);
file_put_contents("../data/$room_name/notifications.json", json_encode($notifications_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Очистка действий игроков
file_put_contents("../data/$room_name/actions.json", json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

header('Location: manage_round.php?room=' . urlencode($room_name));
exit();
?>
