<?php
require_once '../includes/functions.php';

session_start();
if (!isset($_SESSION['country_logged_in']) || $_GET['country'] !== $_SESSION['country_logged_in']) {
    header('Location: index.php');
    exit();
}

$game_data = loadGameData();
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

$notifications_data = json_decode(file_get_contents('../data/notifications.json'), true);
$notifications = $notifications_data[$country_name] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($country['name']); ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center display-4 mb-4"><?php echo htmlspecialchars($country['name']); ?></h1>
    <div class="text-center mb-4">
        <p>Количество монет: <span id="money"><?php echo $country['money']; ?></span></p>
        <p>Количество ядерных ракет: <span id="nuclear_missiles"><?php echo $country['nuclear_missiles']; ?></span></p>
        <p>Номер раунда: <?php echo $game_data['current_round']; ?>/<?php echo $game_data['num_rounds']; ?></p>
        <p>Процент мировой экологии: <?php echo $game_data['global_ecology']; ?>%</p>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h3>Города</h3>
            <form id="actionsForm" action="save_actions.php" method="POST">
                <input type="hidden" name="country" value="<?php echo htmlspecialchars($country_name); ?>">
                <?php foreach ($country['cities'] as $city_index => $city): ?>
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php echo isset($city['name']) ? htmlspecialchars($city['name']) : 'Город ' . ($city_index + 1); ?>
                                <input type="hidden" name="city_names[<?php echo $city_index; ?>]" value="<?php echo isset($city['name']) ? htmlspecialchars($city['name']) : 'Город ' . ($city_index + 1); ?>">
                            </h5>
                            <p>Развитие: <?php echo htmlspecialchars($city['development']); ?>%</p>
                            <p>Щит: <?php echo $city['shield'] ? 'Да' : 'Нет'; ?></p>
                            <p>Статус: <?php echo $city['alive'] ? '✔️' : '❌'; ?></p>
                            <div class="form-group">
                                <label for="build_shield_<?php echo $city_index; ?>">Построить щит</label>
                                <input type="checkbox" id="build_shield_<?php echo $city_index; ?>" name="build_shield[]" value="<?php echo $city_index; ?>" <?php echo ($city['shield'] || !$country['nuclear_technology'] || $country['money'] < 300 || !$city['alive']) ? 'disabled' : ''; ?>>
                            </div>
                            <div class="form-group">
                                <label for="improve_city_<?php echo $city_index; ?>">Улучшить развитие</label>
                                <input type="checkbox" id="improve_city_<?php echo $city_index; ?>" name="improve_city[]" value="<?php echo $city_index; ?>" <?php echo ($country['money'] < 300 || !$city['alive']) ? 'disabled' : ''; ?>>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <h3>Действия</h3>
                <div class="form-group">
                    <label>Построить ядерные ракеты</label>
                    <select name="build_nuclear_missile" id="build_nuclear_missile" class="form-control" <?php echo (!$country['nuclear_technology'] || $country['money'] < 150) ? 'disabled' : ''; ?>>
                        <option value="0">0 ракет</option>
                        <option value="1">1 ракета (-150 денег)</option>
                        <option value="2">2 ракеты (-300 денег)</option>
                        <option value="3">3 ракеты (-450 денег)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Построить ядерную технологию</label>
                    <input type="checkbox" id="build_nuclear_technology" name="build_nuclear_technology" <?php echo ($country['nuclear_technology'] || $country['money'] < 450) ? 'disabled' : ''; ?>>
                </div>
                <div class="form-group">
                    <label>Вложиться в экологию</label>
                    <input type="checkbox" id="invest_in_ecology" name="invest_in_ecology" <?php echo ($country['money'] < 50) ? 'disabled' : ''; ?>>
                </div>

                <h3>Банк</h3>
                <div class="form-group">
                    <label for="loan_amount">Взять кредит:</label>
                    <select id="loan_amount" name="loan_amount" class="form-control">
                        <option value="0">Не брать кредит</option>
                        <option value="100">100 монет</option>
                        <option value="200">200 монет</option>
                        <option value="300">300 монет</option>
                        <option value="450">450 монет</option>
                    </select>
                </div>

        </div>
        <div class="col-md-6">
            <h3>Другие страны</h3>
            <?php foreach ($game_data['countries'] as $target_country): ?>
                <?php if ($target_country['name'] !== $country['name']): ?>
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($target_country['name']); ?></h5>
                            <div class="row">
                                <?php foreach ($target_country['cities'] as $target_city_index => $target_city): ?>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <p><?php echo isset($target_city['name']) ? htmlspecialchars($target_city['name']) : 'Город ' . ($target_city_index + 1); ?></p>
                                            <p>Развитие: <?php echo htmlspecialchars($target_city['development']); ?>%</p>
                                            <p>Статус: <?php echo $target_city['alive'] ? '✔️' : '❌'; ?></p>
                                            <label for="launch_missile_<?php echo $target_country['name'] . '_' . $target_city_index; ?>">Запустить ракету</label>
                                            <input type="checkbox" id="launch_missile_<?php echo $target_country['name'] . '_' . $target_city_index; ?>" name="launch_missiles[<?php echo $target_country['name']; ?>][]" value="<?php echo $target_city_index; ?>" <?php echo ($country['nuclear_missiles'] <= 0 || !$target_city['alive']) ? 'disabled' : ''; ?>>
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
    <div class="text-center mt-5">
        <button type="submit" class="btn btn-success btn-lg" id="readyButton">Готов</button>
    </div>
    </form>

    <div class="mt-5">
        <h3>Уведомления</h3>
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#notifications" aria-expanded="false" aria-controls="notifications">
            Показать/Скрыть уведомления
        </button>
        <div class="collapse mt-3" id="notifications">
            <div class="card card-body">
                <?php if (empty($notifications)): ?>
                    <p>Уведомлений нет.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($notifications as $notification): ?>
                            <li><?php echo htmlspecialchars($notification); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<script>
    $(document).ready(function() {
        var initialMoney = <?php echo $country['money']; ?>;
        var initialNuclearMissiles = <?php echo $country['nuclear_missiles']; ?>;

        function updatePreview() {
            var money = initialMoney;
            var nuclear_missiles = initialNuclearMissiles;

            // Обновление предварительного просмотра для вложений в экологию
            if ($('#invest_in_ecology').is(':checked')) {
                money -= 50;
            }

            $('input[name="build_shield[]"]:checked').each(function() {
                money -= 300;
            });

            $('input[name="improve_city[]"]:checked').each(function() {
                money -= 300;
            });

            // Обновление предварительного просмотра для изучения ядерной технологии
            if ($('#build_nuclear_technology').is(':checked')) {
                money -= 450;
            }

            // Обновление предварительного просмотра для кредитов
            var loan_amount = parseInt($('#loan_amount').val()) || 0;
            money += loan_amount;

            // Динамическое обновление количества ракет при запуске
            $('input[name^="launch_missiles"]').each(function() {
                $(this).prop('disabled', false); // Сначала разблокируем все
            });

            $('input[name^="launch_missiles"]:checked').each(function() {
                nuclear_missiles--;
            });

            if (nuclear_missiles < 0) {
                nuclear_missiles = 0;
            }

            $('input[name^="launch_missiles"]').each(function() {
                if (!$(this).is(':checked') && nuclear_missiles <= 0) {
                    $(this).prop('disabled', true);
                }
            });

            $('#money').text(money);
            $('#nuclear_missiles').text(nuclear_missiles);

            // Обновление доступности кнопки "Готов"
            $('#readyButton').prop('disabled', money < 0);

            // Проверка и блокировка чекбоксов при отрицательном балансе
            if (money < 0) {
                $('input[type="checkbox"]:checked').each(function() {
                    if (!$(this).data('initiallyChecked')) {
                        $(this).prop('checked', false);
                    }
                });
                updatePreview();
            }
        }

        $('input, select').on('change', updatePreview);
        $('input[type="checkbox"]').each(function() {
            $(this).data('initiallyChecked', $(this).is(':checked'));
        });
        updatePreview();
    });
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
