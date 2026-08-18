<?php
header('Content-Type: application/json');

$data_file = '../franchise_data.json';

if (!file_exists($data_file)) {
    echo json_encode([]);
    exit;
}

$data = file_get_contents($data_file);
echo $data;
?>
