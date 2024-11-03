<?php include "../inc/dbinfo.inc"; ?>
<html>
<head>
    <title>订单更改</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function showAlert(message) {
            if (message) {
                alert(message);
            }
        }
    </script>
</head>
<body>

<h1>订单更改</h1>

<!-- 菜单 -->
<div class="menu">
    <a href="index.php">首页</a>
    <a href="ticket_query.php">票查询</a>
    <a href="member_login.php">会员登录</a>
    <a href="order_modify.php">订单更改</a>
    <a href="booking.php">订票</a>
</div>

<?php
// 连接到 MySQL 并选择数据库.
$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

if (mysqli_connect_errno()) echo "连接 MySQL 失败: " . mysqli_connect_error();

$database = mysqli_select_db($connection, DB_DATABASE);

// 处理表单提交
$customer_name = htmlentities($_POST['NAME'] ?? '');
$new_address = htmlentities($_POST['NEW_ADDRESS'] ?? '');
$action = $_POST['ACTION'] ?? '';
$message = '';

if ($action === 'update' && !empty($customer_name) && !empty($new_address)) {
    UpdateBooking($connection, $customer_name, $new_address);
    $message = "地址更新成功！";
} elseif ($action === 'delete' && !empty($customer_name)) {
    DeleteBooking($connection, $customer_name);
    $message = "预订已删除！";
}

// 清理
mysqli_close($connection);
?>

<div class="form-container">
    <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="POST" onsubmit="showAlert('<?php echo addslashes($message); ?>');">
        <label for="name">姓名:</label>
        <input type="text" name="NAME" required maxlength="45">
        <br><br>
        <label for="new_address">新地址:</label>
        <input type="text" name="NEW_ADDRESS" maxlength="90">
        <br><br>
        <input type="radio" name="ACTION" value="update"> 更新地址
        <input type="radio" name="ACTION" value="delete"> 删除预订
        <br><br>
        <input type="submit" value="提交">
    </form>
</div>

<script>
    // 直接调用 showAlert 函数，确保在页面加载后显示消息
    showAlert("<?php echo addslashes($message); ?>");
</script>

</body>
</html>

<?php

/* 更新预订地址. */
function UpdateBooking($connection, $name, $address) {
    $n = mysqli_real_escape_string($connection, $name);
    $a = mysqli_real_escape_string($connection, $address);

    $query = "UPDATE BOOKINGS SET ADDRESS = '$a' WHERE NAME = '$n'";

    if(!mysqli_query($connection, $query)) echo("<p>更新预订数据时出错.</p>");
}

/* 删除预订. */
function DeleteBooking($connection, $name) {
    $n = mysqli_real_escape_string($connection, $name);

    $query = "DELETE FROM BOOKINGS WHERE NAME = '$n'";

    if(!mysqli_query($connection, $query)) echo("<p>删除预订数据时出错.</p>");
}
?>
