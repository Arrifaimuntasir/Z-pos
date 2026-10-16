<?php

$files = shell_exec('git grep -l "bi-arrow-left" resources/views');
$files = explode("\n", trim($files));
$count = 0;
foreach($files as $file) {
    if(!file_exists($file)) continue;
    
    // skip warranties create since it's already correct
    if (strpos($file, 'warranties/create.blade.php') !== false) continue;
    
    $content = file_get_contents($file);
    $original = $content;
    
    // Pattern 1: <a href="..." class="btn btn-secondary..."><i class="..."></i> Back...</a>
    $content = preg_replace('/<a\s+href="([^"]+)"\s+class="btn\s+btn-secondary[^"]*"[^>]*>\s*<i\s+class="bi\s+bi-arrow-left[^"]*"[^>]*><\/i>\s*Back[^<]*<\/a>/i', 
                            '<a href="$1" class="btn btn-light border bg-white shadow-sm rounded-pill px-4" style="font-weight: 500; font-size: 14px;"><i class="bi bi-arrow-left me-1"></i> Back</a>', $content);
                            
    // Pattern 2: <a href="..." class="btn btn-light rounded-circle me-3"><i class="..."></i></a>
    $content = preg_replace('/<a\s+href="([^"]+)"\s+class="btn\s+btn-light\s+rounded-circle\s+me-3"[^>]*>\s*<i\s+class="bi\s+bi-arrow-left[^"]*"[^>]*><\/i>\s*<\/a>/i', 
                            '<a href="$1" class="btn btn-light border bg-white shadow-sm rounded-pill px-4 me-3" style="font-weight: 500; font-size: 14px;"><i class="bi bi-arrow-left me-1"></i> Back</a>', $content);
                            
    // Pattern 3: <i class="bi bi-arrow-left"></i> Back to Home / Login (these are inside standard links, wait, let's leave auth links alone, they might be different)
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        $count++;
    }
}
echo "$count files updated.\n";
