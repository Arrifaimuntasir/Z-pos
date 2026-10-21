<?php
// Auto-delete trigger for security
if (isset($_GET['delete_self'])) {
    @unlink(__FILE__);
    echo "<h3 style=\"font-family:sans-serif;color:green\">✅ Faili la clear_cache.php limefutwa kikamilifu kwenye server kwa ajili ya usalama!</h3><p><a href=\"/reports\">Fungua Ripoti za Z-POS</a></p>";
    exit;
}

// Clear Laravel view cache
$dir = __DIR__ . "/storage/framework/views";
$count = 0;
if(is_dir($dir)){
    foreach(glob($dir."/*.php") as $f){
        if(unlink($f)) $count++;
    }
    foreach(glob($dir."/*.blade.php.php") as $f){
        if(unlink($f)) $count++;
    }
}
echo "<h2 style=\"font-family:sans-serif;color:green\">✅ Done! Deleted $count cached view files.</h2>";
echo "<p style=\"font-family:sans-serif\"><a href=\"/reports\" style=\"padding:10px 18px;background:#059669;color:#fff;text-decoration:none;border-radius:8px;font-weight:bold;\">Fungua Ripoti (Reports) &rarr;</a></p>";
echo "<p style=\"font-family:sans-serif\"><a href=\"?delete_self=1\" style=\"padding:8px 14px;background:#dc2626;color:#fff;text-decoration:none;border-radius:6px;font-size:0.85rem;font-weight:bold;\">🗑️ Futa Faili Hili Mara Moja (Auto-Delete for Security)</a></p>";
?>
