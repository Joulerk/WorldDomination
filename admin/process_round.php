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
$notifications_data = json_decode(file_get_contents('../data/notifications.json'), true);

// Обработка действий игроков
foreach ($game_data['countries'] as &$country) {
    if (isset($actions_data[$country['name']])) {
        $actions = $actions_data[$country['name']];
        $notifications = [];

        // Обработка постройки ядерной технологии
        if (isset($actions['build_nuclear_technology']) && $actions['build_nuclear_technology']) {
            $country['nuclear_technology'] = true;
            $country['money'] -= 450;
            $notifications[] = "Построена ядерная технология (-450 монет)";
        }

        // Обработка постройки ядерных ракет
        if (isset($actions['build_nuclear_missile']) && $actions['build_nuclear_missile'] > 0) {
            $country['nuclear_missiles'] += $actions['build_nuclear_missile'];
            $country['money'] -= $actions['build_nuclear_missile'] * 150;
            $notifications[] = "Построено ядерных ракет: {$actions['build_nuclear_missile']} (-" . ($actions['build_nuclear_missile'] * 150) . " монет)";
        }

        // Обработка вложений в экологию
        if (isset($actions['invest_in_ecology']) && $actions['invest_in_ecology']) {
            $game_data['global_ecology'] += 5;
            $country['money'] -= 50;
            $notifications[] = "Вложено в экологию (-50 монет)";
        }

        // Обработка улучшения городов
        if (isset($actions['improve_city'])) {
            foreach ($actions['improve_city'] as $city_index) {
                $country['cities'][$city_index]['development'] += 15;
                $country['money'] -= 300;
                $notifications[] = "Улучшено развитие города: " . $country['cities'][$city_index]['name'] . " (+15% развития, -300 монет)";
            }
        }

        // Обработка постройки щитов
        if (isset($actions['build_shield'])) {
            foreach ($actions['build_shield'] as $city_index) {
                $country['cities'][$city_index]['shield'] = true;
                $country['money'] -= 300;
                $notifications[] = "Построен щит в городе: " . $country['cities'][$city_index]['name'] . " (-300 монет)";
            }
        }

        // Обработка запуска ядерных ракет
        if (isset($actions['launch_missiles'])) {
            foreach ($actions['launch_missiles'] as $target_country_name => $target_city_indices) {
                foreach ($target_city_indices as $target_city_index) {
                    foreach ($game_data['countries'] as &$target_country) {
                        if ($target_country['name'] === $target_country_name) {
                            if ($target_country['cities'][$target_city_index]['shield']) {
                                // Если есть щит, щит уничтожается
                                $target_country['cities'][$target_city_index]['shield'] = false;
                                $notifications[] = "Щит в городе: " . $target_country['cities'][$target_city_index]['name'] . " страны " . $target_country_name . " был уничтожен";
                            } else {
                                // Иначе город уничтожается
                                $target_country['cities'][$target_city_index]['alive'] = false;
                                $notifications[] = "Город: " . $target_country['cities'][$target_city_index]['name'] . " страны " . $target_country_name . " был уничтожен";
                            }
                            $country['nuclear_missiles'] -= 1;
                            $notifications[] = "Запущена ядерная ракета по городу: " . $target_country['cities'][$target_city_index]['name'] . " страны " . $target_country_name;
                            break;
                        }
                    }
                }
            }
        }

        // Обработка кредита
        if (isset($actions['loan_amount']) && $actions['loan_amount'] > 0) {
            $loan_amount = $actions['loan_amount'];
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
    if (isset($country['loan']) && $country['loan'] > 0) {
        $country['money'] -= $country['loan'] * 1.25;
        $notifications[] = "Возвращен кредит с процентами: -" . ($country['loan'] * 1.25) . " монет";
        unset($country['loan']);
    }

    // Расчет заработка
    $total_development = 0;
    foreach ($country['cities'] as $city) {
        $total_development += $city['development'];
    }
    $average_development = $total_development / count($country['cities']);
    $country['development'] = $average_development;

    $earnings = ($average_development / 5) * 150;
    $earnings = $earnings * ($game_data['global_ecology'] / 100);
    $country['money'] += $earnings;
    $notifications[] = "Заработано: {$earnings} монет на основе развития и экологии";
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
