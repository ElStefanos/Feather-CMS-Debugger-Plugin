<?php

session_start();

define('_DEFVAR', 1);


include_once '../../../../file_system.php';

include_once './debuger_callers.php';

$path = __PLUGINPATH__.'/Debuger/src/debuger_out.txt';

if(!file_exists($path)) {
    $file = fopen($path, 'w');
    fwrite($file, 'New Log Created');
    fclose($file);
}

if(isset($_SESSION['allow_debuger'])) {
    
    if(isset($_POST['debuger'])) {
        $string = $_POST['debuger'];
    }else{
        $string = '';
    }

    $string = explode(', ', $string);

    foreach($string as $function) {

        if(strpos($function, '-') !== FALSE) {

            $fun = explode('-', $function);

        } else {

            $fun = explode(' ', $function);

        }

        if(!in_array($fun[0] , $debuger_commands)) {

            $file = fopen($path, 'a');
            fwrite($file, '<br>No function found with callsign: '.$fun[0]);
            fclose($file);
            
        } else {

            $param = explode(' ', $fun[1]);
            $count_p = count($param);
            $count_f = count($fun);

            $date = date(DATE_RSS);

            $log = $fun[0].'-'.$fun[1].' '.$param[1];

            $file = fopen('./console_log.php', 'a');
            fwrite($file, PHP_EOL.'<br><p id="log">'. __CLIENTIP__ .': '. $date .' '. $log . '</p>');
            fclose($file);

            if($count_f == 1 && $param == 1) {

                $fun[0]($param[1]);
            }

            if($count_f > 1 || $count_p == 1) {

                $fun[0]($param[0], $param[1]);

            }

            if($count_f > 1 && $count_p == 0) {

                $fun[0]($fun[1]);

            }

            if($count_f == 1 && $count_p == 0) {

                $fun[0]();

            }

        }
    }


} else{
    if(isset($_POST['debuger'])) {
        
        if(empty($_POST['debuger'])) {

            $file = fopen($path, 'w');
            fwrite($file, '<br>Login to proceed');
            fclose($file);

        } else {

            $pass = $_POST['debuger'];

            $pass = hash('sha512', $pass);

            if($code == $pass) {

                $file = fopen($path, 'a');
                fwrite($file, '<br>Login success');
                fclose($file);

                $_SESSION['allow_debuger'] = $pass;

            } else {

                $file = fopen($path, 'a');
                fwrite($file, '<br>Wrong passcode');
                fclose($file);  

            }
        }

} else {

    $file = fopen($path, 'w');
    fwrite($file, '<br>Login to proceed');
    fclose($file);
    }
}
?>