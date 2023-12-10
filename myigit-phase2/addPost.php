<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        if (empty($title)) {
            $title_error = "Field title is empty";
        }
        if (empty($content)) {
            $content_error = "Field content is empty";
        }
        if (!empty($title_error) || !empty($content_error)) {
            $error_message = "";
            if (!empty($title_error)) {
                $error_message .= $title_error;
            }
            if (!empty($content_error)) {
                $error_message .= ($error_message == "" ? "" : "<br>") . $content_error;
            }
            header("Location: addEntry.php?error=$error_message");
            exit();
        } else {
            $host = "localhost";
            $username = "root";
            $password = "";
            $database = "test_db";
            $connection = mysqli_connect($host, $username, $password, $database);
            if (!$connection) {
                die('Connection failed: ' . mysqli_connect_error());
            }
            $user_id = $_SESSION['id'];
            $sql = "INSERT INTO posts (user_id, title, content) VALUES ('$user_id', '$title', '$content')";
            if (mysqli_query($connection, $sql)) {
                header("Location: viewBlog.php");
                exit();
            } else {
                header("Location: addEntry.php?error=Error adding post: " . mysqli_error($connection));
                exit();
            }
            mysqli_close($connection);
        }
    }
} else {
    header("Location: index.php");
    exit();
}
?>


