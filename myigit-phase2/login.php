<?php
session_start();
    $sname = "localhost";
    $username = "root";
    $password = "";
    $db_name = "test_db";
    $conn = mysqli_connect($sname, $username, $password, $db_name);
    if (!$conn) {   
        echo "Connection failed!";
    }
    if (isset($_POST['username']) && isset($_POST['password'])) {
        function va($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $username = va($_POST['username']);
        $pass = va($_POST['password']);
        if (empty($username)) {
            header("Location: index.php?error=User Name is required");
            exit();
        }else if(empty($pass)){
            header("Location: index.php?error=Password is required");
            exit();
        }else{
            $sql = "SELECT * FROM users WHERE user_name='$username' AND password='$pass'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                if ($row['user_name'] === $username && $row['password'] === $pass){
                    $_SESSION['user_name'] = $row['user_name'];
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['id'] = $row['id'];
                    header("Location: viewBlog.php");
                    exit();
                }
            } else{
                header("Location: index.php?error=Incorrect User name or Password");
                exit();
            }
        }
    } else{
        header("Location: viewBlog.php");
        exit();
    }
?>