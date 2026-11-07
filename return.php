<?php
include "includes/db_connect.php";

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if (isset($_POST['return'])) {
    $issue_id = $_POST['issue_id'];
    $return_date = $_POST['return_date'];

    $get_issue = mysqli_query($conn, "SELECT * FROM issued_books WHERE issue_id=$issue_id");
    $issue_data = mysqli_fetch_assoc($get_issue);

    $due_date = $issue_data['due_date'];
    $book_id = $issue_data['book_id'];
    $member_id = $issue_data['member_id'];

    // 1 day = 86400 seconds
    $overdue = (strtotime($return_date) - strtotime($due_date)) / 86400;
    if ($overdue < 0) {
        $overdue = 0;
    }

    $fee = $overdue * 10;

    mysqli_query($conn, "INSERT INTO return_books (issue_id, book_id, member_id, return_date, overdue_days, fee_charged) VALUES ($issue_id, $book_id, $member_id, '$return_date', $overdue, $fee)");
    mysqli_query($conn, "UPDATE issued_books SET return_date='$return_date', status='Returned' WHERE issue_id=$issue_id");
    mysqli_query($conn, "UPDATE books SET available_qty = available_qty + 1 WHERE book_id=$book_id");

    if ($fee > 0) {
        mysqli_query($conn, "INSERT INTO fees (issue_id, member_id, overdue_days, total_fee, status) VALUES ($issue_id, $member_id, $overdue, $fee, 'Unpaid')");
    }

    // Total fee calculation - sum of all unpaid fees for this member
    $get_total = mysqli_query($conn, "SELECT SUM(total_fee) FROM fees WHERE member_id=$member_id AND status='Unpaid'");
    $total_row = mysqli_fetch_row($get_total);
    $total_fee = $total_row[0];
    if ($total_fee == "") {
        $total_fee = 0;
    }

    $error = "Returned! Overdue: $overdue days, This Fee: Rs.$fee, Total Unpaid: Rs.$total_fee";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Return Books</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="other-bg">
    <?php include "includes/sidebar.php"; ?>
    <div class="main">
        <div class="form-center-wrap">
            <h1>Return Books</h1>
            <?php if ($error != "") { echo "<p style='font-weight:bold; color:green;'>$error</p>"; } ?>

            <form method="POST" class="form-card">
                <label>Issue ID</label>
                <select name="issue_id" required>
                    <option value="">Select Issue</option>
                    <?php
                    $all_issued = mysqli_query($conn, "SELECT * FROM issued_books WHERE status='Issued'");
                    while ($issued = mysqli_fetch_assoc($all_issued)) {
                        echo "<option value='" . $issued['issue_id'] . "'>ID " . $issued['issue_id'] . " - Book " . $issued['book_id'] . "</option>";
                    }
                    ?>
                </select>

                <label>Return Date</label>
                <input type="date" name="return_date" value="<?php echo date('Y-m-d'); ?>" required>

                <button type="submit" name="return" class="btn btn-add" style="width:100%; padding:12px; margin-top:10px">Return Book</button>
            </form>
        </div>
    </div>
</body>
</html>
