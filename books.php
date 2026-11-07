<?php
include "includes/db_connect.php";

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if (isset($_POST['add_book'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $cat_id = $_POST['category_id'];
    $qty = $_POST['qty'];
    $shelf = $_POST['shelf'];

    mysqli_query($conn, "INSERT INTO books (title, author, isbn, category_id, total_qty, available_qty, shelf_location) VALUES ('$title', '$author', '$isbn', '$cat_id', '$qty', '$qty', '$shelf')");

    header("Location: books.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $check_issued = mysqli_query($conn, "SELECT * FROM issued_books WHERE book_id=$id AND status='Issued'");
    if (mysqli_num_rows($check_issued) > 0) {
        $error = "Cannot delete book that is issued! Return book first!";
    } else {
        mysqli_query($conn, "DELETE FROM books WHERE book_id=$id");
        header("Location: books.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Books</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="other-bg">

    <?php include "includes/sidebar.php"; ?>

    <div class="main">
        <h1>Manage Books</h1>

        <?php if ($error != "") { echo "<p style='color:red; font-weight:bold;'>$error</p>"; } ?>

        <input type="text" id="searchInput" class="search" placeholder="Search books..." onkeyup="searchTable('searchInput','booksTable')">

        <button class="btn btn-add" onclick="openPopup('addPopup')">Add Book</button>

        <table id="booksTable">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>ISBN</th>
                <th>Category ID</th>
                <th>Total</th>
                <th>Available</th>
                <th>Shelf</th>
                <th>Action</th>
            </tr>

            <?php
            $all_books = mysqli_query($conn, "SELECT * FROM books ORDER BY book_id ASC");

            while ($book = mysqli_fetch_assoc($all_books)) {
                echo "<tr>";
                echo "<td>" . $book['book_id'] . "</td>";
                echo "<td>" . $book['title'] . "</td>";
                echo "<td>" . $book['author'] . "</td>";
                echo "<td>" . $book['isbn'] . "</td>";
                echo "<td>" . $book['category_id'] . "</td>";
                echo "<td>" . $book['total_qty'] . "</td>";
                echo "<td>" . $book['available_qty'] . "</td>";
                echo "<td>" . $book['shelf_location'] . "</td>";
                echo "<td><a href='books.php?delete=" . $book['book_id'] . "' class='btn btn-del'>Delete</a></td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <div id="addPopup" class="popup">
        <div class="popup-content">
            <h3>Add New Book</h3>

            <form method="POST">
                <input type="text" name="title" placeholder="Title" required>
                <input type="text" name="author" placeholder="Author" required>
                <input type="text" name="isbn" placeholder="ISBN" required>

                <select name="category_id" required>
                    <option value="">Select Category</option>
                    <option value="1">IT</option>
                    <option value="2">Fiction</option>
                    <option value="3">Science</option>
                    <option value="4">History</option>
                    <option value="5">Mathematics</option>
                </select>

                <input type="number" name="qty" placeholder="Quantity" required>
                <input type="text" name="shelf" placeholder="Shelf Location (e.g., A1)" required>

                <button type="submit" name="add_book" class="btn btn-add" style="width:100%">Add Book</button>
                <button type="button" onclick="closePopup('addPopup')" class="btn btn-del" style="width:100%; margin-top:6px">Cancel</button>
            </form>
        </div>
    </div>

    <script src="js/search.js"></script>

</body>
</html>
