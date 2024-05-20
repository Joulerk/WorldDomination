<?php
session_start();
include '../includes/functions.php';

$game_data = loadGameData();
if (!$game_data) {
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Игра не началась</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
</head>
<body>';
    include '../includes/header.php';
    echo '
    <div class="container mt-5">
        <div class="alert alert-warning text-center" role="alert">
            <h4 class="alert-heading">Игра еще не началась</h4>
            <p>Пожалуйста, подождите, пока администратор начнет игру.</p>
        </div>
    </div>';
    include '../includes/footer.php';
    echo '
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>';
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_country = $_POST['country'];
    $password = $_POST['password'];

    foreach ($game_data['countries'] as $country) {
        if ($country['name'] === $selected_country) {
            if (isset($country['password']) && $country['password'] === $password) {
                $_SESSION['country_logged_in'] = $selected_country;
                header("Location: country.php?country=" . urlencode($selected_country));
                exit();
            } else {
                $error = 'Неверное имя страны или пароль';
            }
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Выбор страны</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center display-4 mb-4">Выбор страны</h1>
    <div class="card shadow-sm mt-5">
        <div class="card-body">
            <form action="index.php" method="POST">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <div class="form-group">
                    <label for="country">Выберите страну:</label>
                    <select id="country" name="country" class="form-control" required>
                        <?php foreach ($game_data['countries'] as $country): ?>
                            <option value="<?php echo htmlspecialchars($country['name']); ?>"><?php echo htmlspecialchars($country['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">Пароль:</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success mt-3">Начать игру</button>
            </form>
            <a href="results.php" class="btn btn-primary mt-3">Посмотреть итоги раунда</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
