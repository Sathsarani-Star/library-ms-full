<?php
include "includes/db_connect.php";

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['clear'])) {
    $id = $_GET['clear'];
    mysqli_query($conn, "UPDATE fees SET status='Paid' WHERE fee_id=$id");
    header("Location: fees.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fee Collection</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="other-bg">

    <?php include "includes/sidebar.php"; ?>

    <div class="main">
        <h1>Fee Collection</h1>

        <?php
        $get_unpaid = mysqli_query($conn, "SELECT SUM(total_fee) FROM fees WHERE status='Unpaid'");
        $unpaid_row = mysqli_fetch_row($get_unpaid);
        $unpaid_total = $unpaid_row[0];
        if ($unpaid_total == "") {
            $unpaid_total = 0;
        }

        $get_paid = mysqli_query($conn, "SELECT SUM(total_fee) FROM fees WHERE status='Paid'");
        $paid_row = mysqli_fetch_row($get_paid);
        $paid_total = $paid_row[0];
        if ($paid_total == "") {
            $paid_total = 0;
        }

        echo "<div class='cards'>";
        echo "<div class='black-card'><p>Unpaid Total</p><h3>Rs.$unpaid_total</h3></div>";
        echo "<div class='black-card'><p>Paid Total</p><h3>Rs.$paid_total</h3></div>";
        echo "</div>";
        ?>

        <table>
            <tr>
                <th>Fee ID</th>
                <th>Issue ID</th>
                <th>Member ID</th>
                <th>Overdue Days</th>
                <th>Total Fee</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php
            $all_fees = mysqli_query($conn, "SELECT * FROM fees ORDER BY fee_id DESC");

            while ($fee = mysqli_fetch_assoc($all_fees)) {
                echo "<tr>";
                echo "<td>" . $fee['fee_id'] . "</td>";
                echo "<td>" . $fee['issue_id'] . "</td>";
                echo "<td>" . $fee['member_id'] . "</td>";
                echo "<td>" . $fee['overdue_days'] . "</td>";
                echo "<td>Rs." . $fee['total_fee'] . "</td>";
                echo "<td>" . $fee['status'] . "</td>";
                echo "<td>";

                if ($fee['status'] == 'Unpaid') {
                    echo "<a href='fees.php?clear=" . $fee['fee_id'] . "' class='btn btn-add'>Mark as Paid</a>";
                } else {
                    echo "Cleared";
                }

                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

</body>
</html>
