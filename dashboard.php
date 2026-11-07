<?php
include "includes/db_connect.php";

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$total_books   = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM books"))[0];
$available     = mysqli_fetch_row(mysqli_query($conn, "SELECT SUM(available_qty) FROM books"))[0];
$issued        = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM issued_books WHERE status='Issued'"))[0];
$total_members = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM members"))[0];
$blocked       = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM members WHERE status='Blocked'"))[0];

if ($available == "") {
    $available = 0;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css?v=2">
</head>

<body class="dashboard-bg">

    <?php include "includes/sidebar.php"; ?>

    <div class="main">

        <h1 class="dashboard-title"><b>Dashboard</b></h1>

        <div class="cards">

            <div class="black-card">
                <p>Total Books</p>
                <h3><?php echo $total_books; ?></h3>
            </div>

            <div class="black-card">
                <p>Available</p>
                <h3><?php echo $available; ?></h3>
            </div>

            <div class="black-card">
                <p>Issued</p>
                <h3><?php echo $issued; ?></h3>
            </div>

            <div class="black-card">
                <p>Members</p>
                <h3><?php echo $total_members; ?></h3>
            </div>

            <div class="black-card">
                <p>Blocked</p>
                <h3><?php echo $blocked; ?></h3>
            </div>

        </div>

        <div class="recent-box">

            <h2>Recently Issued Books</h2>

            <table>
                <tr>
                    <th>Issue ID</th>
                    <th>Book ID</th>
                    <th>Member ID</th>
                    <th>Issue Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>

                <?php
                $recent = mysqli_query($conn, "SELECT * FROM issued_books ORDER BY issue_id DESC LIMIT 5");

                while ($row = mysqli_fetch_assoc($recent)) {
                    echo "<tr>";
                    echo "<td>" . $row['issue_id'] . "</td>";
                    echo "<td>" . $row['book_id'] . "</td>";
                    echo "<td>" . $row['member_id'] . "</td>";
                    echo "<td>" . $row['issue_date'] . "</td>";
                    echo "<td>" . $row['due_date'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>

        </div>

    </div>

</body>
</html>