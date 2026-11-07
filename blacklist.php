<?php
include "includes/db_connect.php";

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if (isset($_GET['unblock'])) {
    $id = $_GET['unblock'];

    $get_fee = mysqli_query($conn, "SELECT SUM(total_fee) FROM fees WHERE member_id=$id AND status='Unpaid'");
    $fee_row = mysqli_fetch_row($get_fee);
    $total_fee = $fee_row[0];
    if ($total_fee == "") {
        $total_fee = 0;
    }

    if ($total_fee == 0) {
        mysqli_query($conn, "UPDATE members SET status='Active' WHERE member_id=$id");
    } else {
        $error = "Cannot unblock! Fees due: Rs.$total_fee - Clear fees first.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Blacklist</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="other-bg">

    <?php include "includes/sidebar.php"; ?>

    <div class="main">
        <h1>Blacklisted Members</h1>

        <?php
        if ($error != "") {
            echo "<p style='font-weight:bold; color:red;'>$error</p>";
        }
        ?>

        <table>
            <tr>
                <th>Member ID</th>
                <th>Code</th>
                <th>Name</th>
                <th>Reason</th>
                <th>Fees Due</th>
                <th>Action</th>
            </tr>

            <?php
            $all_blocked = mysqli_query($conn, "SELECT * FROM members WHERE status='Blocked'");

            while ($member = mysqli_fetch_assoc($all_blocked)) {
                $member_id = $member['member_id'];

                $get_fee = mysqli_query($conn, "SELECT SUM(total_fee) FROM fees WHERE member_id=$member_id AND status='Unpaid'");
                $fee_row = mysqli_fetch_row($get_fee);
                $fee_due = $fee_row[0];
                if ($fee_due == "") {
                    $fee_due = 0;
                }

                echo "<tr>";
                echo "<td>" . $member['member_id'] . "</td>";
                echo "<td>" . $member['member_code'] . "</td>";
                echo "<td>" . $member['full_name'] . "</td>";
                echo "<td>Blocked</td>";
                echo "<td>Rs." . $fee_due . "</td>";
                echo "<td><a href='blacklist.php?unblock=" . $member['member_id'] . "' class='btn btn-add'>Unblock</a></td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

</body>
</html>
