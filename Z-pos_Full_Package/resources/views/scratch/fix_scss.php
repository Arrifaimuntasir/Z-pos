<?php
$c = file_get_contents('e:/Z-pos/resources/sass/admin.scss');
$c = preg_replace('/\\x00/', '', $c); // Remove null bytes if any
// Find where it got corrupted
$pos = strpos($c, ' / *   L o c a l i z a t i o n');
if ($pos !== false) {
    $c = substr($c, 0, $pos);
} else {
    // If strpos didn't match due to spaces, let's just explode at line 265
    $lines = explode("\n", $c);
    $c = implode("\n", array_slice($lines, 0, 265));
}

$c .= "\n/* Localization Layout Fixes */\n";
$c .= ".sidebar a {\n    white-space: nowrap;\n    overflow: hidden;\n    text-overflow: ellipsis;\n}\n";
$c .= ".btn {\n    white-space: nowrap;\n}\n";
$c .= ".nav-link {\n    white-space: nowrap;\n}\n";

file_put_contents('e:/Z-pos/resources/sass/admin.scss', $c);
echo "Fixed admin.scss\n";
