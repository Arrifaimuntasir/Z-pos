<?php

$files = shell_exec('git grep -l "btn btn-light border bg-white" resources/views');
$files = explode("\n", trim($files));
$count = 0;
foreach($files as $file) {
    if(!file_exists($file)) continue;
    
    $content = file_get_contents($file);
    $original = $content;
    
    // Find cases where the back button is BEFORE the <h2> or <h4> tag inside a d-flex container.
    // E.g., <div class="d-flex align-items-center mb-4">\n <a ...>...</a>\n <h4 ...>...</h4>\n</div>
    
    $pattern = '/<div class="d-flex align-items-center mb-4">\s*(<a\s+href="[^"]*"\s+class="btn btn-light border bg-white[^>]*>.*?<\/a>)\s*(<h[2345][^>]*>.*?<\/h[2345]>)\s*<\/div>/is';
    
    $replacement = '<div class="d-flex justify-content-between align-items-center mb-4">
    $2
    $1
</div>';
    
    $content = preg_replace($pattern, $replacement, $content);
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        $count++;
    }
}
echo "$count files updated.\n";
