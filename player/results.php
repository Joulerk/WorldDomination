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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .country-row:hover {
            background-color: #f8f9fa;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .city-status.alive {
            color: green;
            font-weight: bold;
        }
        .city-status.dead {
            color: red;
            font-weight: bold;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .icon {
            font-size: 1.2em;
            margin-right: 8px;
        }
    </style>
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center display-4 mb-4 animate__animated animate__fadeInDown">Итоги раунда <?php echo $current_round - 1; ?> <i class="fas fa-chart-line"></i></h1>
    <div class="card shadow-sm mb-5 animate__animated animate__fadeIn">
        <div class="card-body text-center">
            <h3 class="card-title"><i class="fas fa-seedling icon"></i> Мировая экология: <?php echo $global_ecology; ?>%</h3>
        </div>
    </div>
    <canvas id="resultsChart" width="400" height="200" class="animate__animated animate__fadeIn"></canvas>
    <div class="card shadow-sm mt-5 animate__animated animate__fadeIn">
        <div class="card-body">
            <h3 class="card-title"><i class="fas fa-flag icon"></i> Данные о странах</h3>
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                <tr>
                    <th>Страна</th>
                    <th>Развитие (%)</th>
                    <th>Города</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($countries as $index => $country): ?>
                    <tr class="country-row animate__animated animate__fadeInUp <?php echo $index === 0 ? 'table-success' : ''; ?>">
                        <td>
                            <?php echo htmlspecialchars($country['name']); ?>
                            <?php if ($index === 0): ?>
                                <i class="fas fa-trophy text-success icon"></i>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $country['development']; ?></td>
                        <td>
                            <ul>
                                <?php foreach ($country['cities'] as $city): ?>
                                    <li>
                                        <?php echo htmlspecialchars($city['name']); ?> (Развитие: <?php echo $city['development']; ?>%) -
                                        <span class="city-status <?php echo $city['alive'] ? 'alive' : 'dead'; ?>">
                                            <?php echo $city['alive'] ? '<i class="fas fa-smile"></i> Цел' : '<i class="fas fa-skull-crossbones"></i> Мертв'; ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
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
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutBounce'
                }
            }
        });
    });
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
