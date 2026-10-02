<?php
$filePath = realpath(__DIR__ . '/../zpos_app/resources/views/returns/defective.blade.php');
if (!$filePath) $filePath = realpath(__DIR__ . '/../resources/views/returns/defective.blade.php');

if (!$filePath || !file_exists($filePath)) {
    die('<h2 style="color:red">❌ File haipatikani. Path: ' . $filePath . '</h2>');
}

$content = file_get_contents($filePath);

// Check kama tayari imefanywa
if (strpos($content, 'repair_status') !== false) {
    die('<h2 style="color:green">✅ Tayari imefanywa! Repair buttons zipo.</h2><p>File: ' . $filePath . '</p>');
}

$old = '                        <td class="pe-4 text-center">
                            @if($item->saleReturn && $item->saleReturn->sale_id)
                            <a href="{{ route(\'sales.show\', $item->saleReturn->sale_id) }}" class="btn btn-sm btn-light text-primary shadow-sm" style="border-radius: 6px;" title="{{ __(\'View Invoice\') }}">
                                <i class="bi bi-eye"></i>
                            </a>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>';

$new = '                        <td class="pe-4 text-center">
                            <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">
                                @if($item->repair_status === \'repaired\')
                                    <form action="{{ route(\'returns.defective.repair-status\', $item->id) }}" method="POST" class="d-inline">
                                        @csrf @method(\'PUT\')
                                        <input type="hidden" name="repair_status" value="not_repaired">
                                        <button type="submit" class="btn btn-sm btn-success shadow-sm" style="border-radius:6px;font-size:0.75rem;" onclick="return confirm(\'Mark as Not Repaired? Stock will be removed.\')">
                                            <i class="bi bi-check-circle-fill me-1"></i>Repaired
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route(\'returns.defective.repair-status\', $item->id) }}" method="POST" class="d-inline">
                                        @csrf @method(\'PUT\')
                                        <input type="hidden" name="repair_status" value="repaired">
                                        <button type="submit" class="btn btn-sm btn-outline-warning shadow-sm" style="border-radius:6px;font-size:0.75rem;">
                                            <i class="bi bi-tools me-1"></i>Not Repaired
                                        </button>
                                    </form>
                                @endif
                                @if($item->saleReturn && $item->saleReturn->sale_id)
                                <a href="{{ route(\'sales.show\', $item->saleReturn->sale_id) }}" class="btn btn-sm btn-light text-primary shadow-sm" style="border-radius: 6px;" title="View Invoice">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @endif
                            </div>
                        </td>';

if (strpos($content, $old) === false) {
    echo '<h2 style="color:orange">⚠️ Pattern haijapatikana. File inaweza kuwa tofauti.</h2>';
    echo '<p>File path: ' . $filePath . '</p>';
    echo '<p>File size: ' . strlen($content) . ' bytes</p>';
    echo '<h3>Lines 90-105 za file:</h3><pre>';
    $lines = explode("\n", $content);
    for ($i = 89; $i < min(106, count($lines)); $i++) {
        echo ($i+1) . ': ' . htmlspecialchars($lines[$i]) . "\n";
    }
    echo '</pre>';
    exit;
}

$newContent = str_replace($old, $new, $content);
file_put_contents($filePath, $newContent);

// Clear view cache
$cacheDir = realpath(__DIR__ . '/../zpos_app/storage/framework/views');
if (!$cacheDir) $cacheDir = realpath(__DIR__ . '/../storage/framework/views');
if ($cacheDir) {
    foreach (glob($cacheDir . '/*.php') as $f) { @unlink($f); }
}

echo '<h2 style="color:green">✅ IMEFANIKIWA! Repair buttons zimeongezwa.</h2>';
echo '<p>File iliyobadilishwa: ' . $filePath . '</p>';
echo '<p><a href="javascript:history.back()">← Rudi</a></p>';
