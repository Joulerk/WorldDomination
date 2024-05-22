<?php
session_start();
require_once '../includes/functions.php';

if (!isset($_GET['room'])) {
    die('Комната не указана.');
}

$room_name = $_GET['room'];
$game_data = loadGameData($room_name);
if (!$game_data) {
    die('Игра еще не началась.');
}

// Проверка готовности всех стран
$all_ready = true;
foreach ($game_data['countries'] as $country) {
    if (!$country['ready']) {
        $all_ready = false;
        break;
    }
}

// Сброс метки завершенного пересчета раунда, если текущий раунд меньше 7
if ($game_data['current_round'] <= 7) {
    $game_data['round_calculated'] = false;
    saveGameData($room_name, $game_data);
}

// Проверка времени удаления комнаты
$delete_time_file = "../data/$room_name/delete_time.txt";
$delete_time_remaining = null;
if (file_exists($delete_time_file)) {
    $delete_time = (int)file_get_contents($delete_time_file);
    $delete_time_remaining = $delete_time - time();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Управление раундом - <?php echo htmlspecialchars($room_name); ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center display-4 mb-4">Управление раундом - <?php echo htmlspecialchars($room_name); ?></h1>
    <div class="card shadow-sm mb-5">
        <div class="card-body">
            <h3 class="card-title">Статус готовности стран</h3>
            <p>Текущий раунд: <?php echo $game_data['current_round']; ?>/7</p>
            <ul class="list-group">
                <?php foreach ($game_data['countries'] as $country): ?>
                    <li class="list-group-item">
                        <?php echo htmlspecialchars($country['name']); ?> - <?php echo $country['ready'] ? 'Готов' : 'Не готов'; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <?php if ($game_data['current_round'] > 7): ?>
        <div class="alert alert-info">
            Ожидайте удаления комнаты, игра закончена.
            <?php if ($delete_time_remaining !== null): ?>
                Комната будет удалена через: <?php echo gmdate("i:s", $delete_time_remaining); ?> минут.
            <?php endif; ?>
        </div>
    <?php else: ?>
        <form action="process_round.php" method="POST">
            <input type="hidden" name="room" value="<?php echo htmlspecialchars($room_name); ?>">
            <button type="submit" class="btn btn-primary btn-lg btn-block" <?php echo !$all_ready ? 'disabled' : ''; ?>>Рассчитать раунд</button>
        </form>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>