<?php
/**
 * 管理员账号查询脚本
 * 用于查询当前系统中的管理员账号信息
 */
require_once 'config/database.php';

echo "<h2>管理员账号信息查询</h2>";
echo "<style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
    .info-box { background: white; padding: 20px; margin: 10px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .success { color: #28a745; }
    .error { color: #dc3545; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background: #6b7280; color: white; }
</style>";

// 查询 wp802 数据库中的管理员
echo "<div class='info-box'>";
echo "<h3>1. wp802 数据库 (登录/注册数据库) 中的管理员账号：</h3>";
try {
    // 尝试查询 ac_users 表
    try {
        $stmt = $wp885_pdo->query("SELECT user_id, email, first_name, last_name, role, email_verified FROM ac_users WHERE role = 'admin'");
        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($admins)) {
            echo "<table><tr><th>ID</th><th>Email</th><th>姓名</th><th>角色</th><th>已验证</th></tr>";
            foreach ($admins as $admin) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($admin['user_id']) . "</td>";
                echo "<td>" . htmlspecialchars($admin['email']) . "</td>";
                echo "<td>" . htmlspecialchars(($admin['first_name'] ?? '') . ' ' . ($admin['last_name'] ?? '')) . "</td>";
                echo "<td>" . htmlspecialchars($admin['role']) . "</td>";
                echo "<td>" . ($admin['email_verified'] ? '是' : '否') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='error'>ac_users 表中没有找到管理员账号</p>";
        }
    } catch (PDOException $e) {
        echo "<p class='error'>ac_users 表不存在，尝试查询 users 表...</p>";
        
        // 尝试查询 users 表
        try {
            $stmt = $wp885_pdo->query("SELECT user_id, email, first_name, last_name, role, email_verified FROM users WHERE role = 'admin'");
            $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($admins)) {
                echo "<table><tr><th>ID</th><th>Email</th><th>姓名</th><th>角色</th><th>已验证</th></tr>";
                foreach ($admins as $admin) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($admin['user_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($admin['email']) . "</td>";
                    echo "<td>" . htmlspecialchars(($admin['first_name'] ?? '') . ' ' . ($admin['last_name'] ?? '')) . "</td>";
                    echo "<td>" . htmlspecialchars($admin['role']) . "</td>";
                    echo "<td>" . ($admin['email_verified'] ? '是' : '否') . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p class='error'>users 表中没有找到管理员账号</p>";
            }
        } catch (PDOException $e2) {
            echo "<p class='error'>users 表也不存在: " . htmlspecialchars($e2->getMessage()) . "</p>";
        }
    }
} catch (Exception $e) {
    echo "<p class='error'>查询错误: " . htmlspecialchars($e->getMessage()) . "</p>";
}
echo "</div>";

// 查询 513week7 数据库中的管理员
echo "<div class='info-box'>";
echo "<h3>2. 513week7 数据库 (其他页面数据库) 中的管理员账号：</h3>";
try {
    $stmt = $pdo->query("SELECT user_id, email, first_name, last_name, role, email_verified FROM users WHERE role = 'admin'");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($admins)) {
        echo "<table><tr><th>ID</th><th>Email</th><th>姓名</th><th>角色</th><th>已验证</th></tr>";
        foreach ($admins as $admin) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($admin['user_id']) . "</td>";
            echo "<td>" . htmlspecialchars($admin['email']) . "</td>";
            echo "<td>" . htmlspecialchars(($admin['first_name'] ?? '') . ' ' . ($admin['last_name'] ?? '')) . "</td>";
            echo "<td>" . htmlspecialchars($admin['role']) . "</td>";
            echo "<td>" . ($admin['email_verified'] ? '是' : '否') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='error'>没有找到管理员账号</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>查询错误: " . htmlspecialchars($e->getMessage()) . "</p>";
}
echo "</div>";

// 显示已知的管理员账号信息
echo "<div class='info-box'>";
echo "<h3>3. 根据代码文件中的信息，已知的管理员账号：</h3>";
echo "<table>";
echo "<tr><th>数据库</th><th>Email</th><th>密码</th><th>姓名</th></tr>";
echo "<tr><td>513week7</td><td>3442835688@qq.com</td><td>hjy20041206</td><td>Junyi Hu</td></tr>";
echo "</table>";
echo "<p class='success'><strong>提示：</strong>如果管理员账号不存在，请运行 create_admin.php 创建管理员账号。</p>";
echo "</div>";

?>

