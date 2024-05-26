<?php
require_once '../includes/functions.php';

session_start();
if (!isset($_SESSION['country_logged_in']) || $_GET['country'] !== $_SESSION['country_logged_in'] || $_GET['room'] !== $_SESSION['room']) {
    header('Location: index.php');
    exit();
}

$room_name = $_GET['room'];
$game_data = loadGameData($room_name);
if (!$game_data) {
    die('Игра еще не началась.');
}

$country_name = $_GET['country'];
$country = null;

foreach ($game_data['countries'] as $c) {
    if ($c['name'] === $country_name) {
        $country = $c;
        break;
    }
}

if (!$country) {
    die('Страна не найдена.');
}

// Check if the player is ready
$is_ready = $country['ready'];

$notifications_path = "../data/$room_name/notifications.json";
if (file_exists($notifications_path)) {
    $notifications_data = json_decode(file_get_contents($notifications_path), true);
    $notifications = $notifications_data[$country_name] ?? [];
} else {
    $notifications = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($country['name']); ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        var initialMoney = <?php echo json_encode($country['money']); ?>;
        var initialNuclearMissiles = <?php echo json_encode($country['nuclear_missiles']); ?>;
        var gameData = <?php echo json_encode($game_data); ?>;
        var isReady = <?php echo json_encode($is_ready); ?>;
    </script>
    <script src="../assets/js/scripts.js"></script>
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <div class="text-center mb-4">
        <button type="submit" class="btn btn-success btn-lg animated bounce" id="readyButton" <?php echo $is_ready ? 'disabled' : ''; ?>>Готов <i class="material-icons">check</i></button>
    </div>
    <div class="info-header text-center mb-4">
        <h1 class="display-4 mb-4"><?php echo htmlspecialchars($country['name']); ?> <i class="material-icons">flag</i></h1>
        <div class="row justify-content-center country-info">
            <div class="col-md-3">
                <div class="card mb-3 shadow-sm bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="material-icons">attach_money</i> Количество монет</h5>
                        <p class="card-text" id="money"><?php echo htmlspecialchars($country['money']); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-3 shadow-sm bg-danger text-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="material-icons">explosive</i> Количество ядерных ракет</h5>
                        <p class="card-text" id="nuclear_missiles"><?php echo htmlspecialchars($country['nuclear_missiles']); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-3 shadow-sm bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="material-icons">hourglass_empty</i> Номер раунда</h5>
                        <p class="card-text"><?php echo htmlspecialchars($game_data['current_round']); ?>/<?php echo htmlspecialchars($game_data['num_rounds']); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-3 shadow-sm bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="material-icons">eco</i> Процент мировой экологии</h5>
                        <p class="card-text"><?php echo htmlspecialchars($game_data['global_ecology']); ?>%</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-3 shadow-sm bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="material-icons">trending_up</i> Развитие страны</h5>
                        <p class="card-text"><?php echo htmlspecialchars($country['development']); ?>%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h3>Города <i class="material-icons">location_city</i></h3>
            <form id="actionsForm" action="save_actions.php" method="POST">
                <input type="hidden" name="country" value="<?php echo htmlspecialchars($country_name); ?>">
                <input type="hidden" name="room" value="<?php echo htmlspecialchars($room_name); ?>">
                <div class="cities-grid">
                    <?php foreach ($country['cities'] as $city_index => $city): ?>
                        <div class="city-card mb-3 shadow-sm animated fadeIn <?php echo $city['alive'] ? 'bg-light' : 'bg-secondary text-white'; ?>">
                            <div class="card-header card-header-custom">
                                <h5 class="card-title card-title-custom">
                                    <?php echo isset($city['name']) ? htmlspecialchars($city['name']) : 'Город ' . ($city_index + 1); ?> <i class="material-icons">location_city</i>
                                    <input type="hidden" name="city_names[<?php echo $city_index; ?>]" value="<?php echo isset($city['name']) ? htmlspecialchars($city['name']) : 'Город ' . ($city_index + 1); ?>">
                                </h5>
                            </div>
                            <div class="card-body">
                                <p><i class="material-icons">trending_up</i> Уровень жизни: <?php echo htmlspecialchars($city['development']); ?>%</p>
                                <p><i class="material-icons">security</i> Щит: <?php echo $city['shield'] ? 'Да' : 'Нет'; ?></p>
                                <p><i class="material-icons">favorite</i> Статус: <?php echo $city['alive'] ? '✔️' : '❌'; ?></p>
                                <div class="form-group">
                                    <input type="checkbox" class="custom-checkbox" id="build_shield_<?php echo $city_index; ?>" name="build_shield[]" value="<?php echo $city_index; ?>" <?php echo ($city['shield'] || !$country['nuclear_technology'] || $country['money'] < 300 || !$city['alive'] || $is_ready) ? 'disabled' : ''; ?>>
                                    <label class="custom-checkbox-label" for="build_shield_<?php echo $city_index; ?>">Построить щит <i class="material-icons">security</i></label>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" class="custom-checkbox" id="improve_city_<?php echo $city_index; ?>" name="improve_city[]" value="<?php echo $city_index; ?>" <?php echo ($country['money'] < 300 || !$city['alive'] || $is_ready) ? 'disabled' : ''; ?>>
                                    <label class="custom-checkbox-label" for="improve_city_<?php echo $city_index; ?>">Улучшить развитие <i class="material-icons">trending_up</i></label>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <h3>Действия <i class="material-icons">assignment</i></h3>
                <div class="form-group">
                    <label for="build_nuclear_missile">Построить ядерные ракеты <i class="material-icons">explosive</i></label>
                    <select name="build_nuclear_missile" id="build_nuclear_missile" class="form-control" <?php echo (!$country['nuclear_technology'] || $country['money'] < 150 || $is_ready) ? 'disabled' : ''; ?>>
                        <option value="0">0 ракет</option>
                        <option value="1">1 ракета (-150 денег)</option>
                        <option value="2">2 ракеты (-300 денег)</option>
                        <option value="3">3 ракеты (-450 денег)</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="checkbox" class="custom-checkbox" id="build_nuclear_technology" name="build_nuclear_technology" <?php echo ($country['nuclear_technology'] || $country['money'] < 450 || $is_ready) ? 'disabled' : ''; ?>>
                    <label class="custom-checkbox-label" for="build_nuclear_technology">Построить ядерную технологию <i class="material-icons">science</i></label>
                </div>
                <div class="form-group">
                    <input type="checkbox" class="custom-checkbox" id="invest_in_ecology" name="invest_in_ecology" <?php echo ($country['money'] < 50 || $is_ready) ? 'disabled' : ''; ?>>
                    <label class="custom-checkbox-label" for="invest_in_ecology">Вложиться в экологию <i class="material-icons">eco</i></label>
                </div>

                <h3>Банк <i class="material-icons">account_balance</i></h3>
                <div class="form-group">
                    <label for="loan_amount">Взять кредит <i class="material-icons">attach_money</i>:</label>
                    <select id="loan_amount" name="loan_amount" class="form-control" <?php echo $is_ready ? 'disabled' : ''; ?>>
                        <option value="0">Не брать кредит</option>
                        <option value="100">100 монет</option>
                        <option value="200">200 монет</option>
                        <option value="300">300 монет</option>
                        <option value="450">450 монет</option>
                    </select>
                </div>

                <div class="mt-5">
                    <h3>Уведомления <i class="material-icons">notifications</i></h3>
                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#notifications" aria-expanded="false" aria-controls="notifications">
                        Показать/Скрыть уведомления <i class="material-icons">expand_more</i>
                    </button>
                    <div class="collapse mt-3 notifications-card" id="notifications">
                        <div class="card card-body">
                            <?php if (empty($notifications)): ?>
                                <p>Уведомлений нет.</p>
                            <?php else: ?>
                                <ul>
                                    <?php foreach ($notifications as $notification): ?>
                                        <li><?php echo htmlspecialchars($notification); ?> <i class="material-icons">info</i></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
        </div>
        <div class="col-md-6">
            <h3>Другие страны <i class="material-icons">public</i></h3>
            <div class="countries-grid">
                <?php foreach ($game_data['countries'] as $target_country): ?>
                    <?php if ($target_country['name'] !== $country['name']): ?>
                        <div class="country-card mb-3 shadow-sm animated fadeIn bg-light">
                            <div class="card-header card-header-custom">
                                <h5 class="card-title card-title-custom"><?php echo htmlspecialchars($target_country['name']); ?> <i class="material-icons">flag</i></h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php foreach ($target_country['cities'] as $target_city_index => $target_city): ?>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group <?php echo !$target_city['alive'] ? 'bg-secondary text-white' : ''; ?>">
                                                <p><i class="material-icons">location_city</i> <?php echo isset($target_city['name']) ? htmlspecialchars($target_city['name']) : 'Город ' . ($target_city_index + 1); ?></p>
                                                <p><i class="material-icons">trending_up</i> Развитие: <?php echo htmlspecialchars($target_city['development']); ?>%</p>
                                                <p><i class="material-icons">favorite</i> Статус: <?php echo $target_city['alive'] ? '✔️' : '❌'; ?></p>
                                                <input type="checkbox" class="custom-checkbox" id="launch_missile_<?php echo $target_country['name'] . '_' . $target_city_index; ?>" name="launch_missiles[<?php echo $target_country['name']; ?>][]" value="<?php echo $target_city_index; ?>" <?php echo ($country['nuclear_missiles'] <= 0 || !$target_city['alive'] || $is_ready) ? 'disabled' : ''; ?>>
                                                <label class="custom-checkbox-label" for="launch_missile_<?php echo $target_country['name'] . '_' . $target_city_index; ?>">Запустить ракету <i class="material-icons">explosive</i></label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#readyButton').click(function(event) {
            event.preventDefault();
            if (!isReady) {
                $('#actionsForm').submit();
            } else {
                window.location.href = 'wait.php?country=<?php echo urlencode($country_name); ?>&room=<?php echo urlencode($room_name); ?>';
            }
        });
    });
</script>
</body>
</html>
