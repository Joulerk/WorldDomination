<?php
session_start();
require_once '../includes/functions.php';

$country_name = $_GET['country'];

$notifications_data = json_decode(file_get_contents('../data/notifications.json'), true);
$country_notifications = isset($notifications_data[$country_name]) ? $notifications_data[$country_name] : [];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Информация - <?php echo htmlspecialchars($country_name); ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center display-4 mb-4">Информация для <?php echo htmlspecialchars($country_name); ?></h1>
    <?php if ($country_notifications): ?>
        <div class="card shadow-sm">
            <div class="card-body">
                <?php foreach ($country_notifications as $notification): ?>
                    <p><?php echo htmlspecialchars($notification); ?></p>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Уведомлений нет.</div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
