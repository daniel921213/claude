<?php include "../inc/dbinfo.inc"; ?>
<html lang="zh">
<head>
    <title>会员注册</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h1>会员注册</h1>

<!-- 菜单 -->
<div class="menu">
    <a href="index.php">首页</a>
    <a href="ticket_query.php">票查询</a>
    <a href="member_login.php">会员登录</a>
    <a href="member_modify.php">会员修改</a>
    <a href="booking.php">订票</a>
</div>

<div class="form-container">
    <form action="register.php" method="POST">
        <label for="username">用户名:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">密码:</label>
        <input type="password" id="password" name="password" required><br><br>
        <label for="email">邮箱:</label>
        <input type="email" id="email" name="email" required><br><br>
        <input type="submit" value="注册">
    </form>

    <?php
    // 处理注册表单
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // 连接数据库
        $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
        
        if (mysqli_connect_errno()) {
            echo "连接 MySQL 失败: " . mysqli_connect_error();
            exit();
        }

        $database = mysqli_select_db($connection, DB_DATABASE);

        // 确保用户表存在
        CreateUserTable($connection);

        // 获取表单数据并进行处理
        $username = mysqli_real_escape_string($connection, $_POST['username']);
        $password = mysqli_real_escape_string($connection, $_POST['password']);
        $email = mysqli_real_escape_string($connection, $_POST['email']);

        // 哈希密码
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 插入用户数据
        $query = "INSERT INTO USERS (username, password, email) VALUES ('$username', '$hashed_password', '$email')";
        
        if (mysqli_query($connection, $query)) {
            echo "<p>注册成功！您现在可以 <a href='member_login.php'>登录</a>。</p>";
        } else {
            echo "<p>注册失败: " . mysqli_error($connection) . "</p>";
        }

        // 关闭数据库连接
        mysqli_close($connection);
    }

    // 创建用户表的函数
    function CreateUserTable($connection) {
        $query = "CREATE TABLE IF NOT EXISTS USERS (
            ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE
        )";
        
        if (!mysqli_query($connection, $query)) {
            echo "<p>创建用户表失败: " . mysqli_error($connection) . "</p>";
        }
    }
    ?>
</div>

</body>
</html>
