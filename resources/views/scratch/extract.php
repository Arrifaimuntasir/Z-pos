<?php
$c = file_get_contents('e:/Z-pos/resources/views/welcome.blade.php');
preg_match_all('/__\(\'(.*?)\'\)/', $c, $matches);
foreach($matches[1] as $m) {
    echo "'" . $m . "'\n";
}
