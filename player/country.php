<?php
session_start();
require_once '../includes/functions.php';

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

$can_take_loan = $country['money'] >= 0 && count($country['cities']) > 0;
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
                                <input type="checkbox" id="build_shield_<?php echo $city_index; ?>" name="build_shield[]" value="<?php echo $city_index; ?>" <?php echo ($city['shield'] || !$country['nuclear_technology'] || $country['money'] < 300) ? 'disabled' : ''; ?>>
                            </div>
                            <div class="form-group">
                                <label for="improve_city_<?php echo $city_index; ?>">Улучшить развитие</label>
                                <input type="checkbox" id="improve_city_<?php echo $city_index; ?>" name="improve_city[]" value="<?php echo $city_index; ?>" <?php echo ($country['money'] < 300) ? 'disabled' : ''; ?>>
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
                    <select id="loan_amount" name="loan_amount" class="form-control" <?php echo !$can_take_loan ? 'disabled' : ''; ?>>
                        <option value="0">Не брать кредит</option>
                        <option value="100">100 монет</option>
                        <option value="200">200 монет</option>
                        <option value="300">300 монет</option>
                        <option value="450">450 монет</option>
                    </select>
                </div>
                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-success btn-lg">Готов</button>
                </div>
            </form>
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
</div>

<?php include '../includes/footer.php'; ?>
<script>
    $(document).ready(function() {
        var initialMoney = <?php echo $country['money']; ?>;
        var initialNuclearMissiles = <?php echo $country['nuclear_missiles']; ?>;

        function updatePreview() {
            var money = initialMoney;
            var nuclear_missiles = initialNuclearMissiles;

            // Обновление предварительного просмотра для ядерных ракет
            var build_nuclear_missile = parseInt($('#build_nuclear_missile').val());
            if (money >= build_nuclear_missile * 150) {
                nuclear_missiles += build_nuclear_missile;
                money -= build_nuclear_missile * 150;
            }

            // Обновление предварительного просмотра для ядерной технологии
            if ($('#build_nuclear_technology').is(':checked')) {
                money -= 450;
            }

            // Обновление предварительного просмотра для вложений в экологию
            if ($('#invest_in_ecology').is(':checked')) {
                money -= 50;
            }

            // Обновление предварительного просмотра для улучшений городов
            $('input[name="improve_city[]"]:checked').each(function() {
                money -= 300;
            });

            // Обновление предварительного просмотра для постройки щитов
            $('input[name="build_shield[]"]:checked').each(function() {
                money -= 300;
            });

            // Обновление предварительного просмотра для кредитов
            var loan_amount = parseInt($('#loan_amount').val()) || 0;
            money += loan_amount;

            $('#money').text(money);
            $('#nuclear_missiles').text(nuclear_missiles);

            // Блокировка действий при недостатке денег
            $('input[type="checkbox"], select').each(function() {
                if ($(this).attr('name') === 'build_nuclear_missile' || $(this).attr('name') === 'loan_amount') {
                    return;
                }

                var cost = 0;
                if ($(this).attr('name') === 'build_nuclear_technology') {
                    cost = 450;
                } else if ($(this).attr('name') === 'invest_in_ecology') {
                    cost = 50;
                } else if ($(this).attr('name') === 'improve_city[]' || $(this).attr('name') === 'build_shield[]') {
                    cost = 300;
                }

                $(this).prop('disabled', $(this).is(':checked') ? false : money < cost);
            });

            // Обновление доступности кнопки "Готов"
            $('button[type="submit"]').prop('disabled', money < 0);
        }

        $('input, select').on('change', updatePreview);
        updatePreview();
    });
</script>
</body>
</html>
