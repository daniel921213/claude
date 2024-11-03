<?php include "../inc/dbinfo.inc"; session_start(); ?>
<html lang="zh">
<head>
    <title>会员登录</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function showAlert() {
            alert("登录成功！");
        }
    </script>
</head>
<body>

<h1>会员登录</h1>

<!-- 菜单 -->
<div class="menu">
    <a href="index.php">首页</a>
    <a href="ticket_query.php">票查询</a>
    <a href="member_login.php">会员登录</a>
    <a href="member_modify.php">会员修改</a>
    <a href="booking.php">订票</a>
</div>

<!-- 显示用户名 -->
<div style="text-align: right; padding: 10px;">
    <?php
    if (isset($_SESSION['username'])) {
        echo "欢迎, " . htmlspecialchars($_SESSION['username']);
    }
    ?>
</div>

<div class="form-container">
    <form action="member_login.php" method="POST">
        <label for="username">用户名:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">密码:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="登录">
    </form>

    <!-- 注册按钮 -->
    <p>还没有账户？ <a href="register.php">点击这里注册</a></p>

    <?php
    // 处理登录表单
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // 连接数据库
        $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
        
        if (mysqli_connect_errno()) {
            echo "连接 MySQL 失败: " . mysqli_connect_error();
            exit();
        }

        $database = mysqli_select_db($connection, DB_DATABASE);

        // 获取表单数据并进行处理
        $username = mysqli_real_escape_string($connection, $_POST['username']);
        $password = $_POST['password'];

        // 查询用户
        $query = "SELECT password FROM USERS WHERE username='$username'";
        $result = mysqli_query($connection, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            // 验证密码
            if (password_verify($password, $row['password'])) {
                // 登录成功，设置 session 并弹出提示
                $_SESSION['username'] = $username;
                echo "<script>showAlert();</script>";
            } else {
                echo "<p>密码错误。</p>";
            }
        } else {
            echo "<p>用户名不存在。</p>";
        }

        // 关闭数据库连接
        mysqli_close($connection);
    }
    ?>
</div>

</body>
</html>
