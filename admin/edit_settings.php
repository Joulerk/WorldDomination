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

<?php
if (!isset($_GET['room'])) {
    die('Параметр room не передан.');
}

$room_name = $_GET['room'];
$settings = loadSettings($room_name);
?>

<div class="container mt-5">
    <h1 class="text-center display-4 mb-4">Редактирование настроек</h1>
    <form action="edit_settings_process.php?room=<?php echo urlencode($room_name); ?>" method="POST">
        <?php if ($settings): ?>
            <?php $settings = $settings['default']; ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Настройки</h5>
                            <?php foreach ($settings as $key => $value): ?>
                                <div class="form-group">
                                    <label for="<?php echo $key; ?>"><?php echo ucfirst(str_replace('_', ' ', $key)); ?></label>
                                    <input type="number" id="<?php echo $key; ?>" name="settings[<?php echo $key; ?>]" class="form-control" value="<?php echo htmlspecialchars($value); ?>">
                                </div>
                            <?php endforeach; ?>
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
<script src="https://stackpath.amazonaws.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
