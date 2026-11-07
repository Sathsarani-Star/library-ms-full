<?php
include "includes/db_connect.php";

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if (isset($_POST['issue'])) {
    $member_id = $_POST['member_id'];
    $book_id = $_POST['book_id'];

    $check_member = mysqli_query($conn, "SELECT * FROM members WHERE member_id=$member_id");
    $member_data = mysqli_fetch_assoc($check_member);

    if ($member_data['status'] == 'Blocked') {
        $error = "Member is blocked! Cannot issue.";
    } else {

        $check_book = mysqli_query($conn, "SELECT * FROM books WHERE book_id=$book_id");
        $book_data = mysqli_fetch_assoc($check_book);

        if ($book_data['available_qty'] <= 0) {
            $error = "Book not available!";
        } else {

            $issue_date = date("Y-m-d");
            $due_date = date("Y-m-d", strtotime("+14 days"));

            mysqli_query($conn, "INSERT INTO issued_books (book_id, member_id, issue_date, due_date, status) VALUES ($book_id, $member_id, '$issue_date', '$due_date', 'Issued')");

            mysqli_query($conn, "UPDATE books SET available_qty = available_qty - 1 WHERE book_id=$book_id");

            $error = "Book issued successfully! Due: $due_date";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Issue Books</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="other-bg">

    <?php include "includes/sidebar.php"; ?>

    <div class="main">
        <div class="form-center-wrap">
            <h1>Issue Books</h1>

            <?php
            if ($error != "") {
                echo "<p style='font-weight:bold; color:green;'>$error</p>";
            }
            ?>

            <form method="POST" class="form-card">
                <label>Member ID</label>
                <select name="member_id" required>
                    <option value="">Select Member</option>
                    <?php
                    $all_members = mysqli_query($conn, "SELECT * FROM members WHERE status='Active'");
                    while ($member = mysqli_fetch_assoc($all_members)) {
                        echo "<option value='" . $member['member_id'] . "'>" . $member['member_code'] . " - " . $member['full_name'] . "</option>";
                    }
                    ?>
                </select>

                <label>Book ID</label>
                <select name="book_id" required>
                    <option value="">Select Book</option>
                    <?php
                    $all_books = mysqli_query($conn, "SELECT * FROM books WHERE available_qty > 0");
                    while ($book = mysqli_fetch_assoc($all_books)) {
                        echo "<option value='" . $book['book_id'] . "'>" . $book['book_id'] . " - " . $book['title'] . "</option>";
                    }
                    ?>
                </select>

                <label>Due Date</label>
                <input type="text" value="<?php echo date('Y-m-d', strtotime('+14 days')); ?>" readonly>

                <button type="submit" name="issue" class="btn btn-add" style="width:100%; padding:12px; margin-top:10px">Issue Book</button>
            </form>
        </div>
    </div>

    <script src="js/search.js"></script>

</body>
</html>
