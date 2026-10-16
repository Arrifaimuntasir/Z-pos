<?php

$source = 'e:/Z-pos';
$zipFile = 'e:/Z-pos/update_v4.zip';

if (file_exists($zipFile)) {
    unlink($zipFile);
}

// Get git status output
$output = [];
exec('cd ' . escapeshellarg($source) . ' && git status -s', $output);

$zip = new ZipArchive();
if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    exit("Cannot open <$zipFile>\n");
}

$filesAdded = 0;

foreach ($output as $line) {
    if (strlen($line) > 3) {
        $file = trim(substr($line, 3));
        
        // Remove trailing slash if any
        $file = rtrim($file, '/');
        
        // Skip some files
        if (strpos($file, 'scratch/') === 0 || preg_match('/^update.*\.zip$/', $file) || $file === 'admin_history.txt') {
            continue;
        }

        $fullPath = $source . '/' . $file;
        
        if (is_dir($fullPath)) {
            // Add directory and its contents recursively
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($fullPath, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );
            
            foreach ($iterator as $item) {
                $subFile = $item->getPathname();
                $relativePath = substr($subFile, strlen($source) + 1);
                // Fix slashes for windows
                $relativePath = str_replace('\\', '/', $relativePath);
                
                if ($item->isDir()) {
                    $zip->addEmptyDir($relativePath);
                } else {
                    $zip->addFile($subFile, $relativePath);
                    $filesAdded++;
                }
            }
        } elseif (is_file($fullPath)) {
            $zip->addFile($fullPath, $file);
            $filesAdded++;
        }
    }
}

$zip->close();
echo "Update zip created successfully at $zipFile with $filesAdded files.\n";
