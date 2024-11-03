<?php include "../inc/dbinfo.inc"; ?>
<html>
<head>
    <title>Booking System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        .menu {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: left;
        }
        .menu a {
            color: white;
            margin: 10px;
            text-decoration: none;
        }
        .menu a:hover {
            text-decoration: underline;
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
  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* Ensure that the BOOKINGS table exists. */
  VerifyBookingsTable($connection, DB_DATABASE);

  /* If input fields are populated, add a row to the BOOKINGS table. */
  $customer_name = htmlentities($_POST['NAME']);
  $customer_address = htmlentities($_POST['ADDRESS']);

  if (strlen($customer_name) || strlen($customer_address)) {
    AddBooking($connection, $customer_name, $customer_address);
  }
?>

<!-- Input form -->
<div class="form-container">
    <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
        <table>
            <tr>
                <td>NAME</td>
                <td>ADDRESS</td>
            </tr>
            <tr>
                <td>
                    <input type="text" name="NAME" maxlength="45" size="30" />
                </td>
                <td>
                    <input type="text" name="ADDRESS" maxlength="90" size="60" />
                </td>
                <td>
                    <input type="submit" value="Book Ticket" />
                </td>
            </tr>
        </table>
    </form>
</div>

<!-- Display table data. -->
<table>
    <tr>
        <th>ID</th>
        <th>NAME</th>
        <th>ADDRESS</th>
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

<!-- Clean up. -->
<?php
  mysqli_free_result($result);
  mysqli_close($connection);
?>

</body>
</html>

<?php

/* Add a booking to the table. */
function AddBooking($connection, $name, $address) {
    $n = mysqli_real_escape_string($connection, $name);
    $a = mysqli_real_escape_string($connection, $address);

    $query = "INSERT INTO BOOKINGS (NAME, ADDRESS) VALUES ('$n', '$a');";

    if(!mysqli_query($connection, $query)) echo("<p>Error adding booking data.</p>");
}

/* Check whether the BOOKINGS table exists and, if not, create it. */
function VerifyBookingsTable($connection, $dbName) {
    if(!TableExists("BOOKINGS", $connection, $dbName)) {
        $query = "CREATE TABLE BOOKINGS (
            ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            NAME VARCHAR(45),
            ADDRESS VARCHAR(90)
        )";

        if(!mysqli_query($connection, $query)) echo("<p>Error creating table.</p>");
    }
}

/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName) {
    $t = mysqli_real_escape_string($connection, $tableName);
    $d = mysqli_real_escape_string($connection, $dbName);

    $checktable = mysqli_query($connection,
        "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

    return mysqli_num_rows($checktable) > 0;
}
?>
