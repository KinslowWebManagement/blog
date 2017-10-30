<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/30/17
 * Time: 6:19 AM
 */
session_start();
include_once('db.php');

ini_set('display_errors', 'On');

if (!isset($_GET['pid'])){
    header('Location: index.php');
}

$pid = $_GET['pid'];
?>

<html>
<head>
    <title>Blog - View</title>
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
<hr />
<?php
    $post = "";
    require_once('nbbc/nbbc.php');

    $bbcode = new BBCode;
    $admin = "";

    $sql = "SELECT * FROM posts WHERE id='$pid'";
    $res = mysqli_query($db, $sql) or die(mysqli_error());
    if (mysqli_num_rows($res) == 0){
        echo "<p>Unable to locate content. Please try again later.</p>";
        return;
    }
    else {
        $row = mysqli_fetch_assoc($res);
        $ptitle = $row['title'];
        $pcontent = $row['content'];
        $pPosterId = $row['posterID'];
        $pdate = $row['date'];
        $pPosterName = "";

        $sql = "SELECT * FROM users WHERE id='$pPosterId'";
        $res = mysqli_query($db, $sql);
        if (mysqli_num_rows($res) == 0){
            $pPosterName = "Former Member";
        }
        else {
            $row = mysqli_fetch_assoc($res);
            $pPosterName = $row['username'];
        }

        if (isset($_SESSION['admin']) && $_SESSION['admin'] > 3 && $_SESSION['username'] == $pPosterName){
            $admin = "<div><a href='del_post.php?pid=$pid'>Delete</a>&nbsp;|&nbsp;<a href='edit_post.php?pid=$pid'>Edit</a></div>";
        }

        $output = $bbcode->Parse($pcontent);

        $post = "<div id='post'><h1>$ptitle</h1><h2>$pPosterName - $pdate</h2><p>$output</p>$admin</div>";
    }

    echo $post;
?>
</body>
</html>
