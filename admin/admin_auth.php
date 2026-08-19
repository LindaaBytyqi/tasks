<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../index.php");
    exit();
}

if(!isset($_SESSION['status']) || $_SESSION['status'] !== true){

    session_unset();
    session_destroy();

    header("Location: ../login.php");
    exit();
}

?>