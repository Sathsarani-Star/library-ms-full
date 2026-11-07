<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "library_db");
if (!$conn) {
    die("Connection failed");
}
?>
