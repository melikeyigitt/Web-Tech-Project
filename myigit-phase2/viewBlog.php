<?php
session_start();
if (isset($_SESSION['id'], $_SESSION['user_name'], $_SESSION['name'])) {
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "test_db";
    $connection = mysqli_connect($host, $username, $password, $database);
    if (!$connection) {
        die('Connection failed: ' . mysqli_connect_error());
    }
    if (isset($_GET['delete'], $_GET['type']) && $_GET['type'] === 'post' && $_SESSION['id'] == 2) {
        $delete_id = mysqli_real_escape_string($connection, $_GET['delete']);
        $sql = "DELETE FROM posts WHERE id = '$delete_id'";
        mysqli_query($connection, $sql);
    }
    if (isset($_POST['submit_comment'], $_POST['post_id'], $_POST['name'], $_POST['comments'])) {
        $post_id = mysqli_real_escape_string($connection, $_POST['post_id']);
        $name = mysqli_real_escape_string($connection, $_POST['name']);
        $comment = mysqli_real_escape_string($connection, $_POST['comments']);
        $time = date("Y-m-d H:i:s");

        $sql = "SELECT id FROM posts WHERE id = '$post_id'";
        $result = mysqli_query($connection, $sql);
        if (mysqli_num_rows($result) == 1) {
            $sql = "INSERT INTO comments (post_id, name, comment, time) VALUES ('$post_id', '$name', '$comment', '$time')";
            mysqli_query($connection, $sql);
        }
    }
    if (isset($_GET['delete'], $_GET['type'], $_GET['post_id']) && $_GET['type'] === 'comment' && $_SESSION['id'] == 2) {
        $delete_id = mysqli_real_escape_string($connection, $_GET['delete']);
        $post_id = mysqli_real_escape_string($connection, $_GET['post_id']);
        $sql = "DELETE FROM comments WHERE id = '$delete_id' AND post_id = '$post_id'";
        mysqli_query($connection, $sql);
    }
    $sql_months = "SELECT DISTINCT DATE_FORMAT(time, '%Y-%m') as month FROM posts";
    $result_months = mysqli_query($connection, $sql_months);

    $selected_month = (isset($_GET['month'])) ? $_GET['month'] : 'all';
    if ($selected_month == 'all') {
        $sql = "SELECT * FROM posts";
    } else {
        $sql = "SELECT * FROM posts WHERE DATE_FORMAT(time, '%Y-%m') = '$selected_month'";
    }
    $result_posts = mysqli_query($connection, $sql);
    $posts_array = [];
    while ($row = mysqli_fetch_assoc($result_posts)) {
        $posts_array[] = $row;
    }
    function sortPostsByTimeDescending($post1, $post2) {
        $time1 = strtotime($post1['time']);
        $time2 = strtotime($post2['time']);
        return $time2 - $time1;
    }
    usort($posts_array, 'sortPostsByTimeDescending');
?>
<!DOCTYPE html>
    <html>
    <head>
        <title>View Blog</title>
        <script src="script.js"></script>
        <style>
            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                background-color: #333;
                color: #fff;
                padding: 0.6rem 1.25rem;
            }
            .header a {
                text-decoration: none;
                color: #fff;
                border: 0.125rem solid #fff;
                background-color: transparent;
                padding: 0.5rem 1rem;
                margin-right: 0.6rem;
            }
            .header a:hover {
                background-color: #fff;
                color: #333;
                border-color: #333;
            }
            body {
                font-family: Arial, sans-serif;
                background-color: #EA5455;
                color: #333;
            }
            h1, h2 {
                color: #444;
            }
            h2 {
                text-align: center;
            }
            a {
                display: inline-block;
                text-decoration: none;
                background-color: #2196F3;
                color: white;
                border: 0.05rem solid #ccc;
                padding: 0.4rem 0.75rem;
                margin-right: 0.6rem;
            }
            table {
                width: 90%;
                border-collapse: collapse;
                margin-bottom: 1.25rem;
            }
            th, td {
                text-align: left;
                padding: 0.625rem;
                border: 0.05rem solid #ccc;
            }
            th {
                background-color: #f2f2f2;
                font-weight: bold;
            }
            tr{
                background-color: #f2f2f2;
            }
            label {
                display: inline-block;
                margin-right: 10px;
            }
            .container {
                width: 80%;
                margin: 0 auto;
                padding: 1.25rem;
            }
            input[type="text"], textarea {
                width: 100%;
                padding: 0.5rem;
                margin-bottom: 0.6rem;
                border: 0.05rem solid #ccc;
                box-sizing: border-box;
            }
            input[type="submit"] {
                background-color: #2196F3;
                color: white;
                border: none;
                padding: 0.5rem 1rem;
                cursor: pointer;
            }
            input[type="submit"]:hover {
                background-color: #1976D2;
            }
            button {
                background-color: #2196F3;
                color: white;
                border: none;
                padding: 0.5rem 1rem;
                margin-bottom: 0.6rem;
            }
            button:hover {
                background-color: #1976D2;
            }
            select {
                padding: 0.5rem;
                margin-bottom: 1.25rem;
                border: 0.05rem solid #ccc;
                background-color: #fff;
            }
        </style>
    </head>
    <body>
    <div class="header">
        <a href="logout.php">Logout</a>
        <a href="addEntry.php">Add Post</a>
    </div>
    <div class="container">
    <h1>Hello, <?php echo $_SESSION['name']?></h1>
    <h2>Blog Posts</h2>
    <label for="month-selector">Select Month:</label>
    <select id="month-selector" onchange="changeMonth()">
        <option value="all" <?php echo ($selected_month == 'all') ? 'selected' : ''; ?>>All</option>
        <?php while ($row_month = mysqli_fetch_assoc($result_months)): ?>
            <option value="<?php echo $row_month['month']; ?>" <?php echo ($row_month['month'] == $selected_month) ? 'selected' : ''; ?>><?php echo $row_month['month']; ?></option>
        <?php endwhile; ?>
    </select>
    <table>
        <tr>
            <th>Title</th>
            <th>Content</th>
            <th>Date</th>
            <th>Comments</th>
            <?php if (isset($_SESSION['id']) && $_SESSION['id'] == 2): ?>
                <th>Action</th>
            <?php endif; ?>
        </tr>
    <?php foreach ($posts_array as $row): ?>
            <tr>
                <td><?php echo $row['title'] ?></td>
                <td><?php echo $row['content'] ?></td>
                <td><?php echo date('jS F Y, g.i A T', strtotime($row['time'])); ?></td>
                <td>
                    <?php
                    $post_id = $row['id'];
                    $sql_comments = "SELECT * FROM comments WHERE post_id = '$post_id' ORDER BY time ASC";
                    $result_comments = mysqli_query($connection, $sql_comments);
                    if (mysqli_num_rows($result_comments) > 0) {
                        while ($comment_row = mysqli_fetch_assoc($result_comments)) {
                            echo '<p>' . $comment_row['name'] . ': ' . $comment_row['comment'] . '</p>';
                            if (isset($_SESSION['id']) && $_SESSION['id'] == 2) {
                                echo '<a href="' . htmlspecialchars($_SERVER['PHP_SELF']) . '?delete=' . $comment_row['id'] . '&type=comment&post_id=' . $post_id . '">Remove Comment</a>';
                            }
                        }
                    } else {
                        echo '';
                    }
                    ?>
                    <?php if (isset($_SESSION['id'])): ?>
                        <button onclick="toggleCommentForm(<?php echo $row['id']; ?>)">Add Comment</button>
                        <form id="comment-form-<?php echo $row['id']; ?>" style="display: none" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                            <input type="hidden" name="post_id" value="<?php echo $row['id']; ?>">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" required>
                            <br>
                            <label for="comments">Comment:</label>
                            <textarea id="comments" name="comments" required></textarea>
                            <br>
                            <input type="submit" name="submit_comment" value="Submit">
                        </form>
                    <?php endif; ?>
                </td>
                <?php if (isset($_SESSION['id']) && $_SESSION['id'] == 2): ?>
                    <td>
                        <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?delete=' . $row['id'] . '&type=post'; ?>">Delete Post</a>
                    </td>
                <?php endif; ?>
            </tr>
    <?php endforeach; ?>
    </table>
    </div>
    </body>
    </html>
    <?php
    mysqli_close($connection);
} else {
    header("Location: index.php");
    exit();
}
?>
