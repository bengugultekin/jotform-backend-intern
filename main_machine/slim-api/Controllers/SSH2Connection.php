<?php

function SSH2Connection($container_name, $command) {
    // showing errors
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if ($connection = ssh2_connect($container_name, 22)) {
        ssh2_auth_password($connection, 'root', 'mypassword');
        $stream = ssh2_exec($connection, $command);
        stream_set_blocking($stream, true);
        $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
        echo stream_get_contents($stream_out);
    }
    else {
        echo "Failed to connect";
    }
}
?>
