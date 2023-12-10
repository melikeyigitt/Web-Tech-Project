<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        if (empty($title)|| empty($content)) {
            $_SESSION['errors'] = array();
            if ($title === '') {
                $_SESSION['errors']['title'] = 'Title field is empty';
            }
            if ($content === '') {
                $_SESSION['errors']['content'] = 'Content field is empty';
            }
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Add Post</title>
        <style>
            html, body {
                font-family: Arial, sans-serif;
                background-color: #EA5455;
            }
            .container {
                display: flex;
                flex-direction: column;
                align-items: center;
                width: 50%;
                background-color: #ffffff;
                padding: 2rem;
                border-radius: 0.3rem;
                margin: 5% auto 0;
            }
            label {
                font-size: 1.12rem;
                margin-bottom: 0.6rem;
            }
            input[type="text"], textarea {
                width: 95%;
                padding: 0.5rem 0.75rem;
                margin-bottom: 1.25rem;
                font-size: 1rem;
                border: 0.05rem solid #cccccc;
                border-radius: 0.25rem;
            }
            button {
                width: 60%;
                padding: 0.6rem;
                font-size: 1rem;
                font-weight: bold;
                margin: 0.5rem;
                color: #ffffff;
                background-color: #2196F3;
                border: none;
                border-radius: 0.25rem;
            }
            .error {
                color: red;
            }
            .top-right {
                position: fixed;
                top: 0.625rem;
                right: 1.25rem;
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
        </style>
    </head>
    <body>
    <div class="container">
        <h1>Hello, <?php echo $_SESSION['name']?></h1>
        <div class="top-right"><a href="logout.php">Logout</a></div>
        <h2>Add Post</h2>
        <form method="post" action="addPost.php">
            <div>
                <label for="title">Title</label>
                <input type="text" name="title" id="title">
            </div>
            <div>
                <label for="content">Content</label>
                <textarea name="content" id="content"></textarea>
            </div>

            <?php if (isset($_GET['error'])) { ?>
                <p class="error"><?php echo $_GET['error']; ?></p>
            <?php } ?>

            <div>
                <button type="submit">Submit</button>
                <button type="button" id="clear-button">Clear</button>
            </div>
        </form>
        <script src="script(1).js"></script>
    </div>
    </body>
    </html>

<?php
} else {
    header("Location: index.php");
    exit();
}
?>