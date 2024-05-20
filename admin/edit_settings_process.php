<?php
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reset']) && $_POST['reset'] == 1) {
        // Сброс настроек к значениям по умолчанию
        $default_settings = loadDefaultSettings();
        saveSettings(['default' => $default_settings]);
    } else {
        // Сохранение настроек
        $settings = $_POST['settings'];
        saveSettings(['default' => $settings]);
    }
    header('Location: edit_settings.php');
    exit();
}
?>
