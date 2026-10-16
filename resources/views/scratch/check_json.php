<?php
$json = file_get_contents('e:/Z-pos/lang/sw.json');
$arr = json_decode($json, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "JSON Error: " . json_last_error_msg() . "\n";
} else {
    echo "JSON is valid.\n";
    if (isset($arr['Everything you need to scale'])) {
        echo "Found: Everything you need to scale\n";
    } else {
        echo "Missing: Everything you need to scale\n";
    }
    if (isset($arr['Loved by Business Owners'])) {
        echo "Found: Loved by Business Owners\n";
    } else {
        echo "Missing: Loved by Business Owners\n";
    }
}
