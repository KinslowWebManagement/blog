<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/30/17
 * Time: 6:19 AM
 */
session_start();
include_once('config.php');

ini_set('display_errors', 'On');

if (!isset($_GET['pid'])){
    header('Location: index.php');
}

$pid = $_GET['pid'];

if(isset($_POST['newcomment'])){
    if(isset($_SESSION['username'])){
        $user = $_SESSION['username'];
        $userid = null;
        $sql = "SELECT * FROM users WHERE username='$user'";
        $res = mysqli_query($db, $sql);
        if (mysqli_num_rows($res) > 0){
            $row = mysqli_fetch_assoc($res);
            $userid = $row['id'];
        }
        $date = date('n\/d\/Y');
        
        $content = $_POST['body'];
        $sql = "INSERT INTO comments (postid, posterid, date, content) VALUES ('$pid', '$userid', '$date', '$content')";
        mysqli_query($db, $sql);
    }
}
?>

<html>
<head>
    <title>Blog - View</title>
    <?php echo $embed; ?>
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
    
    $bbcode->RemoveRule('img');
    $bbcode->AddRule('img', Array(
        'mode' => BBCODE_MODE_ENHANCED,
        'template' => '<img class="img-fluid" src="{$_content}" />',
        'class' => 'block',
        'allow_in' => Array('listitem', 'block', 'columns'),
    ));
    
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

    $post .= '<hr style="border-width:3px;" />';
    echo $post;
    if(isset($_SESSION['username'])){
?>
    <form method="POST" action="view_post.php?pid=<?php echo $pid; ?>">
        <textarea name="body" placeholde="Type here to write a new comment" rows="4" cols="30"></textarea><br />
        <input type="submit" name="newcomment" value="Post Comment" />
    </form>
<?php
} else{
    echo "Please <a href='login.php'>login</a> or <a href='signup.php'>Create an Account</a> if you would like to comment.";
    echo "<hr />";
}

$sql = "SELECT * FROM comments WHERE postid='$pid'";
$res = mysqli_query($db, $sql);

$comments = "";

if (mysqli_num_rows($res) > 0){
    while($row = mysqli_fetch_assoc($res)) {
        $id = $row['id'];
        $posterid = $row['posterid'];
        $content = $row['content'];
        $posterName = "";
        $date = $row['date'];

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
            $admin = ""; // "<div align='center'><a href='del_post.php?pid=$id'>Delete</a>&nbsp;|&nbsp;<a href='edit_post.php?pid=$id'>Edit</a></div>";
        }
        else{
            $admin = "";
        }

        $output = $bbcode->Parse($content);

        $comments .= "<hr /><div align='left'><h4 align='left'>$posterName - $date</h4><p>$output</p>$admin</div>";
    }
    $comments .= "<hr />";
    echo $comments;
}
else {
    if(isset($_SESSION['username'])){
        echo "<hr />";
        echo "Be the first to comment. Just type something above.";
        echo "<hr />";
    }
}
?>
</body>
</html>
		