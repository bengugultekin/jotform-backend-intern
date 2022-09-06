<?php
require_once(__DIR__. '/../Config/Config.php');

$sql = "SELECT * FROM executions";
$db = new DB();
$conn = $db->connect();
$stmt = $conn->query($sql);
$executions = $stmt->fetchAll(PDO::FETCH_OBJ);
$db = null;

$sql = "SELECT * FROM containers";
$db = new DB();
$conn = $db->connect();
$stmt = $conn->query($sql);
$machines = $stmt->fetchAll(PDO::FETCH_OBJ);
$db = null;

$id = $args['id'];
$sql = "SELECT * FROM containers WHERE id = :id";
$db = new DB();
$conn = $db->connect();
$stmt = $conn->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();
$container = $stmt->fetchAll(PDO::FETCH_OBJ);
$container_name = $container['container_name'];
$db = null;
?>