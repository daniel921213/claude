<?php include "../inc/dbinfo.inc"; ?>
<html>
<head>
    <title>会员登录</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .menu {
            background-color: #333;
            color: white;
            padding: 10px;
        }
        .menu a {
            color: white;
            margin: 10px;
            text-decoration: none;
        }
        .form-container {
            margin-top: 20px;
        }
    </style>
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

<div class="form-container">
    <form action="member_login.php" method="POST">
        <label for="username">用户名:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">密码:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="登录">
    </form>
</div>

</body>
</html>
