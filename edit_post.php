<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/30/17
 * Time: 7:52 AM
 */

    session_start();
    include_once('db.php');

    if (!isset($_SESSION['username']) || !isset($_SESSION['admin']) || $_SESSION['admin'] < 4){
        header('Location: index.php');
    }


    if (!isset($_GET['pid'])){
        header('Location: index.php');
    }
    $pid = $_GET['pid'];

    if (isset($_POST['editpost'])){
        $title = strip_tags($_POST['title']);
        $content = strip_tags($_POST['content']);

        $title = mysqli_real_escape_string($db, $title);
        $content = mysqli_real_escape_string($db, $content);
        $date = date('n\/d\/Y');

        $sql = "UPDATE posts SET title='$title', content='$content', date='$date' WHERE id='$pid'";
        if($title == "" || $content == ""){
            echo "Please complete your post!";
            return;
        }

        mysqli_query($db, $sql) or die(mysqli_error());
        header('Location: index.php');
    }
?>

<html>
<head>
    <title>Blog - Edit</title>
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
    <?php
        $sql_get = "SELECT * FROM posts WHERE id='$pid' LIMIT 1";
        $res = mysqli_query($db, $sql_get);
        if (mysqli_num_rows($res) > 0){
            $row = mysqli_fetch_assoc($res);
            $title = $row['title'];
            $content = $row['content'];
        }

    ?>
<form method="POST" action="edit_post.php?pid=<?php echo $pid; ?>">
    <input type="text" name="title" placeholder="Title" value="<?php echo $title; ?>" /><br />
    <textarea name="content" placeholder="Content" rows="20" cols="50"><?php echo $content; ?></textarea><br />
    <input type="submit" name="editpost" value="Edit Post" />
</form>
</body>
</html>
