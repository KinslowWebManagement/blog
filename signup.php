<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/30/17
 * Time: 8:47 AM
 */
    //ini_set('display_errors', 'On');
    session_start();
    include_once('db.php');

    if(isset($_SESSION['username'])){
        header('Location: index.php');
    }

    if (isset($_POST['createuser'])){
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $hashedPass = md5($pass);
        $sql = "INSERT INTO users (username, password) VALUES ('$user', '$hashedPass')";;
        mysqli_query($db, $sql);
        $_SESSION['username'] = $user;
        header('Location: index.php');
    }
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf8">
    <title>Blog - Login</title>
    <link rel="stylesheet" type="text/css" href="styles/main.css" />
</head>
<body>

<p>Already have an account? Login <a href="login.php">here</a>.</p>

<form method="POST">
    <input type="text" placeholder="Username" name="username" /><br />
    <input type="password" placeholder="Password" name="password" /><br />
    <input name="createuser" type="submit" value="Create Account" />
</form>

</body>
</html>
