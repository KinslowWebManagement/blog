<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/30/17
 * Time: 6:12 AM
 */
    session_start();
    session_destroy();
    header('Location: index.php');
?>