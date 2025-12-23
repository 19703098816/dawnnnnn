<?php
// Simple DB connectivity and tables check for Dawn's site
require_once __DIR__ . '/config/database.php';

header('Content-Type: text/plain; charset=utf-8');

echo "DB connectivity test for `if0_37969254_wp885`\n";

try {
    // $pdo is created by config/database.php
    if (!isset($pdo)) {
        throw new Exception('PDO connection ($pdo) is not set in config/database.php');
    }

    // Test basic query
    $stmt = $pdo->query('SELECT 1 AS ok');
    $res = $stmt->fetch();
    echo "Ping query result: " . ($res['ok'] ?? 'no result') . "\n\n";

    // List tables
    echo "Listing first 200 tables in the current database:\n";
    $tables = [];
    $stmt = $pdo->query("SHOW TABLES");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }
    if (empty($tables)) {
        echo "No tables found (database may be empty)\n";
    } else {
        $count = 0;
        foreach ($tables as $t) {
            $count++;
            echo " - $t\n";
            if ($count >= 200) break;
        }
    }

    // Show counts for key tables if exist
    $keyTables = ['users','products','orders','cart','artisan_profiles'];
    echo "\nKey table row counts (if table exists):\n";
    foreach ($keyTables as $kt) {
        $exists = in_array($kt, $tables);
        if ($exists) {
            $c = $pdo->query("SELECT COUNT(*) AS c FROM `$kt`")->fetch(PDO::FETCH_ASSOC);
            echo " - $kt: " . ($c['c'] ?? '0') . " rows\n";
        } else {
            echo " - $kt: (missing)\n";
        }
    }

    echo "\nAll checks completed. If any errors occurred, please copy the full output and share it.\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    if ($e instanceof PDOException) {
        echo "PDO Error Info: \n";
        print_r($e->errorInfo);
    }
}

?>