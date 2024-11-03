<?php include "../inc/dbinfo.inc"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking System</title>
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

<h1>Booking System</h1>

<!-- Menu -->
<div class="menu">
    <a href="index.php">Home</a>
    <a href="ticket_query.php">Ticket Query</a>
    <a href="member_login.php">Member Login</a>
    <a href="member_modify.php">Member Modification</a>
    <a href="booking.php">Book Ticket</a>
</div>

<?php
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  VerifyBookingsTable($connection, DB_DATABASE);

  // 获取表单数据
  $customer_name = htmlentities($_POST['NAME']);
  $customer_address = htmlentities($_POST['ADDRESS']);
  $customer_phone = htmlentities($_POST['PHONE']); // 获取电话号码

  // 检查数据长度并插入
  if (strlen($customer_name) || strlen($customer_address) || strlen($customer_phone)) {
    AddBooking($connection, $customer_name, $customer_address, $customer_phone);
  }

  // 检查是否有删除请求
  if (isset($_POST['DELETE'])) {
      $id_to_delete = intval($_POST['ID']); // 获取要删除的 ID
      DeleteBooking($connection, $id_to_delete); // 调用删除函数
  }
?>

<!-- Input form -->
<div class="form-container">
    <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
        <table>
            <tr>
                <td>NAME</td>
                <td>ADDRESS</td>
                <td>PHONE</td> <!-- 添加电话号码列 -->
            </tr>
            <tr>
                <td>
                    <input type="text" name="NAME" maxlength="45" size="30" />
                </td>
                <td>
                    <input type="text" name="ADDRESS" maxlength="90" size="60" />
                </td>
                <td>
                    <input type="text" name="PHONE" maxlength="15" size="15" /> <!-- 添加电话号码输入框 -->
                </td>
                <td>
                    <input type="submit" value="Book Ticket" />
                </td>
            </tr>
        </table>
    </form>
</div>

<!-- Display table data -->
<table class="data-table">
    <tr>
        <th>ID</th>
        <th>NAME</th>
        <th>ADDRESS</th>
        <th>PHONE</th> <!-- 添加电话号码列 -->
        <th>ACTION</th> <!-- 添加操作列 -->
    </tr>

<?php
$result = mysqli_query($connection, "SELECT * FROM BOOKINGS");

while($query_data = mysqli_fetch_row($result)) {
    echo "<tr>";
    echo "<td>", $query_data[0], "</td>",
         "<td>", $query_data[1], "</td>",
         "<td>", $query_data[2], "</td>",
         "<td>", $query_data[3], "</td>",
         "<td><form action='' method='POST'>
                 <input type='hidden' name='ID' value='" . $query_data[0] . "' />
                 <input type='submit' name='DELETE' value='Delete' onclick='return confirm(\"Are you sure you want to delete this booking?\");' />
              </form></td>"; // 添加删除按钮
    echo "</tr>";
}
?>

</table>

<?php
  mysqli_free_result($result);
  mysqli_close($connection);
?>

</body>
</html>

<?php

function AddBooking($connection, $name, $address, $phone) { // 更新参数列表以包括电话
    $n = mysqli_real_escape_string($connection, $name);
    $a = mysqli_real_escape_string($connection, $address);
    $p = mysqli_real_escape_string($connection, $phone); // 处理电话号码

    $query = "INSERT INTO BOOKINGS (NAME, ADDRESS, PHONE) VALUES ('$n', '$a', '$p');"; // 更新插入查询

    if(!mysqli_query($connection, $query)) echo("<p>Error adding booking data.</p>");
}

function DeleteBooking($connection, $id) {
    $id = mysqli_real_escape_string($connection, $id); // 转义 ID
    $query = "DELETE FROM BOOKINGS WHERE ID = '$id'"; // 删除查询

    if (!mysqli_query($connection, $query)) {
        echo("<p>Error deleting booking data.</p>");
    }
}

function VerifyBookingsTable($connection, $dbName) {
    if(!TableExists("BOOKINGS", $connection, $dbName)) {
        $query = "CREATE TABLE BOOKINGS (
            ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            NAME VARCHAR(45),
            ADDRESS VARCHAR(90),
            PHONE VARCHAR(15) -- 添加电话号码字段
        )";

        if(!mysqli_query($connection, $query)) echo("<p>Error creating table.</p>");
    }
}

function TableExists($tableName, $connection, $dbName) {
    $t = mysqli_real_escape_string($connection, $tableName);
    $d = mysqli_real_escape_string($connection, $dbName);

    $checktable = mysqli_query($connection,
        "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

    return mysqli_num_rows($checktable) > 0;
}
?>
