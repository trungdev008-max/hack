<!-- viewer.php - Xem dữ liệu đã thu thập -->
<?php
$log_file = 'victims.log';
if (file_exists($log_file)) {
    echo '<pre>' . htmlspecialchars(file_get_contents($log_file)) . '</pre>';
} else {
    echo 'Chưa có dữ liệu.';
}
?>