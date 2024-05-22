<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Редактирование городов</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/header.php'; ?>
<?php include '../includes/functions.php'; ?>

<div class="container mt-5">
    <h1 class="text-center display-4 mb-4">Редактирование городов</h1>
    <form action="edit_cities_process.php" method="POST">
        <div class="form-group">
            <label for="room_name">Выберите комнату:</label>
            <select id="room_name" name="room_name" class="form-control" required onchange="this.form.submit()">
                <option value="">-- Выберите комнату --</option>
                <?php $rooms = loadRoomsData(); ?>
                <?php foreach ($rooms as $room): ?>
                    <option value="<?php echo htmlspecialchars($room['name']); ?>" <?php echo (isset($_GET['room']) && $_GET['room'] === $room['name']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($room['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <?php
    if (isset($_GET['room'])) {
        $room_name = $_GET['room'];
        $game_data = loadGameData($room_name);
    }
    ?>

    <?php if (isset($game_data) && $game_data): ?>
        <form action="edit_cities_process.php" method="POST">
            <input type="hidden" name="room_name" value="<?php echo htmlspecialchars($room_name); ?>">
            <div class="row">
                <?php foreach ($game_data['countries'] as $country_index => $country): ?>
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Страна: <?php echo htmlspecialchars($country['name']); ?></h5>
                                <div class="form-group">
                                    <label for="country_name_<?php echo $country_index; ?>">Название страны</label>
                                    <input type="text" id="country_name_<?php echo $country_index; ?>" name="countries[<?php echo $country_index; ?>][name]" class="form-control" value="<?php echo htmlspecialchars($country['name']); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Пароль</label>
                                    <input type="text" name="countries[<?php echo $country_index; ?>][password]" class="form-control" value="<?php echo htmlspecialchars($country['password'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Деньги</label>
                                    <input type="number" name="countries[<?php echo $country_index; ?>][money]" class="form-control" value="<?php echo htmlspecialchars($country['money']); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Количество ядерных ракет</label>
                                    <input type="number" name="countries[<?php echo $country_index; ?>][nuclear_missiles]" class="form-control" value="<?php echo htmlspecialchars($country['nuclear_missiles']); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Ядерная технология</label>
                                    <select name="countries[<?php echo $country_index; ?>][nuclear_technology]" class="form-control">
                                        <option value="1" <?php echo $country['nuclear_technology'] ? 'selected' : ''; ?>>Да</option>
                                        <option value="0" <?php echo !$country['nuclear_technology'] ? 'selected' : ''; ?>>Нет</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Статус жизни</label>
                                    <select name="countries[<?php echo $country_index; ?>][alive]" class="form-control">
                                        <option value="1" <?php echo $country['alive'] ? 'selected' : ''; ?>>Жив</option>
                                        <option value="0" <?php echo !$country['alive'] ? 'selected' : ''; ?>>Мертв</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Развитие страны (%)</label>
                                    <input type="number" name="countries[<?php echo $country_index; ?>][development]" class="form-control" value="<?php echo htmlspecialchars($country['development']); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Готовность к следующему раунду</label>
                                    <select name="countries[<?php echo $country_index; ?>][ready]" class="form-control">
                                        <option value="1" <?php echo $country['ready'] ? 'selected' : ''; ?>>Готов</option>
                                        <option value="0" <?php echo !$country['ready'] ? 'selected' : ''; ?>>Не готов</option>
                                    </select>
                                </div>
                                <h6>Города:</h6>
                                <div class="row">
                                    <?php foreach ($country['cities'] as $city_index => $city): ?>
                                        <div class="col-md-6 mb-2">
                                            <div class="card shadow-sm">
                                                <div class="card-body">
                                                    <h6 class="card-title">Город: <input type="text" name="countries[<?php echo $country_index; ?>][cities][<?php echo $city_index; ?>][name]" class="form-control" value="<?php echo isset($city['name']) ? htmlspecialchars($city['name']) : 'Без имени'; ?>"></h6>
                                                    <div class="form-group">
                                                        <label>Развитие города (%)</label>
                                                        <input type="number" name="countries[<?php echo $country_index; ?>][cities][<?php echo $city_index; ?>][development]" class="form-control" value="<?php echo htmlspecialchars($city['development']); ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Щит</label>
                                                        <select name="countries[<?php echo $country_index; ?>][cities][<?php echo $city_index; ?>][shield]" class="form-control">
                                                            <option value="1" <?php echo $city['shield'] ? 'selected' : ''; ?>>Да</option>
                                                            <option value="0" <?php echo !$city['shield'] ? 'selected' : ''; ?>>Нет</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Статус жизни</label>
                                                        <select name="countries[<?php echo $country_index; ?>][cities][<?php echo $city_index; ?>][alive]" class="form-control">
                                                            <option value="1" <?php echo $city['alive'] ? 'selected' : ''; ?>>Жив</option>
                                                            <option value="0" <?php echo !$city['alive'] ? 'selected' : ''; ?>>Мертв</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block mt-3">Сохранить изменения</button>
        </form>
    <?php else: ?>
        <div class="alert alert-warning">
            <p>Пожалуйста, выберите комнату для редактирования.</p>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.amazonaws.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
