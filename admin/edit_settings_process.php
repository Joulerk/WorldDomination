<?php
require_once '../includes/functions.php';

$room_name = $_GET['room'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reset']) && $_POST['reset'] == 1) {
        // Сброс настроек к значениям по умолчанию
        $default_settings = loadDefaultSettings();
        saveSettings($room_name, ['default' => $default_settings]);
    } else {
        // Сохранение настроек
        $settings = $_POST['settings'];
        saveSettings($room_name, ['default' => $settings]);
    }
    header('Location: edit_settings.php?room=' . urlencode($room_name));
    exit();
}
?>
