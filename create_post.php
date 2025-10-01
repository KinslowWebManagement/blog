<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/30/17
 * Time: 6:45 AM
 */
session_start();
include_once('config.php');
if (!isset($_SESSION['username']) || !isset($_SESSION['admin']) || $_SESSION['admin'] < 4) {
    header('Location: index.php');
}

if (isset($_POST['newpost'])){
    $user = $_SESSION['username'];
    $usersql = "SELECT * FROM users WHERE username='$user'";
    $userres = mysqli_query($db, $usersql);
    if (!mysqli_num_rows($userres) == 0){
        $row = mysqli_fetch_assoc($userres);
        $userid = $row['id'];

        $title = strip_tags($_POST['title']);
        $content = strip_tags($_POST['content']);

        $title = mysqli_real_escape_string($db, $title);
        $content = mysqli_real_escape_string($db, $content);
        $date = date('n\/d\/Y');

        $sql = "INSERT INTO posts (title, posterID, content, date) VALUES ('$title', '$userid', '$content', '$date')";
        if ($title == "" || $content == ""){
            echo "Please complete your post!";
            return;
        }
        mysqli_query($db, $sql);
        header('Location: index.php');
    }
}
?>

<html>
<head>
    <title>Blog - New Post</title>
    <?php echo $embed; ?>
    <link rel="stylesheet" type="text/css" href="styles/main.css" />
</head>
<body>
<hr />
<div id="topbar">
    <?php
    if(isset($_SESSION['username'])){
        $user = $_SESSION['username'];
        echo "Logged in as: $user";
    }
    else {
        //echo "<a href='login.php'>Login</a>";
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
<form method="POST">
    <input name="title" type="text" placeholder="Title" class="form-control" /><br />
    <textarea name="content" placeholder="Content" rows="12" class="form-control"></textarea><br />
    <input type="submit" name="newpost" value="Create Post" />
</form>
</body>
</html>
