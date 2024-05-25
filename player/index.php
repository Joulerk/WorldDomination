<?php
session_start();
require_once '../includes/functions.php';

// Проверяем и удаляем просроченные комнаты
deleteExpiredRooms();

$rooms_data = loadRoomsData();

$error = '';
$deletion_timers = [];

foreach ($rooms_data as $room) {
    $room_name = $room['name'];
    $delete_time = loadRoomDeleteTime($room_name); // Загрузка времени удаления комнаты
    if ($delete_time) {
        if ($delete_time > time()) {
            $deletion_timers[$room_name] = $delete_time - time();
        } else {
            // Удаляем файлы комнаты
            array_map('unlink', glob("../data/$room_name/*"));
            rmdir("../data/$room_name");

            // Удаляем комнату из rooms.json
            $rooms_data = array_filter($rooms_data, fn($r) => $r['name'] !== $room_name);

            // Перезаписываем rooms.json
            saveRoomsData(array_values($rooms_data));
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_room'])) {
        $room_name = $_POST['room_name'];
        $num_countries = (int)$_POST['num_countries'];

        // Перенаправление на страницу создания комнаты
        header("Location: create_room.php?room_name=" . urlencode($room_name) . "&num_countries=" . $num_countries);
        exit();
    } elseif (isset($_POST['join_room'])) {
        $room_name = $_POST['room_name'];
        $room_password = $_POST['room_password'];

        // Проверка пароля комнаты
        foreach ($rooms_data as $room) {
            if ($room['name'] === $room_name) {
                if ($room['password'] === $room_password) {
                    // Пароль верный, перенаправляем на страницу присоединения
                    header("Location: join_room.php?room=" . urlencode($room_name));
                    exit();
                } else {
                    $error = 'Неверный пароль комнаты';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Присоединение к комнате</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center display-4 mb-4">Присоединение к комнате <i class="material-icons">meeting_room</i></h1>
    <div class="card shadow-sm mt-5 animated fadeIn">
        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?> <i class="material-icons">error</i></div>
            <?php endif; ?>
            <form action="index.php" method="POST">
                <div class="form-group">
                    <label for="room_name"><i class="material-icons">meeting_room</i> Название комнаты:</label>
                    <select id="room_name" name="room_name" class="form-control" required>
                        <?php foreach ($rooms_data as $room): ?>
                            <option value="<?php echo htmlspecialchars($room['name']); ?>"><?php echo htmlspecialchars($room['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="room_password"><i class="material-icons">lock</i> Пароль комнаты:</label>
                    <input type="password" name="room_password" id="room_password" class="form-control" required>
                </div>
                <button type="submit" name="join_room" class="btn btn-success mt-3"><i class="material-icons">login</i> Присоединиться</button>
            </form>
            <form action="index.php" method="POST" class="mt-3">
                <button type="submit" name="create_room" class="btn btn-primary mt-3"><i class="material-icons">add_box</i> Создать новую комнату</button>
            </form>
            <form action="manage_round.php" method="GET" class="mt-3">
                <label for="room_manage"><i class="material-icons">settings</i> Название комнаты:</label>
                <select id="room_manage" name="room" class="form-control" required>
                    <?php foreach ($rooms_data as $room): ?>
                        <option value="<?php echo htmlspecialchars($room['name']); ?>"><?php echo htmlspecialchars($room['name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-warning mt-3"><i class="material-icons">manage_accounts</i> Управлять раундом</button>
            </form>
            <?php if (!empty($deletion_timers)): ?>
                <div class="mt-3">
                    <h4><i class="material-icons">timer</i> Таймеры удаления комнат:</h4>
                    <?php foreach ($deletion_timers as $room_name => $time_left): ?>
                        <p>Комната "<?php echo htmlspecialchars($room_name); ?>" будет удалена через <span id="timer-<?php echo htmlspecialchars($room_name); ?>"><?php echo gmdate("H:i:s", $time_left); ?></span></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.amazonaws.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    <?php if (!empty($deletion_timers)): ?>
    <?php foreach ($deletion_timers as $room_name => $time_left): ?>
    (function updateTimer<?php echo htmlspecialchars($room_name); ?>() {
        var timerElement = document.getElementById('timer-<?php echo htmlspecialchars($room_name); ?>');
        var timeLeft = <?php echo $time_left; ?>;

        function update() {
            timeLeft--;
            if (timeLeft <= 0) {
                timerElement.innerText = 'Комната удалена';
            } else {
                timerElement.innerText = new Date(timeLeft * 1000).toISOString().substr(11, 8);
                setTimeout(update, 1000);
            }
        }
        update();
    })();
    <?php endforeach; ?>
    <?php endif; ?>
</script>
</body>
</html>
