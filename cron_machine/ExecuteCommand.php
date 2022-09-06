<?php

function getListOfMachines() {
  $url = "http://server_machine/v1/machines";

  $options = array(
      'http' => array(
        'method'  => 'GET',
        'header'=>  "Content-Type: application/json\r\n" .
                    "Accept: application/json\r\n"
      )
  );

  $context  = stream_context_create( $options );
  $result = file_get_contents( $url, false, $context );
  $response = json_decode( $result );
  return $response;
}

function sendExecution($id) {
  $url = "http://server_machine/v1/machine/$id/exec";
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
    return $response;
}

function sendExecutionsToAllMachines() {
  $listOfMachines = getListOfMachines();

  // If statement to check whether there is saved machines
  if(!empty($listOfMachines)) {
    // For loop to send commands to all machines
    foreach ($listOfMachines as $machine) {
      sendExecution($machine->id);
    }
  }
  else {
    echo "There is no machine to execute command\n";
  }
}

sendExecutionsToAllMachines();

?>