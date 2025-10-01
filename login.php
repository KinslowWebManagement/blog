<?php

    session_start();
    include_once('config.php');

    if (isset($_POST['signin'])){
        $user = $_POST['username'];
        $pass = $_POST['password'];

        $userSanitized = mysqli_real_escape_string($db, $user);
        $passSanitized = mysqli_real_escape_string($db, $pass);

        if (empty($user) || empty($pass)){
            echo "<p class='redtext'>ERROR: You may not enter an empty username or password</p>";
        }
        else {
            $hashedPass = md5($passSanitized);
            $sql = "SELECT * FROM users WHERE username = '$userSanitized' AND password='$hashedPass'";

            $res = mysqli_query($db, $sql);

            if (mysqli_num_rows($res) == 0){
                echo "<p class='redtext'>ERROR: Was unable to find an account by that name identified by that password!</p>";
            }
            else {
                $row = mysqli_fetch_assoc($res);
                $_SESSION['username'] = $user;
                $adminlevel = (int)$row['admin'];
                if ($adminlevel > 0){
//                    echo $adminlevel;
//                    echo "<br />";
//                    echo gettype($adminlevel);
                    $_SESSION['admin'] = $row['admin'];
                }
                header('Location: index.php');
            }
        }
    }

?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf8">
    <title>Blog - Login</title>
    <?php echo $embed; ?>
    <link rel="stylesheet" type="text/css" href="styles/main.css" />
</head>
<body>
    <div class="center-block">
    <p>Don't already have an account? Create one <a href="signup.php">here</a>.</p>

    <form method="POST">
        <input type="text" placeholder="Username" name="username" /><br />
        <input type="password" placeholder="Password" name="password" /><br />
        <input name="signin" type="submit" value="Login" />
    </form>
    </div>
</body>
</html>	