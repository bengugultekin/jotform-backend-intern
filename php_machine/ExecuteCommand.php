<?php
$url = "http://server_machine:80/v1/machine/1/exec";
#$url = "http://localhost:9000/v1/machine/1/exec";

$data = array('command' => 'pwd');

$options = array(
    'http' => array(
      'method'  => 'POST',
      'content' => json_encode( $data ),
      'header'=>  "Content-Type: application/json\r\n" .
                  "Accept: application/json\r\n"
      )
  );
  
  $context  = stream_context_create( $options );
  $result = file_get_contents( $url, false, $context );
  $response = json_decode( $result );

?>