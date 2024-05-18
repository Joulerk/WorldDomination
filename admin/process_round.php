<?php
require_once '../includes/functions.php';

$game_data = loadGameData();
if (!$game_data) {
    die('Игра еще не началась.');
}

$actions_data = loadActionsData();

// Обработка действий игроков
foreach ($game_data['countries'] as &$country) {
    if (isset($actions_data[$country['name']])) {
        $actions = $actions_data[$country['name']];

        // Обработка постройки ядерной технологии
        if (isset($actions['build_nuclear_technology'])) {
            $country['nuclear_technology'] = true;
            $country['money'] -= 450;
        }

        // Обработка постройки ядерных ракет
        if (isset($actions['build_nuclear_missile'])) {
            $country['nuclear_missiles'] += $actions['build_nuclear_missile'];
            $country['money'] -= $actions['build_nuclear_missile'] * 150;
        }

        // Обработка вложений в экологию
        if (isset($actions['invest_in_ecology'])) {
            $game_data['global_ecology'] += 5;
            $country['money'] -= 50;
        }

        // Обработка улучшения городов
        if (isset($actions['improve_city'])) {
            foreach ($actions['improve_city'] as $city_index) {
                $country['cities'][$city_index]['development'] += 15;
                $country['money'] -= 300;
            }
        }

        // Обработка постройки щитов
        if (isset($actions['build_shield'])) {
            foreach ($actions['build_shield'] as $city_index) {
                $country['cities'][$city_index]['shield'] = true;
                $country['money'] -= 300;
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
                            } else {
                                // Иначе город уничтожается
                                $target_country['cities'][$target_city_index]['alive'] = false;
                            }
                            $country['nuclear_missiles'] -= 1;
                            break;
                        }
                    }
                }
            }
        }

        // Сброс готовности страны
        $country['ready'] = false;
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
}

// Увеличение номера раунда
$game_data['current_round'] += 1;
$game_data['round_calculated'] = true; // Метка для завершенного пересчета раунда

// Сохранение данных
saveGameData($game_data);

// Очистка действий игроков
file_put_contents('../data/actions.json', json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

header('Location: manage_round.php');
exit();
?>
