<?php
session_start();
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_name = $_POST['room_name'];
    $num_countries = (int)$_POST['num_countries'];

    // Загружаем данные всех комнат
    $rooms_data = loadRoomsData();

    // Проверяем, если количество комнат достигло максимального значения
    if (count($rooms_data) >= 15) {
        $error = 'Максимальное количество комнат уже создано. Пожалуйста, удалите одну из существующих комнат перед созданием новой.';
    } else {
        // Проверяем уникальность имени комнаты
        $room_exists = false;
        foreach ($rooms_data as $room) {
            if ($room['name'] === $room_name) {
                $room_exists = true;
                break;
            }
        }

        if ($room_exists) {
            $error = 'Комната с таким именем уже существует. Пожалуйста, выберите другое имя для комнаты.';
        } else {
            $countries = [
                [
                    "name" => "США",
                    "cities" => ["Нью-Йорк", "Лос-Анджелес", "Чикаго", "Хьюстон"]
                ],
                [
                    "name" => "Россия",
                    "cities" => ["Москва", "Санкт-Петербург", "Новосибирск", "Екатеринбург"]
                ],
                [
                    "name" => "Китай",
                    "cities" => ["Пекин", "Шанхай", "Гуанчжоу", "Шэньчжэнь"]
                ],
                [
                    "name" => "Германия",
                    "cities" => ["Берлин", "Гамбург", "Мюнхен", "Кёльн"]
                ],
                [
                    "name" => "Франция",
                    "cities" => ["Париж", "Марсель", "Лион", "Тулуза"]
                ],
                [
                    "name" => "Индия",
                    "cities" => ["Нью-Дели", "Мумбаи", "Бангалор", "Ченнай"]
                ],
                [
                    "name" => "Бразилия",
                    "cities" => ["Сан-Паулу", "Рио-де-Жанейро", "Бразилиа", "Салвадор"]
                ],
                [
                    "name" => "Япония",
                    "cities" => ["Токио", "Осака", "Йокогама", "Нагоя"]
                ],
                [
                    "name" => "Канада",
                    "cities" => ["Торонто", "Ванкувер", "Монреаль", "Калгари"]
                ],
                [
                    "name" => "Австралия",
                    "cities" => ["Сидней", "Мельбурн", "Брисбен", "Перт"]
                ],
                [
                    "name" => "Южная Корея",
                    "cities" => ["Сеул", "Пусан", "Инчхон", "Тэгу"]
                ],
                [
                    "name" => "Италия",
                    "cities" => ["Рим", "Милан", "Неаполь", "Турин"]
                ]
            ];

            // Перемешиваем массив стран и отбираем необходимое количество
            shuffle($countries);
            $selected_countries = array_slice($countries, 0, $num_countries);

            $game_countries = [];
            foreach ($selected_countries as $country) {
                $cities = [];
                $total_development = 0;
                foreach ($country['cities'] as $city_name) {
                    $city_development = 15;
                    $cities[] = [
                        "name" => $city_name,
                        "development" => $city_development,
                        "shield" => false,
                        "alive" => true
                    ];
                    $total_development += $city_development;
                }
                $country_development = $total_development / count($cities);
                $game_countries[] = [
                    "name" => $country['name'],
                    "development" => $country_development,
                    "money" => 1000,
                    "nuclear_missiles" => 0,
                    "nuclear_technology" => false,
                    "cities" => $cities,
                    "alive" => true,
                    "ready" => false,
                    "password" => "1" // Устанавливаем пароль "1" для каждой страны
                ];
            }

            $game_data = [
                "num_rounds" => 7,
                "current_round" => 1,
                "countries" => $game_countries,
                "global_ecology" => 100,
                "round_calculated" => false
            ];

            // Сохраняем данные игры
            saveGameData($room_name, $game_data);

            // Сохраняем настройки по умолчанию
            $default_settings = loadDefaultSettings();
            saveSettings($room_name, ['default' => $default_settings]);

            // Обновляем данные комнат
            $rooms_data[] = ["name" => $room_name, "countries" => $game_countries];
            saveRoomsData($rooms_data);

            header("Location: join_room.php?room=" . urlencode($room_name));
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Создать комнату</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center display-4 mb-4">Создать комнату <i class="material-icons">add_box</i></h1>
    <div class="card shadow-sm mt-5 animated fadeIn">
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?> <i class="material-icons">error</i></div>
            <?php endif; ?>
            <form action="create_room.php" method="POST">
                <div class="form-group">
                    <label for="room_name"><i class="material-icons">meeting_room</i> Название комнаты:</label>
                    <input type="text" id="room_name" name="room_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="num_countries"><i class="material-icons">public</i> Количество стран (5-12):</label>
                    <select id="num_countries" name="num_countries" class="form-control" required>
                        <?php for ($i = 5; $i <= 12; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mt-3"><i class="material-icons">save</i> Создать комнату</button>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
