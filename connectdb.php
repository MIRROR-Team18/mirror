<?php

$db_host = 'localhost';
$db_name = 'mirror_mvp';
$username =' u-220176950';
$password ='ZdFZxpIysgXSifF';


try{

    $db = new PDO("mysql:dbname=$db_name;host =$db_host",$username,$password);
    echo("Successfully connected to the database.");
}catch(PDOException $ex){
    echo("Failed to connect to the database.<br>");
    echo($ex->getMessage());
    exit;
}
?>