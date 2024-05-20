<?php
require_once '../includes/functions.php';

$game_data = loadGameData();
if (!$game_data) {
    die('Игра еще не началась.');
}

// Проверка текущего раунда
if ($game_data['current_round'] > 7) {
    header('Location: manage_round.php');
    exit();
}

$actions_data = loadActionsData();
$notifications_data = json_decode(file_get_contents('../data/notifications.json'), true) ?: [];

// Обработка действий игроков
foreach ($game_data['countries'] as &$country) {
    $country_name = $country['name'];
    $notifications = [];
    $actions = $actions_data[$country_name] ?? null;

    if ($actions) {
        // Обработка постройки ядерной технологии
        if (!empty($actions['build_nuclear_technology'])) {
            $country['nuclear_technology'] = true;
            $country['money'] -= 450;
            $game_data['global_ecology'] -= 5;
            $notifications[] = "Построена ядерная технология (-450 монет, -5% экологии)";
        }

        // Обработка постройки ядерных ракет
        if (!empty($actions['build_nuclear_missile'])) {
            $num_missiles = (int)$actions['build_nuclear_missile'];
            $country['nuclear_missiles'] += $num_missiles;
            $country['money'] -= $num_missiles * 150;
            $game_data['global_ecology'] -= $num_missiles * 2.5;
            $notifications[] = "Построено ядерных ракет: {$num_missiles} (-" . ($num_missiles * 150) . " монет, -" . ($num_missiles * 2.5) . "% экологии)";
        }

        // Обработка вложений в экологию
        if (!empty($actions['invest_in_ecology'])) {
            $game_data['global_ecology'] += 5;
            $country['money'] -= 50;
            $notifications[] = "Вложено в экологию (-50 монет, +5% экологии)";
        }

        // Обработка улучшения городов
        if (!empty($actions['improve_city'])) {
            foreach ($actions['improve_city'] as $city_index) {
                $city = &$country['cities'][$city_index];
                $city['development'] += 15;
                $country['money'] -= 300;
                $notifications[] = "Улучшено развитие города: " . $city['name'] . " (+15% развития, -300 монет)";
            }
        }

        // Обработка постройки щитов
        if (!empty($actions['build_shield'])) {
            foreach ($actions['build_shield'] as $city_index) {
                $city = &$country['cities'][$city_index];
                $city['shield'] = true;
                $country['money'] -= 300;
                $notifications[] = "Построен щит в городе: " . $city['name'] . " (-300 монет)";
            }
        }

        // Обработка запуска ядерных ракет
        $missile_targets = [];
        if (!empty($actions['launch_missiles'])) {
            foreach ($actions['launch_missiles'] as $launch) {
                $target_country_name = htmlspecialchars($launch['target_country']);
                $target_city_index = (int)$launch['target_city_index'];
                $missile_targets[$target_country_name][$target_city_index][] = $country_name;
            }
        }

        foreach ($missile_targets as $target_country_name => $target_cities) {
            foreach ($target_cities as $target_city_index => $launching_countries) {
                foreach ($game_data['countries'] as &$target_country) {
                    if ($target_country['name'] === $target_country_name) {
                        $city = &$target_country['cities'][$target_city_index];
                        $missile_count = count($launching_countries);

                        if ($city['shield'] && $missile_count == 1) {
                            $city['shield'] = false;
                            $notifications[] = "Щит в городе: " . $city['name'] . " страны " . $target_country_name . " был уничтожен одной ракетой";
                        } else {
                            $city['alive'] = false;
                            $city['development'] = 0;
                            $notifications[] = "Город: " . $city['name'] . " страны " . $target_country_name . " был уничтожен";
                        }

                        foreach ($launching_countries as $launching_country_name) {
                            foreach ($game_data['countries'] as &$lc) {
                                if ($lc['name'] === $launching_country_name) {
                                    $lc['nuclear_missiles'] -= 1;
                                    $game_data['global_ecology'] -= 5;
                                    $notifications[] = "Запущена ядерная ракета по городу: " . $city['name'] . " страны " . $target_country_name . " (-5% экологии)";
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }

        // Обработка кредита
        if (!empty($actions['loan_amount'])) {
            $loan_amount = (int)$actions['loan_amount'];
            $country['money'] += $loan_amount;
            $country['loan'] = $loan_amount;
            $notifications[] = "Взят кредит: {$loan_amount} монет";
        }

        // Сброс готовности страны
        $country['ready'] = false;

        // Сохранение уведомлений
        $notifications_data[$country['name']] = $notifications;
    }

    // Возврат кредита с процентами
    if (!empty($country['loan'])) {
        $loan_payment = $country['loan'] * 1.25;
        $country['money'] -= $loan_payment;
        $notifications_data[$country['name']][] = "Возвращен кредит с процентами: -{$loan_payment} монет";
        unset($country['loan']);
    }

    // Расчет заработка
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
    $earnings = $earnings * ($game_data['global_ecology'] / 100);
    $country['money'] += $earnings;
    $notifications_data[$country['name']][] = "Заработано: {$earnings} монет на основе развития и экологии";

    // Уменьшение развития за уничтоженные города
    foreach ($country['cities'] as $city) {
        if (!$city['alive']) {
            $country['development'] -= 25;
        }
    }

    // Если развитие страны меньше 0, установить его на 0
    if ($country['development'] < 0) {
        $country['development'] = 0;
    }
}

// Увеличение номера раунда
$game_data['current_round'] += 1;
$game_data['round_calculated'] = true; // Метка для завершенного пересчета раунда

// Сохранение данных
saveGameData($game_data);
file_put_contents('../data/notifications.json', json_encode($notifications_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Очистка действий игроков
file_put_contents('../data/actions.json', json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

header('Location: manage_round.php');
exit();
?>
