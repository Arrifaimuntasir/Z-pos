<?php
$filePath = '/home/loufaypy/zpos_app/resources/views/layouts/admin.blade.php';

if (!file_exists($filePath)) {
    die('<h2 style="color:red">❌ File haipatikani: ' . $filePath . '</h2>');
}

$content = file_get_contents($filePath);

// Check kama fix tayari ipo
if (strpos($content, "endsWith(' ')") !== false) {
    die('<h2 style="color:green">✅ Fix tayari ipo! Space fix ilikuwepo tayari.</h2>');
}

// Check kama auto-search ipo kabla ya kuifanyia kazi
if (strpos($content, 'Auto-search') === false && strpos($content, 'clearTimeout') === false) {
    die('<h2 style="color:orange">⚠️ Auto-search haijaonekana kwenye file hii. Tuma screenshot ya tatizo.</h2>');
}

// Fix 1: Jaribu kuongeza space check kwenye addEventListener('input'
$old1 = "input.addEventListener('input', function() {\n                    clearTimeout(timer);\n                    timer = setTimeout(() => {";
$new1 = "input.addEventListener('input', function() {\n                    clearTimeout(timer);\n                    // Usitume form kama mtumiaji ameweka space - bado anaandika\n                    if (this.value.endsWith(' ')) return;\n                    timer = setTimeout(() => {";

// Fix 2: Jaribu version nyingine ya code
$old2 = "input.addEventListener('input', function(e) {\n                    clearTimeout(timer);\n                    \n                    timer = setTimeout(() => {";
$new2 = "input.addEventListener('input', function(e) {\n                    clearTimeout(timer);\n                    // Usitume form kama mtumiaji ameweka space\n                    if (this.value.endsWith(' ')) return;\n                    timer = setTimeout(() => {";

if (strpos($content, $old1) !== false) {
    $content = str_replace($old1, $new1, $content);
    file_put_contents($filePath, $content);
    echo '<h2 style="color:green">✅ Fix 1 imefanikia! Space fix imeongezwa.</h2>';
} elseif (strpos($content, $old2) !== false) {
    $content = str_replace($old2, $new2, $content);
    file_put_contents($filePath, $content);
    echo '<h2 style="color:green">✅ Fix 2 imefanikia! Space fix imeongezwa.</h2>';
} else {
    // Onyesha sehemu ya code iliyo karibu na maeneo ya auto-search
    echo '<h2 style="color:orange">⚠️ Pattern haikupatikana. Code inayohusiana:</h2><pre>';
    preg_match('/(.{200}clearTimeout.{200})/s', $content, $matches);
    if ($matches) echo htmlspecialchars($matches[1]);
    echo '</pre>';
    exit;
}

// Clear view cache
foreach (glob('/home/loufaypy/zpos_app/storage/framework/views/*.php') as $f) {
    @unlink($f);
}

echo '<p>✅ Cache imesafishwa.</p>';
echo '<p><strong>Refresh ukurasa wa search sasa - space haitafanya submit tena!</strong></p>';
