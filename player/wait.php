<?php
session_start();
require_once '../includes/functions.php';

$room_name = $_SESSION['room'];
$game_data = loadGameData($room_name);
if (!$game_data) {
    die('Игра еще не началась.');
}

$country_name = $_SESSION['country_logged_in'];
$all_ready = true;

foreach ($game_data['countries'] as $country) {
    if (!$country['ready']) {
        $all_ready = false;
        break;
    }
}

$round_calculated = isset($game_data['round_calculated']) && $game_data['round_calculated'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ожидание</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function(){
            setInterval(function(){
                location.reload();
            }, 5000);
        });
    </script>
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <div class="text-center">
        <div class="card shadow-sm mb-5 animated fadeIn">
            <div class="card-body">
                <h2 class="card-title"><i class="material-icons">hourglass_empty</i> Номер раунда: <?php echo $game_data['current_round']; ?></h2>
            </div>
        </div>

        <div class="card shadow-sm mb-5 animated fadeIn">
            <div class="card-body">
                <h3 class="card-title"><i class="material-icons">flag</i> Статус готовности стран</h3>
                <ul class="list-group">
                    <?php foreach ($game_data['countries'] as $country): ?>
                        <li class="list-group-item">
                            <?php echo htmlspecialchars($country['name']); ?> - <?php echo $country['ready'] ? '<i class="material-icons">check</i> Готов' : '<i class="material-icons">close</i> Не готов'; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <a href="results.php?room=<?php echo urlencode($room_name); ?>" class="btn btn-primary btn-lg mb-3 animated bounce"><i class="material-icons">bar_chart</i> Просмотреть итоги</a>

        <?php if ($all_ready): ?>
            <?php if ($round_calculated): ?>
                <form action="country.php" method="GET">
                    <input type="hidden" name="country" value="<?php echo htmlspecialchars($country_name); ?>">
                    <input type="hidden" name="room" value="<?php echo htmlspecialchars($room_name); ?>">
                    <button type="submit" class="btn btn-success btn-lg animated bounce"><i class="material-icons">play_arrow</i> Перейти к следующему раунду</button>
                </form>
            <?php else: ?>
                <div class="alert alert-info animated fadeIn"><i class="material-icons">hourglass_full</i> Ожидаем подсчета раунда...</div>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-info animated fadeIn"><i class="material-icons">group</i> Ожидание других игроков...</div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
