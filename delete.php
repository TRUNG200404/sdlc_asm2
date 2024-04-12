<?php
// Assuming you have a record ID passed via GET or POST
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $id = $_GET["id"];

    $_servername = "localhost";
    $_username = "root";
    $_password = "";
    $_dbname = "your_database_name";


    // Prepare an SQL statement to delete the record
    $connection = new mysqli($_servername, $_username, $_password, $_dbname);

    $sql = "DELETE FROM your_table_name WHERE id = $id";
    $connection->query($sql);
}

header("location: /ASM2/home_page.php");
exit;
?>
