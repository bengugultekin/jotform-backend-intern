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

function getListOfCronjobs() {
  $url = "http://server_machine/v1/cronjobs";

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

function sendExecution($id, $cronjob_command) {
  $url = "http://server_machine/v1/machine/$id/exec";
  $data = array('command' => $cronjob_command);

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
  $listOfCronjobs = getListOfCronjobs();

  // If statement to check whether there is saved machines
  if (!empty($listOfMachines) && !empty($listOfCronjobs)) {

    // For loops to send commands to proper machine by checking cronjob table
    foreach ($listOfMachines as $machine) {
      foreach ($listOfCronjobs as $cronjob) {
        if ($machine->id == $cronjob->execution_machine_id) {
          sendExecution($machine->id, $cronjob->cronjob_command);
        }
      }
    }
  }
  else {
    echo "There is no machine or cronjob to execute command\n";
  }
}

sendExecutionsToAllMachines();

?>