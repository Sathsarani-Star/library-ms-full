<?php
include "includes/db_connect.php";

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if (isset($_POST['add_member'])) {
    $code = $_POST['member_code'];
    $name = $_POST['full_name'];
    $nic = $_POST['nic'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $addr = $_POST['address'];

    mysqli_query($conn, "INSERT INTO members (member_code, full_name, nic, phone, email, address) VALUES ('$code', '$name', '$nic', '$phone', '$email', '$addr')");

    header("Location: members.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $check_issued = mysqli_query($conn, "SELECT * FROM issued_books WHERE member_id=$id AND status='Issued'");
    if (mysqli_num_rows($check_issued) > 0) {
        $error = "Cannot delete member with issued books! Return books first!";
    } else {
        mysqli_query($conn, "DELETE FROM members WHERE member_id=$id");
        header("Location: members.php");
        exit();
    }
}

if (isset($_GET['block'])) {
    $id = $_GET['block'];
    mysqli_query($conn, "UPDATE members SET status='Blocked' WHERE member_id=$id");
    header("Location: members.php");
    exit();
}

if (isset($_GET['unblock'])) {
    $id = $_GET['unblock'];
    mysqli_query($conn, "UPDATE members SET status='Active' WHERE member_id=$id");
    header("Location: members.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Members</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="other-bg">

    <?php include "includes/sidebar.php"; ?>

    <div class="main">
        <h1>Manage Members</h1>

        <?php if ($error != "") { echo "<p style='color:red; font-weight:bold;'>$error</p>"; } ?>

        <input type="text" id="searchInput" class="search" placeholder="Search members..." onkeyup="searchTable('searchInput','membersTable')">

        <button class="btn btn-add" onclick="openPopup('addMemberPopup')">Add Member</button>

        <table id="membersTable">
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>Name</th>
                <th>NIC</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php
            $all_members = mysqli_query($conn, "SELECT * FROM members ORDER BY member_id ASC");

            while ($member = mysqli_fetch_assoc($all_members)) {
                echo "<tr>";
                echo "<td>" . $member['member_id'] . "</td>";
                echo "<td>" . $member['member_code'] . "</td>";
                echo "<td>" . $member['full_name'] . "</td>";
                echo "<td>" . $member['nic'] . "</td>";
                echo "<td>" . $member['phone'] . "</td>";
                echo "<td>" . $member['status'] . "</td>";
                echo "<td>";

                echo "<a href='members.php?delete=" . $member['member_id'] . "' class='btn btn-del'>Delete</a> ";

                if ($member['status'] == 'Active') {
                    echo "<a href='members.php?block=" . $member['member_id'] . "' class='btn btn-edit'>Block</a>";
                } else {
                    echo "<a href='members.php?unblock=" . $member['member_id'] . "' class='btn btn-add'>Unblock</a>";
                }

                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <div id="addMemberPopup" class="popup">
        <div class="popup-content">
            <h3>Add New Member</h3>

            <form method="POST">
                <input type="text" name="member_code" placeholder="Member Code (e.g., M005)" required>
                <input type="text" name="full_name" placeholder="Full Name" required>
                <input type="text" name="nic" placeholder="NIC" required>
                <input type="text" name="phone" placeholder="Phone" required>
                <input type="email" name="email" placeholder="Email">
                <input type="text" name="address" placeholder="Address">
                <button type="submit" name="add_member" class="btn btn-add" style="width:100%">Add Member</button>
                <button type="button" onclick="closePopup('addMemberPopup')" class="btn btn-del" style="width:100%; margin-top:6px">Cancel</button>
            </form>
        </div>
    </div>

    <script src="js/search.js"></script>

</body>
</html>