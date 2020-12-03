<?php
    
    session_start();

    include '/security_check.php';

    if(isset($_SESSION['admin_request_debuger'])) {
        header("Location: http://".__DOMAIN__."/paging.php?debuger=1");
        exit();
    } else {
        $_SESSION['admin_request_debuger'] = 1;
        header("Location: http://".__DOMAIN__."/paging.php?debuger=1");
        exit();
    }
?>