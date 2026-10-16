<?php

$files = shell_exec('git grep -l "bi-arrow-left" resources/views');
$files = explode("\n", trim($files));
$count = 0;
foreach($files as $file) {
    if(!file_exists($file)) continue;
    
    // skip warranties create and auth views
    if (strpos($file, 'warranties/create.blade.php') !== false) continue;
    if (strpos($file, 'auth/') !== false) continue;
    
    $content = file_get_contents($file);
    $original = $content;
    
    // Any <a> tag that contains <i class="bi bi-arrow-left..."> and the word "Back"
    // We will replace its class attribute to the new styling.
    
    $content = preg_replace('/<a\s+href="([^"]+)"\s+class="[^"]*"[^>]*>\s*<i\s+class="bi\s+bi-arrow-left[^"]*"[^>]*><\/i>\s*Back[^<]*<\/a>/i', 
                            '<a href="$1" class="btn btn-light border bg-white shadow-sm rounded-pill px-4" style="font-weight: 500; font-size: 14px;"><i class="bi bi-arrow-left me-1"></i> Back</a>', $content);
                            
    if ($content !== $original) {
        file_put_contents($file, $content);
        $count++;
    }
}
echo "$count files updated.\n";
