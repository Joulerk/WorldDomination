<?php
session_start();
require_once '../includes/functions.php';

$room_name = $_GET['room'];
$game_data = loadGameData($room_name);
if (!$game_data) {
    die('Игра еще не началась.');
}

$countries = $game_data['countries'];
usort($countries, function($a, $b) {
    return $b['development'] - $a['development'];
});
$global_ecology = $game_data['global_ecology'];
$current_round = $game_data['current_round'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Итоги раунда</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center display-4 mb-4">Итоги раунда <?php echo $current_round - 1; ?> <i class="material-icons">assessment</i></h1>
    <div class="card shadow-sm mb-5 animated fadeIn">
        <div class="card-body text-center">
            <h3 class="card-title"><i class="material-icons">eco</i> Мировая экология: <?php echo $global_ecology; ?>%</h3>
        </div>
    </div>
    <canvas id="resultsChart" width="400" height="200"></canvas>
    <div class="card shadow-sm mt-5 animated fadeIn">
        <div class="card-body">
            <h3 class="card-title"><i class="material-icons">flag</i> Данные о странах</h3>
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                <tr>
                    <th>Страна</th>
                    <th>Развитие (%)</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($countries as $index => $country): ?>
                    <tr class="<?php echo $index === 0 ? 'table-success' : ''; ?>">
                        <td>
                            <?php echo htmlspecialchars($country['name']); ?>
                            <?php if ($index === 0): ?>
                                <i class="material-icons text-success">emoji_events</i>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $country['development']; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var ctx = document.getElementById('resultsChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($countries, 'name')); ?>,
                datasets: [{
                    label: 'Развитие стран (%)',
                    data: <?php echo json_encode(array_column($countries, 'development')); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
