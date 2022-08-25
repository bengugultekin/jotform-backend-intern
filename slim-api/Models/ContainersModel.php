<?php

class ContainersModel {

    public $id;
    public $container_name;
    public $created_at;

    protected $tableName = "containers";
    protected $primaryKey = ['id'];
}

?>