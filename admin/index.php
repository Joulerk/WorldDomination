<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_password = '1'; // Пароль администратора
    if ($_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $error = 'Неверный пароль';
    }
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Админ-панель - Вход</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/css/styles.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    </head>
    <body>
    <?php include '../includes/header.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center display-4 mb-4">Админ-панель <i class="material-icons">admin_panel_settings</i></h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm animated fadeIn">
                    <div class="card-body">
                        <h5 class="card-title text-center"><i class="material-icons">lock</i> Введите пароль для доступа к админ-панели</h5>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?> <i class="material-icons">error</i></div>
                        <?php endif; ?>
                        <form method="POST" action="index.php">
                            <div class="form-group">
                                <label for="password"><i class="material-icons">vpn_key</i> Пароль</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block mt-3"><i class="material-icons">login</i> Войти</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
    </html>

    <?php
    exit();
} else {
    // Убираем сессию после успешного входа
    unset($_SESSION['admin_logged_in']);
}

include '../includes/functions.php';
$rooms_data = loadRoomsData();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center display-4 mb-4">Админ-панель <i class="material-icons">admin_panel_settings</i></h1>
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 mb-3">
            <div class="card text-center shadow-sm animated fadeIn">
                <div class="card-body">
                    <h5 class="card-title"><i class="material-icons">location_city</i> Редактировать города</h5>
                    <p class="card-text">Редактируйте города и их данные</p>
                    <form action="edit_cities.php" method="get">
                        <div class="form-group">
                            <label for="room"><i class="material-icons">meeting_room</i> Выберите комнату:</label>
                            <select id="room" name="room" class="form-control" required>
                                <?php foreach ($rooms_data as $room): ?>
                                    <option value="<?php echo htmlspecialchars($room['name']); ?>"><?php echo htmlspecialchars($room['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-info btn-lg btn-block"><i class="material-icons">edit</i> Редактировать</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card text-center shadow-sm animated fadeIn">
                <div class="card-body">
                    <h5 class="card-title"><i class="material-icons">settings</i> Редактировать настройки</h5>
                    <p class="card-text">Редактируйте настройки игры</p>
                    <form action="edit_settings.php" method="get">
                        <div class="form-group">
                            <label for="room"><i class="material-icons">meeting_room</i> Выберите комнату:</label>
                            <select id="room" name="room" class="form-control" required>
                                <?php foreach ($rooms_data as $room): ?>
                                    <option value="<?php echo htmlspecialchars($room['name']); ?>"><?php echo htmlspecialchars($room['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-secondary btn-lg btn-block"><i class="material-icons">settings_applications</i> Настройки</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.amazonaws.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
