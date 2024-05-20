<?php
session_start();
require_once '../includes/functions.php';

$game_data = loadGameData();
if (!$game_data) {
    die('Игра еще не началась.');
}

// Сброс метки завершенного пересчета раунда, если текущий раунд меньше 7
if ($game_data['current_round'] <= 7) {
    $game_data['round_calculated'] = false;
    saveGameData($game_data);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Управление раундом</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center display-4 mb-4">Управление раундом</h1>
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

    <form action="process_round.php" method="POST">
        <button type="submit" class="btn btn-primary btn-lg btn-block" <?php echo $game_data['current_round'] > 7 ? 'disabled' : ''; ?>>Рассчитать раунд</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
