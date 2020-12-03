<?php

    use DataBase\DataBase;

    $db = new DataBase;

    $plugins[] = 'Debuger';

    $path = $_SERVER['PHP_SELF'];

    $root = explode('\\', __ROOT__);
    $path = explode('/', $path);
    
    $final_path = array_intersect($root, $path);
    
    $path = implode("",$final_path);
     
    $deb_path = 'http://'.__DOMAIN__.'/'.$path.'/assets/plugins/debuger/src/';
    
    if(isset($_GET['debuger']) && isset($_SESSION['admin_request_debuger'])) {

        include __PLUGINPATH__.'Debuger/src/debuger.php';

    } elseif(isset($_GET['debuger']) && !$test=$db->db_connect() && !isset($_SESSION['admin_request_debuger'])) {

        include __PLUGINPATH__.'Debuger/src/debuger.php';

    } elseif(isset($_GET['debuger']) && !isset($_SESSION['admin_request_debuger']) && $test=$db->db_connect()) {

        echo 'You should not do that :)';
        
    }

?>
