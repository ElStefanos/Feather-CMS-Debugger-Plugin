<?php

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if(!isset($_SESSION['debuger_errors'])) {
        $_SESSION['debuger_errors'] = array();
    }
    
    if(!isset($_SESSION['debuger_warnings'])) {
        $_SESSION['debuger_warnings'] = array();
    }
    
    if(!isset($_SESSION['debuger_settings'])) {
        $_SESSION['debuger_settings'] = array();
    }
    
    if(!isset($_SESSION['debuger_plugins_names'])) {
        $_SESSION['debuger_plugins_names'] = array();
    }
    
    if(!isset($_SESSION['debuger_plugins_paths'])) {
        $_SESSION['debuger_plugins_paths'] = array();
    }
    
    $test = new DataBase\DataBase;

    $debuger_errors = $_SESSION['debuger_errors'];

    $debuger_warnings = $_SESSION['debuger_warnings'];

    $debuger = $_SESSION['debuger_settings'];

    $plugins_n = $_SESSION['debuger_plugins_names'];

    $plugins_p = $_SESSION['debuger_plugins_paths'];

    $debuger = array_unique($debuger);

    $debuger_errors = array_unique($debuger_errors);

    $debuger_warnings = array_unique($debuger_warnings);

    $plugins_n = array_unique($plugins_n, SORT_REGULAR);


    include_once __PLUGINPATH__.'Debuger/src/pwd.php';

    $debuger_commands = array (

        'debuger' => 'debuger',
        'test_connection' => 'test_connection',
        'clear' => 'clear',
        'clear_errors' => 'clear_errors',
        'clear_warnings' => 'clear_warnings',
        'log_out' => 'log_out',
        'help' => 'help',
        'show_plugins' => 'show_plugins',
        'sessions' => 'sessions',
        'change_pwd' => 'change_pwd',
        's_console_log' => 's_console_log',
    );

    
    function clear($method = '') {
        switch ($method) {
            case 's':
                $file = fopen('./debuger_out.txt', 'w');
                fwrite($file, ' ');
                fclose($file);
                break;
            
            default:
                $file = fopen('./debuger_out.txt', 'w');
                fwrite($file, 'Cleared<br>');
                fclose($file);
                break;
        }
    }
    
    
    function debuger($method='') {
        global $debuger;
        global $debuger_errors;
        global $debuger_warnings;

        switch ($method) {
            case 'e':

                foreach($debuger_errors as $error) {
                    $file = fopen('./debuger_out.txt', 'a');
                    fwrite($file, '<p style="color: red;">'.$error.'</p><br>');
                    fclose($file);
                }
                
                if(count($debuger_errors)  < 1) {
                    $file = fopen('./debuger_out.txt', 'a');
                    fwrite($file, '<p style="color: green;">No errors in this session</p><br>');
                    fclose($file);      
                }

                break;
            
            case 'w':

                foreach($debuger_warnings as $warnings) {
                    $file = fopen('./debuger_out.txt', 'a');
                    fwrite($file, '<p style="color: yellow;">'.$warnings.'</p><br>');
                    fclose($file);
                }
                
                if(count($debuger_warnings)  < 1) {
                    $file = fopen('./debuger_out.txt', 'a');
                    fwrite($file, '<p style="color: green;">No warnings in this session</p><br>');
                    fclose($file);      
                }

                break;

            case 's':

                foreach($debuger as $term) {
                    $file = fopen('./debuger_out.txt', 'a');
                    fwrite($file, $term.'<br>');
                    fclose($file);
                }

                break;

            default:

                    $file = fopen('./debuger_out.txt', 'a');
                    fwrite($file, 'Debuger commands:<br>');
                    fwrite($file, 'Debuger-s: shows current settings<br>');
                    fwrite($file, 'Debuger-w: shows current warnings<br>');
                    fwrite($file, 'Debuger-e: shows current errors<br>');
                    fwrite($file, 'Debuger: shows help<br>');
                    fclose($file);
                
            break;
        }
    }

    function help() {
        global $debuger_commands;
        
        foreach($debuger_commands as $command) {
            $file = fopen('./debuger_out.txt', 'a');
            fwrite($file, $command.'<br>');
            fclose($file);
        }
    }
    
    function log_out() {
        unset($_SESSION['allow_debuger']);
        $file = fopen('./debuger_out.txt', 'a');
        fwrite($file, 'Loged Out<br>');
        fclose($file);
        sleep(1);
        clear();
    }


    function clear_errors() {
        global $debuger_errors;

        $_SESSION['debuger_errors'] = array();
    }

    function clear_warnings() {
        global $debuger_warnings;

        $_SESSION['debuger_warnings'] = array();
    }

    function test_connection() {
        
        global $test;

        $connect = $test->db_connect();

        if($connect) {
            $file = fopen('./debuger_out.txt', 'a');
            fwrite($file, '<p style="color: green;">Database connection established</p><br>');
            fclose($file);
        } else {
                $file = fopen('./debuger_out.txt', 'a');
                fwrite($file, '<p style="color: red;">Error connecting to DataBase</p><br>');
                fclose($file);    
            }
    }

    function show_plugins($method = ''){
        global $plugins_n;
        global $plugins_p;

        switch ($method) {
            case 'p':

                foreach($plugins_p as $plugin) {
                    $i = 1;

                    $file = fopen('./debuger_out.txt', 'a');
                    fwrite($file, $i.': '.$plugin.'<br>');
                    fclose($file);

                    $i++;
                }

            break;
            
            default:

                foreach($plugins_n as $plugin) {
                    $i = 1;

                    $file = fopen('./debuger_out.txt', 'a');
                    fwrite($file, $i.': '.$plugin.'<br>');
                    fclose($file);

                    $i++;
                }

            break;
        }
    }

    function sessions($method='', $param='') {

        global $code;

        switch ($method) {

            case 'd':

                if(empty($param)) {

                    $file = fopen('./debuger_out.txt', 'a');
                    fwrite($file, '<p style="color: yellow;">Password required for this action (session-d [password])</p><br>');
                    fclose($file);

                } else {
        
                    $password = hash('sha512', $param);
        
                    if($code == $password) {
        
                        $file = fopen('./debuger_out.txt', 'w');
                        fwrite($file, 'All sessions for current user are destroyed. Debuger cleared<br>.');
                        fclose($file);
                        session_destroy();    
                        clear();
        
                    } else {
        
                        $file = fopen('./debuger_out.txt', 'a');
                        fwrite($file, '<p style="color: red;">Wrong password</p><br>');
                        fclose($file);
        
                    }
                }

            break;
            
            case 's':

                $i = 0;

                foreach ($_SESSION as $key) {
                    $i++;
                }


                $file = fopen('./debuger_out.txt', 'a');
                fwrite($file, 'There are: '.$i.' active sessions<br>');
                fclose($file);

                if (empty($param)) {
                    foreach($_SESSION as $key => $value) {
    
                        $file = fopen('./debuger_out.txt', 'a');
                        fwrite($file, $key.'<br>');
                        fclose($file);
    
                    }
                } elseif($param == 'value') {

                    foreach($_SESSION as $key => $value) {
                        
                        if(is_array($value)) {
                            $file = fopen('./debuger_out.txt', 'a');
                            fwrite($file, $key.' ==> Array {');

                                foreach($value as $key) {
                                    fwrite($file, '<br>'.$key);
                                }
                            
                            fwrite($file, '<br>}<br>');

                            fclose($file);

                        } else {

                            $file = fopen('./debuger_out.txt', 'a');
                            fwrite($file, $key.' ==> '. $value.'<br>');
                            fclose($file);

                        }
                    }
                }

            break;
            
            case 'p':

                if(!empty($param)) {

                    if(isset($_SESSION[$param])) {

                        if(is_array($_SESSION[$param])) {

                            $peak = $_SESSION[$param];

                            $file = fopen('./debuger_out.txt', 'a');
                            fwrite($file, $param.' ==> Array {');

                                foreach($peak as $key) {
                                    fwrite($file, '<br>'.$key);
                                }
                            
                            fwrite($file, '<br>}<br>');

                            fclose($file);

                        } else {

                            $file = fopen('./debuger_out.txt', 'a');
                            fwrite($file, $param.' ==> '. $_SESSION[$param].'<br>');
                            fclose($file);

                        }

                    } else {

                        $file = fopen('./debuger_out.txt', 'a');
                        fwrite($file, '<p style="color: red;">You need to specify valid session name. Use sessions-s to discover active sessions</p><br>');
                        fclose($file);

                    }

                } else {

                    $file = fopen('./debuger_out.txt', 'a');
                    fwrite($file, '<p style="color: yellow;">You need to specify session name (sessions-p [name])</p><br>');
                    fclose($file);
                }


            break;

            case 'r':

                if(isset($param)) {

                    if(isset($_SESSION[$param])) {
                        unset($_SESSION[$param]);
                        $file = fopen('./debuger_out.txt', 'a');
                        fwrite($file, '<p style="color: green;">Reloaded session "'.$param.'"</p><br>');
                        fclose($file);
                    } else {
                        $file = fopen('./debuger_out.txt', 'a');
                        fwrite($file, '<p style="color: red;">You need to specify valid session name. Use sessions-s to discover active sessions</p><br>');
                        fclose($file);
                    }

                } else {
                    $file = fopen('./debuger_out.txt', 'a');
                    fwrite($file, '><p style="color: yellow;">You need to specify session name (sessions-r [name])</p><br>');
                    fclose($file);                    
                }

            break;

            case 'start':
                
                global $test;

                if(!$db = $test->db_connect()) {
                    if(!empty($param)) {

                        $param1 = explode('=', $param);

                        $name = $param1[0];

                        $_SESSION[$name] = $param1[1];

                        $file = fopen('./debuger_out.txt', 'a');
                        fwrite($file, '<p style="color: yellow;">Started new session '. $param1[0] .' => '. $param1[1] .'</p><br>');
                        fclose($file);

                    } else {

                        $file = fopen('./debuger_out.txt', 'a');
                        fwrite($file, '<p style="color: yellow;">Specify the name and the value of new session ([name]=[value])</p><br>');
                        fclose($file);

                    }

                } else {

                    $file = fopen('./debuger_out.txt', 'a');
                    fwrite($file, '<p style="color: yellow;">This command can only be used if database connection failed</p><br>');
                    fclose($file);

                }
            
            break;
            default:
                $file = fopen('./debuger_out.txt', 'a');
                fwrite($file, 'Use -d (session-d [debuger password]) to destroy all sessions<br> Use -s to show all active sessions (sessions-s)<br>Use -s value (sessions-s value) to show the content of them<br>Use -p (sessions-p [session_name]) to see content of specific session<br>Use -r (sessions-r [session name]) to reload or unset session<br>Use -start (sessions-start [name]=[value]) to start new session<br>');
                fclose($file);
            break;
        }

    }

    function change_pwd($param, $param1) {
        global $code;

        $param = hash('sha512', $param);

        if(empty($param) || empty($param1)) {

            $file = fopen('./debuger_out.txt', 'a');
            fwrite($file, 'Missing arguments (change_pwd-[current password] [new password])<br>');
            fclose($file);            

        } elseif($param == $code) {

            $new = hash('sha512', $param1);

            $file = fopen('./pwd.php', 'w');
            fwrite($file, '<?php'.PHP_EOL."defined('_DEFVAR') or exit('Restricted Access');");
            fwrite($file, PHP_EOL.'$code = "'.$new.'";'.PHP_EOL.'?>' );
            fclose($file);

            $file = fopen('./debuger_out.txt', 'a');
            fwrite($file, '<p style="color: green;">Password changed</p><br>');
            fclose($file);

        } else {

            $file = fopen('./debuger_out.txt', 'a');
            fwrite($file, '<p style="color: red;">Wrong password</p><br>');
            fclose($file);

        }

    }

    function s_console_log() {

        $file = fopen('./debuger_out.txt', 'a');
        fwrite($file, '<p style="color: green;">Debuger output saved to console log</p><br>');
        fclose($file);

        $read = file_get_contents(__PLUGINPATH__.'Debuger/src/debuger_out.txt');
        $file = fopen(__PLUGINPATH__.'Debuger/src/console_log.php', 'a');
        fwrite($file, PHP_EOL.'<hr>Debuger output save:<hr>'.PHP_EOL.'<hr>' . $read . '<hr>');
        fclose($file);

    }

?>