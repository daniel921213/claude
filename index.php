<?php include "../inc/dbinfo.inc"; ?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>資訊頁面</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h1>歡迎來到訂票系統</h1>

<!-- 菜單 -->
<div class="menu">
    <a href="index.php">首頁</a>
    <a href="ticket_query.php">票務查詢</a>
    <a href="member_login.php">會員登入</a>
    <a href="member_modify.php">會員修改</a>
    <a href="booking.php">訂票</a>
</div>
<!-- 显示用户名 -->
<div style="text-align: right; padding: 10px;">
    <?php
    if (isset($_SESSION['username'])) {
        echo "欢迎, " . htmlspecialchars($_SESSION['username']);
    }
    ?>
</div>

<!-- 資訊區域 -->
<div class="info-section">
    <h2>資訊</h2>
    <p>歡迎來到我們的訂票系統。在這裡，您可以找到有關我們服務的各種資訊。</p>
    <h3>提供的服務</h3>
    <ul>
        <li>線上訂票</li>
        <li>票務查詢與修改</li>
        <li>會員服務</li>
    </ul>
    <h3>聯絡我們</h3>
    <p>如果您有任何問題，請隨時<a href="contact.php">聯絡我們</a>。</p>
</div>

</body>
</html>
