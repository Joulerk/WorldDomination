<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование настроек</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/header.php'; ?>
<?php include '../includes/functions.php'; ?>

<div class="container mt-5">
    <h1 class="text-center display-4 mb-4">Редактирование настроек</h1>
    <form action="edit_settings_process.php" method="POST">
        <?php $settings = loadSettings(); ?>
        <?php if ($settings): ?>
            <?php $settings = $settings['default']; ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Настройки</h5>
                            <div class="form-group">
                                <label for="nuclear_technology_cost">Стоимость ядерной технологии</label>
                                <input type="number" id="nuclear_technology_cost" name="settings[nuclear_technology_cost]" class="form-control" value="<?php echo htmlspecialchars($settings['nuclear_technology_cost'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="nuclear_missile_cost">Стоимость ядерной ракеты</label>
                                <input type="number" id="nuclear_missile_cost" name="settings[nuclear_missile_cost]" class="form-control" value="<?php echo htmlspecialchars($settings['nuclear_missile_cost'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="city_shield_cost">Стоимость щита</label>
                                <input type="number" id="city_shield_cost" name="settings[city_shield_cost]" class="form-control" value="<?php echo htmlspecialchars($settings['city_shield_cost'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="city_improvement_cost">Стоимость улучшения города</label>
                                <input type="number" id="city_improvement_cost" name="settings[city_improvement_cost]" class="form-control" value="<?php echo htmlspecialchars($settings['city_improvement_cost'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="ecology_investment_cost">Стоимость вложений в экологию</label>
                                <input type="number" id="ecology_investment_cost" name="settings[ecology_investment_cost]" class="form-control" value="<?php echo htmlspecialchars($settings['ecology_investment_cost'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="loan_interest_rate">Процент по кредиту</label>
                                <input type="number" id="loan_interest_rate" name="settings[loan_interest_rate]" class="form-control" value="<?php echo htmlspecialchars($settings['loan_interest_rate'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="city_destruction_development_penalty">Штраф за уничтожение города</label>
                                <input type="number" id="city_destruction_development_penalty" name="settings[city_destruction_development_penalty]" class="form-control" value="<?php echo htmlspecialchars($settings['city_destruction_development_penalty'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="nuclear_missile_ecology_penalty">Влияние на глобальную экологию за ядерные ракеты (%)</label>
                                <input type="number" id="nuclear_missile_ecology_penalty" name="settings[nuclear_missile_ecology_penalty]" class="form-control" value="<?php echo htmlspecialchars($settings['nuclear_missile_ecology_penalty'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="nuclear_technology_ecology_penalty">Влияние на глобальную экологию за ядерные технологии (%)</label>
                                <input type="number" id="nuclear_technology_ecology_penalty" name="settings[nuclear_technology_ecology_penalty]" class="form-control" value="<?php echo htmlspecialchars($settings['nuclear_technology_ecology_penalty'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="nuclear_missile_build_ecology_penalty">Влияние на глобальную экологию за строительство ядерных ракет (%)</label>
                                <input type="number" id="nuclear_missile_build_ecology_penalty" name="settings[nuclear_missile_build_ecology_penalty]" class="form-control" value="<?php echo htmlspecialchars($settings['nuclear_missile_build_ecology_penalty'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="city_improvement_development_increase">Увеличение развития города за улучшение (%)</label>
                                <input type="number" id="city_improvement_development_increase" name="settings[city_improvement_development_increase]" class="form-control" value="<?php echo htmlspecialchars($settings['city_improvement_development_increase'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="earnings_per_5_percent_development">Доход за каждые 5% развития</label>
                                <input type="number" id="earnings_per_5_percent_development" name="settings[earnings_per_5_percent_development]" class="form-control" value="<?php echo htmlspecialchars($settings['earnings_per_5_percent_development'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <button type="submit" class="btn btn-primary btn-lg btn-block mt-3">Сохранить изменения</button>
                </div>
                <div class="col-md-6 mb-3">
                    <button type="submit" name="reset" value="1" class="btn btn-secondary btn-lg btn-block mt-3">По умолчанию</button>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <p>Настройки не найдены. Пожалуйста, создайте файл настроек.</p>
            </div>
        <?php endif; ?>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
