<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "test_db";
$connection = mysqli_connect($host, $username, $password, $database);
if (!$connection) {
    die('Connection failed: ' . mysqli_connect_error());
}
$sql = "SELECT users.user_name, posts.title, posts.content, posts.time FROM posts INNER JOIN users ON posts.user_id = users.id ORDER BY posts.time DESC";
$result = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Blog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #EA5455;
            color: #333;
        }
        h1 {
            color: #444;
            text-align: center;
            margin-top: 1.25rem;
        }
        .login-link {
            text-align: right;
            padding: 0.6rem 1.25rem;
        }
        .login-link a {
            text-decoration: none;
            background-color: #2196F3;
            color: white;
            border: 0.05rem solid #ccc;
            padding: 0.4rem 0.75rem;
            margin-right: 0.6rem;
        }
        .post {
            border: 0.05rem solid #ccc;
            padding: 10rem;
            margin: 1.25rem auto;
            background-color: #f2f2f2;
            width: 80%;
            box-sizing: border-box;
        }
        .title {
            font-weight: bold;
            font-size: 1.5em;
            color: #444;
        }
        .username {
            font-size: 0.8em;
            color: #999;
        }
        .time {
            font-size: 0.8em;
            color: #999;
            float: right;
        }
    </style>
</head>
<body>
<div class="login-link">
    <a href="login.html">Add Post</a>
</div>
<h1>Blog Posts</h1>
<?php if (mysqli_num_rows($result) > 0): ?>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="post">
            <div class="title"><?php echo htmlspecialchars($row['title']); ?></div>
            <div class="username">By: <?php echo htmlspecialchars($row['user_name']); ?></div>
            <div class="time"><?php echo htmlspecialchars($row['time']); ?></div>
            <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No posts available.</p>
<?php endif; ?>
<?php
mysqli_close($connection);
?>
</body>
</html>
