<?php include "../inc/dbinfo.inc"; ?>
<html>
<head>
    <title>票查询</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h1>票查询</h1>

<!-- 菜单 -->
<div class="menu">
    <a href="index.php">首页</a>
    <a href="ticket_query.php">票查询</a>
    <a href="member_login.php">会员登录</a>
    <a href="member_modify.php">会员修改</a>
    <a href="booking.php">订票</a>
</div>

<?php
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "连接 MySQL 失败: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);
?>

<!-- 显示表格数据. -->
<table>
    <tr>
        <th>ID</th>
        <th>姓名</th>
        <th>地址</th>
    </tr>

<?php
$result = mysqli_query($connection, "SELECT * FROM BOOKINGS");

while($query_data = mysqli_fetch_row($result)) {
    echo "<tr>";
    echo "<td>", $query_data[0], "</td>",
         "<td>", $query_data[1], "</td>",
         "<td>", $query_data[2], "</td>";
    echo "</tr>";
}
?>

</table>

<!-- 清理. -->
<?php
  mysqli_free_result($result);
  mysqli_close($connection);
?>

</body>
</html>
