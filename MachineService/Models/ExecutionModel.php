<?php

class ExecutionModel {

    public $id;
    public $container_id;
    public $exec_command;
    public $exec_response;
    public $exec_time;

    protected $tableName = "executions";
    protected $primaryKey = ['id'];
}

?>
