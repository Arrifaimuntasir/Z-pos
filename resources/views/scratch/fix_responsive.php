<?php
$directory = new RecursiveDirectoryIterator('e:/Z-pos/resources/views');
$iterator = new RecursiveIteratorIterator($directory);
$regex = new RegexIterator($iterator, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

$count = 0;
foreach ($regex as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    
    // Pattern 1: d-flex justify-content-between align-items-center mb-4
    $modified = preg_replace(
        '/d-flex justify-content-between align-items-center mb-4/i',
        'd-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3',
        $content
    );
    
    // Pattern 2: card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center
    $modified = preg_replace(
        '/card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center/i',
        'card-header bg-white border-bottom-0 pt-4 pb-0 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3',
        $modified
    );
    
    // Pattern 3: d-flex justify-content-between align-items-center mb-3
    $modified = preg_replace(
        '/d-flex justify-content-between align-items-center mb-3/i',
        'd-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3 gap-2',
        $modified
    );

    if ($modified !== $content) {
        file_put_contents($path, $modified);
        $count++;
    }
}
echo "Modified $count files for mobile responsiveness.\n";
