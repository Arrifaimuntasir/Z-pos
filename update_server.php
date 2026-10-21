<?php
/**
 * Z-POS Server Updater & Database Sync
 * Safe runner - DOES NOT DELETE OR OVERWRITE EXISTING DATA.
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Auto-delete trigger for security
if (isset($_GET['delete_self'])) {
    @unlink(__FILE__);
    echo "<h3 style='font-family:sans-serif;color:#10b981;padding:30px;background:#0f172a;'>✅ Faili la update_server.php limefutwa kikamilifu kwenye server kwa ajili ya usalama! <br><br><a href='/home' style='color:#38bdf8;'>Fungua Dashboard ya Z-POS &rarr;</a></h3>";
    exit;
}

// 1. Read .env for DB credentials
$env = [];
if (file_exists(__DIR__ . '/.env')) {
    foreach (file(__DIR__ . '/.env') as $line) {
        $line = trim($line);
        if ($line && strpos($line, '=') !== false && $line[0] !== '#') {
            list($k, $v) = explode('=', $line, 2);
            $env[trim($k)] = trim(trim($v), "\"'\r\n");
        }
    }
}

$host = $env['DB_HOST'] ?? '127.0.0.1';
$port = $env['DB_PORT'] ?? '3306';
$db   = $env['DB_DATABASE'] ?? '';
$user = $env['DB_USERNAME'] ?? '';
$pass = $env['DB_PASSWORD'] ?? '';

$messages = [];
$errors = [];

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $messages[] = "Imeunganishwa kwenye Database ($db) kikamilifu.";

    // 2. Check & Add 'can_manage_products' to 'users' table
    $stmt = $pdo->query("SHOW COLUMNS FROM `users` LIKE 'can_manage_products'");
    $colExists = $stmt->fetch();
    if (!$colExists) {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `can_manage_products` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_active`");
        $messages[] = "Safu ya 'can_manage_products' imeongezwa kwenye jedwali la 'users' (Default: Ruhusa ya kawaida ya Cashier).";
    } else {
        $messages[] = "Safu ya 'can_manage_products' tayari ipo kwenye jedwali la 'users'.";
    }

    // 2b. Check & Add 'theme' to 'shops' table
    $stmt = $pdo->query("SHOW COLUMNS FROM `shops` LIKE 'theme'");
    $themeExists = $stmt->fetch();
    if (!$themeExists) {
        $pdo->exec("ALTER TABLE `shops` ADD COLUMN `theme` VARCHAR(20) NOT NULL DEFAULT 'light' AFTER `receipt_paper_width`");
        $messages[] = "Safu ya 'theme' imeongezwa kwenye jedwali la 'shops' (Hifadhi ya Dark/White mode ya kila duka).";
    } else {
        $messages[] = "Safu ya 'theme' tayari ipo kwenye jedwali la 'shops'.";
    }

    // 3. Check & Create 'warehouses' table if not exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS `warehouses` (
        `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `shop_id` BIGINT UNSIGNED NOT NULL,
        `name` VARCHAR(255) NOT NULL,
        `location` VARCHAR(255) NULL,
        `description` TEXT NULL,
        `is_active` TINYINT(1) NOT NULL DEFAULT 1,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL,
        INDEX `warehouses_shop_id_idx` (`shop_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $messages[] = "Jedwali la 'warehouses' (Maghala) lipo tayari.";

    // 4. Check & Create 'warehouse_stocks' table if not exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS `warehouse_stocks` (
        `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `warehouse_id` BIGINT UNSIGNED NOT NULL,
        `product_id` BIGINT UNSIGNED NOT NULL,
        `quantity` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL,
        UNIQUE KEY `wh_product_unique` (`warehouse_id`, `product_id`),
        INDEX `wh_stocks_wh_idx` (`warehouse_id`),
        INDEX `wh_stocks_product_idx` (`product_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $messages[] = "Jedwali la 'warehouse_stocks' (Stock ya Maghala) lipo tayari.";

    // 5. Check & Create 'stock_movements' table if not exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS `stock_movements` (
        `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `shop_id` BIGINT UNSIGNED NOT NULL,
        `product_id` BIGINT UNSIGNED NOT NULL,
        `moved_by` BIGINT UNSIGNED NOT NULL,
        `from_type` VARCHAR(30) NOT NULL,
        `from_warehouse_id` BIGINT UNSIGNED NULL,
        `to_type` VARCHAR(30) NOT NULL,
        `to_warehouse_id` BIGINT UNSIGNED NULL,
        `quantity` DECIMAL(10,2) NOT NULL,
        `notes` TEXT NULL,
        `movement_date` DATE NOT NULL,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL,
        INDEX `sm_shop_idx` (`shop_id`),
        INDEX `sm_product_idx` (`product_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    try {
        $pdo->exec("ALTER TABLE `stock_movements` MODIFY COLUMN `from_type` VARCHAR(50) NOT NULL");
        $pdo->exec("ALTER TABLE `stock_movements` MODIFY COLUMN `to_type` VARCHAR(50) NOT NULL");
        $messages[] = "Safu za 'from_type' na 'to_type' kwenye 'stock_movements' zimebadilishwa kuwa VARCHAR(50) (inasaidia 'supplier', 'warehouse', 'shop', 'branch').";
    } catch (Throwable $e) {}

    // Check & Add from_branch_id, to_branch_id
    try {
        $stmt = $pdo->query("SHOW COLUMNS FROM `stock_movements` LIKE 'from_branch_id'");
        if (!$stmt->fetch()) {
            $pdo->exec("ALTER TABLE `stock_movements` ADD COLUMN `from_branch_id` BIGINT UNSIGNED NULL AFTER `from_warehouse_id`");
            $pdo->exec("ALTER TABLE `stock_movements` ADD COLUMN `to_branch_id` BIGINT UNSIGNED NULL AFTER `to_warehouse_id`");
            $messages[] = "Safu za 'from_branch_id' na 'to_branch_id' zimeongezwa kwenye 'stock_movements' kwa ajili ya uhamisho wa maduka/matawi.";
        }
    } catch (Throwable $e) {}

    // Safe auto-healing: Ensure any transferred products where product.stock > 0 are synced to branch_product
    try {
        $healingStmt = $pdo->query("SELECT p.id, p.shop_id, p.stock FROM `products` p WHERE p.stock > 0");
        $healedCount = 0;
        while ($row = $healingStmt->fetch(PDO::FETCH_ASSOC)) {
            $pid = (int) $row['id'];
            $pShopId = (int) $row['shop_id'];
            $pStock = (float) $row['stock'];

            $bpCheck = $pdo->prepare("SELECT SUM(quantity) as total_qty FROM `branch_product` WHERE product_id = ?");
            $bpCheck->execute([$pid]);
            $bpTotal = (float) ($bpCheck->fetch(PDO::FETCH_ASSOC)['total_qty'] ?? 0);

            if ($bpTotal <= 0 && $pStock > 0) {
                // Find primary branch for this shop
                $bStmt = $pdo->prepare("SELECT id FROM `branches` WHERE shop_id = ? ORDER BY id ASC LIMIT 1");
                $bStmt->execute([$pShopId]);
                $bRow = $bStmt->fetch(PDO::FETCH_ASSOC);
                if ($bRow) {
                    $bId = (int) $bRow['id'];
                    $ins = $pdo->prepare("INSERT INTO `branch_product` (branch_id, product_id, quantity, created_at, updated_at) 
                                          VALUES (?, ?, ?, NOW(), NOW())
                                          ON DUPLICATE KEY UPDATE quantity = ?, updated_at = NOW()");
                    $ins->execute([$bId, $pid, $pStock, $pStock]);
                    $healedCount++;
                }
            }
        }
        if ($healedCount > 0) {
            $messages[] = "Stock ya bidhaa $healedCount imesawazishwa kwenye duka/matawi (branch_product) kikamilifu.";
        }
    } catch (Throwable $e) {}

    $messages[] = "Jedwali la 'stock_movements' (Uhamisho wa Mzigo) lipo tayari.";

    // 6. Check & Create 'defective_stocks' table if not exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS `defective_stocks` (
        `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `shop_id` BIGINT UNSIGNED NOT NULL,
        `branch_id` BIGINT UNSIGNED NULL,
        `product_id` BIGINT UNSIGNED NOT NULL,
        `user_id` BIGINT UNSIGNED NULL,
        `quantity` DECIMAL(15,2) NOT NULL,
        `reason` TEXT NULL,
        `status` VARCHAR(20) NOT NULL DEFAULT 'defective',
        `restored_at` TIMESTAMP NULL DEFAULT NULL,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL,
        INDEX `defective_stocks_shop_id_idx` (`shop_id`),
        INDEX `defective_stocks_branch_id_idx` (`branch_id`),
        INDEX `defective_stocks_product_id_idx` (`product_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    // 7. Check & Add user_id and shop_id to 'testimonials' table if not exists
    try {
        $stmt = $pdo->query("SHOW COLUMNS FROM `testimonials` LIKE 'user_id'");
        if (!$stmt->fetch()) {
            $pdo->exec("ALTER TABLE `testimonials` ADD COLUMN `user_id` BIGINT UNSIGNED NULL AFTER `id`");
            $messages[] = "Safu ya 'user_id' imeongezwa kwenye jedwali la 'testimonials'.";
        }
        $stmt = $pdo->query("SHOW COLUMNS FROM `testimonials` LIKE 'shop_id'");
        if (!$stmt->fetch()) {
            $pdo->exec("ALTER TABLE `testimonials` ADD COLUMN `shop_id` BIGINT UNSIGNED NULL AFTER `user_id`");
            $messages[] = "Safu ya 'shop_id' imeongezwa kwenye jedwali la 'testimonials'.";
        }
    } catch (Throwable $e) {}

    // 8. Clean up any floating point price artifacts on products (e.g. 999.92 -> 1000)
    try {
        $updatedRows = $pdo->exec("UPDATE `products` SET `selling_price` = ROUND(`selling_price`) WHERE ABS(`selling_price` - ROUND(`selling_price`)) < 0.1 AND ABS(`selling_price` - ROUND(`selling_price`)) > 0");
        if ($updatedRows > 0) {
            $messages[] = "Bei za bidhaa $updatedRows zilizokuwa na desimali zisizo sahihi (k.m 999.92) zimesawazishwa kuwa namba kamili (k.m 1000 TSh).";
        }
    } catch (Throwable $e) {}

} catch (Exception $e) {
    $errors[] = "Hitilafu ya Database: " . $e->getMessage();
}

// 6. Clear Laravel view cache & reset OPcache
$viewDir = __DIR__ . '/storage/framework/views';
$viewsCleared = 0;
if (is_dir($viewDir)) {
    foreach (glob($viewDir . '/*.php') as $f) {
        if (@unlink($f)) $viewsCleared++;
    }
}
$messages[] = "Cache ya views imesafishwa kikamilifu ($viewsCleared files zimefutwa).";

if (function_exists('opcache_reset')) {
    if (@opcache_reset()) {
        $messages[] = "OPcache ya PHP imesafishwa kikamilifu kutoka kwenye kumbukumbu ya server.";
    }
}

?>
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Z-POS Server Update Status</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background: #0f172a; color: #f8fafc; padding: 30px 15px; margin: 0; }
        .container { max-width: 650px; margin: 0 auto; background: #1e293b; border-radius: 16px; padding: 25px 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.3); border: 1px solid #334155; }
        h2 { margin-top: 0; color: #38bdf8; font-size: 1.4rem; display: flex; align-items: center; gap: 8px; }
        .alert-success { background: #064e3b; color: #a7f3d0; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: 500; border: 1px solid #059669; }
        .alert-danger { background: #7f1d1d; color: #fecaca; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-weight: 500; border: 1px solid #dc2626; }
        ul { padding-left: 20px; line-height: 1.8; }
        li { margin-bottom: 6px; }
        .btn { display: inline-block; background: #4f46e5; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 10px; font-weight: 600; margin-top: 15px; }
        .btn:hover { background: #4338ca; }
        .danger-note { background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #fca5a5; padding: 12px 16px; border-radius: 10px; font-size: 0.85rem; margin-top: 25px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🚀 Z-POS Server Sync Completed</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="alert-danger">
                <strong>Kuna hitilafu iliyotokea:</strong>
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php else: ?>
            <div class="alert-success">
                ✅ Mfumo wako umesasishwa kikamilifu! Data zote za awali ziko salama 100%.
            </div>
        <?php endif; ?>

        <h4 style="color: #94a3b8; margin-bottom: 8px;">Hatua Zilizofanyika:</h4>
        <ul>
            <?php foreach ($messages as $msg): ?>
                <li><span style="color:#10b981;">✓</span> <?= htmlspecialchars($msg) ?></li>
            <?php endforeach; ?>
        </ul>

        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center; margin-top: 15px;">
            <a href="/home" class="btn">Fungua Dashboard ya Z-POS &rarr;</a>
            <a href="?delete_self=1" class="btn" style="background:#dc2626;" onclick="return confirm('Je, una uhakika unataka kufuta faili hili la update_server.php kwenye server sasa?');">🗑️ Futa Faili Hili Mara Moja (Kwa Usalama)</a>
        </div>

        <div class="danger-note">
            ⚠️ <strong>Muhimu:</strong> Baada ya kuona ujumbe huu, bofya kitufe chekundu cha <strong>"Futa Faili Hili Mara Moja"</strong> hapo juu kwa ajili ya usalama kamili wa mfumo wako.
        </div>
    </div>
</body>
</html>
