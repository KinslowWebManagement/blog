<?php

    ini_set('display_errors', 'On');

    session_start();
    include_once('db.php');


?>

<html>
<head>
<title>Blog</title>
    <link rel="stylesheet" type="text/css" href="styles/main.css" />
</head>
<body>
<hr />
<div id="topbar">
    <?php
        if(isset($_SESSION['username'])){
            $user = $_SESSION['username'];
            echo "Logged in as: $user&nbsp;|&nbsp;<a href='logout.php'>Logout</a>";
        }
        else {
            echo "<a href='login.php'>Login</a>&nbsp;|&nbsp;<a href='signup.php'>Create Account</a>";
        }
    ?>
</div>
<hr />
<div id="nav">
    <a href="index.php">Home</a>&nbsp;|&nbsp;
    <?php
        if (isset($_SESSION['admin']) && $_SESSION['admin'] > 3){
            echo "<a href='create_post.php'>Create a new post!</a>";
        }
    ?>
</div>
<?php
    require_once("nbbc/nbbc.php");

    $nbbc = new BBCode();

    $sql = "SELECT * FROM posts ORDER BY id DESC";

    $res = mysqli_query($db, $sql) or die(mysqli_error());

    $posts = "";

    if (mysqli_num_rows($res) > 0){
        while($row = mysqli_fetch_assoc($res)) {
            $id = $row['id'];
            $title = $row['title'];
            $posterid = $row['posterID'];
            $content = $row['content'];
            $date = $row['date'];
            $posterName = "";

            $usersql = "SELECT * FROM users WHERE id='$posterid'";
            $userres = mysqli_query($db, $usersql) or die(mysqli_error());
            if (mysqli_num_rows($userres) == 0){
                $posterName = "Former Member";
            }
            else{
                $userrow = mysqli_fetch_assoc($userres);
                $posterName = $userrow['username'];
            }

            if (isset($_SESSION['admin']) && $_SESSION['admin'] > 5 && $_SESSION['username'] == $posterName){
                $admin = "<div><a href='del_post.php?pid=$id'>Delete</a>&nbsp;|&nbsp;<a href='edit_post.php?pid=$id'>Edit</a></div>";
            }
            else{
                $admin = "";
            }

            $output = $nbbc->Parse($content);

            $posts .= "<hr /><div><h2><a href='view_post.php?pid=$id'>$title</a></h2><h3>$posterName - $date</h3><p>$output</p>$admin</div>";
        }
        $posts .= "<hr />";
        echo $posts;
    }
    else {
        echo "<hr />";
        echo "There are no posts to display.";
        echo "<hr />";
    }

//    if (isset($_SESSION['admin']) && $_SESSION['admin'] > 3){
//        echo "<div><a href='create_post.php'>Create a new post!</a></div>";
//    }
?>
</body>
</html>
