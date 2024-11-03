<?php include "../inc/dbinfo.inc"; session_start(); ?>
<html>
<head>
    <title>订票</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function showAlert() {
            alert("订票成功！");
        }
    </script>
</head>
<body>

<h1>订票</h1>

<!-- 菜单 -->
<div class="menu">
    <a href="index.php">首页</a>
    <a href="ticket_query.php">票查询</a>
    <a href="member_login.php">会员登录</a>
    <a href="member_modify.php">会员修改</a>
    <a href="booking.php">订票</a>
</div>

<?php
  /* 连接到 MySQL 并选择数据库. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "连接 MySQL 失败: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* 确保 BOOKINGS 表存在. */
  VerifyBookingsTable($connection, DB_DATABASE);

  /* 如果输入字段被填充，则将一行添加到 BOOKINGS 表. */
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $customer_name = htmlentities($_POST['NAME']);
      $customer_phone = htmlentities($_POST['PHONE']);
      $customer_address = htmlentities($_POST['ADDRESS']);
      $customer_email = htmlentities($_POST['EMAIL']);

      if (strlen($customer_name) || strlen($customer_phone) || strlen($customer_address) || strlen($customer_email)) {
          AddBooking($connection, $customer_name, $customer_phone, $customer_address, $customer_email);
          echo "<script>showAlert();</script>";  // 弹出成功提示
      }
  }
?>

<div class="form-container">
    <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
        <label for="name">姓名:</label>
        <input type="text" name="NAME" required maxlength="45">
        <br><br>
        <label for="phone">电话号码:</label>
        <input type="text" name="PHONE" required maxlength="15">
        <br><br>
        <label for="address">地址:</label>
        <input type="text" name="ADDRESS" required maxlength="90">
        <br><br>
        <label for="email">电子邮件:</label>
        <input type="email" name="EMAIL" required maxlength="100">
        <br><br>
        <input type="submit" value="订票">
    </form>
</div>

<!-- 显示已预订的票务信息 -->
<h2>已预订的票务</h2>
<table>
    <tr>
        <th>ID</th>
        <th>姓名</th>
        <th>电话号码</th>
        <th>地址</th>
        <th>电子邮件</th>
    </tr>

<?php
$result = mysqli_query($connection, "SELECT * FROM BOOKINGS");

while($query_data = mysqli_fetch_row($result)) {
    echo "<tr>";
    echo "<td>", $query_data[0], "</td>",
         "<td>", $query_data[1], "</td>",
         "<td>", $query_data[2], "</td>",
         "<td>", $query_data[3], "</td>",
         "<td>", $query_data[4], "</td>";
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

<?php

/* 向表中添加预订. */
function AddBooking($connection, $name, $phone, $address, $email) {
    $n = mysqli_real_escape_string($connection, $name);
    $p = mysqli_real_escape_string($connection, $phone);
    $a = mysqli_real_escape_string($connection, $address);
    $e = mysqli_real_escape_string($connection, $email);

    $query = "INSERT INTO BOOKINGS (NAME, PHONE, ADDRESS, EMAIL) VALUES ('$n', '$p', '$a', '$e');";

    if(!mysqli_query($connection, $query)) echo("<p>添加预订数据时出错.</p>");
}

/* 检查 BOOKINGS 表是否存在，如不存在则创建它. */
function VerifyBookingsTable($connection, $dbName) {
    if(!TableExists("BOOKINGS", $connection, $dbName)) {
        $query = "CREATE TABLE BOOKINGS (
            ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            NAME VARCHAR(45),
            PHONE VARCHAR(15),
            ADDRESS VARCHAR(90),
            EMAIL VARCHAR(100)
        )";

        if(!mysqli_query($connection, $query)) echo("<p>创建表时出错.</p>");
    }
}

/* 检查表是否存在. */
function TableExists($tableName, $connection, $dbName) {
    $t = mysqli_real_escape_string($connection, $tableName);
    $d = mysqli_real_escape_string($connection, $dbName);

    $checktable = mysqli_query($connection,
        "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

    return mysqli_num_rows($checktable) > 0;
}
?>
