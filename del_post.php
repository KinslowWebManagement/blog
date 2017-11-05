<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/30/17
 * Time: 7:46 AM
 */
    session_start();
    include_once('config.php');

    if (!isset($_SESSION['username']) || !isset($_SESSION['admin']) || $_SESSION['admin'] < 4){
        header('Location: index.php');
    }

    if (!isset($_GET['pid'])){
        header('Location: index.php');
    }

    $pid = $_GET['pid'];

    $sql = "DELETE FROM posts WHERE id='$pid'";
    mysqli_query($db, $sql) or die(mysqli_error());
    header('Location: index.php');
?>