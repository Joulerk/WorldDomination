<?php
include '../includes/functions.php';  // Подключаем файл с функциями

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num_countries = (int)$_POST['num_countries'];

    if ($num_countries < 2 || $num_countries > 10) {
        die('Количество стран должно быть между 2 и 10.');
    }

    // Массив реальных стран и городов
    $real_countries = [
        'США' => ['Нью-Йорк', 'Лос-Анджелес', 'Чикаго', 'Хьюстон'],
        'Китай' => ['Пекин', 'Шанхай', 'Гуанчжоу', 'Шэньчжэнь'],
        'Россия' => ['Москва', 'Санкт-Петербург', 'Новосибирск', 'Екатеринбург'],
        'Япония' => ['Токио', 'Осака', 'Нагоя', 'Саппоро'],
        'Германия' => ['Берлин', 'Гамбург', 'Мюнхен', 'Кёльн'],
        'Франция' => ['Париж', 'Марсель', 'Лион', 'Тулуза'],
        'Великобритания' => ['Лондон', 'Бирмингем', 'Манчестер', 'Ливерпуль'],
        'Индия' => ['Мумбаи', 'Дели', 'Бангалор', 'Ченнай'],
        'Бразилия' => ['Сан-Паулу', 'Рио-де-Жанейро', 'Бразилиа', 'Сальвадор'],
        'Италия' => ['Рим', 'Милан', 'Неаполь', 'Турин'],
    ];

    // Инициализация данных игры
    $game_data = [
        'num_rounds' => 7,
        'current_round' => 1,
        'countries' => [],
        'global_ecology' => 90,
    ];

    $selected_countries = array_slice(array_keys($real_countries), 0, $num_countries);

    foreach ($selected_countries as $country_name) {
        $cities = [];
        foreach ($real_countries[$country_name] as $city_name) {
            $cities[] = [
                'name' => $city_name,
                'development' => 15,
                'shield' => false,
                'alive' => true,
            ];
        }

        $game_data['countries'][] = [
            'name' => $country_name,
            'development' => 15,  // Среднее значение развития всех городов страны
            'money' => 1000,
            'nuclear_missiles' => 0,
            'nuclear_technology' => false,
            'cities' => $cities,
            'alive' => true,
            'ready' => false,  // Готовность к следующему раунду
        ];
    }

    // Сохранение данных в JSON файл
    saveGameData($game_data);

    // Перенаправление на страницу администратора
    header('Location: index.php');
    exit();
}
?>
